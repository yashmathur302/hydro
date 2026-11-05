<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\SiteSettingKey;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_sends_security_headers(): void
    {
        $response = $this->get(route('home'))->assertOk();

        $csp = (string) $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringNotContainsString("'unsafe-inline'", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);

        $response
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_home_page_uses_default_seo_settings(): void
    {
        app(SiteSettingService::class)->update([
            SiteSettingKey::SiteName->value => 'Acme Pumps',
            SiteSettingKey::DefaultMetaTitle->value => 'Acme Pumps – Industrial Pumps',
            SiteSettingKey::DefaultMetaDescription->value => 'Industrial pumps for every need.',
        ]);

        $this->get(route('home'))
            ->assertSee('<title>Acme Pumps – Industrial Pumps</title>', false)
            ->assertSee('<meta name="description" content="Industrial pumps for every need.">', false)
            ->assertSee('<link rel="canonical" href="'.route('home').'">', false)
            ->assertSee('<meta property="og:site_name" content="Acme Pumps">', false);
    }

    public function test_seo_values_are_html_escaped(): void
    {
        app(SiteSettingService::class)->update([
            SiteSettingKey::DefaultMetaDescription->value => '"><script>alert(1)</script>',
        ]);

        $this->get(route('home'))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_tracking_code_is_not_rendered_without_ids(): void
    {
        $this->get(route('home'))
            ->assertDontSee('googletagmanager.com/gtag/js', false)
            ->assertDontSee('fbevents.js', false);
    }

    public function test_tracking_code_is_rendered_with_nonce_when_ids_are_set(): void
    {
        app(SiteSettingService::class)->update([
            SiteSettingKey::Ga4MeasurementId->value => 'G-TEST12345',
            SiteSettingKey::FacebookPixelId->value => '123456789012345',
        ]);

        $response = $this->get(route('home'));
        $csp = (string) $response->headers->get('Content-Security-Policy');
        preg_match("/'nonce-([^']+)'/", $csp, $matches);

        $response
            ->assertSee('https://www.googletagmanager.com/gtag/js?id=G-TEST12345', false)
            ->assertSee("gtag('config', 'G-TEST12345')", false)
            ->assertSee("fbq('init', '123456789012345')", false)
            ->assertSee('nonce="'.$matches[1].'"', false);
    }

    public function test_unknown_page_shows_friendly_404(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('Page not found');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\HomeSection;
use App\Enums\SiteSettingKey;
use App\Services\HomePageService;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_default_banner_and_about_content(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Precision Pigging &amp; Gauging Equipment', false)
            ->assertSee('Pigging Products Built To Perform In Every Pipeline')
            ->assertSee('<h1 class="hero__title" id="hero-heading">', false);
    }

    public function test_header_uses_contact_details_from_settings(): void
    {
        app(SiteSettingService::class)->update([
            SiteSettingKey::ContactEmail->value => 'sales@example.com',
            SiteSettingKey::ContactPhone->value => '+91 98765 43210',
            SiteSettingKey::LinkedinUrl->value => 'https://www.linkedin.com/company/example',
        ]);

        $this->get(route('home'))
            ->assertSee('href="mailto:sales@example.com"', false)
            ->assertSee('href="tel:+919876543210"', false)
            ->assertSee('href="https://www.linkedin.com/company/example"', false);
    }

    public function test_saved_section_content_is_escaped(): void
    {
        app(HomePageService::class)->save(HomeSection::Hero, ['heading' => '<script>alert(1)</script>'], [], []);

        $this->get(route('home'))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_menu_pages_exist_and_are_not_indexed_yet(): void
    {
        foreach (['about', 'products.index', 'certificates', 'contact'] as $routeName) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        }
    }
}

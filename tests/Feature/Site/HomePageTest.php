<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\HomeSection;
use App\Enums\SiteSettingKey;
use App\Models\Client;
use App\Models\Industry;
use App\Services\PageSectionService;
use App\Services\SiteSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_uploaded_logo_replaces_the_site_name_in_the_header(): void
    {
        Storage::fake('public');
        app(SiteSettingService::class)->replaceFile(
            SiteSettingKey::LogoPath,
            UploadedFile::fake()->image('logo.png', 1238, 848),
        );

        $this->get(route('home'))
            ->assertSee('alt="Lucky Instru Hydro"', false)
            ->assertDontSee('<span class="site-logo__name">', false);
    }

    public function test_site_name_is_shown_until_a_logo_is_uploaded(): void
    {
        $this->get(route('home'))->assertSee('<span class="site-logo__name">Lucky Instru Hydro</span>', false);
    }

    public function test_industries_and_clients_sections_show_only_active_items(): void
    {
        Industry::factory()->create(['name' => 'Oil And Gas', 'icon' => 'oil-gas']);
        Industry::factory()->create(['name' => 'Hidden Industry', 'is_active' => false]);
        Client::factory()->create(['name' => 'Acme Pipelines']);

        $this->get(route('home'))
            ->assertSee('Oil And Gas')
            ->assertDontSee('Hidden Industry')
            ->assertSee('alt="Acme Pipelines"', false)
            ->assertSee('<span class="stat-pill__value" data-count-up>500+</span>', false);
    }

    public function test_empty_industries_and_clients_sections_are_hidden(): void
    {
        $this->get(route('home'))
            ->assertDontSee('class="industries section"', false)
            ->assertDontSee('class="clients"', false);
    }

    public function test_saved_section_content_is_escaped(): void
    {
        app(PageSectionService::class)->save(HomeSection::Hero, ['heading' => '<script>alert(1)</script>'], [], []);

        $this->get(route('home'))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_menu_pages_exist_and_are_not_indexed_yet(): void
    {
        foreach (['products.index', 'contact'] as $routeName) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        }
    }
}

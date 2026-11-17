<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\HomeSection;
use App\Enums\SiteSettingKey;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Product;
use App\Models\ProductCategory;
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

    public function test_products_section_is_hidden_without_products(): void
    {
        $this->get(route('home'))->assertOk()->assertDontSee('home-products-heading');
    }

    public function test_products_section_shows_featured_products_first_and_respects_the_limit(): void
    {
        $category = ProductCategory::factory()->create();

        Product::factory()->count(Product::HOME_LIMIT)->for($category, 'category')
            ->sequence(fn ($sequence) => ['name' => 'Regular Pig '.$sequence->index, 'sort_order' => $sequence->index])
            ->create();
        Product::factory()->for($category, 'category')->create(['name' => 'Star Gauging Pig', 'is_featured' => true, 'sort_order' => 99]);
        Product::factory()->for($category, 'category')->inactive()->create(['name' => 'Hidden Pig', 'is_featured' => true]);
        Product::factory()->for(ProductCategory::factory()->create(['is_active' => false]), 'category')->create(['name' => 'Hidden Category Pig', 'is_featured' => true]);

        $html = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Pigging Equipment For Every Pipeline')
            ->assertSee('href="'.route('products.index').'"', false)
            ->assertDontSee('Hidden Pig')
            ->assertDontSee('Hidden Category Pig')
            ->getContent();

        $section = substr($html, (int) strpos($html, 'home-products-heading'));
        $section = substr($section, 0, (int) strpos($section, '</section>'));

        $this->assertSame(Product::HOME_LIMIT, substr_count($section, 'class="product-card__name"'));
        $this->assertLessThan(strpos($section, 'Regular Pig 0'), strpos($section, 'Star Gauging Pig'));
        $this->assertStringNotContainsString('Regular Pig '.(Product::HOME_LIMIT - 1), $section);
    }
}

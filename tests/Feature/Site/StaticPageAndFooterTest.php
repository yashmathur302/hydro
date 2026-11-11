<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Admin;
use App\Models\Page;
use App\Services\SiteSettingService;
use App\Support\SiteLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

final class StaticPageAndFooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_static_page_is_shown_with_safe_markdown(): void
    {
        Page::factory()->create([
            'title' => 'Shipping Policy',
            'slug' => 'shipping-policy',
            'content' => "## Delivery\n\n**Fast** <script>alert(1)</script>",
            'is_active' => true,
        ]);

        $this->get('/shipping-policy')
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">Shipping Policy</h1>', false)
            ->assertSee('<h2>Delivery</h2>', false)
            ->assertSee('<strong>Fast</strong>', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_hidden_and_unknown_pages_return_404(): void
    {
        Page::factory()->create(['slug' => 'draft-page', 'is_active' => false]);

        $this->get('/draft-page')->assertNotFound();
        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_static_pages_cannot_take_reserved_addresses(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.pages.store'), ['title' => 'Admin', 'slug' => 'admin', 'is_active' => '1'])
            ->assertSessionHasErrors('slug');

        $this->actingAs(Admin::factory()->create())
            ->post(route('admin.pages.store'), ['title' => 'Products', 'is_active' => '1']);

        $this->assertSame('products-2', Page::query()->where('title', 'Products')->value('slug'));
        $this->get('/admin')->assertRedirect();
    }

    public function test_footer_lists_active_static_pages_and_contact_details(): void
    {
        Page::factory()->create(['title' => 'Privacy Notice', 'slug' => 'privacy-notice', 'is_active' => true]);
        Page::factory()->create(['title' => 'Draft Terms', 'slug' => 'draft-terms', 'is_active' => false]);
        app(SiteSettingService::class)->update([
            'contact_address' => '12 Industrial Estate, Pune',
            'contact_email' => 'sales@example.com',
            'facebook_url' => 'https://facebook.com/hydro',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="'.route('pages.show', 'privacy-notice').'"', false)
            ->assertDontSee('Draft Terms')
            ->assertSee('12 Industrial Estate, Pune')
            ->assertSee('href="mailto:sales@example.com"', false)
            ->assertSee('Send Us Email')
            ->assertSee('href="https://facebook.com/hydro"', false)
            ->assertSee('© '.now()->year.' Lucky Instru Hydro. All rights reserved.');
    }

    public function test_site_paths_resolve_inside_a_subfolder(): void
    {
        URL::forceRootUrl('https://example.com/hydro');
        URL::forceScheme('https');

        $this->assertSame('https://example.com/hydro/products', SiteLink::href('/products'));
        $this->assertSame('https://partner.example/page', SiteLink::href('https://partner.example/page'));
        $this->assertNull(SiteLink::href(''));
        $this->assertTrue(SiteLink::isExternal('https://partner.example/page'));
        $this->assertFalse(SiteLink::isExternal('/products'));
    }
}

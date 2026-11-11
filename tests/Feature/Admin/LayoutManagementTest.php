<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\LayoutSection;
use App\Models\Admin;
use App\Services\PageSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class LayoutManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_cannot_edit_the_header_and_footer(): void
    {
        $this->get(route('admin.layout.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.layout.update'), $this->payload())->assertRedirect(route('admin.login'));
    }

    public function test_editor_shows_the_default_menu(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.layout.edit'))
            ->assertOk()
            ->assertSee('value="/about-us"', false)
            ->assertSee('value="Contact Us"', false);
    }

    public function test_admin_can_change_the_menu_and_footer(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.layout.update'), $this->payload([
                'footer_cta_background_image' => UploadedFile::fake()->image('cta.jpg', 1600, 500),
            ]))
            ->assertRedirect(route('admin.layout.edit'))
            ->assertSessionHasNoErrors();

        $menu = app(PageSectionService::class)->section(LayoutSection::Menu)['items'];
        $this->assertSame([
            ['label' => 'Start', 'url' => '/'],
            ['label' => 'Our Story', 'url' => '/about-us'],
            ['label' => 'Partner', 'url' => 'https://example.com'],
        ], $menu);
        $this->assertStringStartsWith('layout/', (string) app(PageSectionService::class)->section(LayoutSection::FooterCta)['background_image']);

        $response = $this->get(route('certificates'))->assertOk();
        $this->assertMatchesRegularExpression('/href="https:\/\/example\.com"\s+target="_blank" rel="noopener"\s*>Partner</', $response->getContent());

        $response
            ->assertSee('>Our Story</a>', false)
            ->assertDontSee('>Certificates</a>', false)
            ->assertSee('Talk To Our Engineers')
            ->assertSee('Footer about text')
            ->assertSee('Copyright '.now()->year.' Hydro');
    }

    public function test_unsafe_links_and_an_empty_menu_are_rejected(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.layout.update'), $this->payload([
                'menu' => ['items' => [['label' => 'Bad', 'url' => 'javascript:alert(1)'], ['label' => 'Also bad', 'url' => '//evil.example']]],
            ]))
            ->assertSessionHasErrors(['menu.items.0.url', 'menu.items.1.url']);

        $this->actingAs($admin)
            ->put(route('admin.layout.update'), $this->payload([
                'menu' => ['items' => [['label' => '', 'url' => '']]],
            ]))
            ->assertSessionHasErrors('menu.items');

        $this->assertDatabaseMissing('page_sections', ['page' => 'layout']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'menu' => ['items' => [
                ['label' => 'Start', 'url' => '/'],
                ['label' => '', 'url' => ''],
                ['label' => 'Our Story', 'url' => '/about-us'],
                ['label' => 'Partner', 'url' => 'https://example.com'],
            ]],
            'footer_cta' => ['eyebrow' => 'Ask us', 'heading' => 'Talk To Our Engineers', 'button_label' => 'Contact', 'button_url' => '/contact-us'],
            'footer' => [
                'description' => 'Footer about text',
                'menu_heading' => 'Menu Links',
                'links_heading' => 'Useful Links',
                'contact_heading' => 'Contact Us',
                'copyright' => 'Copyright {year} Hydro',
            ],
        ], $overrides);
    }
}

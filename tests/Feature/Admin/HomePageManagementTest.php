<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\HomeSection;
use App\Models\Admin;
use App\Services\HomePageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class HomePageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_cannot_edit_the_home_page(): void
    {
        $this->get(route('admin.home-page.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.home-page.update'), $this->payload())->assertRedirect(route('admin.login'));
    }

    public function test_editor_renders_with_defaults(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.home-page.edit'))
            ->assertOk()
            ->assertSee('value="Precision Pigging &amp; Gauging Equipment"', false);
    }

    public function test_admin_can_save_text_and_images(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.home-page.update'), $this->payload([
                'hero_background_image' => UploadedFile::fake()->image('banner.jpg', 2400, 1100),
                'about_image_main' => UploadedFile::fake()->image('main.png', 620, 740),
            ]))
            ->assertRedirect(route('admin.home-page.edit'))
            ->assertSessionHasNoErrors();

        $service = app(HomePageService::class);
        $hero = $service->section(HomeSection::Hero);
        $about = $service->section(HomeSection::About);

        $this->assertSame('New banner heading', $hero['heading']);
        $this->assertSame(['First point', 'Second point'], $about['points']);
        $this->assertStringEndsWith('.webp', $hero['background_image']);
        $this->assertSame(1920, getimagesizefromstring(Storage::disk('public')->get($hero['background_image']))[0]);

        $this->get(route('home'))->assertSee('New banner heading')->assertSee($hero['background_image_url']);
    }

    public function test_replacing_and_removing_images_deletes_old_files(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->put(route('admin.home-page.update'), $this->payload([
            'about_image_top' => UploadedFile::fake()->image('first.jpg'),
        ]));
        $first = app(HomePageService::class)->section(HomeSection::About)['image_top'];

        $this->actingAs($admin)->put(route('admin.home-page.update'), $this->payload([
            'remove_about_image_top' => '1',
        ]));

        Storage::disk('public')->assertMissing($first);
        $this->assertNull(app(HomePageService::class)->section(HomeSection::About)['image_top_url']);
    }

    public function test_javascript_links_and_fake_images_are_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.home-page.update'), $this->payload([
                'hero' => ['heading' => 'Banner', 'button_url' => 'javascript:alert(1)'],
                'about_image_main' => UploadedFile::fake()->createWithContent('evil.jpg', '<?php echo 1; ?>'),
            ]))
            ->assertSessionHasErrors(['hero.button_url', 'about_image_main']);
    }

    public function test_heading_is_required(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.home-page.update'), $this->payload(['hero' => ['heading' => '']]))
            ->assertSessionHasErrors('hero.heading');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'hero' => ['heading' => 'New banner heading', 'button_label' => 'Products', 'button_url' => '/products'],
            'about' => ['heading' => 'About heading', 'points' => ['First point', '', 'Second point']],
        ], $overrides);
    }
}

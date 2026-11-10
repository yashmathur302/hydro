<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\AboutSection;
use App\Models\Admin;
use App\Services\PageSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class AboutPageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guests_cannot_edit_the_about_page(): void
    {
        $this->get(route('admin.about-page.edit'))->assertRedirect(route('admin.login'));
        $this->put(route('admin.about-page.update'), $this->payload())->assertRedirect(route('admin.login'));
    }

    public function test_editor_renders_with_defaults(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->get(route('admin.about-page.edit'))
            ->assertOk()
            ->assertSee('value="About Us"', false)
            ->assertSee('value="Pipeline Pigging Experts"', false);
    }

    public function test_admin_can_save_the_banner_text_and_photo(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.about-page.update'), $this->payload([
                'banner_background_image' => UploadedFile::fake()->image('banner.jpg', 2400, 700),
            ]))
            ->assertRedirect(route('admin.about-page.edit'))
            ->assertSessionHasNoErrors();

        $banner = app(PageSectionService::class)->section(AboutSection::Banner);

        $this->assertSame('Who We Are', $banner['title']);
        $this->assertSame('Since 1999', $banner['eyebrow']);
        $this->assertStringStartsWith('about/', $banner['background_image']);
        $this->assertStringEndsWith('.webp', $banner['background_image']);
        $this->assertSame(1920, getimagesizefromstring(Storage::disk('public')->get($banner['background_image']))[0]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">Who We Are</h1>', false)
            ->assertSee('Since 1999')
            ->assertSee($banner['background_image_url']);
    }

    public function test_removing_the_photo_deletes_the_file(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin)->put(route('admin.about-page.update'), $this->payload([
            'banner_background_image' => UploadedFile::fake()->image('banner.jpg', 1920, 600),
        ]));
        $path = app(PageSectionService::class)->section(AboutSection::Banner)['background_image'];

        $this->actingAs($admin)->put(route('admin.about-page.update'), $this->payload([
            'remove_banner_background_image' => '1',
        ]));

        Storage::disk('public')->assertMissing($path);
        $this->assertNull(app(PageSectionService::class)->section(AboutSection::Banner)['background_image_url']);
        $this->get(route('about'))->assertSee('page-banner--no-image', false);
    }

    public function test_title_is_required_and_fake_images_are_rejected(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->from(route('admin.about-page.edit'))
            ->put(route('admin.about-page.update'), $this->payload([
                'banner' => ['eyebrow' => '', 'title' => ''],
                'banner_background_image' => UploadedFile::fake()->create('banner.jpg', 10, 'application/pdf'),
            ]))
            ->assertRedirect(route('admin.about-page.edit'))
            ->assertSessionHasErrors(['banner.title', 'banner_background_image']);
    }

    public function test_saving_the_about_page_leaves_the_home_page_untouched(): void
    {
        $this->actingAs(Admin::factory()->create())->put(route('admin.about-page.update'), $this->payload());

        $this->assertDatabaseHas('page_sections', ['page' => 'about', 'section' => 'banner']);
        $this->assertDatabaseMissing('page_sections', ['page' => 'home']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'banner' => ['eyebrow' => 'Since 1999', 'title' => 'Who We Are'],
        ], $overrides);
    }
}

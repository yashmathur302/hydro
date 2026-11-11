<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Enums\ProductsPageSection;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Images\PlainBackgroundDetector;
use App\Services\PageSectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProductsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_lists_active_products_by_category_order(): void
    {
        $foam = ProductCategory::factory()->create(['sort_order' => 2]);
        $mandrel = ProductCategory::factory()->create(['sort_order' => 1]);
        $hiddenCategory = ProductCategory::factory()->create(['is_active' => false]);

        Product::factory()->for($foam, 'category')->create(['name' => 'Foam Pig', 'sort_order' => 1]);
        Product::factory()->for($mandrel, 'category')->create(['name' => 'Gauging Pig', 'sort_order' => 2]);
        Product::factory()->for($mandrel, 'category')->create(['name' => 'Brushing Pig', 'sort_order' => 1]);
        Product::factory()->for($mandrel, 'category')->create(['name' => 'Hidden Pig', 'is_active' => false]);
        Product::factory()->for($hiddenCategory, 'category')->create(['name' => 'Orphan Pig']);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('<h1 class="page-banner__title" id="page-title">Products</h1>', false)
            ->assertSee('Our Product Lines')
            ->assertSeeInOrder(['Brushing Pig', 'Gauging Pig', 'Foam Pig'])
            ->assertDontSee('Hidden Pig')
            ->assertDontSee('Orphan Pig')
            ->assertDontSee('noindex', false);
    }

    public function test_empty_catalogue_shows_a_message(): void
    {
        $this->get(route('products.index'))->assertOk()->assertSee('Our product catalogue will be published here soon.');
    }

    public function test_admin_can_edit_the_products_page_texts(): void
    {
        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.products-page.update'), [
                'banner' => ['eyebrow' => 'Pigging', 'title' => 'Our Products'],
                'intro' => ['heading' => 'Pigs For Every Line', 'text' => '<b>Intro</b> text'],
            ])
            ->assertRedirect(route('admin.products-page.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame('Pigs For Every Line', app(PageSectionService::class)->section(ProductsPageSection::Intro)['heading']);

        $this->get(route('products.index'))
            ->assertSee('Our Products')
            ->assertSee('Pigs For Every Line')
            ->assertSee('&lt;b&gt;Intro&lt;/b&gt; text', false);
    }

    public function test_products_page_editor_requires_login_and_a_heading(): void
    {
        $this->get(route('admin.products-page.edit'))->assertRedirect(route('admin.login'));

        $this->actingAs(Admin::factory()->create())
            ->put(route('admin.products-page.update'), ['banner' => ['title' => 'Products'], 'intro' => ['heading' => '']])
            ->assertSessionHasErrors('intro.heading');
    }

    public function test_white_background_photos_are_detected_as_cut_outs(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('white.png', $this->solidImage(40, 40, [255, 255, 255]));
        Storage::disk('public')->put('photo.jpg', $this->solidImage(40, 40, [90, 90, 90]));

        $detector = app(PlainBackgroundDetector::class);

        $this->assertTrue($detector->hasPlainBackground('white.png'));
        $this->assertFalse($detector->hasPlainBackground('photo.jpg'));
    }

    /**
     * @param  array{int, int, int}  $rgb
     */
    private function solidImage(int $width, int $height, array $rgb): string
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, ...$rgb));
        ob_start();
        imagejpeg($image);

        return (string) ob_get_clean();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProductCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(Admin::factory()->create());
    }

    public function test_category_pages_render(): void
    {
        $category = ProductCategory::factory()->create();

        $this->get(route('admin.categories.index'))->assertOk()->assertSee($category->name);
        $this->get(route('admin.categories.create'))->assertOk();
        $this->get(route('admin.categories.edit', $category))->assertOk();
    }

    public function test_category_is_created_with_a_generated_slug_and_webp_image(): void
    {
        $this->post(route('admin.categories.store'), [
            'name' => 'Pressure Gauges',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('gauge.jpg', 2400, 1200),
        ])->assertRedirect(route('admin.categories.index'));

        $category = ProductCategory::query()->sole();

        $this->assertSame('pressure-gauges', $category->slug);
        $this->assertStringEndsWith('.webp', $category->image_path);
        Storage::disk('public')->assertExists($category->image_path);
        $this->assertSame(1600, getimagesizefromstring(Storage::disk('public')->get($category->image_path))[0]);
    }

    public function test_duplicate_names_get_unique_slugs(): void
    {
        ProductCategory::factory()->create(['name' => 'Valves']);

        $this->post(route('admin.categories.store'), ['name' => 'Valves']);

        $this->assertDatabaseHas('product_categories', ['slug' => 'valves-2']);
    }

    public function test_invalid_slug_is_rejected(): void
    {
        $this->post(route('admin.categories.store'), ['name' => 'Valves', 'slug' => '../Bad Slug'])
            ->assertSessionHasErrors('slug');
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->for($category, 'category')->create();

        $this->delete(route('admin.categories.destroy', $category))->assertSessionHas('error');

        $this->assertModelExists($category);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $category = ProductCategory::factory()->create();

        $this->delete(route('admin.categories.destroy', $category))->assertRedirect(route('admin.categories.index'));

        $this->assertModelMissing($category);
    }
}

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

final class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private ProductCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->category = ProductCategory::factory()->create();
        $this->actingAs(Admin::factory()->create());
    }

    public function test_guests_cannot_reach_product_management(): void
    {
        auth()->logout();

        $this->get(route('admin.products.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.products.store'), $this->payload())->assertRedirect(route('admin.login'));
        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_list_renders_with_filters(): void
    {
        Product::factory()->for($this->category, 'category')->create(['name' => 'Brass Ball Valve']);
        Product::factory()->for($this->category, 'category')->create(['name' => 'Digital Gauge']);

        $this->get(route('admin.products.index', ['q' => 'valve']))
            ->assertOk()
            ->assertSee('Brass Ball Valve')
            ->assertDontSee('Digital Gauge');
    }

    public function test_search_wildcards_are_treated_as_text(): void
    {
        Product::factory()->for($this->category, 'category')->create(['name' => 'Digital Gauge']);

        $this->get(route('admin.products.index', ['q' => '%']))->assertOk()->assertDontSee('Digital Gauge');
    }

    public function test_product_is_created_with_images_specs_and_brochure(): void
    {
        $this->post(route('admin.products.store'), $this->payload([
            'images' => [UploadedFile::fake()->image('a.jpg', 2000, 1500), UploadedFile::fake()->image('b.png', 800, 600)],
            'brochure' => UploadedFile::fake()->createWithContent('brochure.pdf', "%PDF-1.4\n%test\n"),
            'specifications' => [
                ['label' => 'Material', 'value' => 'SS 316'],
                ['label' => '', 'value' => ''],
                ['label' => 'Size', 'value' => '1/2 inch'],
            ],
        ]))->assertSessionHasNoErrors();

        $product = Product::query()->with(['images', 'specifications'])->sole();

        $this->assertSame('industrial-ball-valve', $product->slug);
        $this->assertTrue($product->is_active);
        $this->assertCount(2, $product->images);
        $this->assertSame(['Material', 'Size'], $product->specifications->pluck('label')->all());
        $this->assertStringStartsWith('brochures/', $product->brochure_path);

        foreach ($product->images as $image) {
            $this->assertStringEndsWith('.webp', $image->path);
            Storage::disk('public')->assertExists([$image->path, $image->thumbnail_path]);
        }

        $this->assertSame(600, getimagesizefromstring(Storage::disk('public')->get($product->images[0]->thumbnail_path))[0]);
    }

    public function test_unchecked_toggles_save_as_false(): void
    {
        $this->post(route('admin.products.store'), $this->payload(['is_active' => '0', 'is_featured' => '0']));

        $this->assertFalse(Product::query()->sole()->is_active);
    }

    public function test_fake_images_and_non_pdf_brochures_are_rejected(): void
    {
        $this->post(route('admin.products.store'), $this->payload([
            'images' => [UploadedFile::fake()->createWithContent('shell.jpg', '<?php system($_GET["c"]); ?>')],
            'brochure' => UploadedFile::fake()->createWithContent('brochure.pdf', '<script>alert(1)</script>'),
        ]))->assertSessionHasErrors(['images.0', 'brochure']);

        $this->assertDatabaseCount('products', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_unknown_category_is_rejected(): void
    {
        $this->post(route('admin.products.store'), $this->payload(['product_category_id' => 999]))
            ->assertSessionHasErrors('product_category_id');
    }

    public function test_spec_value_without_label_is_rejected(): void
    {
        $this->post(route('admin.products.store'), $this->payload([
            'specifications' => [['label' => '', 'value' => 'Orphan value']],
        ]))->assertSessionHasErrors('specifications.0.label');
    }

    public function test_images_can_be_reordered_and_removed(): void
    {
        $product = $this->createProductWithImages(2);
        [$first, $second] = $product->images->all();

        $this->put(route('admin.products.update', $product), $this->payload([
            'existing_images' => [
                $first->id => ['alt_text' => 'Front view', 'sort_order' => 5, 'remove' => '1'],
                $second->id => ['alt_text' => 'Side view', 'sort_order' => 0],
            ],
        ]))->assertSessionHasNoErrors();

        $this->assertModelMissing($first);
        Storage::disk('public')->assertMissing([$first->path, $first->thumbnail_path]);
        $this->assertSame('Side view', $second->fresh()->alt_text);
    }

    public function test_images_of_another_product_cannot_be_touched(): void
    {
        $victim = $this->createProductWithImages(1);
        $victimImage = $victim->images->first();
        $attacker = Product::factory()->for($this->category, 'category')->create();

        $this->put(route('admin.products.update', $attacker), $this->payload([
            'existing_images' => [$victimImage->id => ['remove' => '1', 'alt_text' => 'hacked']],
        ]));

        $this->assertModelExists($victimImage);
        $this->assertNotSame('hacked', $victimImage->fresh()->alt_text);
        Storage::disk('public')->assertExists($victimImage->path);
    }

    public function test_gallery_size_is_limited(): void
    {
        $product = $this->createProductWithImages(12);

        $this->put(route('admin.products.update', $product), $this->payload([
            'images' => [UploadedFile::fake()->image('extra.jpg')],
        ]))->assertSessionHasErrors('images');
    }

    public function test_deleting_a_product_removes_its_files_but_keeps_enquiries(): void
    {
        $product = $this->createProductWithImages(1);
        $image = $product->images->first();
        $enquiry = $product->enquiries()->forceCreate([
            'type' => 'product', 'status' => 'new', 'product_name' => $product->name, 'name' => 'Ravi', 'phone' => '9876543210',
        ]);

        $this->delete(route('admin.products.destroy', $product))->assertRedirect(route('admin.products.index'));

        $this->assertModelMissing($product);
        Storage::disk('public')->assertMissing($image->path);
        $this->assertNull($enquiry->fresh()->product_id);
        $this->assertSame($product->name, $enquiry->fresh()->product_name);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'product_category_id' => $this->category->id,
            'name' => 'Industrial Ball Valve',
            'short_description' => 'A sturdy valve.',
            'description' => "## Features\n\n- Leak proof",
            'is_active' => '1',
            'is_featured' => '0',
            'sort_order' => '0',
            ...$overrides,
        ];
    }

    private function createProductWithImages(int $count): Product
    {
        $product = Product::factory()->for($this->category, 'category')->create();

        for ($position = 0; $position < $count; $position++) {
            $path = "products/test-{$product->id}-{$position}.webp";
            Storage::disk('public')->put($path, 'image');
            $product->images()->create(['path' => $path, 'thumbnail_path' => $path, 'sort_order' => $position]);
        }

        return $product->load('images');
    }
}

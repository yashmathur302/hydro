<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProductCategoryRequest;
use App\Models\ProductCategory;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class ProductCategoryController extends Controller
{
    private const IMAGE_DIRECTORY = 'categories';

    public function __construct(private readonly WebpImageProcessor $images) {}

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => ProductCategory::query()->withCount('products')->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new ProductCategory(['is_active' => true])]);
    }

    public function store(SaveProductCategoryRequest $request): RedirectResponse
    {
        $category = new ProductCategory($request->categoryAttributes());

        if ($image = $request->file('image')) {
            $category->image_path = $this->images->storeSingle($image, self::IMAGE_DIRECTORY);
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->name}\" created.");
    }

    public function edit(ProductCategory $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(SaveProductCategoryRequest $request, ProductCategory $category): RedirectResponse
    {
        $previousImage = $category->image_path;
        $category->fill($request->categoryAttributes());

        if ($image = $request->file('image')) {
            $category->image_path = $this->images->storeSingle($image, self::IMAGE_DIRECTORY);
        } elseif ($request->boolean('remove_image')) {
            $category->image_path = null;
        }

        $category->save();

        if ($previousImage !== null && $previousImage !== $category->image_path) {
            $this->disk()->delete($previousImage);
        }

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->name}\" updated.");
    }

    /**
     * A category that still has products is never deleted, so no product is left without one.
     */
    public function destroy(ProductCategory $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', "\"{$category->name}\" still has products. Move or delete them first.");
        }

        $category->delete();

        if ($category->image_path !== null) {
            $this->disk()->delete($category->image_path);
        }

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->name}\" deleted.");
    }

    private function disk(): Filesystem
    {
        return Storage::disk('public');
    }
}

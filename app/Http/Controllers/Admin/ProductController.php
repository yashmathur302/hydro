<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductService;
use App\Support\SearchTerm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

final class ProductController extends Controller
{
    private const PER_PAGE = 15;

    public function __construct(private readonly ProductService $products) {}

    public function index(Request $request): View
    {
        $search = SearchTerm::normalise($request->query('q'));
        $categoryId = $request->integer('category') ?: null;
        $status = in_array($request->query('status'), ['active', 'inactive', 'featured'], true) ? $request->query('status') : null;

        $products = Product::query()
            ->with(['category:id,name', 'primaryImage'])
            ->when($search, static fn (Builder $query, string $term) => $query->where(
                static fn (Builder $inner) => $inner->where('name', 'like', SearchTerm::contains($term))
                    ->orWhere('slug', 'like', SearchTerm::contains($term)),
            ))
            ->when($categoryId, static fn (Builder $query, int $id) => $query->where('product_category_id', $id))
            ->when($status === 'active', static fn (Builder $query) => $query->where('is_active', true))
            ->when($status === 'inactive', static fn (Builder $query) => $query->where('is_active', false))
            ->when($status === 'featured', static fn (Builder $query) => $query->where('is_featured', true))
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $this->categoryOptions(),
            'filters' => ['q' => $search, 'category' => $categoryId, 'status' => $status],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true]),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(SaveProductRequest $request): RedirectResponse
    {
        $product = $this->products->create(
            $request->productAttributes(),
            $request->specificationRows(),
            $request->newImages(),
            $request->file('brochure'),
        );

        return redirect()->route('admin.products.edit', $product)->with('status', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'specifications']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function update(SaveProductRequest $request, Product $product): RedirectResponse
    {
        $this->products->update(
            $product,
            $request->productAttributes(),
            $request->specificationRows(),
            $request->existingImageChanges(),
            $request->newImages(),
            $request->file('brochure'),
            $request->boolean('remove_brochure'),
        );

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product saved.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->products->delete($product);

        return redirect()->route('admin.products.index')->with('status', "Product \"{$product->name}\" deleted.");
    }

    /**
     * @return Collection<int, string> category names keyed by id
     */
    private function categoryOptions(): Collection
    {
        return ProductCategory::query()->ordered()->pluck('name', 'id');
    }
}

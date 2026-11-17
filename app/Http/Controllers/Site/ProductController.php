<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\ProductsPageSection;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class ProductController extends Controller
{
    /**
     * Products page: banner, heading and every active product of an active
     * category, ordered by category and then by product sort order.
     */
    public function index(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $banner = $pageSections->section(ProductsPageSection::Banner);

        $products = Product::query()
            ->active()
            ->whereHas('category', static fn ($query) => $query->active())
            ->with('primaryImage')
            ->orderBy(
                ProductCategory::query()
                    ->select('sort_order')
                    ->whereColumn('product_categories.id', 'products.product_category_id'),
            )
            ->ordered()
            ->get();

        return view('site.products.index', [
            'seo' => $seo->make(title: $banner['title'], imageUrl: $banner['background_image_url']),
            'banner' => $banner,
            'intro' => $pageSections->section(ProductsPageSection::Intro),
            'products' => $products,
        ]);
    }
}

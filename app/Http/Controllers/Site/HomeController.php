<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\HomeSection;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Product;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $hero = $pageSections->section(HomeSection::Hero);

        return view('site.home', [
            'seo' => $seo->make(imageUrl: $hero['background_image_url']),
            'hero' => $hero,
            'about' => $pageSections->section(HomeSection::About),
            'productsSection' => $pageSections->section(HomeSection::Products),
            'products' => Product::query()
                ->active()
                ->whereHas('category', static fn ($query) => $query->active())
                ->with('primaryImage')
                ->orderByDesc('is_featured')
                ->ordered()
                ->limit(Product::HOME_LIMIT)
                ->get(),
            'industriesSection' => $pageSections->section(HomeSection::Industries),
            'industries' => Industry::query()->active()->ordered()->get(),
            'stats' => $pageSections->section(HomeSection::Stats),
            'whyUs' => $pageSections->section(HomeSection::WhyUs),
            'clients' => Client::query()->active()->ordered()->get(),
            'certificatesSection' => $pageSections->section(HomeSection::Certificates),
            'certificates' => Certificate::query()->active()->ordered()->limit(Certificate::HOME_LIMIT)->get(),
        ]);
    }
}

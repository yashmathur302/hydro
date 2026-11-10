<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\AboutSection;
use App\Enums\HomeSection;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

/**
 * About Us page: its own banner, then the home page's About, Why choose us,
 * Clients and Counters sections with the same content (edited once, in the
 * home page editor).
 */
final class AboutController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $banner = $pageSections->section(AboutSection::Banner);

        return view('site.about', [
            'seo' => $seo->make(title: $banner['title'], imageUrl: $banner['background_image_url']),
            'banner' => $banner,
            'about' => $pageSections->section(HomeSection::About),
            'whyUs' => $pageSections->section(HomeSection::WhyUs),
            'clients' => Client::query()->active()->ordered()->get(),
            'stats' => $pageSections->section(HomeSection::Stats),
        ]);
    }
}

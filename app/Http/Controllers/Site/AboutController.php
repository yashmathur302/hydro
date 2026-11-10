<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\AboutSection;
use App\Http\Controllers\Controller;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

final class AboutController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $banner = $pageSections->section(AboutSection::Banner);

        return view('site.about', [
            // Kept out of search results until the rest of the page design is built.
            'seo' => $seo->make(title: $banner['title'], imageUrl: $banner['background_image_url'], indexable: false),
            'banner' => $banner,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\ContactPageSection;
use App\Http\Controllers\Controller;
use App\Services\PageSectionService;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

/**
 * Contact Us page: banner, contact cards, form with text and photo, map.
 * The form posts to ContactMessageController.
 */
final class ContactController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, PageSectionService $pageSections): View
    {
        $banner = $pageSections->section(ContactPageSection::Banner);

        return view('site.contact', [
            'seo' => $seo->make(title: $banner['title'], imageUrl: $banner['background_image_url']),
            'banner' => $banner,
            'cards' => $pageSections->section(ContactPageSection::Cards),
            'form' => $pageSections->section(ContactPageSection::Form),
            'map' => $pageSections->section(ContactPageSection::Map),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

/**
 * Temporary "coming soon" page for menu items whose design is not built yet.
 * Not indexed by search engines. Each route is replaced by its real controller.
 */
final class PlaceholderPageController extends Controller
{
    public function __invoke(SeoMetaFactory $seo, string $title): View
    {
        return view('site.placeholder', [
            'title' => $title,
            'seo' => $seo->make(title: $title, indexable: false),
        ]);
    }
}

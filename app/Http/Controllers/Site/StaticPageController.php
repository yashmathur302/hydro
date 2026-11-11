<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Content\MarkdownRenderer;
use App\Support\Seo\SeoMetaFactory;
use Illuminate\View\View;

/**
 * Static pages from Admin > Static Pages (privacy policy, terms, ...) at
 * /{slug}. Hidden pages return 404.
 */
final class StaticPageController extends Controller
{
    public function __invoke(string $slug, SeoMetaFactory $seo, MarkdownRenderer $markdown): View
    {
        $page = Page::query()->active()->where('slug', $slug)->firstOrFail();

        return view('site.static-page', [
            'page' => $page,
            'content' => $markdown->render($page->content),
            'seo' => $seo->make(
                title: $page->meta_title ?: $page->title,
                description: $page->meta_description,
                keywords: $page->meta_keywords,
                type: 'article',
            ),
        ]);
    }
}

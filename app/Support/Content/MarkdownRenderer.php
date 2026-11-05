<?php

declare(strict_types=1);

namespace App\Support\Content;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Turns admin-written Markdown into HTML for the website.
 * Raw HTML in the source is escaped and javascript:/data: links are dropped,
 * so content can never inject scripts into a page.
 */
final class MarkdownRenderer
{
    private const OPTIONS = [
        'html_input' => 'escape',
        'allow_unsafe_links' => false,
        'max_nesting_level' => 20,
    ];

    public function render(?string $markdown): HtmlString
    {
        if ($markdown === null || trim($markdown) === '') {
            return new HtmlString('');
        }

        return new HtmlString(Str::markdown($markdown, self::OPTIONS));
    }
}

<?php

declare(strict_types=1);

namespace App\Support\Seo;

/**
 * Everything the page <head> needs for search engines and social sharing.
 * Build instances through SeoMetaFactory so site defaults are applied.
 */
final readonly class SeoMeta
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $keywords,
        public string $canonicalUrl,
        public ?string $imageUrl,
        public string $siteName,
        public string $type = 'website',
        public bool $indexable = true,
    ) {}
}

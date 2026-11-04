<?php

declare(strict_types=1);

namespace App\Support\Seo;

use App\Enums\SiteSettingKey;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Builds SeoMeta for a page, falling back to the default SEO values set in
 * the admin panel for anything the page does not provide.
 */
final class SeoMetaFactory
{
    private const TITLE_SEPARATOR = ' | ';

    private const DESCRIPTION_MAX_LENGTH = 160;

    public function __construct(
        private readonly SiteSettingService $settings,
        private readonly Request $request,
    ) {}

    public function make(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $imageUrl = null,
        string $type = 'website',
        ?string $canonicalUrl = null,
        bool $indexable = true,
    ): SeoMeta {
        $siteName = $this->settings->get(SiteSettingKey::SiteName, (string) config('app.name'));

        return new SeoMeta(
            title: $this->buildTitle($title, $siteName),
            description: $this->buildDescription($description),
            keywords: $this->clean($keywords) ?? $this->settings->get(SiteSettingKey::DefaultMetaKeywords),
            canonicalUrl: $canonicalUrl ?? $this->request->url(),
            imageUrl: $imageUrl ?? $this->settings->fileUrl(SiteSettingKey::LogoPath),
            siteName: $siteName,
            type: $type,
            indexable: $indexable,
        );
    }

    private function buildTitle(?string $title, string $siteName): string
    {
        $title = $this->clean($title);

        if ($title === null) {
            return $this->settings->get(SiteSettingKey::DefaultMetaTitle, $siteName);
        }

        return $title.self::TITLE_SEPARATOR.$siteName;
    }

    private function buildDescription(?string $description): ?string
    {
        $description = $this->clean($description)
            ?? $this->settings->get(SiteSettingKey::DefaultMetaDescription);

        return $description === null
            ? null
            : Str::limit($description, self::DESCRIPTION_MAX_LENGTH, '…', preserveWords: true);
    }

    /**
     * Strip tags and collapse whitespace so rich text can be reused as meta text.
     */
    private function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = Str::squish(strip_tags($value));

        return $value === '' ? null : $value;
    }
}

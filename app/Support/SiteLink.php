<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Links typed in the admin panel: a page path on this site ("/products"), a
 * full http(s) address, or "#" as a placeholder until the real link is known. Paths are resolved against the site's base URL, so
 * they keep working when the site runs in a subfolder (e.g. /hydro).
 */
final class SiteLink
{
    /**
     * Placeholder link: stays on the current page and never opens a new tab.
     */
    public const PLACEHOLDER = '#';

    /**
     * Allowed link formats: "#" or "#section", a site path, or an http(s)
     * address. Blocks javascript:, data: and protocol-relative ("//host") links.
     */
    public const PATTERN = '/^(#[^\s]*|\/(?!\/)[^\s]*|https?:\/\/[^\s]+)$/';

    /**
     * Validation for fields that take a full web address (social profiles,
     * client websites) and also accept the "#" placeholder.
     *
     * @return list<mixed>
     */
    public static function webAddressRules(mixed $value): array
    {
        return [
            'nullable', 'string', 'max:255',
            ...($value === self::PLACEHOLDER ? [] : ['url:http,https']),
        ];
    }

    public static function isPlaceholder(?string $link): bool
    {
        return $link !== null && str_starts_with($link, '#');
    }

    public static function href(?string $link): ?string
    {
        if ($link === null || trim($link) === '') {
            return null;
        }

        return str_starts_with($link, '/') ? url($link) : $link;
    }

    public static function isExternal(?string $link): bool
    {
        return $link !== null && preg_match('/^https?:\/\//i', $link) === 1
            && ! str_starts_with($link, rtrim(url('/'), '/'));
    }

    /**
     * Whether a site path is the page being viewed (or a page below it, so
     * "/products" stays highlighted on a product page).
     */
    public static function isCurrent(?string $link): bool
    {
        if ($link === null || ! str_starts_with($link, '/')) {
            return false;
        }

        $target = trim((string) parse_url($link, PHP_URL_PATH), '/');
        $current = trim(request()->path(), '/');

        if ($target === '') {
            return $current === '';
        }

        return $current === $target || str_starts_with($current, $target.'/');
    }
}

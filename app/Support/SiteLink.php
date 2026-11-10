<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Links typed in the admin panel: a page path on this site ("/products") or a
 * full http(s) address. Paths are resolved against the site's base URL, so
 * they keep working when the site runs in a subfolder (e.g. /hydro).
 */
final class SiteLink
{
    /**
     * Allowed link formats. Blocks javascript:, data: and protocol-relative
     * ("//host") links.
     */
    public const PATTERN = '/^(\/(?!\/)[^\s]*|https?:\/\/[^\s]+)$/';

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

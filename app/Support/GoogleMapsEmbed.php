<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Google Maps "Embed a map" links. The admin may paste either the src URL or
 * the whole <iframe> code Google gives; only the URL is kept, and only Google
 * Maps embed addresses are accepted.
 */
final class GoogleMapsEmbed
{
    private const PATTERN = '~^https://(www\.)?google\.[a-z.]{2,10}/maps/embed\?[^\s"\'<>]+$|^https://maps\.google\.[a-z.]{2,10}/maps\?[^\s"\'<>]*output=embed[^\s"\'<>]*$~i';

    /**
     * The embed URL from pasted text: the src of an <iframe>, or the text itself.
     */
    public static function extract(?string $input): ?string
    {
        $input = trim((string) $input);

        if ($input === '') {
            return null;
        }

        if (preg_match('/<iframe[^>]*\ssrc\s*=\s*(["\'])(.*?)\1/is', $input, $match) === 1) {
            $input = $match[2];
        }

        return html_entity_decode(trim($input), ENT_QUOTES | ENT_HTML5);
    }

    public static function isValid(?string $url): bool
    {
        return $url !== null && strlen($url) <= 2000 && preg_match(self::PATTERN, $url) === 1;
    }
}

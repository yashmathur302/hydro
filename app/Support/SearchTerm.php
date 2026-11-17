<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Turns user search input into a safe LIKE pattern. The value is still bound
 * as a query parameter; this only stops % and _ from acting as wildcards.
 */
final class SearchTerm
{
    private const MAX_LENGTH = 100;

    public static function normalise(?string $input): ?string
    {
        $term = Str::squish((string) $input);

        return $term === '' ? null : mb_substr($term, 0, self::MAX_LENGTH);
    }

    public static function contains(string $term): string
    {
        return '%'.addcslashes($term, '\\%_').'%';
    }
}

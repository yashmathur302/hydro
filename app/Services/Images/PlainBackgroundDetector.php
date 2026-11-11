<?php

declare(strict_types=1);

namespace App\Services\Images;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Tells whether a stored image is a "cut-out" product shot on a plain white
 * background (all four corners near-white). Such images are shown whole and
 * blended into the card background instead of being cropped to fill it.
 * The answer never changes for a stored file, so it is cached per path.
 */
final class PlainBackgroundDetector
{
    private const CACHE_PREFIX = 'image-plain-background.';

    /** Minimum brightness (0-255) of every channel for a corner to count as white. */
    private const WHITE_THRESHOLD = 235;

    /** Size of the corner square that is averaged, in pixels. */
    private const SAMPLE_SIZE = 6;

    public function __construct(
        private readonly Filesystem $disk,
        private readonly CacheRepository $cache,
    ) {}

    public function hasPlainBackground(string $path): bool
    {
        return (bool) $this->cache->rememberForever(self::CACHE_PREFIX.sha1($path), fn (): bool => $this->detect($path));
    }

    private function detect(string $path): bool
    {
        $contents = $this->disk->get($path);
        $image = is_string($contents) ? @imagecreatefromstring($contents) : false;

        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $size = min(self::SAMPLE_SIZE, $width, $height);
        $corners = [[0, 0], [$width - $size, 0], [0, $height - $size], [$width - $size, $height - $size]];

        foreach ($corners as [$left, $top]) {
            if (! $this->isWhite($image, $left, $top, $size)) {
                return false;
            }
        }

        return true;
    }

    private function isWhite(\GdImage $image, int $left, int $top, int $size): bool
    {
        $totals = [0, 0, 0];

        for ($x = $left; $x < $left + $size; $x++) {
            for ($y = $top; $y < $top + $size; $y++) {
                $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));
                $totals[0] += $rgb['red'];
                $totals[1] += $rgb['green'];
                $totals[2] += $rgb['blue'];
            }
        }

        $pixels = $size * $size;

        return min($totals) / $pixels >= self::WHITE_THRESHOLD;
    }
}

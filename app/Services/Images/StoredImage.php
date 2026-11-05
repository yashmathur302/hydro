<?php

declare(strict_types=1);

namespace App\Services\Images;

/**
 * Paths (on the public disk) of an image saved by WebpImageProcessor.
 */
final readonly class StoredImage
{
    public function __construct(
        public string $path,
        public string $thumbnailPath,
    ) {}
}

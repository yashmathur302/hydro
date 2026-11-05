<?php

declare(strict_types=1);

namespace App\Services\Images;

use GdImage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Re-encodes uploaded images to WebP with GD, in a large and a thumbnail size.
 *
 * Re-encoding (instead of storing the original file) keeps pages light and
 * also discards anything hidden in the upload besides the pixels, such as
 * EXIF data (camera, GPS location) or embedded payloads.
 */
final class WebpImageProcessor
{
    private const LARGE_MAX_WIDTH = 1600;

    private const THUMBNAIL_MAX_WIDTH = 600;

    private const QUALITY = 82;

    public function __construct(private readonly Filesystem $disk) {}

    public function store(UploadedFile $file, string $directory): StoredImage
    {
        $source = $this->load($file);

        try {
            $name = Str::random(40);
            $path = "{$directory}/{$name}.webp";
            $thumbnailPath = "{$directory}/{$name}-thumb.webp";

            $this->write($source, $path, self::LARGE_MAX_WIDTH);
            $this->write($source, $thumbnailPath, self::THUMBNAIL_MAX_WIDTH);
        } finally {
            imagedestroy($source);
        }

        return new StoredImage($path, $thumbnailPath);
    }

    /**
     * Store a single WebP version, e.g. for a category banner.
     */
    public function storeSingle(UploadedFile $file, string $directory, int $maxWidth = self::LARGE_MAX_WIDTH): string
    {
        $source = $this->load($file);
        $path = $directory.'/'.Str::random(40).'.webp';

        try {
            $this->write($source, $path, $maxWidth);
        } finally {
            imagedestroy($source);
        }

        return $path;
    }

    public function delete(StoredImage $image): void
    {
        $this->disk->delete([$image->path, $image->thumbnailPath]);
    }

    private function load(UploadedFile $file): GdImage
    {
        $contents = (string) file_get_contents($file->getRealPath());
        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            throw new RuntimeException('The uploaded file is not a readable image.');
        }

        return $image;
    }

    private function write(GdImage $source, string $path, int $maxWidth): void
    {
        $image = $this->resizeToWidth($source, $maxWidth);

        ob_start();
        $encoded = imagewebp($image, null, self::QUALITY);
        $binary = (string) ob_get_clean();

        if ($image !== $source) {
            imagedestroy($image);
        }

        if (! $encoded || ! $this->disk->put($path, $binary)) {
            throw new RuntimeException("Could not save image [{$path}].");
        }
    }

    /**
     * Returns the source itself when it is already narrow enough.
     */
    private function resizeToWidth(GdImage $source, int $maxWidth): GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);

        if ($width <= $maxWidth) {
            return $source;
        }

        $targetHeight = (int) max(1, round($height * $maxWidth / $width));
        $resized = imagecreatetruecolor($maxWidth, $targetHeight);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $targetHeight, $width, $height);

        return $resized;
    }
}

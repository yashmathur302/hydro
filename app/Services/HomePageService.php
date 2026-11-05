<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\HomeSection;
use App\Models\PageSection;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

/**
 * Reads and saves the editable home page sections. Content is cached until
 * the next save; images are re-encoded to WebP and old files are removed.
 */
final class HomePageService
{
    private const PAGE = 'home';

    private const CACHE_KEY = 'page_sections.home';

    private const IMAGE_DIRECTORY = 'home';

    /** @var array<string, array<string, mixed>>|null */
    private ?array $stored = null;

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Filesystem $publicDisk,
        private readonly WebpImageProcessor $images,
    ) {}

    /**
     * Section content with defaults filled in, plus "<slot>_url" for every image slot.
     *
     * @return array<string, mixed>
     */
    public function section(HomeSection $section): array
    {
        $content = array_replace($section->defaults(), $this->stored()[$section->value] ?? []);

        foreach (array_keys($section->imageSlots()) as $slot) {
            $path = $content[$slot] ?? null;
            $content[$slot.'_url'] = is_string($path) && $path !== '' ? $this->publicDisk->url($path) : null;
        }

        return $content;
    }

    /**
     * @param  array<string, string|list<string>|null>  $text  validated text fields
     * @param  array<string, UploadedFile>  $uploads  new images keyed by slot
     * @param  list<string>  $removals  image slots to clear
     */
    public function save(HomeSection $section, array $text, array $uploads, array $removals): void
    {
        $current = $this->stored()[$section->value] ?? [];
        $content = array_replace($current, $text);
        $filesToDelete = [];

        foreach ($section->imageSlots() as $slot => $maxWidth) {
            $previous = $current[$slot] ?? null;

            if (isset($uploads[$slot])) {
                $content[$slot] = $this->images->storeSingle($uploads[$slot], self::IMAGE_DIRECTORY, $maxWidth);
            } elseif (in_array($slot, $removals, true)) {
                $content[$slot] = null;
            }

            if ($previous !== null && $previous !== ($content[$slot] ?? null)) {
                $filesToDelete[] = $previous;
            }
        }

        PageSection::query()->updateOrCreate(
            ['page' => self::PAGE, 'section' => $section->value],
            ['content' => $content],
        );

        $this->publicDisk->delete($filesToDelete);
        $this->flush();
    }

    public function flush(): void
    {
        $this->stored = null;
        $this->cache->forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function stored(): array
    {
        return $this->stored ??= $this->cache->rememberForever(
            self::CACHE_KEY,
            static fn (): array => PageSection::query()
                ->where('page', self::PAGE)
                ->pluck('content', 'section')
                ->map(static fn ($content): array => is_array($content) ? $content : (array) json_decode((string) $content, true))
                ->all(),
        );
    }
}

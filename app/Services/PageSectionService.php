<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\EditablePageSection;
use App\Models\PageSection;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

/**
 * Reads and saves the editable sections of the website pages (home, about,
 * ...). Content is cached per page until the next save; images are
 * re-encoded to WebP into a folder named after the page, and replaced or
 * removed files are deleted.
 */
final class PageSectionService
{
    private const CACHE_PREFIX = 'page_sections.';

    /** @var array<string, array<string, array<string, mixed>>> stored content per page */
    private array $stored = [];

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
    public function section(EditablePageSection $section): array
    {
        $content = array_replace($section->defaults(), $this->stored($section->page())[$section->value] ?? []);

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
    public function save(EditablePageSection $section, array $text, array $uploads, array $removals): void
    {
        $current = $this->stored($section->page())[$section->value] ?? [];
        $content = array_replace($current, $text);
        $filesToDelete = [];

        foreach ($section->imageSlots() as $slot => $maxWidth) {
            $previous = $current[$slot] ?? null;

            if (isset($uploads[$slot])) {
                $content[$slot] = $this->images->storeSingle($uploads[$slot], $section->page(), $maxWidth);
            } elseif (in_array($slot, $removals, true)) {
                $content[$slot] = null;
            }

            if ($previous !== null && $previous !== ($content[$slot] ?? null)) {
                $filesToDelete[] = $previous;
            }
        }

        PageSection::query()->updateOrCreate(
            ['page' => $section->page(), 'section' => $section->value],
            ['content' => $content],
        );

        $this->publicDisk->delete($filesToDelete);
        $this->flush($section->page());
    }

    public function flush(string $page): void
    {
        unset($this->stored[$page]);
        $this->cache->forget(self::CACHE_PREFIX.$page);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function stored(string $page): array
    {
        return $this->stored[$page] ??= $this->cache->rememberForever(
            self::CACHE_PREFIX.$page,
            static fn (): array => PageSection::query()
                ->where('page', $page)
                ->pluck('content', 'section')
                ->map(static fn ($content): array => is_array($content) ? $content : (array) json_decode((string) $content, true))
                ->all(),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SiteSettingKey;
use App\Models\SiteSetting;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Reads and writes site-wide settings. All settings are loaded in one query,
 * cached until the next change, and kept in memory for the current request.
 */
final class SiteSettingService
{
    private const CACHE_KEY = 'site_settings.all';

    private const UPLOAD_DIRECTORY = 'settings';

    /** @var array<string, string|null>|null */
    private ?array $settings = null;

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Filesystem $publicDisk,
    ) {}

    public function get(SiteSettingKey $key, ?string $default = null): ?string
    {
        $value = $this->all()[$key->value] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Public URL of an uploaded file setting (logo, favicon), or null.
     */
    public function fileUrl(SiteSettingKey $key): ?string
    {
        $path = $this->get($key);

        return $path === null ? null : $this->publicDisk->url($path);
    }

    /**
     * @return array<string, string|null> setting values keyed by SiteSettingKey value
     */
    public function all(): array
    {
        return $this->settings ??= $this->cache->rememberForever(
            self::CACHE_KEY,
            static fn (): array => SiteSetting::query()->pluck('value', 'key')->all(),
        );
    }

    /**
     * @param  array<string, string|null>  $values  values keyed by SiteSettingKey value
     */
    public function update(array $values): void
    {
        $rows = [];

        foreach ($values as $key => $value) {
            $rows[] = [
                'key' => SiteSettingKey::from($key)->value,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($rows === []) {
            return;
        }

        DB::transaction(static function () use ($rows): void {
            SiteSetting::query()->upsert($rows, ['key'], ['value', 'updated_at']);
        });

        $this->flush();
    }

    /**
     * Store a new upload for a file setting under a random name and remove
     * the file it replaces.
     */
    public function replaceFile(SiteSettingKey $key, UploadedFile $file): void
    {
        $previousPath = $this->get($key);
        $newPath = $this->publicDisk->putFile(self::UPLOAD_DIRECTORY, $file);

        $this->update([$key->value => $newPath ?: null]);
        $this->deleteStoredFile($previousPath);
    }

    public function removeFile(SiteSettingKey $key): void
    {
        $previousPath = $this->get($key);

        $this->update([$key->value => null]);
        $this->deleteStoredFile($previousPath);
    }

    public function flush(): void
    {
        $this->settings = null;
        $this->cache->forget(self::CACHE_KEY);
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path !== null && str_starts_with($path, self::UPLOAD_DIRECTORY.'/')) {
            $this->publicDisk->delete($path);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Fills the "slug" column from a source column (default "name") when it is
 * left empty, adding -2, -3 ... until the slug is unique in the table.
 */
trait HasUniqueSlug
{
    protected static function bootHasUniqueSlug(): void
    {
        static::saving(static function (self $model): void {
            if (blank($model->slug)) {
                $model->slug = $model->generateUniqueSlug((string) $model->getAttribute($model->slugSourceColumn()));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slugSourceColumn(): string
    {
        return 'name';
    }

    private function generateUniqueSlug(string $source): string
    {
        $base = Str::limit(Str::slug($source), 150, '') ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while ($this->slugExists($slug)) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug): bool
    {
        return static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists();
    }
}

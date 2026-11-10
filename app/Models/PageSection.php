<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stored content of one fixed page section. Read and write it through
 * App\Services\PageSectionService, which applies defaults and caching.
 *
 * @property int $id
 * @property string $page
 * @property string $section
 * @property array<string, mixed> $content
 */
class PageSection extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'page',
        'section',
        'content',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}

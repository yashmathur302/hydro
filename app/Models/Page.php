<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A static content page such as the privacy policy, shown at /{slug}.
 * Content is Markdown.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property bool $is_active
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 */
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasUniqueSlug;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * First path segments used by other parts of the site; a page may not use
     * them as its slug.
     */
    public const RESERVED_SLUGS = [
        'about-us', 'products', 'certificates', 'contact-us', 'contact', 'enquiries',
        'admin', 'login', 'logout', 'build', 'storage', 'uploads', 'up', 'vendor',
        'robots', 'sitemap', 'favicon',
    ];

    protected function slugSourceColumn(): string
    {
        return 'title';
    }

    /**
     * @return list<string>
     */
    protected function reservedSlugs(): array
    {
        return self::RESERVED_SLUGS;
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

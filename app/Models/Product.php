<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedScope;
use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $product_category_id
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $brochure_path
 * @property bool $is_featured
 * @property bool $is_active
 * @property int $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use HasPublishedScope;
    use HasUniqueSlug;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'is_featured',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * @return BelongsTo<ProductCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * The first gallery image, used for listings and social sharing.
     *
     * @return HasOne<ProductImage, $this>
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany(['sort_order' => 'min', 'id' => 'min']);
    }

    /**
     * @return HasMany<ProductSpecification, $this>
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return HasMany<Enquiry, $this>
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where($this->qualifyColumn('is_featured'), true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

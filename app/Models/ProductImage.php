<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Images\PlainBackgroundDetector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A gallery image stored as WebP in two sizes (large and thumbnail).
 *
 * @property int $id
 * @property int $product_id
 * @property string $path
 * @property string $thumbnail_path
 * @property string|null $alt_text
 * @property int $sort_order
 */
class ProductImage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'path',
        'thumbnail_path',
        'alt_text',
        'sort_order',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function thumbnailUrl(): string
    {
        return Storage::disk('public')->url($this->thumbnail_path);
    }

    /**
     * Whether the photo is a product cut-out on a plain white background.
     */
    public function hasPlainBackground(): bool
    {
        return app(PlainBackgroundDetector::class)->hasPlainBackground($this->thumbnail_path);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}

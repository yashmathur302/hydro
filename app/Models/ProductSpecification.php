<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One row of a product's specification table, e.g. "Material: Stainless steel".
 *
 * @property int $id
 * @property int $product_id
 * @property string $label
 * @property string $value
 * @property int $sort_order
 */
class ProductSpecification extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'value',
        'sort_order',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

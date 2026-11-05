<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeatureIcon;
use App\Models\Concerns\HasPublishedScope;
use Database\Factories\IndustryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property FeatureIcon $icon
 * @property string|null $image_path
 * @property bool $is_active
 * @property int $sort_order
 */
class Industry extends Model
{
    /** @use HasFactory<IndustryFactory> */
    use HasFactory;

    use HasPublishedScope;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    public function imageUrl(): ?string
    {
        return $this->image_path === null ? null : Storage::disk('public')->url($this->image_path);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'icon' => FeatureIcon::class,
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

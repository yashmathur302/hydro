<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedScope;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A client whose logo is shown on the home page.
 *
 * @property int $id
 * @property string $name
 * @property string $logo_path
 * @property string|null $website_url
 * @property bool $is_active
 * @property int $sort_order
 */
class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    use HasPublishedScope;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'website_url',
        'is_active',
        'sort_order',
    ];

    public function logoUrl(): string
    {
        return Storage::disk('public')->url($this->logo_path);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedScope;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A certificate (ISO, government approval, ...) uploaded as a PDF. Visitors
 * open the PDF in a new browser tab; the optional cover image is shown on the
 * card.
 *
 * @property int $id
 * @property string $name
 * @property string|null $issuer
 * @property string $file_path
 * @property string|null $cover_image_path
 * @property bool $is_active
 * @property int $sort_order
 */
class Certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory;

    use HasPublishedScope;

    /** Number of certificates shown on the home page. */
    public const HOME_LIMIT = 3;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'issuer',
        'is_active',
        'sort_order',
    ];

    public function fileUrl(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function coverUrl(): ?string
    {
        return $this->cover_image_path ? Storage::disk('public')->url($this->cover_image_path) : null;
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

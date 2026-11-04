<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use Database\Factories\EnquiryFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A product enquiry or contact-form message sent from the website.
 *
 * @property int $id
 * @property EnquiryType $type
 * @property EnquiryStatus $status
 * @property int|null $product_id
 * @property string|null $product_name
 * @property string $name
 * @property string|null $email
 * @property string $phone
 * @property string|null $company
 * @property string|null $city
 * @property string|null $subject
 * @property string|null $message
 * @property string|null $admin_notes
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 */
class Enquiry extends Model
{
    /** @use HasFactory<EnquiryFactory> */
    use HasFactory;

    /**
     * Only visitor-supplied fields are fillable; status, notes and request
     * metadata are always set explicitly.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'city',
        'subject',
        'message',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    #[Scope]
    protected function ofType(Builder $query, EnquiryType $type): void
    {
        $query->where('type', $type->value);
    }

    #[Scope]
    protected function unread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => EnquiryType::class,
            'status' => EnquiryStatus::class,
            'read_at' => 'datetime',
        ];
    }
}

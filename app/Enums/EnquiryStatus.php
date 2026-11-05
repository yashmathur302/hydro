<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Follow-up stage of an enquiry, set by the admin.
 */
enum EnquiryStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::InProgress => 'In progress',
            self::Closed => 'Closed',
        };
    }

    /**
     * Modifier for the admin "badge" component.
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::New => 'info',
            self::InProgress => 'warning',
            self::Closed => 'neutral',
        };
    }
}

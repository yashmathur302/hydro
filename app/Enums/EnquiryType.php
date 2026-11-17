<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Where an enquiry came from on the website.
 */
enum EnquiryType: string
{
    case Product = 'product';
    case Contact = 'contact';

    public function label(): string
    {
        return match ($this) {
            self::Product => 'Product enquiry',
            self::Contact => 'Contact message',
        };
    }

    public function pluralLabel(): string
    {
        return match ($this) {
            self::Product => 'Enquiries',
            self::Contact => 'Contact messages',
        };
    }
}

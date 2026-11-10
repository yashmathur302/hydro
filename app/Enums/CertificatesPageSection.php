<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Editable sections of the Certificates page (the certificates themselves are
 * managed in Admin > Certificates).
 */
enum CertificatesPageSection: string implements EditablePageSection
{
    case Banner = 'banner';

    public function page(): string
    {
        return 'certificates';
    }

    public function label(): string
    {
        return match ($this) {
            self::Banner => 'Banner',
        };
    }

    /**
     * @return array<string, string>
     */
    public function defaults(): array
    {
        return match ($this) {
            self::Banner => [
                'eyebrow' => 'Quality You Can Verify',
                'title' => 'Certificates',
            ],
        };
    }

    /**
     * @return array<string, int>
     */
    public function imageSlots(): array
    {
        return match ($this) {
            self::Banner => [
                'background_image' => 1920,
            ],
        };
    }
}

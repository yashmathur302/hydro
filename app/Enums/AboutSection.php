<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Editable sections of the About Us page. Further sections are added here as
 * their designs arrive. Defaults are shown until the admin saves the page.
 */
enum AboutSection: string implements EditablePageSection
{
    case Banner = 'banner';

    public function page(): string
    {
        return 'about';
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
                'eyebrow' => 'Pipeline Pigging Experts',
                'title' => 'About Us',
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

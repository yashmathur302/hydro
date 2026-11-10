<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Site-wide parts shown on every page: the main menu (header and footer),
 * the call-to-action box above the footer and the footer itself.
 */
enum LayoutSection: string implements EditablePageSection
{
    case Menu = 'menu';
    case FooterCta = 'footer_cta';
    case Footer = 'footer';

    public const MAX_MENU_ITEMS = 8;

    /** Placeholder in the copyright text replaced with the current year. */
    public const YEAR_PLACEHOLDER = '{year}';

    public function page(): string
    {
        return 'layout';
    }

    public function label(): string
    {
        return match ($this) {
            self::Menu => 'Menu',
            self::FooterCta => 'Footer call-to-action',
            self::Footer => 'Footer',
        };
    }

    /**
     * @return array<string, string|list<array<string, string>>>
     */
    public function defaults(): array
    {
        return match ($this) {
            self::Menu => [
                'items' => [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'About Us', 'url' => '/about-us'],
                    ['label' => 'Products', 'url' => '/products'],
                    ['label' => 'Certificates', 'url' => '/certificates'],
                    ['label' => 'Contact Us', 'url' => '/contact-us'],
                ],
            ],
            self::FooterCta => [
                'eyebrow' => 'Trusted Pipeline Partner',
                'heading' => 'Pigging Solutions For Your Next Pipeline Project',
                'button_label' => 'Contact Us',
                'button_url' => '/contact-us',
            ],
            self::Footer => [
                'description' => 'We design and manufacture gauging pigs, brush pigs and pipeline cleaning equipment for oil, gas and water pipelines.',
                'menu_heading' => 'Menu Links',
                'links_heading' => 'Useful Links',
                'contact_heading' => 'Contact Us',
                'copyright' => '© '.self::YEAR_PLACEHOLDER.' Lucky Instru Hydro. All rights reserved.',
            ],
        };
    }

    /**
     * @return array<string, int>
     */
    public function imageSlots(): array
    {
        return match ($this) {
            self::Menu => [],
            self::FooterCta => [
                'background_image' => 1400,
            ],
            self::Footer => [
                'logo' => 480,
            ],
        };
    }
}

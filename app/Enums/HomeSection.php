<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Editable sections of the home page: their text fields, default content and
 * image slots. Defaults are shown until the admin saves the section.
 */
enum HomeSection: string
{
    case Hero = 'hero';
    case About = 'about';

    public const MAX_ABOUT_POINTS = 5;

    public function label(): string
    {
        return match ($this) {
            self::Hero => 'Banner',
            self::About => 'About us',
        };
    }

    /**
     * Default text content.
     *
     * @return array<string, string|list<string>>
     */
    public function defaults(): array
    {
        return match ($this) {
            self::Hero => [
                'eyebrow' => 'Pipeline Pigging Solutions',
                'heading' => 'Precision Pigging & Gauging Equipment',
                'text' => 'We design and manufacture gauging pigs, brush pigs and pipeline cleaning equipment that keep pipelines clean, safe and running at full capacity.',
                'button_label' => 'All Products',
                'button_url' => '/products',
                'watermark' => 'Pigging',
            ],
            self::About => [
                'eyebrow' => 'Trusted Pipeline Partner',
                'heading' => 'Pigging Products Built To Perform In Every Pipeline',
                'intro' => 'We manufacture pigging and gauging equipment for oil, gas and water pipelines, engineered for reliable cleaning, inspection and commissioning.',
                'subheading' => 'Serving Pipeline Projects Across Industries And Regions.',
                'points' => [
                    'Gauging, Brush And Cleaning Pigs For Every Line Size',
                    'Built To Project Specifications And Standards',
                    'Quick Support From Enquiry To Delivery',
                ],
                'badge_number' => '1999',
                'badge_label' => 'Since',
                'person_name' => 'Founder Name',
                'person_role' => 'CEO & Founder',
                'button_label' => 'More About Us',
                'button_url' => '/about-us',
            ],
        };
    }

    /**
     * Image slots with the maximum stored width in pixels.
     *
     * @return array<string, int>
     */
    public function imageSlots(): array
    {
        return match ($this) {
            self::Hero => [
                'background_image' => 1920,
            ],
            self::About => [
                'image_main' => 900,
                'image_top' => 600,
                'image_bottom' => 700,
                'person_photo' => 200,
                'signature_image' => 400,
                'decoration_image' => 700,
            ],
        };
    }
}

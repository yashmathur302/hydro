<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Editable sections of the home page: their text fields, default content and
 * image slots. Defaults are shown until the admin saves the section.
 */
enum HomeSection: string implements EditablePageSection
{
    case Hero = 'hero';
    case About = 'about';
    case Industries = 'industries';
    case Stats = 'stats';
    case WhyUs = 'why_us';

    public const MAX_ABOUT_POINTS = 5;

    public const STAT_COUNT = 4;

    public const MAX_WHY_ITEMS = 6;

    public function page(): string
    {
        return 'home';
    }

    public function label(): string
    {
        return match ($this) {
            self::Hero => 'Banner',
            self::About => 'About us',
            self::Industries => 'Industries',
            self::Stats => 'Counters',
            self::WhyUs => 'Why choose us',
        };
    }

    /**
     * Default text content.
     *
     * @return array<string, string|list<string>|list<array<string, string>>>
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
            self::Industries => [
                'eyebrow' => 'Industries We Serve',
                'heading' => 'Pigging Solutions For The Industries We Serve',
            ],
            self::Stats => [
                'word' => 'Hydro',
                'subtitle' => 'Pigging equipment that goes beyond the benchmark',
                'items' => [
                    ['value' => '25+', 'label' => 'Years of experience'],
                    ['value' => '500+', 'label' => 'Projects delivered'],
                    ['value' => '20+', 'label' => 'Product types'],
                    ['value' => '98%', 'label' => 'Client satisfaction'],
                ],
            ],
            self::WhyUs => [
                'eyebrow' => 'Why Choose Us',
                'heading' => 'Why Industries Trust Our Pigging Solutions',
                'items' => [
                    ['icon' => 'quality', 'title' => 'Precision Engineered Products'],
                    ['icon' => 'custom', 'title' => 'Custom Sizes And Designs'],
                    ['icon' => 'certificate', 'title' => 'Tested And Certified'],
                    ['icon' => 'delivery', 'title' => 'On-Time Delivery'],
                    ['icon' => 'support', 'title' => 'Expert Technical Support'],
                ],
                'footer_text' => 'Engineering, quality and support in one place.',
                'link_label' => 'View our products',
                'link_url' => '/products',
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
            self::Industries, self::WhyUs => [],
            self::Stats => [
                'word_image' => 1600,
            ],
        };
    }
}

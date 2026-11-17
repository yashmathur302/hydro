<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Editable sections of the Products page (the products themselves are
 * managed in Admin > Products).
 */
enum ProductsPageSection: string implements EditablePageSection
{
    case Banner = 'banner';
    case Intro = 'intro';

    public function page(): string
    {
        return 'products';
    }

    public function label(): string
    {
        return match ($this) {
            self::Banner => 'Banner',
            self::Intro => 'Heading and introduction',
        };
    }

    /**
     * @return array<string, string>
     */
    public function defaults(): array
    {
        return match ($this) {
            self::Banner => [
                'eyebrow' => 'Pipeline Pigging Equipment',
                'title' => 'Products',
            ],
            self::Intro => [
                'heading' => 'Our Product Lines',
                'text' => 'From gauging and brush pigs to foam pigs and replacement discs, we build pigging equipment for every stage of a pipeline\'s life: pre-commissioning, cleaning, dewatering, batching and routine maintenance. Each pig is made to suit your pipeline diameter and operating conditions.',
            ],
        };
    }

    /**
     * @return array<string, int>
     */
    public function imageSlots(): array
    {
        return match ($this) {
            self::Banner => ['background_image' => 1920],
            self::Intro => [],
        };
    }
}

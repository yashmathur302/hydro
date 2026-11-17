<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Icons the admin can choose for industries and "why choose us" cards.
 * Drawn in resources/views/components/site/feature-icon.blade.php.
 */
enum FeatureIcon: string
{
    case OilGas = 'oil-gas';
    case Water = 'water';
    case Pipeline = 'pipeline';
    case Refinery = 'refinery';
    case Power = 'power';
    case Chemical = 'chemical';
    case Gauge = 'gauge';
    case Quality = 'quality';
    case Delivery = 'delivery';
    case Support = 'support';
    case Certificate = 'certificate';
    case Tools = 'tools';
    case Custom = 'custom';
    case Global = 'global';
    case Experience = 'experience';
    case Team = 'team';

    public function label(): string
    {
        return match ($this) {
            self::OilGas => 'Oil & gas rig',
            self::Water => 'Water',
            self::Pipeline => 'Pipeline',
            self::Refinery => 'Refinery / plant',
            self::Power => 'Power',
            self::Chemical => 'Chemical',
            self::Gauge => 'Pressure gauge',
            self::Quality => 'Quality / safety',
            self::Delivery => 'Delivery',
            self::Support => 'Support',
            self::Certificate => 'Certificate',
            self::Tools => 'Tools',
            self::Custom => 'Custom design',
            self::Global => 'Global',
            self::Experience => 'Experience',
            self::Team => 'Team',
        };
    }

    /**
     * @return array<string, string> labels keyed by value, for select boxes
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $icon) {
            $options[$icon->value] = $icon->label();
        }

        return $options;
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\EditablePageSection;

/**
 * Editable sections of the Contact Us page. The address, phone and email
 * themselves come from Site settings > Contact; these sections hold the
 * page's own texts, images and the Google Maps embed.
 */
enum ContactPageSection: string implements EditablePageSection
{
    case Banner = 'banner';
    case Cards = 'cards';
    case Form = 'form';
    case Map = 'map';

    public function page(): string
    {
        return 'contact';
    }

    public function label(): string
    {
        return match ($this) {
            self::Banner => 'Banner',
            self::Cards => 'Contact cards',
            self::Form => 'Form section',
            self::Map => 'Map',
        };
    }

    /**
     * @return array<string, string|null>
     */
    public function defaults(): array
    {
        return match ($this) {
            self::Banner => [
                'eyebrow' => 'We Are Here To Help',
                'title' => 'Contact Us',
            ],
            self::Cards => [
                'address_title' => 'Visit Our Office',
                'phone_title' => 'Call Us',
                'email_title' => 'Email Us',
                'extra_phone' => null,
                'extra_email' => null,
            ],
            self::Form => [
                'heading' => 'Get In Touch',
                'text' => 'Tell us about your pipeline: line size, length and what you need to achieve. Our team will recommend the right pigging solution and get back to you quickly.',
                'form_heading' => 'Fill Up The Form',
                'form_note' => 'Your details are only used to reply to your message. Required fields are marked *',
                'button_label' => 'Send Message',
            ],
            self::Map => [
                'embed_url' => null,
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
            self::Form => ['image' => 1000],
            self::Cards, self::Map => [],
        };
    }
}

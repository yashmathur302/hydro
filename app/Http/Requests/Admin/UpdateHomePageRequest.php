<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\HomeSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Validates the home page editor. Text fields arrive as section[field]
 * (e.g. hero[heading]); images as <section>_<slot> (e.g. hero_background_image).
 */
final class UpdateHomePageRequest extends FormRequest
{
    public const IMAGE_MAX_KILOBYTES = 5120;

    /**
     * Relative path ("/products") or an http(s) URL. Blocks javascript: and similar schemes.
     */
    private const LINK_PATTERN = '/^(\/[^\s]*|https?:\/\/[^\s]+)$/';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $link = ['nullable', 'string', 'max:255', 'regex:'.self::LINK_PATTERN];

        $rules = [
            'hero.eyebrow' => ['nullable', 'string', 'max:80'],
            'hero.heading' => ['required', 'string', 'max:120'],
            'hero.text' => ['nullable', 'string', 'max:400'],
            'hero.button_label' => ['nullable', 'string', 'max:40'],
            'hero.button_url' => $link,
            'hero.watermark' => ['nullable', 'string', 'max:20'],

            'about.eyebrow' => ['nullable', 'string', 'max:80'],
            'about.heading' => ['required', 'string', 'max:120'],
            'about.intro' => ['nullable', 'string', 'max:600'],
            'about.subheading' => ['nullable', 'string', 'max:160'],
            'about.points' => ['nullable', 'array', 'max:'.HomeSection::MAX_ABOUT_POINTS],
            'about.points.*' => ['nullable', 'string', 'max:120'],
            'about.badge_number' => ['nullable', 'string', 'max:10'],
            'about.badge_label' => ['nullable', 'string', 'max:20'],
            'about.person_name' => ['nullable', 'string', 'max:80'],
            'about.person_role' => ['nullable', 'string', 'max:80'],
            'about.button_label' => ['nullable', 'string', 'max:40'],
            'about.button_url' => $link,
        ];

        foreach (HomeSection::cases() as $section) {
            foreach (array_keys($section->imageSlots()) as $slot) {
                $field = $section->value.'_'.$slot;
                $rules[$field] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::IMAGE_MAX_KILOBYTES, 'dimensions:max_width=6000,max_height=6000'];
                $rules['remove_'.$field] = ['nullable', 'boolean'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hero.button_url.regex' => 'Use a page path such as /products, or a full https:// link.',
            'about.button_url.regex' => 'Use a page path such as /about-us, or a full https:// link.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'hero.heading' => 'banner heading',
            'about.heading' => 'about heading',
        ];
    }

    /**
     * Validated text fields of one section; empty list items are dropped.
     *
     * @return array<string, string|list<string>|null>
     */
    public function textFor(HomeSection $section): array
    {
        $text = $this->validated($section->value, []);

        if ($section === HomeSection::About) {
            $text['points'] = array_values(array_filter(
                $text['points'] ?? [],
                static fn ($point): bool => is_string($point) && trim($point) !== '',
            ));
        }

        return $text;
    }

    /**
     * @return array<string, UploadedFile>
     */
    public function uploadsFor(HomeSection $section): array
    {
        $uploads = [];

        foreach (array_keys($section->imageSlots()) as $slot) {
            $file = $this->file($section->value.'_'.$slot);

            if ($file instanceof UploadedFile) {
                $uploads[$slot] = $file;
            }
        }

        return $uploads;
    }

    /**
     * @return list<string>
     */
    public function removalsFor(HomeSection $section): array
    {
        return array_values(array_filter(
            array_keys($section->imageSlots()),
            fn (string $slot): bool => $this->boolean('remove_'.$section->value.'_'.$slot),
        ));
    }
}

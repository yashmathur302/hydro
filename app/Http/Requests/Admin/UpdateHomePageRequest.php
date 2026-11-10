<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\FeatureIcon;
use App\Enums\HomeSection;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates the home page editor. Text fields arrive as section[field]
 * (e.g. hero[heading]); images as <section>_<slot> (e.g. hero_background_image).
 */
final class UpdateHomePageRequest extends FormRequest
{
    use HandlesPageSectionImages;

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

            'industries.eyebrow' => ['nullable', 'string', 'max:80'],
            'industries.heading' => ['required', 'string', 'max:120'],

            'stats.word' => ['nullable', 'string', 'max:20'],
            'stats.subtitle' => ['nullable', 'string', 'max:160'],
            'stats.items' => ['nullable', 'array', 'max:'.HomeSection::STAT_COUNT],
            'stats.items.*.value' => ['nullable', 'string', 'max:12', 'required_with:stats.items.*.label'],
            'stats.items.*.label' => ['nullable', 'string', 'max:60'],

            'why_us.eyebrow' => ['nullable', 'string', 'max:80'],
            'why_us.heading' => ['required', 'string', 'max:120'],
            'why_us.items' => ['nullable', 'array', 'max:'.HomeSection::MAX_WHY_ITEMS],
            'why_us.items.*.icon' => ['nullable', Rule::enum(FeatureIcon::class)],
            'why_us.items.*.title' => ['nullable', 'string', 'max:60'],
            'why_us.footer_text' => ['nullable', 'string', 'max:200'],
            'why_us.link_label' => ['nullable', 'string', 'max:40'],
            'why_us.link_url' => $link,
        ];

        $rules += $this->imageRules(HomeSection::cases());

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
            'why_us.link_url.regex' => 'Use a page path such as /products, or a full https:// link.',
            'stats.items.*.value.required_with' => 'Enter the number for this counter.',
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
            'industries.heading' => 'industries heading',
            'why_us.heading' => 'why choose us heading',
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

        if ($section === HomeSection::Stats) {
            $text['items'] = $this->filledRows($text['items'] ?? [], 'value', ['value', 'label']);
        }

        if ($section === HomeSection::WhyUs) {
            $text['items'] = $this->filledRows($text['items'] ?? [], 'title', ['icon', 'title']);
        }

        return $text;
    }

    /**
     * Keep only rows whose required field is filled, with just the known keys, in order.
     *
     * @param  array<int|string, mixed>  $rows
     * @param  list<string>  $keys
     * @return list<array<string, string>>
     */
    private function filledRows(array $rows, string $requiredKey, array $keys): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row) || blank($row[$requiredKey] ?? null)) {
                continue;
            }

            $clean[] = array_map(static fn (string $key): string => (string) ($row[$key] ?? ''), array_combine($keys, $keys));
        }

        return $clean;
    }
}

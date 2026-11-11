<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\LayoutSection;
use App\Http\Requests\Admin\Concerns\CleansRepeatedRows;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use App\Support\SiteLink;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the Header & Footer editor: menu rows (menu[items][n][label|url]),
 * the footer call-to-action box and the footer texts.
 */
final class UpdateLayoutRequest extends FormRequest
{
    use CleansRepeatedRows;
    use HandlesPageSectionImages;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $link = ['nullable', 'string', 'max:255', 'regex:'.SiteLink::PATTERN];

        return [
            'menu.items' => ['required', 'array', 'max:'.LayoutSection::MAX_MENU_ITEMS],
            'menu.items.*.label' => ['nullable', 'string', 'max:30', 'required_with:menu.items.*.url'],
            'menu.items.*.url' => [...$link, 'required_with:menu.items.*.label'],

            'footer_cta.eyebrow' => ['nullable', 'string', 'max:80'],
            'footer_cta.heading' => ['required', 'string', 'max:120'],
            'footer_cta.button_label' => ['nullable', 'string', 'max:40'],
            'footer_cta.button_url' => $link,

            'footer.description' => ['nullable', 'string', 'max:400'],
            'footer.menu_heading' => ['nullable', 'string', 'max:40'],
            'footer.links_heading' => ['nullable', 'string', 'max:40'],
            'footer.contact_heading' => ['nullable', 'string', 'max:40'],
            'footer.copyright' => ['nullable', 'string', 'max:200'],
        ] + $this->imageRules(LayoutSection::cases());
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $linkHint = 'Use a page path such as /contact-us, or a full https:// link.';

        return [
            'menu.items.*.url.regex' => $linkHint,
            'menu.items.*.url.required_with' => 'Enter the link for this menu item.',
            'menu.items.*.label.required_with' => 'Enter the text for this menu item.',
            'footer_cta.button_url.regex' => $linkHint,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'footer_cta.heading' => 'call-to-action heading',
            'footer_cta_background_image' => 'call-to-action photo',
            'footer_logo' => 'footer logo',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function textFor(LayoutSection $section): array
    {
        $text = $this->validated($section->value, []);

        if ($section === LayoutSection::Menu) {
            $text['items'] = $this->filledRows($text['items'] ?? [], 'label', ['label', 'url']);
        }

        return $text;
    }

    /**
     * At least one menu item must remain after empty rows are dropped.
     *
     * @return list<callable>
     */
    public function after(): array
    {
        return [
            function ($validator): void {
                $filled = $this->filledRows((array) $this->input('menu.items', []), 'label', ['label', 'url']);

                if ($filled === []) {
                    $validator->errors()->add('menu.items', 'Keep at least one menu item.');
                }
            },
        ];
    }
}

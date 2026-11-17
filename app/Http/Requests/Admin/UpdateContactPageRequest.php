<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ContactPageSection;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use App\Http\Requests\Admin\Concerns\HasPageBannerFields;
use App\Support\GoogleMapsEmbed;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the Contact Us page editor. The map field accepts either the
 * Google Maps embed URL or the whole <iframe> code; only the URL is kept.
 */
final class UpdateContactPageRequest extends FormRequest
{
    use HandlesPageSectionImages;
    use HasPageBannerFields;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $map = (array) $this->input('map', []);
        $map['embed_url'] = GoogleMapsEmbed::extract($map['embed_url'] ?? null);

        $this->merge(['map' => $map]);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return $this->bannerRules() + [
            'cards.address_title' => ['required', 'string', 'max:40'],
            'cards.phone_title' => ['required', 'string', 'max:40'],
            'cards.email_title' => ['required', 'string', 'max:40'],
            'cards.extra_phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'cards.extra_email' => ['nullable', 'string', 'email', 'max:191'],

            'form.heading' => ['required', 'string', 'max:80'],
            'form.text' => ['nullable', 'string', 'max:500'],
            'form.form_heading' => ['required', 'string', 'max:80'],
            'form.form_note' => ['nullable', 'string', 'max:200'],
            'form.button_label' => ['required', 'string', 'max:30'],

            'map.embed_url' => ['nullable', 'string', static function (string $attribute, mixed $value, Closure $fail): void {
                if (! GoogleMapsEmbed::isValid(is_string($value) ? $value : null)) {
                    $fail('Paste the link or the <iframe> code from Google Maps › Share › Embed a map.');
                }
            }],
        ] + $this->imageRules(ContactPageSection::cases());
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['cards.extra_phone.regex' => 'Enter a valid phone number.'];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->bannerAttributes() + [
            'cards.extra_phone' => 'second phone number',
            'cards.extra_email' => 'second email address',
            'form.heading' => 'left heading',
            'form.form_heading' => 'form heading',
            'form.button_label' => 'button text',
            'form_image' => 'photo',
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function textFor(ContactPageSection $section): array
    {
        return $this->validated($section->value, []);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\SiteSettingKey;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateSiteSettingsRequest extends FormRequest
{
    public const LOGO_MAX_KILOBYTES = 2048;

    public const FAVICON_MAX_KILOBYTES = 512;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $optionalUrl = ['nullable', 'string', 'url:http,https', 'max:255'];

        return [
            SiteSettingKey::SiteName->value => ['required', 'string', 'max:100'],
            SiteSettingKey::SiteTagline->value => ['nullable', 'string', 'max:150'],

            'logo' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:'.self::LOGO_MAX_KILOBYTES, 'dimensions:max_width=4000,max_height=4000'],
            'favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:'.self::FAVICON_MAX_KILOBYTES],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],

            SiteSettingKey::ContactPhone->value => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            SiteSettingKey::ContactEmail->value => ['nullable', 'string', 'email', 'max:191'],
            SiteSettingKey::ContactAddress->value => ['nullable', 'string', 'max:500'],
            SiteSettingKey::WhatsappNumber->value => ['nullable', 'string', 'regex:/^[1-9][0-9]{7,14}$/'],
            SiteSettingKey::EnquiryNotificationEmail->value => ['nullable', 'string', 'email', 'max:191'],

            SiteSettingKey::FacebookUrl->value => $optionalUrl,
            SiteSettingKey::InstagramUrl->value => $optionalUrl,
            SiteSettingKey::LinkedinUrl->value => $optionalUrl,
            SiteSettingKey::YoutubeUrl->value => $optionalUrl,
            SiteSettingKey::XUrl->value => $optionalUrl,

            SiteSettingKey::DefaultMetaTitle->value => ['nullable', 'string', 'max:120'],
            SiteSettingKey::DefaultMetaDescription->value => ['nullable', 'string', 'max:300'],
            SiteSettingKey::DefaultMetaKeywords->value => ['nullable', 'string', 'max:255'],

            SiteSettingKey::Ga4MeasurementId->value => ['nullable', 'string', 'regex:/^G-[A-Z0-9]{4,20}$/'],
            SiteSettingKey::FacebookPixelId->value => ['nullable', 'string', 'regex:/^[0-9]{10,20}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            SiteSettingKey::ContactPhone->value.'.regex' => 'Use digits, spaces, +, - and brackets only.',
            SiteSettingKey::WhatsappNumber->value.'.regex' => 'Enter the number with country code and digits only, e.g. 919876543210.',
            SiteSettingKey::Ga4MeasurementId->value.'.regex' => 'A GA4 Measurement ID looks like G-XXXXXXXXXX.',
            SiteSettingKey::FacebookPixelId->value.'.regex' => 'A Pixel ID contains digits only, e.g. 123456789012345.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            SiteSettingKey::SiteName->value => 'site name',
            SiteSettingKey::Ga4MeasurementId->value => 'GA4 Measurement ID',
            SiteSettingKey::FacebookPixelId->value => 'Facebook Pixel ID',
            SiteSettingKey::WhatsappNumber->value => 'WhatsApp number',
        ];
    }

    /**
     * Validated text settings keyed by SiteSettingKey value, with empty strings stored as null.
     *
     * @return array<string, string|null>
     */
    public function textSettings(): array
    {
        $validated = $this->validated();
        $values = [];

        foreach (SiteSettingKey::textKeys() as $key) {
            $value = $validated[$key->value] ?? null;
            $values[$key->value] = ($value === null || $value === '') ? null : (string) $value;
        }

        return $values;
    }

    protected function prepareForValidation(): void
    {
        $ga4Id = $this->input(SiteSettingKey::Ga4MeasurementId->value);
        $whatsapp = $this->input(SiteSettingKey::WhatsappNumber->value);
        $pixelId = $this->input(SiteSettingKey::FacebookPixelId->value);

        $this->merge([
            SiteSettingKey::Ga4MeasurementId->value => is_string($ga4Id) ? strtoupper(trim($ga4Id)) : $ga4Id,
            SiteSettingKey::WhatsappNumber->value => is_string($whatsapp) ? preg_replace('/[\s+\-()]/', '', $whatsapp) : $whatsapp,
            SiteSettingKey::FacebookPixelId->value => is_string($pixelId) ? trim($pixelId) : $pixelId,
        ]);
    }
}

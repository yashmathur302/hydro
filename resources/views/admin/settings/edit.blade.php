@php
    use App\Enums\SiteSettingKey as Key;
    use App\Http\Requests\Admin\UpdateSiteSettingsRequest as Rules;

    $sections = [
        'branding' => 'Branding',
        'contact' => 'Contact & enquiries',
        'social' => 'Social profiles',
        'seo' => 'Default SEO',
        'tracking' => 'Tracking & verification',
    ];
@endphp

<x-admin.layout title="Site settings">
    <x-slot:header>
        <x-admin.page-header title="Site settings" description="Branding, contact details, default SEO and tracking used across the whole website." />
    </x-slot:header>

    <div class="settings">
        <nav class="segmented" aria-label="Settings sections">
            <ul class="segmented__list">
                @foreach ($sections as $anchor => $label)
                    <li><a class="segmented__item" href="#{{ $anchor }}" data-section-link>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            <x-admin.card id="branding" title="Branding" description="Your business name and the images shown in the header and browser tab.">
                <div class="form-grid">
                    <x-admin.form.input :name="Key::SiteName->value" label="Site name" :value="$settings[Key::SiteName->value] ?? null" required maxlength="100" />
                    <x-admin.form.input :name="Key::SiteTagline->value" label="Tagline" :value="$settings[Key::SiteTagline->value] ?? null" maxlength="150" />
                    <x-admin.form.image-upload name="logo" label="Logo" :current-url="$logoUrl" accept="image/png,image/jpeg,image/webp" remove-name="remove_logo" hint="PNG, JPG or WebP, up to {{ Rules::LOGO_MAX_KILOBYTES / 1024 }} MB." />
                    <x-admin.form.image-upload name="favicon" label="Favicon" :current-url="$faviconUrl" accept="image/png,.ico" remove-name="remove_favicon" hint="Square PNG or ICO (at least 48 × 48), up to {{ Rules::FAVICON_MAX_KILOBYTES }} KB." />
                </div>
            </x-admin.card>

            <x-admin.card id="contact" title="Contact & enquiries" description="Shown on the website and used for the Enquire and WhatsApp buttons.">
                <div class="form-grid">
                    <x-admin.form.input :name="Key::ContactPhone->value" type="tel" label="Phone number" :value="$settings[Key::ContactPhone->value] ?? null" maxlength="30" autocomplete="off" />
                    <x-admin.form.input :name="Key::ContactEmail->value" type="email" label="Email address" :value="$settings[Key::ContactEmail->value] ?? null" maxlength="191" />
                    <x-admin.form.input :name="Key::WhatsappNumber->value" type="tel" label="WhatsApp number" :value="$settings[Key::WhatsappNumber->value] ?? null" maxlength="20" inputmode="numeric" hint="With country code, digits only, e.g. 919876543210." />
                    <x-admin.form.input :name="Key::EnquiryNotificationEmail->value" type="email" label="Send enquiries to" :value="$settings[Key::EnquiryNotificationEmail->value] ?? null" maxlength="191" hint="Every new enquiry is emailed to this address." />
                    <x-admin.form.textarea :name="Key::ContactAddress->value" label="Address" :value="$settings[Key::ContactAddress->value] ?? null" maxlength="500" class="form-grid__full" />
                </div>
            </x-admin.card>

            <x-admin.card id="social" title="Social profiles" description="Full profile links. Leave a field empty to hide that icon.">
                <div class="form-grid">
                    <x-admin.form.input :name="Key::FacebookUrl->value" type="url" label="Facebook" :value="$settings[Key::FacebookUrl->value] ?? null" maxlength="255" placeholder="https://www.facebook.com/…" />
                    <x-admin.form.input :name="Key::InstagramUrl->value" type="url" label="Instagram" :value="$settings[Key::InstagramUrl->value] ?? null" maxlength="255" placeholder="https://www.instagram.com/…" />
                    <x-admin.form.input :name="Key::LinkedinUrl->value" type="url" label="LinkedIn" :value="$settings[Key::LinkedinUrl->value] ?? null" maxlength="255" placeholder="https://www.linkedin.com/…" />
                    <x-admin.form.input :name="Key::YoutubeUrl->value" type="url" label="YouTube" :value="$settings[Key::YoutubeUrl->value] ?? null" maxlength="255" placeholder="https://www.youtube.com/…" />
                    <x-admin.form.input :name="Key::XUrl->value" type="url" label="X (Twitter)" :value="$settings[Key::XUrl->value] ?? null" maxlength="255" placeholder="https://x.com/…" />
                </div>
            </x-admin.card>

            <x-admin.card id="seo" title="Default SEO" description="Used on any page that does not have its own meta title, description or keywords.">
                <div class="form-stack">
                    <x-admin.form.input :name="Key::DefaultMetaTitle->value" label="Meta title" :value="$settings[Key::DefaultMetaTitle->value] ?? null" maxlength="120" counter hint="Aim for 50–60 characters." />
                    <x-admin.form.textarea :name="Key::DefaultMetaDescription->value" label="Meta description" :value="$settings[Key::DefaultMetaDescription->value] ?? null" maxlength="300" counter hint="Aim for 140–160 characters." />
                    <x-admin.form.input :name="Key::DefaultMetaKeywords->value" label="Meta keywords" :value="$settings[Key::DefaultMetaKeywords->value] ?? null" maxlength="255" hint="Separate keywords with commas." />
                </div>
            </x-admin.card>

            <x-admin.card id="tracking" title="Tracking & verification" description="Enter only the ID. The website adds the official tracking code automatically. Leave empty to turn tracking off.">
                <div class="form-grid">
                    <x-admin.form.input :name="Key::Ga4MeasurementId->value" label="Google Analytics 4 Measurement ID" :value="$settings[Key::Ga4MeasurementId->value] ?? null" maxlength="24" placeholder="G-XXXXXXXXXX" autocomplete="off" spellcheck="false" hint="Google Analytics › Admin › Data streams › your web stream." />
                    <x-admin.form.input :name="Key::FacebookPixelId->value" label="Facebook (Meta) Pixel ID" :value="$settings[Key::FacebookPixelId->value] ?? null" maxlength="20" inputmode="numeric" placeholder="123456789012345" autocomplete="off" hint="Meta Events Manager › Data sources › your pixel." />
                    <x-admin.form.input :name="Key::GoogleSiteVerification->value" label="Google Search Console verification" :value="$settings[Key::GoogleSiteVerification->value] ?? null" maxlength="255" autocomplete="off" spellcheck="false" class="form-grid__full" hint="Search Console › Add property › HTML tag. Paste the code or the whole tag; only the code is kept." />
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save settings</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

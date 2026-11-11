@php($maxMb = \App\Http\Requests\Admin\UpdateContactPageRequest::IMAGE_MAX_KILOBYTES / 1024)

<x-admin.layout title="Contact Us page">
    <x-slot:header>
        <x-admin.page-header title="Contact Us page" description="Texts, photo and map of the Contact Us page. The address, phone and email come from Site settings › Contact.">
            <x-admin.button :href="route('contact')" variant="secondary" icon="globe" target="_blank" rel="noopener">View page</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <nav class="segmented" aria-label="Contact page sections">
            <ul class="segmented__list">
                <li><a class="segmented__item" href="#banner" data-section-link>Banner</a></li>
                <li><a class="segmented__item" href="#cards" data-section-link>Contact cards</a></li>
                <li><a class="segmented__item" href="#form-section" data-section-link>Form section</a></li>
                <li><a class="segmented__item" href="#map" data-section-link>Map</a></li>
            </ul>
        </nav>

        <form method="POST" action="{{ route('admin.contact-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            @include('admin.partials.page-banner-card')

            <x-admin.card id="cards" title="Contact cards" description="Three cards under the banner. Address, main phone and main email come from Site settings › Contact; add a second number or email here if needed.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="cards[address_title]" label="Address card title" :value="$cards['address_title']" required maxlength="40" />
                        <x-admin.form.input name="cards[phone_title]" label="Phone card title" :value="$cards['phone_title']" required maxlength="40" />
                        <x-admin.form.input name="cards[email_title]" label="Email card title" :value="$cards['email_title']" required maxlength="40" />
                        <x-admin.form.input name="cards[extra_phone]" label="Second phone number (optional)" :value="$cards['extra_phone']" maxlength="30" />
                        <x-admin.form.input name="cards[extra_email]" type="email" label="Second email address (optional)" :value="$cards['extra_email']" maxlength="191" />
                    </div>
                    <p class="mb-0"><a href="{{ route('admin.settings.edit') }}#contact">Edit address, phone and email in Site settings</a></p>
                </div>
            </x-admin.card>

            <x-admin.card id="form-section" title="Form section" description="Text and photo on the left, contact form on the right. Messages arrive in Inbox › Messages.">
                <div class="form-stack">
                    <x-admin.form.input name="form[heading]" label="Left heading" :value="$form['heading']" required maxlength="80" />
                    <x-admin.form.textarea name="form[text]" label="Left text" :value="$form['text']" maxlength="500" counter rows="3" />
                    <x-admin.form.image-upload name="form_image" label="Photo (optional)" :current-url="$form['image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_form_image" hint="Shown under the left text, e.g. your factory or products. Landscape works best. Up to {{ $maxMb }} MB." />
                    <div class="form-grid">
                        <x-admin.form.input name="form[form_heading]" label="Form heading" :value="$form['form_heading']" required maxlength="80" />
                        <x-admin.form.input name="form[button_label]" label="Button text" :value="$form['button_label']" required maxlength="30" />
                    </div>
                    <x-admin.form.input name="form[form_note]" label="Note under the form heading" :value="$form['form_note']" maxlength="200" />
                </div>
            </x-admin.card>

            <x-admin.card id="map" title="Map" description="Google Maps with your location marker, shown above the footer.">
                <div class="form-stack">
                    <x-admin.form.textarea name="map[embed_url]" label="Google Maps embed" :value="$map['embed_url']" rows="3" hint="On Google Maps, find your location › Share › Embed a map › Copy HTML, and paste it here (the whole code or just the link). Leave empty to hide the map." />
                    @if ($map['embed_url'])
                        <iframe class="map-preview" src="{{ $map['embed_url'] }}" title="Map preview" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save Contact Us page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

@php
    use App\Enums\HomeSection;
    use App\Http\Requests\Admin\UpdateHomePageRequest as Rules;

    $maxMb = Rules::IMAGE_MAX_KILOBYTES / 1024;
    $points = array_pad(old('about.points', $about['points'] ?? []), HomeSection::MAX_ABOUT_POINTS, '');
@endphp

<x-admin.layout title="Home page">
    <x-admin.page-header title="Home page" description="Text and images of the home page sections. Contact details in the header come from Site settings.">
        <x-admin.button :href="route('home')" variant="secondary" icon="globe" target="_blank" rel="noopener">View home page</x-admin.button>
    </x-admin.page-header>

    <div class="settings">
        <nav class="segmented" aria-label="Home page sections">
            <ul class="segmented__list">
                <li><a class="segmented__item" href="#banner" data-section-link>Banner</a></li>
                <li><a class="segmented__item" href="#about" data-section-link>About us</a></li>
            </ul>
        </nav>

        <form method="POST" action="{{ route('admin.home-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            <x-admin.card id="banner" title="Banner" description="The large first section with the background photo.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="hero[eyebrow]" label="Small text above the heading" :value="$hero['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="hero[watermark]" label="Faint background word" :value="$hero['watermark']" maxlength="20" hint="Large outlined word behind the heading. Leave empty to hide." />
                    </div>
                    <x-admin.form.input name="hero[heading]" label="Heading" :value="$hero['heading']" required maxlength="120" counter />
                    <x-admin.form.textarea name="hero[text]" label="Text" :value="$hero['text']" maxlength="400" counter rows="3" />
                    <div class="form-grid">
                        <x-admin.form.input name="hero[button_label]" label="Button text" :value="$hero['button_label']" maxlength="40" />
                        <x-admin.form.input name="hero[button_url]" label="Button link" :value="$hero['button_url']" maxlength="255" hint="A page path such as /products, or a full https:// link." />
                    </div>
                    <x-admin.form.image-upload name="hero_background_image" label="Background photo" :current-url="$hero['background_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_hero_background_image" hint="Wide landscape photo, at least 1920 × 900 px. JPG, PNG or WebP up to {{ $maxMb }} MB." />
                    <p class="panel__note">The phone number next to the button comes from Site settings › Contact.</p>
                </div>
            </x-admin.card>

            <x-admin.card id="about" title="About us" description="The section right after the banner.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="about[eyebrow]" label="Small text above the heading" :value="$about['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="about[heading]" label="Heading" :value="$about['heading']" required maxlength="120" />
                    </div>
                    <x-admin.form.textarea name="about[intro]" label="Introduction" :value="$about['intro']" maxlength="600" counter rows="3" />
                    <x-admin.form.input name="about[subheading]" label="Bold line" :value="$about['subheading']" maxlength="160" />

                    <fieldset class="fieldset">
                        <legend class="field__label">Check-list points</legend>
                        <div class="form-stack form-stack--tight">
                            @foreach ($points as $index => $point)
                                <x-admin.form.input name="about[points][{{ $index }}]" label="Point {{ $index + 1 }}" :value="$point" maxlength="120" />
                            @endforeach
                        </div>
                        <p class="field__hint">Empty points are not shown.</p>
                    </fieldset>

                    <div class="form-grid">
                        <x-admin.form.input name="about[badge_number]" label="Badge number" :value="$about['badge_number']" maxlength="10" hint="e.g. the founding year 1999." />
                        <x-admin.form.input name="about[badge_label]" label="Badge label" :value="$about['badge_label']" maxlength="20" />
                        <x-admin.form.input name="about[person_name]" label="Person name" :value="$about['person_name']" maxlength="80" hint="Shown when no signature image is uploaded." />
                        <x-admin.form.input name="about[person_role]" label="Person role" :value="$about['person_role']" maxlength="80" />
                        <x-admin.form.input name="about[button_label]" label="Button text" :value="$about['button_label']" maxlength="40" />
                        <x-admin.form.input name="about[button_url]" label="Button link" :value="$about['button_url']" maxlength="255" />
                    </div>

                    <x-admin.form.image-upload name="about_image_main" label="Main photo (tall)" :current-url="$about['image_main_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_main" hint="Portrait, about 620 × 740 px." />
                    <x-admin.form.image-upload name="about_image_top" label="Small photo (top right)" :current-url="$about['image_top_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_top" hint="About 330 × 360 px." />
                    <x-admin.form.image-upload name="about_image_bottom" label="Photo (bottom)" :current-url="$about['image_bottom_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_bottom" hint="Landscape, about 500 × 420 px." />
                    <x-admin.form.image-upload name="about_person_photo" label="Person photo" :current-url="$about['person_photo_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_person_photo" hint="Square, at least 120 × 120 px." />
                    <x-admin.form.image-upload name="about_signature_image" label="Signature" :current-url="$about['signature_image_url']" accept="image/png,image/webp" remove-name="remove_about_signature_image" hint="PNG with transparent background." />
                    <x-admin.form.image-upload name="about_decoration_image" label="Side image (bottom right)" :current-url="$about['decoration_image_url']" accept="image/png,image/webp" remove-name="remove_about_decoration_image" hint="Optional cut-out product photo with transparent background (PNG). Shown on large screens." />
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live home page as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save home page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

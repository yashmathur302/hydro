@php
    use App\Http\Requests\Admin\UpdateAboutPageRequest as Rules;

    $maxMb = Rules::IMAGE_MAX_KILOBYTES / 1024;
@endphp

<x-admin.layout title="About Us page">
    <x-slot:header>
        <x-admin.page-header title="About Us page" description="Text and images of the About Us page. The header and footer are shared with the whole website.">
            <x-admin.button :href="route('about')" variant="secondary" icon="globe" target="_blank" rel="noopener">View page</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            <x-admin.card id="banner" title="Banner" description="The photo strip under the header with the page title.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="banner[eyebrow]" label="Small text above the title" :value="$banner['eyebrow']" maxlength="80" hint="Shown in orange. Leave empty to hide." />
                        <x-admin.form.input name="banner[title]" label="Page title" :value="$banner['title']" required maxlength="80" />
                    </div>
                    <x-admin.form.image-upload name="banner_background_image" label="Background photo" :current-url="$banner['background_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_banner_background_image" hint="Wide landscape photo, at least 1920 × 500 px. JPG, PNG or WebP up to {{ $maxMb }} MB. A dark overlay keeps the title readable." />
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save About Us page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

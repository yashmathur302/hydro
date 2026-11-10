{{-- Banner fields shared by the inner page editors. Expects $banner (section content). --}}
@php($maxMb = \App\Http\Requests\Admin\UpdateAboutPageRequest::IMAGE_MAX_KILOBYTES / 1024)

<x-admin.card id="banner" title="Banner" description="The photo strip under the header with the page title.">
    <div class="form-stack">
        <div class="form-grid">
            <x-admin.form.input name="banner[eyebrow]" label="Small text above the title" :value="$banner['eyebrow']" maxlength="80" hint="Shown in orange. Leave empty to hide." />
            <x-admin.form.input name="banner[title]" label="Page title" :value="$banner['title']" required maxlength="80" />
        </div>
        <x-admin.form.image-upload name="banner_background_image" label="Background photo" :current-url="$banner['background_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_banner_background_image" hint="Wide landscape photo, at least 1920 × 500 px. JPG, PNG or WebP up to {{ $maxMb }} MB. A dark overlay keeps the title readable." />
    </div>
</x-admin.card>

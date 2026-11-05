@php($isEdit = $industry->exists)

<x-admin.layout :title="$isEdit ? 'Edit industry' : 'Add industry'">
    <x-admin.page-header :title="$isEdit ? $industry->name : 'Add industry'">
        <x-admin.button :href="route('admin.industries.index')" variant="secondary">Back to industries</x-admin.button>
    </x-admin.page-header>

    <form method="POST" action="{{ $isEdit ? route('admin.industries.update', $industry) : route('admin.industries.store') }}" enctype="multipart/form-data" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Details">
                <div class="form-stack">
                    <x-admin.form.input name="name" label="Industry name" :value="$industry->name" required maxlength="100" />
                    <x-admin.form.textarea name="description" label="Short text" :value="$industry->description" maxlength="300" counter rows="3" hint="One or two short lines." />
                    <x-admin.form.select name="icon" label="Icon" :options="\App\Enums\FeatureIcon::options()" :value="$industry->icon?->value" required />
                    <div class="icon-preview" aria-hidden="true">
                        @foreach (\App\Enums\FeatureIcon::cases() as $icon)
                            <span class="icon-preview__item" title="{{ $icon->label() }}"><x-site.feature-icon :name="$icon" /><span>{{ $icon->label() }}</span></span>
                        @endforeach
                    </div>
                    <x-admin.form.image-upload name="image" label="Photo (tall strip on the right of the card)" :current-url="$industry->imageUrl()" accept="image/jpeg,image/png,image/webp" remove-name="remove_image" hint="Portrait photo, about 250 × 790 px. Converted to WebP automatically." />
                </div>
            </x-admin.card>
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <div class="form-stack">
                    <x-admin.form.toggle name="is_active" label="Show on website" :checked="$industry->is_active" />
                    <x-admin.form.input name="sort_order" type="number" label="Sort order" :value="$industry->sort_order ?? 0" min="0" max="100000" hint="Lower numbers appear first." />
                </div>
                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save industry' : 'Add industry' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </form>
</x-admin.layout>

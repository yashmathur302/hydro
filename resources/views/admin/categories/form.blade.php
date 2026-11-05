@php($isEdit = $category->exists)

<x-admin.layout :title="$isEdit ? 'Edit category' : 'Add category'">
    <x-admin.page-header :title="$isEdit ? $category->name : 'Add category'">
        <x-admin.button :href="route('admin.categories.index')" variant="secondary">Back to categories</x-admin.button>
    </x-admin.page-header>

    <form method="POST" action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Details">
                <div class="form-stack">
                    <x-admin.form.input name="name" label="Category name" :value="$category->name" required maxlength="150" />
                    <x-admin.form.textarea name="description" label="Description" :value="$category->description" maxlength="2000" counter rows="4" hint="Shown at the top of the category page." />
                    <x-admin.form.image-upload name="image" label="Image" :current-url="$category->image_path ? Storage::disk('public')->url($category->image_path) : null" accept="image/jpeg,image/png,image/webp" remove-name="remove_image" hint="JPG, PNG or WebP, up to 5 MB. Converted to WebP automatically." />
                </div>
            </x-admin.card>

            <x-admin.seo-fields :model="$category" :url-prefix="url('/products/category').'/'" />
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <div class="form-stack">
                    <x-admin.form.toggle name="is_active" label="Show on website" :checked="$category->is_active" />
                    <x-admin.form.input name="sort_order" type="number" label="Sort order" :value="$category->sort_order ?? 0" min="0" max="100000" hint="Lower numbers appear first." />
                </div>

                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save category' : 'Create category' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </form>
</x-admin.layout>

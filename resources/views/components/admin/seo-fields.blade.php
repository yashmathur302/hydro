{{-- Slug + SEO fields shared by products, categories and pages. --}}
@props(['model', 'urlPrefix', 'description' => 'Leave empty to use the name and the default SEO settings.'])

<x-admin.card title="Search engine (SEO)" :$description>
    <div class="form-stack">
        <x-admin.form.input name="slug" label="URL slug" :value="$model->slug" maxlength="170" spellcheck="false" autocomplete="off"
            hint="{{ $urlPrefix }}{{ $model->slug ?: 'generated-from-the-name' }}" />
        <x-admin.form.input name="meta_title" label="Meta title" :value="$model->meta_title" maxlength="120" counter hint="Aim for 50–60 characters." />
        <x-admin.form.textarea name="meta_description" label="Meta description" :value="$model->meta_description" maxlength="300" counter hint="Aim for 140–160 characters." />
        <x-admin.form.input name="meta_keywords" label="Meta keywords" :value="$model->meta_keywords" maxlength="255" hint="Separate keywords with commas." />
    </div>
</x-admin.card>

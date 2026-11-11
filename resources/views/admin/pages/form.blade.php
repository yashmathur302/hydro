@php($isEdit = $page->exists)

<x-admin.layout :title="$isEdit ? 'Edit page' : 'Add page'">
    <x-slot:header>
        <x-admin.page-header :title="$isEdit ? $page->title : 'Add page'">
            <x-admin.button :href="route('admin.pages.index')" variant="secondary">Back to pages</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <form method="POST" action="{{ $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Content">
                <div class="form-stack">
                    <x-admin.form.input name="title" label="Page title" :value="$page->title" required maxlength="150" />
                    <x-admin.form.markdown-editor name="content" label="Page content" :value="$page->content" rows="20" maxlength="100000" />
                </div>
            </x-admin.card>

            <x-admin.seo-fields :model="$page" :url-prefix="url('/').'/'" description="Leave empty to use the page title and the default SEO settings." />
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <x-admin.form.toggle name="is_active" label="Published" :checked="$page->is_active" hint="Drafts are not visible on the website." />

                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save page' : 'Create page' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </form>
</x-admin.layout>

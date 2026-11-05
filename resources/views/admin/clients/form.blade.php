@php($isEdit = $client->exists)

<x-admin.layout :title="$isEdit ? 'Edit client' : 'Add client'">
    <x-admin.page-header :title="$isEdit ? $client->name : 'Add client'">
        <x-admin.button :href="route('admin.clients.index')" variant="secondary">Back to clients</x-admin.button>
    </x-admin.page-header>

    <form method="POST" action="{{ $isEdit ? route('admin.clients.update', $client) : route('admin.clients.store') }}" enctype="multipart/form-data" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Details">
                <div class="form-stack">
                    <x-admin.form.input name="name" label="Client name" :value="$client->name" required maxlength="100" hint="Used as the logo's alternative text for screen readers and search engines." />
                    <x-admin.form.image-upload name="logo" label="Logo" :current-url="$isEdit ? $client->logoUrl() : null" accept="image/png,image/jpeg,image/webp" hint="PNG with transparent background works best, up to {{ \App\Http\Requests\Admin\SaveClientRequest::LOGO_MAX_KILOBYTES / 1024 }} MB." />
                    <x-admin.form.input name="website_url" type="url" label="Website (optional)" :value="$client->website_url" maxlength="255" placeholder="https://" hint="If filled in, the logo links to this website (opens in a new tab)." />
                </div>
            </x-admin.card>
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <div class="form-stack">
                    <x-admin.form.toggle name="is_active" label="Show on website" :checked="$client->is_active" />
                    <x-admin.form.input name="sort_order" type="number" label="Sort order" :value="$client->sort_order ?? 0" min="0" max="100000" hint="Lower numbers appear first." />
                </div>
                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save client' : 'Add client' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </form>
</x-admin.layout>

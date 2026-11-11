<x-admin.layout title="Products page">
    <x-slot:header>
        <x-admin.page-header title="Products page" description="The banner and the heading above the product grid. The products themselves are managed in Products.">
            <x-admin.button :href="route('products.index')" variant="secondary" icon="globe" target="_blank" rel="noopener">View page</x-admin.button>
            <x-admin.button :href="route('admin.products.index')" variant="secondary">Back to products</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <form method="POST" action="{{ route('admin.products-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            @include('admin.partials.page-banner-card')

            <x-admin.card id="intro" title="Heading and introduction" description="Shown centred above the product cards.">
                <div class="form-stack">
                    <x-admin.form.input name="intro[heading]" label="Heading" :value="$intro['heading']" required maxlength="120" />
                    <x-admin.form.textarea name="intro[text]" label="Introduction" :value="$intro['text']" maxlength="600" counter rows="4" hint="Leave empty to hide." />
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save Products page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

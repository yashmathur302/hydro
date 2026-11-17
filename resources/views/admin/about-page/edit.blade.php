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

            @include('admin.partials.page-banner-card')

            <x-admin.card title="Other sections" description="Shown on this page with the same content as the home page.">
                <ul class="mb-0 ps-3 d-grid gap-2">
                    <li>About us, Why choose us and Counters: edit them in <a href="{{ route('admin.home-page.edit') }}">Home page</a>.</li>
                    <li>Client logos: edit them in <a href="{{ route('admin.clients.index') }}">Clients</a>.</li>
                </ul>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save About Us page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

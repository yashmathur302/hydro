<x-admin.layout title="Certificates page">
    <x-slot:header>
        <x-admin.page-header title="Certificates page" description="The banner at the top of the Certificates page. The certificates themselves are managed in Certificates.">
            <x-admin.button :href="route('admin.certificates.index')" variant="secondary">Back to certificates</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <form method="POST" action="{{ route('admin.certificates-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            @include('admin.partials.page-banner-card')

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save banner</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>

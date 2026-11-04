{{-- Sidebar wordmark: the site name, shortened to its initial when the sidebar is collapsed. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
@endphp

<a href="{{ route('admin.dashboard') }}" {{ $attributes->merge(['class' => 'brand']) }} aria-label="{{ $siteName }} admin dashboard">
    <span class="brand__initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($siteName, 0, 1)) }}</span>
    <span class="brand__name">{{ $siteName }}</span>
</a>

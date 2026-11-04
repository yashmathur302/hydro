{{-- Admin panel brand mark: logo when uploaded, otherwise the site initial. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
@endphp

<a href="{{ route('admin.dashboard') }}" {{ $attributes->merge(['class' => 'brand']) }}>
    <span class="brand__mark" aria-hidden="true">{{ mb_strtoupper(mb_substr($siteName, 0, 1)) }}</span>
    <span class="brand__text">
        <span class="brand__name">{{ $siteName }}</span>
        <span class="brand__label">Admin panel</span>
    </span>
</a>

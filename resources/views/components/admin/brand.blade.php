{{--
    Sidebar / mobile-bar brand: the logo from Site settings, or the site name
    until a logo is uploaded. In the collapsed sidebar it shrinks to the
    favicon, or the first letter of the name.
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
    $logoUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::LogoPath);
    $faviconUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::FaviconPath);
@endphp

<a href="{{ route('admin.dashboard') }}" {{ $attributes->merge(['class' => 'brand']) }} aria-label="{{ $siteName }} admin dashboard">
    <span @class(['brand__initial', 'brand__initial--image' => $faviconUrl]) aria-hidden="true">
        @if ($faviconUrl)
            <img src="{{ $faviconUrl }}" alt="" width="36" height="36">
        @else
            {{ mb_strtoupper(mb_substr($siteName, 0, 1)) }}
        @endif
    </span>
    @if ($logoUrl)
        <img class="brand__logo" src="{{ $logoUrl }}" alt="{{ $siteName }}">
    @else
        <span class="brand__name">{{ $siteName }}</span>
    @endif
</a>

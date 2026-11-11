{{-- Brand link used in the sidebar, the top bar and the sign-in page: logo from Site settings, or the site name. --}}
@props(['href' => null])

@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
    $logoUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::LogoPath);
    $faviconUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::FaviconPath);
@endphp

<a href="{{ $href ?? route('admin.dashboard') }}" {{ $attributes->merge(['class' => 'navbar-brand']) }} aria-label="{{ $siteName }}">
    <div class="logo-main">
        <div class="logo-normal">
            @if ($logoUrl)
                <img class="brand-logo" src="{{ $logoUrl }}" alt="{{ $siteName }}">
            @else
                <span class="brand-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($siteName, 0, 1)) }}</span>
            @endif
        </div>
        <div class="logo-mini">
            @if ($faviconUrl)
                <img class="brand-logo brand-logo--mini" src="{{ $faviconUrl }}" alt="">
            @else
                <span class="brand-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($siteName, 0, 1)) }}</span>
            @endif
        </div>
    </div>
    @unless ($logoUrl)
        <h4 class="logo-title">{{ $siteName }}</h4>
    @endunless
</a>

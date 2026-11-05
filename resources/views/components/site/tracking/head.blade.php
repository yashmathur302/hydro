{{--
    Google Analytics 4 and Meta Pixel base code, generated from the IDs saved in
    Site settings. IDs are validated on save and JSON-encoded here, so no
    admin-supplied script ever reaches the page.
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $ga4Id = $siteSettings->get(\App\Enums\SiteSettingKey::Ga4MeasurementId);
    $pixelId = $siteSettings->get(\App\Enums\SiteSettingKey::FacebookPixelId);
    $nonce = \Illuminate\Support\Facades\Vite::cspNonce();
@endphp

@if ($ga4Id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($ga4Id) }}" nonce="{{ $nonce }}"></script>
    <script nonce="{{ $nonce }}">
        window.dataLayer = window.dataLayer || [];
        function gtag() { window.dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', @js($ga4Id));
    </script>
@endif

@if ($pixelId)
    <script nonce="{{ $nonce }}">
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', @js($pixelId));
        fbq('track', 'PageView');
    </script>
@endif

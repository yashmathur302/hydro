{{-- Meta Pixel fallback for visitors with JavaScript disabled. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@if ($pixelId = $siteSettings->get(\App\Enums\SiteSettingKey::FacebookPixelId))
    <noscript>
        <img height="1" width="1" class="tracking-pixel" alt="" src="https://www.facebook.com/tr?id={{ urlencode($pixelId) }}&amp;ev=PageView&amp;noscript=1">
    </noscript>
@endif

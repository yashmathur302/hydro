{{--
    Public website shell. Every page passes a SeoMeta object built with
    App\Support\Seo\SeoMetaFactory.
--}}
@props(['seo'])

@inject('siteSettings', 'App\Services\SiteSettingService')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-site.seo-meta :seo="$seo" />
    @if ($faviconUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::FaviconPath))
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif
    @vite(['resources/css/site.css', 'resources/js/site.js'])
    @stack('head')
    <x-site.tracking.head />
</head>
<body>
    <x-site.tracking.body />
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <x-site.header />

    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <x-site.footer />

    <a class="back-to-top" href="#main-content" data-back-to-top aria-label="Back to top">
        <x-site.icon name="arrow-up" />
    </a>
</body>
</html>

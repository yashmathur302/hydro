{{--
    Public website shell. Every page passes a SeoMeta object built with
    App\Support\Seo\SeoMetaFactory. Header and footer are added once the
    page designs are shared.
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

    <main id="main-content">
        {{ $slot }}
    </main>
</body>
</html>

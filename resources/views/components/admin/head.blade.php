{{-- Shared <head> contents for every admin page. --}}
@props(['title'])

@inject('siteSettings', 'App\Services\SiteSettingService')

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>{{ $title }} · {{ $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name')) }} Admin</title>
@if ($faviconUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::FaviconPath))
    <link rel="icon" href="{{ $faviconUrl }}">
@endif
@vite(['resources/scss/admin.scss', 'resources/js/admin.js'])

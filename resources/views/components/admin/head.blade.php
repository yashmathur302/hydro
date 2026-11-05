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
{{-- Restore the collapsed sidebar before first paint so the layout does not jump. --}}
<script nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}">
    try {
        if (localStorage.getItem('admin.sidebar') === 'collapsed') {
            document.documentElement.classList.add('is-sidebar-collapsed');
        }
    } catch (error) {}
</script>
@vite(['resources/css/admin.css', 'resources/js/admin.js'])

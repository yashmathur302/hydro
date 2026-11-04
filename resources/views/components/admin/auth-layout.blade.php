{{-- Split-screen layout for login and password reset pages. --}}
@props(['title', 'heading', 'intro' => null])

@inject('siteSettings', 'App\Services\SiteSettingService')

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body class="admin admin--auth">
    <div class="auth">
        <aside class="auth__aside" aria-hidden="true">
            <div class="auth__aside-inner">
                <span class="brand__mark brand__mark--large">{{ mb_strtoupper(mb_substr($siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name')), 0, 1)) }}</span>
                <p class="auth__aside-title">{{ $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name')) }}</p>
                <p class="auth__aside-text">Manage products, pages, enquiries and site settings from one place.</p>
            </div>
            <div class="auth__grid"></div>
        </aside>

        <main class="auth__main">
            <div class="auth__panel">
                <div class="auth__lock"><x-admin.icon name="lock" /></div>
                <h1 class="auth__heading">{{ $heading }}</h1>
                @if ($intro)
                    <p class="auth__intro">{{ $intro }}</p>
                @endif

                <x-admin.flash />

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>

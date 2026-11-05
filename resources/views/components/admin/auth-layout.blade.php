{{-- Centered layout for login and password reset pages. --}}
@props(['title', 'heading', 'intro' => null])

@inject('siteSettings', 'App\Services\SiteSettingService')

@php($siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name')))

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body class="admin admin--auth">
    <main class="auth">
        <div class="auth__frame">
            <section class="auth__panel">
                <p class="auth__brand">{{ $siteName }}</p>

                <h1 class="auth__heading">{{ $heading }}</h1>
                @if ($intro)
                    <p class="auth__intro">{{ $intro }}</p>
                @endif

                <x-admin.flash />

                {{ $slot }}
            </section>

            <aside class="auth__aside" aria-hidden="true">
                <div class="auth__art">
                    @include('admin.partials.pigging-illustration')
                </div>
                <p class="auth__aside-title">Your website, managed in one calm place.</p>
                <p class="auth__aside-text">{{ $siteName }} admin panel</p>
            </aside>
        </div>
    </main>
</body>
</html>

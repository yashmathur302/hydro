{{-- Centered layout for login and password reset pages. --}}
@props(['title', 'heading', 'intro' => null])

@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
    $logoUrl = $siteSettings->fileUrl(\App\Enums\SiteSettingKey::LogoPath);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body class="admin admin--auth">
    <main class="auth">
        <div class="auth__frame">
            <section class="auth__panel">
                <div class="auth__brand">
                    @if ($logoUrl)
                        <img class="auth__logo" src="{{ $logoUrl }}" alt="{{ $siteName }}">
                    @else
                        <p class="auth__brand-name">{{ $siteName }}</p>
                    @endif
                </div>

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
            </aside>
        </div>
    </main>
</body>
</html>

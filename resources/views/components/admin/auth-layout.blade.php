{{--
    Sign-in style pages (login, forgot / reset password): the form on the left,
    and on wide screens a pipeline-pigging illustration on the right.
--}}
@props(['title', 'heading', 'intro' => null])

@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body>
    <main class="auth">
        <section class="auth__panel">
            <div class="auth__form">
                <x-admin.brand :href="route('admin.login')" class="auth__brand" />

                <h1 class="auth__heading">{{ $heading }}</h1>
                @if ($intro)
                    <p class="auth__intro">{{ $intro }}</p>
                @endif

                <x-admin.flash />

                {{ $slot }}
            </div>

            <p class="auth__footer">&copy; {{ now()->year }} {{ $siteName }}</p>
        </section>

        <aside class="auth__aside" aria-hidden="true">
            @include('admin.partials.pigging-illustration')
        </aside>
    </main>
</body>
</html>

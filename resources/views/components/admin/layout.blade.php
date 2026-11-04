{{-- Admin application shell: light sidebar with navigation and account card, white workspace. --}}
@props(['title'])

@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $admin = auth()->user();
    $siteName = $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body class="admin">
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <div class="shell">
        <aside class="sidebar" id="admin-sidebar" data-sidebar aria-label="Admin navigation">
            <div class="sidebar__top">
                <x-admin.brand />
                <button type="button" class="round-button sidebar__collapse" data-sidebar-collapse aria-controls="admin-sidebar" aria-expanded="true" aria-label="Collapse sidebar">
                    <x-admin.icon name="chevrons-left" />
                </button>
                <button type="button" class="round-button sidebar__close" data-sidebar-close aria-label="Close navigation">
                    <x-admin.icon name="close" />
                </button>
            </div>

            <a class="site-card" href="{{ route('home') }}" target="_blank" rel="noopener" title="View website">
                <span class="site-card__icon"><x-admin.icon name="globe" /></span>
                <span class="site-card__text">
                    <span class="site-card__name">{{ $siteName }}</span>
                    <span class="site-card__url">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
                </span>
                <x-admin.icon name="arrow-up-right" class="site-card__arrow" />
            </a>

            <nav class="sidebar__nav">
                <p class="sidebar__heading" id="nav-menu">Menu</p>
                <ul class="nav-list" aria-labelledby="nav-menu">
                    <x-admin.nav-link :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
                </ul>

                <p class="sidebar__heading" id="nav-config">Configuration</p>
                <ul class="nav-list" aria-labelledby="nav-config">
                    <x-admin.nav-link :href="route('admin.settings.edit')" icon="settings" :active="request()->routeIs('admin.settings.*')">Site settings</x-admin.nav-link>
                </ul>
            </nav>

            <div class="sidebar__account">
                <a class="account-card" href="{{ route('admin.account.edit') }}" title="My account" @if (request()->routeIs('admin.account.*')) aria-current="page" @endif>
                    <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($admin->name, 0, 1)) }}</span>
                    <span class="account-card__text">
                        <span class="account-card__name">{{ $admin->name }}</span>
                        <span class="account-card__email">{{ $admin->email }}</span>
                    </span>
                </a>
                <ul class="nav-list">
                    <x-admin.nav-link :href="route('admin.account.edit')" icon="user" :active="request()->routeIs('admin.account.*')">My account</x-admin.nav-link>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="nav-link" title="Log out">
                                <x-admin.icon name="logout" />
                                <span class="nav-link__label">Log out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="sidebar-backdrop" data-sidebar-close hidden></div>

        <div class="shell__main">
            <header class="mobile-bar">
                <button type="button" class="round-button" data-sidebar-open aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open navigation">
                    <x-admin.icon name="menu" />
                </button>
                <x-admin.brand />
                <span class="avatar avatar--small" aria-hidden="true">{{ mb_strtoupper(mb_substr($admin->name, 0, 1)) }}</span>
            </header>

            <main class="content" id="main-content" tabindex="-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

{{-- Admin application shell: sidebar navigation, top bar and main content area. --}}
@props(['title'])

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body class="admin">
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <div class="shell" data-shell>
        <aside class="sidebar" id="admin-sidebar" data-sidebar aria-label="Admin navigation">
            <div class="sidebar__top">
                <x-admin.brand />
                <button type="button" class="icon-button sidebar__close" data-sidebar-close aria-label="Close navigation">
                    <x-admin.icon name="close" />
                </button>
            </div>

            <nav class="sidebar__nav">
                <p class="sidebar__heading" id="nav-main">Overview</p>
                <ul class="nav-list" aria-labelledby="nav-main">
                    <x-admin.nav-link :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
                </ul>

                <p class="sidebar__heading" id="nav-config">Configuration</p>
                <ul class="nav-list" aria-labelledby="nav-config">
                    <x-admin.nav-link :href="route('admin.settings.edit')" icon="settings" :active="request()->routeIs('admin.settings.*')">Site settings</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.account.edit')" icon="user" :active="request()->routeIs('admin.account.*')">My account</x-admin.nav-link>
                </ul>
            </nav>

            <div class="sidebar__footer">
                <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener">
                    <x-admin.icon name="external" />
                    <span>View website</span>
                </a>
            </div>
        </aside>

        <div class="sidebar-backdrop" data-sidebar-close hidden></div>

        <div class="shell__main">
            <header class="topbar">
                <button type="button" class="icon-button topbar__menu" data-sidebar-open aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open navigation">
                    <x-admin.icon name="menu" />
                </button>

                <div class="topbar__user">
                    <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="topbar__name">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="button button--ghost button--small">
                            <x-admin.icon name="logout" />
                            <span>Log out</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="content" id="main-content" tabindex="-1">
                <x-admin.flash />
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

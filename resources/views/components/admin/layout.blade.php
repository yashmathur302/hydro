{{--
    Admin application shell in the Hope UI layout: sidebar, top bar with the user
    menu, a header banner (page title from the "header" slot) and the content area.
--}}
@props(['title'])

@php
    $admin = auth()->user();
    $nonce = \Illuminate\Support\Facades\Vite::cspNonce();
    $enquiryType = request()->routeIs('admin.enquiries.show') ? request()->route('enquiry')?->type : null;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <x-admin.head :title="$title" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all" id="admin-sidebar" data-sidebar aria-label="Admin navigation">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <x-admin.brand />
            <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-controls="admin-sidebar" aria-expanded="true" aria-label="Collapse menu">
                <i class="icon"><x-admin.icon name="chevrons-left" class="icon-20" /></i>
            </button>
        </div>
        <div class="sidebar-body pt-0">
            <div class="sidebar-list">
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    <x-admin.nav-heading>Home</x-admin.nav-heading>
                    <x-admin.nav-link tone="blue" :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>

                    <li><hr class="hr-horizontal"></li>
                    <x-admin.nav-heading>Catalog</x-admin.nav-heading>
                    <x-admin.nav-link tone="orange" :href="route('admin.products.index')" icon="box" :active="request()->routeIs('admin.products.*', 'admin.products-page.*')">Products</x-admin.nav-link>
                    <x-admin.nav-link tone="lime" :href="route('admin.categories.index')" icon="folder" :active="request()->routeIs('admin.categories.*')">Categories</x-admin.nav-link>

                    <li><hr class="hr-horizontal"></li>
                    <x-admin.nav-heading>Inbox</x-admin.nav-heading>
                    <x-admin.nav-link tone="green" :href="route('admin.enquiries.index')" icon="inbox" :count="$unreadCounts['product']" :active="request()->routeIs('admin.enquiries.index') || $enquiryType === \App\Enums\EnquiryType::Product">Enquiries</x-admin.nav-link>
                    <x-admin.nav-link tone="cyan" :href="route('admin.contact-messages.index')" icon="mail" :count="$unreadCounts['contact']" :active="request()->routeIs('admin.contact-messages.*') || $enquiryType === \App\Enums\EnquiryType::Contact">Messages</x-admin.nav-link>

                    <li><hr class="hr-horizontal"></li>
                    <x-admin.nav-heading>Content</x-admin.nav-heading>
                    <x-admin.nav-link tone="indigo" :href="route('admin.layout.edit')" icon="list" :active="request()->routeIs('admin.layout.*')">Header & Footer</x-admin.nav-link>
                    <x-admin.nav-link tone="violet" :href="route('admin.home-page.edit')" icon="home" :active="request()->routeIs('admin.home-page.*')">Home page</x-admin.nav-link>
                    <x-admin.nav-link tone="sky" :href="route('admin.about-page.edit')" icon="info" :active="request()->routeIs('admin.about-page.*')">About Us page</x-admin.nav-link>
                    <x-admin.nav-link tone="teal" :href="route('admin.contact-page.edit')" icon="phone" :active="request()->routeIs('admin.contact-page.*')">Contact Us page</x-admin.nav-link>
                    <x-admin.nav-link tone="red" :href="route('admin.industries.index')" icon="factory" :active="request()->routeIs('admin.industries.*')">Industries</x-admin.nav-link>
                    <x-admin.nav-link tone="purple" :href="route('admin.clients.index')" icon="handshake" :active="request()->routeIs('admin.clients.*')">Clients</x-admin.nav-link>
                    <x-admin.nav-link tone="amber" :href="route('admin.certificates.index')" icon="award" :active="request()->routeIs('admin.certificates.*', 'admin.certificates-page.*')">Certificates</x-admin.nav-link>
                    <x-admin.nav-link tone="pink" :href="route('admin.pages.index')" icon="file" :active="request()->routeIs('admin.pages.*')">Static pages</x-admin.nav-link>
                    <x-admin.nav-link tone="slate" :href="route('admin.settings.edit')" icon="settings" :active="request()->routeIs('admin.settings.*')">Site settings</x-admin.nav-link>
                </ul>
            </div>
        </div>
        {{-- Restores the collapsed menu before the page is painted (no layout jump). Kept inside the aside: the template's CSS needs .main-content to follow the sidebar directly. --}}
        <script nonce="{{ $nonce }}">
            (function () {
                var sidebar = document.getElementById('admin-sidebar');
                var small = window.matchMedia('(max-width: 1199.98px)').matches;
                var collapsed = false;
                try { collapsed = localStorage.getItem('admin.sidebar') === 'collapsed'; } catch (error) {}
                if (small || collapsed) { sidebar.classList.add('sidebar-mini'); }
            })();
        </script>
    </aside>
    <main class="main-content" id="main-content" tabindex="-1">
        <div class="position-relative iq-banner">
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar" aria-label="Account">
                <div class="container-fluid navbar-inner">
                    <x-admin.brand class="d-xl-none" />
                    <button type="button" class="sidebar-toggle d-xl-none" data-sidebar-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open menu">
                        <i class="icon"><x-admin.icon name="menu" class="icon-20" /></i>
                    </button>

                    <ul class="navbar-nav ms-auto align-items-center navbar-list flex-row gap-2">
                        <li class="nav-item dropdown" data-dropdown>
                            <button type="button" class="py-0 nav-link d-flex align-items-center bg-transparent border-0 account-toggle" data-dropdown-toggle aria-expanded="false" aria-haspopup="true">
                                <span class="visually-hidden">Account menu:</span>
                                <span class="avatar avatar-50 avatar-rounded avatar-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($admin->name, 0, 1)) }}</span>
                                <span class="caption ms-3 d-none d-md-block text-start">
                                    <span class="mb-0 caption-title d-block">{{ $admin->name }}</span>
                                    <span class="mb-0 caption-sub-title d-block">Administrator</span>
                                </span>
                                <x-admin.icon name="chevron-down" class="icon-18 ms-2 account-toggle__arrow" />
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" data-dropdown-menu>
                                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank" rel="noopener">View website</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.account.edit') }}">My account</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}">Site settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Log out</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="iq-navbar-header">
                <div class="container-fluid iq-container">
                    {{ $header ?? '' }}
                </div>
                <div class="iq-header-img" aria-hidden="true"></div>
            </div>
        </div>

        <div class="container-fluid content-inner pb-4">
            <x-admin.flash />
            {{ $slot }}
        </div>

        <footer class="footer">
            <div class="footer-body">
                <span>© {{ now()->year }} {{ config('app.name') }}</span>
                <span>Admin panel</span>
            </div>
        </footer>
    </main>

    <div class="sidebar-backdrop" data-sidebar-close hidden></div>
    <x-admin.confirm-dialog />
</body>
</html>

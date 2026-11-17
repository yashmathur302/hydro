{{--
    Site header: contact top bar (desktop) and main bar with logo, menu, phone,
    search and the "Get a quote" button. On small screens the menu becomes a drawer.
    Contact details and social links come from Site settings; the menu from
    Header & Footer (LayoutSection::Menu).
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')
@inject('pageSections', 'App\Services\PageSectionService')

@php
    use App\Enums\SiteSettingKey as Key;

    $siteName = $siteSettings->get(Key::SiteName, config('app.name'));
    $logoUrl = $siteSettings->fileUrl(Key::LogoPath);
    $email = $siteSettings->get(Key::ContactEmail);
    $phone = $siteSettings->get(Key::ContactPhone);
    $phoneHref = $phone ? 'tel:'.preg_replace('/[^0-9+]/', '', $phone) : null;

    $socialLinks = $siteSettings->socialLinks();

    $menu = $pageSections->section(\App\Enums\LayoutSection::Menu)['items'];
@endphp

<header class="site-header" data-site-header>
    <div class="topbar">
        <div class="container topbar__inner">
            <ul class="topbar__contacts">
                @if ($email)
                    <li><a class="topbar__link" href="mailto:{{ $email }}"><x-site.icon name="mail" />{{ $email }}</a></li>
                @endif
                @if ($phone)
                    <li><a class="topbar__link" href="{{ $phoneHref }}"><x-site.icon name="phone" />{{ $phone }}</a></li>
                @endif
            </ul>

            @if ($socialLinks !== [])
                <div class="topbar__social">
                    <span>Follow Us :</span>
                    <x-site.social-links />
                </div>
            @endif
        </div>
    </div>

    <div class="mainbar">
        <div class="container mainbar__inner">
            <a class="site-logo" href="{{ route('home') }}" aria-label="{{ $siteName }} home">
                @if ($logoUrl)
                    <img class="site-logo__image" src="{{ $logoUrl }}" alt="{{ $siteName }}">
                @else
                    <span class="site-logo__mark" aria-hidden="true"></span>
                    <span class="site-logo__name">{{ $siteName }}</span>
                @endif
            </a>

            <nav class="site-nav" id="site-nav" aria-label="Main" data-site-nav>
                <div class="site-nav__head">
                    <span class="site-nav__title">Menu</span>
                    <button type="button" class="site-nav__close" data-site-nav-close aria-label="Close menu">
                        <x-site.icon name="close" />
                    </button>
                </div>
                <ul class="site-nav__list">
                    @foreach ($menu as $item)
                        @php($isCurrent = \App\Support\SiteLink::isCurrent($item['url']))
                        <li>
                            <a @class(['site-nav__link', 'is-current' => $isCurrent]) href="{{ \App\Support\SiteLink::href($item['url']) }}" @if ($isCurrent) aria-current="page" @endif @if (\App\Support\SiteLink::isExternal($item['url'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
                @if ($phone)
                    <a class="site-nav__phone" href="{{ $phoneHref }}"><x-site.icon name="phone" />{{ $phone }}</a>
                @endif
                <a class="btn btn--accent site-nav__cta" href="{{ route('contact') }}">Get A Quote</a>
            </nav>

            <div class="mainbar__tools">
                <a class="header-search" href="{{ route('products.index') }}" aria-label="Search products">
                    <x-site.icon name="search" />
                </a>
                <button type="button" class="nav-toggle" data-site-nav-open aria-controls="site-nav" aria-expanded="false" aria-label="Open menu">
                    <x-site.icon name="menu" />
                </button>
            </div>
        </div>

        {{-- Inside .mainbar so it shares the menu drawer's stacking context and sits beneath it. --}}
        <div class="site-nav-backdrop" data-site-nav-close hidden></div>

        <a class="header-cta" href="{{ route('contact') }}">
            <span>Get A Quote</span>
            <x-site.icon name="arrow-circle" />
        </a>
    </div>
</header>

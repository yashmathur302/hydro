{{--
    Site header: contact top bar (desktop) and main bar with logo, menu, phone,
    search and the "Get a quote" button. On small screens the menu becomes a drawer.
    All contact details and links come from Site settings.
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    use App\Enums\SiteSettingKey as Key;

    $siteName = $siteSettings->get(Key::SiteName, config('app.name'));
    $logoUrl = $siteSettings->fileUrl(Key::LogoPath);
    $email = $siteSettings->get(Key::ContactEmail);
    $phone = $siteSettings->get(Key::ContactPhone);
    $phoneHref = $phone ? 'tel:'.preg_replace('/[^0-9+]/', '', $phone) : null;

    $socialLinks = array_filter([
        'facebook' => ['label' => 'Facebook', 'url' => $siteSettings->get(Key::FacebookUrl)],
        'linkedin' => ['label' => 'LinkedIn', 'url' => $siteSettings->get(Key::LinkedinUrl)],
        'x' => ['label' => 'X (Twitter)', 'url' => $siteSettings->get(Key::XUrl)],
        'youtube' => ['label' => 'YouTube', 'url' => $siteSettings->get(Key::YoutubeUrl)],
        'instagram' => ['label' => 'Instagram', 'url' => $siteSettings->get(Key::InstagramUrl)],
    ], static fn (array $link): bool => $link['url'] !== null);

    $menu = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About Us', 'route' => 'about'],
        ['label' => 'Products', 'route' => 'products.index'],
        ['label' => 'Certificates', 'route' => 'certificates'],
        ['label' => 'Contact Us', 'route' => 'contact'],
    ];
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
                    <ul class="social-list">
                        @foreach ($socialLinks as $icon => $link)
                            <li>
                                <a class="social-list__link" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link['label'] }}">
                                    <x-site.icon :name="$icon" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="mainbar">
        <div class="container mainbar__inner">
            <a class="site-logo" href="{{ route('home') }}" aria-label="{{ $siteName }} home">
                @if ($logoUrl)
                    <img class="site-logo__image" src="{{ $logoUrl }}" alt="" aria-hidden="true">
                @else
                    <span class="site-logo__mark" aria-hidden="true"></span>
                @endif
                <span class="site-logo__name">{{ $siteName }}</span>
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
                        @php($isCurrent = request()->routeIs($item['route'] === 'products.index' ? 'products.*' : $item['route']))
                        <li>
                            <a @class(['site-nav__link', 'is-current' => $isCurrent]) href="{{ route($item['route']) }}" @if ($isCurrent) aria-current="page" @endif>{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
                @if ($phone)
                    <a class="site-nav__phone" href="{{ $phoneHref }}"><x-site.icon name="phone" />{{ $phone }}</a>
                @endif
                <a class="btn btn--accent site-nav__cta" href="{{ route('contact') }}">Get A Quote</a>
            </nav>

            <div class="mainbar__tools">
                @if ($phone)
                    <a class="header-phone" href="{{ $phoneHref }}">
                        <span class="header-phone__icon" aria-hidden="true"><x-site.icon name="phone-solid" /></span>
                        <span class="header-phone__text">
                            <span class="header-phone__label">Phone:</span>
                            <span class="header-phone__number">{{ $phone }}</span>
                        </span>
                    </a>
                @endif
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

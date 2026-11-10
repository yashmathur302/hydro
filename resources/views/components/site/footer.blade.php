{{--
    Site footer: call-to-action box overlapping the top edge, four columns
    (logo and text, menu links, useful links = active static pages, contact
    details with social icons) and the copyright line.
    Texts and images come from Header & Footer (LayoutSection); contact
    details, logo and social links from Site settings.
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')
@inject('pageSections', 'App\Services\PageSectionService')

@php
    use App\Enums\LayoutSection;
    use App\Enums\SiteSettingKey as Key;
    use App\Support\SiteLink;

    $cta = $pageSections->section(LayoutSection::FooterCta);
    $footer = $pageSections->section(LayoutSection::Footer);
    $menu = $pageSections->section(LayoutSection::Menu)['items'];
    $usefulPages = \App\Models\Page::query()->active()->orderBy('title')->get(['title', 'slug']);

    $siteName = $siteSettings->get(Key::SiteName, config('app.name'));
    $logoUrl = $footer['logo_url'] ?? $siteSettings->fileUrl(Key::LogoPath);
    $phone = $siteSettings->get(Key::ContactPhone);
    $phoneHref = $phone ? 'tel:'.preg_replace('/[^0-9+]/', '', $phone) : null;
    $email = $siteSettings->get(Key::ContactEmail);
    $address = $siteSettings->get(Key::ContactAddress);
    $whatsapp = $siteSettings->get(Key::WhatsappNumber);
    $copyright = str_replace(LayoutSection::YEAR_PLACEHOLDER, (string) now()->year, (string) $footer['copyright']);
@endphp

<footer class="site-footer">
    <div class="container">
        <div @class(['footer-cta', 'footer-cta--has-image' => $cta['background_image_url']])>
            @if ($cta['background_image_url'])
                <img class="footer-cta__image" src="{{ $cta['background_image_url'] }}" alt="" loading="lazy" decoding="async">
            @endif

            <div class="footer-cta__content">
                @if ($cta['eyebrow'])
                    <p class="eyebrow footer-cta__eyebrow"><x-site.icon name="bolt" />{{ $cta['eyebrow'] }}</p>
                @endif
                <h2 class="footer-cta__heading">{{ $cta['heading'] }}</h2>
                @if ($cta['button_label'] && $cta['button_url'])
                    <a class="btn btn--accent footer-cta__button" href="{{ SiteLink::href($cta['button_url']) }}">{{ $cta['button_label'] }}</a>
                @endif
            </div>

            @if ($phone || $email)
                <ul class="footer-cta__contacts">
                    @if ($phone)
                        <li>
                            <a class="contact-pill" href="{{ $phoneHref }}">
                                <span class="contact-pill__icon" aria-hidden="true"><x-site.icon name="phone" /></span>
                                <span class="contact-pill__text">
                                    <span class="contact-pill__label">Call For Inquiry</span>
                                    <span class="contact-pill__value">{{ $phone }}</span>
                                </span>
                            </a>
                        </li>
                    @endif
                    @if ($email)
                        <li>
                            <a class="contact-pill" href="mailto:{{ $email }}">
                                <span class="contact-pill__icon" aria-hidden="true"><x-site.icon name="mail" /></span>
                                <span class="contact-pill__text">
                                    <span class="contact-pill__label">Send Us Email</span>
                                    <span class="contact-pill__value">{{ $email }}</span>
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>

    <div class="site-footer__main">
        <div class="container footer-grid">
            <div class="footer-col footer-col--about">
                <a class="footer-logo" href="{{ route('home') }}" aria-label="{{ $siteName }} home">
                    @if ($logoUrl)
                        <img class="footer-logo__image" src="{{ $logoUrl }}" alt="{{ $siteName }}" loading="lazy">
                    @else
                        <span class="footer-logo__name">{{ $siteName }}</span>
                    @endif
                </a>
                @if ($footer['description'])
                    <p class="footer-col__text">{{ $footer['description'] }}</p>
                @endif
            </div>

            <nav class="footer-col" aria-labelledby="footer-menu-heading">
                <h2 class="footer-title" id="footer-menu-heading">{{ $footer['menu_heading'] ?: 'Menu Links' }}</h2>
                <ul class="footer-links">
                    @foreach ($menu as $item)
                        <li>
                            <a href="{{ SiteLink::href($item['url']) }}" @if (SiteLink::isExternal($item['url'])) target="_blank" rel="noopener" @endif>{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            @if ($usefulPages->isNotEmpty())
                <nav class="footer-col" aria-labelledby="footer-links-heading">
                    <h2 class="footer-title" id="footer-links-heading">{{ $footer['links_heading'] ?: 'Useful Links' }}</h2>
                    <ul class="footer-links">
                        @foreach ($usefulPages as $page)
                            <li><a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="footer-col footer-col--contact">
                <h2 class="footer-title">{{ $footer['contact_heading'] ?: 'Contact Us' }}</h2>
                <ul class="footer-contact">
                    @if ($address)
                        <li><x-site.icon name="map-pin" /><span>{{ $address }}</span></li>
                    @endif
                    @if ($phone)
                        <li><x-site.icon name="phone" /><a href="{{ $phoneHref }}">{{ $phone }}</a></li>
                    @endif
                    @if ($email)
                        <li><x-site.icon name="mail" /><a href="mailto:{{ $email }}">{{ $email }}</a></li>
                    @endif
                    @if ($whatsapp)
                        <li><x-site.icon name="whatsapp" /><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp us</a></li>
                    @endif
                </ul>
                <x-site.social-links class="social-list--footer" />
            </div>
        </div>
    </div>

    @if ($copyright !== '')
        <div class="site-footer__bottom">
            <div class="container">
                <p class="site-footer__copyright">{{ $copyright }}</p>
            </div>
        </div>
    @endif
</footer>

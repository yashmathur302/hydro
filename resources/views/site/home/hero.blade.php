{{-- Home banner. $hero comes from PageSectionService; the phone number from Site settings. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    $phone = $siteSettings->get(\App\Enums\SiteSettingKey::ContactPhone);
@endphp

<section @class(['hero', 'hero--no-image' => ! $hero['background_image_url']]) aria-labelledby="hero-heading">
    @if ($hero['background_image_url'])
        <img class="hero__image" src="{{ $hero['background_image_url'] }}" alt="" fetchpriority="high" decoding="async">
    @endif

    <div class="container hero__inner">
        @if ($hero['watermark'])
            <span class="hero__watermark" aria-hidden="true">{{ $hero['watermark'] }}</span>
        @endif

        <div class="hero__content">
            @if ($hero['eyebrow'])
                <p class="hero__eyebrow">{{ $hero['eyebrow'] }}</p>
            @endif
            <h1 class="hero__title" id="hero-heading">{{ $hero['heading'] }}</h1>
            @if ($hero['text'])
                <p class="hero__text">{{ $hero['text'] }}</p>
            @endif

            <div class="hero__actions">
                @if ($hero['button_label'] && $hero['button_url'])
                    <a class="btn btn--accent" href="{{ \App\Support\SiteLink::href($hero['button_url']) }}">{{ $hero['button_label'] }}</a>
                @endif
                @if ($phone)
                    <a class="call-block" href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                        <span class="call-block__icon" aria-hidden="true"><x-site.icon name="phone-solid" /></span>
                        <span class="call-block__text">
                            <span class="call-block__label">Call Us Now</span>
                            <span class="call-block__number">{{ $phone }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

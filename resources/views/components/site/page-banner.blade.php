{{--
    Banner under the header on inner pages (About Us, Products, ...): a
    background photo behind a dark overlay, a small orange line and the page
    title. Without a photo it falls back to the brand colour.
--}}
@props(['title', 'eyebrow' => null, 'imageUrl' => null])

<section @class(['page-banner', 'page-banner--no-image' => ! $imageUrl]) aria-labelledby="page-title">
    @if ($imageUrl)
        <img class="page-banner__image" src="{{ $imageUrl }}" alt="" fetchpriority="high" decoding="async">
    @endif

    <div class="container page-banner__inner">
        @if ($eyebrow)
            <p class="page-banner__eyebrow">{{ $eyebrow }}</p>
        @endif
        <h1 class="page-banner__title" id="page-title">{{ $title }}</h1>
    </div>
</section>

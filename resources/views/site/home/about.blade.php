{{-- Home "About us" block. $about comes from PageSectionService. --}}
@php
    $photos = [
        'main' => $about['image_main_url'],
        'top' => $about['image_top_url'],
        'bottom' => $about['image_bottom_url'],
    ];
@endphp

<section class="about section home-band" aria-labelledby="about-heading">
    <div class="container about__inner">
        <div class="about__media">
            <div class="collage">
                @foreach ($photos as $position => $url)
                    <figure class="collage__photo collage__photo--{{ $position }}">
                        @if ($url)
                            <img src="{{ $url }}" alt="" loading="lazy" decoding="async">
                        @else
                            <span class="photo-placeholder"><x-site.icon name="image" /></span>
                        @endif
                    </figure>
                @endforeach

                @if ($about['badge_number'])
                    <p class="collage__badge">
                        <span class="collage__badge-number">{{ $about['badge_number'] }}</span>
                        @if ($about['badge_label'])
                            <span class="collage__badge-label">{{ $about['badge_label'] }}</span>
                        @endif
                    </p>
                @endif
                <span class="collage__dots" aria-hidden="true"></span>
            </div>
        </div>

        <div class="about__content">
            @if ($about['eyebrow'])
                <p class="eyebrow"><x-site.icon name="bolt" />{{ $about['eyebrow'] }}</p>
            @endif
            <h2 class="section-title" id="about-heading">{{ $about['heading'] }}</h2>

            @if ($about['intro'])
                <p class="about__intro">{{ $about['intro'] }}</p>
            @endif
            @if ($about['subheading'])
                <p class="about__subheading">{{ $about['subheading'] }}</p>
            @endif

            @if ($about['points'] !== [])
                <ul class="check-list">
                    @foreach ($about['points'] as $point)
                        <li class="check-list__item"><x-site.icon name="check-circle" />{{ $point }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="about__footer">
                @if ($about['person_name'] || $about['signature_image_url'])
                    <div class="author">
                        <span class="author__photo">
                            @if ($about['person_photo_url'])
                                <img src="{{ $about['person_photo_url'] }}" alt="" width="54" height="54" loading="lazy">
                            @else
                                {{ mb_strtoupper(mb_substr((string) $about['person_name'], 0, 1)) }}
                            @endif
                        </span>
                        <span class="author__text">
                            @if ($about['signature_image_url'])
                                <img class="author__signature" src="{{ $about['signature_image_url'] }}" alt="{{ $about['person_name'] }}" loading="lazy">
                            @else
                                <span class="author__name">{{ $about['person_name'] }}</span>
                            @endif
                            @if ($about['person_role'])
                                <span class="author__role">{{ $about['person_role'] }}</span>
                            @endif
                        </span>
                    </div>
                @endif

                @if (($showButton ?? true) && $about['button_label'] && $about['button_url'])
                    <a class="btn btn--dark" href="{{ $about['button_url'] }}">{{ $about['button_label'] }}</a>
                @endif
            </div>
        </div>
    </div>

    @if ($about['decoration_image_url'])
        <img class="about__decoration" src="{{ $about['decoration_image_url'] }}" alt="" loading="lazy" decoding="async">
    @endif
</section>

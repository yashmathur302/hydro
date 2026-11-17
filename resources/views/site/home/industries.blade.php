{{-- "Industries we serve": one card per active industry. Hidden when there are none. --}}
@if ($industries->isNotEmpty())
    <section class="industries section home-band" aria-labelledby="industries-heading">
        <div class="container">
            <header class="section-head">
                @if ($industriesSection['eyebrow'])
                    <p class="eyebrow"><x-site.icon name="bolt" />{{ $industriesSection['eyebrow'] }}</p>
                @endif
                <h2 class="section-title" id="industries-heading">{{ $industriesSection['heading'] }}</h2>
            </header>

            <ul class="industry-grid">
                @foreach ($industries as $industry)
                    <li class="industry-card">
                        <div class="industry-card__body">
                            <x-site.feature-icon :name="$industry->icon" class="industry-card__icon" />
                            <p class="industry-card__number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="industry-card__title">{{ $industry->name }}</h3>
                            @if ($industry->description)
                                <p class="industry-card__text">{{ $industry->description }}</p>
                            @endif
                        </div>
                        <div class="industry-card__media">
                            @if ($industry->imageUrl())
                                <img src="{{ $industry->imageUrl() }}" alt="" loading="lazy" decoding="async">
                            @else
                                <span class="photo-placeholder"><x-site.icon name="image" /></span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif

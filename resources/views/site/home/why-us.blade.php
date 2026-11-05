{{-- "Why choose us": centred heading and icon cards. --}}
@if (($whyUs['items'] ?? []) !== [])
    <section class="why-us section home-band" aria-labelledby="why-us-heading">
        <div class="container">
            <header class="section-head section-head--center">
                @if ($whyUs['eyebrow'])
                    <p class="eyebrow"><x-site.icon name="bolt" />{{ $whyUs['eyebrow'] }}</p>
                @endif
                <h2 class="section-title" id="why-us-heading">{{ $whyUs['heading'] }}</h2>
            </header>

            <ul class="feature-grid">
                @foreach ($whyUs['items'] as $item)
                    <li class="feature-card">
                        @if (\App\Enums\FeatureIcon::tryFrom($item['icon'] ?? ''))
                            <x-site.feature-icon :name="$item['icon']" class="feature-card__icon" />
                        @endif
                        <h3 class="feature-card__title">{{ $item['title'] }}</h3>
                    </li>
                @endforeach
            </ul>

            @if ($whyUs['footer_text'])
                <p class="why-us__footer">
                    {{ $whyUs['footer_text'] }}
                    @if ($whyUs['link_label'] && $whyUs['link_url'])
                        <a class="why-us__link" href="{{ $whyUs['link_url'] }}">{{ $whyUs['link_label'] }}<span class="why-us__plus" aria-hidden="true">+</span></a>
                    @endif
                </p>
            @endif
        </div>
    </section>
@endif

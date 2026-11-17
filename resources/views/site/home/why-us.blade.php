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
        </div>
    </section>
@endif

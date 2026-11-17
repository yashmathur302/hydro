{{-- Certificates: the first few active certificates; hidden when there are none. --}}
@if ($certificates->isNotEmpty())
    <section class="certificates section home-band" aria-labelledby="certificates-heading">
        <div class="container">
            <header class="section-head section-head--center">
                @if ($certificatesSection['eyebrow'])
                    <p class="eyebrow"><x-site.icon name="bolt" />{{ $certificatesSection['eyebrow'] }}</p>
                @endif
                <h2 class="section-title" id="certificates-heading">{{ $certificatesSection['heading'] }}</h2>
            </header>

            <ul class="certificate-grid">
                @foreach ($certificates as $certificate)
                    <li><x-site.certificate-card :certificate="$certificate" /></li>
                @endforeach
            </ul>

            @if ($certificatesSection['link_label'])
                <p class="certificates__more">
                    <a class="btn btn--accent" href="{{ route('certificates') }}">{{ $certificatesSection['link_label'] }}</a>
                </p>
            @endif
        </div>
    </section>
@endif

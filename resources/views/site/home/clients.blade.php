{{-- Client logos. Hidden when there are none. --}}
@if ($clients->isNotEmpty())
    <section class="clients" aria-label="Our clients">
        <div class="container">
            <ul class="client-logos">
                @foreach ($clients as $client)
                    <li class="client-logos__item">
                        @if ($client->website_url)
                            <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer nofollow">
                                <img src="{{ $client->logoUrl() }}" alt="{{ $client->name }}" loading="lazy" decoding="async">
                            </a>
                        @else
                            <img src="{{ $client->logoUrl() }}" alt="{{ $client->name }}" loading="lazy" decoding="async">
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif

{{--
    Counters: big word filled with a photo, subtitle and counter pills.
    Numbers are rendered in full (correct without JavaScript); site/counters.js
    counts them up when they scroll into view.
--}}
@php
    $items = $stats['items'] ?? [];
@endphp

@if ($stats['word'] || $items !== [])
    <section class="stats section home-band" aria-label="Key figures">
        <div class="container stats__inner">
            @if ($stats['word'])
                @if ($stats['word_image_url'])
                    {{-- Our own storage URL, set in a nonce'd <style> in the head so the CSP needs no inline-style exception. --}}
                    @push('head')
                        <style nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}">.stats__word--image{background-image:url("{{ $stats['word_image_url'] }}")}</style>
                    @endpush
                @endif
                <p @class(['stats__word', 'stats__word--image' => $stats['word_image_url']]) aria-hidden="true">{{ $stats['word'] }}</p>
            @endif
            @if ($stats['subtitle'])
                <h2 class="stats__subtitle">{{ $stats['subtitle'] }}</h2>
            @endif

            @if ($items !== [])
                <ul class="stats__list">
                    @foreach ($items as $item)
                        <li class="stat-pill">
                            <span class="stat-pill__value" data-count-up>{{ $item['value'] }}</span>
                            @if ($item['label'])
                                <span class="stat-pill__label">{{ $item['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endif

{{-- Content card with an optional heading; renders as a <section> when titled. --}}
@props(['title' => null, 'description' => null])

@php($headingId = $title ? 'card-'.Str::slug($title) : null)

<section {{ $attributes->merge(['class' => 'card']) }} @if ($headingId) aria-labelledby="{{ $headingId }}" @endif>
    @if ($title)
        <header class="card__header">
            <h2 class="card__title" id="{{ $headingId }}">{{ $title }}</h2>
            @if ($description)
                <p class="card__description">{{ $description }}</p>
            @endif
        </header>
    @endif
    <div class="card__body">
        {{ $slot }}
    </div>
    @isset($footer)
        <footer class="card__footer">{{ $footer }}</footer>
    @endisset
</section>

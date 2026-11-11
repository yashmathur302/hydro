{{--
    Hope UI card: optional header (title, description, actions slot), body and footer slot.
    "flush" removes the body padding for tables and lists that run edge to edge.
--}}
@props(['title' => null, 'description' => null, 'flush' => false])

@php($headingId = $title ? 'card-'.Str::slug($title) : null)

<section {{ $attributes->merge(['class' => 'card']) }} @if ($headingId) aria-labelledby="{{ $headingId }}" @endif>
    @if ($title)
        <div class="card-header d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div class="header-title">
                <h2 class="card-title h4" id="{{ $headingId }}">{{ $title }}</h2>
                @if ($description)
                    <p class="mb-0 mt-1 text-secondary small">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex-shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div @class(['card-body', 'p-0' => $flush])>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer d-flex flex-wrap gap-2 justify-content-end">{{ $footer }}</div>
    @endisset
</section>

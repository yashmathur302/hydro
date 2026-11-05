{{--
    Content panel: heading (and optional actions) above a bordered box.
    Slots: default (box content), "actions" (beside the heading), "footer" (inside the box, bottom).
--}}
@props(['title' => null, 'description' => null, 'flush' => false])

@php($headingId = $title ? 'panel-'.Str::slug($title) : null)

<section {{ $attributes->merge(['class' => 'panel']) }} @if ($headingId) aria-labelledby="{{ $headingId }}" @endif>
    @if ($title)
        <header class="panel__header">
            <div>
                <h2 class="panel__title" id="{{ $headingId }}">{{ $title }}</h2>
                @if ($description)
                    <p class="panel__description">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="panel__actions">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div @class(['panel__box', 'panel__box--flush' => $flush])>
        {{ $slot }}

        @isset($footer)
            <footer class="panel__footer">{{ $footer }}</footer>
        @endisset
    </div>
</section>

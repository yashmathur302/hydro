{{-- Button or link styled as a button. Pass "href" to render a link. --}}
@props(['variant' => 'primary', 'type' => 'button', 'href' => null, 'icon' => null, 'block' => false])

@php
    $classes = ['button', 'button--'.$variant, 'button--block' => $block];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" />@endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" />@endif
        <span>{{ $slot }}</span>
    </button>
@endif

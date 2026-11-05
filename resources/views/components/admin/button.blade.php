{{-- Button or link styled as a button. Pass "href" to render a link. Variants: primary, secondary, ghost. --}}
@props(['variant' => 'primary', 'type' => 'button', 'href' => null, 'icon' => null, 'iconEnd' => null, 'size' => null, 'block' => false])

@php
    $classes = ['button', 'button--'.$variant, 'button--'.$size => $size, 'button--block' => $block];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" />@endif
        <span>{{ $slot }}</span>
        @if ($iconEnd)<x-admin.icon :name="$iconEnd" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" />@endif
        <span>{{ $slot }}</span>
        @if ($iconEnd)<x-admin.icon :name="$iconEnd" />@endif
    </button>
@endif

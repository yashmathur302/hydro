{{-- Bootstrap button or button-styled link. Variants: primary, secondary, ghost, danger. Pass "href" for a link. --}}
@props(['variant' => 'primary', 'type' => 'button', 'href' => null, 'icon' => null, 'iconEnd' => null, 'size' => null, 'block' => false])

@php
    $variantClass = match ($variant) {
        'secondary' => 'btn-outline-primary',
        'ghost' => 'btn-link',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
    $classes = ['btn', $variantClass, 'btn-sm' => $size === 'small', 'w-100' => $block, 'd-inline-flex align-items-center justify-content-center gap-2'];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" class="icon-18" />@endif
        <span>{{ $slot }}</span>
        @if ($iconEnd)<x-admin.icon :name="$iconEnd" class="icon-18" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)<x-admin.icon :name="$icon" class="icon-18" />@endif
        <span>{{ $slot }}</span>
        @if ($iconEnd)<x-admin.icon :name="$iconEnd" class="icon-18" />@endif
    </button>
@endif

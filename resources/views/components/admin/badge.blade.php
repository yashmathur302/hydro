{{-- Status badge. Variants map to Hope UI soft colours. --}}
@props(['variant' => 'neutral'])

@php
    $variantClass = match ($variant) {
        'success' => 'bg-soft-success text-success',
        'info' => 'bg-soft-primary text-primary',
        'warning' => 'bg-soft-warning text-warning',
        'danger' => 'bg-soft-danger text-danger',
        default => 'bg-soft-secondary text-secondary',
    };
@endphp

<span {{ $attributes->class(['badge', $variantClass]) }}>{{ $slot }}</span>

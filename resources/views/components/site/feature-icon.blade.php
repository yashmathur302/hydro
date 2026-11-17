{{--
    Feature / industry icons (see App\Enums\FeatureIcon). Original line drawings,
    48 × 48 grid, drawn in the current text colour. Decorative: aria-hidden.
--}}
@props(['name'])

@php
    $icon = $name instanceof \App\Enums\FeatureIcon ? $name->value : (string) $name;
@endphp

<svg {{ $attributes->merge(['class' => 'feature-icon']) }} viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($icon)
        @case('oil-gas')
            <path d="M24 5L13 42M24 5l11 37"/><path d="M16.3 30h15.4M18.6 21.5h10.8M21 13h6"/><path d="M16.3 30L29.4 21.5M18.6 21.5L27 13"/><path d="M7 42h34"/><path d="M24 5V2.5"/>
            @break
        @case('water')
            <path d="M24 5s-12 13.5-12 23a12 12 0 0 0 24 0C36 18.5 24 5 24 5z"/><path d="M18.5 29a5.5 5.5 0 0 0 5.5 5.5"/>
            @break
        @case('pipeline')
            <path d="M4 19h16v10H4M44 19H28v10h16"/><rect x="20" y="14" width="8" height="20" rx="1.5"/><path d="M24 14v20"/><path d="M10 40h26m-4-3.5l4 3.5-4 3.5"/>
            @break
        @case('refinery')
            <path d="M5 42V25l9 5v-5l9 5v-5l9 5V9h8v33z"/><path d="M5 42h38"/><path d="M11 36h3M18 36h3M25 36h3"/><path d="M34 9V5M38 9V6"/>
            @break
        @case('power')
            <circle cx="24" cy="24" r="19"/><path d="M26.5 10L16 26.5h8.5L22 38l10.5-16.5H24z"/>
            @break
        @case('chemical')
            <path d="M18.5 5h11M21 5v13L10.2 37.6A3.3 3.3 0 0 0 13.1 42.5h21.8a3.3 3.3 0 0 0 2.9-4.9L27 18V5"/><path d="M14.5 31h19"/><circle cx="21" cy="36.5" r="1.2"/><circle cx="27.5" cy="35" r="1.2"/>
            @break
        @case('gauge')
            <circle cx="24" cy="22" r="17"/><path d="M24 22l8-8"/><circle cx="24" cy="22" r="2.2"/><path d="M13.5 30.5l2.5-2M10 22h3.5M13.5 13.5l2.5 2M24 8.5V12M34.5 13.5l-2.5 2M38 22h-3.5"/><path d="M20 39v5h8v-5"/>
            @break
        @case('quality')
            <path d="M24 4l15 6v12c0 10-6.5 17-15 21-8.5-4-15-11-15-21V10z"/><path d="M17 23.5l5 5 9-10"/>
            @break
        @case('delivery')
            <rect x="4" y="12" width="24" height="20" rx="1.5"/><path d="M28 19h8l6 7.5V32H28"/><circle cx="13" cy="35" r="4"/><circle cx="35" cy="35" r="4"/><path d="M10 19h10M10 25h6"/>
            @break
        @case('support')
            <path d="M10 28v-5a14 14 0 0 1 28 0v5"/><rect x="6" y="26" width="7" height="11" rx="3"/><rect x="35" y="26" width="7" height="11" rx="3"/><path d="M38.5 37v1.5a5 5 0 0 1-5 5H27"/><circle cx="25" cy="43.5" r="2"/>
            @break
        @case('certificate')
            <rect x="5" y="7" width="38" height="27" rx="2"/><path d="M12 15h24M12 21h14"/><circle cx="33" cy="29" r="6"/><path d="M29.5 34l-2 9 5.5-3 5.5 3-2-9"/>
            @break
        @case('tools')
            <path d="M30.5 5.5a9 9 0 0 0-8.6 11.8L6.4 32.8a3.6 3.6 0 0 0 5.1 5.1L27 22.4A9 9 0 0 0 38.9 13l-5.3 5.3-4.6-1.3-1.3-4.6L33 7a9 9 0 0 0-2.5-1.5z"/>
            @break
        @case('custom')
            <path d="M7 14h34M7 24h34M7 34h34"/><circle cx="16" cy="14" r="4" fill="#fff"/><circle cx="31" cy="24" r="4" fill="#fff"/><circle cx="21" cy="34" r="4" fill="#fff"/>
            @break
        @case('global')
            <circle cx="24" cy="24" r="19"/><ellipse cx="24" cy="24" rx="8" ry="19"/><path d="M5 24h38M8.5 14h31M8.5 34h31"/>
            @break
        @case('experience')
            <circle cx="24" cy="30" r="12"/><path d="M24 23.5l2 4.1 4.5.6-3.3 3.1.8 4.5-4-2.1-4 2.1.8-4.5-3.3-3.1 4.5-.6z"/><path d="M17.5 20L12 5h8l4.5 10M30.5 20L36 5h-8l-2.2 5"/>
            @break
        @case('team')
            <circle cx="18" cy="16" r="6"/><path d="M5.5 40c0-7.2 5.6-12.5 12.5-12.5S30.5 32.8 30.5 40"/><circle cx="33.5" cy="17.5" r="5"/><path d="M32 27.5c6 0 10.5 4.5 10.5 11"/>
            @break
    @endswitch
</svg>

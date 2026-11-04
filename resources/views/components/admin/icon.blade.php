{{-- Inline stroke icons for the admin panel. Decorative by default (aria-hidden). --}}
@props(['name'])

<svg {{ $attributes->merge(['class' => 'icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('dashboard')
            <rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>
            @break
        @case('settings')
            <path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>
            @break
        @case('user')
            <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>
            @break
        @case('logout')
            <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3"/><path d="M10 17l-5-5 5-5"/><path d="M5 12h11"/>
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break
        @case('close')
            <path d="M6 6l12 12M18 6L6 18"/>
            @break
        @case('check')
            <path d="M5 12.5l4.5 4.5L19 7.5"/>
            @break
        @case('circle')
            <circle cx="12" cy="12" r="8"/>
            @break
        @case('eye')
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
            @break
        @case('eye-off')
            <path d="M3 3l18 18"/><path d="M10.6 5.1A10.5 10.5 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.2M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
            @break
        @case('external')
            <path d="M14 4h6v6"/><path d="M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
            @break
        @case('alert')
            <circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5M12 16.5v.01"/>
            @break
        @case('success')
            <circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16 9.5"/>
            @break
        @case('lock')
            <rect x="4.5" y="10.5" width="15" height="10" rx="2"/><path d="M8 10.5V7a4 4 0 0 1 8 0v3.5"/>
            @break
        @case('arrow-right')
            <path d="M5 12h14M13 6l6 6-6 6"/>
            @break
        @case('globe')
            <circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18a14 14 0 0 1 0-18z"/>
            @break
        @case('chevrons-left')
            <path d="M11 17l-5-5 5-5"/><path d="M18 17l-5-5 5-5"/>
            @break
        @case('chevron-down')
            <path d="M6 9l6 6 6-6"/>
            @break
        @case('arrow-up-right')
            <path d="M7 17L17 7"/><path d="M8 7h9v9"/>
            @break
        @case('sparkle')
            <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M19 16l.7 1.8 1.8.7-1.8.7L19 21l-.7-1.8-1.8-.7 1.8-.7z"/>
            @break
    @endswitch
</svg>

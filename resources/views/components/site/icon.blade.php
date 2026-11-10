{{-- Icons for the public website. Decorative (aria-hidden); give the parent an accessible name. --}}
@props(['name'])

@php
    $filled = in_array($name, ['facebook', 'linkedin', 'x', 'youtube', 'instagram', 'bolt', 'phone-solid'], true);
@endphp

<svg {{ $attributes->merge(['class' => 'site-icon']) }} viewBox="0 0 24 24" @if ($filled) fill="currentColor" @else fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" @endif aria-hidden="true" focusable="false">
    @switch($name)
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 6.5l8.5 6 8.5-6"/>
            @break
        @case('phone')
            <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/>
            @break
        @case('phone-solid')
            <path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/>
            @break
        @case('search')
            <circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>
            @break
        @case('arrow-circle')
            <circle cx="12" cy="12" r="9"/><path d="M8 12h8M13 9l3 3-3 3"/>
            @break
        @case('arrow-up')
            <path d="M12 19V5M6 11l6-6 6 6"/>
            @break
        @case('map-pin')
            <path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11z"/><circle cx="12" cy="10" r="2.4"/>
            @break
        @case('whatsapp')
            <path d="M12 3.2a8.8 8.8 0 0 0-7.6 13.2L3.2 20.8l4.5-1.2A8.8 8.8 0 1 0 12 3.2z"/><path d="M9 8.5c.3-.6.6-.6.9-.6h.5c.2 0 .4 0 .6.5l.7 1.7c.1.2 0 .4-.1.6l-.5.6c-.1.2-.2.3 0 .6a6.4 6.4 0 0 0 3 2.6c.3.1.4.1.6-.1l.7-.8c.2-.2.4-.2.6-.1l1.6.8c.2.1.4.2.4.4 0 .5-.2 1.3-.9 1.7-.6.4-1.4.6-2.6.2a9.2 9.2 0 0 1-4.9-4.2c-.8-1.4-.8-2.6-.1-3.5z" fill="currentColor" stroke="none"/>
            @break
        @case('external')
            <path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break
        @case('close')
            <path d="M6 6l12 12M18 6L6 18"/>
            @break
        @case('check-circle')
            <circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5"/>
            @break
        @case('bolt')
            <path d="M13.5 2L4 13.5h6.5L9 22l11-12.5h-6.7z"/>
            @break
        @case('image')
            <rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 16l-5-5-9 9"/>
            @break
        @case('facebook')
            <path d="M13.5 21v-7.6h2.6l.4-3h-3V8.5c0-.9.3-1.5 1.5-1.5h1.6V4.3a21 21 0 0 0-2.3-.1c-2.3 0-3.9 1.4-3.9 4v2.2H7.8v3h2.6V21z"/>
            @break
        @case('linkedin')
            <path d="M6.9 8.8H3.8V20h3.1zM5.4 3.5a1.8 1.8 0 1 0 0 3.6 1.8 1.8 0 0 0 0-3.6zM20.2 13.5c0-3-1.6-4.9-4.3-4.9-1.4 0-2.4.8-2.8 1.5V8.8H10V20h3.1v-5.8c0-1.5.6-2.6 2-2.6s1.9 1 1.9 2.6V20h3.2z"/>
            @break
        @case('x')
            <path d="M17.6 3h3.1l-6.8 7.7L22 21h-6.3l-4.9-6.4L5.2 21H2.1l7.3-8.3L1.7 3h6.4l4.4 5.9zm-1.1 16.2h1.7L7.6 4.7H5.8z"/>
            @break
        @case('youtube')
            <path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3z"/>
            @break
        @case('instagram')
            <path d="M12 7.3a4.7 4.7 0 1 0 0 9.4 4.7 4.7 0 0 0 0-9.4zm0 7.7a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm6-7.9a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0zM12 4c2.2 0 2.5 0 3.3.1 2.2.1 3.3 1.2 3.4 3.4.1.9.1 1.1.1 3.3v2.4c0 2.2 0 2.5-.1 3.3-.1 2.2-1.2 3.3-3.4 3.4-.8.1-1.1.1-3.3.1s-2.5 0-3.3-.1c-2.2-.1-3.3-1.2-3.4-3.4-.1-.8-.1-1.1-.1-3.3v-2.4c0-2.2 0-2.4.1-3.3.1-2.2 1.2-3.3 3.4-3.4C9.5 4 9.8 4 12 4zm0-1.6c-2.3 0-2.6 0-3.4.1-3 .1-4.6 1.8-4.8 4.8-.1.8-.1 1.1-.1 3.4v1.2c0 2.3 0 2.6.1 3.4.1 3 1.8 4.6 4.8 4.8.8.1 1.1.1 3.4.1s2.6 0 3.4-.1c3-.1 4.6-1.8 4.8-4.8.1-.8.1-1.1.1-3.4v-1.2c0-2.3 0-2.6-.1-3.4-.1-3-1.8-4.6-4.8-4.8-.8-.1-1.1-.1-3.4-.1z"/>
            @break
    @endswitch
</svg>

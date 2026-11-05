{{--
    Decorative illustration for the login screen: a cut-away pipeline with a
    brush (cleaning) pig followed by a gauging pig, a pressure gauge and flanges.
    Inline SVG: no extra request, sharp on every screen, no external assets.
--}}
<svg class="auth__illustration" viewBox="0 0 480 400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="pig-pipe-wall" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#5c5c66" />
            <stop offset="0.5" stop-color="#2a2a2e" />
            <stop offset="1" stop-color="#0f0f10" />
        </linearGradient>
        <linearGradient id="pig-bore" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#e7e7ea" />
            <stop offset="0.5" stop-color="#f8f8f9" />
            <stop offset="1" stop-color="#dcdce0" />
        </linearGradient>
        <linearGradient id="pig-body" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#77777f" />
            <stop offset="0.45" stop-color="#3f3f46" />
            <stop offset="1" stop-color="#1c1c1e" />
        </linearGradient>
        <linearGradient id="pig-cup" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#3f3f46" />
            <stop offset="0.5" stop-color="#0f0f10" />
            <stop offset="1" stop-color="#2a2a2e" />
        </linearGradient>
        <linearGradient id="pig-plate" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#ffffff" />
            <stop offset="0.5" stop-color="#c9c9cf" />
            <stop offset="1" stop-color="#a1a1aa" />
        </linearGradient>
        <pattern id="pig-bristles" width="4" height="6" patternUnits="userSpaceOnUse">
            <path d="M1 0v6" stroke="#5c5c66" stroke-width="1.2" />
            <path d="M3 0v6" stroke="#a1a1aa" stroke-width="0.8" />
        </pattern>
        <clipPath id="pig-bore-clip">
            <rect x="0" y="188" width="480" height="104" />
        </clipPath>
    </defs>

    {{-- Floor shadow --}}
    <ellipse cx="240" cy="338" rx="220" ry="10" fill="#0f0f10" opacity="0.06" />

    {{-- Pressure gauge on a riser --}}
    <rect x="96" y="112" width="12" height="62" rx="2" fill="#3f3f46" />
    <rect x="88" y="160" width="28" height="14" rx="3" fill="#1c1c1e" />
    <circle cx="102" cy="78" r="46" fill="#1c1c1e" />
    <circle cx="102" cy="78" r="39" fill="#ffffff" />
    <path d="M74 98a34 34 0 1 1 56 0" stroke="#e7e7ea" stroke-width="6" stroke-linecap="round" />
    <path d="M118 104a34 34 0 0 0 12-6" stroke="#0f0f10" stroke-width="6" stroke-linecap="round" />
    <g stroke="#3f3f46" stroke-width="2" stroke-linecap="round">
        <path d="M73.6 99l4.3-3.4" />
        <path d="M68 78h5.5" />
        <path d="M74.6 57.4l4.2 3.6" />
        <path d="M102 44v5.5" />
        <path d="M129.4 57.4l-4.2 3.6" />
        <path d="M136 78h-5.5" />
        <path d="M130.4 99l-4.3-3.4" />
    </g>
    <path d="M102 78l20-21" stroke="#0f0f10" stroke-width="3" stroke-linecap="round" />
    <circle cx="102" cy="78" r="5" fill="#0f0f10" />
    <rect x="90" y="92" width="24" height="8" rx="2" fill="#f3f3f4" />

    {{-- Pipeline: outer wall --}}
    <rect x="-10" y="174" width="500" height="132" rx="10" fill="url(#pig-pipe-wall)" />

    {{-- Cut-away bore --}}
    <rect x="-10" y="188" width="500" height="104" fill="url(#pig-bore)" />
    <path d="M-10 196h500M-10 284h500" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1" />

    <g clip-path="url(#pig-bore-clip)">
        {{-- Brush (cleaning) pig --}}
        <g>
            <rect x="74" y="216" width="120" height="48" rx="10" fill="url(#pig-body)" />
            <rect x="92" y="189" width="26" height="102" rx="4" fill="url(#pig-bristles)" />
            <rect x="142" y="189" width="26" height="102" rx="4" fill="url(#pig-bristles)" />
            <rect x="92" y="189" width="26" height="102" rx="4" stroke="#3f3f46" stroke-width="1" />
            <rect x="142" y="189" width="26" height="102" rx="4" stroke="#3f3f46" stroke-width="1" />
            <rect x="76" y="192" width="10" height="96" rx="5" fill="url(#pig-cup)" />
            <rect x="178" y="192" width="10" height="96" rx="5" fill="url(#pig-cup)" />
            <circle cx="70" cy="240" r="6" fill="#3f3f46" />
        </g>

        {{-- Gauging pig with its aluminium gauge plate at the front --}}
        <g>
            <rect x="262" y="216" width="112" height="48" rx="10" fill="url(#pig-body)" />
            <rect x="268" y="191" width="12" height="98" rx="6" fill="url(#pig-cup)" />
            <rect x="300" y="191" width="12" height="98" rx="6" fill="url(#pig-cup)" />
            <rect x="352" y="191" width="12" height="98" rx="6" fill="url(#pig-cup)" />
            <rect x="380" y="200" width="5" height="80" rx="2" fill="url(#pig-plate)" stroke="#77777f" stroke-width="0.8" />
            <rect x="374" y="236" width="20" height="8" rx="3" fill="#1c1c1e" />
            <circle cx="398" cy="240" r="5" fill="#0f0f10" />
        </g>

        {{-- Flow direction --}}
        <g stroke="#a1a1aa" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M420 228l12 12-12 12" />
            <path d="M440 228l12 12-12 12" opacity="0.6" />
            <path d="M460 228l12 12-12 12" opacity="0.3" />
            <path d="M22 228l12 12-12 12" opacity="0.3" />
            <path d="M40 228l12 12-12 12" opacity="0.6" />
        </g>
    </g>

    {{-- Flanges with bolts --}}
    <g>
        <rect x="222" y="160" width="20" height="160" rx="3" fill="#1c1c1e" />
        <rect x="244" y="160" width="20" height="160" rx="3" fill="#2a2a2e" />
        <g fill="#a1a1aa">
            <circle cx="232" cy="168" r="3" />
            <circle cx="254" cy="168" r="3" />
            <circle cx="232" cy="312" r="3" />
            <circle cx="254" cy="312" r="3" />
        </g>
    </g>

    {{-- Section line at the cut-away edge --}}
    <path d="M232 188v104" stroke="#ffffff" stroke-opacity="0.15" stroke-width="2" />

    {{-- Labels --}}
    <g font-family="Inter Variable, system-ui, sans-serif" font-size="12" font-weight="500" fill="#3f3f46">
        <path d="M134 302v24" stroke="#a1a1aa" stroke-width="1" stroke-dasharray="2 3" />
        <rect x="88" y="326" width="92" height="24" rx="12" fill="#ffffff" stroke="#e7e7ea" />
        <text x="134" y="342" text-anchor="middle">Brush pig</text>

        <path d="M330 302v24" stroke="#a1a1aa" stroke-width="1" stroke-dasharray="2 3" />
        <rect x="280" y="326" width="100" height="24" rx="12" fill="#ffffff" stroke="#e7e7ea" />
        <text x="330" y="342" text-anchor="middle">Gauging pig</text>

        <path d="M148 78h24" stroke="#a1a1aa" stroke-width="1" stroke-dasharray="2 3" />
        <rect x="172" y="66" width="112" height="24" rx="12" fill="#ffffff" stroke="#e7e7ea" />
        <text x="228" y="82" text-anchor="middle">Pressure gauge</text>
    </g>
</svg>

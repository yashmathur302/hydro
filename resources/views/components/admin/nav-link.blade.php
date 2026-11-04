{{-- Sidebar navigation link; "active" matches a route name pattern such as "admin.settings.*". --}}
@props(['href', 'icon', 'active' => false])

<li>
    <a href="{{ $href }}" @class(['nav-link', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
        <x-admin.icon :name="$icon" />
        <span>{{ $slot }}</span>
    </a>
</li>

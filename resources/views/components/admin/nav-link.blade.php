{{-- Sidebar navigation link with an optional unread count. The title names the link when the sidebar is collapsed. --}}
@props(['href', 'icon', 'active' => false, 'count' => 0])

<li>
    <a href="{{ $href }}" @class(['nav-link', 'is-active' => $active]) title="{{ trim($slot) }}" @if ($active) aria-current="page" @endif>
        <x-admin.icon :name="$icon" />
        <span class="nav-link__label">{{ $slot }}</span>
        @if ($count > 0)
            <span class="nav-link__count">{{ $count > 99 ? '99+' : $count }}<span class="visually-hidden"> unread</span></span>
        @endif
    </a>
</li>

{{-- Sidebar link in the Hope UI menu markup, with an optional unread count. --}}
@props(['href', 'icon', 'active' => false, 'count' => 0])

<li class="nav-item">
    <a @class(['nav-link', 'active' => $active]) href="{{ $href }}" @if ($active) aria-current="page" @endif title="{{ trim($slot) }}">
        <i class="icon"><x-admin.icon :name="$icon" class="icon-20" /></i>
        <span class="item-name">{{ $slot }}</span>
        @if ($count > 0)
            <span class="badge rounded-pill bg-primary nav-count">{{ $count > 99 ? '99+' : $count }}<span class="visually-hidden"> unread</span></span>
        @endif
    </a>
</li>

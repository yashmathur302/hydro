{{--
    Sidebar link in the Hope UI menu markup, with an optional unread count.
    "tone" colours the icon (see $nav-tones in resources/scss/admin/_app.scss).
--}}
@props(['href', 'icon', 'active' => false, 'count' => 0, 'tone' => 'blue'])

<li class="nav-item">
    <a @class(['nav-link', 'nav-tone--'.$tone, 'active' => $active]) href="{{ $href }}" @if ($active) aria-current="page" @endif title="{{ trim($slot) }}">
        <i class="icon"><x-admin.icon :name="$icon" class="icon-18" /></i>
        <span class="item-name">{{ $slot }}</span>
        @if ($count > 0)
            <span class="badge rounded-pill bg-primary nav-count">{{ $count > 99 ? '99+' : $count }}<span class="visually-hidden"> unread</span></span>
        @endif
    </a>
</li>

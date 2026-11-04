{{-- Sidebar navigation link. The title attribute names the link when the sidebar is collapsed to icons. --}}
@props(['href', 'icon', 'active' => false])

<li>
    <a href="{{ $href }}" @class(['nav-link', 'is-active' => $active]) title="{{ trim($slot) }}" @if ($active) aria-current="page" @endif>
        <x-admin.icon :name="$icon" />
        <span class="nav-link__label">{{ $slot }}</span>
    </a>
</li>

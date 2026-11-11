@props(['icon', 'title', 'text' => null])

<div class="empty-state text-center">
    <span class="empty-state__icon"><x-admin.icon :name="$icon" class="icon-24" /></span>
    <h3 class="h5 mt-3 mb-1">{{ $title }}</h3>
    @if ($text)
        <p class="text-secondary mb-0">{{ $text }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-3">{{ $slot }}</div>
    @endif
</div>

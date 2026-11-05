@props(['icon', 'title', 'text' => null])

<div class="empty-state">
    <span class="empty-state__icon"><x-admin.icon :name="$icon" /></span>
    <p class="empty-state__title">{{ $title }}</p>
    @if ($text)
        <p class="empty-state__text">{{ $text }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="empty-state__actions">{{ $slot }}</div>
    @endif
</div>

@props(['title', 'description' => null])

<header class="page-header">
    <div>
        <h1 class="page-header__title">{{ $title }}</h1>
        @if ($description)
            <p class="page-header__description">{{ $description }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="page-header__actions">{{ $slot }}</div>
    @endif
</header>

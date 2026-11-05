{{-- Page title row with optional actions on the right; flash messages appear right below it. --}}
@props(['title', 'description' => null])

<header class="page-header">
    <div class="page-header__text">
        <h1 class="page-header__title">{{ $title }}</h1>
        @if ($description)
            <p class="page-header__description">{{ $description }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="page-header__actions">{{ $slot }}</div>
    @endif
</header>

<x-admin.flash />

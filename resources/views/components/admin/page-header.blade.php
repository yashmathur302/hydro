{{-- Page title row inside the header banner; actions go on the right. Used in the layout's "header" slot. --}}
@props(['title', 'description' => null])

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>{{ $title }}</h1>
        @if ($description)
            <p class="mb-0">{{ $description }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 page-actions">{{ $slot }}</div>
    @endif
</div>

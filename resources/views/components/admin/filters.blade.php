{{-- GET filter bar for list pages: search box plus any selects passed in the slot. --}}
@props(['action', 'search' => null, 'placeholder' => 'Search…', 'active' => false])

<form method="GET" action="{{ $action }}" class="filters" role="search">
    <div class="filters__search">
        <x-admin.icon name="search" class="filters__search-icon" />
        <label class="visually-hidden" for="filter-q">Search</label>
        <input class="input filters__input" type="search" id="filter-q" name="q" value="{{ $search }}" placeholder="{{ $placeholder }}" maxlength="100">
    </div>
    {{ $slot }}
    <button type="submit" class="button button--secondary">Filter</button>
    @if ($active)
        <a class="button button--ghost" href="{{ $action }}">Clear</a>
    @endif
</form>

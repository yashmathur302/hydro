{{--
    GET filter bar for list pages: search box (with its Search button) plus any
    selects passed in the slot, which apply as soon as an option is chosen
    (resources/js/admin/filters.js). Clear resets everything.
--}}
@props(['action', 'search' => null, 'placeholder' => 'Search…', 'active' => false])

<form method="GET" action="{{ $action }}" class="filters card-body border-bottom d-flex flex-wrap gap-2" role="search">
    <div class="input-group filters__search">
        <span class="input-group-text"><x-admin.icon name="search" class="icon-18" /></span>
        <label class="visually-hidden" for="filter-q">Search</label>
        <input class="form-control" type="search" id="filter-q" name="q" value="{{ $search }}" placeholder="{{ $placeholder }}" maxlength="100">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
    {{ $slot }}
    @if ($active)
        <a class="btn btn-outline-secondary" href="{{ $action }}">Clear</a>
    @endif
</form>

{{-- Pagination for admin lists (used as ->links('admin.partials.pagination')). --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">
        <p class="pagination__summary">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>
        <ul class="pagination__list">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="pagination__link is-disabled" aria-disabled="true">Previous</span>
                @else
                    <a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                @endif
            </li>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="pagination__link is-disabled" aria-hidden="true">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="pagination__page">
                            @if ($page === $paginator->currentPage())
                                <span class="pagination__link is-current" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pagination__link" href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li>
                @if ($paginator->hasMorePages())
                    <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                @else
                    <span class="pagination__link is-disabled" aria-disabled="true">Next</span>
                @endif
            </li>
        </ul>
    </nav>
@endif

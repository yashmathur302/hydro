{{-- Bootstrap pagination for admin lists (used as ->links('admin.partials.pagination')). --}}
@if ($paginator->hasPages())
    <nav class="pagination-bar" aria-label="Pagination">
        <p class="mb-0 small text-secondary">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</p>
        <ul class="pagination mb-0">
            <li @class(['page-item', 'disabled' => $paginator->onFirstPage()])>
                @if ($paginator->onFirstPage())
                    <span class="page-link" aria-disabled="true">Previous</span>
                @else
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                @endif
            </li>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled d-none d-sm-block"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li class="page-item active d-none d-sm-block"><span class="page-link" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <li @class(['page-item', 'disabled' => ! $paginator->hasMorePages()])>
                @if ($paginator->hasMorePages())
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                @else
                    <span class="page-link" aria-disabled="true">Next</span>
                @endif
            </li>
        </ul>
    </nav>
@endif

@if ($paginator->hasPages())
    <ul class="pagination">
        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="btn hub-btn" rel="next">Load More</a></li>
        @endif
    </ul>
@endif

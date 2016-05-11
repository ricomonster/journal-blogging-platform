<nav class="pagination" role="navigation">
    @if ($posts->currentPage() != 1)
    <a class="newer-posts" href="{{ $posts->previousPageUrl() }}">
        <span aria-hidden="true">&larr;</span> Newer Posts
    </a>
    @endif

    <span class="page-number">
        Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
    </span>

    @if ($posts->hasMorePages())
    <a class="older-posts" href="{{ $posts->nextPageUrl() }}">
        Older Posts <span aria-hidden="true">&rarr;</span>
    </a>
    @endif
</nav>

<<<<<<< HEAD
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
=======
@if($posts->total() > $posts->perPage())
<nav class="pagination">
    <ul class="links clearfix">
        <li class="previous {{ ($posts->currentPage() == 0 || $posts->currentPage() == 1) ?
        'disabled' : null }}">
            <a {!! ($posts->currentPage() == 0 || $posts->currentPage() == 1) ?
            null : 'href="'.$posts->previousPageUrl().'"' !!}>
                <i class="fa fa-angle-double-left"></i>
                Previous
            </a>
        </li>
        <li class="next {{ ($posts->currentPage() == $posts->lastPage()) ? 'disabled' : null }}">
            <a {!!  ($posts->currentPage() == $posts->lastPage()) ?
            null : 'href="'.$posts->nextPageUrl().'"' !!}>
                Next
                <i class="fa fa-angle-double-right"></i>
            </a>
        </li>
    </ul>
</nav>
@endif
>>>>>>> 55ef9cdd20e1f3f8a49d2e17e257a0d5eadd821a

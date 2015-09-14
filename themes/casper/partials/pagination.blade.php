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

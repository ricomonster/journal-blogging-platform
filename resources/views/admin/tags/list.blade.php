@extends('admin.layout')
@section('title', 'Tags')

@section('content')
<journal-tags-list inline-template>
    <div id="tags_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Tags</h1>
        </header>
        <section class="tags-list scrollable-content">
            <div class="wrapper">
                <article class="tag">
                    <a href="#">
                        <i class="fa fa-tag"></i>
                        <h3>Tag name</h3>
                    </a>
                </article>
            </div>
        </section>
    </div>
</journal-tags-list>
@endsection

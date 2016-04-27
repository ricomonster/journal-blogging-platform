@extends('admin.layout')
@section('title', 'Tags')

@section('content')
<journal-tags-list inline-template>
    <div id="tags_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Tags</h1>

            <a class="btn btn-default options" v-on:click="toggleSidebar">
                <i class="fa fa-plus"></i> Add new tag
            </a>
        </header>
        <section class="tags-list scrollable-content">
            <div class="wrapper centered-content">
                <article class="tag clearfix" v-for="tag in tags">
                    <a href="/journal/tags/@{{ tag.id }}/update" class="clearfix">
                        <span class="post-count label label-default">
                            @{{ tag.posts.length }} posts
                        </span>
                        <i class="fa fa-tag"></i>
                        <div class="tag-details">
                            <h3 class="tag-title">@{{ tag.title }}</h3>
                            <p class="tag-slug">@{{ tag.slug }}</p>
                        </div>
                    </a>
                </article>
            </div>
        </section>

        <!-- Sidebar -->
        @include('admin.tags.sidebar')
    </div>
</journal-tags-list>
@include('admin.scripts.image-uploader')
@endsection

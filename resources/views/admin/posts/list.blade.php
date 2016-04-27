@extends('admin.layout')

@section('title', 'Posts')

@section('content')
<journal-posts-list inline-template>
    <div id="posts_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Posts</h1>
            <a class="new-post options" href="{{url('journal/editor')}}">
                <i class="fa fa-pencil-square"></i>
            </a>
        </header>
        <section class="post-lists">
            <header class="post-lists-header">
                <input type="text" class="form-control"
                placeholder="Search..."/>
            </header>
            <section class="lists-wrapper">
                <article class="post auto-height" v-if="!loading && posts.length"
                v-for="post in posts" v-bind:class="{'active' : post == active}">
                    <a class="hyperlink" v-on:click="selectPost(post)">
                        <header class="post-title">@{{post.title}}</header>
                        <section class="post-meta">
                            <time class="post-timestamp published"
                            v-if="post.status == 1">
                                <span>Published</span>
                                <time-ago :timestamp="post.published_at"></time-ago>
                            </time>
                            <span class="post-status draft" v-if="post.status == 2">Draft</span>
                        </section>
                    </a>
                </article>
                <div class="auto-height no-posts" v-if="!loading && !posts.length">
                    <h3>No posts to display.</h3>
                </div>
            </section>
        </section>
        <section class="post-preview">
            <header class="preview-header clearfix" v-if="!loading && active">
                <div class="post-meta">
                    <span class="post-status published" v-if="active.status == 1">
                        Published by
                    </span>
                    <span class="post-status draft" v-if="active.status == 2">
                        Written by
                    </span>
                    <span class="author-name">
                        @{{active.author.name}}
                    </span>
                </div>
                <div class="post-controls">
                    <ul class="control-lists">
                        <li>
                            <a href="/journal/editor/@{{active.id}}" class="edit-post">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </li>
                        <li>
                            <a class="preview-post">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>
                        <li>
                            <a class="delete-post" data-toggle="modal"
                            data-target="#delete_post_modal">
                                <i class="fa fa-trash"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </header>
            <section class="preview-content" v-if="!loading && active">
                <!-- Markdown Preview Directive goes here -->
                <div class="rendered-markdown">
                    <markdown-reader :markdown.sync="active.content"></markdown-reader>
                </div>
            </section>
            <div class="no-post" v-if="!loading && !active">
                <h3>Compose your first post.</h3>
                <a href="/journal/editor" class="btn btn-success">
                    Go to Editor
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </section>
    </div>

    <aside id="delete_post_modal" class="modal fade" tabindex="-1" role="dialog"
    v-if="!loading && active">
        <div class="modal-dialog">
            <div class="modal-content">
                <header class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Delete Post</h4>
                </header>
                <section class="modal-body">
                    <p>
                        Are you sure you wanted to delete the post
                        <span class="post-title">@{{active.title}}</span>?
                    </p>
                </section>
                <footer class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="deletePost()">
                        Yes, delete this post
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </footer>
            </div>
        </div>
    </aside>
</journal-posts-list>
@endsection

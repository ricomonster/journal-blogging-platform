@extends('admin.layout')

@section('title', 'Posts')

@section('content')
<journal-posts-list inline-template>
    <div id="posts_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Posts</h1>
            <a class="btn btn-default new-post" href="{{url('journal/editor')}}">
                <i class="fa fa-pencil-square-o"></i> New Post
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
                                <span>7 years ago</span>
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
            <header class="preview-header clearfix" v-if="!loading && active.id">
                <div class="post-meta">
                    <span class="post-status published" v-if="active.status == 1">
                        Published by
                    </span>
                    <span class="post-status draft" v-if="active.status == 2">
                        Written by
                    </span>
                    <span class="author-name">
                        Author Name
                    </span>
                </div>
                <div class="post-controls">
                    <ul class="control-lists">
                        <li>
                            <a href="/journal/editor/@{{active.id}}" class="btn btn-success btn-sm">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-warning btn-sm">
                                <i class="fa fa-search"></i> Preview
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-o"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </header>
            <section class="preview-content" v-if="!loading && active.id">
                <!-- Markdown Preview Directive goes here -->
                <div class="rendered-markdown">
                    <markdown-reader :markdown.sync="active.content"></markdown-reader>
                </div>
            </section>
            <div class="no-post" v-if="!loading && !active.id">
                <h3>Compose your first post.</h3>
                <a href="/journal/editor" class="btn btn-success">
                    Go to Editor
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </section>
    </div>
</journal-posts-list>
@endsection

@extends('admin.layout')
@section('title', 'Editor')

@section('content')
<journal-editor inline-template>
    <div id="editor_page">
        <form v-on:submit="savePost($event)">
            <header class="page-header clearfix">
                <input type="text" v-model="post.title" placeholder="Title"
                class="form-control input-lg input-post-slug" v-on:blur="generateSlug()"/>
                <div class="editor-controls">
                    <a class="sidebar-toggler" v-on:click="toggleSidebar()">
                        <i class="fa fa-cog"></i>
                    </a>
                    <!-- Buttons -->
                    <div class="btn-group">
                        <button id="split_button" type="submit" class="btn"
                        v-bind:class="active.class">@{{active.text}}</button>
                        <button type="button" class="btn" data-toggle="dropdown"
                        v-bind:class="active.class">
                            <span class="caret"></span>
                            <span class="sr-only"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="menuitem" v-for="button in buttons"
                            v-if="button.group == active.group"
                            v-bind:class="{'active' : button === active}">
                                <a v-on:click="setPostStatus(button)">@{{button.text}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <section class="editor">
                <section class="markdown-editor window">
                    <section class="editor-wrapper editor-scroll">
                        <!-- Codemirror here -->
                        <div class="codemirror-wrapper"
                        v-codemirror="post.content"
                        v-sync-scroll.literal=".preview-wrapper"></div>
                    </section>
                    <footer class="floating-footer">
                        <span class="title">
                            <i class="fa fa-arrow-circle-o-down"></i> Markdown
                        </span>
                        <a class="markdown-helper" title="Open the markdown helper">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </footer>
                </section>
                <section class="markdown-preview window">
                    <section class="preview-wrapper">
                        <div class="rendered-markdown">
                            <markdown-reader :markdown.sync="post.content"
                            :editor-mode="true" :counter.sync="counter"></markdown-reader>
                        </div>
                    </section>
                    <footer class="floating-footer">
                        <span class="title">
                            <i class="fa fa-eye"></i> Preview
                        </span>
                        <span class="word-counter">@{{counter.count}} words</span>
                    </footer>
                </section>
            </section>
            <!-- Sidebar -->
            @include('admin.editor.sidebar')

            @if ($postId)
            <input type="hidden" name="post_id" value="{{$postId}}"/>
            @endif
        </form>
    </div>

    <div id="delete_post_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Delete Post</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you wanted to delete the post
                        <span class="post-title">@{{post.title}}</span>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="deletePost()">
                        Yes, delete this post
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</journal-editor>

@include('admin.scripts.image-uploader')
@endsection

@extends('admin.layout')
@section('title', 'Editor')

@section('content')
<journal-editor inline-template>
    <div id="editor_page">
        <div class="loading-overlay" v-if="loading">
            <div class="content">
                <i class="fa fa-circle-o-notch fa-spin"></i>
                <p class="loading-text">Loading</p>
            </div>
        </div>
        <form v-on:submit.prevent="savePost($event)">
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
                        v-bind:class="active.class" v-button-loader="processing"
                        no-text="true">
                            <span class="button-text" v-if="!processing">
                                @{{active.text}}
                            </span>
                        </button>
                        <button type="button" class="btn" data-toggle="dropdown"
                        v-bind:class="active.class" v-bind:disabled="processing">
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
                <markdown-editor :model.sync="post.content"></markdown-editor>
            </section>
            <!-- Sidebar -->
            @include('admin.editor.sidebar')
        </form>

        @if ($postId)
        <input type="hidden" name="post_id" value="{{$postId}}"/>
        @endif
    </div>

    <article id="delete_post_modal" class="modal fade" tabindex="-1" role="dialog">
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
                        <span class="post-title">@{{post.title}}</span>?
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
    </article>
</journal-editor>

@include('admin.scripts.markdown-editor')
@include('admin.scripts.image-uploader')
@endsection

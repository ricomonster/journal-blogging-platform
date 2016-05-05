@extends('admin.layout')
@section('title', 'Editor')

@section('content')
<journal-editor inline-template>
    <div id="editor_page">
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
                        v-bind:class="active.class" v-button-loader="processing">
                            @{{active.text}}
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
                <markdown-editor :content="post.content"></markdown-editor>
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

<template id="markdown_editor_template">
    <div class="markdown-editor">
        <header class="editor-controls clearfix">
            <div class="navigation">
                <ul class="navs">
                   <li><a>Markdown</a></li>
                   <li><a>Preview</a></li>
                </ul>
            </div>
            <div class="controls">
                <ul class="tools">
                    <li v-for="(index, toolbar) in toolbars">
                        <a v-on:click="action(index)">
                            <i class="fa" v-bind:class="toolbar"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </header>
        <section class="editor">
            <textarea id="codemirror_textarea"></textarea>
        </section>
    </div>
    @include('admin.common.modal.image-uploader')
</template>

@include('admin.scripts.image-uploader')
@endsection

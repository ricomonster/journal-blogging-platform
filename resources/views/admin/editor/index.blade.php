@extends('admin.layout')

@section('title', 'Editor')

@section('content')
<journal-editor inline-template>
    <div id="editor_page">
        <form v-on:submit.prevent="savePost()">
            <header class="page-header clearfix">
                <input type="text" v-model="post.title" placeholder="Title"
                class="form-control input-lg input-post-slug"/>
                <div class="editor-controls">
                    <a class="btn btn-default sidebar-toggler">
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
                    <header class="floating-header">
                        <span class="title">
                            <i class="fa fa-arrow-circle-o-down"></i> Markdown
                        </span>
                        <a class="markdown-helper" title="Open the markdown helper">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </header>
                    <section class="editor-wrapper editor-scroll">
                        <!-- Codemirror here -->
                        <div v-codemirror="post.content"
                        v-sync-scroll.literal=".preview-wrapper"></div>
                    </section>
                </section>
                <section class="markdown-preview window">
                    <header class="floating-header">
                        <span class="title">
                            <i class="fa fa-eye"></i> Preview
                        </span>
                        <span class="word-counter">0 words</span>
                    </header>
                    <section class="preview-wrapper">
                        <div class="rendered-markdown">
                            <markdown-reader :markdown.sync="post.content"></markdown-reader>
                        </div>
                    </section>
                </section>
            </section>
            <!-- Sidebar -->

            @if ($postId)
            <input type="hidden" name="post_id" value="{{$postId}}"/>
            @endif
        </form>
    </div>
</journal-editor>
@endsection

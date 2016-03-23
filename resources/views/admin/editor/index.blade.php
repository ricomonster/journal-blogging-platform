@extends('admin.layout')

@section('title', 'Editor')

@section('css')
<link rel="stylesheet" href="{{asset('/vendor/css/codemirror.css')}}"/>
@endsection

@section('content')
<div id="editor_page" v-cloak>
    <form v-on:submit.prevent="savePost()">
        <header class="page-header clearfix">
            <input type="text" v-model="post.title" placeholder="Title"
            class="form-control input-lg input-post-slug"/>
            <div class="editor-controls">
                <a class="btn btn-default sidebar-toggler">
                    <i class="fa fa-cog"></i>
                </a>
                <!-- Buttons -->
                <editor-buttons :status.sync="post.status"></editor-buttons>
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
                    <div v-codemirror="post.content"></div>
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
                        <markdown-renderer :markdown.sync="post.content"></markdown-renderer>
                    </div>
                </section>
            </section>
        </section>
        <!-- Sidebar -->

        @if ($postId)
        <input type="hidden" name="post_id" value="{{$postId}}"/>
        @endif
    </form>
    <template id="editor_buttons_template">
        <dropdown class="btn-group">
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
        </dropdown>
    </template>
    <template id="markdown_renderer_template">
        @{{{renderedMarkdown}}}
    </template>
</div>
@endsection

@section('footer.js')
<script src="{{ asset('/vendor/js/codemirror.js') }}"></script>
<script src="{{ asset('/vendor/js/showdown.js') }}"></script>
<script type="text/javascript">
    // Codemirror Directive
    Vue.directive('codemirror', {
        twoWay : true,
        bind : function () {
            this.codemirror = CodeMirror(this.el, {
                mode : "markdown",
                tabMode : "indent",
                lineWrapping : !0
            });

            this.codemirror.on("change", function () {
                this.set(this.codemirror.getValue());
            }.bind(this));
        },
        update: function (value, oldValue) {
            this.codemirror.setValue(value || '');
        }
    });

    // Editor Buttons Component
    Vue.component('editor-buttons', {
        template : '#editor_buttons_template',
        props : ['status'],
        data : function () {
            return {
                active : [],
                buttons : [
                    { class : 'btn-danger', group : 1, status : 1, text : 'Publish Now' },
                    { class : 'btn-primary', group : 1, status : 2, text : 'Save as Draft' },
                    { class : 'btn-danger', group : 2, status : 2, text : 'Unpublish Post' },
                    { class : 'btn-info', group : 2, status : 1, text : 'Update Post' }]
            }
        },
        ready : function () {
            this.renderButtons(this.status || 2);
        },
        methods : {
            /**
             * This will render the buttons to be shown
             */
            renderButtons : function (status) {
                // we're going to assume that status is published.
                var selectedOption = this.buttons[3];

                // check if the post status is draft
                if (status == 2) {
                    selectedOption = this.buttons[1];
                }

                // set the button option
                this.active = selectedOption;
            },
            /**
             * Set the status of the post
             */
            setPostStatus : function (option) {
                var vm = this;

                // set the selected one to be active
                vm.active = option;

                // reflect to the prop
                vm.status = option.status;
            }
        }
    });

    // Markdown Rendered Component
    Vue.component('markdown-renderer', {
        template : '#markdown_renderer_template',
        props : ['markdown', 'editorMode', 'wordCount'],
        computed : {
            renderedMarkdown : function () {
                var converter   = new showdown.Converter(),
                    html        = converter.makeHtml(this.markdown);

                // do some cleaning in the converted markdown
                // check if it is in editor mode
                if (this.editorMode) {
                    // replace the scripts and iframes
                    html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                        '<div class="embedded-javascript">Embedded JavaScript</div>');
                    html = html.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
                        '<div class="embedded-iframe">Embedded iFrame</div>');
                }

                return html;
            }
        }
    });

    new Vue({
        el : '#editor_page',
        data: {
            loading : true,
            post : {
                status : 2
            },
            userId : Journal.userId
        },
        ready : function () {
            if ($('input[name="post_id"]').length > 0) {
                // get the the post
                this.getPost($('input[name="post_id"').val());
            }
        },
        methods : {
            getPost : function (postId) {
                var vm = this;

                vm.$http.get('/api/posts/get?post_id='+postId)
                    .then(function (response) {
                        if (response.data.post) {
                            vm.post = response.data.post;
                        }
                    });
            },
            /**
             * Saves the post to the API
             */
            savePost : function () {
                var vm = this,
                    post = vm.post,
                    url = '/api/posts/save?author_id='+vm.userId;

                // check if post id is present
                if (post.id) {
                    url += '&post_id='+post.id
                }

                // save post
                vm.$http.post(url, post)
                    .then(function (response) {
                        if (response.data.post) {
                            // update the post data
                            vm.post = response.data.post;

                            // notify user for success
                        }
                    }, function (response) {
                        // error, show it
                    });
            }
        }
    });
</script>
@endsection

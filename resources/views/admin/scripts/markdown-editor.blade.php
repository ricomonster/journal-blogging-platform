<template id="markdown_editor_template">
    <div class="markdown-editor">
        <header class="editor-controls clearfix">
            <div class="navigation">
                <ul class="navs clearfix">
                    <li v-bind:class="{ 'active' : active == 'markdown' }">
                        <a v-on:click="toggleWindow">
                            <i class="fa fa-arrow-circle-o-down"></i> Markdown
                        </a>
                    </li>
                    <li v-bind:class="{ 'active' : active == 'preview' }">
                        <a v-on:click="toggleWindow">
                            <i class="fa fa-eye"></i> Preview
                        </a>
                    </li>
                </ul>
            </div>
            <div class="controls">
                <ul class="tools">
                    <li v-for="toolbar in toolbars" v-bind:class="index">
                        <a v-on:click="action(toolbar.action)" data-toggle="tooltip"
                        data-placement="bottom" title="@{{ toolbar.tooltip }}">
                            <i class="fa" v-bind:class="toolbar.icon"></i>
                        </a>
                    </li>
                    <li class="markdown-help">
                        <a v-on:click="showMarkdownHelpModal" data-toggle="tooltip" data-placement="bottom"
                        title="Markdown Help">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </header>
        <section class="editor-wrapper" v-show="active == 'markdown'">
            <textarea id="codemirror_textarea" placeholder="Write here..."></textarea>
        </section>
        <section class="preview-wrapper" v-show="active == 'preview'">
            <markdown-reader :markdown.sync="model" :editor-mode="true"
            :counter.sync="counter" class="rendered-markdown"></markdown-reader>
        </section>
    </div>

    @include('admin.common.modal.image-uploader')
    @include('admin.common.modal.markdown-helper')
</template>
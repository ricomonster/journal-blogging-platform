Vue.component('markdown-reader', {
    template : '<div class="markdown-reader">{{{html}}}</div>',
    props : [
        {
            name : 'markdown',
            type : String
        },
        {
            name : 'editorMode',
            type : Boolean
        }
    ],
    computed : {
        html : function () {
            var converter   = new showdown.Converter(),
                html        = (this.markdown) ?
                    converter.makeHtml(this.markdown) : '';

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

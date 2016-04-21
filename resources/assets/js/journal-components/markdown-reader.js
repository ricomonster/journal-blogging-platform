Vue.component('markdown-reader', {
    template : '<div class="markdown-reader">{{{html}}}</div>',
    props : [
        {
            name : 'counter',
            type : Object
        },
        {
            name : 'editorMode',
            type : Boolean
        },
        {
            name : 'markdown',
            type : String
        }
    ],
    computed : {
        html : function () {
            var vm          = this,
                converter   = new showdown.Converter(),
                html        = (this.markdown) ? converter.makeHtml(this.markdown) : '';

            // do some cleaning in the converted markdown
            // check if it is in editor mode
            if (vm.editorMode) {
                // replace the scripts and iframes
                html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                    '<div class="embedded-javascript">Embedded JavaScript</div>');
                html = html.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
                    '<div class="embedded-iframe">Embedded iFrame</div>');
            }

            // check if we need to count the words
            if (vm.counter && vm.counter.enable) {
                var wordCount = 0;
                // trim first the content
                var trimmedContent = $(html).text().replace(/^\s\s*/, '').replace(/\s\s*$/, '');

                // check if there is content
                if (trimmedContent.length > 0) {
                    wordCount = trimmedContent.match(/[^\s]+/g).length;
                }

                // set the value of countwords
                vm.$set('counter.count', wordCount);
            }

            return html;
        }
    }
});

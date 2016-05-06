require('./markdown-reader');

var toolbars = {
    'bold' : 'fa-bold',
    'italic' : 'fa-italic',
    'link' : 'fa-link',
    'code' : 'fa-code',
    'image' : 'fa-picture-o',
    'unordered-list' : 'fa-list-ul',
    'ordered-list' : 'fa-list-ol',
    'quote' : 'fa-quote-right',
    'fullscreen' : 'fa-arrows-alt'
};

var shortcuts = {
    bold: 'Cmd-B',
    italic: 'Cmd-I',
    link: 'Cmd-K',
    image: 'Cmd-Alt-I',
    quote: "Cmd-'",
    'ordered-list': 'Cmd-Alt-L',
    'unordered-list': 'Cmd-L',
    'undo': 'Cmd-Z',
    'redo': 'Cmd-Y'
};

Vue.component('markdown-editor', {
    template : '#markdown_editor_template',

    props : [
        {
            name : 'model',
            type : String
        }
    ],

    data : function () {
        return {
            active : 'markdown',
            codemirror : '',
            counter : {
                enable : true,
                count : 0
            },
            fullscreen : false,
            isMac : false,
            modal : {
                image : null,
                type : null
            },
            toolbars : toolbars
        }
    },

    ready : function () {
        // set the shortcut keys
        var vm = this,
            keyMaps = {};

        vm.isMac = /Mac/.test(navigator.platform);

        for (var key in shortcuts) {
            (function(key) {
                keyMaps[vm.fixShortcut(shortcuts[key])] = function(cm) {
                    vm.action(key, cm);
                };
            })(key);
        }

        keyMaps["Enter"] = "newlineAndIndentContinueMarkdownList";

        vm.codemirror = CodeMirror.fromTextArea(
            document.getElementById("codemirror_textarea"), {
                mode: 'markdown',
                theme: 'paper',
                indentWithTabs: true,
                lineNumbers: false,
                lineWrapping: true,
                extraKeys: keyMaps
            });

        vm.codemirror.refresh();

        // checks for changes and we're going to set it to the props
        vm.codemirror.on("change", function () {
            vm.$set('model', vm.codemirror.getValue());
        });

        // set the initial value
        vm.codemirror.setValue(vm.model || '');

        vm.$watch('model', function(value) {
            if (value && value !== vm.codemirror.getValue())
                vm.codemirror.setValue(value);
        });
    },

    methods : {
        /**
         * Toggles which action to be triggered.
         */
        action : function (name, cm) {
            var vm = this;

            cm = cm || vm.codemirror;
            if (!cm) return;

            var stat = vm.getState(cm);

            switch (name) {
                case 'bold':
                    vm.replaceSelection('**', '', name);
                    break;
                case 'italic':
                    vm.replaceSelection('*', '', name);
                    break;
                case 'code':
                    vm.replaceSelection('`', '', name);
                    break;
                case 'link':
                    vm.replaceSelection('[', '](http://)', name);
                    break;
                case 'image':
                    // open image uploader
                    vm.openImageUploader();
                    break;
                case 'quote':
                case 'unordered-list':
                case 'ordered-list':
                    vm.toggleLine(name);
                    break;
                case 'undo':
                    cm.undo();
                    cm.focus();
                    break;
                case 'redo':
                    cm.redo();
                    cm.focus();
                    break;
                case 'fullscreen':
                    vm.toggleFullScreen();
                    break;
            }
        },

        /**
         * Fixes the shortcut key by checking if the browser runs on a Mac.
         */
        fixShortcut : function (text) {
            var vm = this;

            if (vm.isMac) {
                text = text.replace('Ctrl', 'Cmd');
            } else {
                text = text.replace('Cmd', 'Ctrl');
            }
            return text;
        },

        /**
         * Gets the current state of Codemirror
         */
        getState : function (cm, pos) {
            pos = pos || cm.getCursor('start');
            var stat = cm.getTokenAt(pos);
            if (!stat.type) return {};

            var types = stat.type.split(' ');

            var ret = {}, data, text;
            for (var i = 0; i < types.length; i++) {
                data = types[i];
                if (data === 'strong') {
                    ret.bold = true;
                } else if (data === 'variable-2') {
                    text = cm.getLine(pos.line);
                    if (/^\s*\d+\.\s/.test(text)) {
                        ret['ordered-list'] = true;
                    } else {
                        ret['unordered-list'] = true;
                    }
                } else if (data === 'atom') {
                    ret.quote = true;
                } else if (data === 'em') {
                    ret.italic = true;
                }
            }
            return ret;
        },

        /**
         * Opens a modal to show the image uploader.
         */
        openImageUploader : function () {
            // clear it
            this.modal = {
                image : null,
                type : null
            };

            $('#upload_image_modal').modal('show');
        },

        /**
         * Places the selected action in the Codemirror
         */
        replaceSelection : function (start, end, name) {
            var vm = this;
            var cm = this.codemirror;
            var text;
            var startPoint = cm.getCursor('start');
            var endPoint = cm.getCursor('end');

            var stat = vm.getState(cm);

            if (stat[name]) {
                text = cm.getLine(startPoint.line);
                start = text.slice(0, startPoint.ch);
                end = text.slice(startPoint.ch);

                if (name === 'bold') {
                    start = start.replace(/^(.*)?(\*|\_){2}(\S+.*)?$/, '$1$3');
                    end = end.replace(/^(.*\S+)?(\*|\_){2}(\s+.*)?$/, '$1$3');
                    startPoint.ch -= 2;
                    endPoint.ch -= 2;
                } else if (name === 'italic') {
                    start = start.replace(/^(.*)?(\*|\_)(\S+.*)?$/, '$1$3');
                    end = end.replace(/^(.*\S+)?(\*|\_)(\s+.*)?$/, '$1$3');
                    startPoint.ch -= 1;
                    endPoint.ch -= 1;
                }

                cm.replaceRange(start + end, {
                    line: startPoint.line
                });

                cm.setSelection(startPoint, endPoint);
                cm.focus();
                return;
            }

            if (end === null) {
                end = '';
            } else {
                end = end || start;
            }

            text = cm.getSelection();
            cm.replaceSelection(start + text + end);

            startPoint.ch += start.length;
            endPoint.ch += start.length;
            cm.setSelection(startPoint, endPoint);
            cm.focus();
        },

        /**
         * Sets the uploaded or link the image to the editor
         */
        saveImage : function () {
            var vm = this,
                // get the image
                imageUrl = vm.modal.image;

            // put it in the editor
            vm.replaceSelection('![', '](' + imageUrl + ')', 'image');

            // close modal and empty the modal
            $('#upload_image_modal').modal('hide');

            vm.modal = {
                image : null,
                type : null
            };
        },

        showMarkdownHelpModal : function () {
            // $('#markdown_help_modal').modal('show');
            alert('Coming soon...');
        },

        /**
         * Full screen mode baby.
         */
        toggleFullScreen : function () {
            var vm = this;

            // toggle to fullscreen or not
            vm.fullscreen = !vm.fullscreen;

            if (vm.fullscreen) {
                // hide everything except the editor
                $('body').addClass('editor-fullscreen');
            }

            if (!vm.fullscreen) {
                $('body').removeClass('editor-fullscreen');
            }
        },

        /**
         * Wraps and prepend markdown objects to selected text or in the
         * current line of the cursor.
         */
        toggleLine : function(name) {
            var vm = this;
            var cm = vm.codemirror;
            var stat = vm.getState(cm);
            var startPoint = cm.getCursor("start");
            var endPoint = cm.getCursor("end");
            var repl = {
                "quote": /^(\s*)\>\s+/,
                "unordered-list": /^(\s*)(\*|\-|\+)\s+/,
                "ordered-list": /^(\s*)\d+\.\s+/
            };

            var map = {
                "quote": "> ",
                "unordered-list": "* ",
                "ordered-list": "1. "
            };

            for(var i = startPoint.line; i <= endPoint.line; i++) {
                (function(i) {
                    var text = cm.getLine(i);
                    if(stat[name]) {
                        text = text.replace(repl[name], "$1");
                    } else {
                        text = map[name] + text;
                    }

                    cm.replaceRange(text, {
                        line: i,
                        ch: 0
                    }, {
                        line: i,
                        ch: 99999999999999
                    });
                })(i);
            }

            cm.focus();
        },

        /**
         * Selects which window to be shown.
         */
        toggleWindow : function () {
            var vm = this;

            // check current active window
            if (vm.active == 'markdown') {
                // set active window to preview
                vm.active = 'preview';
                return;
            }

            if (vm.active == 'preview') {
                // set active window to preview
                vm.active = 'markdown';
                return;
            }
        }
    }
});
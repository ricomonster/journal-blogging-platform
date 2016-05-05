require('./../directives/codemirror');

var toolbars = {
    'bold' : 'fa-bold',
    'italic' : 'fa-italic',
    'link' : 'fa-link',
    'code' : 'fa-code',
    'image' : 'fa-picture-o',
    'list' : 'fa-list',
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
            name : 'content',
            type : String
        }
    ],

    data : function () {
        return {
            codemirror : '',
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

        this.codemirror = CodeMirror.fromTextArea(
            document.getElementById("codemirror_textarea"), {
                mode: 'markdown',
                theme: 'paper',
                indentWithTabs: true,
                lineNumbers: false,
                lineWrapping: true,
                extraKeys: keyMaps
            });

        this.codemirror.refresh();
    },

    methods : {
        /**
         * Toggles which action to be triggered.
         */
        action : function (name, cm) {
            var vm = this;

            cm = cm || vm.codemirror;
            if (!cm) return;

            var toggleLine = function() {
                var startPoint = cm.getCursor('start');
                var endPoint = cm.getCursor('end');
                var repl = {
                    quote: /^(\s*)\>\s+/,
                    'unordered-list': /^(\s*)(\*|\-|\+)\s+/,
                    'ordered-list': /^(\s*)\d+\.\s+/
                };

                var map = {
                    quote: '> ',
                    'unordered-list': '* ',
                    'ordered-list': '1. '
                };

                for (var i = startPoint.line; i <= endPoint.line; i++) {
                    (function(i) {
                        var text = cm.getLine(i);
                        if (stat[name]) {
                            text = text.replace(repl[name], '$1');
                        } else {
                            text = map[name] + text;
                        }
                        cm.setLine(i, text);
                    })(i);
                }
                cm.focus();
            };

            switch (name) {
                case 'bold':
                    vm.replaceSelection('**');
                    break;
                case 'italic':
                    vm.replaceSelection('*');
                    break;
                case 'code':
                    vm.replaceSelection('`', '`');
                    break;
                case 'link':
                    vm.replaceSelection('[', '](http://)');
                    break;
                case 'image':
                    // open image uploader
                    vm.openImageUploader();
                    //replaceSelection('![', '](http://)');
                    break;
                case 'quote':
                case 'unordered-list':
                case 'ordered-list':
                    toggleLine();
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
                    // toggleFullScreen(cm.getWrapperElement());
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

        openImageUploader : function () {
            // clear it
            this.modal = {
                image : null,
                type : null
            };

            $('#upload_image_modal').modal('show');
        },

        replaceSelection : function (start, end) {
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

                cm.setLine(startPoint.line, start + end);
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

        saveImage : function () {
            var vm = this,
                // get the image
                imageUrl = vm.modal.image;

            // put it in the editor
            vm.replaceSelection('![', '](' + imageUrl + ')');

            // close modal and empty the modal
            $('#upload_image_modal').modal('hide');

            vm.modal = {
                image : null,
                type : null
            };
        }
    }
});
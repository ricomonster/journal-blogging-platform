(function() {
    'use strict';

    angular.module('journal.shared.markdownReader')
        .provider('MarkdownReader', [MarkdownReader]);

    function MarkdownReader() {
        var options = {};
        return {
            config : function(newOptions) {
                options = newOptions;
            },
            $get : function() {
                return new showdown.Converter(options);
            }
        }
    }
})();
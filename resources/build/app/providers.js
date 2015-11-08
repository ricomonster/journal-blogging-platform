(function() {
    'use strict';

    angular.module('journal.shared.markdownConverter')
        .provider('MarkdownConverter', [MarkdownConverter]);

    function MarkdownConverter() {
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

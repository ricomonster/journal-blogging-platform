(function() {
    'use strict';

    angular.module('journal.shared.markdownConverter')
        .directive('journalMarkdown', ['$sanitize', 'MarkdownConverter', JournalMarkdown]);

    function JournalMarkdown($sanitize, MarkdownConverter) {
        return {
            restrict : 'AE',
            scope : {
                journalMarkdown : '=',
                counter : '='
            },
            link : function(scope, element, attributes) {
                var countWords = function() {
                    var wordCount = 0;
                    // trim first the content
                    var trimmedContent = element.text().replace(/^\s\s*/, '').replace(/\s\s*$/, '');

                    // check if there is content
                    if (trimmedContent.length > 0) {
                        wordCount = trimmedContent.match(/[^\s]+/g).length;
                    }

                    scope.counter = wordCount;
                };

                if (attributes.journalMarkdown) {
                    scope.$watch('journalMarkdown', function(value) {
                        var html = value ? $sanitize(MarkdownConverter.makeHtml(value)) : '';
                        element.html(html);
                        countWords();
                    });
                } else {
                    var html = $sanitize(MarkdownConverter.makeHtml(element.text()));
                    element.html(html);
                    countWords();
                }
            }
        }
    }
})();

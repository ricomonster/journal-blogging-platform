(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .directive('publishIndicator', [PublishIndicatorDirective]);

    function PublishIndicatorDirective() {
        return {
            restrict : 'A',
            scope : {
                publishIndicator : '=publishIndicator'
            },
            link : function(scope, element, attribute) {
                var currentTimestamp = Math.floor(Date.now() / 1000);

                if (attribute.publishIndicator) {
                    scope.$watch(function() {
                        return scope.publishIndicator
                    }, function(timestamp) {
                        // check if the post timestamp is greater than the
                        // current timestamp then set the text to be shown.
                        var text = (currentTimestamp >= timestamp) ?
                            'Published' : 'To be published';

                        // set the text
                        element.text(text);
                    });
                }
            }
        }
    }
})();

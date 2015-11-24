(function() {
    'use strict';

    angular.module('journal.shared.buttonLoader')
        .directive('buttonLoader', [ButtonLoaderDirective]);

    function ButtonLoaderDirective() {
        return {
            restrict : 'A',
            scope : {
                buttonLoader : '='
            },
            link : function(scope, element, attributes) {
                var generateButton = function() {
                    var buttonContent = element.html();

                    // add class
                    element.addClass('btn-loader');
                };

                // check if the attribute exists
                if (attributes.buttonLoader) {
                    // check for scope changes
                    scope.$watch('buttonLoader', function(loading) {
                        // check if the page is loading
                        if (loading) {
                            return element.addClass('btn-loading btn-disabled')
                                .attr('disabled', 'disabled');
                        }

                        return element.removeClass('btn-loading btn-disabled')
                            .removeAttr('disabled');
                    });
                }

                // generate the new content of the button
                generateButton();
            }
        }
    }
})();

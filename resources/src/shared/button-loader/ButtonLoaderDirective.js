(function() {
    'use strict';

    angular.module('journal.shared.buttonLoader')
        .directive('buttonLoader', ['$timeout', ButtonLoaderDirective]);

    function ButtonLoaderDirective($timeout) {
        return {
            restrict : 'EA',
            scope : {
                buttonLoader : '='
            },
            link : function(scope, element, attributes) {
                var generateButton = function() {
                    var buttonContent = element.text(),
                        width = element[0].offsetWidth;

                    $timeout(function() {
                        element.empty()
                            .css({ width: width + 'px' })
                            .addClass('btn-loader')
                            .append('<p>'+buttonContent.toString()+'</p>')
                            .append('<i class="fa fa-cog fa-spin"></i>');
                    });
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

(function() {
    'use strict';

    angular.module('journal.shared.journalLoader')
        .directive('journalLoader', ['CONFIG', JournalLoaderDirective]);

    function JournalLoaderDirective(CONFIG) {
        return {
            restrict : 'EA',
            replace : true,
            templateUrl : CONFIG.TEMPLATE_PATH + '/journal-loader/_journal-loader.html',
            link : function(scope, element, attribute) {
                // TODO: Dynamic settings to be put as an element attribute
                var options = {
                        color: '#4d4a4c',
                        width: 4
                    };

                var spinner = new Spinner(options).spin()

                element[0].appendChild(spinner.el);
            }
        }
    }
})();

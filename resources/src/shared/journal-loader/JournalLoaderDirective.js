(function() {
    'use strict';

    angular.module('journal.shared.journalLoader')
        .directive('journalLoader', ['CONFIG', JournalLoaderDirective]);

    function JournalLoaderDirective(CONFIG) {
        return {
            restrict : 'EA',
            scope : {

            },
            templateUrl : CONFIG.TEMPLATE_PATH + '/journal-loader/_journal-loader.html',
            link : function(scope, element, attributes) {

            }
        }
    }
})();

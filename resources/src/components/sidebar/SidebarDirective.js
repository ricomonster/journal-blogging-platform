(function() {
    'use strict';

    angular.module('journal.components.sidebar')
        .directive('journalSidebar', [SidebarDirective]);

    function SidebarDirective() {
        return {
            restrict : 'E',
            templateUrl : '/assets/templates/sidebar/_sidebar-directive.html',
            replace: true,
            link : function(scope, element, attributes) {

            }
        }
    }
})();
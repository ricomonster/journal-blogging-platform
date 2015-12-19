(function() {
    'use strict';

    angular.module('journal.components.sidebar')
        .directive('journalSidebar', [SidebarDirective]);

    function SidebarDirective() {
        return {
            restrict : 'E',
            templateUrl : '/assets/templates/sidebar/_sidebar-directive.html',
            replace: true,
            controllerAs : 'sd',
            controller : ['AuthService', 'SidebarService', function(AuthService, SidebarService) {
                var vm = this;

                // controller variables
                vm.sidebar = {
                    user : AuthService.user(),
                    title : null
                };

                vm.initialize = function() {
                    SidebarService.getBlogSettings()
                        .then(function(response) {
                            if (response.settings) {
                                vm.sidebar.title = response.settings.title;
                            }
                        },
                        function(error) {

                        });
                };

                vm.initialize();
            }]
        }
    }
})();
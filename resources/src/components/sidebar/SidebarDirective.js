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
            controller : ['$rootScope', '$state', 'AuthService', 'SidebarService', function($rootScope, $state, AuthService, SidebarService) {
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

                vm.setActiveClass = function(stateName) {
                    // get the current state name
                    var state = $state.current.name;

                    return (state.indexOf(stateName) > -1);
                };

                vm.logout = function() {
                    // logout the user
                    AuthService.logout();

                    // redirect to login page
                    $state.go('login');
                };

                /**
                 * Listens for a broadcast which tells that the settings of
                 * the blog has been changed.
                 */
                $rootScope.$on('settings-update', function() {
                    // just get the settings from the API
                    SidebarService.getBlogSettings()
                        .then(function(response) {
                            if (response.settings) {
                                vm.sidebar.title = response.settings.title;
                            }
                        },
                        function(error) {

                        });
                });

                /**
                 * Listens for a broadcast event which tells that the details
                 * of the user has been changed.
                 */
                $rootScope.$on('user-update', function() {
                    // just get again the user details from the AuthService
                    vm.sidebar.user = AuthService.user();
                });

                vm.initialize();
            }]
        }
    }
})();

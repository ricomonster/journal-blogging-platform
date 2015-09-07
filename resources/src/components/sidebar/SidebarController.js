(function() {
    'use strict';

    angular.module('journal.component.sidebar')
        .controller('SidebarController', ['$state', '$rootScope', 'AuthService', 'SidebarService', SidebarController]);

    function SidebarController($state, $rootScope, AuthService, SidebarService) {
        var vm = this;
        vm.openSidebar = false;
        vm.title = 'Journal';
        vm.user = AuthService.user();

        // listen for broadcast event
        $rootScope.$on('toggle-sidebar', function() {
            vm.openSidebar = !vm.openSidebar;
        });

        vm.initialize = function() {
            // get the title of the blog
            SidebarService.getSettings('title')
                .success(function(response) {
                    vm.title = response.settings.title;
                });
        };

        /**
         * Logs out the user from the admin.
         */
        vm.logout = function() {
            // destroy token
            AuthService.logout();

            // redirect to login page
            $state.go('login');
            return;
        };

        /**
         * Once the overlay is clicked, the sidebar closes.
         */
        vm.tapOverlay = function() {
            vm.toggleSidebar();
        };

        /**
         * Opens or closes the sidebar
         */
        vm.toggleSidebar = function() {
            vm.openSidebar = !vm.openSidebar;
        };

        // fire away
        vm.initialize();
    }
})();

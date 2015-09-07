(function() {
    'use strict';

    angular.module('journal.component.sidebar')
        .controller('SidebarController', ['$state', '$rootScope', 'AuthService', SidebarController]);

    function SidebarController($state, $rootScope, AuthService) {
        var vm = this;
        vm.openSidebar = false;
        vm.user = AuthService.user();

        // listen for broadcast event
        $rootScope.$on('toggle-sidebar', function() {
            vm.openSidebar = !vm.openSidebar;
        });

        vm.logout = function() {
            // destroy token
            AuthService.logout();

            // redirect to login page
            $state.go('login');
            return;
        };

        vm.tapOverlay = function() {
            vm.openSidebar = !vm.openSidebar;
        };

        vm.toggleSidebar = function() {
            vm.openSidebar = !vm.openSidebar;
        };
    }
})();

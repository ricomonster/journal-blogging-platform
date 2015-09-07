(function() {
    'use strict';

    angular.module('journal.component.header')
        .controller('HeaderController', ['$rootScope', '$state', 'AuthService', HeaderController]);

    function HeaderController($rootScope, $state, AuthService) {
        var vm = this;

        // get user details
        vm.user = AuthService.user();

        vm.logout = function() {
            // destroy token
            AuthService.logout();

            // redirect to login page
            $state.go('login');
            return;
        };

        vm.setActiveMenu = function(menu) {
            // get the current state name
            var state = $state.current.name;
            return (state.indexOf(menu) > -1);
        };

        vm.toggleSidebar = function() {
            $rootScope.$broadcast('toggle-sidebar');
        };
    }
})();

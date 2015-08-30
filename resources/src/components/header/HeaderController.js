(function() {
    'use strict';

    angular.module('journal.component.header')
        .controller('HeaderController', ['$state', 'AuthService', HeaderController]);

    function HeaderController($state, AuthService) {
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


    }
})();

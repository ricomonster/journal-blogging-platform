(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .controller('UserCreateController', ['UserCreateService', UserCreateController]);

    function UserCreateController(UserCreateService) {
        var vm = this;

        // controller variables
        vm.processing = false;
        vm.roles = {};
        vm.user = {};

        vm.initialize = function() {
            // get the roles
            UserCreateService.getRoles().then(function(response) {
                if (response.roles) {
                    vm.roles = response.roles;
                }
            }, function(error) {
                // error handling
            })
        };

        vm.createUser = function() {
            var user = vm.user;

            // flag that we're processing
            vm.processing = true;
        };

        vm.initialize();
    }
})();

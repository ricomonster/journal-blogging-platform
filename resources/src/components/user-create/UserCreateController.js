(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .controller('UserCreateController', ['ToastrService', 'UserCreateService', UserCreateController]);

    function UserCreateController(ToastrService, UserCreateService) {
        var vm = this;

        // controller variables
        vm.errors       = {};
        vm.processing   = false;
        vm.roles        = {};
        vm.user         = {};

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

            UserCreateService.createUser(user).then(function(response) {
                if (response.user) {
                    // empty the fields
                    vm.user = {};

                    // show success message
                }
            }, function(error) {
                if (error.errors) {
                    ToastrService.toast('Oops, there some errors encountered.', 'error');

                    vm.errors = error.errors;
                }
            });
        };

        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userCreate')
        .controller('UserCreateController', ['ToastrService', 'UserCreateService', UserCreateController]);

    function UserCreateController(ToastrService, UserCreateService) {
        var vm = this;
        // variables needed
        vm.user = [];
        vm.errors = [];
        vm.processing = false;

        vm.createUser = function() {
            // clear the errors
            vm.errors = [];

            // flag that a request is being processed
            vm.processing = true;

            // send request
            UserCreateService.createUser(vm.user)
                .success(function(response) {
                    // user successfully created
                    if (response.user) {
                        vm.processing = false;

                        // clear the form
                        vm.user = [];
                        // show success message
                        ToastrService
                            .toast('You have successfully added ' + response.user.name, 'success');
                    }
                })
                .error(function(response) {
                    if (response.errors) {
                        vm.processing = false;

                        // tell there's an error
                        ToastrService.toast('There are errors encountered.', 'error');

                        vm.errors = response.errors;

                        // show the errors
                        for (var e in response.errors) {
                            ToastrService.toast(response.errors[e][0], 'error');
                        }
                    }
                });
        };
    }
})();

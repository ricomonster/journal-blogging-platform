(function() {
    'use strict';

    angular.module('journal.component.userCreate')
        .controller('UserCreateController', ['GrowlService', 'UserCreateService', UserCreateController]);

    function UserCreateController(GrowlService, UserCreateService) {
        var vm = this;
        // variables needed
        vm.user = [];
        vm.errors = [];

        vm.createUser = function() {
            // clear the errors
            vm.errors = [];

            // send request
            UserCreateService.createUser(vm.user)
                .success(function(response) {
                    // user successfully created
                    if (response.user) {
                        // clear the form
                        vm.user = [];
                        // show success message
                        GrowlService
                            .growl('You have successfully added ' + response.user.name, 'success');
                    }
                })
                .error(function(response) {
                    if (response.errors) {
                        // tell there's an error
                        GrowlService.growl('There are errors encountered.', 'error');

                        vm.errors = response.errors;

                        // show the errors
                        for (var e in response.errors) {
                            GrowlService.growl(response.errors[e][0], 'error');
                        }
                    }
                });
        };
    }
})();
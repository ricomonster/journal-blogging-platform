(function() {
    'use strict';

    angular.module('journal.components.login')
        .controller('LoginController', ['$state', 'AuthService', 'LoginService', 'ToastrService', LoginController]);

    /**
     *
     * @param $state
     * @param AuthService
     * @param LoginService
     * @param ToastrService
     * @constructor
     */
    function LoginController($state, AuthService, LoginService, ToastrService) {
        var vm = this;
        // scope variables
        vm.login        = {};
        vm.processing   = false;

        /**
         * Authenticate the login credentials to gain access to the app.
         */
        vm.authenticateLogin = function() {
            var login = vm.login;

            // flag to be processed
            vm.processing = true;

            // perform API request
            LoginService.authenticate(login.email, login.password)
                .then(function(response) {
                    // save user and token
                    if (response.user && response.token) {
                        // greet the user
                        ToastrService.toast('Welcome back, ' + response.user.name);

                        // save
                        AuthService.login(response.user, response.token);

                        // redirect
                        $state.go('post.lists');
                        return;
                    }

                    vm.processing = false;
                },
                function(error) {
                    vm.processing = false;

                    // catch and show the errors
                    var messages = error.errors.message;

                    if (messages) {
                        // show error
                        ToastrService.toast(messages, 'error');
                        return;
                    }

                    // 500/server error?
                });
        };
    }
})();

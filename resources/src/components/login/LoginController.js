(function() {
    'use strict';

    angular.module('journal.component.login')
        .controller('LoginController', ['$state', 'AuthService', 'GrowlService', 'LoginService', LoginController]);

    function LoginController($state, AuthService, GrowlService, LoginService) {
        var vm = this;

        vm.login = [];

        vm.authenticate = function() {
            var login = vm.login;

            // do an API request to authenticate inputted user credentials
            LoginService.authenticate(login.email, login.password)
                .success(function(response) {
                    if (response.user && response.token) {
                        // save the user details
                        AuthService.login(response.user);

                        // save the token
                        AuthService.setToken(response.token);

                        GrowlService.growl('Welcome, ' + response.user.name, 'success');

                        // redirect
                        $state.go('post.lists');
                        return;
                    }
                })
                .error(function(response) {
                    var message = response.errors.message;
                    // show message
                    GrowlService.growl(message, 'error');
                });
        };
    }
})();

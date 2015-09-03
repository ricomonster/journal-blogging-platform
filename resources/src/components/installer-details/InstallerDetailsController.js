(function() {
    'use strict';

    angular.module('journal.component.installerDetails')
        .controller('InstallerDetailsController', ['$rootScope', '$state', 'AuthService', 'ToastrService', 'InstallerDetailsService', InstallerDetailsController]);

    function InstallerDetailsController($rootScope, $state, AuthService, ToastrService, InstallerDetailsService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 2);

        var vm = this;

        vm.account = [];
        vm.errors = [];

        vm.createAccount = function() {
            InstallerDetailsService.createAccount(vm.account)
                .success(function(response) {
                    if (response.token) {
                        // login the user
                        AuthService.login(response.user);
                        // save the token
                        AuthService.setToken(response.token);
                        // redirect
                        $state.go('installer.success');
                    }
                })
                .error(function(response) {
                    // tell that there's an error
                    ToastrService.toast('There are some errors encountered.', 'error');

                    if (response.errors) {
                        // show the error in the template
                        vm.errors = response.errors;
                    }
                });
        };
    }
})();

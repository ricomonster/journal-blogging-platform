(function() {
    'use strict';

    angular.module('journal.component.installerSuccess')
        .controller('InstallerSuccessController', ['$rootScope', '$state', 'AuthService', 'ToastrService', InstallerSuccessController]);

    function InstallerSuccessController($rootScope, $state, AuthService, ToastrService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 3);

        var vm = this;

        /**
         * Checks if there's a user logged in
         */
        vm.initialize = function() {
            // check first if there's a logged in user and token
            if (!AuthService.user() && !AuthService.getToken()) {
                // growl it first!
                ToastrService.toast('Hey, something went wrong. Can you repeat again?', 'error');
                // redirect
                $state.go('installer.start');
                return;
            }
        };

        vm.go = function() {
            $state.go('post.lists');
        };

        // fire away!
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installerStart')
        .controller('InstallerStartController', [
            '$rootScope', '$state', 'InstallerStartService', 'ToastrService', InstallerStartController]);

    function InstallerStartController($rootScope, $state, InstallerStartService, ToastrService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 1);

        var vm = this;
        vm.processing = false;

        vm.install = function() {
            vm.processing = true;

            InstallerStartService.install()
                .success(function(response) {
                    if (response.installed) {
                        $state.go('installer.details');
                    }
                })
                .error(function() {
                    vm.processing = false;

                    ToastrService.toast('Something went wrong.', 'error');
                });
        };
    }
})();

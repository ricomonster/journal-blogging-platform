(function() {
    'use strict';

    angular.module('journal.component.installerStart')
        .controller('InstallerStartController', ['$rootScope', '$state', 'AuthService', 'GrowlService', InstallerStartController]);

    function InstallerStartController($rootScope) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 1);
    }
})();

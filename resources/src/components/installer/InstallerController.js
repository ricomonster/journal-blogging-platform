(function() {
    'use strict';

    angular.module('journal.component.installer')
        .controller('InstallerController', ['$rootScope', InstallerController]);

    function InstallerController($rootScope) {
        var vm = this;

        // listen for broadcast event
        $rootScope.$on('installer-menu', function(response, data) {
            vm.active = (data || 1);
        });
    }
})();

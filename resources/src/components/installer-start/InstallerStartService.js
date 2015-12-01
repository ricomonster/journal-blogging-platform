(function() {
    'use strict';

    angular.module('journal.component.installerStart')
        .service('InstallerStartService', ['$http', 'CONFIG', InstallerStartService]);

    function InstallerStartService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.install = function() {
            return $http.get(this.apiUrl + '/installer/install');
        }
    }
})();
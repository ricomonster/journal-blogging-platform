(function() {
    'use strict';

    angular.module('journal.component.installerDetails')
        .service('InstallerDetailsService', ['$http', 'CONFIG', InstallerDetailsService]);

    function InstallerDetailsService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createAccount = function(data) {
            return $http.post(this.apiUrl + '/installer/create_account', {
                email       : (data.email || ''),
                name        : (data.name || ''),
                password    : (data.password || ''),
                title       : (data.title || '')
            });
        };
    }
})();

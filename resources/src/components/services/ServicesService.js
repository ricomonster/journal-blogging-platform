(function() {
    'use strict';

    angular.module('journal.component.services')
        .service('ServicesService', ['$http', 'AuthService', 'CONFIG', ServicesService]);

    function ServicesService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getServices = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };

        this.saveServices = function(settings) {
            var requests = {},
                token = AuthService.getToken();

            for (var s in settings) {
                requests[s] = settings[s];
            }

            return $http.post(this.apiUrl + '/settings/save?token=' + token, requests);
        };
    }
})();

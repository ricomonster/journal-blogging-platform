(function() {
    'use strict';

    angular.module('journal.component.settings')
        .service('SettingsService', ['$http', 'AuthService', 'CONFIG', SettingsService]);

    function SettingsService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };

        this.saveSettings = function(settings) {
            var requests = {},
                token = AuthService.getToken();

            for (var s in settings) {
                requests[s] = settings[s];
            }

            return $http.post(this.apiUrl + '/settings/save?token=' + token, requests);
        };
    }
})();

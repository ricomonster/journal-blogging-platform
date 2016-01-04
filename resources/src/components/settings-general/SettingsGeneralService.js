(function() {
    'use strict';

    angular.module('journal.components.settingsGeneral')
        .service('SettingsGeneralService', ['$http', '$q', 'AuthService', 'CONFIG', SettingsGeneralService]);

    function SettingsGeneralService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/settings/get?fields='+fields)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.saveSettings = function(settings) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                request     = {};

            // prepare the request
            for (var s in settings) {
                request[s] = settings[s];
            }

            // send request to the API
            $http.post(this.apiUrl + '/settings/save?token=' + token, request)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.themes = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/settings/themes')
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        }
    }
})();

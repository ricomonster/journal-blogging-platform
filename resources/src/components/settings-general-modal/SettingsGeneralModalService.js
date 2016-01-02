(function() {
    'use strict';

    angular.module('journal.components.settingsGeneralModal')
        .service('SettingsGeneralModalService', ['$http', '$q', 'AuthService', 'CONFIG',SettingsGeneralModalService]);

    function SettingsGeneralModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

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
    }
})();

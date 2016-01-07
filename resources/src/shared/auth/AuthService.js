(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['$http', '$q', 'StorageService', 'CONFIG', AuthService]);

    function AuthService($http, $q, StorageService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.login = function(user, token) {
            // save the user details
            StorageService.set('user', user);

            // save the token details
            StorageService.set('token', token);
        };

        this.logout = function() {
            StorageService.remove('user');
            StorageService.remove('token');
        };

        this.token = function() {
            return StorageService.get('token');
        };

        this.update = function(key, value) {
            return StorageService.set(key, value);
        };

        this.user = function() {
            return StorageService.get('user');
        };

        this.validateInstallation = function() {

        };

        this.validateToken = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/auth/check?token=' + this.token())
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

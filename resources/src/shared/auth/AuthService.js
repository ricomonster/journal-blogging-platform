(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['$http', 'StorageService', 'CONFIG', AuthService]);

    function AuthService($http, StorageService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.checkInstallation = function() {
            return $http.get(this.apiUrl + '/auth/check_installation');
        };

        this.checkToken = function() {
            return $http.get(this.apiUrl + '/auth/check?token=' + this.getToken());
        };

        this.getToken = function() {
            return StorageService.get('token');
        };
        
        this.login = function(user) {
            return StorageService.set('user', JSON.stringify(user));
        };

        this.logout = function() {
            // remove user
            StorageService.remove('user');
            // remove token
            StorageService.remove('token');
        };

        this.setToken = function(token) {
            return StorageService.set('token', token);
        };

        this.user = function() {
            return JSON.parse(StorageService.get('user'));
        };
    }
})();

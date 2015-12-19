(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['StorageService', AuthService]);

    function AuthService(StorageService) {
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

        this.user = function() {
            return StorageService.get('user');
        };
    }
})();
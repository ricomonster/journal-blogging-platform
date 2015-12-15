(function() {
    'use strict';

    angular.module('journal.component.userCreate')
        .service('UserCreateService', ['$http', 'AuthService', 'CONFIG', UserCreateService]);

    function UserCreateService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createUser = function(user) {
            var token = AuthService.getToken();

            return $http.post(this.apiUrl + '/users/create?token=' + token, {
                email       : (user.email || ''),
                password    : (user.password || ''),
                name        : (user.name || '')
            });
        };
    }
})();

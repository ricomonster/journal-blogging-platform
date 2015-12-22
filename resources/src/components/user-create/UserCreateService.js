(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .service('UserCreateService', ['$http', '$q', 'AuthService', 'CONFIG', UserCreateService]);

    function UserCreateService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createUser = function(user) {
            var deferred = $q.defer(),
                token = AuthService.token();

            $http.post(this.apiUrl + '/users/create?token=' + token, {
                    name        : user.name || '',
                    email       : user.email || '',
                    password    : user.password || '',
                    role        : user.role || ''
                })
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
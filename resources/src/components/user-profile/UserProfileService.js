(function() {
    'use strict';

    angular.module('journal.components.userProfile')
        .service('UserProfileService', [
            '$http', '$q', 'AuthService', 'CONFIG', UserProfileService]);

    function UserProfileService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUser = function(id) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/users/get_user?user_id=' + id || '')
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.updateUserDetails = function(user) {
            var deferred = $q.defer();
        };
    }
})();

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

        this.updateProfileDetails = function(user) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                userId      = user.id || '';

            $http.post(this.apiUrl + '/users/update_profile?user_id='+userId+'&token='+token, {
                    name        : user.name         || '',
                    email       : user.email        || '',
                    biography   : user.biography    || '',
                    location    : user.location     || '',
                    website     : user.website      || '',
                    avatar_url  : user.avatar_url   || '',
                    cover_url   : user.cover_url    || ''
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

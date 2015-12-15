(function() {
    'use strict';

    angular.module('journal.component.userProfile')
        .service('UserProfileService', ['$http', 'AuthService', 'CONFIG', UserProfileService]);

    function UserProfileService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUser = function(userId) {
            return $http.get(this.apiUrl + '/users/get_user?user_id=' + userId);
        };

        this.updatePassword = function(data) {
            var token = AuthService.getToken(),
                userId = AuthService.user().id;

            return $http.post(this.apiUrl + '/users/change_password?token=' + token + '&user_id=' + userId, {
                old_password    : data.old_password || '',
                new_password    : data.new_password || '',
                repeat_password : data.repeat_password || ''
            });
        };

        this.updateUserDetails = function(data) {
            var token = AuthService.getToken(),
                userId = (data.id || '');

            return $http.post(this.apiUrl + '/users/update_details?token=' + token + '&user_id=' + userId, {
                name        : (data.name || ''),
                email       : (data.email || ''),
                biography   : (data.biography || ''),
                location    : (data.location || ''),
                website     : (data.website || ''),
                cover_url   : (data.cover_url || ''),
                avatar_url  : (data.avatar_url || '')
            });
        };
    }
})();

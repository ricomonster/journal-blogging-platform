(function() {
    'use strict';

    angular.module('journal.component.deletePostModal')
        .service('DeletePostModalService', ['$http', 'AuthService', 'CONFIG', DeletePostModalService]);

    function DeletePostModalService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.deletePost = function(id) {
            var token = AuthService.getToken();

            return $http.post(this.apiUrl + '/posts/delete?token=' + token, {
                post_id : id
            });
        };
    }
})();

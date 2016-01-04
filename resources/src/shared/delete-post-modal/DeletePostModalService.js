(function() {
    'use strict';

    angular.module('journal.shared.deletePostModal')
        .service('DeletePostModalService', [
            '$http', '$q', 'AuthService', 'CONFIG', DeletePostModalService]);

    function DeletePostModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        /**
         * Sends a request to delete a post.
         *
         * @param postId
         * @returns {*}
         */
        this.deletePost = function(postId) {
            var deferred = $q.defer(),
                token = AuthService.token(),
                postId = postId || '';

            $http.delete(this.apiUrl + '/posts/delete?post_id=' + postId + '&token='+token)
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

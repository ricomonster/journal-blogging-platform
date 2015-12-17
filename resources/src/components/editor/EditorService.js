(function() {
    'use strict';

    angular.module('journal.components.editor')
        .service('EditorService', ['$http', '$q', 'CONFIG', EditorService]);

    function EditorService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getPost = function(postId) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/posts/get_post?post_id=' + postId)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        }
    }
})();
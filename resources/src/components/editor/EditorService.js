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
        };

        this.getSlug = function(title, id) {
            var deferred = $q.defer(),
                parameters = 'slug='+(title || '');

            // check if there's an ID given
            if (id) {
                // append post id to the parameter
                parameters += '&post_id=' + id;
            }

            // perform request to the API
            $http.get(this.apiUrl + '/posts/check_slug?' + parameters)
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
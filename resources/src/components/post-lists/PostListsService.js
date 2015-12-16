(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .service('PostListsService', ['$http', '$q', 'CONFIG', PostListsService]);

    function PostListsService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getAllPosts = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/posts/all')
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
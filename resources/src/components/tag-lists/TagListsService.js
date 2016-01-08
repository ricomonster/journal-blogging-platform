(function() {
    'use strict';

    angular.module('journal.components.tagLists')
        .service('TagListsService', ['$http', '$q', 'AuthService', 'CONFIG', TagListsService]);

    function TagListsService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createTag = function(tag) {
            var deferred    = $q.defer(),
                token       = AuthService.token();

            // perform API request
            $http.post(this.apiUrl + '/tags/create?token=' + token, {
                    name        : tag.name          || '',
                    slug        : tag.slug          || '',
                    image_url   : tag.image_url     || '',
                    description : tag.description   || ''
                })
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.getTags = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/tags/all')
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

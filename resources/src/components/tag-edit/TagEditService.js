(function() {
    'use strict';

    angular.module('journal.components.tagEdit')
        .service('TagEditService', ['$http', '$q', 'AuthService', 'CONFIG', TagEditService]);

    function TagEditService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getTag = function(tagId) {
            var deferred = $q.defer(),
                token = AuthService.token(),
                url = this.apiUrl + '/tags/get_tag?tag_id=' + (tagId || '') +
                    '&token=' + token;

            $http.get(url)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.updateTagDetails = function(tag) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                url         = this.apiUrl + '/tags/update?tag_id=' +
                    (tag.id || '') + '&token=' + token;

            // prepare the request
            $http.put(url, {
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
    }
})();

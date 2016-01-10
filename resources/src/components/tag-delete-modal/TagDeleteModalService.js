(function() {
    'use strict';

    angular.module('journal.components.tagDeleteModal')
        .service('TagDeleteModalService', ['$http', '$q', 'AuthService', 'CONFIG', TagDeleteModalService]);

    function TagDeleteModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.deleteTag = function(tagId) {
            var deferred = $q.defer(),
                token = AuthService.token(),
                url = this.apiUrl + '/tags/delete_tag?tag_id=' + (tagId || '');

            $http.delete(url + '&token=' + token)
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

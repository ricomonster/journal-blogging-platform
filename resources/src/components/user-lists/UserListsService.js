(function() {
    'use strict';

    angular.module('journal.components.userLists')
        .service('UserListsService', ['$http', '$q', 'CONFIG', UserListsService]);

    function UserListsService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUsers = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/users/all')
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
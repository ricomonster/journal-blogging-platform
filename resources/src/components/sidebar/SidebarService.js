(function() {
    'use strict';

    angular.module('journal.components.sidebar')
        .service('SidebarService', ['$http', '$q', 'CONFIG', SidebarService]);

    function SidebarService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getBlogSettings = function() {
            var deferred = $q.defer(),
                fields = 'title';

            $http.get(this.apiUrl + '/settings/get?fields=' + fields)
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
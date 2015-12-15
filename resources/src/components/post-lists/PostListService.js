(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .service('PostListService', ['$http', 'CONFIG', PostListService]);

    function PostListService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getPosts = function() {
            return $http.get(this.apiUrl + '/posts/all');
        };
    }
})();

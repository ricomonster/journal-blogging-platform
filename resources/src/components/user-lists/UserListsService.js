(function() {
    'use strict';

    angular.module('journal.component.userLists')
        .service('UserListsService', ['$http', 'CONFIG', UserListsService]);

    function UserListsService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getAllUsers = function() {
            return $http.get(this.apiUrl + '/users/all');
        };
    }
})();

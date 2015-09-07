(function() {
    'use strict';

    angular.module('journal.component.sidebar')
        .service('SidebarService', ['$http', 'CONFIG', SidebarService]);

    function SidebarService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.components.tagEdit')
        .service('TagEditService', ['$http', '$q', 'AuthService', 'CONFIG', TagEditService]);

    function TagEditService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;
    }
})();

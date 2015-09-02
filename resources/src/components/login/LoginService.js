(function() {
    'use strict';

    angular.module('journal.component.login')
        .service('LoginService', ['$http', 'CONFIG', LoginService]);

    function LoginService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.authenticate = function(email, password) {
            return $http.post(this.apiUrl + '/auth/authenticate', {
                email : (email || ''),
                password : (password || '')
            });
        };
    }
})();

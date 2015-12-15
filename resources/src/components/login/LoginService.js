(function() {
    'use strict';

    angular.module('journal.components.login')
        .service('LoginService', ['$http', '$q', 'CONFIG', LoginService]);

    /**
     *
     * @param $http
     * @param $q
     * @param CONFIG
     * @constructor
     */
    function LoginService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        /**
         *
         * @param email
         * @param password
         * @returns {*}
         */
        this.authenticate = function(email, password) {
            var deferred = $q.defer(),
                parameters = {
                    email       : email,
                    password    : password
                };

            $http.post(this.apiUrl + '/auth/authenticate', parameters)
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
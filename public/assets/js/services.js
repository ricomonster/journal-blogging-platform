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
(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .service('PostListsService', ['$http', '$q', 'CONFIG', PostListsService]);

    function PostListsService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getAllPosts = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/posts/all')
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
(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['StorageService', AuthService]);

    function AuthService(StorageService) {
        this.login = function(user, token) {
            // save the user details
            StorageService.set('user', user);

            // save the token details
            StorageService.set('token', token);
        };

        this.logout = function() {
            StorageService.remove('user');
            StorageService.remove('token');
        };

        this.token = function() {
            return StorageService.get('user');
        };

        this.user = function() {
            return StorageService.get('user');
        };
    }
})();
(function() {
    'use strict';

    angular.module('journal.shared.storage')
        .service('StorageService', ['localStorageService', StorageService]);

    /**
     * Ease the usage of the Local Storage.
     *
     * @param localStorageService
     * @constructor
     */
    function StorageService(localStorageService) {
        /**
         * Save the data to the local storage.
         *
         * @param key
         * @param value
         * @returns {*}
         */
        this.set = function(key, value) {
            return localStorageService.set(key, value);
        };

        /**
         * Fetches a saved data from the local storage.
         *
         * @param key
         * @returns {*}
         */
        this.get = function(key) {
            return localStorageService.get(key);
        };

        /**
         * Removes a data from the local storage.
         *
         * @param key
         * @returns {*}
         */
        this.remove = function(key) {
            return localStorageService.remove(key);
        };
    }
})();
(function() {
    'use strict';

    angular.module('journal.shared.toastr')
        .service('ToastrService', ['toastr', ToastrService]);

    function ToastrService(toastr) {
        this.toast = function(message, type) {
            switch (type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning('message');
                    break;
                default:
                    toastr.success(message);
                    break;
            }
        };
    }
})();


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

(function() {
    'use strict';

    angular.module('journal.shared.storage')
        .service('StorageService', ['localStorageService', StorageService]);

    function StorageService(localStorageService) {
        this.set = function(key, value) {
            return localStorageService.set(key, value);
        };

        this.get = function(key) {
            return localStorageService.get(key);
        };

        this.remove = function(key) {
            return localStorageService.remove(key);
        };
    }
})();

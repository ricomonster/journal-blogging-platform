(function() {
    'use strict';

    angular.module('journal.config')
        .config(['$httpProvider', HttpProviderConfiguration])
        .config(['toastrConfig', ToastrConfiguration])
        .config(['localStorageServiceProvider', LocalStorageConfiguration]);

    function ToastrConfiguration(toastrConfig) {
        angular.extend(toastrConfig, {
            autoDismiss: false,
            containerId: 'toast-container',
            maxOpened: 5,
            newestOnTop: false,
            positionClass: 'toast-top-right',
            preventDuplicates: false,
            preventOpenDuplicates: true,
            target: 'body',
            timeOut: 10000
        });
    }

    function HttpProviderConfiguration($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }

    function LocalStorageConfiguration(localStorageServiceProvider) {
        localStorageServiceProvider.setPrefix('journal');
    }
})();

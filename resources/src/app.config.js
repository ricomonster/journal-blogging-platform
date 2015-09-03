(function() {
    'use strict';

    angular.module('journal.config')
        .config(['$httpProvider', HttpProviderConfiguration])
        .config(['growlProvider', GrowlConfiguration])
        .config(['localStorageServiceProvider', LocalStorageConfiguration]);

    function GrowlConfiguration(growlProvider) {
        growlProvider
            // growl position on the screen
            .globalPosition('top-right')
            // will close after given number of ms
            .globalTimeToLive(10000);
    }

    function HttpProviderConfiguration($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }

    function LocalStorageConfiguration(localStorageServiceProvider) {
        localStorageServiceProvider.setPrefix('journal');
    }
})();

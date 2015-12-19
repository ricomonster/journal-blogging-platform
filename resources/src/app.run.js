(function() {
    'use strict';

    angular.module('journal.run')
        .run(['$rootScope', '$state', '$timeout', 'AuthService', 'ngProgressLite', AuthenticateRoutes]);

    function AuthenticateRoutes($rootScope, $state, $timeout, AuthService, ngProgressLite) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            // start ngprogress
            ngProgressLite.start();

            // check if the next route needs to be authenticated
            if (toState.authenticate && !AuthService.user()) {
                // redirect to login page
                $state.transitionTo('login');
                event.preventDefault();
            }

            // check if the next page is the login page
            if (toState.name == 'login' && AuthService.user()) {
                // user is logged in, redirect to post lists
                $state.transitionTo('post.lists');
                event.preventDefault();
            }
        });

        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
            $timeout(function() {
                ngProgressLite.done();
            });
        });
    }
})();
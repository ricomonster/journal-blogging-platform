(function() {
    'use strict';

    angular.module('journal.run')
        .run(['$rootScope', '$state', '$timeout', 'AuthService', 'ngProgressLite', AuthenticatedRoutes])
        .run(['$state', 'AuthService', CheckAuthentication]);

    /**
     *
     * @param $rootScope
     * @param $state
     * @param AuthService
     * @constructor
     */
    function AuthenticatedRoutes($rootScope, $state, $timeout, AuthService, ngProgressLite) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            ngProgressLite.start();

            // check if the state to be loaded needs to be authenticated and
            // check if the there's a logged in user
            if (!toState.installer && toState.authenticate && !AuthService.user()) {
                $state.transitionTo('login');
                event.preventDefault();
            }

            // check if the next page is installer page
            if (toState.name == 'installer' || toState.name == 'installer.start') {
                // check if journal is already installed
                AuthService.checkInstallation()
                    .success(function(response) {
                        if (response.installed) {
                            // TODO: show 404 page
                            $state.transitionTo('login');
                            event.preventDefault();
                            return;
                        }
                    });
            }

            // check if the next page is login page and if the user is already logged in
            if (toState.name == 'login' && (AuthService.user() && AuthService.getToken())) {
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

    /**
     * Checks if the there's a provided token and checks if the token is valid.
     * This will run once the page loads.
     *
     * @param $state
     * @param AuthService
     * @constructor
     */
    function CheckAuthentication($state, AuthService) {
        // check if its installed properly
        AuthService.checkInstallation()
            .success(function(response) {
                if (!response.installed) {
                    $state.transitionTo('installer');
                    event.preventDefault();
                    return;
                }
            });

        if (!AuthService.getToken() && !AuthService.user()) {
            // logout the user
            AuthService.logout();
            // redirect
            $state.transitionTo('login');
            event.preventDefault();
            return;
        }

        if (AuthService.getToken()) {
            // check
            AuthService.checkToken()
                .success(function(response) {
                    if (response.user) {
                        // update the user data saved in the local storage
                        AuthService.login(response.user);
                    }
                })
                .error(function(response) {
                    // logout the user
                    AuthService.logout();
                    // redirect
                    $state.transitionTo('login');
                    event.preventDefault();
                });
        }
    }
})();

(function() {
    'use strict';

    angular.module('journal.run')
        .run(['$rootScope', '$state', 'AuthService', RunOnBoot])
        .run(['$rootScope', '$state', '$timeout', 'AuthService', 'ngProgressLite', AuthenticateRoutes]);

    function RunOnBoot($rootScope, $state, AuthService) {
        var auth = AuthService;

        // check if the token is still valid
        if (auth.token()) {
            // send request to check validity of the token
            auth.validateToken().then(function(response) {
                if (response.user) {
                    // tell that we're finish booting up
                    $rootScope.bootFinish = true;
                    // login the user, again
                    AuthService.login(response.user, auth.token());

                    // assign it to a variable
                    var nextPage = $rootScope.nextPage;

                    // delete it from the rootscope
                    delete $rootScope.nextPage;

                    // continue loading the page
                    $state.transitionTo(nextPage.name, nextPage.params);
                }
            }, function() {
                // tell that we're finish booting up
                $rootScope.bootFinish = true;
                // logout the user and redirect to login page
                auth.logout();
                // redirect
                $state.transitionTo('login');
            });
        }

        // there's no token, force logout the user and redirect to login page
        if (!auth.token()) {
            $rootScope.bootFinish = true;
            // logout
            auth.logout();
            // redirect
            $state.transitionTo('login');
        }
    }

    function AuthenticateRoutes($rootScope, $state, $timeout, AuthService, ngProgressLite) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            // make sure that the booting functions are done
            if (!$rootScope.bootFinish) {
                // get the next page details and save to the rootscope
                $rootScope.nextPage = toState;
                // save the parameters
                $rootScope.nextPage.params = toParams;

                ngProgressLite.done();
                event.preventDefault();
                return;
            }

            // start ngprogress
            ngProgressLite.start();

            // flag by default that we're logged in
            $rootScope.loggedIn = true;

            // check if the next route needs to be authenticated
            if (toState.authenticate && !AuthService.user()) {
                // redirect to login page
                $state.transitionTo('login');
                $rootScope.loggedIn = false;
                event.preventDefault();

                // finish ngprogress
                ngProgressLite.done();
            }

            // check if the next page is the login page
            if (toState.name == 'login') {
                // login page? set the body class
                $rootScope.loggedIn = false;

                // check if the user is logged in
                if (AuthService.user()) {
                    // update the body class
                    $rootScope.loggedIn = true;

                    // user is logged in, redirect to post lists
                    $state.transitionTo('post.lists');
                    event.preventDefault();
                }
            }
        });

        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
            $timeout(function() {
                ngProgressLite.done();
            });
        });
    }
})();

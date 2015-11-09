(function() {
    'use strict';

    angular.module('Journal', [
        'journal.config',
        'journal.routes',
        'journal.run',
        'journal.constant',
        'journal.component.deletePostModal',
        'journal.component.login',
        'journal.component.header',
        'journal.component.editor',
        'journal.component.installer',
        'journal.component.installerDetails',
        'journal.component.installerStart',
        'journal.component.installerSuccess',
        'journal.component.postLists',
        'journal.component.services',
        'journal.component.settings',
        'journal.component.settingsModal',
        'journal.component.sidebar',
        'journal.component.userCreate',
        'journal.component.userLists',
        'journal.component.userProfile',
        'journal.component.userProfileModal',
        'journal.shared.auth',
        'journal.shared.buttonLoader',
        'journal.shared.fileUploader',
        'journal.shared.storage',
        'journal.shared.toastr',
        'journal.shared.markdownConverter',
        'angularMoment']);

    // app files
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constant', []);
    angular.module('journal.routes', ['ui.router']);
    angular.module('journal.run', ['journal.shared.auth', 'ngProgressLite']);

    // COMPONENTS
    angular.module('journal.component.deletePostModal', []);

    angular.module('journal.component.login', []);
    angular.module('journal.component.header', []);

    // editor
    angular.module('journal.component.editor', ['ngFileUpload', 'ngSanitize', 'ui.codemirror']);

    // installer
    angular.module('journal.component.installer', ['ui.router']);
    angular.module('journal.component.installerStart', ['ui.router']);
    angular.module('journal.component.installerDetails', ['ui.router']);
    angular.module('journal.component.installerSuccess', []);

    // post
    angular.module('journal.component.postLists', ['ui.router']);

    // services
    angular.module('journal.component.services', []);

    // settings
    angular.module('journal.component.settings', ['ui.bootstrap', 'ui.router']);
    angular.module('journal.component.settingsModal', [
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    angular.module('journal.component.sidebar', [
        'ui.bootstrap',
        'ui.router']);

    // user
    angular.module('journal.component.userCreate', ['ui.router']);
    angular.module('journal.component.userLists', ['angularMoment', 'ui.router']);
    angular.module('journal.component.userProfile', ['ui.bootstrap', 'ui.router']);
    angular.module('journal.component.userProfileModal', [
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    // SHARED
    angular.module('journal.shared.auth', []);
    angular.module('journal.shared.buttonLoader', []);
    angular.module('journal.shared.fileUploader', ['ngFileUpload']);
    angular.module('journal.shared.toastr', ['ngAnimate', 'toastr']);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.markdownConverter', ['ngSanitize']);
})();

(function() {
    'use strict';

    angular.module('journal.routes')
        .config(['$stateProvider', '$urlRouterProvider', Routes]);

    function Routes($stateProvider, $urlRouterProvider) {
        var templatePath = '/assets/templates';
        $urlRouterProvider.otherwise('/')
            .when('/', '/post/lists')
            .when('/post', '/post/lists')
            .when('/installer', '/installer/start')
            .when('/user', '/user/lists');

        // states
        $stateProvider
            // plain editor
            .state('editor', {
                url : '/editor',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/editor/editor.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('landing', {
                url : '/',
                authenticate : true,
                installer : false
            })
            .state('login', {
                url : '/login',
                templateUrl : templatePath + '/login/login.html',
                authenticate : false,
                installer : false
            })
            // Installer State Routes
            .state('installer', {
                url : '/installer',
                templateUrl : templatePath + '/installer/installer.html',
                authenticate : false,
                installer : true,
                abstract: true
            })
            .state('installer.start', {
                url : '/start',
                views : {
                    'installer_content' : {
                        templateUrl : templatePath + '/installer-start/installer-start.html'
                    }
                }
            })
            .state('installer.details', {
                url : '/details',
                views : {
                    'installer_content' : {
                        templateUrl : templatePath + '/installer-details/installer-details.html'
                    }
                },
                authenticate : false,
                installer : true
            })
            .state('installer.success', {
                url : '/success',
                views : {
                    'installer_content' : {
                        templateUrl : templatePath + '/installer-success/installer-success.html'
                    }
                },
                authenticate : false,
                installer : true
            })
            // Installer Post Module Routes
            .state('post', {
                url : '/post',
                abstract: true,
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/post/post.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('post.lists', {
                url : '/lists',
                views : {
                    'post_content' : {
                        templateUrl : templatePath + '/post-lists/post-lists.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('postEditor', {
                url : '/editor/:postId',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/editor/editor.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            // services
            .state('services', {
                url : '/services',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/services/services.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            // settings
            .state('settings', {
                url : '/settings',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/settings/settings.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('user', {
                url : '/user',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath + '/user/user.html'
                    },
                    'header_content': {
                        templateUrl: templatePath + '/header/header.html'
                    },
                    'sidebar_content' : {
                        templateUrl : templatePath + '/sidebar/sidebar.html'
                    }
                },
                authenticate : true,
                installer : false,
                abstract: true
            })
            .state('user.lists', {
                url : '/lists',
                views : {
                    'user_content' : {
                        templateUrl : templatePath + '/user-lists/user-lists.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('user.create', {
                url : '/create',
                views : {
                    'user_content' : {
                        templateUrl : templatePath + '/user-create/user-create.html'
                    }
                },
                authenticate : true,
                installer : false
            })
            .state('user.profile', {
                url : '/profile/:userId',
                views : {
                    'user_content' : {
                        templateUrl : templatePath + '/user-profile/user-profile.html'
                    }
                },
                authenticate : true,
                installer : false
            });
    }
})();

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

(function() {
    'use strict';

    angular.module('journal.run')
        .run(['$rootScope', '$state', '$timeout', 'AuthService', 'ngProgressLite', AuthenticatedRoutes])
        .run(['$state', 'AuthService', CheckAuthentication]);

    /**
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
            if (toState.name.indexOf('installer') > 0) {
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
                    $state.transitionTo('installer.start');
                    return;
                }
            });

        if (!AuthService.getToken() && !AuthService.user()) {
            // logout the user
            AuthService.logout();
            // redirect
            $state.transitionTo('login');
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
                    return;
                });
        }
    }
})();

(function() {
    'use strict';

    angular.module('journal.constant')
        .constant('CONFIG', {
            'API_URL' : '/api/v1.0',
            'DEFAULT_AVATAR_URL' : 'http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg',
            'DEFAULT_COVER_URL' : '/assets/images/wallpaper.jpg',
            'VERSION' : '1.0.0'
        });
})();

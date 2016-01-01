(function() {
    'use strict';

    angular.module('Journal', [
        // APP
        'journal.config',
        'journal.constants',
        'journal.routes',
        'journal.run',
        // COMPONENTS
        'journal.components.editor',
        'journal.components.login',
        //'journal.components.post',
        'journal.components.postLists',
        'journal.components.sidebar',
        'journal.components.user',
        'journal.components.userCreate',
        'journal.components.userLists',
        'journal.components.userProfile',
        'journal.components.userProfileModal',
        // SHARED
        'journal.shared.auth',
        'journal.shared.fileUploader',
        'journal.shared.markdownReader',
        'journal.shared.storage',
        'journal.shared.toastr']);

    // APP
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constants', []);
    angular.module('journal.routes', ['ui.router', 'journal.constants']);
    angular.module('journal.run', ['ngProgressLite']);

    // COMPONENTS
    // Editor
    angular.module('journal.components.editor', ['ngFileUpload', 'ui.bootstrap', 'ui.codemirror']);

    // Login
    angular.module('journal.components.login', []);

    // Posts
    angular.module('journal.components.post', []);
    angular.module('journal.components.postLists', []);

    // Sidebar
    angular.module('journal.components.sidebar', []);

    // Users
    angular.module('journal.components.user', []);
    angular.module('journal.components.userCreate', []);
    angular.module('journal.components.userLists', ['angularMoment']);
    angular.module('journal.components.userProfile', []);
    angular.module('journal.components.userProfileModal', []);

    // SHARED
    angular.module('journal.shared.auth', []);
    angular.module('journal.shared.fileUploader', ['ngFileUpload']);
    angular.module('journal.shared.markdownReader', []);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.toastr', ['ngAnimate', 'toastr']);
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
            positionClass: 'toast-bottom-left',
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

    angular.module('journal.routes')
        .config(['$stateProvider', '$urlRouterProvider', 'CONFIG', Routes]);

    function Routes($stateProvider, $urlRouterProvider, CONFIG) {
        var templatePath = function(filename) {
            return CONFIG.TEMPLATE_PATH + filename;
        };

        // default endpoint if page/state does not exists
        $urlRouterProvider.otherwise('/')
            .when('/', '/post/lists');

        // state configuration
        $stateProvider
            // EDITOR
            .state('editor', {
                url : '/editor',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath('editor/editor.html')
                    },
                    'sidebar' : {
                        templateUrl : templatePath('sidebar/sidebar.html')
                    }
                },
                authenticate : true
            })
            .state('editorPost', {
                url : '/editor/:postId',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath('editor/editor.html')
                    },
                    'sidebar' : {
                        templateUrl : templatePath('sidebar/sidebar.html')
                    }
                },
                authenticate : true
            })
            // LOGIN
            .state('login', {
                url : '/login',
                templateUrl : templatePath('login/login.html'),
                authenticate : false
            })
            // POSTS
            .state('post', {
                abstract : true,
                url : '/post',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath('post/post.html')
                    },
                    'sidebar' : {
                        templateUrl : templatePath('sidebar/sidebar.html')
                    }
                },
                authenticate : true
            })
            .state('post.lists', {
                url : '/lists',
                views : {
                    'post_content' : {
                        templateUrl : templatePath('post-lists/post-lists.html')
                    }
                },
                authenticate : true
            })
            // USER
            .state('user', {
                url : '/user',
                views : {
                    '' : {
                        templateUrl : templatePath('user/user.html')
                    },
                    'sidebar' : {
                        templateUrl : templatePath('sidebar/sidebar.html')
                    }
                },
                authenticate : true
            })
            .state('user.create', {
                url : '/create',
                views : {
                    'user_content' : {
                        templateUrl : templatePath('user-create/user-create.html')
                    }
                },
                authenticate : true
            })
            .state('user.lists', {
                url : '/lists',
                views : {
                    'user_content' : {
                        templateUrl : templatePath('user-lists/user-lists.html')
                    }
                },
                authenticate : true
            })
            .state('user.profile', {
                url : '/profile/:userId',
                views : {
                    'user_content' : {
                        templateUrl : templatePath('user-profile/user-profile.html')
                    }
                }
            });
    }
})();

(function() {
    'use strict';

    angular.module('journal.run')
        .run(['$rootScope', '$state', '$timeout', 'AuthService', 'ngProgressLite', AuthenticateRoutes]);

    function AuthenticateRoutes($rootScope, $state, $timeout, AuthService, ngProgressLite) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            // start ngprogress
            ngProgressLite.start();

            $rootScope.loggedIn = true;

            // check if the next route needs to be authenticated
            if (toState.authenticate && !AuthService.user()) {
                // redirect to login page
                $state.transitionTo('login');
                $rootScope.loggedIn = false;
                event.preventDefault();
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
(function() {
    'use strict';

    angular.module('journal.constants')
        .constant('CONFIG', {
            'API_URL' : 'http://localhost:8000/api/v1.0',
            'DEFAULT_AVATAR_URL' : 'http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg',
            'DEFAULT_COVER_URL' : '/assets/images/wallpaper.jpg',
            'VERSION' : '2.0.0',
            'CDN_URL' : 'http://localhost:8000',
            'TEMPLATE_PATH' : '/assets/templates/'
        });
})();

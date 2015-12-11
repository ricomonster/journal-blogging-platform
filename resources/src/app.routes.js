(function() {
    'use strict';

    angular.module('journal.routes')
        .config(['$stateProvider', '$urlRouterProvider', 'CONFIG', Routes]);

    function Routes($stateProvider, $urlRouterProvider, CONFIG) {
        var templatePath = (CONFIG.CDN_URL == '') ?
            '/assets/templates' : CONFIG.CDN_URL + '/assets/templates';

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

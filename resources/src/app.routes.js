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
            //.when('/', '/post/lists')
            .when('/post', '/post/lists');

        // state configuration
        $stateProvider
            // EDITOR
            .state('editor', {
                url : '/editor',
                views : {
                    // default ui-view
                    '' : {
                        templateUrl : templatePath('editor/editor.html')
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
            // SETTINGS
            .state('settings', {
                url : '/settings',
                views : {
                    '' : {
                        templateUrl : templatePath('settings/settings.html')
                    }
                },
                abstract : true,
                authenticate : true
            })
            .state('settings.general', {
                url : '/general',
                views : {
                    'settings_content' : {
                        templateUrl : templatePath('settings-general/settings-general.html')
                    }
                }
            })
            // USER
            .state('user', {
                url : '/user',
                views : {
                    '' : {
                        templateUrl : templatePath('user/user.html')
                    }
                },
                abstract : true,
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

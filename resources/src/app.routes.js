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
            .when('/post', '/post/lists')
            .when('/tag', '/tag/lists')
            .when('/user', '/user/lists');

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
            // ROOT
            .state('root', {
                url : '/',
                controller : ['$state', function($state) {
                    // for now let's redirect this to post.lists then later on
                    // we'll create a dashboard and that would be the permanent
                    // redirect page
                    $state.go('post.lists');
                }],
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
            // TAG
            .state('tag', {
                url : '/tag',
                views : {
                    '' : {
                        templateUrl : templatePath('tag/tag.html')
                    }
                },
                abstract : true,
                authenticate : true
            })
            .state('tag.edit', {
                url : '/edit/:tagId',
                views : {
                    'tag_content' : {
                        templateUrl : templatePath('tag-edit/tag-edit.html')
                    }
                },
                authenticate : true
            })
            .state('tag.lists', {
                url : '/lists',
                views : {
                    'tag_content' : {
                        templateUrl : templatePath('tag-lists/tag-lists.html')
                    }
                },
                authenticate : true
            })
            .state('tag.create', {
                url : '/create',
                views : {
                    'tag_content' : {
                        templateUrl : templatePath('tag-create/tag-create.html')
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

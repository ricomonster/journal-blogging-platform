(function() {
    'use strict';

    angular.module('journal.routes')
        .config(['$stateProvider', '$urlRouterProvider', Routes]);

    function Routes($stateProvider, $urlRouterProvider) {
        var templatePath = function(filename) {
            return '/assets/templates/' + filename;
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

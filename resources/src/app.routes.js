(function() {
    'use strict';

    angular.module('journal.routes')
        .config(['$stateProvider', '$urlRouterProvider', Routes]);

    function Routes($stateProvider, $urlRouterProvider) {
        var templatePath = function(filename) {
            return '/assets/templates/' + filename;
        };

        // default endpoint if page/state does not exists
        $urlRouterProvider.otherwise('/');

        // state configuration
        $stateProvider
            // LOGIN
            .state('login', {
                url : '/login',
                templateUrl : templatePath('login/login.html')
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
                }
            })
            .state('post.lists', {
                url : '/lists',
                views : {
                    'post_content' : {
                        templateUrl : templatePath('post-lists/post-lists.html')
                    }
                }
            })
    }
})();
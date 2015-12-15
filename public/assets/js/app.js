(function() {
    'use strict';

    angular.module('Journal', [
        // APP
        'journal.config',
        'journal.constants',
        'journal.routes',
        'journal.run',
        // COMPONENTS
        'journal.components.login',
        'journal.components.post',
        'journal.components.postLists',
        'journal.components.sidebar',
        // SHARED
        'journal.shared.auth',
        'journal.shared.storage',
        'journal.shared.toastr']);

    // APP
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constants', []);
    angular.module('journal.routes', ['ui.router', 'journal.constants']);
    angular.module('journal.run', []);

    // COMPONENTS
    // Login
    angular.module('journal.components.login', []);

    // Posts
    angular.module('journal.components.post', []);
    angular.module('journal.components.postLists', []);

    // Sidebar
    angular.module('journal.components.sidebar', []);

    // SHARED
    angular.module('journal.shared.auth', []);
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
(function() {
    'use strict';

    angular.module('journal.run');
})();
(function() {
    'use strict';

    angular.module('journal.constants')
        .constant('CONFIG', {
            'API_URL' : 'http://localhost:8000/api/v1.0',
            'DEFAULT_AVATAR_URL' : 'http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg',
            'DEFAULT_COVER_URL' : '/assets/images/wallpaper.jpg',
            'VERSION' : '2.0.0',
            'CDN_URL' : 'http://localhost:8000'
        });
})();
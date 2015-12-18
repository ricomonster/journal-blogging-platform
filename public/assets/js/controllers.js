(function() {
    'use strict';

    angular.module('journal.components.editor')
        .controller('EditorController', ['$stateParams', 'AuthService', 'EditorService', EditorController]);

    function EditorController($stateParams, AuthService, EditorService) {
        var vm = this;

        vm.options = {
            // codemirror
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // word counter
            counter : 0,
            // sidebar toggle
            toggle : true
        };

        // initialize the post object
        vm.post = { status : 2, tags : [] };

        /**
         * Converts timestamp to human readable date.
         * @param timestamp
         * @returns {Date}
         */
        vm.convertTimestamp = function(timestamp) {
            // check if there's a value
            if (timestamp) {
                // let's assume this is a unix timestamp
                var publishDate = new Date(timestamp * 1000);
                return new Date(
                    publishDate.getFullYear(),
                    publishDate.getMonth(),
                    publishDate.getDate(),
                    publishDate.getHours(),
                    publishDate.getMinutes())
            }

            // get the datetime today
            var date = new Date(),
                currentDate = new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate(),
                    date.getHours(),
                    date.getMinutes());

            // return the current date
            return currentDate;
        };

        /**
         * This will run once the page loads. It will check if there's a
         * parameter set and fetches the post based on the given parameter.
         */
        vm.initialize = function() {
            // set the published date/time
            vm.post.published_at = vm.convertTimestamp();

            if ($stateParams.postId) {
                // get the post details
                EditorService.getPost($stateParams.postId)
                    .then(function(response) {
                        if (response.post) {
                            // convert the timestamp to human readable date
                            response.post.published_at = vm.convertTimestamp(response.post.published_at);
                            vm.post = response.post;
                        }
                    },
                    function(error) {
                        // most likely the post is not found, redirect to 404
                    });
            }
        };

        vm.savePost = function() {
            var post = vm.post;
            console.log(post);
        };

        /**
         * Toggles the sidebar to be opened/close.
         */
        vm.toggleSidebar = function() {
            vm.options.toggle = !vm.options.toggle;
        };

        // run this shit!
        vm.initialize();
    }
})();
(function() {
    'use strict';

    angular.module('journal.components.login')
        .controller('LoginController', ['$state', 'AuthService', 'LoginService', 'ToastrService', LoginController]);

    /**
     *
     * @param $state
     * @param AuthService
     * @param LoginService
     * @param ToastrService
     * @constructor
     */
    function LoginController($state, AuthService, LoginService, ToastrService) {
        var vm = this;
        // scope variables
        vm.login        = {};
        vm.processing   = false;

        /**
         * Authenticate the login credentials to gain access to the app.
         */
        vm.authenticateLogin = function() {
            var login = vm.login;

            // flag to be processed
            vm.processing = false;

            // perform API request
            LoginService.authenticate(login.email, login.password)
                .then(function(response) {
                    // save user and token
                    if (response.user && response.token) {
                        // save
                        AuthService.login(response.user, response.token);

                        // redirect
                        $state.go('post.lists');
                        return;
                    }
                },
                function(error) {
                    // catch and show the errors
                    var messages = error.errors.message;

                    if (messages) {
                        // show error
                        ToastrService.toast(messages, 'error');
                        return;
                    }

                    // 500/server error?
                });
        };
    }
})();
(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .controller('PostListsController', ['PostListsService', PostListsController]);

    function PostListsController(PostListsService) {
        var vm = this;

        vm.active = [];
        vm.posts = {};
        vm.processing = true;

        /**
         * Fetches the posts from the API once the page loads.
         */
        vm.initialize = function() {
            PostListsService.getAllPosts()
                .then(function(response) {
                    if (response.posts) {
                        vm.posts = response.posts;

                        // get the first post and set it to active
                        vm.active = vm.posts[0];
                    }

                    vm.processing = false;
                },
                function() {
                    // flag that there's something wrong with fetching the
                    // posts from the API
                });
        };

        /**
         * Shows the selected post from the lists.
         */
        vm.selectThisPost = function(post) {
            vm.active = post;
        };

        // run it!
        vm.initialize();
    }
})();
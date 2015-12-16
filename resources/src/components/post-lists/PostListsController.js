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
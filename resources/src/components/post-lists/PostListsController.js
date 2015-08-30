(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .controller('PostListsController', ['PostListService', PostListsController]);

    function PostListsController(PostListService) {
        var vm = this;

        vm.posts = [];
        vm.activePost = null;

        vm.initialize = function() {
            // get all the posts
            PostListService.getPosts()
                .success(function(response) {
                    if (response.posts) {
                        // get the post and assign to the scope
                        vm.posts = response.posts;
                        // get the first post and make it active
                        vm.activePost = response.posts[0];
                    }
                });
        };

        /**
         * Shows the selected post and preview its content
         */
        vm.previewThisPost = function(post) {
            vm.activePost = post;
        };

        // fire away!
        vm.initialize();
    }
})();

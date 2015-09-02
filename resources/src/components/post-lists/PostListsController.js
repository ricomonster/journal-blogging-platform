(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .controller('PostListsController', ['$modal', 'PostListService', PostListsController]);

    function PostListsController($modal, PostListService) {
        var vm = this;

        vm.posts = [];
        vm.activePost = null;
        vm.activePane = 'lists';

        vm.deletePost = function(post) {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/delete-post-modal/delete-post-modal.html',
                controller : 'DeletePostModalController',
                resolve : {
                    post : function() {
                        return post;
                    }
                }
            });

            modalInstance.result.then(function(results) {
                if (!results.error) {
                    // no error occurred and post is successfully deleted
                    // remove the post
                    var index = vm.posts.indexOf(post);
                    vm.posts.splice(index, 1);

                    vm.activePost = vm.posts[0];
                }
            });
        };

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

        vm.goBack = function() {
            vm.activePane = 'lists';
        };

        /**
         * Shows the selected post and preview its content
         */
        vm.previewThisPost = function(post) {
            vm.activePost = post;

            // set the preview window to active
            vm.activePane = 'preview';
        };

        // fire away!
        vm.initialize();
    }
})();

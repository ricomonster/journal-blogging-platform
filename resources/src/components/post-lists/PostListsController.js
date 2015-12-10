(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .controller('PostListsController', ['$modal', 'PostListService', PostListsController]);

    function PostListsController($modal, PostListService) {
        var vm = this;

        vm.loading = true;
        vm.posts = [];
        vm.activePost = null;

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

                    // get the current first post and make it active
                    vm.activePost = vm.posts[0];

                    // set the active page
                    vm.activePane = 'lists';
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

                    vm.loading = false;
                })
                .error(function() {
                    vm.loading = false;
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

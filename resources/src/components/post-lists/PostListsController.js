(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .controller('PostListsController', ['$uibModal', 'PostListsService', 'ToastrService', 'CONFIG', PostListsController]);

    function PostListsController($uibModal, PostListsService, ToastrService, CONFIG) {
        var vm = this;

        // controller variables
        vm.active       = [];
        vm.loading      = true;
        vm.posts        = {};
        vm.processing   = true;
        vm.query        = '';

        /**
         * Opens the modal to prepare the post to be deleted.
         * @param post
         */
        vm.openDeletePostModal = function() {
            var post = vm.active;

            if (!post) {
                return;
            }

            var modalInstance = $uibModal.open({
                animation: true,
                size: 'delete-post',
                controllerAs : 'dpmc',
                controller : 'DeletePostModalController',
                templateUrl: CONFIG.TEMPLATE_PATH + 'delete-post-modal/delete-post-modal.html',
                resolve : {
                    post : function() {
                        return angular.copy(post);
                    }
                }
            });

            modalInstance.result.then(function(response){
                if (!response.error) {
                    // get the index of the current post
                    var index = vm.posts.indexOf(post);

                    // we want to set the post before the deleted post to be
                    // active so we're going to subtract 1 from the index. but
                    // if the index is 0 or the first post, let's get the next
                    // post by doing +1
                    var nextActiveIndex = (index == 0) ? index + 1 : index - 1;

                    // set the active post
                    vm.active = vm.posts[nextActiveIndex];

                    // remove the deleted post from the scope
                    vm.posts.splice(index, 1);
                }
            }).finally(function() {

            })
        };

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

                        vm.loading = false;
                    }
                },
                function() {
                    // flag that there's something wrong with fetching the
                    // posts from the API
                    ToastrService
                        .toast('Something went wrong while fetching posts from the API. Please try again later.', 'error');

                    vm.loading = false;
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

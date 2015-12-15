(function() {
    'use strict';

    angular.module('journal.component.editor')
        .controller('EditorController', ['$modal', '$state', '$stateParams', 'AuthService', 'EditorService', 'ToastrService', EditorController]);

    function EditorController($modal, $state, $stateParams, AuthService, EditorService, ToastrService) {
        var vm = this;

        vm.sidebar = false;
        vm.post = {
            author_id : AuthService.user().id,
            status : 2,
            tags : []
        };

        // editor config
        vm.editor = {
            // button status
            activeStatus : [],
            // base url of the application
            baseUrl : window.location.origin,
            // codemirror settings
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // counter
            counter : 0,
            // list of status
            status : [
                { class : 'danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'info', group : 2, status : 1, text : 'Update Post' }],
            tags : []
        };
        vm.processing = false;

        /**
         * Initialize some functions to run
         */
        vm.initialize = function() {
            // set the default status of the post
            vm.editor.activeStatus = vm.editor.status[1];

            // check first if there's a parameter set
            if ($stateParams.postId) {
                // get the post
                EditorService.getPost($stateParams.postId)
                    .success(function(response) {
                        if (response.post) {
                            vm.post = response.post;

                            // check if the post is published
                            if (response.post.status == 1) {
                                // published post
                                vm.editor.activeStatus = vm.editor.status[3];
                            }

                            // we're gonna assume that there's a published_at field returned
                            vm.post.published_at = vm.convertTimestampToDate(response.post.published_at);
                        }
                    })
                    .error(function(response) {
                        // something went wrong, redirect to 400, 404 or 500 page
                    });
            }

            // check if there's no post ID
            if (!$stateParams.postId) {
                // set the datetime today
                vm.post.published_at = vm.convertTimestampToDate();
            }
        };

        /**
         * @param timestamp
         * @returns {Date}
         */
        vm.convertTimestampToDate = function(timestamp) {
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
         * Shows the modal to confirm to delete the post
         */
        vm.deletePost = function() {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/delete-post-modal/delete-post-modal.html',
                controller : 'DeletePostModalController',
                resolve : {
                    post : function() {
                        return vm.post;
                    }
                }
            });

            modalInstance.result.then(function(results) {
                if (!results.error) {
                    // no error occurred and post is successfully deleted
                    // redirect to list of posts
                    $state.go('post.lists');
                }
            });
        };

        /**
         * Trigger submit event and sends request to API
         */
        vm.savePost = function() {
            var post = vm.post;

            // flag that it is loading
            vm.processing = true;

            // do an API request to save the post
            EditorService.save(post)
                .success(function(response) {
                    var responsePost = response.post;

                    // unflag processing state
                    vm.processing = false;

                    // check if post is newly created
                    if (!vm.post.id) {
                        ToastrService
                            .toast('You have successfully created the post "'+responsePost.title+'".', 'success');
                        // redirect
                        $state.go('postEditor', { postId : responsePost.id });
                        return;
                    }

                    // check if the post is published
                    if (responsePost.status == 1) {
                        // published post
                        vm.editor.activeStatus = vm.editor.status[3];
                    }

                    // check if the post is saved as draft or unpublished
                    if (responsePost.status == 2) {
                        // default to save as draft
                        vm.editor.activeStatus = vm.editor.status[1];
                    }

                    // show message
                    ToastrService
                        .toast('You have successfully updated "'+responsePost.title+'".', 'success');

                    // convert the published_at datetime
                    responsePost.published_at = vm.convertTimestampToDate(responsePost.published_at);

                    // update the scope
                    vm.post = responsePost;
                })
                .error(function(response) {
                    // unflag processing state
                    vm.processing = false;
                });
        };

        vm.setButtonClass = function() {
            // initialize the button class
            var buttonClass = 'btn-default';

            switch (vm.editor.activeStatus.class) {
                case 'danger' : buttonClass = 'btn-danger'; break;
                case 'primary' : buttonClass = 'btn-primary'; break;
                case 'info' : buttonClass = 'btn-info'; break;
            }

            // check if it's processing
            buttonClass += (vm.processing) ? ' processing' : '';

            return buttonClass;
        };

        /**
         * Sets the status of the post
         * @param state
         */
        vm.setPostStatus = function(state) {
            // set first the active status to the selected one
            vm.editor.activeStatus = state;

            // set the status of the post
            vm.post.status = state.status;
        };

        vm.showMarkdownHelper = function() {
            $modal.open({
                animation : true,
                templateUrl : '/assets/templates/markdown-helper-modal/markdown-helper-modal.html',
                controllerAs : 'mhm',
                controller : 'MarkdownHelperModalController',
                size: 'markdown'
            });
        };

        vm.showPane = function(pane) {
            vm.editor.activePane = pane;
        };

        /**
         * Toggle to open or close the editor sidebar
         */
        vm.toggleSidebar = function() {
            vm.sidebar = !vm.sidebar;
        };

        /**
         * Append the word/words depending the number of words inputted in the editor
         */
        vm.wordCounter = function() {
            return vm.editor.counter > 0 ? vm.editor.counter + ' words' : '0 words';
        };

        vm.initialize();
    }
})();

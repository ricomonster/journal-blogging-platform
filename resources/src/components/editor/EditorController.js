(function() {
    'use strict';

    angular.module('journal.component.editor')
        .controller('EditorController', ['$modal', '$state', '$stateParams', 'AuthService', 'EditorService', 'ToastrService', EditorController]);

    function EditorController($modal, $state, $stateParams, AuthService, EditorService, ToastrService) {
        var vm = this,
            // get current date in yyyy/mm/dd hh:ss format
            date = new Date(),
            currentDate = new Date(
                date.getFullYear(),
                date.getMonth(),
                date.getDate(),
                date.getHours(),
                date.getMinutes());


        vm.sidebar = false;
        vm.post = {
            author_id : AuthService.user().id,
            status : 2,
            tags : []
        };

        // editor config
        vm.editor = {
            activePane : 'markdown',
            // button status
            activeStatus : [],
            // base url of the application
            baseUrl : window.location.origin,
            // codemirror settings
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // counter
            counter : 0,
            // sets the date
            dateNow : currentDate,
            // list of status
            status : [
                { class : 'danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'info', group : 2, status : 1, text : 'Update Post' }],
            tags : []
        };

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
                            vm.editor.dateNow = vm.convertDate(response.post.published_at);
                        }
                    })
                    .error(function(response) {
                        // something went wrong, redirect to 400, 404 or 500 page
                    });
            }
        };

        /**
         * @param timestamp
         * @returns {Date}
         */
        vm.convertDate = function(timestamp) {
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

            // return the current date
            return currentDate;
        };

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

            // do an API request to save the post
            EditorService.save(post)
                .success(function(response) {
                    var post = response.post;

                    // check if post is newly created
                    if (!vm.post.id) {
                        ToastrService
                            .toast('You have successfully created the post "'+post.title+'".', 'success');
                        // redirect
                        $state.go('postEditor', { postId : post.id });
                        return;
                    }

                    // check if the post is published
                    if (post.status == 1) {
                        // published post
                        vm.editor.activeStatus = vm.editor.status[3];
                    }

                    // check if the post is saved as draft or unpublished
                    if (post.status == 2) {
                        // default to save as draft
                        vm.editor.activeStatus = vm.editor.status[1];
                    }

                    // show message
                    ToastrService
                        .toast('You have successfully updated "'+post.title+'".', 'success');

                    // update the scope
                    vm.post = post;
                })
                .error(function(response) {

                });
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

(function() {
    'use strict';

    angular.module('journal.component.editor')
        .controller('EditorController', ['$state', '$stateParams', 'AuthService', 'EditorService', 'GrowlService', EditorController]);

    function EditorController($state, $stateParams, AuthService, EditorService, GrowlService) {
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

        /**
         * Append the word/words depending the number of words inputted in the editor
         */
        vm.wordCounter = function() {
            return vm.editor.counter > 0 ? vm.editor.counter + ' words' : '0 words';
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
                        }
                    })
                    .error(function(response) {
                        // something went wrong, redirect to 400, 404 or 500 page
                    });
            }
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
                        GrowlService
                            .growl('You have successfully created the post "'+post.title+'".', 'success');
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
                    GrowlService
                        .growl('You have successfully updated "'+post.title+'".', 'success');

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

        /**
         * Toggle to open or close the editor sidebar
         */
        vm.toggleSidebar = function() {
            vm.sidebar = !vm.sidebar;
        };

        vm.initialize();
    }
})();

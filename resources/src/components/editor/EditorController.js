(function() {
    'use strict';

    angular.module('journal.components.editor')
        .controller('EditorController', ['$stateParams', 'EditorService', 'ToastrService', EditorController]);

    function EditorController($stateParams, EditorService, ToastrService) {
        var vm = this;

        vm.options = {
            // codemirror
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // word counter
            counter : 0,
            // sidebar toggle
            toggle : false
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

            // flag that is being processed
            vm.processing = true;

            // send data to the API
            EditorService.savePost(post)
                .then(function(response) {
                    if (response.post) {
                        var responsePost = response.post;

                        // check if this is newly created post by checking if
                        // there's a post id in the post object
                        if (!vm.post.id) {
                            ToastrService
                                .toast('You have successfully created the post"'+
                                responsePost.title+'"', 'success');
                            // TODO: update the URL
                        } else {
                            ToastrService
                                .toast('You have successfully updated "'+
                                responsePost.title+'".', 'success');
                        }

                        // update the published date to human readable form
                        responsePost.published_at = vm.convertTimestamp(responsePost.published_at);
                        // update the scope
                        vm.post = responsePost;
                    }

                    vm.processing = false;
                }, function(error) {
                    vm.processing = false;
                });
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
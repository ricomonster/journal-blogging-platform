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
            counter : 0
        };

        vm.post = {
            status : 2,
            tags : []
        };

        /**
         * This will run once the page loads. It will check if there's a
         * parameter set and fetches the post based on the given parameter.
         */
        vm.initialize = function() {
            if ($stateParams.postId) {
                // get the post details
                EditorService.getPost($stateParams.postId)
                    .then(function(response) {
                        if (response.post) {
                            vm.post = response.post;
                        }
                    },
                    function() {

                    });
            }
        };

        vm.initialize();
    }
})();
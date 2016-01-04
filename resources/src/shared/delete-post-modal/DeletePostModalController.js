(function() {
    'use strict';

    angular.module('journal.shared.deletePostModal')
        .controller('DeletePostModalController', [
            '$uibModalInstance', 'DeletePostModalService', 'ToastrService', 'post', DeletePostModalController]);

    function DeletePostModalController($uibModalInstance, DeletePostModalService, ToastrService, post) {
        var vm = this;

        // controller variables
        vm.post = post;
        vm.processing = false;

        /**
         * Closes the modal
         */
        vm.closeModal = function() {
            $uibModalInstance.dismiss('cancel');
        };

        vm.deletePost = function() {
            var post = vm.post;

            vm.processing = true;

            // send a request
            DeletePostModalService.deletePost(post.id)
                .then(function(response) {
                    if (!response.error) {
                        // success message
                        ToastrService
                            .toast('You have successfully deleted the post "'+post.title+'"', 'success');
                        // close and return the response
                        $uibModalInstance.close(response);
                    }
                }, function(error) {
                    ToastrService
                        .toast('Something went wrong while deleting the post. Please try again', 'error');

                    // close the modal
                    $uibModalInstance.dismiss('cancel');
                });
        };
    }
})();

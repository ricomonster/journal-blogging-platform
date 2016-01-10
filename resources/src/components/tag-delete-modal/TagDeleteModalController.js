(function() {
    'use strict';

    angular.module('journal.components.tagDeleteModal')
        .controller('TagDeleteModalController', ['$uibModalInstance', 'TagDeleteModalService', 'ToastrService', 'tag', TagDeleteModalController]);

    function TagDeleteModalController($uibModalInstance, TagDeleteModalService, ToastrService, tag) {
        var vm = this;

        // controller variables
        vm.processing = false;
        vm.tag = tag;

        /**
         * Closes the modal
         */
        vm.closeModal = function() {
            $uibModalInstance.dismiss('cancel');
        };

        vm.deleteTag = function() {
            var tag = vm.tag;

            // flag that we're processing
            vm.processing = true;

            // send request to the API
            TagDeleteModalService.deleteTag(tag.id)
                .then(function(response) {
                    if (!response.error) {
                        // success message
                        ToastrService
                            .toast('You have successfully deleted the tag "'+tag.name+'"', 'success');
                        // close and return the response
                        $uibModalInstance.close(response);
                    }
                }, function(error) {
                    ToastrService
                        .toast('Something went wrong while deleting the tag. Please try again', 'error');

                    // close the modal
                    $uibModalInstance.dismiss('cancel');
                });
        };
    }
})();

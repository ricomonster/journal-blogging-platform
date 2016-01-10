(function() {
    'use strict';

    angular.module('journal.components.tagEdit')
        .controller('TagEditController', ['$state', '$stateParams', '$uibModal', 'TagEditService', 'ToastrService', 'CONFIG', TagEditController]);

    function TagEditController($state, $stateParams, $uibModal, TagEditService, ToastrService, CONFIG) {
        var vm = this;

        // controller variables
        vm.errors = {};
        vm.processing = false;
        vm.tag = {};

        vm.initialize = function() {
            // check for a parameter
            if ($stateParams.tagId) {
                // get the tag from the API
                TagEditService.getTag($stateParams.tagId)
                    .then(function(response) {
                        if (response.tag) {
                            vm.tag = response.tag;
                        }
                    }, function(error) {
                        // TODO: Handle error
                    });
            }

            // TODO: Redirect to a 404 page if the page does not exists.
        };

        this.updateTag = function() {
            var tag = vm.tag;

            // flag that we're going to process the request
            vm.processing = true;

            // send request to the API
            TagEditService.updateTagDetails(tag)
                .then(function(response) {
                    if (response.tag) {
                        vm.errors = {};

                        // show a success message
                        ToastrService.toast('You have successfully updated the tag.', 'success');

                        // update vm.tag
                        vm.tag = response.tag;
                    }

                    vm.processing = false;
                }, function(error) {
                    vm.processing = false;

                    // tell that there's an error
                    ToastrService
                        .toast('There are some errors encountered processing your request.', 'error');

                    // get the errors
                    vm.errors = error.errors;

                    // loop the errors so we can show it via toastr
                    for (var e in error.errors) {
                        ToastrService.toast(error.errors[e][0], 'error');
                    }
                });
        };

        vm.openDeleteTagModal = function() {
            // instantiate the modal
            var modal = $uibModal.open({
                animation: true,
                controllerAs : 'tdmc',
                controller : 'TagDeleteModalController',
                templateUrl: CONFIG.TEMPLATE_PATH + 'tag-delete-modal/tag-delete-modal.html',
                resolve: {
                    tag : function() {
                        return angular.copy(vm.tag);
                    }
                },
                size : 'delete-tag'
            });

            modal.result.then(function(response) {
                if (!response.error) {
                    // redirect to tag lists
                    $state.go('tag.lists');
                }
            });
        };

        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.deletePostModal')
        .controller('DeletePostModalController', ['$scope', '$modalInstance', 'DeletePostModalService', 'GrowlService', 'post', DeletePostModalController]);

    function DeletePostModalController($scope, $modalInstance, DeletePostModalService, GrowlService, post) {
        $scope.post = post;
        $scope.processing = false;

        $scope.cancelPost = function() {
            // just close the modal
            $modalInstance.dismiss('cancel');
        };

        $scope.deletePost = function() {
            // flag that we're processing
            $scope.processing = true;

            // do an API request to delete the post
            DeletePostModalService.deletePost($scope.post.id)
                .success(function(response) {
                    if (!response.error) {
                        // growl
                        GrowlService.growl(
                            'You have successfully deleted the post "'+$scope.post.title+'"',
                            'success');

                        // return response
                        $modalInstance.close({
                            error : false
                        });
                    }
                })
                .error(function(response) {
                    $scope.processing = false;

                    // tell there's a fucking error
                    GrowlService.growl('Something went wrong. Please try again later.', 'error');
                    // close the fucking modal
                    $modalInstance.dismiss('cancel');
                });
        };
    }
})();

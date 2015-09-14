(function() {
    'use strict';

    angular.module('journal.component.deletePostModal')
        .controller('DeletePostModalController', ['$scope', '$modalInstance', 'DeletePostModalService', 'ToastrService', 'post', DeletePostModalController]);

    function DeletePostModalController($scope, $modalInstance, DeletePostModalService, ToastrService, post) {
        $scope.post = post;

        $scope.cancelPost = function() {
            // just close the modal
            $modalInstance.dismiss('cancel');
        };

        $scope.deletePost = function() {
            // do an API request to delete the post
            DeletePostModalService.deletePost($scope.post.id)
                .success(function(response) {
                    if (!response.error) {
                        // growl
                        ToastrService.toast(
                            'You have successfully deleted the post "'+$scope.post.title+'"',
                            'success');

                        // return response
                        $modalInstance.close({
                            error : false
                        });
                    }
                })
                .error(function(response) {
                    // tell there's a fucking error
                    ToastrService.toast('Something went wrong. Please try again later.', 'error');
                    // close the fucking modal
                    $modalInstance.dismiss('cancel');
                });
        };
    }
})();

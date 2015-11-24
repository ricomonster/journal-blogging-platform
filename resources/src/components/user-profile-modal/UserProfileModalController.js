(function() {
    'use strict';

    angular.module('journal.component.userProfileModal')
        .controller('UserProfileModalController', ['$scope', '$modalInstance', 'GrowlService', 'UserProfileService', 'FileUploaderService', 'user', 'type', UserProfileModalController]);

    function UserProfileModalController($scope, $modalInstance, GrowlService, UserProfileService, FileUploaderService, user, type) {
        $scope.activeOption = 'file';
        $scope.imageUrl = null;
        $scope.image = {
            link : null,
            file : null
        };

        $scope.processing = false;
        $scope.user = user;
        $scope.upload = {
            active : false,
            percentage : 0
        };

        /**
         * Listen for changes on the scope
         */
        $scope.$watch('image.file', function() {
            if ($scope.image.file != null) {
                // flag that we're processing a request
                $scope.processing = true;

                FileUploaderService.upload($scope.image.file)
                    .progress(function(event) {
                        $scope.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
                            $scope.processing = false;

                            // show image
                            $scope.imageUrl = response.url;
                            // hide the progress bar
                            $scope.upload = {
                                active : false,
                                percentage : 0
                            };
                        }
                    })
                    .error(function() {
                        $scope.processing = false;

                        // handle the error
                        GrowlService
                            .growl('Something went wrong with the upload. Please try again later.', 'error');

                        // hide progress bar
                        $scope.upload = {
                            active : false,
                            percentage : 0
                        };
                    });
            }
        });

        /**
         * Closes the modal, duh
         */
        $scope.closeModal = function() {
            $modalInstance.dismiss('cancel');
        };

        $scope.initialize = function() {
            if (type == 'cover_url' && $scope.user.cover_url) {
                $scope.imageUrl = $scope.user.cover_url;
            }

            if (type == 'avatar_url' && $scope.user.avatar_url) {
                $scope.imageUrl = $scope.user.avatar_url;
            }
        };

        /**
         * Removes the currently shown image and it also set the setting field to null
         * so when the user saves the setting, the image will also be removed from the
         * database
         */
        $scope.removeImage = function() {
            // empty the imageUrl scope
            $scope.imageUrl = null;
            // empty the setting scope
            $scope.settings[type] = null;
        };

        /**
         * Saves the settings and updates it in the database
         */
        $scope.save = function() {
            // flag that we're processing a request
            $scope.processing = true;

            $scope.user[type] = ($scope.imageUrl) ? $scope.imageUrl : $scope.image.link;

            // do an API request to update details of the user
            UserProfileService.updateUserDetails($scope.user)
                .success(function(response) {
                    if (response.user) {
                        // growl it!
                        GrowlService.growl('You have successfully updated your profile.', 'success');

                        // close the modal and returns the response from the server
                        $modalInstance.close(response.user);
                    }
                })
                .error(function(response) {
                    // handle the error
                    $scope.processing = false;
                });
        };

        /**
         * Switches the option on how the user will upload an image.
         * By default, it is set to upload a file.
         */
        $scope.switchOption = function() {
            switch($scope.activeOption) {
                case 'link' :
                    $scope.activeOption = 'file';
                    break;
                case 'file' :
                    $scope.activeOption = 'link';
                    break;
                default :
                    $scope.activeOption = 'file';
                    break;
            }
        };

        $scope.initialize();
    }
})();

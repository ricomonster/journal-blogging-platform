(function() {
    'use strict';

    angular.module('journal.component.settingsModal')
        .controller('SettingsModalController', ['$scope', '$modalInstance', 'GrowlService', 'SettingsService', 'FileUploaderService', 'settings', 'type', SettingsModalController]);

    /**
     * Fuck this shit. I hate using scope :( :( :(
     *
     * @param $scope
     * @param $modalInstance
     * @param FileUploaderService
     * @constructor
     */
    function SettingsModalController($scope, $modalInstance, GrowlService, SettingsService, FileUploaderService, settings, type) {
        $scope.activeOption = 'file';
        $scope.imageUrl = null;
        $scope.image = {
            link : null,
            file : null
        };

        $scope.settings = settings;

        $scope.upload = {
            active : false,
            percentage : 0
        };

        /**
         * Listen for changes on the scope
         */
        $scope.$watch('image.file', function() {
            if ($scope.image.file != null) {
                //$scope.uploadFile($scope.image.file);
                FileUploaderService.upload($scope.image.file)
                    .progress(function(event) {
                        $scope.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
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
            if (type == 'cover_url' && $scope.settings.cover_url) {
                $scope.imageUrl = $scope.settings.cover_url;
            }

            if (type == 'logo_url' && $scope.settings.logo_url) {
                $scope.imageUrl = $scope.settings.logo_url;
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
            $scope.settings[type] = ($scope.imageUrl) ? $scope.imageUrl : $scope.image.link;

            // save the settings
            SettingsService.saveSettings($scope.settings)
                .success(function(response) {
                    if (response.settings) {
                        // show success message
                        GrowlService.growl('You have successfully updated the settings.', 'success');
                        // close the modal
                        $modalInstance.close(response.settings);
                    }
                })
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

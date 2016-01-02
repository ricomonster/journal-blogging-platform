(function() {
    'use strict';

    angular.module('journal.components.settingsGeneralModal')
        .controller('SettingsGeneralModalController', [
            '$scope', '$timeout', '$uibModalInstance', 'FileUploaderService', 'ToastrService', 'SettingsGeneralModalService', 'settings', 'type', SettingsGeneralModalController]);

    function SettingsGeneralModalController($scope, $timeout, $uibModalInstance, FileUploaderService, ToastrService, SettingsGeneralModalService, settings, type) {
        var vm = this;

        // controller variables
        vm.settings = settings;
        vm.image = {
            link : null,
            file : null,
            option : 'file',
            url : ''
        };

        vm.processing = false;

        vm.type = type;
        // upload variables
        vm.upload = {
            active : false,
            percentage : 0
        };

        vm.closeModal = function() {
            $uibModalInstance.dismiss('cancel');
        };

        /**
         * Fetches the value of the input and be used as image url.
         */
        vm.getImageLink = function() {
            // delay it for a second
            $timeout(function() {
                vm.image.url = vm.image.link;

                // empty the image link
                vm.image.link = null;

                // update
                vm.updatePhotoDetails(vm.image.url);
            }, 1000);
        };

        vm.initialize = function() {
            // check the type of the photo to be updated then update the
            // image url scope
            switch (vm.type) {
                case 'cover' :
                    vm.image.url = vm.settings.cover_url || '';
                    break;
                case 'logo' :
                    vm.image.url = vm.settings.logo_url || '';
                    break;
                default:
                    break;
            }
        };

        /**
         * Removes the image.
         */
        vm.removeImage = function() {
            // empty the image url
            vm.image.url = '';

            // update
            vm.updatePhotoDetails(vm.image.url);
        };

        /**
         * Updates the details of the user.
         */
        vm.save = function() {
            var data = vm.settings;

            // flag that we're processing
            vm.processing = true;

            // update settings
            SettingsGeneralModalService.saveSettings(data)
                .then(function(response) {
                    if (response.settings) {
                        // show message
                        ToastrService
                            .toast('You have successfully updated your '+vm.type+' photo.');

                        // close modal and send data back to the SettingsGeneralController
                        $uibModalInstance.close(response.settings);
                    }

                    vm.processing = false;
                }, function() {
                    ToastrService
                        .toast('Something went wrong while uploading the photo to the server.', 'error');
                    vm.processing = false;
                });
        };

        /**
         * Switches the option to put a featured image.
         */
        vm.switchOption = function() {
            if (vm.image.option == 'file') {
                vm.image.option = 'link';
                return;
            }

            if (vm.image.option == 'link') {
                vm.image.option = 'file';
                return;
            }
        };

        /**
         * Updates the value depending on the image being updated.
         * @param url
         */
        vm.updatePhotoDetails = function(url) {
            // check first the type of image being updated
            switch (vm.type) {
                case 'cover' :
                    vm.settings.cover_url = url;
                    break;
                case 'logo' :
                    vm.settings.logo_url = url;
                    break;
                default:
                    break;
            }
        };

        /**
         * Listen for the changes to the image.file scope and trigger the file
         * upload to the API.
         */
        $scope.$watch(function() {
            return vm.image.file;
        }, function(file) {
            if (file) {
                vm.processing = true;

                // upload
                FileUploaderService.upload(file)
                    .progress(function(event) {
                        vm.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
                            // show image
                            vm.image.url = response.url;

                            // update
                            vm.updatePhotoDetails(response.url);

                            // hide the progress bar
                            vm.upload = {
                                active : false,
                                percentage : 0
                            };
                        }

                        vm.processing = false;
                    })
                    .error(function() {
                        // handle the error
                        ToastrService
                            .toast('Something went wrong with the upload. Please try again later.', 'error');

                        // hide progress bar
                        vm.upload = {
                            active : false,
                            percentage : 0
                        };

                        vm.processing = false;
                    });
            }
        });

        vm.initialize();
    }
})();

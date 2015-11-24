(function() {
    'use strict';

    angular.module('journal.component.settings')
        .controller('SettingsController', ['$modal', 'GrowlService', 'SettingsService', SettingsController]);

    function SettingsController($modal, GrowlService, SettingsService) {
        var vm = this;
        vm.processing = false;
        vm.settings = [];
        vm.themes = [];

        /**
         * Fetch the settings saved
         */
        vm.initialize = function() {
            // get settings
            SettingsService.getSettings('title,description,post_per_page,cover_url,logo_url,theme')
                .success(function(response) {
                    if (response.settings) {
                        vm.settings = response.settings;
                    }
                });

            // get the themes
            SettingsService.themes().success(function(response) {
                if (response.themes) {
                    vm.themes = response.themes;
                }
            });
        };

        vm.saveSettings = function() {
            // flag that we're processing a request
            vm.processing = true;

            // save the settings
            SettingsService.saveSettings(vm.settings)
                .success(function(response) {
                    if (response.settings) {
                        vm.processing = false;

                        // show success message
                        GrowlService.growl('You have successfully updated the settings.', 'success');
                    }
                })
        };

        vm.selectedTheme = function(value) {
            if (vm.settings.theme) {
                return vm.settings.theme == value;
            }

            return false;
        };

        vm.showImageUploader = function(type) {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/uploader-modal/uploader-modal.html',
                controller : 'SettingsModalController',
                resolve : {
                    settings : function() {
                        return vm.settings;
                    },
                    type : function() {
                        return type
                    }
                }
            });

            modalInstance.result.then(function(results) {
                vm.settings = results;
            });
        };

        // fire
        vm.initialize();
    }
})();

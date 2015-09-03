(function() {
    'use strict';

    angular.module('journal.component.settings')
        .controller('SettingsController', ['$modal', 'ToastrService', 'SettingsService', SettingsController]);

    function SettingsController($modal, ToastrService, SettingsService) {
        var vm = this;
        vm.settings = [];

        /**
         * Fetch the settings saved
         */
        vm.initialize = function() {
            // get settings
            SettingsService.getSettings('title,description,post_per_page,cover_url,logo_url')
                .success(function(response) {
                    if (response.settings) {
                        vm.settings = response.settings;
                    }
                });
        };

        vm.saveSettings = function() {
            // save the settings
            SettingsService.saveSettings(vm.settings)
                .success(function(response) {
                    if (response.settings) {
                        // show success message
                        ToastrService.toast('You have successfully updated the settings.', 'success');
                    }
                })
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

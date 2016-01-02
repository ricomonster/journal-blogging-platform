(function() {
    'use strict';

    angular.module('journal.components.settingsGeneral')
        .controller('SettingsGeneralController', ['$uibModal', 'SettingsGeneralService', 'ToastrService', 'CONFIG', SettingsGeneralController]);

    function SettingsGeneralController($uibModal, SettingsGeneralService, ToastrService, CONFIG) {
        var vm = this;

        // controller variables
        vm.loading = true;
        vm.processing = false;
        vm.settings = {};
        vm.themes = {};

        /**
         * This will run once the page loads and fetches the needed settings
         * from the API and be shown in the page.
         */
        vm.initialize = function() {
            var fields = 'title,description,post_per_page,logo_url,cover_url,theme';

            // get the settings from the API
            SettingsGeneralService.getSettings(fields)
                .then(function(response) {
                    if (response.settings) {
                        vm.settings = response.settings;

                        // convert the post_per_page to integer
                        if (vm.settings.post_per_page) {
                            vm.settings.post_per_page = parseInt(vm.settings.post_per_page);
                        }
                    }


                }, function(error) {
                    vm.loading = false;
                });

            // get the installed themes
            SettingsGeneralService.themes()
                .then(function(response) {
                    if (response.themes) {
                        vm.themes = response.themes;
                    }

                    vm.loading = false;
                });
        };

        /**
         * Opens the uploader modal.
         * @param type
         */
        vm.openUploaderModal = function(type) {
            // instantiate the modal
            var modal = $uibModal.open({
                animation: true,
                controllerAs : 'um',
                controller : 'SettingsGeneralModalController',
                templateUrl: CONFIG.TEMPLATE_PATH + 'uploader-modal/uploader-modal.html',
                resolve: {
                    settings : function() {
                        return angular.copy(vm.settings);
                    },
                    type : function() {
                        return type;
                    }
                }
            });

            // once the modal is closed and there's a data returned, update
            // the settings scope.
            modal.result.then(function(settings) {
                vm.settings = settings;

                // convert the post_per_page to integer
                if (vm.settings.post_per_page) {
                    vm.settings.post_per_page = parseInt(vm.settings.post_per_page);
                }
            });
        };

        /**
         * Saves the settings in the API.
         */
        vm.saveSettings = function() {
            var settings = vm.settings;

            // flag that we're now processing
            vm.processing = true;

            // send to the API
            SettingsGeneralService.saveSettings(settings)
                .then(function(response) {
                    if (response.settings) {
                        // update the settings scope
                        vm.settings = settings;

                        // convert the post_per_page to integer
                        if (vm.settings.post_per_page) {
                            vm.settings.post_per_page = parseInt(vm.settings.post_per_page);
                        }

                        ToastrService.toast('You have successfully updated your blog settings.', 'success');
                    }

                    vm.processing = false;
                }, function() {
                    vm.processing = false;

                    ToastrService
                        .toast('Something went wrong while processing your request. Please try again later.', 'error');
                })
        };

        vm.initialize();
    }
})();

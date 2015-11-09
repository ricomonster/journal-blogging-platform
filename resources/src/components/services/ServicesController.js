(function() {
    'use strict';

    angular.module('journal.component.services')
        .controller('ServicesController', ['ServicesService', 'ToastrService', ServicesController]);

    function ServicesController(ServicesService, ToastrService) {
        var vm = this;
        vm.processing = false;
        vm.services = [];

        /**
         * This will run once the page loads
         */
        vm.initialize = function() {
            // get disqus and google analytics values via API
            ServicesService.getServices('google_analytics')
                .success(function(response) {
                    if (response.settings) {
                        // assign it to our scope
                        vm.services = response.settings;
                    }
                }).error(function() {
                    // something went wrong
                    // TODO: Create a handler to handle server errors from the API.
                });
        };

        /**
         * Handles the submit event that will send a request to the API to save
         * the given data by the user
         * @return {[type]} [description]
         */
        vm.saveServices = function() {
            // prepare the data
            var services = vm.services;

            // flag that a request is being processed
            vm.processing = true;

            // trigger API call
            ServicesService.saveServices(services)
                .success(function(response) {
                    // check for the response
                    if (response.settings) {
                        vm.processing = false;

                        // toast it
                        ToastrService.toast('You have successfully updated your services.', 'success');
                    }
                });
        };

        // fire away
        vm.initialize();
    }
})();

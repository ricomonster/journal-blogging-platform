(function() {
    'use strict';

    angular.module('journal.components.tagLists')
        .controller('TagListsController', ['TagListsService', 'ToastrService', TagListsController]);

    function TagListsController(TagListsService, ToastrService) {
        var vm = this;

        // controller variables
        vm.add          = {};
        vm.errors       = {};
        vm.loading      = true;
        vm.processing   = false;
        vm.tags         = {};

        vm.addTag = function() {
            var tag = vm.add;

            // flag that we're going to process this
            vm.processing = true;

            TagListsService.createTag(tag)
                .then(function(response) {
                    if (response.tag) {
                        ToastrService.toast('You have successfully created a new tag!', 'success');

                        // empty the errors
                        vm.errors = {};

                        // empty the form
                        vm.add = {};

                        // push the new tag to the list
                        vm.tags.push(response.tag);
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

        /**
         * Fetch tags from the API once the page loads.
         */
        vm.initialize = function() {
            // fetch the tags from the API
            TagListsService.getTags().then(function(response) {
                if (response.tags) {
                    vm.tags = response.tags;

                    // flag that we're finish loading data
                    vm.loading = false;
                }
            }, function() {

            });
        };

        vm.initialize();
    }
})();

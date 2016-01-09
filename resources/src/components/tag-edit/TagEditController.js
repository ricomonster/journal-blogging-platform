(function() {
    'use strict';

    angular.module('journal.components.tagEdit')
        .controller('TagEditController', ['$state', '$stateParams', 'TagEditService', 'ToastrService', TagEditController]);

    function TagEditController($state, $stateParams, TagEditService, ToastrService) {
        var vm = this;

        // controller variables
        vm.processing = false;
        vm.tag = {};

        vm.initialize = function() {

        };
    }
})();

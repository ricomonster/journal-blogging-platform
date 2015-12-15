(function() {
    'use strict';

    angular.module('journal.component.markdownHelperModal')
        .controller('MarkdownHelperModalController', ['$modalInstance', MarkdownHelperModalController]);

    function MarkdownHelperModalController($modalInstance) {
        var vm = this;

        /**
         * Closes the modal, duh
         */
        vm.closeModal = function() {
            $modalInstance.dismiss('cancel');
        };
    }
})();
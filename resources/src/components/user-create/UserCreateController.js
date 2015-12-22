(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .controller('UserCreateController', ['UserCreateService', UserCreateController]);

    function UserCreateController() {
        var vm = this;

        // controller variables
        vm.user = {};
    }
})();
(function() {
    'use strict';

    angular.module('journal.components.userLists')
        .controller('UserListsController', ['UserListsService', 'CONFIG', UserListsController]);

    function UserListsController(UserListsService, CONFIG) {
        var vm = this;

        // controller variables
        vm.processing = false;
        vm.users = [];

        vm.initialize = function() {
            UserListsService.getUsers()
                .then(function(response) {
                    if (response.users) {
                        vm.users = response.users;
                    }

                    vm.processing = false;
                }, function(error) {

                });
        };

        vm.setUserAvatar = function(user) {
            return (user.avatar_url) ? user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;
        };

        vm.initialize();
    }
})();
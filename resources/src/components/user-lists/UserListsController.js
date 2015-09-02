(function() {
    'use strict';

    angular.module('journal.component.userLists')
        .controller('UserListsController', ['UserListsService', 'CONFIG', UserListsController]);

    function UserListsController(UserListService, CONFIG) {
        var vm = this;

        // user list scope
        vm.users = [];

        /**
         * This will run once page loads
         */
        vm.initialize = function() {
            // get the users
            UserListService.getAllUsers()
                .success(function(response) {
                    if (response.users) {
                        vm.users = response.users;
                    }
                })
                .error(function() {
                    // determine what is the error
                });
        };

        vm.setUserAvatarImage = function(user) {
            return (user.avatar_url) ? user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;
        };

        // fire away
        vm.initialize();
    }
})();

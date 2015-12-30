(function() {
    'use strict';

    angular.module('journal.components.userProfile')
        .controller('UserProfileController', [
            '$stateParams', 'AuthService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($stateParams, AuthService, UserProfileService, CONFIG) {
        var vm = this;

        // controller variables
        vm.current = false;
        vm.loggedInUser = AuthService.user();
        vm.user = {};

        /**
         * Will run once the page loads. Checks if the page has the user id
         * parameter and will send a request to the API to fetch the details
         * of the user based on the given user id.
         */
        vm.initialize = function() {
            // check if there's a userId parameter
            if ($stateParams.userId) {
                // get user details
                UserProfileService.getUser($stateParams.userId)
                    .then(function(response) {
                        if (response.user) {
                            var user = response.user;

                            // check if the user has a cover photo
                            user.cover_photo = (user.cover_photo) ?
                                user.cover_photo : CONFIG.DEFAULT_COVER_URL;

                            // check if the user has a avatar photo
                            user.avatar_url = (user.avatar_url) ?
                                user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;

                            // assign to the variable scope
                            vm.user = user;

                            // determine if the current user is the one who is
                            // being viewed in the profile
                            vm.current = (vm.loggedInUser.id == user.id);
                        }
                    }, function(error) {
                        // redirect to 404 page
                    });
            }
        };

        vm.initialize();
    }
})();

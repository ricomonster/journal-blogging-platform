(function() {
    'use strict';

    angular.module('journal.components.userProfile')
        .controller('UserProfileController', [
            '$state', '$stateParams', '$uibModal', 'AuthService', 'ToastrService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($state, $stateParams, $uibModal, AuthService, ToastrService, UserProfileService, CONFIG) {
        var vm = this;

        // controller variables
        vm.current      = false;
        vm.errors       = {};
        vm.loading      = true;
        vm.loggedInUser = AuthService.user();
        vm.processing   = false;
        vm.user         = {};

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

                            // assign to the variable scope
                            vm.user = user;

                            // determine if the current user is the one who is
                            // being viewed in the profile
                            vm.current = (vm.loggedInUser.id == user.id);

                            vm.loading = false;
                        }
                    }, function(error) {
                        // redirect to 404 page
                    });
            } else {
                // redirect to user lists
                $state.transitionTo('user.lists');
            }
        };

        vm.openPhotoUploader = function(type) {
            // instantiate the modal
            var modal = $uibModal.open({
                animation: true,
                controllerAs : 'um',
                controller : 'UserProfileModalController',
                templateUrl: CONFIG.TEMPLATE_PATH + 'uploader-modal/uploader-modal.html',
                resolve: {
                    user : function() {
                        return angular.copy(vm.user);
                    },
                    type : function() {
                        return type;
                    }
                }
            });

            // once the modal is closed and there's a data returned, update
            // the user scope.
            modal.result.then(function(user) {
                vm.user = user;
            });
        };

        /**
         * Sets the photo to be shown.
         * @param type
         * @returns {*}
         */
        vm.setPhoto = function(type) {
            var photoUrl;

            // check first if the page is still loading to prevent showing
            // the default avatar/cover photo
            if (vm.loading) {
                return;
            }

            switch (type) {
                case 'avatar':
                    photoUrl = (vm.user.avatar_url) ?
                        vm.user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;
                    break;
                case 'cover' :
                    photoUrl = (vm.user.cover_url) ?
                        vm.user.cover_url : CONFIG.DEFAULT_COVER_URL;
                    break;
                default:
                    break;
            }

            return photoUrl;
        };

        vm.updateProfile = function() {
            var user = vm.user;

            // flag that we're processing a request
            vm.processing = true;

            UserProfileService.updateProfileDetails(user)
                .then(function(response) {
                    if (response.user) {
                        // show success message
                        ToastrService.toast('You have successfully updated your profile.', 'success');

                        // update scope
                        vm.user = response.user;
                    }

                    vm.processing = false;
                }, function(error) {
                    if (error.errors) {
                        // show message
                        ToastrService.toast('There are some errors encountered.', 'error');

                        vm.errors = error.errors;
                    }

                    vm.processing = false;
                });
        };

        vm.initialize();
    }
})();

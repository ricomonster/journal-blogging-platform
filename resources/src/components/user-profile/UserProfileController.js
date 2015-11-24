(function() {
    'use strict';

    angular.module('journal.component.userProfile')
        .controller('UserProfileController', ['$modal', '$stateParams', 'AuthService', 'GrowlService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($modal, $stateParams, AuthService, GrowlService, UserProfileService, CONFIG) {
        var vm = this;

        vm.current = false;
        vm.user = [];
        vm.password = {};
        vm.passwordErrors = [];
        vm.processing = false;

        vm.initialize = function() {
            // check if parameter is set
            if (!$stateParams.userId) {
                // redirect to a 404 page
            }

            // do an API call to check and get the user details
            UserProfileService.getUser($stateParams.userId)
                .success(function(response) {
                    if (response.user) {
                        // check if the profile of the user is with the current user
                        vm.current = (AuthService.user().id == response.user.id);
                        vm.user = response.user;
                    }
                })
                .error(function(response, status) {
                    if (status == 404) {
                        // redirect to 404 page
                    }

                    // return to previous page and show a growl error? not sure though
                });
        };

        vm.setImage = function(type) {
            var imageSrc = null;
            switch (type) {
                case 'cover_url' :
                    imageSrc = (vm.user.cover_url) ? vm.user.cover_url : CONFIG.DEFAULT_COVER_URL;
                    imageSrc = "background-image: url('"+imageSrc+"')";
                    break;
                case 'avatar_url' :
                    imageSrc = (vm.user.avatar_url) ? vm.user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;
                    break;
            }

            return imageSrc;
        };

        vm.showImageUploader = function(type) {
            // check if the user owns the profile
            if (!vm.current) {
                return;
            }

            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/uploader-modal/uploader-modal.html',
                controller : 'UserProfileModalController',
                resolve : {
                    user : function() {
                        return vm.user;
                    },
                    type : function() {
                        return type
                    }
                }
            });

            modalInstance.result.then(function(results) {
                vm.user = results;
            });
        };

        /**
         * [function description]
         * @return {[type]} [description]
         */
        vm.updatePassword = function() {
            var passwords = vm.password;

            // flag that we're processing a request
            vm.processing = true;

            // do an API request to change the password
            UserProfileService.updatePassword(passwords)
                .success(function(response) {
                    if (response.user) {
                        vm.processing = false;

                        // clear the fields
                        ToastrService.toast('You have successfully updated your password.', 'success');
                        // empty the variable scope
                        vm.password = {};
                    }
                })
                .error(function(response) {
                    vm.processing = false;

                    // show toastr
                    ToastrService.toast('There are errors encountered.', 'error');
                    if (response.errors) {
                        if (response.message) {
                            return;
                        }

                        // show the errors
                        for (var e in response.errors) {
                            ToastrService.toast(response.errors[e][0], 'error');
                        }
                    }
                });
        };

        vm.updateProfile = function() {
            var user = vm.user;

            // flag that we're processing a request
            vm.processing = true;

            // do an API request to update details of the user
            UserProfileService.updateUserDetails(user)
                .success(function(response) {
                    if (response.user) {
                        vm.processing = false;

                        // growl it!
                        GrowlService.growl('You have successfully updated your profile.', 'success');
                    }
                })
                .error(function(response) {
                    vm.processing = false;
                });
        };

        // fire away
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userProfile')
        .controller('UserProfileController', ['$modal', '$stateParams', 'AuthService', 'GrowlService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($modal, $stateParams, AuthService, GrowlService, UserProfileService, CONFIG) {
        var vm = this;

        vm.current = false;
        vm.user = [];

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
                    break;
                case 'avatar_url' :
                    imageSrc = (vm.user.avatar_url) ? vm.user.avatar_url : CONFIG.DEFAULT_AVATAR_URL;
                    break;
            }

            return "background-image: url('"+imageSrc+"')";
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

        vm.updateProfile = function() {
            var user = vm.user;

            // do an API request to update details of the user
            UserProfileService.updateUserDetails(user)
                .success(function(response) {
                    if (response.user) {
                        // growl it!
                        GrowlService.growl('You have successfully updated your profile.', 'success');
                    }
                })
                .error(function(response) {
                    // handle the error
                });
        };

        // fire away
        vm.initialize();
    }
})();

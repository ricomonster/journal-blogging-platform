(function() {
    'use strict';

    angular.module('journal.components.editor')
        .controller('EditorController', ['$stateParams', 'EditorService', 'ToastrService', EditorController]);

    function EditorController($stateParams, EditorService, ToastrService) {
        var vm = this;

        vm.options = {
            // codemirror
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // word counter
            counter : 0,
            // sidebar toggle
            toggle : false
        };

        // initialize the post object
        vm.post = { status : 2, tags : [] };
        vm.processing = false;

        /**
         * Converts timestamp to human readable date.
         * @param timestamp
         * @returns {Date}
         */
        vm.convertTimestamp = function(timestamp) {
            // check if there's a value
            if (timestamp) {
                // let's assume this is a unix timestamp
                var publishDate = new Date(timestamp * 1000);
                return new Date(
                    publishDate.getFullYear(),
                    publishDate.getMonth(),
                    publishDate.getDate(),
                    publishDate.getHours(),
                    publishDate.getMinutes())
            }

            // get the datetime today
            var date = new Date(),
                currentDate = new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate(),
                    date.getHours(),
                    date.getMinutes());

            // return the current date
            return currentDate;
        };

        /**
         * This will run once the page loads. It will check if there's a
         * parameter set and fetches the post based on the given parameter.
         */
        vm.initialize = function() {
            // set the published date/time
            vm.post.published_at = vm.convertTimestamp();

            if ($stateParams.postId) {
                // get the post details
                EditorService.getPost($stateParams.postId)
                    .then(function(response) {
                        if (response.post) {
                            // convert the timestamp to human readable date
                            response.post.published_at = vm.convertTimestamp(response.post.published_at);
                            vm.post = response.post;
                        }
                    },
                    function(error) {
                        // most likely the post is not found, redirect to 404
                    });
            }
        };

        vm.savePost = function() {
            var post = vm.post;

            // flag that is being processed
            vm.processing = true;

            // send data to the API
            EditorService.savePost(post)
                .then(function(response) {
                    if (response.post) {
                        var responsePost = response.post;

                        // check if this is newly created post by checking if
                        // there's a post id in the post object
                        if (!vm.post.id) {
                            ToastrService
                                .toast('You have successfully created the post"'+
                                responsePost.title+'"', 'success');
                            // TODO: update the URL
                        } else {
                            ToastrService
                                .toast('You have successfully updated "'+
                                responsePost.title+'".', 'success');
                        }

                        // update the published date to human readable form
                        responsePost.published_at = vm.convertTimestamp(responsePost.published_at);
                        // update the scope
                        vm.post = responsePost;
                    }

                    vm.processing = false;
                }, function(error) {
                    vm.processing = false;
                });
        };

        /**
         * Toggles the sidebar to be opened/close.
         */
        vm.toggleSidebar = function() {
            vm.options.toggle = !vm.options.toggle;
        };

        // run this shit!
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.components.login')
        .controller('LoginController', ['$state', 'AuthService', 'LoginService', 'ToastrService', LoginController]);

    /**
     *
     * @param $state
     * @param AuthService
     * @param LoginService
     * @param ToastrService
     * @constructor
     */
    function LoginController($state, AuthService, LoginService, ToastrService) {
        var vm = this;
        // scope variables
        vm.login        = {};
        vm.processing   = false;

        /**
         * Authenticate the login credentials to gain access to the app.
         */
        vm.authenticateLogin = function() {
            var login = vm.login;

            // flag to be processed
            vm.processing = true;

            // perform API request
            LoginService.authenticate(login.email, login.password)
                .then(function(response) {
                    // save user and token
                    if (response.user && response.token) {
                        // greet the user
                        ToastrService.toast('Welcome back, ' + response.user.name);

                        // save
                        AuthService.login(response.user, response.token);

                        // redirect
                        $state.go('post.lists');
                        return;
                    }

                    vm.processing = false;
                },
                function(error) {
                    vm.processing = false;

                    // catch and show the errors
                    var messages = error.errors.message;

                    if (messages) {
                        // show error
                        ToastrService.toast(messages, 'error');
                        return;
                    }

                    // 500/server error?
                });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .controller('PostListsController', ['PostListsService', PostListsController]);

    function PostListsController(PostListsService) {
        var vm = this;

        vm.active = [];
        vm.posts = {};
        vm.processing = true;

        /**
         * Fetches the posts from the API once the page loads.
         */
        vm.initialize = function() {
            PostListsService.getAllPosts()
                .then(function(response) {
                    if (response.posts) {
                        vm.posts = response.posts;

                        // get the first post and set it to active
                        vm.active = vm.posts[0];
                    }

                    vm.processing = false;
                },
                function() {
                    // flag that there's something wrong with fetching the
                    // posts from the API
                });
        };

        /**
         * Shows the selected post from the lists.
         */
        vm.selectThisPost = function(post) {
            vm.active = post;
        };

        // run it!
        vm.initialize();
    }
})();
(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .controller('UserCreateController', ['ToastrService', 'UserCreateService', UserCreateController]);

    function UserCreateController(ToastrService, UserCreateService) {
        var vm = this;

        // controller variables
        vm.errors       = {};
        vm.processing   = false;
        vm.roles        = {};
        vm.user         = {};

        vm.initialize = function() {
            // get the roles
            UserCreateService.getRoles().then(function(response) {
                if (response.roles) {
                    vm.roles = response.roles;
                }
            }, function(error) {
                // error handling
            })
        };

        vm.createUser = function() {
            var user = vm.user;

            // flag that we're processing
            vm.processing = true;

            UserCreateService.createUser(user).then(function(response) {
                if (response.user) {
                    // empty the fields
                    vm.user = {};

                    // show success message
                }
            }, function(error) {
                if (error.errors) {
                    ToastrService.toast('Oops, there some errors encountered.', 'error');

                    vm.errors = error.errors;
                }
            });
        };

        vm.initialize();
    }
})();

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
(function() {
    'use strict';

    angular.module('journal.components.userProfile')
        .controller('UserProfileController', [
            '$stateParams', '$uibModal', 'AuthService', 'ToastrService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($stateParams, $uibModal, AuthService, ToastrService, UserProfileService, CONFIG) {
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
                        return angular.copy(vm.user);;
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

(function() {
    'use strict';

    angular.module('journal.components.userProfileModal')
        .controller('UserProfileModalController', [
            '$scope', '$timeout', '$uibModalInstance', 'FileUploaderService', 'ToastrService', 'UserProfileModalService', 'user', 'type', UserProfileModalController]);

    function UserProfileModalController($scope, $timeout, $uibModalInstance, FileUploaderService, ToastrService, UserProfileModalService, user, type) {
        var vm = this;

        // controller variables
        vm.currentUser = user;
        vm.image = {
            link : null,
            file : null,
            option : 'file',
            url : ''
        };

        vm.processing = false;

        vm.type = type;
        // upload variables
        vm.upload = {
            active : false,
            percentage : 0
        };

        vm.closeModal = function() {
            $uibModalInstance.dismiss('cancel');
        };

        /**
         * Fetches the value of the input and be used as image url.
         */
        vm.getImageLink = function() {
            // delay it for a second
            $timeout(function() {
                vm.image.url = vm.image.link;

                // empty the image link
                vm.image.link = null;

                // update
                vm.updateUserPhotoDetails(vm.image.url);
            }, 1000);
        };

        vm.initialize = function() {
            // check the type of the photo to be updated then update the
            // image url scope
            switch (vm.type) {
                case 'avatar' :
                    vm.image.url = vm.currentUser.avatar_url || '';
                    break;
                case 'cover' :
                    vm.image.url = vm.currentUser.cover_url || '';
                    break;
                default:
                    break;
            }
        };

        /**
         * Removes the image.
         */
        vm.removeImage = function() {
            // empty the image url
            vm.image.url = '';

            // update
            vm.updateUserPhotoDetails(vm.image.url);
        };

        /**
         * Updates the details of the user.
         */
        vm.save = function() {
            var data = vm.currentUser;

            // flag that we're processing
            vm.processing = true;

            // send data to the API
            UserProfileModalService.updateUserDetails(data)
                .then(function(response) {
                    if (response.user) {
                        // show message
                        ToastrService
                            .toast('You have successfully updated your '+vm.type+' photo.');

                        // close modal and send data back to the UserProfileController
                        $uibModalInstance.close(response.user);
                    }

                    vm.processing = false;
                }, function() {
                    ToastrService
                        .toast('Something went wrong while uploading the photo to the server.', 'error');
                    vm.processing = false;
                });
        };

        /**
         * Switches the option to put a featured image.
         */
        vm.switchOption = function() {
            if (vm.image.option == 'file') {
                vm.image.option = 'link';
                return;
            }

            if (vm.image.option == 'link') {
                vm.image.option = 'file';
                return;
            }
        };

        /**
         * Updates the value depending on the image being updated.
         * @param url
         */
        vm.updateUserPhotoDetails = function(url) {
            // check first the type of image being updated
            switch (vm.type) {
                case 'avatar' :
                    vm.currentUser.avatar_url = url;
                    break;
                case 'cover' :
                    vm.currentUser.cover_url = url;
                    break;
                default:
                    break;
            }
        };

        /**
         * Listen for the changes to the image.file scope and trigger the file
         * upload to the API.
         */
        $scope.$watch(function() {
            return vm.image.file;
        }, function(file) {
            if (file) {
                vm.processing = true;

                // upload
                FileUploaderService.upload(file)
                    .progress(function(event) {
                        vm.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
                            // show image
                            vm.image.url = response.url;

                            // update
                            vm.updateUserPhotoDetails(response.url);

                            // hide the progress bar
                            vm.upload = {
                                active : false,
                                percentage : 0
                            };
                        }

                        vm.processing = false;
                    })
                    .error(function() {
                        // handle the error
                        ToastrService
                            .toast('Something went wrong with the upload. Please try again later.', 'error');

                        // hide progress bar
                        vm.upload = {
                            active : false,
                            percentage : 0
                        };

                        vm.processing = false;
                    });
            }
        });

        vm.initialize();
    }
})();

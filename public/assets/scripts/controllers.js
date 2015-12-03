(function() {
    'use strict';

    angular.module('journal.component.deletePostModal')
        .controller('DeletePostModalController', ['$scope', '$modalInstance', 'DeletePostModalService', 'ToastrService', 'post', DeletePostModalController]);

    function DeletePostModalController($scope, $modalInstance, DeletePostModalService, ToastrService, post) {
        $scope.post = post;
        $scope.processing = false;

        $scope.cancelPost = function() {
            // just close the modal
            $modalInstance.dismiss('cancel');
        };

        $scope.deletePost = function() {
            // flag that we're processing
            $scope.processing = true;

            // do an API request to delete the post
            DeletePostModalService.deletePost($scope.post.id)
                .success(function(response) {
                    if (!response.error) {
                        // growl
                        ToastrService.toast(
                            'You have successfully deleted the post "'+$scope.post.title+'"',
                            'success');

                        // return response
                        $modalInstance.close({
                            error : false
                        });
                    }
                })
                .error(function(response) {
                    $scope.processing = false;

                    // tell there's a fucking error
                    ToastrService.toast('Something went wrong. Please try again later.', 'error');
                    // close the fucking modal
                    $modalInstance.dismiss('cancel');
                });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.editor')
        .controller('EditorController', ['$modal', '$state', '$stateParams', 'AuthService', 'EditorService', 'ToastrService', EditorController]);

    function EditorController($modal, $state, $stateParams, AuthService, EditorService, ToastrService) {
        var vm = this,
            // get current date in yyyy/mm/dd hh:ss format
            date = new Date(),
            currentDate = new Date(
                date.getFullYear(),
                date.getMonth(),
                date.getDate(),
                date.getHours(),
                date.getMinutes());

        vm.sidebar = false;
        vm.post = {
            author_id : AuthService.user().id,
            status : 2,
            tags : []
        };

        // editor config
        vm.editor = {
            // button status
            activeStatus : [],
            // base url of the application
            baseUrl : window.location.origin,
            // codemirror settings
            codemirror : { mode : "markdown", tabMode : "indent", lineWrapping : !0},
            // counter
            counter : 0,
            // sets the date
            dateNow : currentDate,
            // list of status
            status : [
                { class : 'danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'info', group : 2, status : 1, text : 'Update Post' }],
            tags : []
        };
        vm.processing = false;

        /**
         * Initialize some functions to run
         */
        vm.initialize = function() {
            // set the default status of the post
            vm.editor.activeStatus = vm.editor.status[1];

            // check first if there's a parameter set
            if ($stateParams.postId) {
                // get the post
                EditorService.getPost($stateParams.postId)
                    .success(function(response) {
                        if (response.post) {
                            vm.post = response.post;

                            // check if the post is published
                            if (response.post.status == 1) {
                                // published post
                                vm.editor.activeStatus = vm.editor.status[3];
                            }

                            // we're gonna assume that there's a published_at field returned
                            vm.editor.dateNow = vm.convertDate(response.post.published_at);
                        }
                    })
                    .error(function(response) {
                        // something went wrong, redirect to 400, 404 or 500 page
                    });
            }
        };

        /**
         * @param timestamp
         * @returns {Date}
         */
        vm.convertDate = function(timestamp) {
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

            // return the current date
            return currentDate;
        };

        /**
         * Shows the modal to confirm to delete the post
         */
        vm.deletePost = function() {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/delete-post-modal/delete-post-modal.html',
                controller : 'DeletePostModalController',
                resolve : {
                    post : function() {
                        return vm.post;
                    }
                }
            });

            modalInstance.result.then(function(results) {
                if (!results.error) {
                    // no error occurred and post is successfully deleted
                    // redirect to list of posts
                    $state.go('post.lists');
                }
            });
        };

        /**
         * Trigger submit event and sends request to API
         */
        vm.savePost = function() {
            var post = vm.post;

            // flag that it is loading
            vm.processing = true;

            // do an API request to save the post
            EditorService.save(post)
                .success(function(response) {
                    var post = response.post;

                    // unflag processing state
                    vm.processing = false;

                    // check if post is newly created
                    if (!vm.post.id) {
                        ToastrService
                            .toast('You have successfully created the post "'+post.title+'".', 'success');
                        // redirect
                        $state.go('postEditor', { postId : post.id });
                        return;
                    }

                    // check if the post is published
                    if (post.status == 1) {
                        // published post
                        vm.editor.activeStatus = vm.editor.status[3];
                    }

                    // check if the post is saved as draft or unpublished
                    if (post.status == 2) {
                        // default to save as draft
                        vm.editor.activeStatus = vm.editor.status[1];
                    }

                    // show message
                    ToastrService
                        .toast('You have successfully updated "'+post.title+'".', 'success');

                    // update the scope
                    vm.post = post;
                })
                .error(function(response) {
                    // unflag processing state
                    vm.processing = false;
                });
        };

        vm.setButtonClass = function() {
            // initialize the button class
            var buttonClass = 'btn-default';

            switch (vm.editor.activeStatus.class) {
                case 'danger' : buttonClass = 'btn-danger'; break;
                case 'primary' : buttonClass = 'btn-primary'; break;
                case 'info' : buttonClass = 'btn-info'; break;
            }

            // check if it's processing
            buttonClass += (vm.processing) ? ' processing' : '';

            return buttonClass;
        };

        /**
         * Sets the status of the post
         * @param state
         */
        vm.setPostStatus = function(state) {
            // set first the active status to the selected one
            vm.editor.activeStatus = state;

            // set the status of the post
            vm.post.status = state.status;
        };

        vm.showMarkdownHelper = function() {
            $modal.open({
                animation : true,
                templateUrl : '/assets/templates/markdown-helper-modal/markdown-helper-modal.html',
                controllerAs : 'mhm',
                controller : 'MarkdownHelperModalController',
                size: 'markdown'
            });
        };

        vm.showPane = function(pane) {
            vm.editor.activePane = pane;
        };

        /**
         * Toggle to open or close the editor sidebar
         */
        vm.toggleSidebar = function() {
            vm.sidebar = !vm.sidebar;
        };

        /**
         * Append the word/words depending the number of words inputted in the editor
         */
        vm.wordCounter = function() {
            return vm.editor.counter > 0 ? vm.editor.counter + ' words' : '0 words';
        };

        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.header')
        .controller('HeaderController', ['$rootScope', '$state', 'AuthService', HeaderController]);

    function HeaderController($rootScope, $state, AuthService) {
        var vm = this;

        // get user details
        vm.user = AuthService.user();

        vm.logout = function() {
            // destroy token
            AuthService.logout();

            // redirect to login page
            $state.go('login');
            return;
        };

        vm.setActiveMenu = function(menu) {
            // get the current state name
            var state = $state.current.name;
            return (state.indexOf(menu) > -1);
        };

        vm.toggleSidebar = function() {
            $rootScope.$broadcast('toggle-sidebar');
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installer')
        .controller('InstallerController', ['$rootScope', InstallerController]);

    function InstallerController($rootScope) {
        var vm = this;

        // listen for broadcast event
        $rootScope.$on('installer-menu', function(response, data) {
            vm.active = (data || 1);
        });
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installerDetails')
        .controller('InstallerDetailsController', ['$rootScope', '$state', 'AuthService', 'ToastrService', 'InstallerDetailsService', InstallerDetailsController]);

    function InstallerDetailsController($rootScope, $state, AuthService, ToastrService, InstallerDetailsService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 2);

        var vm = this;
        vm.account      = [];
        vm.errors       = [];
        vm.processing   = false;

        vm.createAccount = function() {
            // flag that we're processing the request
            vm.processing = true;
            
            InstallerDetailsService.createAccount(vm.account)
                .success(function(response) {
                    if (response.token) {
                        // login the user
                        AuthService.login(response.user);
                        // save the token
                        AuthService.setToken(response.token);
                        // redirect
                        $state.go('installer.success');
                    }
                })
                .error(function(response) {
                    vm.processing = false;
                    
                    // tell that there's an error
                    ToastrService.toast('There are some errors encountered.', 'error');

                    if (response.errors) {
                        // show the error in the template
                        vm.errors = response.errors;
                    }
                });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installerStart')
        .controller('InstallerStartController', [
            '$rootScope', '$state', 'InstallerStartService', 'ToastrService', InstallerStartController]);

    function InstallerStartController($rootScope, $state, InstallerStartService, ToastrService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 1);

        var vm = this;
        vm.processing = false;

        vm.install = function() {
            vm.processing = true;

            InstallerStartService.install()
                .success(function(response) {
                    if (response.installed) {
                        $state.go('installer.details');
                    }
                })
                .error(function() {
                    vm.processing = false;

                    ToastrService.toast('Something went wrong.', 'error');
                });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installerSuccess')
        .controller('InstallerSuccessController', ['$rootScope', '$state', 'AuthService', 'ToastrService', InstallerSuccessController]);

    function InstallerSuccessController($rootScope, $state, AuthService, ToastrService) {
        // broadcast that this is now the active page
        $rootScope.$broadcast('installer-menu', 3);

        var vm = this;

        /**
         * Checks if there's a user logged in
         */
        vm.initialize = function() {
            // check first if there's a logged in user and token
            if (!AuthService.user() && !AuthService.getToken()) {
                // growl it first!
                ToastrService.toast('Hey, something went wrong. Can you repeat again?', 'error');
                // redirect
                $state.go('installer.start');
                return;
            }
        };

        vm.go = function() {
            $state.go('post.lists');
        };

        // fire away!
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.login')
        .controller('LoginController', ['$state', 'AuthService', 'ToastrService', 'LoginService', LoginController]);

    function LoginController($state, AuthService, ToastrService, LoginService) {
        var vm = this;
        vm.loading = false;
        vm.login = [];

        vm.authenticate = function() {
            var login = vm.login;

            // set it to loading
            vm.loading = true;

            // do an API request to authenticate inputted user credentials
            LoginService.authenticate(login.email, login.password)
                .success(function(response) {
                    if (response.user && response.token) {
                        // save the user details
                        AuthService.login(response.user);

                        // save the token
                        AuthService.setToken(response.token);

                        ToastrService.toast('Welcome, ' + response.user.name, 'success');

                        // redirect
                        $state.go('post.lists');
                        return;
                    }
                })
                .error(function(response) {
                    vm.loading = false;

                    var message = response.errors.message;
                    // show message
                    ToastrService.toast(message, 'error');
                });
        };
    }
})();

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
(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .controller('PostListsController', ['$modal', 'PostListService', PostListsController]);

    function PostListsController($modal, PostListService) {
        var vm = this;

        vm.posts = [];
        vm.activePost = null;

        vm.deletePost = function(post) {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/delete-post-modal/delete-post-modal.html',
                controller : 'DeletePostModalController',
                resolve : {
                    post : function() {
                        return post;
                    }
                }
            });

            modalInstance.result.then(function(results) {
                if (!results.error) {
                    // no error occurred and post is successfully deleted
                    // remove the post
                    var index = vm.posts.indexOf(post);
                    vm.posts.splice(index, 1);

                    // get the current first post and make it active
                    vm.activePost = vm.posts[0];

                    // set the active page
                    vm.activePane = 'lists';
                }
            });
        };

        vm.initialize = function() {
            // get all the posts
            PostListService.getPosts()
                .success(function(response) {
                    if (response.posts) {
                        // get the post and assign to the scope
                        vm.posts = response.posts;
                        // get the first post and make it active
                        vm.activePost = response.posts[0];
                    }
                });
        };

        /**
         * Shows the selected post and preview its content
         */
        vm.previewThisPost = function(post) {
            vm.activePost = post;
        };

        // fire away!
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.services')
        .controller('ServicesController', ['ServicesService', 'ToastrService', ServicesController]);

    function ServicesController(ServicesService, ToastrService) {
        var vm = this;
        vm.processing = false;
        vm.services = [];

        /**
         * This will run once the page loads
         */
        vm.initialize = function() {
            // get disqus and google analytics values via API
            ServicesService.getServices('google_analytics')
                .success(function(response) {
                    if (response.settings) {
                        // assign it to our scope
                        vm.services = response.settings;
                    }
                }).error(function() {
                    // something went wrong
                    // TODO: Create a handler to handle server errors from the API.
                });
        };

        /**
         * Handles the submit event that will send a request to the API to save
         * the given data by the user
         * @return {[type]} [description]
         */
        vm.saveServices = function() {
            // prepare the data
            var services = vm.services;

            // flag that a request is being processed
            vm.processing = true;

            // trigger API call
            ServicesService.saveServices(services)
                .success(function(response) {
                    // check for the response
                    if (response.settings) {
                        vm.processing = false;

                        // toast it
                        ToastrService.toast('You have successfully updated your services.', 'success');
                    }
                });
        };

        // fire away
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.settings')
        .controller('SettingsController', ['$modal', 'ToastrService', 'SettingsService', SettingsController]);

    function SettingsController($modal, ToastrService, SettingsService) {
        var vm = this;
        vm.processing = false;
        vm.settings = [];
        vm.themes = [];

        /**
         * Fetch the settings saved
         */
        vm.initialize = function() {
            // get settings
            SettingsService.getSettings('title,description,post_per_page,cover_url,logo_url,theme')
                .success(function(response) {
                    if (response.settings) {
                        vm.settings = response.settings;
                    }
                });

            // get the themes
            SettingsService.themes().success(function(response) {
                if (response.themes) {
                    vm.themes = response.themes;
                }
            });
        };

        vm.saveSettings = function() {
            // flag that we're processing a request
            vm.processing = true;

            // save the settings
            SettingsService.saveSettings(vm.settings)
                .success(function(response) {
                    if (response.settings) {
                        vm.processing = false;

                        // show success message
                        ToastrService.toast('You have successfully updated the settings.', 'success');
                    }
                })
        };

        vm.selectedTheme = function(value) {
            if (vm.settings.theme) {
                return vm.settings.theme == value;
            }

            return false;
        };

        vm.showImageUploader = function(type) {
            var modalInstance = $modal.open({
                animation : true,
                templateUrl : '/assets/templates/uploader-modal/uploader-modal.html',
                controller : 'SettingsModalController',
                resolve : {
                    settings : function() {
                        return vm.settings;
                    },
                    type : function() {
                        return type
                    }
                }
            });

            modalInstance.result.then(function(results) {
                vm.settings = results;
            });
        };

        // fire
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.settingsModal')
        .controller('SettingsModalController', ['$scope', '$modalInstance', 'ToastrService', 'SettingsService', 'FileUploaderService', 'settings', 'type', SettingsModalController]);

    /**
     * Fuck this shit. I hate using scope :( :( :(
     *
     * @param $scope
     * @param $modalInstance
     * @param FileUploaderService
     * @constructor
     */
    function SettingsModalController($scope, $modalInstance, ToastrService, SettingsService, FileUploaderService, settings, type) {
        $scope.activeOption = 'file';
        $scope.imageUrl = null;
        $scope.image = {
            link : null,
            file : null
        };

        $scope.processing = false;
        $scope.settings = settings;
        $scope.upload = {
            active : false,
            percentage : 0
        };

        /**
         * Listen for changes on the scope
         */
        $scope.$watch('image.file', function() {
            if ($scope.image.file != null) {
                // flag that we're processing
                $scope.processing = true;

                FileUploaderService.upload($scope.image.file)
                    .progress(function(event) {
                        $scope.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
                            $scope.processing = false;
                            // show image
                            $scope.imageUrl = response.url;
                            // hide the progress bar
                            $scope.upload = {
                                active : false,
                                percentage : 0
                            };
                        }
                    })
                    .error(function() {
                        $scope.processing = false;
                        // handle the error
                        ToastrService
                            .toast('Something went wrong with the upload. Please try again later.', 'error');

                        // hide progress bar
                        $scope.upload = {
                            active : false,
                            percentage : 0
                        };
                    });
            }
        });

        /**
         * Closes the modal, duh
         */
        $scope.closeModal = function() {
            $modalInstance.dismiss('cancel');
        };

        $scope.initialize = function() {
            if (type == 'cover_url' && $scope.settings.cover_url) {
                $scope.imageUrl = $scope.settings.cover_url;
            }

            if (type == 'logo_url' && $scope.settings.logo_url) {
                $scope.imageUrl = $scope.settings.logo_url;
            }
        };

        /**
         * Removes the currently shown image and it also set the setting field to null
         * so when the user saves the setting, the image will also be removed from the
         * database
         */
        $scope.removeImage = function() {
            // empty the imageUrl scope
            $scope.imageUrl = null;
            // empty the setting scope
            $scope.settings[type] = null;
        };

        /**
         * Saves the settings and updates it in the database
         */
        $scope.save = function() {
            // flag that we're processing
            $scope.processing = true;

            $scope.settings[type] = ($scope.imageUrl) ? $scope.imageUrl : $scope.image.link;

            // save the settings
            SettingsService.saveSettings($scope.settings)
                .success(function(response) {
                    if (response.settings) {
                        // show success message
                        ToastrService.toast('You have successfully updated the settings.', 'success');
                        // close the modal
                        $modalInstance.close(response.settings);
                    }
                })
        };

        /**
         * Switches the option on how the user will upload an image.
         * By default, it is set to upload a file.
         */
        $scope.switchOption = function() {
            switch($scope.activeOption) {
                case 'link' :
                    $scope.activeOption = 'file';
                    break;
                case 'file' :
                    $scope.activeOption = 'link';
                    break;
                default :
                    $scope.activeOption = 'file';
                    break;
            }
        };

        $scope.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.sidebar')
        .controller('SidebarController', ['$state', '$rootScope', 'AuthService', 'SidebarService', SidebarController]);

    function SidebarController($state, $rootScope, AuthService, SidebarService) {
        var vm = this;
        vm.openSidebar = false;
        vm.title = 'Journal';
        vm.user = AuthService.user();

        // listen for broadcast event
        $rootScope.$on('toggle-sidebar', function() {
            vm.openSidebar = !vm.openSidebar;
        });

        vm.initialize = function() {
            // get the title of the blog
            SidebarService.getSettings('title')
                .success(function(response) {
                    vm.title = response.settings.title;
                });
        };

        /**
         * Logs out the user from the admin.
         */
        vm.logout = function() {
            // destroy token
            AuthService.logout();

            // redirect to login page
            $state.go('login');
            return;
        };

        /**
         * Once the overlay is clicked, the sidebar closes.
         */
        vm.tapOverlay = function() {
            vm.toggleSidebar();
        };

        /**
         * Opens or closes the sidebar
         */
        vm.toggleSidebar = function() {
            vm.openSidebar = !vm.openSidebar;
        };

        // fire away
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userCreate')
        .controller('UserCreateController', ['ToastrService', 'UserCreateService', UserCreateController]);

    function UserCreateController(ToastrService, UserCreateService) {
        var vm = this;
        // variables needed
        vm.user = [];
        vm.errors = [];
        vm.processing = false;

        vm.createUser = function() {
            // clear the errors
            vm.errors = [];

            // flag that a request is being processed
            vm.processing = true;

            // send request
            UserCreateService.createUser(vm.user)
                .success(function(response) {
                    // user successfully created
                    if (response.user) {
                        vm.processing = false;

                        // clear the form
                        vm.user = [];
                        // show success message
                        ToastrService
                            .toast('You have successfully added ' + response.user.name, 'success');
                    }
                })
                .error(function(response) {
                    if (response.errors) {
                        vm.processing = false;

                        // tell there's an error
                        ToastrService.toast('There are errors encountered.', 'error');

                        vm.errors = response.errors;

                        // show the errors
                        for (var e in response.errors) {
                            ToastrService.toast(response.errors[e][0], 'error');
                        }
                    }
                });
        };
    }
})();

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

(function() {
    'use strict';

    angular.module('journal.component.userProfile')
        .controller('UserProfileController', ['$modal', '$stateParams', 'AuthService', 'ToastrService', 'UserProfileService', 'CONFIG', UserProfileController]);

    function UserProfileController($modal, $stateParams, AuthService, ToastrService, UserProfileService, CONFIG) {
        var vm = this;

        vm.current = false;
        vm.user = [];
        vm.password = {};
        vm.passwordErrors = [];
        vm.processingChangePassword = false;
        vm.processingUpdateProfile = false;

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
            vm.processingChangePassword = true;

            // do an API request to change the password
            UserProfileService.updatePassword(passwords)
                .success(function(response) {
                    if (response.user) {
                        vm.processingChangePassword = false;

                        // clear the fields
                        ToastrService.toast('You have successfully updated your password.', 'success');
                        // empty the variable scope
                        vm.password = {};
                    }
                })
                .error(function(response) {
                    vm.processingChangePassword = false;

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
            vm.processingUpdateProfile = true;

            // do an API request to update details of the user
            UserProfileService.updateUserDetails(user)
                .success(function(response) {
                    if (response.user) {
                        vm.processingUpdateProfile = false;

                        // toast it!
                        ToastrService.toast('You have successfully updated your profile.', 'success');
                    }
                })
                .error(function(response) {
                    vm.processingUpdateProfile = false;
                });
        };

        // fire away
        vm.initialize();
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userProfileModal')
        .controller('UserProfileModalController', ['$scope', '$modalInstance', 'ToastrService', 'UserProfileService', 'FileUploaderService', 'user', 'type', UserProfileModalController]);

    function UserProfileModalController($scope, $modalInstance, ToastrService, UserProfileService, FileUploaderService, user, type) {
        $scope.activeOption = 'file';
        $scope.imageUrl = null;
        $scope.image = {
            link : null,
            file : null
        };

        $scope.processing = false;
        $scope.user = user;
        $scope.upload = {
            active : false,
            percentage : 0
        };

        /**
         * Listen for changes on the scope
         */
        $scope.$watch('image.file', function() {
            if ($scope.image.file != null) {
                // flag that we're processing a request
                $scope.processing = true;

                FileUploaderService.upload($scope.image.file)
                    .progress(function(event) {
                        $scope.upload = {
                            active : true,
                            percentage : parseInt(100.0 * event.loaded / event.total)
                        };
                    })
                    .success(function(response) {
                        if (response.url) {
                            $scope.processing = false;

                            // show image
                            $scope.imageUrl = response.url;
                            // hide the progress bar
                            $scope.upload = {
                                active : false,
                                percentage : 0
                            };
                        }
                    })
                    .error(function() {
                        $scope.processing = false;

                        // handle the error
                        ToastrService
                            .toast('Something went wrong with the upload. Please try again later.', 'error');

                        // hide progress bar
                        $scope.upload = {
                            active : false,
                            percentage : 0
                        };
                    });
            }
        });

        /**
         * Closes the modal, duh
         */
        $scope.closeModal = function() {
            $modalInstance.dismiss('cancel');
        };

        $scope.initialize = function() {
            if (type == 'cover_url' && $scope.user.cover_url) {
                $scope.imageUrl = $scope.user.cover_url;
            }

            if (type == 'avatar_url' && $scope.user.avatar_url) {
                $scope.imageUrl = $scope.user.avatar_url;
            }
        };

        /**
         * Removes the currently shown image and it also set the setting field to null
         * so when the user saves the setting, the image will also be removed from the
         * database
         */
        $scope.removeImage = function() {
            // empty the imageUrl scope
            $scope.imageUrl = null;
            // empty the setting scope
            $scope.settings[type] = null;
        };

        /**
         * Saves the settings and updates it in the database
         */
        $scope.save = function() {
            // flag that we're processing a request
            $scope.processing = true;

            $scope.user[type] = ($scope.imageUrl) ? $scope.imageUrl : $scope.image.link;

            // do an API request to update details of the user
            UserProfileService.updateUserDetails($scope.user)
                .success(function(response) {
                    if (response.user) {
                        // growl it!
                        ToastrService.toast('You have successfully updated your profile.', 'success');

                        // close the modal and returns the response from the server
                        $modalInstance.close(response.user);
                    }
                })
                .error(function(response) {
                    // handle the error
                    $scope.processing = false;
                });
        };

        /**
         * Switches the option on how the user will upload an image.
         * By default, it is set to upload a file.
         */
        $scope.switchOption = function() {
            switch($scope.activeOption) {
                case 'link' :
                    $scope.activeOption = 'file';
                    break;
                case 'file' :
                    $scope.activeOption = 'link';
                    break;
                default :
                    $scope.activeOption = 'file';
                    break;
            }
        };

        $scope.initialize();
    }
})();

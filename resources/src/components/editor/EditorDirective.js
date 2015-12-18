(function() {
    'use strict';

    angular.module('journal.components.editor')
        .directive('editorScroll', [EditorScroller])
        .directive('editorPublishButtons', [EditorPublishButtons])
        .directive('inputPostSlug', [InputPostSlug])
        .directive('editorSidebar', [EditorSidebar])
        .directive('featuredImage', [FeaturedImage]);

    /**
     * Enables both editor windows to scroll in sync.
     *
     * @returns {{restrict: string, link: Function}}
     * @constructor
     */
    function EditorScroller() {
        return {
            restrict : 'C',
            link : function() {
                angular
                    .element(document.getElementsByClassName('CodeMirror-scroll')[0])
                    .on('scroll', function(event) {
                        var editor = angular.element(event.target),
                            previewContent = angular.element(
                                document.getElementsByClassName('preview-wrapper')),
                            sizer = angular.element(
                                document.getElementsByClassName('CodeMirror-sizer')),
                            renderedMarkdown = angular.element(
                                document.getElementsByClassName('rendered-markdown')),
                            editorDifference = sizer[0].offsetHeight - editor[0].offsetHeight,
                            previewDifference = renderedMarkdown[0].offsetHeight - previewContent[0].offsetHeight,
                            quotient = previewDifference / editorDifference,
                            scroll = editor[0].scrollTop * quotient;

                        previewContent[0].scrollTop = scroll;
                    });
            }
        }
    }

    /**
     * Controls and sets the options to be shown on changing the post status
     * and the status indicator for the post.
     *
     * @returns {{require: string, restrict: string, replace: boolean, scope: {postStatus: string}, templateUrl: string, controllerAs: string, controller: *[], link: Function}}
     * @constructor
     */
    function EditorPublishButtons() {
        return {
            require : 'ngModel',
            restrict : 'EA',
            replace : true,
            scope : {
                ngModel : '=ngModel',
                post : '=post'
            },
            templateUrl : '/assets/templates/editor/_editor-publish-buttons.html',
            controllerAs : 'epb',
            controller : ['$scope', function($scope) {
                var vm = this;

                // scope variables
                vm.options = {
                    status : 2,
                    active : [],
                    buttons : [
                        { class : 'btn-danger', group : 1, status : 1, text : 'Publish Now' },
                        { class : 'btn-primary', group : 1, status : 2, text : 'Save as Draft' },
                        { class : 'btn-danger', group : 2, status : 2, text : 'Unpublish Post' },
                        { class : 'btn-info', group : 2, status : 1, text : 'Update Post' }]
                };

                /**
                 * Set the status of the post.
                 * @param option
                 */
                vm.selectPostStatus = function(option) {
                    vm.options.active = option;

                    // set post status
                    vm.options.status = vm.options.active.status;
                    // update ng-model
                    $scope.ngModel = vm.options.status;
                };

                /**
                 * Set the options and state of the buttons.
                 * @param status
                 */
                vm.setButtons = function(status) {
                    // we're going to assume that status is published.
                    var selectedOption = vm.options.buttons[3];

                    // check if the post status is draft
                    if (status == 2) {
                        selectedOption = vm.options.buttons[1];
                    }

                    // set the button option
                    vm.options.active = selectedOption;
                };
            }],
            link : function(scope, element, attributes) {
                scope.$watch('post', function(post) {
                    // set button
                    scope.epb.setButtons(post.status);
                });
            }
        }
    }

    /**
     * Generates slug based on the post title inputted.
     *
     * @returns {{require: string, restrict: string, scope: {title: string, postId: string, slug: string}, controllerAs: string, controller: *[], link: Function}}
     * @constructor
     */
    function InputPostSlug() {
        return {
            require : 'ngModel',
            restrict : 'C',
            scope : {
                title   : '=ngModel',
                postId  : '=postId',
                slug    : '=slug'
            },
            controllerAs : 'ips',
            controller : ['$scope', 'EditorService', function($scope, EditorService) {
                var vm = this;

                /**
                 * Sends a request to the API to generate the slug based on the
                 * given title of the post.
                 *
                 * @param title
                 * @param id
                 */
                vm.checkPostTitle = function(title, id) {
                    EditorService.getSlug(title, id).then(function(response) {
                        if (response.slug) {
                            // update the slug scope
                            $scope.slug = response.slug;
                        }
                    }, function(error) {
                        // TODO: handle error
                    });
                };
            }],
            link : function(scope, element, attributes, ngModel) {
                // check for if the element triggers a blur event
                element.on('blur', function() {
                    // get the value of the input
                    var title = ngModel.$modelValue;

                    // check first if title is set or not empty
                    if (!title || title.length == 0) {
                        return;
                    }

                    // generate slug
                    scope.ips.checkPostTitle(title, scope.postId);
                });
            }
        }
    }

    /**
     * Sidebar directive for the Editor.
     * @returns {{restrict: string, scope: {toggle: string, postData: string}, replace: boolean, templateUrl: string, controllerAs: string, controller: *[], link: Function}}
     * @constructor
     */
    function EditorSidebar() {
        return {
            restrict : 'EA',
            scope : {
                toggle      : '=toggle',
                postData    : '=post'
            },
            replace : true,
            templateUrl : ' /assets/templates/editor/_editor-sidebar.html',
            controllerAs : 'es',
            controller : ['$scope', function($scope) {
                var vm = this;

                // scope variables
                vm.toggle   = false;
                vm.post     = [];
                vm.siteUrl  = window.location.origin;

                /**
                 * Closes the sidebar.
                 */
                vm.closeSidebar = function() {
                    // close the sidebar
                    vm.toggle = false;
                    // update the scope
                    $scope.toggle = false;
                };

                /**
                 * Listen for changes in the vm.post object so we can update
                 * the scope post.
                 */
                $scope.$watchCollection(function() {
                    return vm.post;
                }, function(post) {
                    // update the scope post
                    $scope.post = post;
                });
            }],
            link : function(scope, element, attributes) {
                // check for the scope post
                scope.$watch('postData', function(post) {
                    // assign to the controller variable
                    scope.es.post = post;
                });

                // check for the scope toggle
                scope.$watch('toggle', function(toggle) {
                    scope.es.toggle = toggle;
                });
            }
        }
    }

    function FeaturedImage() {
        return {
            restrict : 'EA',
            require : 'ngModel',
            scope : {
                featuredImage : '=ngModel'
            },
            replace: true,
            templateUrl : '/assets/templates/editor/_featured-image.html',
            controllerAs : 'fi',
            controller : ['$scope', '$timeout', 'FileUploaderService', 'ToastrService',
                function($scope, $timeout, FileUploaderService, ToastrService) {
                var vm = this;

                // scope controller variables
                vm.image = {
                    link : null,
                    file : null,
                    option : 'file',
                    url : ''
                };

                vm.processing = false;

                // upload variables
                vm.upload = {
                    active : false,
                    percentage : 0
                };

                /**
                 * Fetches the value of the input and be used as image url.
                 */
                vm.getImageLink = function() {
                    // delay it for a second
                    $timeout(function() {
                        vm.image.url = vm.image.link;
                        // update the scope
                        $scope.featuredImage = vm.image.url;

                        // empty the image link
                        vm.image.link = null;
                    }, 1000);
                };

                /**
                 * Removes the image.
                 */
                vm.removeImage = function() {
                    // empty the image url
                    vm.image.url = '';
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
                 * Watches the change in the scope variable which will trigger
                 * the upload to the server side.
                 */
                $scope.$watch(function() {
                    return vm.image.file;
                }, function(file) {
                    if (file) {
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

                                    // attach to the ng-model
                                    $scope.featuredImage = vm.image.url;

                                    // hide the progress bar
                                    vm.upload = {
                                        active : false,
                                        percentage : 0
                                    };
                                }
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
                            });
                    }
                });
            }],
            link : function(scope, element, attributes, ngModel) {
                scope.$watch('featuredImage', function(imageUrl) {
                    scope.fi.image.url = imageUrl;
                });
            }
        }
    }
})();

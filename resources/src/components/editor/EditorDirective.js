(function() {
    'use strict';

    angular.module('journal.components.editor')
        .directive('editorScroll', [EditorScroller])
        .directive('editorPublishButtons', [EditorPublishButtons])
        .directive('inputPostSlug', [InputPostSlug]);

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
                postStatus : '=ngModel'
            },
            templateUrl : '/assets/templates/editor/_editor-publish-buttons.html',
            controllerAs : 'epb',
            controller : ['$scope', function($scope) {
                var vm = this;

                // scope variables
                vm.options = {
                    status : 0,
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
                    // update the ng-model scope
                    $scope.postStatus = vm.options.active.status;
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
                // listen for changes to the ng-model value
                scope.$watch('postStatus', function(value) {
                    // assign the value to the controller variable
                    scope.epb.options.status = value;
                    // update the button
                    scope.epb.setButtons(value);
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
})();

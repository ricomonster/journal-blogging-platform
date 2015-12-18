(function() {
    'use strict';

    angular.module('journal.components.editor')
        .directive('editorScroll', [EditorScroller])
        .directive('editorPublishButtons', [EditorPublishButtons])
        .directive('inputPostSlug', [InputPostSlug])
        .directive('editorSidebar', [EditorSidebar]);

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
})();

(function() {
    'use strict';

    angular.module('journal.components.sidebar')
        .directive('journalSidebar', [SidebarDirective]);

    function SidebarDirective() {
        return {
            restrict : 'E',
            templateUrl : '/assets/templates/sidebar/_sidebar-directive.html',
            replace: true,
            link : function(scope, element, attributes) {

            }
        }
    }
})();
(function() {
    'use strict';

    angular.module('journal.shared.markdownReader')
        .directive('journalMarkdown', ['MarkdownReader', JournalMarkdown]);

    function JournalMarkdown(MarkdownReader) {
        return {
            restrict : 'AE',
            scope : {
                journalMarkdown : '=',
                counter : '='
            },
            link : function(scope, element, attributes) {
                var countWords = function() {
                    var wordCount = 0;
                    // trim first the content
                    var trimmedContent = element.text().replace(/^\s\s*/, '').replace(/\s\s*$/, '');

                    // check if there is content
                    if (trimmedContent.length > 0) {
                        wordCount = trimmedContent.match(/[^\s]+/g).length;
                    }

                    scope.counter = wordCount;
                };

                if (attributes.journalMarkdown) {
                    scope.$watch('journalMarkdown', function(value) {
                        var html = value ? MarkdownReader.makeHtml(value) : '';

                        if (attributes.hideScriptIframe) {
                            // replace the scripts and iframes
                            html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                                '<div class="embedded-javascript">Embedded JavaScript</div>');
                            html = html.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
                                '<div class="embedded-iframe">Embedded iFrame</div>');
                        }

                        element.html(html);
                        countWords();
                    });
                } else {
                    var html = MarkdownReader.makeHtml(element.text());

                    if (attributes.hideScriptIframe) {
                        // replace the scripts and iframes
                        html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                            '<div class="embedded-javascript">Embedded JavaScript</div>');
                        html = html.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
                            '<div class="embedded-iframe">Embedded iFrame</div>');
                    }

                    element.html(html);
                    countWords();
                }
            }
        }
    }
})();
(function() {
    'use strict';

    angular.module('journal.component.editor')
        .directive('checkPostSlug', ['$timeout', 'EditorService', CheckPostSlug])
        .directive('tagInput', ['EditorService', TagInput])
        .directive('editorScroll', [EditorScroll])
        .directive('featuredImage', ['FileUploaderService', 'ToastrService', FeaturedImageDirective]);

    function CheckPostSlug($timeout, EditorService) {
        return {
            require : 'ngModel',
            scope : {
                ngModel : '=',
                slug : '=',
                postId : '='
            },
            link : function(scope, element, attribute, ngModel) {
                element.on('blur', function() {
                    var title = ngModel.$modelValue;

                    // check first if title is set or not empty
                    if (!title || title.length == 0) {
                        return;
                    }

                    // do an API request to check if the slug to be generated is valid
                    EditorService.checkSlug(title, scope.postId)
                        .success(function(response) {
                            if (response.slug) {
                                $timeout(function() {
                                    scope.slug = response.slug;
                                });
                            }
                        })
                        .error(function(response) {
                            // TODO:show error via growl
                        });
                });
            }
        }
    }

    function TagInput(EditorService) {
        return {
            restrict: 'E',
            templateUrl: '/assets/templates/editor/tags.html',
            scope: {
                postTags: '=tags'
            },
            link: function (scope, element, attrs) {
                scope.inputtedTag       = '';
                scope.journalTags       = [];
                scope.suggestedTags     = [];
                scope.tags              = [];
                scope.showSuggestions   = false;

                /**
                 * Handles the click event when the user wants the suggested tag
                 * to be added as tag for the post
                 * @param tag
                 */
                scope.addTag = function(tag) {
                    // add the tag
                    scope.addToPostTag(tag);
                };

                /**
                 * Adds the tag to the array of post tags
                 * @param tag
                 */
                scope.addToPostTag = function(tag) {
                    // check first if tag already exists
                    for (var t in scope.postTags) {
                        if (scope.postTags[t].name == tag) {
                            return;
                        }
                    }

                    // hide the suggestion box
                    scope.showSuggestions   = false;

                    // add the tag
                    scope.postTags.push({ name : tag });
                    // empty the inputTag scope
                    scope.inputtedTag = '';

                    // fix the suggestion tags
                    scope.setupSuggestionTags();
                };

                /**
                 * This will run once the page/directive loads. Fetches the tags
                 * from the API and will cross reference the tags and determines
                 * which will be shown as suggestion tags.
                 */
                scope.initialize = function() {
                    EditorService.getTags()
                        .success(function(response) {
                            if (response.tags) {
                                // for storage
                                scope.journalTags = response.tags;

                                // check the tags between the tags from the post and
                                // tags from journal
                                scope.setupSuggestionTags();
                            }
                        });
                };

                /**
                 * Handles the the keypress event. As the user types, whatever the
                 * user is inputted, it will render suggested tags based on it. It
                 * also adds the inputted word to the post tags once the user pressed
                 * the 'Enter' key
                 * @param event
                 */
                scope.inputYourTag = function(event) {
                    scope.showSuggestions = true;

                    console.log(scope.suggestedTags.length);

                    // user pressed the 'Enter' key and assuming that the user
                    // inputted something in the scope
                    if (event.which == 13) {
                        event.preventDefault();

                        if (scope.inputtedTag.length > 0) {
                            // add the tag
                            scope.addToPostTag(scope.inputtedTag);
                        }
                    }

                    // input is empty
                    if (scope.inputtedTag.length < 0) {
                        // close the suggestions
                        scope.showSuggestions = false;
                    }
                };

                /**
                 * Fixes the tags to be suggested
                 */
                scope.setupSuggestionTags = function() {
                    scope.suggestedTags = scope.journalTags;

                    if (!scope.postTags) {
                        return;
                    }

                    // loop the post tags
                    for (var p in scope.postTags) {
                        // loop the suggested tags
                        for (var s in scope.suggestedTags) {
                            // check the tags in they are the same
                            if (scope.postTags[p].name == scope.suggestedTags[s].name) {
                                // remove it from the array
                                scope.suggestedTags.splice(s, 1);
                            }
                        }
                    }

                    return;
                };

                /**
                 * Removes a specific post tag
                 * @param tag
                 */
                scope.removePostTag = function(tag) {
                    var index = scope.postTags.indexOf(tag);
                    scope.postTags.splice(index, 1);

                    // return to the suggested tags
                    scope.suggestedTags.push(tag);
                };

                // fire away
                scope.initialize();
            }
        }
    }

    function EditorScroll() {
        return {
            link : function() {
                angular
                    .element(document.getElementsByClassName('CodeMirror-scroll')[0])
                    .on('scroll', function(event) {
                        var editor = angular.element(event.target),
                            previewContent = angular.element(
                                document.getElementsByClassName('entry-preview-content')),
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

    function FeaturedImageDirective(FileUploaderService, ToastrService) {
        return {
            restrict : 'EA',
            scope : {
                featuredImage : '=ngModel'
            },
            controllerAs : 'fid',
            controller : ['$scope', function($scope) {
                var vm = this;
                vm.activeOption = 'file';
                vm.imageUrl = $scope.featuredImage || '';
                vm.image = {
                    link : null,
                    file : null
                };

                vm.processing = false;
                vm.upload = {
                    active : false,
                    percentage : 0
                };

                /**
                 * Handles the blur event to get the image link
                 */
                vm.getImageLink = function() {
                    vm.imageUrl = vm.image.link;
                };

                /**
                 * Removes the featured image
                 */
                vm.removeImage = function() {
                    vm.imageUrl = null;
                    // empty the link, just in case
                    vm.image.link = null;
                };

                /**
                 * Handles changing of option on how to upload featured image.
                 * @returns {*}
                 */
                vm.switchOption = function() {
                    if (vm.activeOption == 'file') {
                        return vm.activeOption = 'link';
                    } else if (vm.activeOption == 'link') {
                        return vm.activeOption = 'file';
                    }
                };

                /**
                 * Listens for changes in the file scope
                 */
                $scope.$watch(function() {
                    return vm.image.file;
                }, function(file) {
                    // now let's processing the image to be uploaded
                    if (file) {
                        FileUploaderService.upload(file)
                            .progress(function(event) {
                                vm.upload = {
                                    active : true,
                                    percentage : parseInt(100.0 * event.loaded / event.total)
                                };
                            })
                            .success(function(response) {
                                if (response.url) {
                                    //$scope.processing = false;

                                    // show image
                                    vm.imageUrl = response.url;
                                    // hide the progress bar
                                    vm.upload = {
                                        active : false,
                                        percentage : 0
                                    };
                                }
                            })
                            .error(function() {
                                //$scope.processing = false;

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

                /**
                 * Listen for changes for the image url
                 */
                $scope.$watch(function() {
                    return vm.imageUrl;
                }, function(image) {
                    // set the ng-model
                    $scope.featuredImage = image;
                });
            }],
            replace : true,
            templateUrl : '/assets/templates/editor/featured-image.html',
            link : function(scope, element, attributes, controller) {

            }
        }
    }
})();

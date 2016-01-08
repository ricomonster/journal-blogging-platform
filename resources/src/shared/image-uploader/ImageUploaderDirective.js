(function() {
    'use strict';

    angular.module('journal.shared.imageUploader')
        .directive('imageUploader', ['CONFIG', ImageUploaderDirective]);

    function ImageUploaderDirective(CONFIG) {
        return {
            restrict : 'EA',
            require : 'ngModel',
            scope : {
                image : '=ngModel'
            },
            templateUrl : CONFIG.TEMPLATE_PATH + '/image-uploader/_image-uploader.html',
            controllerAs : 'iud',
            controller : ['$scope', '$state', 'FileUploaderService', function($scope, $state, FileUploaderService) {
                var vm = this;

                // controller variables
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
                                    $scope.image = vm.image.url;

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
            link : function(scope, element, attribute) {
                scope.$watch('image', function(imageUrl) {
                    scope.iud.image.url = imageUrl;
                });
            }
        }
    }
})();

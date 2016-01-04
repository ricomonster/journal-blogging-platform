(function() {
    'use strict';

    angular.module('journal.shared.fileUploader')
        .service('FileUploaderService', ['Upload', 'CONFIG', FileUploaderService]);

    function FileUploaderService(Upload, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.upload = function(file) {
            return Upload.upload({
                url : this.apiUrl + '/upload',
                file : file
            });
        }
    }
})();
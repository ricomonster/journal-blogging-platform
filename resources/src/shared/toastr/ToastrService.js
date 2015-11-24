(function() {
    'use strict';

    angular.module('journal.shared.toastr')
        .service('ToastrService', ['toastr', ToastrService]);

    function ToastrService(toastr) {
        this.toast = function(message, type) {
            switch (type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning('message');
                    break;
                default:
                    toastr.success(message);
                    break;
            }
        };
    }
})();
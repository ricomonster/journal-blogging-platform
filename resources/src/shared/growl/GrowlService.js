(function() {
    'use strict';

    angular.module('journal.shared.growl')
        .service('GrowlService', ['growl', GrowlService]);

    function GrowlService(growl) {
        this.message = 'Message';
        this.status = 'success';

        this.growl = function(message, status) {
            // check if the status exists
            switch (status) {
                case 'warning' :
                    growl.warning(message);
                    break;
                case 'info' :
                    growl.info(message);
                    break;
                case 'success' :
                    growl.success(message);
                    break;
                case 'error' :
                    growl.error(message);
                    break;
                default :
                    growl.success(message);
                    break;
            }
        };
    }
})();

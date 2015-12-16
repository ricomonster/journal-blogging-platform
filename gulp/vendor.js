var paths = require('./paths.js');

module.exports = {
    minified : {
        app : [
            paths.destination.vendorBase + '/vendor.js',
            paths.destination.vendorBase + '/journal.js'
        ],
        css : [
            paths.destination.vendor.css + '/bootstrap.min.css',
            paths.destination.vendor.css + '/font-awesome.min.css',
            paths.destination.vendor.css + '/angular-toastr.min.css',
            paths.destination.vendor.css + '/codemirror.css',
            paths.destination.vendor.css + '/ngprogress-lite.css',
            paths.destination.assets.css + '/screen.css'
        ],
        js : [
            paths.destination.vendor.js + '/codemirror.js',
            paths.destination.vendor.js + '/showdown.min.js',
            paths.destination.vendor.js + '/moment.min.js',
            paths.destination.vendor.js + '/angular.min.js',
            paths.destination.vendor.js + '/angular-animate.min.js',
            paths.destination.vendor.js + '/angular-sanitize.min.js',
            paths.destination.vendor.js + '/angular-ui-router.min.js',
            paths.destination.vendor.js + '/ui-bootstrap.min.js',
            paths.destination.vendor.js + '/ui-bootstrap-tpls.min.js',
            paths.destination.vendor.js + '/ui-codemirror.min.js',
            paths.destination.vendor.js + '/angular-local-storage.min.js',
            paths.destination.vendor.js + '/angular-toastr.min.js',
            paths.destination.vendor.js + '/angular-toastr.tpls.min.js',
            paths.destination.vendor.js + '/angular-moment.min.js',
            paths.destination.vendor.js + '/ng-file-upload-shim.min.js',
            paths.destination.vendor.js + '/ng-file-upload.min.js',
            paths.destination.vendor.js + '/ngprogress-lite.min.js'
        ]
    },
    unminified : {
        app : [
            paths.destination.assets.js + '/app.js',
            paths.destination.assets.js + '/controllers.js',
            paths.destination.assets.js + '/directives.js',
            paths.destination.assets.js + '/providers.js',
            paths.destination.assets.js + '/services.js'
        ],
        css : [
            paths.destination.vendor.css + '/bootstrap.css',
            paths.destination.vendor.css + '/font-awesome.css',
            paths.destination.vendor.css + '/angular-toastr.css',
            paths.destination.vendor.css + '/codemirror.css',
            paths.destination.vendor.css + '/ngprogress-lite.css',
            paths.destination.assets.css + '/screen.css'
        ],
        js : [
            paths.destination.vendor.js + '/codemirror.js',
            paths.destination.vendor.js + '/showdown.js',
            paths.destination.vendor.js + '/moment.js',
            paths.destination.vendor.js + '/angular.js',
            paths.destination.vendor.js + '/angular-animate.js',
            paths.destination.vendor.js + '/angular-sanitize.js',
            paths.destination.vendor.js + '/angular-ui-router.js',
            paths.destination.vendor.js + '/ui-bootstrap.js',
            paths.destination.vendor.js + '/ui-bootstrap-tpls.js',
            paths.destination.vendor.js + '/ui-codemirror.js',
            paths.destination.vendor.js + '/angular-local-storage.js',
            paths.destination.vendor.js + '/angular-toastr.js',
            paths.destination.vendor.js + '/angular-toastr.tpls.js',
            paths.destination.vendor.js + '/angular-moment.js',
            paths.destination.vendor.js + '/ng-file-upload-shim.js',
            paths.destination.vendor.js + '/ng-file-upload.js',
            paths.destination.vendor.js + '/ngprogress-lite.js'
        ]
    }
};
var paths = require('./paths');

module.exports = {
    css : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/css/*',
        // codemirror
        paths.base.bower + '/codemirror/lib/codemirror.css',
        // font awesome
        paths.base.bower + '/font-awesome/css/*'
    ],
    js : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/js/*',
        // codemirror
        paths.base.bower + '/codemirror/lib/codemirror.js',
        // jquery
        paths.base.bower + '/jquery/dist/*.js',
        // showdown
        paths.base.bower + '/showdown/dist/*.js',
        // vue
        paths.base.bower + '/vue/dist/*.js',
        // vue resource
        paths.base.bower + '/vue-resource/dist/*.js',
        // vue strap
        paths.base.bower + '/vue-strap/dist/*.js'
    ],
    fonts : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/fonts/*',
        // font awesome
        paths.base.bower + '/font-awesome/fonts/*'
    ]
};

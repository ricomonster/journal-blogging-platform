var paths = require('./paths');

module.exports = {
    css : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/css/*',
        // font awesome
        paths.base.bower + '/font-awesome/css/*'
    ],
    js : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/js/*',
        // jquery
        paths.base.bower + '/jquery/dist/*.js',
        // vue
        paths.base.bower + '/vue/dist/*.js',
        // vue resource
        paths.base.bower + '/vue-resource/dist/*.js'
    ],
    fonts : [
        // bootstrap
        paths.base.bower + '/bootstrap/dist/fonts/*',
        // font awesome
        paths.base.bower + '/font-awesome/fonts/*'
    ]
}

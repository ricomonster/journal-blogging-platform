var gulp            = require('gulp'),
    autoprefixer    = require('gulp-autoprefixer'),
    concat          = require('gulp-concat'),
    minifyCss       = require('gulp-minify-css'),
    plumber         = require('gulp-plumber'),
    notify          = require('gulp-notify'),
    uglify          = require('gulp-uglify'),
    util            = require('gulp-util'),
    inject          = require('gulp-inject'),
    runSequence     = require('run-sequence');

var paths       = require('./gulp/paths'),
    bowerFiles  = require('./gulp/bower'),
    vendor      = require('./gulp/vendor');

// check the environment to use
var isProduction = (util.env.prod) ? true : false;

// notify error handler
var onError = function(err) {
    notify.onError({
        title:    "Gulp",
        subtitle: "Failure!",
        message:  "Error: <%= error.message %>",
        sound:    "Beep"
    })(err);

    this.emit('end');
};

var transformAssetPath = function(filename) {
    filename = "{{ asset('"+filename.replace('/public', '')+"') }}";

    // check if the file is a js or css file
    if (filename.indexOf('js') > -1) {
        return '<script type="text/javascript" src="'+filename+'"></script>';
    }

    return '<link rel="stylesheet" href="'+filename+'"/>';
};

/**
 * Build: Transfer needed files from bower to the vendor folder
 */
gulp.task('install-from-bower', function() {
    // css
    gulp.src(bowerFiles.css)
        .pipe(gulp.dest(paths.destination.vendor.css));

    // fonts
    gulp.src(bowerFiles.fonts)
        .pipe(gulp.dest(paths.destination.vendor.fonts));

    // js
    gulp.src(bowerFiles.js)
        .pipe(gulp.dest(paths.destination.vendor.js));
});

/**
 * Build: Build task for the admin main app file.
 */
gulp.task('build-admin-app', function() {
    return gulp.src(paths.sources.app)
        .pipe(concat('app.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.build))
        .pipe(gulp.dest(paths.destination.assets.js));
});

/**
 * Build: Build task for the admin controllers.
 */
gulp.task('build-admin-controllers', function() {
    return gulp.src([
            paths.sources.components.controllers,
            paths.sources.shared.controllers
        ])
        .pipe(concat('controllers.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.build))
        .pipe(gulp.dest(paths.destination.assets.js));
});

/**
 * Build: Build task for the admin directives.
 */
gulp.task('build-admin-directives', function() {
    return gulp.src([
            paths.sources.components.directives,
            paths.sources.shared.directives
        ])
        .pipe(concat('directives.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.build))
        .pipe(gulp.dest(paths.destination.assets.js));
});

/**
 * Build: Build task for the admin services.
 */
gulp.task('build-admin-services', function() {
    return gulp.src([
            paths.sources.components.services,
            paths.sources.shared.services
        ])
        .pipe(concat('services.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.build))
        .pipe(gulp.dest(paths.destination.assets.js));
});

/**
 * Build: Build task for the admin providers.
 */
gulp.task('build-admin-providers', function() {
    return gulp.src([
            paths.sources.components.providers,
            paths.sources.shared.providers
        ])
        .pipe(concat('providers.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.build))
        .pipe(gulp.dest(paths.destination.assets.js));
});

/**
 * Build: Copies the templates.
 */
gulp.task('build-admin-templates', function() {
    return gulp.src([
            paths.sources.components.templates,
            paths.sources.shared.templates
        ])
        .pipe(gulp.dest(paths.destination.build + '/templates'))
        .pipe(gulp.dest(paths.destination.templates));
});

/**
 * Build: Builds the stylesheets
 */
gulp.task('build-stylesheets', function() {
    return gulp.src(paths.sources.css)
        // autoprefixer
        .pipe(autoprefixer('last 2 versions'))
        .pipe(plumber({
            errorHandler : onError
        }))
        // concatenate
        .pipe(concat('screen.css'))
        .pipe(plumber({
            errorHandler : onError
        }))
        // uglify if it sets to production
        .pipe(isProduction ? minifyCss() : util.noop())
        .pipe(plumber({
            errorHandler : onError
        }))
        // go to build
        .pipe(gulp.dest(paths.destination.build))
        // assets
        .pipe(gulp.dest(paths.destination.assets.css));
});

/**
 * Build: Builds the vendor files and concatenates them all to a single file.
 */
gulp.task('build-vendor', function() {
    return gulp.src(vendor.minified.js)
        // concat
        .pipe(concat('vendor.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        // uglify
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.vendorBase));
});

/**
 * Build: Builds the application files.
 */
gulp.task('build-admin', function() {
    return gulp.src(paths.sources.build)
        // concat
        .pipe(concat('journal.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        // uglify
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.destination.vendorBase));
});

/**
 * Inject: Inject needed scripts to the base file.
 */
gulp.task('inject-scripts', function() {
    var devJsFiles = vendor.unminified.js.concat(vendor.unminified.app);

    return gulp.src('./resources/views/journal.blade.php')
        // insert css of the dependencies
        .pipe(inject(gulp.src(vendor.minified.css), {
            transform : transformAssetPath
        }))
        // insert js dependencies
        .pipe(inject(gulp.src(isProduction ? vendor.minified.app : devJsFiles), {
            transform : transformAssetPath
        }))
        .pipe(gulp.dest('./resources/views'));
});

/**
 * Watch: Watches the selected files and run specific tasks on it.
 */
gulp.task('watch', function() {
    // app
    gulp.watch(paths.sources.app, ['build-admin-app']);

    // controllers
    gulp.watch([
        paths.sources.components.controllers,
        paths.sources.shared.controllers
    ], ['build-admin-controllers']);

    // directives
    gulp.watch([
        paths.sources.components.directives,
        paths.sources.shared.directives
    ], ['build-admin-directives']);

    // services
    gulp.watch([
        paths.sources.components.services,
        paths.sources.shared.services
    ], ['build-admin-services']);

    // templates
    gulp.watch([
        paths.sources.components.templates,
        paths.sources.shared.templates
    ], ['build-admin-templates']);

    // css
    gulp.watch(paths.sources.css, ['build-stylesheets']);
});

gulp.task('default', function(callback) {
    // check if there's a production flag
    if (isProduction) {
        runSequence(
            'install-from-bower',
            'build-vendor',
            'build-admin',
            'build-stylesheets',
            'inject-scripts',
            callback);
        return;
    }

    // development setup
    runSequence(
        'install-from-bower',
        'build-admin-app',
        'build-admin-controllers',
        'build-admin-directives',
        'build-admin-services',
        'build-admin-providers',
        'build-admin-templates',
        'build-stylesheets',
        'inject-scripts',
        'watch',
        callback);
    return;
});
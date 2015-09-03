var gulp        = require('gulp'),
    concat      = require('gulp-concat'),
    minifyCss   = require('gulp-minify-css'),
    plumber     = require('gulp-plumber'),
    notify      = require('gulp-notify'),
    uglify      = require('gulp-uglify'),
    inject      = require('gulp-inject'),
    runSequence = require('run-sequence');

// paths
var paths = {
    src : './resources/src',
    public : './public',
    bower : './bower_components'
};

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
 * Build Task: Fetch library files from the bower_components folder
 */
gulp.task('build-bower-files', function() {
    // javascript
    gulp.src([
        // angular
        paths.bower + '/angular/angular.js',
        paths.bower + '/angular/angular.min.js',
        // angular animate
        paths.bower + '/angular-animate/*.js',
        // angular bootstrap
        paths.bower + '/angular-bootstrap/ui-bootstrap.js',
        paths.bower + '/angular-bootstrap/ui-bootstrap.min.js',
        paths.bower + '/angular-bootstrap/ui-bootstrap-tpls.js',
        paths.bower + '/angular-bootstrap/ui-bootstrap-tpls.min.js',
        // angular local storage
        paths.bower + '/angular-local-storage/dist/*',
        // angular sanitize
        paths.bower + '/angular-sanitize/angular-sanitize.js',
        paths.bower + '/angular-sanitize/angular-sanitize.min.js',
        // angular toastr
        paths.bower + '/angular-toastr/dist/*.js',
        // angular ui codemirror
        paths.bower + '/angular-ui-codemirror/*.js',
        // angular ui router
        paths.bower + '/angular-ui-router/release/*',
        // codemirror
        paths.bower + '/codemirror/lib/codemirror.js',
        // ng-file-upload
        paths.bower + '/ng-file-upload/ng-file-upload.js',
        paths.bower + '/ng-file-upload/ng-file-upload.min.js',
        paths.bower + '/ng-file-upload/ng-file-upload-shim.js',
        paths.bower + '/ng-file-upload/ng-file-upload-shim.min.js',
        // ngprogress lite
        paths.bower + '/ngprogress-lite/*.js',
        // showdown
        paths.bower + '/showdown/dist/*'
    ]).pipe(gulp.dest(paths.public + '/vendor/javascript'));

    // stylesheets
    gulp.src([
        // angular toastr
        paths.bower + '/angular-toastr/dist/*.css',
        // bootstrap
        paths.bower + '/bootstrap/dist/css/*',
        // codemirror
        paths.bower + '/codemirror/lib/codemirror.css',
        // fontawesome
        paths.bower + '/font-awesome/css/*',
        // ngprogress lite
        paths.bower + '/ngprogress-lite/*.css'
    ]).pipe(gulp.dest(paths.public + '/vendor/stylesheets'));

    // fonts
    gulp.src([
        // bootstrap
        paths.bower + '/bootstrap/dist/fonts/*',
        // fontawesome
        paths.bower + '/font-awesome/fonts/*'
    ]).pipe(gulp.dest(paths.public + '/vendor/fonts'));
});

/**
 * Build Task: Concatenate and uglifies the main application file
 */
gulp.task('build-app', function() {
    return gulp.src([
            paths.src + '/app.module.js',
            paths.src + '/app.routes.js',
            paths.src + '/app.config.js',
            paths.src + '/app.run.js',
            paths.src + '/app.constant.js'
        ])
        .pipe(concat('app.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets/scripts'));
});

/**
 * Build Task: Concatenate and uglifies controllers from components
 */
gulp.task('build-controllers', function() {
    return gulp.src([
            paths.src + '/components/**/*Controller.js'
        ])
        .pipe(concat('controllers.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets/scripts'));
});

/**
 * Build Task: Concatenate and uglifies directives from components
 * and shared components
 */
gulp.task('build-directives', function() {
    return gulp.src([
        paths.src + '/components/**/*Directive.js',
        paths.src + '/shared/**/*Directive.js'
    ])
        .pipe(concat('directives.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets/scripts'));
});

/**
 * Build Task: Concatenate and uglifies services from components
 * and shared components
 */
gulp.task('build-services', function() {
    return gulp.src([
            paths.src + '/components/**/*Service.js',
            paths.src + '/shared/**/*Service.js'
        ])
        .pipe(concat('services.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets/scripts'));
});

/**
 * Build Task: Concatenate and uglifies providers from components
 * and shared components
 */
gulp.task('build-providers', function() {
    return gulp.src([
        paths.src + '/shared/**/*Provider.js',
    ])
        .pipe(concat('providers.js'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(uglify())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets/scripts'));
});

/**
 * Build Task: Copies and transfer to the public folder all the templates
 * from components and shared
 */
gulp.task('build-templates', function() {
    return gulp.src([
            paths.src + '/components/**/*.html',
            paths.src + '/shared/**/*.html'
        ])
        .pipe(gulp.dest(paths.public + '/assets/templates'));
});

/**
 * Build Task: Concatenates and minifies the css files from global css,
 * components css and shared css
 */
gulp.task('build-stylesheets', function() {
    return gulp.src([
            paths.src + '/*.css',
            paths.src + '/components/**/*.css',
            paths.src + '/shared/**/*.css'
        ])
        .pipe(concat('screen.css'))
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(minifyCss())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest(paths.public + '/assets'));
});

/**
 * Inject Task: Development Environment
 */
gulp.task('inject-development-scripts', function() {
    return gulp.src('./resources/views/journal.blade.php')
        // insert css of the dependencies
        .pipe(inject(gulp.src([
            paths.public + '/vendor/stylesheets/bootstrap.css',
            paths.public + '/vendor/stylesheets/font-awesome.css',
            paths.public + '/vendor/stylesheets/angular-toastr.css',
            paths.public + '/vendor/stylesheets/codemirror.css',
            paths.public + '/vendor/stylesheets/ngprogress-lite.css',
            paths.public + '/assets/screen.css'
        ]), {
            transform : transformAssetPath
        }))
        // insert js dependencies
        .pipe(inject(gulp.src([
            paths.public + '/vendor/javascript/codemirror.js',
            paths.public + '/vendor/javascript/showdown.js',
            paths.public + '/vendor/javascript/angular.js',
            paths.public + '/vendor/javascript/angular-animate.js',
            paths.public + '/vendor/javascript/angular-sanitize.js',
            paths.public + '/vendor/javascript/angular-ui-router.js',
            paths.public + '/vendor/javascript/ui-bootstrap.js',
            paths.public + '/vendor/javascript/ui-bootstrap-tpls.js',
            paths.public + '/vendor/javascript/ui-codemirror.js',
            paths.public + '/vendor/javascript/angular-local-storage.js',
            paths.public + '/vendor/javascript/angular-toastr.js',
            paths.public + '/vendor/javascript/angular-toastr.tpls.js',
            paths.public + '/vendor/javascript/ng-file-upload-shim.js',
            paths.public + '/vendor/javascript/ng-file-upload.js',
            paths.public + '/vendor/javascript/ngprogress-lite.js',
            paths.public + '/assets/scripts/*.js'
        ]), {
            transform : transformAssetPath
        }))
        .pipe(gulp.dest('./resources/views'));
});

/**
 * Inject Task: Production Environment
 */
gulp.task('inject-production-scripts', function() {
    return gulp.src('./resources/views/journal.blade.php')
        // insert css of the dependencies
        .pipe(inject(gulp.src([
            paths.public + '/vendor/stylesheets/bootstrap.min.css',
            paths.public + '/vendor/stylesheets/font-awesome.min.css',
            paths.public + '/vendor/stylesheets/angular-toastr.min.css',
            paths.public + '/vendor/stylesheets/codemirror.css',
            paths.public + '/vendor/stylesheets/ngprogress-lite.css',
            paths.public + '/assets/screen.css'
        ]), {
            transform : transformAssetPath
        }))
        // insert js dependencies
        .pipe(inject(gulp.src([
            paths.public + '/vendor/javascript/codemirror.js',
            paths.public + '/vendor/javascript/showdown.min.js',
            paths.public + '/vendor/javascript/angular.min.js',
            paths.public + '/vendor/javascript/angular-animate.min.js',
            paths.public + '/vendor/javascript/angular-sanitize.min.js',
            paths.public + '/vendor/javascript/angular-ui-router.min.js',
            paths.public + '/vendor/javascript/ui-bootstrap.min.js',
            paths.public + '/vendor/javascript/ui-bootstrap-tpls.min.js',
            paths.public + '/vendor/javascript/ui-codemirror.min.js',
            paths.public + '/vendor/javascript/angular-local-storage.min.js',
            paths.public + '/vendor/javascript/angular-toastr.min.js',
            paths.public + '/vendor/javascript/angular-toastr.tpls.min.js',
            paths.public + '/vendor/javascript/ng-file-upload-shim.min.js',
            paths.public + '/vendor/javascript/ng-file-upload.min.js',
            paths.public + '/vendor/javascript/ngprogress-lite.min.js',
            paths.public + '/assets/scripts/*.js'
        ]), {
            transform : transformAssetPath
        }))
        .pipe(gulp.dest('./resources/views'));
});

/**
 * Watch Task: Watches the given files and do the given tasks according
 * to the file that is being watched.
 */
gulp.task('watch', function() {
    gulp.watch([
            paths.src + '/app.module.js',
            paths.src + '/app.routes.js',
            paths.src + '/app.config.js',
            paths.src + '/app.run.js',
            paths.src + '/app.constant.js'
        ], ['build-app']);

    gulp.watch(paths.src + '/components/**/*Controller.js', ['build-controllers']);

    gulp.watch([
        paths.src + '/components/**/*Directive.js',
        paths.src + '/shared/**/*Directive.js'], ['build-directives']);

    gulp.watch([
        paths.src + '/components/**/*Service.js',
        paths.src + '/shared/**/*Service.js'], ['build-services']);

    gulp.watch(paths.src + '/shared/**/*Provider.js', ['build-providers']);

    gulp.watch([
        paths.src + '/components/**/*.html',
        paths.src + '/shared/**/*.html'], ['build-templates']);

    gulp.watch([
        paths.src + '/*.css',
        paths.src + '/components/**/*.css',
        paths.src + '/shared/**/*.css'], ['build-stylesheets']);
});

/**
 * More likely the build script for development environment
 */
gulp.task('build', function(callback) {
    runSequence(
        'build-bower-files',
        'build-app',
        'build-controllers',
        'build-directives',
        'build-providers',
        'build-services',
        'build-templates',
        'build-stylesheets',
        'inject-development-scripts',
        callback);
});

/**
 * Build script for production environment
 */
gulp.task('build-prod', function(callback) {
    runSequence(
        'build-bower-files',
        'build-app',
        'build-controllers',
        'build-directives',
        'build-providers',
        'build-services',
        'build-templates',
        'build-stylesheets',
        'inject-production-scripts',
        callback);
});

/**
 * Default Task
 */
gulp.task('default', function(callback) {
    runSequence('build', 'watch', callback)
});

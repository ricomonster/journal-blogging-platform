var browserify  = require('browserify'),
    gulp        = require('gulp'),
    notify      = require('gulp-notify'),
    plumber     = require('gulp-plumber'),
    rename      = require('gulp-rename'),
    sass        = require('gulp-sass'),
    util        = require('gulp-util'),
    runSequence = require('run-sequence'),
    source      = require('vinyl-source-stream'),
    buffer      = require('vinyl-buffer');

var production = !!util.env.prod;

/**
 * notify error handler
 */
var onError = function(err) {
    notify.onError({
        title:    "Gulp",
        subtitle: "Failure!",
        message:  "Error: <%= error.message %>",
        sound:    "Beep"
    })(err);

    this.emit('end');
};

gulp.task('browserify', function () {
    var transform = browserify({
        entries : './resources/assets/js/app.js',
        debug : true
    });

    return transform
        .bundle()
        .on('error', onError)
        .pipe(source('app.js'))
        .pipe(buffer())
        .pipe(plumber({
            errorHandler : onError
        }))
        .pipe(gulp.dest('./public/assets/'));
});

gulp.task('build-sass', function () {
    return gulp.src('./resources/assets/sass/app.scss')
        .pipe(sass())
        .on('error', onError)
        .pipe(rename('screen.css'))
        .pipe(gulp.dest('./public/assets/'));
});

gulp.task('get-fonts', function () {
    return gulp.src([
            './node_modules/font-awesome/fonts/*'
        ])
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('watch', function () {
    gulp.watch([
        './resources/assets/js/*.js',
        './resources/assets/js/**/*.js',
        './resources/assets/js/**/**/*.js'], ['browserify']);

    gulp.watch([
        './resources/assets/sass/*.scss',
        './resources/assets/sass/**/.scss'], ['build-sass']);
});

gulp.task('default', function (callback) {
    runSequence('browserify', 'build-sass', 'watch', callback);
});

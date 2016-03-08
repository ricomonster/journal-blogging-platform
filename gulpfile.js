var gulp = require('gulp'),
    concat = require('gulp-concat'),
    minifyCss = require('gulp-minify-css'),
    notify = require('gulp-notify'),
    uglify = require('gulp-uglify'),
    plumber = require('plumber'),
    runSequence = require('run-sequence');

var paths = require('./gulp-lib/paths'),
    bower = require('./gulp-lib/bower'),
    vendor = require('./gulp-lib/vendor');

gulp.task('bower', function () {
    // css
    gulp.src(bower.css)
        .pipe(gulp.dest(paths.base.vendor + '/css'));

    // fonts
    gulp.src(bower.fonts)
        .pipe(gulp.dest(paths.base.vendor + '/fonts'));

    // js
    gulp.src(bower.js)
        .pipe(gulp.dest(paths.base.vendor + '/js'));
});

gulp.task('default', function (callback) {

});

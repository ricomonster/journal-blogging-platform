var gulp            = require('gulp'),
    autoprefixer    = require('gulp-autoprefixer'),
    minifycss       = require('gulp-minify-css'),
    rename          = require('gulp-rename'),
    uglify          = require('gulp-uglify'),
    concat          = require('gulp-concat'),
    plumber         = require('gulp-plumber');

// BOWER ASSETS
gulp.task('build-assets', function() {
 // fonts
 gulp.src([
  // bootstrap
  'bower_components/bootstrap/dist/fonts/*',
  // font awesome
  'bower_components/font-awesome/fonts/*'
 ])
     .pipe(gulp.dest('public/vendor/fonts'));

 // javascript
 gulp.src([
  // bootstrap
  'bower_components/bootstrap/dist/js/bootstrap.js',
  'bower_components/bootstrap/dist/js/bootstrap.min.js',
  // bootstrap notify
  'bower_components/bootstrap-notify/js/bootstrap-notify.js',
  // codemirror
  'bower_components/codemirror/lib/codemirror.js',
  // jquery
  'bower_components/jquery/dist/jquery.js',
  'bower_components/jquery/dist/jquery.min.js',
  'bower_components/jquery/dist/jquery.min.map',
  // jquery form
  'bower_components/jquery-form/jquery.form.js',
  // jquery cookie
  'bower_components/jquery.cookie/jquery.cookie.js',
  // showdown
  'bower_components/showdown/src/showdown.js'
 ])
     .pipe(gulp.dest('public/vendor/javascript'));

 // stylesheets
 gulp.src([
  // bootstrap
  'bower_components/bootstrap/dist/css/bootstrap.css',
  'bower_components/bootstrap/dist/css/bootstrap.min.css',
  'bower_components/bootstrap/dist/css/bootstrap.css.map',
  // bootstrap notify
  'bower_components/bootstrap-notify/css/bootstrap-notify.css',
  // codemirror
  'bower_components/codemirror/lib/codemirror.css',
  // font awesome
  'bower_components/font-awesome/css/font-awesome.css',
  'bower_components/font-awesome/css/font-awesome.min.css'
 ])
 .pipe(gulp.dest('public/vendor/stylesheets'));
});

// build javascript files
gulp.task('build-javascript', function() {
 return gulp.src('resources/assets/javascript/*.js')
     .pipe(uglify())
     .pipe(plumber({
      handleError: function (err) {
       console.log(err);
       this.emit('end');
      }
     }))
     .pipe(rename({ extname : '.min.js' }))
     .pipe(gulp.dest('public/javascript'));
});

// build stylesheet files
gulp.task('build-stylesheets', function() {
 return gulp.src('resources/assets/stylesheets/*.css')
     .pipe(autoprefixer([
      'Android 2.3',
      'Android >= 4',
      'Chrome >= 20',
      'Firefox >= 24',
      'Explorer >= 8',
      'iOS >= 6',
      'Opera >= 12',
      'Safari >= 6']))
     .pipe(plumber({
      handleError: function (err) {
       console.log(err);
       this.emit('end');
      }
     }))
     .pipe(concat('screen.css'))
     .pipe(plumber({
      handleError: function (err) {
       console.log(err);
       this.emit('end');
      }
     }))
     .pipe(minifycss())
     .pipe(plumber({
      handleError: function (err) {
       console.log(err);
       this.emit('end');
      }
     }))
     .pipe(gulp.dest('public/stylesheets'));
});

// build task
gulp.task('build', function() {
 gulp.run(['build-assets', 'build-javascript', 'build-stylesheets']);
});

gulp.task('default', function() {
 gulp.watch('resources/assets/javascript/*.js', function() {
  gulp.run('build-javascript');
 });

 gulp.watch('resources/assets/stylesheets/*.css', function() {
  gulp.run('build-stylesheets');
 });
});

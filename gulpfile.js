var gulp        = require('gulp'),
    concat      = require('gulp-concat'),
    minifyCss   = require('gulp-minify-css'),
    plumber     = require('gulp-plumber'),
    notify      = require('gulp-notify'),
    uglify      = require('gulp-uglify'),
    inject      = require('gulp-inject'),
    runSequence = require('run-sequence');
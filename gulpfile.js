var gulp = require('gulp');
var minify = require('gulp-minify');
var strip = require('gulp-strip-comments');
var cleanCSS = require('gulp-clean-css');
var concat = require('gulp-concat');

gulp.task('copy-images', function () {
    return gulp.src('node_modules/leaflet/dist/images/*')
        .pipe(gulp.dest('public/img/leaflet/'));
});

gulp.task('compress-css', function () {
    return gulp.src([
            'node_modules/leaflet/dist/leaflet.css',
            'node_modules/bootstrap/dist/css/bootstrap.css',
        ])
        .pipe(cleanCSS())
        .pipe(concat('fahrverbot.min.css'))
        .pipe(gulp.dest('public/css/'))
});

gulp.task('compress-js', function () {
    return gulp.src([
            'node_modules/jquery/dist/jquery.slim.js',
            'node_modules/popper.js/dist/popper.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/leaflet/dist/leaflet.js',
        ])
        .pipe(minify())
        //.pipe(concat('fahrverbot.min.js'))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('build', ['copy-images', 'compress-js', 'compress-css'], function () {});

gulp.task('default', ['build']);

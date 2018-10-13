var gulp = require('gulp');
var minify = require('gulp-minify');
var strip = require('gulp-strip-comments');
var cleanCSS = require('gulp-clean-css');
var concat = require('gulp-concat');

gulp.task('copy-images', function () {
    return gulp.src('node_modules/leaflet/dist/images/*');
});

gulp.task('compress-css', function () {
    return gulp.src('node_modules/leaflet/dist/leaflet.css')
        .pipe(cleanCSS())
        .pipe(concat('leaflet.min.css'))
        .pipe(gulp.dest('public/css/leaflet'))
});

gulp.task('compress-js', function () {
    return gulp.src('node_modules/leaflet/dist/leaflet.js')
        .pipe(minify())
        .pipe(gulp.dest('public/js/leaflet'))
});

gulp.task('build', ['copy-images', 'compress-js', 'compress-css'], function () {});

gulp.task('default', ['build']);

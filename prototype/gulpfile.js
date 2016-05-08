// Reference Gulp
var gulp = require('gulp');
// Utility Plugins
var gutil = require('gulp-util');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var rename = require('gulp-rename');
var nodemailer = require('nodemailer');
var fs = require('fs');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;
var html_strip = require('htmlstrip-native');
var marked = require('gulp-marked');
var inject = require('gulp-inject');
var clean = require('gulp-rimraf');

// CSS Plugins
var minifycss = require('gulp-minify-css');
var inlinesource = require('gulp-inline-source');
var inlinecss = require('gulp-inline-css');
// Optimization Plugins
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');

// Move Task - copies over files necessary for dist
gulp.task('move', function() {
    gulp.src(['./src/*.html'])
        .pipe(gulp.dest('./dist'))
        .pipe(reload({stream: true}));
});

gulp.task('js', function() {
    gulp.src(['./src/scripts/**/*.js'])
        .pipe(gulp.dest('./dist/scripts/'))
        .pipe(reload({stream: true}));
});

// SCSS Task - compiles scss with prefixes and minification to dist/styles
gulp.task('styles', function() {
    gulp.src(['./src/styles/**/*.scss'])
        .pipe(sass())
        .on('error', gutil.log)
        .pipe(autoprefixer('last 2 versions'))
        .pipe(minifycss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./dist/styles'))
        .on('error', gutil.log)
        .pipe(reload({stream: true}));
});

gulp.task('images', function() {
    return gulp.src('./src/images/**/*')
        .pipe(imagemin({
            progressive: true,
            interlaced: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./dist/images'));
});

gulp.task('serve', function() {
    browserSync.init(null, {
        server: {
            baseDir: './dist'
        }
    });
    gulp.watch('./src/**/*.scss', ['build']);
    gulp.watch('./src/**/*.html', ['build']);
    gulp.watch('./src/**/*.js', ['build']);
});

gulp.task('default', ['build', 'serve']);
gulp.task('build', ['styles', 'js', 'move', 'images']);


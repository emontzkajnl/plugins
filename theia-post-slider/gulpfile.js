var gulp = require('gulp');
var compass = require('gulp-compass');
var sourcemaps = require('gulp-sourcemaps');
var gutil = require('gulp-util');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

gulp.task('style', function () {
    gulp.src('assets/scss/**/*.scss')
        .pipe(compass({
            config_file: './assets/scss/config.rb',
            css: './dist/css',
            sass: './assets/scss/sass'
        }))
        .on('error', gutil.log)
        .pipe(gulp.dest('dist/css'));
});

gulp.task('js', function () {
    gulp.src(['node_modules/slim-select/dist/**/*'])
        .pipe(gulp.dest('dist/slim-select'));

    return gulp.src([
        'assets/js/async.js',
        'assets/js/tps-tinymce-customcodes.js',
        'node_modules/zingtouch/dist/zingtouch.js',
        'node_modules/iframe-resizer/js/iframeResizer.js'
    ])
        .pipe(sourcemaps.init())
        .on('error', gutil.log)
        .pipe(gulp.dest('dist/js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(sourcemaps.write('sourcemaps'))
        .pipe(gulp.dest('dist/js'));
});

gulp.task('default', ['style', 'js']);

gulp.task('watch', function () {
    gulp.watch('assets/scss/**/*.scss', ['style']);
    gulp.watch('assets/js/**/*.js', ['js']);
});

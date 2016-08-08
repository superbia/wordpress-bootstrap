var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var jshint = require('gulp-jshint');
var browserSync = require('browser-sync').create();
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var gutil = require('gulp-util');

var paths = {
  css: {
    source: 'sass/style.scss',
    all: 'sass/**/*.scss'
  },
  js: {
    source: 'js/functions.js'
  }
};

var appConfig = {
  autoprefixer: { browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1'] },
  sass: { outputStyle: 'compressed' }
};

var jsHintError = function (err) {
  gutil.beep();
  this.emit('end');
};

gulp.task('sass', function() {
  	gulp.src(paths.css.source)
      .pipe(sourcemaps.init())
      .pipe(sass(appConfig.sass).on('error', sass.logError))
      .pipe(autoprefixer(appConfig.autoprefixer))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest('./'))
      .pipe(browserSync.stream({match: '**/*.css'}))
      .pipe(notify({ message: 'Sass compiled' }));
});

gulp.task('jshint', function() {
  return gulp.src(paths.js.source)
    .pipe(plumber({ errorHandler: jsHintError }))
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(notify(function (file) {
      if (file.jshint.success) {
        return 'JSHint passed! 🤓';
      }
 
      return 'JSHint failed 😫💩';
    }))
    .pipe(jshint.reporter('fail'));
});

gulp.task('browsersync', function() {
  browserSync.init({
    proxy: 'http://wp-bootstrap.dev',
    open: false
  });
});


gulp.task('watch', ['browsersync'], function() {
  gulp.watch(paths.css.all, ['sass']);
  gulp.watch(paths.js.source, ['jshint']).on('change', browserSync.reload);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
});

gulp.task('default', ['watch']);
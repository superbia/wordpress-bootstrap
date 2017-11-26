const gulp              = require( 'gulp' );
const browserSync       = require( 'browser-sync' ).create();
const sourcemaps        = require( 'gulp-sourcemaps' );
const postcss           = require( 'gulp-postcss' );
const autoprefixer      = require( 'autoprefixer' );
const sass              = require( 'gulp-sass' );
const sassLint          = require( 'gulp-sass-lint' );
const jshint            = require( 'gulp-jshint' );
const imagemin          = require( 'gulp-imagemin' );
const modernizr         = require( 'gulp-modernizr' );
const notify            = require( 'gulp-notify' );
const plumber           = require( 'gulp-plumber' );
const uglify            = require( 'gulp-uglify' );

// Are we in production?
const isProduction = process.env.NODE_ENV === 'production';

// Config for all gulp tasks.
const config = {
	sass: {
		outputStyle: 'compressed',
		includePaths: [ 'node_modules/breakpoint-sass/stylesheets' ]
	},
	imagemin: [
		imagemin.jpegtran( { progressive: true } ),
		imagemin.optipng( { optimizationLevel: 5 } ),
		imagemin.svgo( { plugins: [ { removeViewBox: true } ] } )
	],
	postcss: [
		autoprefixer( { browsers: ['last 3 versions'] } ),
	],
};

// Compile and minify styles.
gulp.task( 'styles', ['lint-sass'], () => {
	gulp.src( './sass/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( sass( config.sass ).on( 'error', sass.logError ) )
		.pipe( postcss( config.postcss ) )
		.pipe( sourcemaps.write('.') )
		.pipe( gulp.dest('./') )
		.pipe( browserSync.stream( { stream: true } ) )
		.pipe( notify({
			title: 'SASS',
			message: '🦄 🍾 🎉 🍩',
			onLast: true,
			sound: false
	}));
});

// Sass linting.
gulp.task( 'lint-sass', () => {
	return gulp.src( [ './sass/**/*.scss', '!./sass/_normalize.scss', '!./sass/editor.scss', '!./sass/ui_patterns/_accessibility.scss' ] )
		.pipe( sassLint( { configFile: '.sass-lint.yml' } ) )
		.pipe( sassLint.format() )
		.pipe( sassLint.failOnError() )
		.on( 'error', notify.onError( {
			title: 'SASS',
			message: '💩 💣 🚽 🐍',
			sound: 'Frog'
		}));
});

// JS linting.
gulp.task( 'jshint', () => {
	return gulp.src( 'js/functions.js' )
		.pipe( plumber() )
		.pipe( jshint() )
		.pipe( jshint.reporter( 'jshint-stylish' ) )
		.pipe( jshint.reporter( 'fail' ) )
		.on( 'error', notify.onError( {
			title: 'JS',
			message: '💩 💣 🚽 🐍',
			sound: 'Frog'
		}));
});

// Automate custom modernizr build.
gulp.task( 'modernizr', () => {
	gulp.src( [ './sass/**/*.scss', './js/*.js' ] )
	.pipe( modernizr( {
		options: [ 'setClasses' ]
	} ) )
	.pipe( uglify() )
	.pipe( gulp.dest( './js' ) )
});

// Optimise images.
gulp.task( 'images', () => {
	gulp.src( './images/*' )
		.pipe( imagemin( config.imagemin, { verbose: true } ) )
		.pipe( gulp.dest( './images' ) );
});

// Browsersync
gulp.task( 'browsersync', () => {
	browserSync.init( {
		proxy: 'http://wp-bootstrap.dev',
		open: false,
		notify: false
	});
});

// Watch
gulp.task( 'watch', [ 'browsersync' ], () => {
	gulp.watch( 'sass/**/*.scss', [ 'styles' ] );
	gulp.watch( 'js/*.js', ['jshint'] ).on( 'change', browserSync.reload );
	gulp.watch( '**/*.php' ).on( 'change', browserSync.reload );
});

// Default
gulp.task( 'default', [ 'styles', 'jshint', 'modernizr', 'images' ] );

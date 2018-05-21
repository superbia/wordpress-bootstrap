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
	gulp.src( './assets/src/styles/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( sass( config.sass ).on( 'error', sass.logError ) )
		.pipe( postcss( config.postcss ) )
		.pipe( sourcemaps.write('.') )
		.pipe( gulp.dest('./assets/dist/styles') )
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
	return gulp.src( [ './assets/src/styles/**/*.scss', '!./assets/src/styles/base/_normalize.scss' ] )
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
	return gulp.src( 'assets/src/scripts/functions.js' )
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
	gulp.src( [ './assets/src/styles/**/*.scss', './assets/src/scripts/*.js' ] )
	.pipe( modernizr( {
		options: [ 'setClasses' ]
	} ) )
	.pipe( uglify() )
	.pipe( gulp.dest( './assets/dist/scripts' ) )
});

// Optimise images.
gulp.task( 'images', () => {
	gulp.src( './assets/src/images/*' )
		.pipe( imagemin( config.imagemin, { verbose: true } ) )
		.pipe( gulp.dest( './assets/dist/images' ) );
});

// Browsersync
gulp.task( 'browsersync', () => {
	browserSync.init( {
		proxy: 'https://wp-bootstrap.dev',
		open: false,
		notify: false
	});
});

// Watch
gulp.task( 'watch', [ 'browsersync' ], () => {
	gulp.watch( 'assets/src/styles/**/*.scss', [ 'styles' ] );
	gulp.watch( [ 'assets/src/scripts/**/*.js','!assets/src/scripts/admin/*.js' ], [ 'scripts-watch' ] );
	gulp.watch( '**/*.php' ).on( 'change', browserSync.reload );
});

// Default
gulp.task( 'default', [ 'styles', 'jshint', 'modernizr', 'images' ] );

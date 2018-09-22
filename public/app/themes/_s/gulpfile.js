const autoprefixer      = require( 'autoprefixer' );
const browserSync       = require( 'browser-sync' );
const del               = require( 'del' );
const ESLint            = require( 'gulp-eslint' );
const gulp              = require( 'gulp' );
const imagemin          = require( 'gulp-imagemin' );
const newer             = require( 'gulp-newer' );
const notify            = require( 'gulp-notify' );
const os                = require( 'os' );
const postcss           = require( 'gulp-postcss' );
const rollup            = require( 'rollup' );
const rollupBabel       = require( 'rollup-plugin-babel' );
const rollupCommonjs    = require( 'rollup-plugin-commonjs' );
const rollupNodeResolve = require( 'rollup-plugin-node-resolve' );
const rollupUglify      = require( 'rollup-plugin-uglify' );
const sass              = require( 'gulp-sass' );
const sassLint          = require( 'gulp-sass-lint' );
const sourcemaps        = require( 'gulp-sourcemaps' );
const gulpSvgSprite     = require( 'gulp-svg-sprite' );
const server            = browserSync.create();
const isProduction      = process.env.NODE_ENV === 'production';

const basePaths = {
	src: './assets/src/',
	dist: './assets/dist/',
}

const paths = {
	styles: {
		src: `${basePaths.src}styles/**/*.scss`,
		dest: `${basePaths.dist}styles/`,
	},
	scripts: {
		src: `${basePaths.src}scripts/**/*.js`,
		entry: `${basePaths.src}scripts/theme.js`,
		exit: `${basePaths.dist}scripts/theme.bundle.js`,
	},
	images: {
		src:  `${basePaths.src}images/**/*.{jpg,png,svg}`,
		dest: `${basePaths.dist}images`,
	},
	svgsprite: {
		src: `${basePaths.src}icons/**/*.svg`,
		dest: `${basePaths.dist}images`,
	},
	certs: os.homedir() + '/Sites/config/certs/localhost/',
	templates: './**/*.php',
};

const config = {
	sass: {
		outputStyle: 'compressed',
		includePaths: [
			'./node_modules/breakpoint-sass/stylesheets/',
			'./node_modules/normalize.css/',
		]
	},
	postcss: [
		require( 'autoprefixer' ),
	],
	rollup: {
		bundle: {
			input: paths.scripts.entry,
			plugins: [
				rollupNodeResolve( {
					jsnext: true,
					main: true,
					browser: true,
				} ),
				rollupCommonjs(),
				rollupBabel( {
					exclude: 'node_modules/**',
				} ),
			]
		},
		write: {
			file: paths.scripts.exit,
			format: 'iife',
			globals: {
				jquery: 'jQuery'
			},
			sourcemap: ( isProduction ) ? true : 'inline',
		}
	},
	imagemin: [
		imagemin.jpegtran( { progressive: true } ),
		imagemin.optipng( { optimizationLevel: 5 } ),
		imagemin.svgo( { plugins: [ { removeViewBox: true } ] } )
	],
	svgsprite: {
		mode: {
			symbol: {
				dest: '.',
				sprite: 'sprite.symbol.svg',
			},
		},
	},
	browsersync: {
		proxy: 'https://dev.wp-bootstrap',
		open: false,
		notify: false,
		https: {
			key: `${paths.certs}key.pem`,
			cert: `${paths.certs}cert.pem`,
		},
	}
};

// Update config for production.
if ( isProduction ) {
	config.rollup.bundle.plugins.push( rollupUglify.uglify() );
}

const clean = () => del(
	[ basePaths.dist ]
);

function styles() {
	return gulp.src( paths.styles.src )
		.pipe( sourcemaps.init() )
		.pipe( sass( config.sass ).on( 'error', sass.logError ) )
		.pipe( postcss( config.postcss ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( paths.styles.dest ) )
		.pipe( server.reload( { stream: true } ) );
}

function lintSass() {
	return gulp.src( paths.styles.src )
		.pipe( sassLint( { configFile: '.sass-lint.yml' } ) )
		.pipe( sassLint.format() )
		.pipe( sassLint.failOnError() )
		.on( 'error', notify.onError( {
			title: 'SASS',
			message: '💩 💣 🚽 🐍',
			sound: 'Frog'
		} ) );
}

async function scripts() {
	const bundle = await rollup.rollup( config.rollup.bundle );
	await bundle.write( config.rollup.write );
}

function lintScripts() {
	return gulp.src( paths.scripts.src )
		.pipe( ESLint() )
		.pipe( ESLint.format() )
		.pipe( ESLint.failAfterError() )
		.on( 'error', notify.onError( {
			title: 'ESLint',
			message: '💩 💣 🚽 🐍',
			sound: 'Frog'
		} ) );
}

function images() {
	return gulp.src( paths.images.src )
		.pipe( newer( paths.images.dest ) )
		.pipe( imagemin( config.imagemin, { verbose: true } ) )
		.pipe( gulp.dest( paths.images.dest ) );
}

function svgSprite() {
	return gulp.src( paths.svgsprite.src )
	  .pipe( gulpSvgSprite( config.svgsprite ) )
	  .pipe( gulp.dest( paths.svgsprite.dest ) );
}

const buildStyles  = gulp.series( lintSass, styles );
const buildScripts = gulp.series( lintScripts, scripts );
const build        = gulp.series( clean, gulp.parallel( buildStyles, buildScripts, images, svgSprite ) );

function reload( done ) {
	server.reload();
	done();
}

function serve() {
	server.init( config.browsersync );
	gulp.watch( paths.styles.src, gulp.series( buildStyles ) );
	gulp.watch( paths.scripts.src, gulp.series( buildScripts, reload ) );
	gulp.watch( paths.images.src, gulp.series( images, reload ) );
	gulp.watch( paths.svgsprite.src, gulp.series( svgSprite, reload ) );
	gulp.watch( paths.templates, gulp.series( reload ) );
}

gulp.task( 'styles', buildStyles );
gulp.task( 'scripts', buildScripts );
gulp.task( 'images', images );
gulp.task( 'svgsprite', svgSprite );
gulp.task( 'watch', serve );
gulp.task( 'build', build );
gulp.task( 'default', build );

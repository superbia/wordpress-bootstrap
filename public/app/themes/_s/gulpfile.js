const autoprefixer      = require( 'autoprefixer' );
const browserSync       = require( 'browser-sync' );
const cssnano           = require( 'cssnano' );
const del               = require( 'del' );
const ESLint            = require( 'gulp-eslint' );
const gulp              = require( 'gulp' );
const gulpSvgSprite     = require( 'gulp-svg-sprite' );
const imagemin          = require( 'gulp-imagemin' );
const newer             = require( 'gulp-newer' );
const notify            = require( 'gulp-notify' );
const os                = require( 'os' );
const postcss           = require( 'gulp-postcss' );
const postcssImport     = require( 'postcss-import' );
const responsive        = require( 'gulp-responsive' );
const rev               = require( 'gulp-rev' );
const revCssUrl         = require( 'gulp-rev-css-url' );
const rollup            = require( 'rollup' );
const rollupBabel       = require( 'rollup-plugin-babel' );
const rollupCommonjs    = require( 'rollup-plugin-commonjs' );
const rollupNodeResolve = require( 'rollup-plugin-node-resolve' );
const rollupUglify      = require( 'rollup-plugin-uglify' );
const sass              = require( 'gulp-sass' );
const sassLint          = require( 'gulp-sass-lint' );
const sourcemaps        = require( 'gulp-sourcemaps' );
const workboxBuild      = require( 'workbox-build' );
const server            = browserSync.create();
const isProduction      = process.env.NODE_ENV === 'production';

const basePaths = {
	src: './assets/src/',
	dest: './assets/dist/',
}

const paths = {
	styles: {
		src: `${basePaths.src}styles/**/*.scss`,
		dest: `${basePaths.dest}styles/`,
	},
	scripts: {
		src: `${basePaths.src}scripts/**/*.js`,
		dest: `${basePaths.src}scripts/`,
		entry: `${basePaths.src}scripts/theme.js`,
		exit: `${basePaths.dest}scripts/theme.bundle.js`,
	},
	images: {
		src:  `${basePaths.src}images/**/*.{jpg,gif,png,svg}`,
		dest: `${basePaths.dest}images`,
	},
	svgsprite: {
		src: `${basePaths.src}icons/**/*.svg`,
		dest: `${basePaths.dest}images`,
	},
	appicon: {
		src: `${basePaths.src}pwa/app-icon.png`,
		dest: `${basePaths.dest}images`,
	},
	fonts: {
		src: `${basePaths.src}fonts/*.{woff,woff2}`,
		dest: `${basePaths.dest}fonts`,
	},
	workbox: {
		src: [
			'**\/*.{json,js,css,woff2,png,svg}',
		],
		ignore: [
			'styles/admin.css',
			'styles/editor.css',
		],
		dest: './../../../sw.js',
	},
	certs: os.homedir() + '/Sites/config/certs/localhost/',
	templates: './**/*.php',
	// String interpolation or concatenation doesn't work here.
	rev: [
		'./assets/dist/styles/*.css',
		'./assets/dist/scripts/*.js',
		'./assets/dist/images/*.{png,gif,jpg,svg}',
		'./assets/dist/fonts/*.{woff,woff2}',
	]
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
		postcssImport,
		autoprefixer,
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
	appicon: {
		'app-icon.png': [
			{
				width: 512,
				rename: 'app-icon-512.png',
			},
			{
				width: 192,
				rename: 'app-icon-192.png',
			},
			{
				width: 180,
				rename: 'apple-touch-icon.png',
			},
		],
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
	config.postcss.push( cssnano );
	config.rollup.bundle.plugins.push( rollupUglify.uglify() );
}

const clean = () => del(
	[ basePaths.dest ]
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

function appIcons() {
	return gulp.src(
		paths.appicon.src, {
			allowEmpty: true,
		}
	)
	.pipe( newer( paths.images.dest ) )
	.pipe( responsive( config.appicon ) )
	.pipe( imagemin( config.imagemin, { verbose: true } ) )
	.pipe( gulp.dest( paths.appicon.dest ) );
}

function fonts() {
	return gulp.src( paths.fonts.src )
		.pipe( gulp.dest( paths.fonts.dest ) );
}

function serviceWorker() {
	return workboxBuild.generateSW( {
		globDirectory: basePaths.dest,
		globPatterns: paths.workbox.src,
		globIgnores: paths.workbox.ignore,
		swDest: paths.workbox.dest,
	} );
}

function revisionAssets() {
	return gulp.src( paths.rev, {
			allowEmpty: true,
			base: basePaths.dest
		} )
		.pipe( rev() )
		.pipe( revCssUrl() )
		.pipe( gulp.dest( basePaths.dest ) )
		.pipe( rev.manifest() )
		.pipe( gulp.dest( basePaths.dest ) )
}

const buildStyles  = gulp.series( lintSass, styles );
const buildScripts = gulp.series( lintScripts, scripts );
const build        = gulp.series( clean, gulp.parallel( buildStyles, buildScripts, images, svgSprite, appIcons, fonts ), serviceWorker, revisionAssets );

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
gulp.task( 'appIcons', appIcons );
gulp.task( 'watch', serve );
gulp.task( 'build', build );
gulp.task( 'default', build );

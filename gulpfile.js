const gulp = require('gulp');
const notify = require("gulp-notify");
const compass = require("gulp-compass");
const autoprefixer = require('gulp-autoprefixer');
const cleancss = require('gulp-clean-css');

/**
 * Primary Compass Configurations
 * Note that all settings besides css_dir and sass_dir can just go in config.rb.
 */
var compassConfig = {
	config_file: 'config.rb',
	css: 'assets/css/', // Must match css_dir value in config.rb.
	sass: 'assets/sass/', // Must match sass_dir value in config.rb
	sourcemap: false
};

/**
 * Files and directories we reference in our tasks below. The "toCompile" properties
 * also generally contain patterns of files that are watched to trigger compilation.
 */
var files = {
	sass: ['assets/sass/*.scss', 'assets/sass/*/*.scss']
};

// compile sass
gulp.task('compassBuild', function() {
	return gulp.src(files.sass)
		.pipe(compass(compassConfig))
		.pipe(autoprefixer({
			browsers: ['last 10 versions']
		}))
		.pipe(gulp.dest('./' + compassConfig.css))
		.pipe(notify('Sass compiled successfully!'));
});

// minify css
gulp.task('minifyCss', () => {
	return gulp.src('assets/css/*.css')
		.pipe(cleancss())
		.pipe(gulp.dest('./' + compassConfig.css))
		.pipe(notify('CSS minified successfully!'));
});

// Default task (one-time build).
gulp.task('default', ['compassBuild']);

// Gulp Watcher if there is any change on SCSS file.
gulp.watch(files.sass, ['default']);
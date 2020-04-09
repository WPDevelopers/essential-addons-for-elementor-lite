const gulp = require("gulp");
const notify = require("gulp-notify");
const compass = require("gulp-compass");
const autoprefixer = require("gulp-autoprefixer");
const cleancss = require("gulp-clean-css");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const runsequence = require('run-sequence');

/**
 * Primary Compass Configurations
 * Note that all settings besides css_dir and sass_dir can just go in config.rb.
 */
var compassConfig = {
    config_file: "config.rb",
    css: "assets/front-end/css/", // Must match css_dir value in config.rb.
    sass: "assets/front-end/sass/", // Must match sass_dir value in config.rb
    js: "assets/front-end/js",
    sourcemap: false
};

/**
 * Files and directories we reference in our tasks below. The "toCompile" properties
 * also generally contain patterns of files that are watched to trigger compilation.
 */
var files = {
    sass: ["assets/front-end/sass/*.scss", "assets/front-end/sass/**/*.scss"],
    css: [
        "assets/front-end/css/*.css",
        "!assets/front-end/css/*.min.css",
        "assets/front-end/css/**/*.css",
        "!assets/front-end/css/**/*.min.css",
        "assets/front-end/css/**/**/*.css",
        "!assets/front-end/css/**/**/*.min.css"
    ],
    minCSS: [
        "assets/front-end/css/*.min.css",
        "assets/front-end/css/**/*.min.css",
        "assets/front-end/css/**/**/*.min.css"
    ],
    js: [
        "assets/front-end/js/vendor/!(load-more)**/*.js",
        "!assets/front-end/js/vendor/!(load-more)**/*.min.js",
        "assets/front-end/js/vendor/load-more/*.js",
        "!assets/front-end/js/vendor/load-more/*.min.js",
        "assets/front-end/js/!(vendor)**/*.js",
        "!assets/front-end/js/!(vendor)**/*.min.js"
    ],
    minJS: [
        "assets/front-end/js/vendor/!(load-more)**/*.min.js",
        "assets/front-end/js/vendor/load-more/*.min.js",
        "assets/front-end/js/!(vendor)**/*.min.js"
    ]
};

// compile sass
gulp.task("compileSCSS", function() {
    return gulp
        .src(files.sass)
        .pipe(compass(compassConfig))
        .pipe(
            autoprefixer({
                Browserslist: ["last 10 versions"]
            })
        )
        .pipe(gulp.dest("./" + compassConfig.css));
});

// minify csss
gulp.task("minifyCSS", function() {
    return gulp
        .src(files.css)
        .pipe(cleancss())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        );
});

// combine css
gulp.task("combineCSS", function() {
    return gulp
        .src(files.css)
        .pipe(concat("eael.css"))
        .pipe(gulp.dest("./" + compassConfig.css));
});

gulp.task("combineMinCSS", function() {
    return gulp
        .src(files.minCSS)
        .pipe(concat("eael.min.css"))
        .pipe(gulp.dest("./" + compassConfig.css));
});

// minify js
gulp.task("minifyJS", function() {
    return gulp
        .src(files.js)
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        )
});

// combine js
gulp.task("combineJS", function() {
    return gulp
        .src(files.js)
        .pipe(concat("eael.js"))
        .pipe(gulp.dest("./" + compassConfig.js));
});

gulp.task("combineMinJS", function() {
    return gulp
        .src(files.minJS)
        .pipe(concat("eael.min.js"))
        .pipe(gulp.dest("./" + compassConfig.js));
});

gulp.task('buildcss', function(callback) {
    runsequence('compileSCSS',
        'minifyCSS','combineCSS','combineMinCSS',
        callback);
});

gulp.task('buildjs', function(callback) {
    runsequence('minifyJS',
        'combineJS','combineMinJS',
        callback);
});

const gulp = require("gulp");
const notify = require("gulp-notify");
const compass = require("gulp-compass");
const autoprefixer = require("gulp-autoprefixer");
const cleancss = require("gulp-clean-css");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");

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
        "assets/front-end/css/**/*.css",
        "assets/front-end/css/**/**/*.css",
        "!assets/front-end/css/*.min.css",
        "!assets/front-end/css/**/*.min.css",
        "!assets/front-end/css/**/**/*.min.css"
    ],
    js: [
        "assets/front-end/js/vendor/!(load-more)**/*.js",
        "assets/front-end/js/vendor/load-more/*.js",
        "assets/front-end/js/!(vendor)**/*.js",
        "!assets/front-end/js/vendor/!(load-more)**/*.min.js",
        "!assets/front-end/js/vendor/load-more/*.min.js",
        "!assets/front-end/js/!(vendor)**/*.min.js"
    ]
};

// compile sass
gulp.task("compileSCSS", function() {
    return gulp
        .src(files.sass)
        .pipe(compass(compassConfig))
        .pipe(
            autoprefixer({
                browsers: ["last 10 versions"]
            })
        )
        .pipe(gulp.dest("./" + compassConfig.css));
});

// minify & combine css
gulp.task("minifyCombineCSS", function() {
    return gulp
        .src(files.css)
        .pipe(concat("eael.css"))
        .pipe(gulp.dest("./" + compassConfig.css))
        .pipe(cleancss())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        )
        .pipe(concat("eael.min.css"))
        .pipe(gulp.dest("./" + compassConfig.css));
});

// minify & combine js
gulp.task("minifyCombineJS", function() {
    return gulp
        .src(files.js)
        .pipe(concat("eael.js"))
        .pipe(gulp.dest("./" + compassConfig.js))
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        )
        .pipe(concat("eael.min.js"))
        .pipe(gulp.dest("./" + compassConfig.js));
});

// Default task (one-time build).
gulp.task("default", ["compileSCSS", "minifyCombineCSS", "minifyCombineJS"]);

// Gulp Watcher if there is any change on SCSS file.
gulp.watch([files.sass, files.js], ["default"]);

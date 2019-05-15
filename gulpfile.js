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
    sass: ["assets/front-end/sass/*.scss", "assets/front-end/sass/*/*.scss"],
    js: [
        "assets/front-end/js/vendor/!(load-more)**/*.js",
        "assets/front-end/js/vendor/load-more/*.js",
        "assets/front-end/js/!(vendor)**/*.js"
    ],
    css: [
        "assets/front-end/css/*.css",
        "assets/front-end/css/**/*.css",
        "assets/front-end/css/**/**/*.css"
    ]
};

// compile sass
gulp.task("compassBuild", function() {
    return gulp
        .src(files.sass)
        .pipe(compass(compassConfig))
        .pipe(
            autoprefixer({
                browsers: ["last 10 versions"]
            })
        )
        .pipe(gulp.dest("./" + compassConfig.css));
    // .pipe(notify('Sass compiled successfully!'));
});

//concat and minify js
gulp.task("minifyJS", () => {
    return gulp
        .src(files.js)
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        )
        .pipe(concat("eael.min.js"))
        .pipe(gulp.dest("./" + compassConfig.js))
        .pipe(notify("JS minified successfully!"));
});

//concat and minify js
gulp.task("compileJS", () => {
    return gulp
        .src(files.js)
        .pipe(concat("eael-pro.js"))
        .pipe(gulp.dest("./" + compassConfig.js))
        .pipe(notify("JS minified successfully!"));
});

// minify css
gulp.task("minifyCss", () => {
    return gulp
        .src(files.css)
        .pipe(cleancss())
        .pipe(rename({ suffix: ".min" }))
        .pipe(
            gulp.dest(function(file) {
                return file.base;
            })
        )
        .pipe(gulp.dest("./" + compassConfig.css))
        .pipe(notify("CSS minified successfully!"));
});

// Default task (one-time build).
gulp.task("default", ["compassBuild"]);

// Gulp Watcher if there is any change on SCSS file.
gulp.watch([files.sass, files.js], ["default"]);

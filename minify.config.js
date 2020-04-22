const args = process.argv.slice(2);
const concat = require("concat-files");
const cssEntry = (min = true) => {
	let paths = [];
	let files = [
		"general.min.css",
		"load-more.min.css",
		"post-grid.min.css",
		"product-grid.min.css",
		"post-timeline.min.css",
		"fancy-text.min.css",
		"creative-btn.min.css",
		"count-down.min.css",
		"team-members.min.css",
		"testimonials.min.css",
		"info-box.min.css",
		"flip-box.min.css",
		"call-to-action.min.css",
		"dual-header.min.css",
		"tooltipster.bundle.min.css",
		"price-table.min.css",
		"twitter-feed.min.css",
		"facebook-feed.min.css",
		"advanced-data-table.min.css",
		"data-table.min.css",
		"magnific-popup.min.css",
		"filterable-gallery.min.css",
		"image-accordion.min.css",
		"content-ticker.min.css",
		"tooltip.min.css",
		"advanced-accordion.min.css",
		"advanced-tabs.min.css",
		"progress-bar.min.css",
		"feature-list.min.css",
		"contact-form-7.min.css",
		"weforms.min.css",
		"ninja-form.min.css",
		"formstack.min.css",
		"gravity-form.min.css",
		"caldera-form.min.css",
		"wpforms.min.css",
		"fluentform.min.css",
		"sticky-video-plyr.min.css",
		"sticky-video.min.css",
		"calendar-main.min.css",
		"daygrid.min.css",
		"timegrid.min.css",
		"listgrid.min.css",
		"event-calendar.min.css",
		"reading-progress.min.css",
		"table-of-content.min.css",
	];

	files.forEach((file) => {
		if (min === false) {
			file = file.replace(".min", "");
		}

		paths.push("./assets/front-end/css/" + file);
	});

	return paths;
};

const jsEntry = (min = true) => {
	let paths = [];
	let files = [
		"common.min.js",
		"general.min.js",
		"imagesloaded.pkgd.min.js",
		"isotope.pkgd.min.js",
		"load-more.min.js",
		"post-grid.min.js",
		"morphext.min.js",
		"typed.min.js",
		"fancy-text.min.js",
		"countdown.min.js",
		"count-down.min.js",
		"tooltipster.bundle.min.js",
		"price-table.min.js",
		"twitter-feed.min.js",
		"facebook-feed.min.js",
		"advanced-data-table.min.js",
		"data-table.min.js",
		"jquery.magnific-popup.min.js",
		"filterable-gallery.min.js",
		"image-accordion.min.js",
		"content-ticker.min.js",
		"advanced-accordion.min.js",
		"advanced-tabs.min.js",
		"inview.min.js",
		"progress-bar.min.js",
		"sticky-video-plyr.min.js",
		"sticky-video.min.js",
		"locales-all.min.js",
		"moment.min.js",
		"calendar-main.min.js",
		"daygrid.min.js",
		"timegrid.min.js",
		"listgrid.min.js",
		"event-calendar.min.js",
		"reading-progress.min.js",
		"table-of-content.min.js",
	];

	files.forEach((file) => {
		if (min === false) {
			file = file.replace(".min", "");
		}

		paths.push("./assets/front-end/js/" + file);
	});

	return paths;
};

if (args[0] == "development") {
	concat(cssEntry(false), "./assets/front-end/css/eael.css", function (err) {
		if (err) {
			throw err;
		}

		console.log("eael.css generated");
	});

	concat(jsEntry(false), "./assets/front-end/js/eael.js", function (err) {
		if (err) {
			throw err;
		}

		console.log("eael.js generated");
	});
} else {
	concat(cssEntry(), "./assets/front-end/css/eael.min.css", function (err) {
		if (err) {
			throw err;
		}

		console.log("eael.min.css generated");
	});

	concat(jsEntry(), "./assets/front-end/js/eael.min.js", function (err) {
		if (err) {
			throw err;
		}

		console.log("eael.min.js generated");
	});
}

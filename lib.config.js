const fs = require("fs");
const concat = require("concat");

const cssEntry = (context, minified) => {
	let paths = [];
	let lib = {
		view: [
			"tooltipster.bundle.min.css",
			"magnific-popup.min.css",
			"sticky-video-plyr.min.css",
			"calendar-main.min.css",
			"daygrid.min.css",
			"timegrid.min.css",
			"listgrid.min.css",
		],
	};

	lib[context].forEach((file) => {
		if (minified === false) {
			file = file.replace(".min", "");
		}

		paths.push("./assets/front-end/css/lib-" + context + "/" + file);
	});

	return paths;
};

const jsEntry = (context, minified) => {
	let paths = [];
	let lib = {
		view: [
			"imagesloaded.pkgd.min.js",
			"isotope.pkgd.min.js",
			"morphext.min.js",
			"typed.min.js",
			"countdown.min.js",
			"tooltipster.bundle.min.js",
			"jquery.magnific-popup.min.js",
			"inview.min.js",
			"sticky-video-plyr.min.js",
			"locales-all.min.js",
			"moment.min.js",
			"calendar-main.min.js",
			"daygrid.min.js",
			"timegrid.min.js",
			"listgrid.min.js",
			"embed.min.js",

		],
	};

	lib[context].forEach((file) => {
		if (minified === false) {
			file = file.replace(".min", "");
		}

		paths.push("./assets/front-end/js/lib-" + context + "/" + file);
	});

	return paths;
};

// lib.view.min.css
concat(cssEntry("view", true)).then((result) => {
	fs.writeFile("./assets/front-end/css/lib-view/lib-view.min.css", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.view.css
concat(cssEntry("view", false)).then((result) => {
	fs.writeFile("./assets/front-end/css/lib-view/lib-view.css", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.view.min.js
concat(jsEntry("view", true)).then((result) => {
	fs.writeFile("./assets/front-end/js/lib-view/lib-view.min.js", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.view.js
concat(jsEntry("view", false)).then((result) => {
	fs.writeFile("./assets/front-end/js/lib-view/lib-view.js", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

const fs = require("fs");
const concat = require("concat");

const cssEntry = (context, minified) => {
	let paths = [];
	let lib = {
		view: [
			"tooltipster/tooltipster.bundle.min.css",
			"magnific-popup/magnific-popup.min.css",
			"plyr/plyr.min.css",
			"full-calendar/calendar-main.min.css",
			"full-calendar/daygrid.min.css",
			"full-calendar/timegrid.min.css",
			"full-calendar/listgrid.min.css",
		],
		edit: ["quill/quill.bubble.min.css"],
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
			"imagesloaded/imagesloaded.pkgd.min.js",
			"isotope/isotope.pkgd.min.js",
			"morphext/morphext.min.js",
			"typed/typed.min.js",
			"countdown/countdown.min.js",
			"tooltipster/tooltipster.bundle.min.js",
			"magnific-popup/jquery.magnific-popup.min.js",
			"inview/inview.min.js",
			"plyr/plyr.min.js",
			"full-calendar/locales-all.min.js",
			"moment/moment.min.js",
			"full-calendar/calendar-main.min.js",
			"full-calendar/daygrid.min.js",
			"full-calendar/timegrid.min.js",
			"full-calendar/listgrid.min.js",
			"typeform/embed.min.js"
		],
		edit: ["quill/quill.min.js"],
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

// lib.edit.min.css
concat(cssEntry("edit", true)).then((result) => {
	fs.writeFile("./assets/front-end/css/lib-edit/lib-edit.min.css", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.edit.css
concat(cssEntry("edit", false)).then((result) => {
	fs.writeFile("./assets/front-end/css/lib-edit/lib-edit.css", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.edit.min.js
concat(jsEntry("edit", true)).then((result) => {
	fs.writeFile("./assets/front-end/js/lib-edit/lib-edit.min.js", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

// lib.edit.js
concat(jsEntry("edit", false)).then((result) => {
	fs.writeFile("./assets/front-end/js/lib-edit/lib-edit.js", result, (err) => {
		if (err) {
			return console.log(err);
		}
	});
});

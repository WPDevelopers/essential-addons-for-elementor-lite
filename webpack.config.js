const fs = require("fs");
const path = require("path");
const glob = require("glob");
// const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const outputEntry = (argv) => {
	let paths = {};

	// if (argv.single == "true") {
	// 	paths["js/view/view"] = [];
	// 	paths["js/edit/edit"] = [];
	// 	paths["css/view/view"] = [];
	// }

	glob.sync("./src/js/view/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			paths[path.join("js", "view", fileName)] = file;
		}
	}, {});

	glob.sync("./src/js/edit/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			paths[path.join("js", "edit", fileName)] = file;
		}
	}, {});

	// glob.sync("./src/css/view/*").reduce((acc, file) => {
	// 	let fileName = path.parse(file).name;

	// 	if (fileName.charAt(0) !== "_") {
	// 		paths[path.join("css", "view", fileName)] = file;
	// 	}
	// }, {});

	return paths;
};
// const removeEntry = (argv) => {
// 	entry = [];

// 	if (argv.single == "true") {
// 		entry.push(path.join("css", "view", "view.js"));
// 		entry.push(path.join("css", "view", "view.min.js"));

// 		return entry;
// 	}

// 	glob.sync("./src/css/view/*").reduce((acc, file) => {
// 		let fileName = path.parse(file).name;

// 		if (fileName.charAt(0) !== "_") {
// 			entry.push(path.join("css", "view", fileName.concat(".js")));
// 			entry.push(path.join("css", "view", fileName.concat(".min.js")));
// 		}
// 	}, {});

// 	return entry;
// };

module.exports = (env, argv) => {
	return {
		entry: outputEntry(argv),
		output: {
			path: path.resolve(__dirname, "assets/front-end/"),
			filename: argv.mode === "development" ? "[name].min.js" : "[name].js",
		},
		plugins: [
			// new MiniCssExtractPlugin({
			// 	filename: argv.mode === "production" ? "[name].min.css" : "[name].css",
			// }),
			// {
			// 	apply(compiler) {
			// 		compiler.hooks.shouldEmit.tap(
			// 			"removeStylesFromOutput",
			// 			(compilation) => {
			// 				removeEntry(argv).forEach((entry) => {
			// 					delete compilation.assets[entry];
			// 				});
			// 				return true;
			// 			}
			// 		);
			// 	},
			// },
			{
				apply: (compiler) => {
					compiler.hooks.afterEmit.tap("postBuild", (compilation) => {
						const dir = "./../../uploads/essential-addons-elementor";

						fs.readdir(dir, (err, files) => {
							if (err) throw err;

							for (let file of files) {
								fs.unlink(path.join(dir, file), (err) => {
									if (err) throw err;
								});
							}
						});
					});
				},
			},
		],
		module: {
			rules: [
				{
					test: /\.(js)$/,
					exclude: /node_modules/,
					use: {
						loader: "babel-loader",
					},
				},
				// {
				// 	test: /\.(scss)$/,
				// 	use: [
				// 		MiniCssExtractPlugin.loader,
				// 		{ loader: "css-loader", options: { url: false } },
				// 		"postcss-loader",
				// 		"sass-loader",
				// 	],
				// },
			],
		},
	};
};

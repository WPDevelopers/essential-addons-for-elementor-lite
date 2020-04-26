const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { exec } = require("child_process");
const outputEntry = (argv) => {
	let paths = {};

	if (argv.single == "true") {
		paths[path.join("js", "eael-view")] = [];
		paths[path.join("css", "eael-view")] = [];
	}

	glob.sync("./src/js/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			if (argv.single == "true") {
				paths[path.join("js", "eael-view")].push(file);
			} else {
				paths[path.join("js", fileName)] = file;
			}
		}
	}, {});

	glob.sync("./src/css/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			if (argv.single == "true") {
				paths[path.join("css", "eael-view")].push(file);
			} else {
				paths[path.join("css", fileName)] = file;
			}
		}
	}, {});

	return paths;
};
const removeEntry = (argv) => {
	entry = [];

	if (argv.single == "true") {
		entry.push(path.join("css", "eael-view.js"));
		entry.push(path.join("css", "eael-view.min.js"));

		return entry;
	}

	glob.sync("./src/css/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			entry.push(path.join("css", fileName.concat(".js")));
			entry.push(path.join("css", fileName.concat(".min.js")));
		}
	}, {});

	return entry;
};

module.exports = (env, argv) => {
	return {
		stats: "minimal",
		entry: outputEntry(argv),
		output: {
			path: path.resolve(__dirname, "assets/front-end/"),
			filename: argv.mode === "production" ? "[name].min.js" : "[name].js",
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: argv.mode === "production" ? "[name].min.css" : "[name].css",
			}),
			{
				apply(compiler) {
					compiler.hooks.shouldEmit.tap("removeStylesFromOutput", (compilation) => {
						removeEntry(argv).forEach((entry) => {
							delete compilation.assets[entry];
						});
						return true;
					});
				},
			},
			{
				apply: (compiler) => {
					compiler.hooks.afterEmit.tap("postBuild", (compilation) => {
						// exec(`node minify.config.js ${argv.mode}`);
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
				{
					test: /\.(scss)$/,
					use: [MiniCssExtractPlugin.loader, { loader: "css-loader", options: { url: false } }, "postcss-loader", "sass-loader"],
				},
			],
		},
	};
};

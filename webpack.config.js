const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const outputEntry = () => {
	paths = {};

	glob.sync("./src/js/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			paths[path.join("js", fileName)] = file;
		}
	}, {});

	glob.sync("./src/css/*").reduce((acc, file) => {
		let fileName = path.parse(file).name;

		if (fileName.charAt(0) !== "_") {
			paths[path.join("css", fileName)] = file;
		}
	}, {});

	return paths;
};
const removeEntry = () => {
	entry = [];

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
		watch: argv.mode === "development" ? true : false,
		entry: outputEntry(),
		output: {
			path: path.resolve(__dirname, "assets/front-end"),
			filename: argv.mode === "development" ? "[name].js" : "[name].min.js",
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: argv.mode === "development" ? "[name].css" : "[name].min.css",
			}),
			{
				apply(compiler) {
					compiler.hooks.shouldEmit.tap("Remove styles from output", (compilation) => {
						removeEntry().forEach((entry) => {
							delete compilation.assets[entry];
						});
						return true;
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
					use: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"],
				},
			],
		},
	};
};

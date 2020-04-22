const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { exec } = require("child_process");
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
		stats: "minimal",
		entry: outputEntry(),
		output: {
			path: path.resolve(__dirname, "assets/front-end"),
			filename: argv.mode === "production" ? "[name].min.js" : "[name].js",
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: argv.mode === "production" ? "[name].min.css" : "[name].css",
			}),
			{
				apply(compiler) {
					compiler.hooks.shouldEmit.tap("removeStylesFromOutput", (compilation) => {
						removeEntry().forEach((entry) => {
							delete compilation.assets[entry];
						});
						return true;
					});
				},
			},
			{
				apply: (compiler) => {
					compiler.hooks.afterEmit.tap("postBuild", (compilation) => {
						exec(`node minify.config.js ${argv.mode}`);
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

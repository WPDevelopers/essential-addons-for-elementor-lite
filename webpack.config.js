const path = require("path");

module.exports = (env, argv) => {
	return {
		watch: true,
		entry: {
			"advanced-data-table": "./src/advancedDataTable.js",
			eael: ["./src/common.js", "./src/advancedDataTable.js"],
		},
		output: {
			path: path.resolve(__dirname, "assets/front-end/js"),
			filename: argv.mode === "development" ? "[name]/index.js" : "[name]/index.min.js",
		},
		module: {
			rules: [
				{
					test: /\.(js)$/,
					exclude: /node_modules/,
					use: {
						loader: "babel-loader",
					},
				},
			],
		},
	};
};

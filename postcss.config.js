module.exports = (ctx) => {
	const config = {
		plugins: [
			require("postcss-import"),
			require("autoprefixer")({
				overrideBrowserslist: ["last 20 versions"],
			}),
		],
	};

	if (ctx.webpack.mode === "production") {
		config.plugins.push(
			require("cssnano")({
				preset: "default",
			})
		);
	}

	return config;
};

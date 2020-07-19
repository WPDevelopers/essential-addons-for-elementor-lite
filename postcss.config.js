module.exports = (ctx) => {
	const config = {
		plugins: [
			require("postcss-import"),
			require("autoprefixer")({
				overrideBrowserslist: ["last 10 versions"],
			}),
		],
	};

	return config;
};

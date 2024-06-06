const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');

const config = {
	...defaultConfig,
	entry: {
		'quick-setup.min': './src/js/admin/quick-seteup/index.js',
	},
	output: {
		path: path.resolve(__dirname, 'assets/admin/js'),
		filename: '[name].js',
	},
	plugins: [...defaultConfig.plugins, new CleanWebpackPlugin()],
};

module.exports = config;
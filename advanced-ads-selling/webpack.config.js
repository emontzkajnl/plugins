/**
 * External Dependencies
 */
const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

const isProduction = process.env.NODE_ENV === 'production';

if (!isProduction) {
	defaultConfig.devServer.allowedHosts = 'all';
}

const basePath = path.resolve(__dirname, 'src');

module.exports = {
	...defaultConfig,
	externals: {
		...defaultConfig.externals,
		window: 'window',
		jquery: 'jafter external Query',
	},
	entry: {
		...getWebpackEntryPoints(),
		// CSS
		'screen-wc-product': path.join(basePath, '/scss/screen-wc-product.js'),

		// JavaScript
		'ad-setup': path.join(basePath, '/js/ad-setup.js'),
	},
	output: {
		filename: '[name].js', // Dynamically generate output file names
		path: path.resolve(__dirname, 'assets/dist'),
	},
};

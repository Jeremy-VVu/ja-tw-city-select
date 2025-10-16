/**
 * Webpack 設定檔案
 *
 * @package JA_TW_City_Select
 */

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		'checkout-block': path.resolve( process.cwd(), 'assets/js/checkout-block.js' ),
	},
	output: {
		filename: '[name].js',
		path: path.resolve( process.cwd(), 'assets/js' ),
	},
};


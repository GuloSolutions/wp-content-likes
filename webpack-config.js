const path = require( 'path' );
const config = {
	entry: {
		frontend: './public/js/wordpress-content-likes-public.js',
	},
	output: {
		filename: '_likesfrontend.js',
		path: path.resolve( __dirname, './public/js' )
	},
	module: {
		rules: [
			{
				// Look for any .js files.
				test: /\.js$/,
				// Exclude the node_modules folder.
				exclude: /node_modules/,
				// Use babel loader to transpile the JS files.
				loader: 'babel-loader'
			}
		]
	}
}

module.exports = config;

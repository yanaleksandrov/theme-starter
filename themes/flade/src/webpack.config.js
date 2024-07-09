const defaultConfig             = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );
const SpriteLoaderPlugin        = require( 'svg-sprite-loader/plugin' );
const RemoveEmptyScriptsPlugin  = require( 'webpack-remove-empty-scripts' );
const glob                      = require( 'glob' );
const path                      = require( 'path' );

const isProduction = process.env.NODE_ENV === 'production';

// Modify and extend default module rules
const moduleRules = () => {
	// SVG icons sprite generation
	const spriteRule = {
		test: /\.svg$/,
		include: [ /sprite/ ],
		use: [
			{
				loader: 'svg-sprite-loader',
				options: {
					extract: true,
					spriteFilename: 'sprite.svg',
				},
			},
			{
				loader: 'svgo-loader',
			},
		],
	};

	// Get default rules
	const defaultRules = [ ...defaultConfig.module.rules ];

	// Combine default and our rules
	const allRules = defaultRules.concat( spriteRule );

	// Change default rules
	return allRules.map(
		function( ruleData ) {
			// Find the rule for svg inside the stylesheet
			if ( ruleData?.type === 'asset/inline' ) { //phpcs:ignore
				// Make it separate svg file instead of inline css code,
				// that allows us to reduce the css size
				ruleData.type      = 'asset/resource';
				ruleData.generator = {
					filename: 'images/[name][ext]',
				};
			}

			// Find the rule for images and fonts
			if ( ruleData?.generator?.filename !== undefined ) { //phpcs:ignore
				// Change returned filename to be without the hash
				ruleData.generator = {
					filename: ruleData.generator.filename.replace( '.[hash:8]', '' ),
				};
			}

			return ruleData;
		}
	)
}

// Compile every scss file from "styles/inline" folder in a separate file for inline use
const inlineEntries = () => {
	return glob.sync( './src/styles/inline/**.scss' ).reduce( function( obj, el ) {
		obj[ 'inline/' + path.parse( el ).name ] = el;
		return obj;
	}, {} )
}

// Entry for SVG icons sprite
const spriteEntry = () => {
	// Get all files inside the sprite folder
	const entryFilepath = glob.sync( './src/sprite/*.svg' );

	// Define entry
	let entry = {};

	// If there are some files inside the folder - declare the entry parameters
	if ( entryFilepath.length ) {
		entry[ 'sprite' ] = entryFilepath;
	}

	return entry;
}

let config = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints(),
		...inlineEntries(),
		...spriteEntry(),
		'front': path.resolve( __dirname, './entry.js' ),
		'front-delayed': path.resolve( __dirname, './front-delayed.js' ),
		'admin': path.resolve( __dirname, './admin.js' )
	},
	externals: {
		//'jquery': 'jQuery',
		'react': 'React',
		'react-dom': 'ReactDOM',
	},
	module: {
		rules: moduleRules()
	},
	output: {
		filename: './[name].js',
		path: path.resolve( process.cwd(), 'build' ),
		publicPath: '/wp-content/themes/flade/build/', // ToDo path inside blocks is broken
	},
	plugins: [
		...defaultConfig.plugins,
		new SpriteLoaderPlugin(),

		// Remove unexpected .js and .php files, generated for scss entries (like 'inline' folder)
		new RemoveEmptyScriptsPlugin( {
			stage: RemoveEmptyScriptsPlugin.STAGE_BEFORE_PROCESS_PLUGINS,
		} )
	],
};

if ( ! isProduction ) {
	config.devtool = 'inline-source-map';
}

module.exports = config;

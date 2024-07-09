<?php

namespace fladeTheme\Blocks;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Register function on init must have higher priority than each block itself
	add_action( 'init', $callback( 'register_theme_blocks' ), 9 );

	add_filter( 'block_categories_all', $callback( 'block_categories' ) );

	// Load block assets only when they are rendered
	add_filter( 'should_load_separate_core_block_assets', '__return_true' );
}


/**
 * Write your blocks here, just folder name from src/blocks.
 *
 * @return void
 */
function register_theme_blocks() {
	$blocks = [
		'example',
		'example-advanced',
	];

	foreach ( $blocks as $block ) {
		$block_path = FLADE_THEME_BLOCKS . "$block/index.php";

		// Fix for local environment, ToDo check on other OS
		$block_path = str_replace( '\\', '/', $block_path );

		if ( file_exists( $block_path ) ) {
			require_once $block_path;
		}
	}
}

/**
 * Add theme blocks category, put it first.
 *
 * @param $categories
 *
 * @return array
 */
function block_categories( $categories ): array {
	return array_merge(
		[
			[
				'slug'  => 'flade-blocks',
				'title' => esc_attr__( 'Theme Blocks', 'flade' ),
			],
		],
		$categories
	);
}

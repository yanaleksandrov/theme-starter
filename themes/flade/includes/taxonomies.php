<?php

namespace fladeTheme\Taxonomies;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	//phpcs:ignore add_action( 'init', $callback( 'register_taxonomy_game_type' ) );
}

/**
 * Register taxonomy game.
 * @return void
 */
function register_taxonomy_game_type() {
	register_taxonomy(
		'game_type',
		array( 'game' ),
		array(
			'label'             => '',
			'labels'            => [
				'name'              => esc_attr__( 'Game Types', 'flade' ),
				'singular_name'     => esc_attr__( 'Game Type', 'flade' ),
				'search_items'      => esc_attr__( 'Search Types', 'flade' ),
				'all_items'         => esc_attr__( 'All Types', 'flade' ),
				'view_item '        => esc_attr__( 'View Type', 'flade' ),
				'parent_item'       => esc_attr__( 'Parent Type', 'flade' ),
				'parent_item_colon' => esc_attr__( 'Parent Type:', 'flade' ),
				'edit_item'         => esc_attr__( 'Edit Type', 'flade' ),
				'update_item'       => esc_attr__( 'Update Type', 'flade' ),
				'add_new_item'      => esc_attr__( 'Add New Type', 'flade' ),
				'new_item_name'     => esc_attr__( 'New Game Type', 'flade' ),
				'menu_name'         => esc_attr__( 'Game Types', 'flade' ),
			],
			'description'       => esc_attr__( 'Game Types', 'flade' ),
			'public'            => true,
			'hierarchical'      => true,
			'rewrite'           => true,
			'capabilities'      => [],
			'meta_box_cb'       => null,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rest_base'         => null,
		)
	);
}

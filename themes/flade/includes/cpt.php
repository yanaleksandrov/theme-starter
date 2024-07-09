<?php

namespace fladeTheme\CPT;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	//phpcs:ignore add_action( 'init', $callback( 'register_post_type_brand' ) );
}

/**
 * Register post type
 * @return void
 */
function register_post_type_brand() {
	register_post_type(
		'brand',
		[
			'label'                 => esc_attr__( 'Brands', 'flade' ),
			'labels'                => [
				'name'          => esc_attr__( 'Brands', 'flade' ),
				'singular_name' => esc_attr__( 'Brand', 'flade' ),
			],
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => 'brand',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => false,
			'rewrite'               => [
				'slug'       => 'brand',
				'with_front' => true,
			],
			'query_var'             => true,
			'supports'              => [
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
			],
			'menu_icon'             => 'dashicons-star-filled',
		]
	);
}

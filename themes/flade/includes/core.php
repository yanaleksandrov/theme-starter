<?php

namespace fladeTheme\Core;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Theme features
	add_action( 'after_setup_theme', $callback( 'after_setup_theme' ) );

	// Work with widgets
	//phpcs:ignore add_action( 'widgets_init', $callback( 'widgets_init' ) );
}

/**
 * Widgets init.
 *
 * @return void
 */
function widgets_init() {
	$sidebars = [
		[
			'name'          => esc_html__( 'Match page 1', 'flade' ),
			'id'            => 'match_1',
			'description'   => esc_html__( 'Add widgets here.', 'flade' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		],
	];

	foreach ( $sidebars as $sidebar ) {
		register_sidebar( $sidebar );
	}
}

/**
 * After setup theme.
 *
 * @return void
 */
function after_setup_theme() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
	);

	register_nav_menus(
		[
			'header' => esc_html__( 'Header Menu', 'flade' ),
			'footer' => esc_html__( 'Footer Menu', 'flade' ),
		]
	);

	// @codingStandardsIgnoreStart
	/*
	 * Register additional Post Thumbnail sizes.
	 * Default sizes for reference:
	 * add_image_size( 'thumbnail', 150, 150, true );
	 * add_image_size( 'medium', 300, 300, false );
	 * add_image_size( 'medium_large', 768, '', false );
	 * add_image_size( 'large', 1024, 1024, false );
	 *    add_theme_support( 'post-formats',
	 *        [
	 *            'aside',
	 *            'image',
	 *            'video',
	 *            'quote',
	 *            'link',
	 *        ]
	 *    );
	 * add_post_type_support( 'page', 'excerpt' );
	 * add_theme_support( 'customize-selective-refresh-widgets' );
	 */
	// @codingStandardsIgnoreEnd
}

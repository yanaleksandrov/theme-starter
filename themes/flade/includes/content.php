<?php

namespace fladeTheme\Content;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Extend allowed content
	add_filter( 'wp_kses_allowed_html', $callback( 'wp_kses_allowed_html' ) );
}


/**
 * Allow additional tags and attributes in HTML.
 * Everything must be in lowercase here.
 *
 * @param $tags
 *
 * @return array
 */
function wp_kses_allowed_html( $tags ): array {
	// Extend img allowed attributes
	$tags['img']['fetchpriority'] = true;

	// Allow inline style tags
	$tags['style'] = array(
		'class' => true,
		'id'    => true,
	);

	return $tags;
}

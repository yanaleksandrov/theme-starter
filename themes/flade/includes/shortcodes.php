<?php

namespace fladeTheme\Shortcodes;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	//phpcs:ignore add_shortcode( 'flade_button', $callback( 'flade_button' ) );
}

/**
 * Button shortcode.
 *
 * @param $atts
 * @param $content
 *
 * @return false|string
 */
function flade_button( $atts, $content ) {
	$attributes = shortcode_atts(
		[
			'href' => '#',
		],
		$atts
	);

	ob_start();

	get_template_part( 'partials/shortcodes/button', null, compact( 'attributes', 'content' ) );

	return ob_get_clean();
}

<?php

namespace fladeTheme\CF7;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Disable CF7 auto paragraph
	add_filter( 'wpcf7_autop_or_not', '__return_false' );
}

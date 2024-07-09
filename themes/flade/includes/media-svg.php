<?php

namespace fladeTheme\MediaSVG;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Allow svg to upload
	add_filter( 'upload_mimes', $callback( 'allow_svg' ) );

	// Allow svg to display
	add_filter( 'wp_kses_allowed_html', $callback( 'kses_allowed_svg' ) );

	// Fix an SVG mime type
	add_filter( 'wp_check_filetype_and_ext', $callback( 'fix_svg_mime_type' ), 10, 5 );
}

/**
 * Allow SVG.
 *
 * @param $types
 *
 * @return array
 */
function allow_svg( $types ): array {
	$new_types         = [];
	$new_types['svg']  = 'image/svg+xml';
	$new_types['svgz'] = 'image/svg+xml';

	return array_merge( $types, $new_types );
}

/**
 * Allow SVG attributes. Everything must be in lowercase here.
 *
 * @param $tags
 *
 * @return array
 */
function kses_allowed_svg( $tags ): array {
	$tags['svg'] = array(
		'aria-hidden' => true,
		'class'       => true,
		'fill'        => true,
		'focusable'   => true,
		'height'      => true,
		'id'          => true,
		'role'        => true,
		'style'       => true,
		'viewbox'     => true,
		'width'       => true,
		'xmlns'       => true,
	);

	$tags['animatetransform'] = array(
		'attributename' => true,
		'begin'         => true,
		'calcmode'      => true,
		'dur'           => true,
		'fill'          => true,
		'keysplines'    => true,
		'repeatcount'   => true,
		'values'        => true,
	);

	$tags['g'] = array(
		'clip-path' => true,
		'fill'      => true,
		'fill-rule' => true,
		'style'     => true,
		'transform' => true,
	);

	$tags['line'] = array(
		'id' => true,
		'x1' => true,
		'y1' => true,
		'x2' => true,
		'y2' => true,
	);

	$tags['path'] = array(
		'class'           => true,
		'd'               => true,
		'fill'            => true,
		'fill-opacity'    => true,
		'id'              => true,
		'stroke'          => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'stroke-width'    => true,
		'style'           => true,
		'transform'       => true,
	);

	$tags['polyline'] = array(
		'id'     => true,
		'points' => true,
	);

	$tags['rect'] = array(
		'clip-path' => true,
		'fill'      => true,
		'height'    => true,
		'style'     => true,
		'rx'        => true,
		'transform' => true,
		'width'     => true,
		'x'         => true,
		'y'         => true,
	);

	$tags['use'] = array(
		'href'       => true,
		'xlink:href' => true,
	);

	return $tags;
}

/**
 * Fix an SVG mime type.
 *
 * @param $data
 * @param $file
 * @param $filename
 * @param $mimes
 * @param string $real_mime
 *
 * @return mixed
 */
function fix_svg_mime_type( $data, $file, $filename, $mimes, string $real_mime = '' ) {
	$do_svg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ], true );

	if ( $do_svg ) {
		if ( current_user_can( 'manage_options' ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} else {
			$data['ext']  = false;
			$data['type'] = false;
		}
	}

	return $data;
}

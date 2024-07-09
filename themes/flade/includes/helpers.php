<?php

namespace fladeTheme\Helpers;

if ( isset( $_GET['flade_debug'] ) && (int) $_GET['flade_debug'] === 1 ) { //phpcs:ignore
	! WP_DEBUG ? define( 'WP_DEBUG', true ) : null;
	! WP_DEBUG_DISPLAY ? define( 'WP_DEBUG_DISPLAY', true ) : null;
	@ini_set( 'display_errors', 'On' ); //phpcs:ignore
	@error_reporting( 'E_ALL' );        //phpcs:ignore
}

/**
 * Get version.
 * @return array|false|int|string
 */
function get_version() {
	if ( FLADE_WP_ENV === 'development' ) {
		// Help us develop without browser caching
		return time();
	} else {
		return wp_get_theme()->get( 'Version' );
	}
}

/**
 * Dump function.
 *
 * @param $data
 * @param bool $die
 *
 * @return void
 */
function dump( $data, bool $die = false ) {
	echo '<pre style="text-align: left;width:100%;direction: ltr;">';
	var_dump( $data ); //phpcs:ignore
	echo '</pre>';

	if ( $die ) {
		die();
	}
}

/**
 * Get browser.
 * @return array
 */
function get_browser(): array {
	$u_agent  = $_SERVER['HTTP_USER_AGENT'];
	$bname    = 'Unknown';
	$platform = 'Unknown';
	$version  = '';

	//First get the platform?
	if ( preg_match( '/linux/i', $u_agent ) ) {
		$platform = 'linux';
	} elseif ( preg_match( '/macintosh|mac os x/i', $u_agent ) ) {
		$platform = 'mac';
	} elseif ( preg_match( '/windows|win32/i', $u_agent ) ) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes seperately and for good reason
	if ( preg_match( '/MSIE/i', $u_agent ) && ! preg_match( '/Opera/i', $u_agent ) ) {
		$bname = 'Internet Explorer';
		$ub    = 'MSIE';
	} elseif ( preg_match( '/Firefox/i', $u_agent ) ) {
		$bname = 'Mozilla Firefox';
		$ub    = 'Firefox';
	} elseif ( preg_match( '/Chrome/i', $u_agent ) ) {
		$bname = 'Google Chrome';
		$ub    = 'Chrome';
	} elseif ( preg_match( '/Safari/i', $u_agent ) ) {
		$bname = 'Apple Safari';
		$ub    = 'Safari';
	} elseif ( preg_match( '/Opera/i', $u_agent ) ) {
		$bname = 'Opera';
		$ub    = 'Opera';
	} elseif ( preg_match( '/Netscape/i', $u_agent ) ) {
		$bname = 'Netscape';
		$ub    = 'Netscape';
	}

	// finally get the correct version number
	$known   = array(
		'Version',
		$ub,
		'other',
	);
	$pattern = '#(?<browser>' . join( '|', $known ) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

	if ( ! preg_match_all( $pattern, $u_agent, $matches ) ) { //phpcs:ignore
		// we have no matching number just continue
	}

	// see how many we have
	$i = count( $matches['browser'] );
	if ( $i !== 1 ) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if ( strripos( $u_agent, 'Version' ) < strripos( $u_agent, $ub ) ) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}

	// check if we have a number
	if ( $version === null || $version === '' ) {
		$version = '?';
	}

	return [
		'agent'    => $u_agent,
		'name'     => $bname,
		'version'  => $version,
		'platform' => $platform,
		'pattern'  => $pattern,
	];
}

/**
 * Trim string or return second argument if string is empty
 * @param $value
 * @param $return_if_not_string
 *
 * @return mixed|string
 */
function trim_string( $value, $return_if_not_string = '' ) {
	if ( ! is_string( $value ) ) {
		return $return_if_not_string;
	}

	return trim( $value );
}

/**
 * Check if value is array and return it or return an empty array if value is not array
 * @param $value
 *
 * @return array
 */
function get_array( $value ) {
	if ( ! is_array( $value ) ) {
		return [];
	}

	return $value;
}

<?php

namespace fladeTheme\AnalyticsScripts;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Paste "Google Site Verification" meta tag (head)
	add_filter( 'wp_head', $callback( 'add_google_site_verification_tag' ), - 1 );

	// Paste "Google Tag Manager" script (head and body parts)
	add_filter( 'wp_head', $callback( 'add_gtm_head' ), 15 );
	add_filter( 'wp_body_open', $callback( 'add_gtm_body' ), 5 );

	// Paste "Facebook Pixel" script (head and body parts)
	add_filter( 'wp_head', $callback( 'add_fb_pixel_head' ), 16 );
	add_filter( 'wp_body_open', $callback( 'add_fb_pixel_body' ), 6 );

	// Paste "LinkedIn Insight Tag" script (footer)
	add_filter( 'wp_footer', $callback( 'add_linkedin_insight_tag' ), 50 );
}

function add_google_site_verification_tag() {
	$code = get_theme_mod( 'flade_google_verification' );
	if ( ! $code ) {
		return;
	}

	get_template_part(
		'partials/analytics-google-verification-head',
		null,
		compact( 'code' )
	);
}

function add_gtm_head() {
	$gtm = get_theme_mod( 'flade_gtm' );
	if ( ! $gtm ) {
		return;
	}

	get_template_part(
		'partials/analytics-gtm-head',
		null,
		compact( 'gtm' )
	);
}

function add_gtm_body() {
	$gtm = get_theme_mod( 'flade_gtm' );
	if ( ! $gtm ) {
		return;
	}

	get_template_part(
		'partials/analytics-gtm-body',
		null,
		compact( 'gtm' )
	);
}

function add_fb_pixel_head() {
	$fb_pixel = get_theme_mod( 'flade_fbpixel' );
	if ( ! $fb_pixel ) {
		return;
	}

	get_template_part(
		'partials/analytics-fb-pixel-head',
		null,
		compact( 'fb_pixel' )
	);
}

function add_fb_pixel_body() {
	$fb_pixel = get_theme_mod( 'flade_fbpixel' );
	if ( ! $fb_pixel ) {
		return;
	}

	get_template_part(
		'partials/analytics-fb-pixel-body',
		null,
		compact( 'fb_pixel' )
	);
}

function add_linkedin_insight_tag() {
	$linkedin_insight_tag_id = get_theme_mod( 'flade_linkedin_insight' );
	if ( ! $linkedin_insight_tag_id ) {
		return;
	}

	get_template_part(
		'partials/analytics-linkedin-insight-tag-footer',
		null,
		compact( 'linkedin_insight_tag_id' )
	);
}

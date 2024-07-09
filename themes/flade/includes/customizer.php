<?php

namespace fladeTheme\Customizer;

use WP_Customize_Manager;
use WP_Customize_Media_Control;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'customize_register', $callback( 'customize_identify' ) );
	add_action( 'customize_register', $callback( 'customize_analytics' ) );
}

/**
 * Customize identify
 *
 * @param WP_Customize_Manager $wp_customize
 *
 * @return void
 */
function customize_identify( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_setting(
		'flade_logo',
		[
			'default'   => '',
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'flade_logo',
			[
				'label'     => esc_html__( 'Upload your logo image', 'flade' ),
				'settings'  => 'flade_logo',
				'section'   => 'title_tagline',
				'mime_type' => 'image',
				'priority'  => 1,
			]
		)
	);

	$wp_customize->add_setting(
		'flade_mobile_logo',
		[
			'default'   => '',
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'flade_mobile_logo',
			[
				'label'     => esc_html__( 'Upload your logo image for mobile', 'flade' ),
				'settings'  => 'flade_mobile_logo',
				'section'   => 'title_tagline',
				'mime_type' => 'image',
				'priority'  => 1,
			]
		)
	);
}

/**
 * Customize analytics
 *
 * @param WP_Customize_Manager $wp_customize
 *
 * @return void
 */
function customize_analytics( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_section(
		'flade_analytics',
		array(
			'title'       => esc_html__( 'Analytics', 'flade' ),
			'description' => esc_html__( 'Site analytics snippets', 'flade' ),
			'priority'    => 20,
		)
	);

	$wp_customize->add_setting(
		'flade_fbpixel',
		array(
			'default' => '',
		)
	);

	$wp_customize->add_control(
		'flade_fbpixel',
		array(
			'label'    => esc_html__( 'Facebook Pixel ID', 'flade' ),
			'type'     => 'text',
			'settings' => 'flade_fbpixel',
			'section'  => 'flade_analytics',
		)
	);

	$wp_customize->add_setting(
		'flade_gtm',
		array(
			'default' => '',
		)
	);

	$wp_customize->add_control(
		'flade_gtm',
		array(
			'label'    => esc_html__( 'Google Tag Manager ID', 'flade' ),
			'type'     => 'text',
			'settings' => 'flade_gtm',
			'section'  => 'flade_analytics',
		)
	);

	$wp_customize->add_setting(
		'flade_google_verification',
		array(
			'default' => '',
		)
	);

	$wp_customize->add_control(
		'flade_google_verification',
		array(
			'label'    => esc_html__( 'Google Verification Code', 'flade' ),
			'type'     => 'text',
			'settings' => 'flade_google_verification',
			'section'  => 'flade_analytics',
		)
	);

	$wp_customize->add_setting(
		'flade_linkedin_insight',
		array(
			'default' => '',
		)
	);

	$wp_customize->add_control(
		'flade_linkedin_insight',
		array(
			'label'    => esc_html__( 'LinkedIn Insight Tag ID', 'flade' ),
			'type'     => 'text',
			'settings' => 'flade_linkedin_insight',
			'section'  => 'flade_analytics',
		)
	);
}

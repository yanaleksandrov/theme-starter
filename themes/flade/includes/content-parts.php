<?php

use function fladeTheme\Helpers\get_version;

// ACF fallback. Check for admin, so the plugin can be properly activated
if ( ! is_admin() && ! function_exists( 'get_field' ) ) {
	function get_field( $selector, $_post_id = false ) {
		if ( ! $_post_id ) {
			$_post_id = get_the_ID();
		}

		return get_post_meta( $_post_id, $selector, true );
	}
}

/**
 * Insert inline styles with an internal style tag.
 * Every stylesheet will be added only once.
 *
 * @param string $name The css filename to paste the styles from.
 * @param bool $echo Optional. Print or just return the style tag. Default true.
 */
function flade_inline_style( string $name, bool $echo = true ): string {
	global $flade_used_inline_styles;
	if ( ! is_array( $flade_used_inline_styles ) ) {
		return '';
	}

	// Check is this stylesheet was already added on the page
	$is_added = in_array( $name, $flade_used_inline_styles, true );
	if ( ! $is_added ) {
		$flade_used_inline_styles[] = $name;
	}

	// Add stylesheet for the first time, force adding if it's an ajax request
	if ( ! $is_added || wp_doing_ajax() ) {
		// Include filesystem and make sure that it's properly setup
		global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// Prepare the stylesheet path
		$path = FLADE_THEME_PATH . "build/inline/$name.css";

		if ( $wp_filesystem->exists( $path ) ) {
			$critical_css_content = $wp_filesystem->get_contents( $path );

			// Remove charset as it's breaking the website after WP Rocket minification
			$critical_css_content = str_replace( '@charset "UTF-8";', '', $critical_css_content );

			// Add unique ID
			$id = 'flade-' . str_replace( '_', '', $name ) . '-inline-css';
			if ( $is_added ) {
				$id = uniqid( "$id-" );
			}

			$style = '<style id="' . esc_attr( $id ) . '">' . wp_strip_all_tags( $critical_css_content ) . '</style>';

			if ( $echo ) {
				echo $style; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				return $style;
			}
		}
	}

	return '';
}

/**
 * Generate responsive image tag.
 *
 * @param mixed $image Image Array|ID. ACF image field can be passed as well.
 * @param string $classes Optional. Additional classes of the image.
 * @param string|array $size Optional. Size of the image. Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default 'full'.
 * @param string|array $extra_attributes Optional. Additional attributes of the image. It can be a string or an array (associative or simple).
 * @param bool $print Optional. Print or just return the markup.
 */
function flade_the_image( $image, string $classes = '', $size = 'full', $extra_attributes = '', bool $print = true ) {
	// Get image classes
	$img_classes = 'img-autosize' . ( $classes ? ' ' . $classes : '' );

	// If empty size was passed to the function - use a full as default
	if ( ! $size ) {
		$size = 'full';
	}

	// If we got an image array
	if ( is_array( $image ) ) {
		$image_id = $image['ID'] ?? 0;
	} else {
		// If we got an image ID (can be string or integer)
		$image_id = (int) $image;
	}

	if ( ! $image_id ) {
		return null;
	}

	$image = wp_get_attachment_image(
		$image_id,
		$size,
		false,
		array(
			'class' => $img_classes,
		)
	);

	// Convert an array of attributes into string
	if ( is_array( $extra_attributes ) ) {
		// Check an if array is associative
		if ( array_values( $extra_attributes ) === $extra_attributes ) {
			// Array is not associative - use only values as attribute string
			$extra_attributes = implode( ' ', $extra_attributes );
		} else {
			$extra_attributes_string = '';

			$i = 0;
			foreach ( $extra_attributes as $attr_key => $attr_val ) {
				// Insert empty space between attributes
				if ( $i > 0 ) {
					$extra_attributes_string .= ' ';
				}

				// Some attributes should be without the value
				if ( $attr_val ) {
					$extra_attributes_string .= "$attr_key=\"$attr_val\"";
				} else {
					$extra_attributes_string .= $attr_key;
				}

				$i ++;
			}

			$extra_attributes = $extra_attributes_string;
		}
	}

	// Insert additional image attributes
	if ( $extra_attributes ) {
		$search_to_replace = ' class=';
		$replace_with      = ' ' . $extra_attributes . $search_to_replace;

		$image = str_replace( $search_to_replace, $replace_with, $image );
	}

	if ( $print ) {
		echo $image; //phpcs:ignore
	} else {
		return $image;
	}

	return null;
}

/**
 * Generate icon linked to svg sprite.
 *
 * @param string $icon_id ID of the icon used in the svg sprite.
 * @param array $sizes Optional. SVG width and height dimensions. Format [width, height] in px.
 * @param string $classes Optional. Additional classes passed to the SVG tag.
 * @param bool $print Optional. Print or just return the markup.
 */
function flade_the_sprite_icon( string $icon_id, array $sizes = array(), string $classes = '', bool $print = true ) {
	$ver = get_version();

	$icon_url = FLADE_TEMPLATE_URL . "build/sprite.svg?v=$ver#$icon_id";

	ob_start();
	?>
	<svg
		<?php echo $classes ? ' class="' . esc_attr( $classes ) . '"' : ''; ?>
		<?php echo isset( $sizes[0] ) ? ' width="' . esc_attr( $sizes[0] ) . '"' : ''; ?>
		<?php echo isset( $sizes[1] ) ? ' height="' . esc_attr( $sizes[1] ) . '"' : ''; ?>
	>
		<use xlink:href="<?php echo esc_url( $icon_url ); ?>"></use>
	</svg>

	<?php
	if ( $print ) {
		return ob_get_flush();
	}

	return ob_get_clean();
}

/**
 * Get logo from customizer
 */
function flade_the_logo() {
	if ( FLADE_IS_MOB ) {
		$custom_logo_id = get_theme_mod( 'flade_mobile_logo' ) ? get_theme_mod( 'flade_mobile_logo' ) : get_theme_mod( 'flade_logo' );
	} else {
		$custom_logo_id = get_theme_mod( 'flade_logo' );
	}

	ob_start();

	if ( $custom_logo_id ) {
		?>
		<a class="custom-logo-link img-middle"
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
			rel="home">
			<?php flade_the_image( $custom_logo_id, 'custom-logo' ); ?>
		</a>
		<?php
	}

	ob_get_flush();
}

/**
 * Generate link tag.
 *
 * @param array $link Link Array. ACF link field can be passed as well.
 * @param string $classes Optional. Additional classes of the link.
 * @param string $extra_data Optional. Additional attributes of the link.
 * @param bool $print Optional. Print or just return the markup.
 */
function flade_the_link( array $link, string $classes = '', string $extra_data = '', bool $print = true ) {
	$link_url    = $link['url'] ?? '#';
	$link_target = boolval( $link['target'] ?? false );
	$link_title  = $link['title'] ?? '';

	ob_start();
	?>

	<a
		class="<?php echo esc_attr( $classes ); ?>"
		href="<?php echo esc_url( $link_url ); ?>"
		<?php echo wp_kses_post( $link_target ? ' target="_blank"' : '' ); ?>
		<?php echo wp_kses_post( $extra_data ); ?>
	>
		<?php echo wp_kses_post( $link_title ); ?>
	</a>

	<?php
	if ( $print ) {
		return ob_get_flush();
	}

	return ob_get_clean();
}

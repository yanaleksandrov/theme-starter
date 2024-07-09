<?php
$code = $args['code'] ?? '';
if ( ! $code ) {
	return;
}

// Should be added only on the homepage
if ( ! is_front_page() ) {
	return;
}
?>

<meta name="google-site-verification" content="<?php echo esc_attr( $code ); ?>">

<?php
$fb_pixel = $args['fb_pixel'] ?? '';
if ( ! $fb_pixel ) {
	return;
}
?>

<!-- Facebook Pixel Code (noscript) -->
<noscript>
	<img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=<?php echo esc_js( $fb_pixel ); ?>&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code (noscript) -->

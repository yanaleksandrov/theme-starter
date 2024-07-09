<?php
$gtm = $args['gtm'] ?? '';
if ( ! $gtm ) {
	return;
}
?>

<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_js( $gtm ); ?>" height="0" width="0"
		style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

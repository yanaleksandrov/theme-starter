<?php
$gtm = $args['gtm'] ?? '';
if ( ! $gtm ) {
	return;
}
?>

<!-- Google Tag Manager -->
<?php //phpcs:ignore ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $gtm ); ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo esc_js( $gtm ); ?>');
</script>
<!-- End Google Tag Manager -->

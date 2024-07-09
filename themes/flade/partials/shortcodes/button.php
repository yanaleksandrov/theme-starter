<?php
/**
 * @var array $args
 * @var array $content
 */

?>

<a href="<?php echo esc_url( $args['href'] ); ?>">
	<?php echo wp_kses_post( $content ); ?>
</a>

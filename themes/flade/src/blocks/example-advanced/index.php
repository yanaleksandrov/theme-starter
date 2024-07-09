<?php

namespace fladeTheme\Blocks\ExampleAdvanced;

defined( 'ABSPATH' ) || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 */
function register_block() {
	// Register the block by passing the location of block.json to register_block_type.
	register_block_type(
		__DIR__,
		array(
			'render_callback' => __NAMESPACE__ . '\\render_block',
		)
	);
}

add_action( 'init', __NAMESPACE__ . '\\register_block' );

function render_block( $attributes, $content, $block ) {
	$is_editing = filter_input( INPUT_GET, 'edit', FILTER_SANITIZE_NUMBER_INT );

	$post_title   = $attributes['postTitle'] ?? get_the_title();
	$promo_img    = $attributes['promoImage'] ?? 0;
	$show_date    = $attributes['showDate'] ?? false;
	$excerpt_text = $attributes['excerptText'] ?? '';

	ob_start();
	?>

	<div class="example-hero">
		<?php if ( $promo_img ) : ?>
			<div class="example-hero__promo text-center">
				<?php flade_the_image( $promo_img, 'example-hero__promo-img' ); ?>
			</div>
		<?php endif; ?>

		<h1 class="example-hero__title ff-second">
			<?php echo wp_kses_post( $post_title ); ?>
		</h1>

		<?php if ( $show_date ) : ?>
			<div class="example-hero__date">
				<?php the_date(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $excerpt_text ) : ?>
			<div class="example-hero__excerpt">
				<?php echo wp_kses_post( $excerpt_text ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}

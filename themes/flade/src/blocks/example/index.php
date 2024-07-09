<?php

namespace fladeTheme\Blocks\Example;

defined( 'ABSPATH' ) || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg
 * in the corresponding context.
 */
function register_block() {
	// Register the block by passing the location of block.json to register_block_type
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

	$content = $attributes['content'] ?? '';
	ob_start();
	?>

	<div class="example">
		<?php if ( $is_editing ) : ?>
			<p>You're in edit mode</p>
		<?php endif; ?>

		<?php if ( $content ) : ?>
			<div class="example__content">
				<?php echo wp_kses_post( $content ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}

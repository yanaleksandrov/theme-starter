<?php
/**
 * The main template file
 */
get_header();
?>

<?php if ( ! empty( get_post_field( 'post_content' ) ) ) : ?>
	<section class="page-content">
		<div class="wrapper">
			<div class="entry-content">
				<?php
				the_title( '<h1>', '</h1>' );
				the_content();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();

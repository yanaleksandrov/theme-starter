<?php
/**
 * The template for displaying the footer.
 */

?>

</main><!-- /.main -->
</div><!-- /.site-content -->

<?php flade_inline_style( 'footer' ); ?>
<footer class="footer">
	<div class="wrapper footer__wrapper">
		<div class="footer__inner flex fwrap jcspb aifs">
			<div class="footer__logo">
				<?php flade_the_logo(); ?>
			</div>

			<nav class="footer__nav">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'flex fdc',
						'container'      => false,
					)
				);
				?>
			</nav>
		</div>
	</div>
</footer><!-- /.footer -->

</div><!-- /.main-wrapper -->
<?php wp_footer(); ?>

</body>
</html>

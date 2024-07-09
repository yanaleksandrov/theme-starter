<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'flex' ); ?>>
<?php wp_body_open(); ?>

<div class="main-wrapper flex fdc relative">
	<?php flade_inline_style( 'header' ); ?>
	<header class="header">
		<div class="wrapper header__wrapper">
			<div class="header__inner flex jcspb">
				<div class="header__logo relative">
					<?php flade_the_logo(); ?>
				</div>

				<nav class="header__nav flex fwrap">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'header',
							'menu_class'     => 'flex fwrap',
							'container'      => false,
						)
					);
					?>
				</nav>
			</div>
		</div>
	</header>

	<div class="site-content flex">
		<main class="main">

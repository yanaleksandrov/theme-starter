<?php
$is_github_actions = getenv( 'GITHUB_ACTION' );
// First we need to load the composer autoloader, so we can use WP Mock
if ( $is_github_actions ) {
	// Path when running on GitHub Actions
	require_once '../../vendor/autoload.php';
} else {
	// Local path
	require_once './vendor/autoload.php';
}

// Bootstrap WP_Mock to initialize built-in features
WP_Mock::bootstrap();

if ( $is_github_actions ) {
	require_once '../../themes/flade/includes/helpers.php';
} else {
	require_once './themes/flade/includes/helpers.php';
}

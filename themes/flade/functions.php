<?php
// Type your constants here
const FLADE_WP_ENV         = 'development';
const FLADE_PATH           = __DIR__ . DIRECTORY_SEPARATOR;
const FLADE_THEME_INCLUDES = FLADE_PATH . 'includes' . DIRECTORY_SEPARATOR;
const FLADE_THEME_BLOCKS   = FLADE_PATH . 'build/blocks/';

define( 'FLADE_TEMPLATE_URL', get_template_directory_uri() . '/' );
define( 'FLADE_STYLESHEET_URL', get_stylesheet_uri() );
define( 'FLADE_THEME_PATH', get_template_directory() . DIRECTORY_SEPARATOR );
define( 'FLADE_STATIC_URL', get_template_directory_uri() . '/static/' );
define( 'FLADE_IS_MOB', wp_is_mobile() );

// Creating global variable to see what styles were already added to prevent multiple insertions of the same stylesheet
global $flade_used_inline_styles;
$flade_used_inline_styles = [];

require_once FLADE_THEME_INCLUDES . 'patterns/class-singleton.php';

require_once FLADE_THEME_INCLUDES . 'acf.php';
require_once FLADE_THEME_INCLUDES . 'admin-menu.php';
require_once FLADE_THEME_INCLUDES . 'analytics-scripts.php';
require_once FLADE_THEME_INCLUDES . 'blocks.php';
require_once FLADE_THEME_INCLUDES . 'cf7.php';
require_once FLADE_THEME_INCLUDES . 'cleaner.php';
require_once FLADE_THEME_INCLUDES . 'content.php';
require_once FLADE_THEME_INCLUDES . 'content-parts.php';
require_once FLADE_THEME_INCLUDES . 'core.php';
require_once FLADE_THEME_INCLUDES . 'cpt.php';
require_once FLADE_THEME_INCLUDES . 'customizer.php';
require_once FLADE_THEME_INCLUDES . 'enqueue.php';
require_once FLADE_THEME_INCLUDES . 'helpers.php';
require_once FLADE_THEME_INCLUDES . 'media.php';
require_once FLADE_THEME_INCLUDES . 'media-svg.php';
require_once FLADE_THEME_INCLUDES . 'shortcodes.php';
require_once FLADE_THEME_INCLUDES . 'taxonomies.php';

require_once FLADE_THEME_INCLUDES . 'ajax/class-ajax.php';
require_once FLADE_THEME_INCLUDES . 'ajax/class-ajax-core.php';

fladeTheme\ACF\start();
fladeTheme\AdminMenu\start();
fladeTheme\AnalyticsScripts\start();
fladeTheme\Blocks\start();
fladeTheme\CF7\start();
fladeTheme\Cleaner\start();
fladeTheme\Content\start();
fladeTheme\CPT\start();
fladeTheme\Core\start();
fladeTheme\Customizer\start();
fladeTheme\Enqueue\start();
fladeTheme\Media\start();
fladeTheme\MediaSVG\start();
fladeTheme\Shortcodes\start();
fladeTheme\Taxonomies\start();

fladeTheme\Ajax\Ajax_Core::get_instance()->init();

// Require Composer autoloader if it exists
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

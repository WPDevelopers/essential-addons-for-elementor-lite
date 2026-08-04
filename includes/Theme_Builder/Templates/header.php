<?php
/**
 * Replacement document head and site header.
 *
 * Included from `Frontend::override_header()`, so `$this` is the Frontend
 * instance. Everything the theme's own `header.php` would normally emit — the
 * doctype, the head, the opening `<body>` tag — has to be emitted here, because
 * the theme file is discarded right after this one runs.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

/**
 * Fires just before the Theme Builder header is printed.
 *
 * @since 6.7.3
 */
do_action( 'eael/theme_builder/before_header' );

$this->render_location( 'header' );

/**
 * Fires just after the Theme Builder header is printed.
 *
 * @since 6.7.3
 */
do_action( 'eael/theme_builder/after_header' );

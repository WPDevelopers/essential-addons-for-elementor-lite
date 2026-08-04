<?php
/**
 * Blank canvas used to edit and preview a Theme Builder template.
 *
 * Only used when Elementor's own canvas page template is unavailable — see
 * `Elementor_Integration::template_include()`.
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
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'eael-theme-builder-canvas elementor-template-canvas' ); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

wp_footer();
?>
</body>
</html>

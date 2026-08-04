<?php
/**
 * Replacement site footer and document close.
 *
 * Included from `Frontend::override_footer()`, so `$this` is the Frontend
 * instance. `wp_footer()` has to run here because the theme's own `footer.php`
 * is discarded right after this file.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Fires just before the Theme Builder footer is printed.
 *
 * @since 6.7.3
 */
do_action( 'eael/theme_builder/before_footer' );

$this->render_location( 'footer' );

/**
 * Fires just after the Theme Builder footer is printed.
 *
 * @since 6.7.3
 */
do_action( 'eael/theme_builder/after_footer' );

wp_footer();
?>
</body>
</html>

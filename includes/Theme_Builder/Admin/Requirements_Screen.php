<?php
/**
 * Theme Builder screen shown while the module cannot run.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Admin;

use Essential_Addons_Elementor\Classes\Bootstrap;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Keeps the Theme Builder page reachable when Elementor is not available.
 *
 * Without this the whole module — and with it the submenu page — is never
 * registered, so the menu item disappears and the URL people have bookmarked
 * answers with core's "Sorry, you are not allowed to access this page."
 * There is no way to tell that apart from a permissions problem.
 *
 * The screen renders the same dashboard template as the real page; the template
 * already branches on `Theme_Builder::is_enabled()` and shows the requirement
 * notice instead of the templates list.
 *
 * @since 6.7.3
 */
class Requirements_Screen {

	/**
	 * Register the admin hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		// Priority 11: the Essential Addons top level menu is registered at 10.
		add_action( 'admin_menu', [ $this, 'register_menu' ], 11 );
	}

	/**
	 * Add the Theme Builder submenu.
	 *
	 * @since 6.7.3
	 */
	public function register_menu() {
		$hook_suffix = add_submenu_page(
			Admin::PARENT_SLUG,
			__( 'Theme Builder', 'essential-addons-for-elementor-lite' ),
			__( 'Theme Builder', 'essential-addons-for-elementor-lite' ),
			Theme_Builder::capability(),
			Theme_Builder::page_slug(),
			[ $this, 'render_page' ]
		);

		if ( $hook_suffix ) {
			add_action( 'load-' . $hook_suffix, [ $this, 'on_load' ] );
			add_action( 'admin_print_styles-' . $hook_suffix, [ $this, 'enqueue_assets' ] );
		}
	}

	/**
	 * Prepare the screen.
	 *
	 * Essential Addons prints its own "requires Elementor" notice on every admin
	 * screen, with the same wording and the same Activate button this page
	 * already shows in the context of the templates it is about. Two identical
	 * notices stacked on top of each other read as a bug, so the global one steps
	 * aside here — this screen exists to say exactly that one thing.
	 *
	 * Runs on `load-{hook}`, which fires before `admin_notices`.
	 *
	 * @since 6.7.3
	 */
	public function on_load() {
		remove_action( 'admin_notices', [ Bootstrap::instance(), 'elementor_not_loaded' ] );
	}

	/**
	 * Enqueue the dashboard stylesheet.
	 *
	 * Only the stylesheet: with no list table and no modals there is nothing for
	 * the scripts to do.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'eael-theme-builder',
			EAEL_PLUGIN_URL . 'assets/admin/css/theme-builder.css',
			[],
			EAEL_PLUGIN_VERSION
		);
	}

	/**
	 * Render the dashboard without a templates list.
	 *
	 * @since 6.7.3
	 */
	public function render_page() {
		if ( ! current_user_can( Theme_Builder::capability() ) ) {
			wp_die( esc_html__( 'You do not have permission to manage Theme Builder templates.', 'essential-addons-for-elementor-lite' ) );
		}

		$list_table = null;
		$notice     = '';
		$warning    = '';

		include Theme_Builder::path() . 'Templates/admin/dashboard.php';
	}
}

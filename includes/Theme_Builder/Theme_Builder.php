<?php
/**
 * Theme Builder module bootstrap.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder;

use Essential_Addons_Elementor\Theme_Builder\Admin\Admin;
use Essential_Addons_Elementor\Theme_Builder\Admin\Ajax;
use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Frontend\Frontend;
use Essential_Addons_Elementor\Theme_Builder\Integrations\Compatibility;
use Essential_Addons_Elementor\Theme_Builder\Integrations\Elementor_Integration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Entry point of the Theme Builder module.
 *
 * The module ships Header and Footer template types in v1. Additional types
 * (Single, Archive, 404, Search, Popup, Mega Menu, …) can be added by third
 * parties — or by future releases — through `Template_Types::register_type()`
 * on the `eael/theme_builder/register_types` action, without touching any of
 * the components below.
 *
 * @since 6.7.3
 */
class Theme_Builder {

	/**
	 * Module version. Bumped independently of the plugin so cached artefacts
	 * (admin assets, condition caches) can be invalidated on their own.
	 */
	const VERSION = '1.0.0';

	/**
	 * Singleton instance.
	 *
	 * @var Theme_Builder|null
	 */
	private static $instance = null;

	/**
	 * Instantiated components, keyed by slug.
	 *
	 * @var array
	 */
	private $components = [];

	/**
	 * Singleton accessor.
	 *
	 * @since 6.7.3
	 *
	 * @return Theme_Builder
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Boot the module.
	 *
	 * @since 6.7.3
	 */
	private function __construct() {
		$this->register_components();

		/**
		 * Fires once every Theme Builder component is wired up.
		 *
		 * @since 6.7.3
		 *
		 * @param Theme_Builder $module The module instance.
		 */
		do_action( 'eael/theme_builder/init', $this );
	}

	/**
	 * Instantiate the components required by the current request context.
	 *
	 * @since 6.7.3
	 */
	private function register_components() {
		// Always needed — the CPT, the type registry and the condition engine are
		// shared by both the admin screens and the front-end render engine.
		$this->components['types']      = Template_Types::instance();
		$this->components['post_type']  = new Post_Type();
		$this->components['conditions'] = Conditions_Manager::instance();
		$this->components['elementor']  = new Elementor_Integration();
		$this->components['compat']     = new Compatibility();

		if ( is_admin() ) {
			$this->components['admin'] = new Admin();
			$this->components['ajax']  = new Ajax();
		} else {
			$this->components['frontend'] = new Frontend();
		}
	}

	/**
	 * Get a registered component.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Component slug.
	 *
	 * @return object|null
	 */
	public function get_component( $slug ) {
		return isset( $this->components[ $slug ] ) ? $this->components[ $slug ] : null;
	}

	/**
	 * Absolute path of the module directory, with a trailing slash.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function path() {
		return EAEL_PLUGIN_PATH . 'includes/Theme_Builder/';
	}

	/**
	 * Whether the module can run at all.
	 *
	 * Elementor powers both the editing and the rendering side, so without it
	 * the module stays completely dormant.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		$enabled = defined( 'ELEMENTOR_VERSION' ) && class_exists( '\Elementor\Plugin' );

		/**
		 * Filters whether the Theme Builder module should load.
		 *
		 * @since 6.7.3
		 *
		 * @param bool $enabled Whether the module is enabled.
		 */
		return (bool) apply_filters( 'eael/theme_builder/enabled', $enabled );
	}

	/**
	 * Capability required to manage Theme Builder templates.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function capability() {
		/**
		 * Filters the capability required to manage Theme Builder templates.
		 *
		 * @since 6.7.3
		 *
		 * @param string $capability Capability name.
		 */
		return (string) apply_filters( 'eael/theme_builder/capability', 'manage_options' );
	}

	/**
	 * Slug of the Theme Builder admin page.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function page_slug() {
		return 'eael-theme-builder';
	}

	/**
	 * URL of the Theme Builder admin page.
	 *
	 * @since 6.7.3
	 *
	 * @param array $args Extra query args.
	 *
	 * @return string
	 */
	public static function page_url( $args = [] ) {
		$args = array_merge( [ 'page' => self::page_slug() ], $args );

		return add_query_arg( $args, admin_url( 'admin.php' ) );
	}
}

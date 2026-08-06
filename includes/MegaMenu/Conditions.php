<?php

namespace Essential_Addons_Elementor\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;

/**
 * Availability / compatibility gate for the Mega Menu feature.
 *
 * The widget extends Elementor's `Widget_Nested_Base`, which only ships with
 * Elementor 3.8+. Every consumer (config.php registration, the widget itself and
 * the editor panel visibility) asks this class first so the feature degrades
 * silently instead of fataling on older Elementor installs.
 *
 * @since 6.3.0
 */
class Conditions {

	/**
	 * Fully qualified name of the nested widget base class.
	 */
	const NESTED_BASE_CLASS = 'Elementor\\Modules\\NestedElements\\Base\\Widget_Nested_Base';

	/**
	 * Fully qualified name of the nested repeater control.
	 */
	const NESTED_REPEATER_CLASS = 'Elementor\\Modules\\NestedElements\\Controls\\Control_Nested_Repeater';

	/**
	 * Elementor experiment id that exposes the nested elements API.
	 */
	const NESTED_EXPERIMENT = 'nested-elements';

	/**
	 * Minimum Elementor version that ships the nested elements API.
	 */
	const MIN_ELEMENTOR_VERSION = '3.8.0';

	/**
	 * Memoized result of self::is_supported().
	 *
	 * @var bool|null
	 */
	protected static $is_supported = null;

	/**
	 * Is Elementor loaded at all.
	 *
	 * @return bool
	 */
	public static function has_elementor() {
		return defined( 'ELEMENTOR_VERSION' ) && did_action( 'elementor/loaded' );
	}

	/**
	 * Is the running Elementor new enough for the nested elements API.
	 *
	 * @return bool
	 */
	public static function has_min_elementor_version() {
		return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '>=' );
	}

	/**
	 * Are both nested elements classes the widget relies on autoloadable.
	 *
	 * @return bool
	 */
	public static function has_nested_elements_api() {
		return class_exists( self::NESTED_BASE_CLASS ) && class_exists( self::NESTED_REPEATER_CLASS );
	}

	/**
	 * Can the Mega Menu widget class be safely instantiated.
	 *
	 * Used by the `condition` entry in config.php — when this returns false the
	 * widget is never registered and its class file is never autoloaded.
	 *
	 * @return bool
	 */
	public static function is_supported() {
		if ( null === self::$is_supported ) {
			self::$is_supported = self::has_min_elementor_version() && self::has_nested_elements_api();
		}

		return self::$is_supported;
	}

	/**
	 * Is the nested elements experiment switched on.
	 *
	 * Newer Elementor versions may graduate the experiment and drop it from the
	 * experiments registry altogether; in that case the API classes existing is
	 * treated as "active" rather than reporting the feature as unavailable.
	 *
	 * @return bool
	 */
	public static function is_nested_elements_active() {
		if ( ! self::is_supported() ) {
			return false;
		}

		if ( ! isset( Plugin::$instance->experiments ) || ! is_object( Plugin::$instance->experiments ) ) {
			return true;
		}

		$experiments = Plugin::$instance->experiments;

		if ( ! method_exists( $experiments, 'get_features' ) || ! method_exists( $experiments, 'is_feature_active' ) ) {
			return true;
		}

		// Experiment was merged into core — nothing left to toggle.
		if ( empty( $experiments->get_features( self::NESTED_EXPERIMENT ) ) ) {
			return true;
		}

		return (bool) $experiments->is_feature_active( self::NESTED_EXPERIMENT, true );
	}
}

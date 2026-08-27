<?php
/**
 * Cache layer for Theme Builder template lookups.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Core;

use Essential_Addons_Elementor\Theme_Builder\Integrations\Compatibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Two-tier cache: a per-request static array on top of the persistent object
 * cache (transients when no external object cache is present).
 *
 * Every entry is namespaced by a version number stored in an autoloaded option.
 * Flushing therefore costs a single option write and never leaves orphaned
 * transients behind, which matters on sites with hundreds of templates.
 *
 * @since 6.7.3
 */
class Template_Cache {

	/**
	 * Option holding the cache version.
	 */
	const VERSION_OPTION = 'eael_theme_builder_cache_version';

	/**
	 * Cache group / transient prefix.
	 */
	const GROUP = 'eael_theme_builder';

	/**
	 * Per-request memoization.
	 *
	 * @var array
	 */
	private static $runtime = [];

	/**
	 * Current cache version.
	 *
	 * @since 6.7.3
	 *
	 * @return int
	 */
	public static function get_version() {
		$version = get_option( self::VERSION_OPTION );

		if ( ! $version ) {
			$version = 1;
			update_option( self::VERSION_OPTION, $version, true );
		}

		return (int) $version;
	}

	/**
	 * Read a cached value.
	 *
	 * @since 6.7.3
	 *
	 * @param string $key Cache key.
	 *
	 * @return mixed|false False when the entry is missing.
	 */
	public static function get( $key ) {
		$key = self::build_key( $key );

		if ( array_key_exists( $key, self::$runtime ) ) {
			return self::$runtime[ $key ];
		}

		$value = get_transient( $key );

		if ( false !== $value ) {
			self::$runtime[ $key ] = $value;
		}

		return $value;
	}

	/**
	 * Write a cached value.
	 *
	 * @since 6.7.3
	 *
	 * @param string $key        Cache key.
	 * @param mixed  $value      Value to store.
	 * @param int    $expiration Lifetime in seconds.
	 */
	public static function set( $key, $value, $expiration = DAY_IN_SECONDS ) {
		$key = self::build_key( $key );

		self::$runtime[ $key ] = $value;

		set_transient( $key, $value, $expiration );
	}

	/**
	 * Memoize a value for the duration of the current request only.
	 *
	 * Used for request-scoped results (the matched template for *this* URL),
	 * which must never leak into a persistent cache.
	 *
	 * @since 6.7.3
	 *
	 * @param string $key   Cache key.
	 * @param mixed  $value Value to store.
	 *
	 * @return mixed The stored value.
	 */
	public static function remember( $key, $value ) {
		self::$runtime[ 'runtime:' . $key ] = $value;

		return $value;
	}

	/**
	 * Read a request-scoped memoized value.
	 *
	 * @since 6.7.3
	 *
	 * @param string $key Cache key.
	 *
	 * @return mixed|null Null when the entry is missing.
	 */
	public static function recall( $key ) {
		$key = 'runtime:' . $key;

		return array_key_exists( $key, self::$runtime ) ? self::$runtime[ $key ] : null;
	}

	/**
	 * Invalidate every cached lookup.
	 *
	 * @since 6.7.3
	 */
	public static function flush() {
		self::$runtime = [];

		update_option( self::VERSION_OPTION, self::get_version() + 1, true );

		/**
		 * Fires after the Theme Builder cache is invalidated.
		 *
		 * @since 6.7.3
		 */
		do_action( 'eael/theme_builder/cache_flushed' );
	}

	/**
	 * Build a namespaced, length-safe transient key.
	 *
	 * @since 6.7.3
	 *
	 * @param string $key Raw key.
	 *
	 * @return string
	 */
	private static function build_key( $key ) {
		$language = Compatibility::get_current_language();

		return self::GROUP . '_' . self::get_version() . '_' . md5( $key . '|' . $language );
	}
}

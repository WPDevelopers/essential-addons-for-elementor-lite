<?php
/**
 * Page Cache Setup — how to bring xSpeed up already configured, for a plugin
 * that installs it on a user's behalf.
 *
 * The companion to class-page-cache-safety.php. That file DECIDES (read-only,
 * "does anything already own this page cache?"); this one CONFIGURES. Keep the
 * split: Detector promises never to write, and a host reasons about it on that
 * basis.
 *
 * Copy both files into any WPDeveloper plugin that offers xSpeed as an optional
 * install. The knowledge here — which option holds the page-cache flag, which
 * module rows exist, which one arms the setup-wizard redirect — is xSpeed's, and
 * it goes stale the moment a module is added or renamed. It lives here so the
 * repo that changes those things is the repo that owns the description of them,
 * and so tests/Unit/PortableSetupParityTest.php can fail when they drift.
 *
 * Usage — the ORDER IS THE WHOLE POINT:
 *
 *     require_once __DIR__ . '/page-cache-safety/class-page-cache-setup.php';
 *
 *     use WPDeveloper\PageCacheSafety\Setup;
 *
 *     Setup::prepare();                 // BEFORE activate_plugin()
 *     $activated = activate_plugin( Setup::PLUGIN_FILE );
 *     if ( is_wp_error( $activated ) ) {
 *         Setup::rollback();            // never activated; take the rows back out
 *     } else {
 *         Setup::finish();              // AFTER activation
 *     }
 *
 * Writing the settings BEFORE activation is not a style choice. xSpeed's
 * activation reads what is already stored rather than stamping over it: each
 * module seeds its option row only when one does not exist, and
 * Cache::restore_dropin_if_enabled() sees the page-cache flag already true and
 * installs advanced-cache.php and the WP_CACHE constant itself, through xSpeed's
 * own supported path.
 *
 * Configuring AFTER activation instead does not work, and does not complain:
 * Settings_Manager::update() resolves modules through Module_Registry, which is
 * empty for a plugin activated part-way through the request, so it returns
 * without writing. prepare() is plain update_option() calls that need none of
 * xSpeed's code to be loaded — which is just as well, because at that point none
 * of it is.
 *
 * What this file does NOT do: install or download anything. The host owns the
 * install, its capability checks, and its own UI. This only decides what state
 * xSpeed should be in once the host has put it there.
 *
 * Source of truth: the xSpeed Free repo, page-cache-safety/. Deliberately NOT
 * loaded by xSpeed and NOT shipped in its zip — it exists to be copied. Improve
 * it here and re-copy; do not fork it per plugin.
 *
 * @package WPDeveloper\PageCacheSafety
 * @version 1.0.0
 */

namespace WPDeveloper\PageCacheSafety;

defined( 'ABSPATH' ) || exit;

/*
 * Two plugins on the same site can each carry a copy. First one loaded wins.
 * Unlike Detector these methods write — but everything they write is xSpeed's
 * own option rows, to the same values, so whichever copy runs is immaterial.
 */
if ( ! class_exists( __NAMESPACE__ . '\\Setup' ) ) {

	final class Setup {

		public const VERSION = '1.0.0';

		/**
		 * xSpeed on wordpress.org. A host offering it a row on a screen should
		 * show the real name and link — the user is agreeing to install this
		 * specific plugin.
		 */
		public const PLUGIN_FILE = 'xspeed/xspeed.php';
		public const PLUGIN_SLUG = 'xspeed';
		public const PLUGIN_NAME = 'xSpeed Cache';
		public const PLUGIN_ICON = 'https://ps.w.org/xspeed/assets/icon-256x256.png';
		public const PLUGIN_LINK = 'https://wordpress.org/plugins/xspeed/';

		/**
		 * What xSpeed needs to run. A host whose own floor is lower must check
		 * these before offering the install: WordPress refuses the activation,
		 * and a host that promised a cache then fails mid-import is worse than
		 * one that never offered.
		 */
		public const REQUIRES_WP  = '6.0';
		public const REQUIRES_PHP = '7.4';

		/**
		 * Where xSpeed keeps its settings.
		 *
		 * SETTINGS_OPTION is the pre-module blob. It now holds one key that
		 * matters here, `cache_enabled`, which is deliberately NOT a module
		 * setting: it drives the drop-in install and the wp-config.php edit, and
		 * Settings_Manager rejects it by name for that reason. Everything else
		 * lives in one row per module.
		 */
		public const SETTINGS_OPTION = 'xspeed_options';
		public const MODULE_PREFIX   = 'xspeed_module_';

		/** The key inside SETTINGS_OPTION that turns page caching on. */
		public const PAGE_CACHE_KEY = 'cache_enabled';

		/**
		 * Set unconditionally by xSpeed's activation to force a one-time redirect
		 * to its setup wizard. Nothing a host does before activation can prevent
		 * it, so finish() clears it afterwards.
		 */
		public const WIZARD_REDIRECT_OPTION = 'xspeed_redirect_to_onboarding';

		/**
		 * The module state a host-installed xSpeed should come up with.
		 *
		 * Page caching only. A user who accepted "install a cache" agreed to a
		 * page cache, not to having their markup rewritten: minification, lazy
		 * loading and resource hints all alter the rendered output of whatever
		 * the host just installed, which is the opposite of what was asked for.
		 *
		 * Every module is listed, including ones already off by default, because
		 * "off by default" is not the same as "off". Writing SETTINGS_OPTION
		 * suppresses the first-run path that seeds these rows, so gzip, minify and
		 * browser-cache happen to land off — but that is a side effect of an
		 * unrelated write, not a property of those modules, and a normal install
		 * brings all three up ON. Relying on it silently re-enabled browser
		 * caching for one host already. State the whole end state; write it down.
		 *
		 * Absent on purpose, keeping xSpeed's own defaults: ai-privacy's GDPR
		 * consent requirement, cloudflare's purge-on-update, object-cache's
		 * persistent flag, and cache's purge-on-upgrade. None is a rendering
		 * optimisation, and for the first, off would be the wrong answer. A
		 * blanket "disable everything" rule would have swept up all four and
		 * would silently swallow whatever module ships next; the cost of naming
		 * them is that a new opt-in-by-default optimisation must be added here by
		 * hand, which PortableSetupParityTest exists to catch.
		 */
		public const INITIAL_SETTINGS = array(
			'browser-cache'  => array(
				'enabled' => false,
			),
			'gzip'           => array(
				'gzip_enabled' => false,
			),
			'minify'         => array(
				'minify_html' => false,
				'minify_css'  => false,
			),
			'lazy'           => array(
				'lazy_images'            => false,
				'lazy_iframes'           => false,
				'lazy_videos'            => false,
				'add_missing_dimensions' => false,
			),
			'resource-hints' => array(
				'enabled'     => false,
				'lcp_preload' => false,
				'preconnect'  => false,
			),
			'fonts'          => array(
				'font_display_swap' => false,
			),
			'preloader'      => array(
				'warm_on_publish' => false,
			),
		);

		/**
		 * On disk at all, active or not.
		 *
		 * A host deciding whether to OFFER the install wants this rather than
		 * is_active(): a site that already has xSpeed has made its own decision
		 * about it, and re-offering one the user deactivated is nagging.
		 */
		public static function is_installed(): bool {
			return isset( self::installed_plugins()[ self::PLUGIN_FILE ] );
		}

		public static function is_active(): bool {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				self::load_plugin_api();
			}

			return function_exists( 'is_plugin_active' ) && is_plugin_active( self::PLUGIN_FILE );
		}

		/**
		 * Whether this site can run xSpeed at all, per REQUIRES_*.
		 */
		public static function is_supported(): bool {
			global $wp_version;

			return version_compare( (string) $wp_version, self::REQUIRES_WP, '>=' )
				&& version_compare( PHP_VERSION, self::REQUIRES_PHP, '>=' );
		}

		/**
		 * Write the state xSpeed should come up with. Call BEFORE activation.
		 *
		 * @return bool True when the settings were written. False means the call
		 *              came too late — xSpeed is already active, so its activation
		 *              has already seeded its own rows and writing now would be
		 *              changing a running plugin's configuration rather than
		 *              choosing its starting one. The host should treat false as a
		 *              bug in its own ordering, not as something to retry.
		 */
		public static function prepare(): bool {
			if ( self::is_active() ) {
				return false;
			}

			update_option( self::SETTINGS_OPTION, array( self::PAGE_CACHE_KEY => true ) );

			foreach ( self::INITIAL_SETTINGS as $slug => $values ) {
				update_option( self::MODULE_PREFIX . $slug, $values );
			}

			return true;
		}

		/**
		 * Undo prepare() when the install did not survive to activation.
		 *
		 * Leaving these rows on a site with no xSpeed is litter, and a stranded
		 * `cache_enabled => true` is worse than litter: it would tell a LATER
		 * hand-install to bring up page caching nobody asked for.
		 */
		public static function rollback(): void {
			delete_option( self::SETTINGS_OPTION );

			foreach ( array_keys( self::INITIAL_SETTINGS ) as $slug ) {
				delete_option( self::MODULE_PREFIX . $slug );
			}
		}

		/**
		 * Finish up. Call AFTER a successful activation.
		 *
		 * Two jobs prepare() cannot do:
		 *
		 * - Cancel the setup-wizard redirect, which activation arms
		 *   unconditionally. Someone who accepted a cache during another
		 *   plugin's flow did not ask to be dropped into xSpeed's onboarding on
		 *   their next admin page load. The wizard stays in xSpeed's own menu;
		 *   this only cancels the forced redirect, and deliberately does not mark
		 *   onboarding complete — the user did not complete it.
		 * - Make sure page caching actually took. prepare() should have caused
		 *   activation to install the drop-in; if the filesystem or wp-config.php
		 *   refused, fall back to the long way rather than leave a cache plugin
		 *   that caches nothing.
		 *
		 * @return bool True when xSpeed is active and page caching is live.
		 */
		public static function finish(): bool {
			if ( ! self::is_active() ) {
				return false;
			}

			delete_option( self::WIZARD_REDIRECT_OPTION );

			if ( ! self::page_cache_is_live() ) {
				self::enable_page_cache();
			}

			return self::page_cache_is_live();
		}

		/**
		 * Whether page caching took effect, rather than merely being requested.
		 *
		 * The stored flag proves nothing on its own — the drop-in and the constant
		 * are what make WordPress serve from cache, and either can be missing if a
		 * write was refused.
		 */
		public static function page_cache_is_live(): bool {
			$options = get_option( self::SETTINGS_OPTION, array() );

			return is_array( $options )
				&& ! empty( $options[ self::PAGE_CACHE_KEY ] )
				&& file_exists( WP_CONTENT_DIR . '/advanced-cache.php' )
				&& defined( 'WP_CACHE' ) && WP_CACHE;
		}

		/**
		 * Fallback: switch page caching on the long way.
		 *
		 * Only reached when prepare() did not take. The flag cannot simply be
		 * written — Settings_Manager rejects that key by name, because it is what
		 * drives the drop-in install and the wp-config.php edit, so a bare write
		 * leaves a site claiming a cache it does not have (which Detector then
		 * reads as unknown-occupied). Cache::toggle() does the drop-in and the
		 * constant; the Settings::update() after it persists the flag. Mirrors
		 * Rest_Api::toggle_cache().
		 *
		 * The REST route /xspeed/v1/cache/toggle is the documented entry point and
		 * would be tidier, but a host that just activated xSpeed part-way through a
		 * request cannot reach it: rest_api_init has already fired and the routes
		 * are not registered. These are the same calls that route makes.
		 */
		private static function enable_page_cache(): void {
			if ( ! class_exists( '\\XSpeed\\Cache' ) || ! class_exists( '\\XSpeed\\Settings' ) ) {
				return;
			}

			try {
				$state = \XSpeed\Cache::toggle( true );
				\XSpeed\Settings::update(
					array( self::PAGE_CACHE_KEY => ! empty( $state['enabled'] ) )
				);
			} catch ( \Throwable $e ) {
				// A cache that would not switch on must never take the host's
				// own operation down with it.
				return;
			}

			// Site state just changed under Detector's feet; drop its memo so a
			// later question in this request does not get the pre-install answer.
			if ( class_exists( __NAMESPACE__ . '\\Detector' ) ) {
				Detector::invalidate();
			}
		}

		/**
		 * @return array<string,array>
		 */
		private static function installed_plugins(): array {
			if ( ! function_exists( 'get_plugins' ) ) {
				self::load_plugin_api();
			}

			return function_exists( 'get_plugins' ) ? get_plugins() : array();
		}

		private static function load_plugin_api(): void {
			if ( defined( 'ABSPATH' ) && file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
		}
	}
}

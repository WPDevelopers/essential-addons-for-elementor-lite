<?php
/**
 * Page Cache Setup — how to bring xSpeed up already configured, for a plugin
 * that installs it on a user's behalf.
 *
 * The companion to class-page-cache-safety.php. That file DECIDES (read-only:
 * "does anything already own this page cache?"); this one CONFIGURES. Keep the
 * split — Detector promises never to write, and hosts reason about it on that
 * basis.
 *
 * Call order matters and is enforced in prepare(). See page-cache-safety/README.md.
 *
 * Source of truth: the xSpeed Free repo, page-cache-safety/. Not loaded by
 * xSpeed, not shipped in its zip — it exists to be copied. Improve it here and
 * re-copy; do not fork it per plugin.
 *
 * @package WPDeveloper\PageCacheSafety
 * @version 1.4.1
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

		public const VERSION = '1.4.1';

		/**
		 * A host offering this a row on a screen should show the real name and
		 * link: the user is agreeing to install this specific plugin.
		 */
		public const PLUGIN_FILE = 'xspeed/xspeed.php';
		public const PLUGIN_SLUG = 'xspeed';
		public const PLUGIN_NAME = 'xSpeed Cache';
		public const PLUGIN_ICON = 'https://ps.w.org/xspeed/assets/icon-256x256.png';
		public const PLUGIN_LINK = 'https://wordpress.org/plugins/xspeed/';

		/**
		 * A host whose own floor is lower must check these before offering: a
		 * promised cache that then fails to install is worse than none offered.
		 */
		public const REQUIRES_WP  = '6.0';
		public const REQUIRES_PHP = '7.4';

		/** Pre-module blob, and one row per module. */
		public const SETTINGS_OPTION = 'xspeed_options';
		public const MODULE_PREFIX   = 'xspeed_module_';

		/**
		 * Turns page caching on. Deliberately NOT a module setting — it drives the
		 * drop-in install and the wp-config.php edit, and Settings_Manager rejects
		 * it by name for that reason.
		 */
		public const PAGE_CACHE_KEY = 'cache_enabled';

		/**
		 * What prepare() overwrote, so rollback() can put it back rather than
		 * deleting rows it never created. Option rows outlive plugin deletion, so
		 * a site with no xSpeed can still have them.
		 */
		public const SNAPSHOT_OPTION = 'xspeed_setup_snapshot';

		/**
		 * Set unconditionally by xSpeed's activation to force a one-time redirect
		 * to its setup wizard. Nothing a host does before activation can prevent
		 * it, so finish() clears it afterwards.
		 */
		public const WIZARD_REDIRECT_OPTION = 'xspeed_redirect_to_onboarding';

		/**
		 * The module state a host-installed xSpeed should come up with.
		 *
		 * This is a STATIC COPY of what XSpeed\Settings::conflict_safe_profile()
		 * builds from the live module registry. The copy has to exist: prepare()
		 * runs before activation, when none of xSpeed's code is loaded and there
		 * is no registry to ask. PortableSetupParityTest compares the two and
		 * fails when they drift, and the Pro repo's own test covers its half.
		 *
		 * Two sources feed it, because scanning one misses half the switches:
		 * settings whose schema default is `true` (an ABSENT option row reads
		 * back ON, so these need explicit `false` rows), and the four that
		 * activation seeds through Settings::recommended_module_settings().
		 *
		 * Page caching only. Someone who accepted "install a cache" agreed to a page
		 * cache, not to having their markup rewritten by minify, lazy loading or
		 * resource hints.
		 *
		 * Every module is listed, including ones already off by default, because "off
		 * by default" is not "off": writing SETTINGS_OPTION suppresses the first-run
		 * path that seeds these rows, so a few land off as a side effect of an
		 * unrelated write. Relying on that silently re-enabled browser caching for one
		 * host already.
		 *
		 * ai-privacy, cloudflare, object-cache and cache keep xSpeed's own defaults —
		 * none is a rendering optimisation, and for the first, off would be wrong.
		 */
		public const INITIAL_SETTINGS = array(
			'bloat'          => array(
				'disable_dashicons_frontend' => false,
				'disable_oembed'             => false,
				'disable_rss_feeds'          => false,
				'disable_xmlrpc'             => false,
				'restrict_rest_to_authed'    => false,
				'strip_jquery_migrate'       => false,
			),
			'browser-cache'  => array(
				'enabled' => false,
			),
			'cache'          => array(
				'mobile_separate' => false,
			),
			'cdn'            => array(
				'enabled' => false,
			),
			'cloudflare'     => array(
				'enabled' => false,
			),
			'fonts'          => array(
				'font_display_swap' => false,
			),
			'gzip'           => array(
				'gzip_enabled' => false,
			),
			'lazy'           => array(
				'add_missing_dimensions' => false,
				'lazy_iframes'           => false,
				'lazy_images'            => false,
				'lazy_videos'            => false,
				'video_facade'           => false,
			),
			'minify'         => array(
				'async_css'            => false,
				'combine_css'          => false,
				'combine_js'           => false,
				'defer_js'             => false,
				'delay_js'             => false,
				'minify_css'           => false,
				'minify_html'          => false,
				'minify_js'            => false,
				'remove_query_strings' => false,
			),
			'preloader'      => array(
				'enabled'         => false,
				'warm_on_comment' => false,
				'warm_on_publish' => false,
			),
			'resource-hints' => array(
				'enabled'     => false,
				'lcp_preload' => false,
				'preconnect'  => false,
			),
			'score'          => array(
				'enabled' => false,
			),
		);

		/**
		 * On disk at all, active or not — what an OFFER should gate on. A site that
		 * already has xSpeed has decided about it; re-offering one the user
		 * deactivated is nagging.
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
		 * Has xSpeed ever run on this site?
		 *
		 * The same signal xSpeed's own Settings::set_defaults() gates on: the option
		 * is created on first activation and survives deactivation, so its presence
		 * means these settings are the user's. Its absence means a genuinely fresh
		 * install, whose recommended profile has not been seeded yet.
		 *
		 * This is the question `prepare()` needs, and unlike "are the files on disk"
		 * it is immune to install ordering — activation can only run on an unpacked
		 * plugin, so by the time a host calls prepare() the files are always there.
		 */
		public static function has_settings(): bool {
			return false !== get_option( self::SETTINGS_OPTION, false );
		}

		/**
		 * Did THIS class put xSpeed's settings here?
		 *
		 * The snapshot is prepare()'s receipt: written when it succeeds, cleared by
		 * finish(). Its presence is the only evidence that the page cache is ours to
		 * switch on — without it, prepare() refused because the site already had
		 * settings, and touching them would overwrite the choice the guard protects.
		 */
		public static function is_ours(): bool {
			return false !== get_option( self::SNAPSHOT_OPTION, false );
		}

		public static function is_supported(): bool {
			global $wp_version;

			return version_compare( (string) $wp_version, self::REQUIRES_WP, '>=' )
				&& version_compare( PHP_VERSION, self::REQUIRES_PHP, '>=' );
		}

		/**
		 * Write the state xSpeed should come up with. Call BEFORE activation.
		 *
		 * Not a style choice. xSpeed's activation reads what is already stored rather
		 * than stamping over it: module seeders skip rows that exist, and
		 * Cache::restore_dropin_if_enabled() sees the page-cache flag already true and
		 * installs advanced-cache.php and WP_CACHE itself. Configuring AFTER activation
		 * instead does not work and does not complain — Settings_Manager::update()
		 * resolves modules through Module_Registry, which is empty for a plugin
		 * activated part-way through the request, so it returns without writing.
		 *
		 * Refuses when xSpeed has settings here already — see has_settings(). Not when
		 * its files are on disk: activation can only run on an unpacked plugin, so the
		 * files are always present by the time a host calls this, and guarding on them
		 * meant nothing was ever written.
		 *
		 * Even so, only the keys in INITIAL_SETTINGS are written; anything else in a row
		 * is preserved. Option rows survive plugin deletion, so a site with no xSpeed
		 * can still carry lazy-load exclusions, a preloader sitemap or browser-cache
		 * lifetimes from a previous install, and there is no reason to destroy them to
		 * turn four switches off.
		 *
		 * $enable_cache false installs and configures xSpeed WITHOUT taking the
		 * page cache — for a site where the Detector found an incumbent. Do not
		 * mistake it for "skip this call": skipping means xSpeed's own
		 * set_defaults() sees a fresh install and seeds its recommended profile,
		 * switching on gzip, browser caching and minification. On the very site
		 * where you promised to touch nothing, the user would get markup
		 * rewriting they never agreed to, and no page cache either. Writing the
		 * settings option is what suppresses that first-run path, so this still
		 * has to run — just with the page cache left off.
		 *
		 * @param bool $enable_cache Whether xSpeed should come up owning the page
		 *                           cache. Pass Detector::is_field_clear().
		 *
		 * @return bool False means xSpeed already has settings here, so they are the
		 *              user's and not ours to replace. Not something to retry.
		 */
		public static function prepare( bool $enable_cache = true ): bool {
			if ( self::has_settings() ) {
				/*
				 * A leftover snapshot says these settings are an earlier
				 * prepare() of ours that never reached finish() or rollback() —
				 * the request died between them, which on shared hosting is an
				 * activation that timed out. Left alone it is worse than
				 * untidy: a stranded `cache_enabled => true` makes xSpeed's own
				 * restore_dropin_if_enabled() install the drop-in the next time
				 * the user activates by hand, which is page caching nobody asked
				 * for. Undo it and start again.
				 */
				if ( ! self::is_ours() ) {
					return false;
				}

				self::rollback();

				if ( self::has_settings() ) {
					return false;
				}
			}

			$snapshot = array();
			$settings = get_option( self::SETTINGS_OPTION, null );

			$snapshot[ self::SETTINGS_OPTION ] = $settings;
			update_option(
				self::SETTINGS_OPTION,
				array_merge(
					is_array( $settings ) ? $settings : array(),
					array( self::PAGE_CACHE_KEY => $enable_cache )
				),
				false
			);

			foreach ( self::INITIAL_SETTINGS as $slug => $values ) {
				$option = self::MODULE_PREFIX . $slug;
				$stored = get_option( $option, null );

				$snapshot[ $option ] = $stored;
				update_option(
					$option,
					array_merge( is_array( $stored ) ? $stored : array(), $values ),
					false
				);
			}

			update_option( self::SNAPSHOT_OPTION, $snapshot, false );

			return true;
		}

		/**
		 * Undo prepare() when the install did not survive to activation.
		 *
		 * Restores what was there rather than deleting: a row prepare() merged into
		 * belonged to the site before we touched it. A stranded `cache_enabled => true`
		 * would otherwise tell a LATER hand-install to bring up page caching nobody
		 * asked for.
		 */
		public static function rollback(): void {
			$snapshot = get_option( self::SNAPSHOT_OPTION, null );

			if ( ! is_array( $snapshot ) ) {
				return;
			}

			foreach ( $snapshot as $option => $previous ) {
				if ( null === $previous ) {
					delete_option( $option );
					continue;
				}

				update_option( $option, $previous, false );
			}

			delete_option( self::SNAPSHOT_OPTION );
		}

		/**
		 * Finish up. Call AFTER a successful activation.
		 *
		 * Activation arms the setup-wizard redirect unconditionally, so nothing
		 * prepare() does can prevent it — it has to be cleared here. Clearing the
		 * redirect deliberately does not mark onboarding complete; the user did not
		 * complete it, and the wizard stays in xSpeed's own menu.
		 *
		 * The page-cache fallback is for when the drop-in or wp-config.php write was
		 * refused: better the long way round than a cache plugin that caches nothing.
		 *
		 * @return bool True when xSpeed is active and page caching is live.
		 */
		public static function finish(): bool {
			if ( ! self::is_active() ) {
				return false;
			}

			// Without prepare()'s receipt these settings are not ours, and enabling
			// here would overwrite the very choice the guard protects — two lines
			// later in the same documented flow.
			$ours = self::is_ours();

			// Cancelling the wizard redirect is safe either way: the host caused
			// the activation, so nobody asked to be sent to onboarding.
			delete_option( self::WIZARD_REDIRECT_OPTION );

			if ( ! $ours ) {
				return self::page_cache_is_live();
			}

			/*
			 * prepare( false ) asked for an install that does NOT take the page
			 * cache, and that choice is recorded in the settings row. Enabling
			 * here would install advanced-cache.php over the incumbent's — the
			 * exact conflict the Detector exists to prevent — so the flag is
			 * read back rather than assumed.
			 *
			 * The snapshot still goes: activation succeeded, so there is nothing
			 * to roll back to, and a stranded receipt makes the next prepare()
			 * undo settings that are now legitimately the site's.
			 */
			if ( ! self::page_cache_requested() ) {
				delete_option( self::SNAPSHOT_OPTION );

				return false;
			}

			if ( ! self::page_cache_is_live() ) {
				self::enable_page_cache();
			}

			// Activation succeeded, so there is nothing left to roll back to.
			delete_option( self::SNAPSHOT_OPTION );

			return self::page_cache_is_live();
		}

		/**
		 * Whether page caching took effect, not merely that it was requested: the
		 * flag proves nothing without the drop-in and the WP_CACHE constant.
		 *
		 * The constant is read from wp-config.php, not from `defined()`. Enabling the
		 * cache WRITES it there, and nothing defines it for the request already in
		 * flight — so on a clean site, exactly the site the detector green-lights, the
		 * runtime check is false the whole way through a successful install. Trusting
		 * it made finish() re-run the entire enable and then report failure on a
		 * perfectly good install.
		 */
		/**
		 * Whether the stored settings ASK for page caching — what prepare() was
		 * told, not whether it took effect.
		 *
		 * The distinction matters to finish(): "not live yet" is a job to do,
		 * "not requested" is a decision to respect, and page_cache_is_live()
		 * cannot tell them apart.
		 */
		public static function page_cache_requested(): bool {
			$options = get_option( self::SETTINGS_OPTION, array() );

			return is_array( $options ) && ! empty( $options[ self::PAGE_CACHE_KEY ] );
		}

		public static function page_cache_is_live(): bool {
			$options = get_option( self::SETTINGS_OPTION, array() );

			return is_array( $options )
				&& ! empty( $options[ self::PAGE_CACHE_KEY ] )
				&& file_exists( WP_CONTENT_DIR . '/advanced-cache.php' )
				&& self::wp_cache_constant_written();
		}

		/**
		 * Is WP_CACHE set to true in wp-config.php?
		 *
		 * Delegated to Detector, which already reads that file and understands the
		 * spellings hosts use (`true`, `1`, `'1'`, `TRUE`) and which of them are
		 * literals. A second parser here disagreed with it on both the accepted
		 * values and where wp-config.php lives; two copies of one lookup drift.
		 *
		 * Detector memoises, and the constant may have been written moments ago by
		 * the very call we are checking, so drop the memo first.
		 */
		private static function wp_cache_constant_written(): bool {
			if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
				return true;
			}

			if ( ! class_exists( __NAMESPACE__ . '\\Detector' ) ) {
				// Setup is meant to be copied alongside Detector. Without it the
				// file cannot be read, and "we could not tell" must not read as
				// "it is live".
				return false;
			}

			Detector::invalidate();
			$report = Detector::inspect();

			return isset( $report['wp_cache']['state'] ) && 'true' === $report['wp_cache']['state'];
		}

		/**
		 * Fallback: switch page caching on the long way.
		 *
		 * The flag cannot simply be written — Settings_Manager rejects that key by
		 * name, because it is what drives the drop-in install and the wp-config.php
		 * edit, so a bare write leaves a site claiming a cache it does not have (which
		 * Detector then reads as unknown-occupied). Mirrors Rest_Api::toggle_cache().
		 *
		 * That REST route would be tidier, but a host that just activated xSpeed
		 * part-way through a request cannot reach it: rest_api_init has already fired.
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

<?php
/**
 * Page Cache Safety — a portable, read-only answer to "does this WordPress
 * site already have a page cache, and whose is it?"
 *
 * Copy this ONE file into any WPDeveloper plugin that needs to decide whether
 * to offer, install, or activate a caching plugin. It depends on WordPress and
 * nothing else — no composer package, no xSpeed, no autoloader. That is the
 * point: the decision has to be answerable on a site where xSpeed is not
 * installed yet.
 *
 * Usage:
 *
 *     require_once __DIR__ . '/page-cache-safety/class-page-cache-safety.php';
 *
 *     if ( \WPDeveloper\PageCacheSafety\Detector::is_field_clear() ) {
 *         // Nothing owns the page cache. Safe to promote / install / activate.
 *     } else {
 *         $verdict = \WPDeveloper\PageCacheSafety\Detector::classify();
 *         // $verdict['state']    — one of the STATE_* constants
 *         // $verdict['blockers'] — [ ['code' => …, 'plugin' => …, 'label' => …], … ]
 *     }
 *
 * Blockers carry message CODES, not sentences: the host plugin owns its own
 * textdomain, so it renders the wording. See BLOCKER_* below.
 *
 * What this file will never do: write anything, include or execute another
 * plugin's PHP, or treat a loose substring like "cache" as proof of ownership.
 * An unreadable or unattributable artifact is reported as a blocker, never as
 * a pass — "we could not tell" and "the field is clear" are different answers
 * and only one of them licenses an install.
 *
 * Source of truth: the xSpeed Free repo, page-cache-safety/. It is deliberately
 * NOT loaded by xSpeed and NOT shipped in its zip — it exists to be copied.
 * Improve it there and re-copy; do not fork it per plugin.
 *
 * @package WPDeveloper\PageCacheSafety
 * @version 1.0.0
 */

namespace WPDeveloper\PageCacheSafety;

defined( 'ABSPATH' ) || exit;

/*
 * Two plugins on the same site can each carry a copy of this file. First one
 * loaded wins, which is fine — the classes are pure functions over site state,
 * so any version answers the same question. VERSION is here so a host that
 * cares can log which copy it got.
 */
if ( ! class_exists( __NAMESPACE__ . '\\Detector' ) ) {

	final class Detector {

		public const VERSION = '1.0.0';

		/* Ownership states. */

		/** Nothing owns the page cache. */
		public const STATE_UNCLAIMED = 'unclaimed';
		/** A page cache we can name is installed and serving. */
		public const STATE_FOREIGN_LIVE = 'foreign-live';
		/** Cache files remain but nothing is wired up to serve them. */
		public const STATE_FOREIGN_RESIDUAL = 'foreign-residual';
		/** A page-cache-capable plugin is active with no drop-in evidence. It may cache at server level (LiteSpeed), or have caching switched off — unprovable either way from here. */
		public const STATE_POSSIBLE_LIVE = 'possible-live';
		/** More than one page cache is in play. */
		public const STATE_CONTESTED = 'contested';
		/** A drop-in, or a live WP_CACHE, that we cannot attribute to anyone. */
		public const STATE_UNKNOWN_OCCUPIED = 'unknown-occupied';
		/** We could not read what we needed to decide. */
		public const STATE_UNAVAILABLE = 'unavailable';

		/* Blocker codes. The host plugin supplies the wording. */

		/** A named plugin owns wp-content/advanced-cache.php. `plugin` + `label` are set. */
		public const BLOCKER_FOREIGN_DROPIN = 'foreign_dropin';
		/** A drop-in is installed that we cannot attribute. */
		public const BLOCKER_UNKNOWN_DROPIN = 'unknown_dropin';
		/** A drop-in is installed and could not be read. */
		public const BLOCKER_UNREADABLE_DROPIN = 'unreadable_dropin';
		/** A page-cache-capable plugin is active; its cache may or may not be on. `plugin` + `label` are set. */
		public const BLOCKER_ACTIVE_PAGE_CACHE = 'active_page_cache';
		/** Two or more page caches. */
		public const BLOCKER_MULTIPLE_PAGE_CACHES = 'multiple_page_caches';
		/** WP_CACHE is true but no drop-in owner was found. */
		public const BLOCKER_WP_CACHE_ORPHANED = 'wp_cache_orphaned';
		/** wp-config.php defines WP_CACHE more than once. */
		public const BLOCKER_WP_CACHE_DUPLICATE = 'wp_cache_duplicate';
		/** WP_CACHE is set from an expression, so it cannot be rewritten safely. */
		public const BLOCKER_WP_CACHE_DYNAMIC = 'wp_cache_dynamic';
		/** wp-config.php could not be read. */
		public const BLOCKER_WP_CONFIG_UNREADABLE = 'wp_config_unreadable';

		/* Drop-in owner classifications. */

		public const OWNER_NONE    = 'none';
		public const OWNER_KNOWN   = 'known';
		public const OWNER_UNKNOWN = 'unknown';

		/** Capability keys. */
		public const CAP_PAGE_CACHE   = 'page-cache';
		public const CAP_OBJECT_CACHE = 'object-cache';
		public const CAP_MINIFY       = 'minify';

		/**
		 * @var array|null Memoized report for this request.
		 */
		private static $report = null;

		/**
		 * Is it safe to promote, install, or activate a page-caching plugin?
		 *
		 * The one call most hosts need. True only when nothing owns the page
		 * cache and nothing about the site's state is unreadable or ambiguous.
		 */
		public static function is_field_clear(): bool {
			$verdict = self::classify();

			if ( ! empty( $verdict['blockers'] ) ) {
				return false;
			}

			return in_array(
				$verdict['state'],
				array( self::STATE_UNCLAIMED, self::STATE_FOREIGN_RESIDUAL ),
				true
			);
		}

		/**
		 * Labels of every ACTIVE plugin that can write a page cache. Handy for
		 * a promo screen that wants to say what it found.
		 *
		 * @return string[]
		 */
		public static function active_page_caches(): array {
			$out = array();
			foreach ( self::inspect()['plugins'] as $plugin ) {
				if ( $plugin['page_cache'] && $plugin['active'] ) {
					$out[] = $plugin['label'];
				}
			}
			return $out;
		}

		/**
		 * Who owns wp-content/advanced-cache.php, as a plugin label, or null
		 * when nobody does (or we cannot tell).
		 */
		public static function dropin_owner_label(): ?string {
			$dropin = self::inspect()['dropin'];
			return is_string( $dropin['label'] ) ? $dropin['label'] : null;
		}

		/**
		 * The verdict: one state plus the reasons behind it.
		 *
		 * @return array{state:string,blockers:array<int,array>,notes:array<int,array>,revision:string}
		 */
		public static function classify(): array {
			$report   = self::inspect();
			$dropin   = $report['dropin'];
			$wp_cache = $report['wp_cache'];
			$blockers = array();
			$notes    = array();

			if ( $report['object_dropin']['exists'] ) {
				// Informational only. A persistent object cache sits BESIDE a
				// page cache; it competes for nothing and must never block.
				$notes[] = array(
					'code'   => 'object_cache_present',
					'plugin' => $report['object_dropin']['plugin'],
					'label'  => $report['object_dropin']['label'],
				);
			}

			$active_page_caches = array();
			$active_signals     = array();
			foreach ( $report['plugins'] as $plugin ) {
				if ( $plugin['page_cache'] && $plugin['active'] ) {
					$active_signals = array_merge( $active_signals, (array) $plugin['signals'] );
				}
			}
			foreach ( $report['plugins'] as $plugin ) {
				if ( ! $plugin['page_cache'] ) {
					continue;
				}
				if ( $plugin['active'] ) {
					$active_page_caches[] = $plugin;
				} elseif ( ! empty( $plugin['signals'] ) && array_diff( (array) $plugin['signals'], $active_signals ) ) {
					// Only when this plugin has evidence of its own. Builds
					// that share a signal set with an active sibling (Swift
					// Performance Lite and the commercial build share their
					// constants and options row) would otherwise report the
					// uninstalled twin as having left cache files behind.
					$notes[] = array(
						'code'   => 'residual_cache_files',
						'plugin' => $plugin['plugin'],
						'label'  => $plugin['label'],
					);
				}
			}

			if ( self::OWNER_KNOWN === $dropin['owner'] ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_FOREIGN_DROPIN,
					'plugin' => $dropin['plugin'],
					'label'  => $dropin['label'],
				);
			} elseif ( self::OWNER_UNKNOWN === $dropin['owner'] ) {
				$blockers[] = array(
					'code'   => $dropin['readable'] ? self::BLOCKER_UNKNOWN_DROPIN : self::BLOCKER_UNREADABLE_DROPIN,
					'plugin' => null,
					'label'  => null,
				);
			}

			if ( 'unreadable' === $wp_cache['state'] ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_WP_CONFIG_UNREADABLE,
					'plugin' => null,
					'label'  => null,
				);
			} elseif ( 'duplicate' === $wp_cache['state'] ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_WP_CACHE_DUPLICATE,
					'plugin' => null,
					'label'  => null,
				);
			} elseif ( 'dynamic' === $wp_cache['state'] ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_WP_CACHE_DYNAMIC,
					'plugin' => null,
					'label'  => null,
				);
			}

			// Most specific unsafe state wins.
			if ( 'unreadable' === $wp_cache['state'] || ! $dropin['readable'] ) {
				return self::verdict( self::STATE_UNAVAILABLE, $blockers, $notes, $report );
			}

			/*
			 * Keyed by plugin file so one plugin counts once. A live
			 * competitor is normally both active and the drop-in's owner;
			 * counting those separately put the ordinary single-competitor
			 * site in `contested` with a "more than one page cache" blocker.
			 */
			$owners = array();
			foreach ( $active_page_caches as $active ) {
				$owners[ (string) $active['plugin'] ] = true;
			}
			if ( self::OWNER_KNOWN === $dropin['owner'] ) {
				$owners[ (string) ( $dropin['plugin'] ?? $dropin['label'] ) ] = true;
			}
			if ( count( $owners ) > 1 ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_MULTIPLE_PAGE_CACHES,
					'plugin' => null,
					'label'  => null,
				);
				return self::verdict( self::STATE_CONTESTED, $blockers, $notes, $report );
			}

			if ( self::OWNER_UNKNOWN === $dropin['owner'] ) {
				return self::verdict( self::STATE_UNKNOWN_OCCUPIED, $blockers, $notes, $report );
			}
			if ( self::OWNER_KNOWN === $dropin['owner'] ) {
				return self::verdict( self::STATE_FOREIGN_LIVE, $blockers, $notes, $report );
			}

			if ( ! empty( $active_page_caches ) ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_ACTIVE_PAGE_CACHE,
					'plugin' => $active_page_caches[0]['plugin'],
					'label'  => $active_page_caches[0]['label'],
				);
				return self::verdict( self::STATE_POSSIBLE_LIVE, $blockers, $notes, $report );
			}

			/*
			 * No drop-in, nothing active, but WP_CACHE is on. Something
			 * enabled page caching and we cannot say what. Review, not clear.
			 */
			if ( 'true' === $wp_cache['state'] ) {
				$blockers[] = array(
					'code'   => self::BLOCKER_WP_CACHE_ORPHANED,
					'plugin' => null,
					'label'  => null,
				);
				return self::verdict( self::STATE_UNKNOWN_OCCUPIED, $blockers, $notes, $report );
			}

			foreach ( $notes as $note ) {
				if ( 'residual_cache_files' === $note['code'] ) {
					return self::verdict( self::STATE_FOREIGN_RESIDUAL, $blockers, $notes, $report );
				}
			}

			return self::verdict( self::STATE_UNCLAIMED, $blockers, $notes, $report );
		}

		/**
		 * The raw evidence, for a host that wants to render its own summary.
		 *
		 * @return array{plugins:array<int,array>,dropin:array,object_dropin:array,wp_cache:array,revision:string}
		 */
		public static function inspect(): array {
			if ( null !== self::$report ) {
				return self::$report;
			}

			$report = array(
				'plugins'       => self::inspect_plugins(),
				'dropin'        => self::inspect_dropin(),
				'object_dropin' => self::inspect_object_dropin(),
				'wp_cache'      => self::inspect_wp_cache(),
			);

			// Fingerprint of every fact the verdict rests on. A host that acts
			// on a verdict can re-inspect immediately before acting and bail
			// if this changed.
			$report['revision'] = hash(
				'sha256',
				(string) wp_json_encode(
					array(
						array_map(
							static function ( array $p ) {
								return array( $p['plugin'], $p['active'], $p['signals'] );
							},
							$report['plugins']
						),
						$report['dropin']['owner'],
						$report['dropin']['hash'],
						$report['object_dropin']['hash'],
						$report['wp_cache']['state'],
						$report['wp_cache']['defines'],
					)
				)
			);

			self::$report = $report;
			return $report;
		}

		/** Drop the memoized report — call after activating or deactivating a plugin. */
		public static function invalidate(): void {
			self::$report = null;
		}

		/**
		 * Every catalogued plugin, keyed by its main file path as it appears
		 * in `active_plugins`. Folder-only checks would false-positive on a
		 * plugin merely present on disk.
		 *
		 * Filterable so a host can describe a plugin this copy predates.
		 *
		 * @return array<string,array>
		 */
		public static function catalog(): array {
			$catalog = array(
				'wp-rocket/wp-rocket.php'                      => array(
					'label'        => 'WP Rocket',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'WP_ROCKET_VERSION' ),
					'paths'        => array( 'cache/wp-rocket' ),
					'dropin'       => array( 'WP Rocket', 'WP_ROCKET' ),
				),
				'litespeed-cache/litespeed-cache.php'          => array(
					'label'        => 'LiteSpeed Cache',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_OBJECT_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'LSCWP_V', 'LSCWP_DIR' ),
					'options'      => array( 'litespeed.conf.cache' ),
					'paths'        => array( 'litespeed', 'cache/litespeed' ),
					'dropin'       => array( 'LiteSpeed_Cache', 'LSCWP' ),
				),
				'w3-total-cache/w3-total-cache.php'            => array(
					'label'        => 'W3 Total Cache',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_OBJECT_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'W3TC_DIR', 'W3TC_VERSION' ),
					'paths'        => array( 'w3tc-config/master.php', 'cache/page_enhanced' ),
					'dropin'       => array( 'W3TC', 'w3-total-cache' ),
				),
				'wp-super-cache/wp-cache.php'                  => array(
					'label'        => 'WP Super Cache',
					'capabilities' => array( self::CAP_PAGE_CACHE ),
					'constants'    => array( 'WPCACHEHOME' ),
					'paths'        => array( 'wp-cache-config.php', 'cache/supercache' ),
					'dropin'       => array( 'WP SUPER CACHE', 'wp-cache-phase1' ),
				),
				'wp-fastest-cache/wpFastestCache.php'          => array(
					'label'        => 'WP Fastest Cache',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'classes'      => array( 'WpFastestCache' ),
					'options'      => array( 'WpFastestCache' ),
					'paths'        => array( 'cache/all', 'cache/wpfc-minified' ),
					'dropin'       => array( 'WpFastestCache', 'wpFastestCache' ),
				),
				'swift-performance-lite/performance.php'       => array(
					'label'        => 'Swift Performance Lite',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'SWIFT_PERFORMANCE_VER', 'SWIFT_PERFORMANCE_DIR' ),
					'options'      => array( 'swift_performance_options' ),
					'paths'        => array( 'cache/swift-performance' ),
					'dropin'       => array( 'Swift Performance', 'swift_performance' ),
				),
				'swift-performance/performance.php'            => array(
					'label'        => 'Swift Performance',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'SWIFT_PERFORMANCE_VER', 'SWIFT_PERFORMANCE_DIR' ),
					'options'      => array( 'swift_performance_options' ),
					'paths'        => array( 'cache/swift-performance' ),
					'dropin'       => array( 'Swift Performance', 'swift_performance' ),
				),
				'cache-enabler/cache-enabler.php'              => array(
					'label'        => 'Cache Enabler',
					'capabilities' => array( self::CAP_PAGE_CACHE ),
					'constants'    => array( 'CACHE_ENABLER_DIR', 'CACHE_ENABLER_VERSION' ),
					'classes'      => array( 'Cache_Enabler_Engine' ),
					'paths'        => array( 'cache/cache-enabler' ),
					'dropin'       => array( 'Cache_Enabler', 'cache-enabler' ),
				),
				'comet-cache/comet-cache.php'                  => array(
					'label'        => 'Comet Cache',
					'capabilities' => array( self::CAP_PAGE_CACHE ),
					'paths'        => array( 'cache/comet-cache' ),
					'dropin'       => array( 'comet-cache', 'ZenCache', 'Quick Cache' ),
				),
				'hummingbird-performance/wp-hummingbird.php'   => array(
					'label'        => 'Hummingbird',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'WPHB_ADVANCED_CACHE', 'WPHB_VERSION' ),
					'paths'        => array( 'wphb-cache' ),
					'dropin'       => array( 'Hummingbird', 'WPHB' ),
				),
				'sg-cachepress/sg-cachepress.php'              => array(
					'label'        => 'SG Optimizer',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'SiteGround_Optimizer\\VERSION' ),
					'dropin'       => array( 'SG CachePress', 'SiteGround' ),
				),
				'breeze/breeze.php'                            => array(
					'label'        => 'Breeze',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'BREEZE_VERSION', 'BREEZE_CACHE_DIR' ),
					'paths'        => array( 'cache/breeze-minification' ),
					'dropin'       => array( 'Breeze', 'breeze-cache' ),
				),
				'autoptimize/autoptimize.php'                  => array(
					// Minification only. Present so a host can say "we saw it
					// and it is not a page cache" instead of guessing.
					'label'        => 'Autoptimize',
					'capabilities' => array( self::CAP_MINIFY ),
					'constants'    => array( 'AUTOPTIMIZE_PLUGIN_VERSION' ),
					'paths'        => array( 'cache/autoptimize' ),
				),
				'flying-press/flying-press.php'                => array(
					'label'        => 'FlyingPress',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'FLYING_PRESS_VERSION', 'FLYING_PRESS_CACHE_DIR' ),
					'paths'        => array( 'cache/flying-press' ),
					'dropin'       => array( 'FlyingPress', 'flying-press' ),
				),
				'nitropack/main.php'                           => array(
					'label'        => 'NitroPack',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'NITROPACK_VERSION' ),
					'dropin'       => array( 'NitroPack', 'nitropack' ),
				),
				'wp-optimize/wp-optimize.php'                  => array(
					'label'        => 'WP-Optimize',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'constants'    => array( 'WPO_VERSION', 'WPO_CACHE_DIR' ),
					'paths'        => array( 'cache/wpo-cache' ),
					'dropin'       => array( 'WP-Optimize', 'WPO_CACHE' ),
				),
				'jetpack-boost/jetpack-boost.php'              => array(
					'label'        => 'Jetpack Boost',
					'capabilities' => array( self::CAP_PAGE_CACHE, self::CAP_MINIFY ),
					'paths'        => array( 'boost-cache' ),
					'dropin'       => array( 'Jetpack Boost', 'jetpack-boost' ),
				),
				'redis-cache/redis-cache.php'                  => array(
					// Object cache only — owns object-cache.php, never
					// advanced-cache.php. Catalogued so the object-cache
					// drop-in can be NAMED rather than reported as unknown.
					'label'         => 'Redis Object Cache',
					'capabilities'  => array( self::CAP_OBJECT_CACHE ),
					'constants'     => array( 'WP_REDIS_VERSION' ),
					'object_dropin' => array( 'Redis Object Cache', 'WP_Redis' ),
				),
				'memcached/object-cache.php'                   => array(
					'label'         => 'Memcached Object Cache',
					'capabilities'  => array( self::CAP_OBJECT_CACHE ),
					'object_dropin' => array( 'Memcached', 'memcache' ),
				),
			);

			if ( function_exists( 'apply_filters' ) ) {
				$filtered = apply_filters( 'page_cache_safety_catalog', $catalog );
				if ( is_array( $filtered ) ) {
					$catalog = $filtered;
				}
			}

			return $catalog;
		}

		/**
		 * One row per catalogued plugin that is active or has left evidence.
		 *
		 * @return array<int,array>
		 */
		private static function inspect_plugins(): array {
			if ( ! function_exists( 'is_plugin_active' ) && defined( 'ABSPATH' ) && file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$out = array();
			foreach ( self::catalog() as $file => $entry ) {
				$signals = self::signals_for( $entry );
				$active  = function_exists( 'is_plugin_active' ) ? (bool) is_plugin_active( $file ) : false;
				if ( ! $active && empty( $signals ) ) {
					continue;
				}

				$capabilities = (array) ( $entry['capabilities'] ?? array() );
				$out[]        = array(
					'plugin'       => $file,
					'label'        => (string) ( $entry['label'] ?? $file ),
					'active'       => $active,
					'capabilities' => $capabilities,
					'page_cache'   => in_array( self::CAP_PAGE_CACHE, $capabilities, true ),
					'signals'      => $signals,
				);
			}
			return $out;
		}

		/**
		 * Which of an entry's signals are present. Constants and classes are
		 * checked WITHOUT autoloading, options through get_option, paths by
		 * stat under wp-content. No foreign file is opened or executed.
		 *
		 * @return string[]
		 */
		private static function signals_for( array $entry ): array {
			$found = array();

			foreach ( (array) ( $entry['constants'] ?? array() ) as $constant ) {
				if ( defined( $constant ) ) {
					$found[] = 'constant:' . $constant;
				}
			}
			foreach ( (array) ( $entry['classes'] ?? array() ) as $class ) {
				if ( class_exists( $class, false ) ) {
					$found[] = 'class:' . $class;
				}
			}
			foreach ( (array) ( $entry['options'] ?? array() ) as $option ) {
				if ( function_exists( 'get_option' ) ) {
					$value = get_option( $option, null );
					if ( null !== $value && false !== $value ) {
						$found[] = 'option:' . $option;
					}
				}
			}
			foreach ( (array) ( $entry['paths'] ?? array() ) as $path ) {
				if ( defined( 'WP_CONTENT_DIR' ) && file_exists( WP_CONTENT_DIR . '/' . ltrim( (string) $path, '/' ) ) ) {
					$found[] = 'path:' . $path;
				}
			}

			return $found;
		}

		/**
		 * advanced-cache.php: present, whose, and what it hashes to.
		 */
		private static function inspect_dropin(): array {
			$target = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/advanced-cache.php' : '';
			$state  = array(
				'path'     => $target,
				'exists'   => false,
				'owner'    => self::OWNER_NONE,
				'plugin'   => null,
				'label'    => null,
				'hash'     => null,
				'readable' => true,
			);

			if ( '' === $target || ! file_exists( $target ) ) {
				return $state;
			}

			$state['exists'] = true;
			$contents        = self::read( $target );
			if ( null === $contents ) {
				$state['readable'] = false;
				$state['owner']    = self::OWNER_UNKNOWN;
				return $state;
			}

			$state['hash'] = hash( 'sha256', $contents );
			$owner         = self::identify( $contents, 'dropin' );

			if ( null !== $owner ) {
				$catalog         = self::catalog();
				$state['owner']  = self::OWNER_KNOWN;
				$state['plugin'] = $owner;
				$state['label']  = (string) ( $catalog[ $owner ]['label'] ?? $owner );
				return $state;
			}

			$state['owner'] = self::OWNER_UNKNOWN;
			return $state;
		}

		/**
		 * object-cache.php. Reported, never a blocker: a persistent object
		 * cache is a different layer from a page cache.
		 */
		private static function inspect_object_dropin(): array {
			$target = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/object-cache.php' : '';
			$state  = array(
				'path'   => $target,
				'exists' => false,
				'plugin' => null,
				'label'  => null,
				'hash'   => null,
			);

			if ( '' === $target || ! file_exists( $target ) ) {
				return $state;
			}

			$state['exists'] = true;
			$contents        = self::read( $target );
			if ( null === $contents ) {
				return $state;
			}

			$state['hash'] = hash( 'sha256', $contents );

			// Its own token list first; several page-cache plugins ship both
			// drop-ins and mark them with the same string, hence the fallback.
			$owner = self::identify( $contents, 'object_dropin' );
			if ( null === $owner ) {
				$owner = self::identify( $contents, 'dropin' );
			}
			if ( null !== $owner ) {
				$catalog         = self::catalog();
				$state['plugin'] = $owner;
				$state['label']  = (string) ( $catalog[ $owner ]['label'] ?? $owner );
			}

			return $state;
		}

		/**
		 * Match a drop-in body against the catalog's tokens. Case-insensitive
		 * on a raw string — the file is never included or executed. Null means
		 * "unknown", which is NOT the same as "safe".
		 */
		private static function identify( string $contents, string $key ): ?string {
			if ( '' === $contents ) {
				return null;
			}
			$haystack = strtolower( $contents );
			foreach ( self::catalog() as $file => $entry ) {
				foreach ( (array) ( $entry[ $key ] ?? array() ) as $token ) {
					if ( '' !== (string) $token && false !== strpos( $haystack, strtolower( (string) $token ) ) ) {
						return $file;
					}
				}
			}
			return null;
		}

		/**
		 * WP_CACHE as WRITTEN in wp-config.php, plus the runtime value.
		 *
		 * The literal is what matters: a value behind an expression, or two
		 * competing defines, cannot be rewritten safely, and a host that acts
		 * on "WP_CACHE is false" without noticing it is `getenv(...)` will get
		 * it wrong.
		 *
		 * @return array{state:string,runtime:?bool,defines:int}
		 */
		private static function inspect_wp_cache(): array {
			$runtime = defined( 'WP_CACHE' ) ? (bool) constant( 'WP_CACHE' ) : null;
			$path    = self::wp_config_path();

			if ( '' === $path ) {
				return array(
					'state'   => 'unreadable',
					'runtime' => $runtime,
					'defines' => 0,
				);
			}

			$config = self::read( $path );
			if ( null === $config ) {
				return array(
					'state'   => 'unreadable',
					'runtime' => $runtime,
					'defines' => 0,
				);
			}

			// Non-greedy up to the closing `);` so a value containing
			// parentheses is captured whole and read as dynamic, rather than
			// not matching at all and being reported as undefined.
			$count = preg_match_all(
				"/define\\s*\\(\\s*['\"]WP_CACHE['\"]\\s*,\\s*(.*?)\\s*\\)\\s*;/i",
				$config,
				$matches
			);

			if ( ! $count ) {
				return array(
					'state'   => 'undefined',
					'runtime' => $runtime,
					'defines' => 0,
				);
			}
			if ( $count > 1 ) {
				return array(
					'state'   => 'duplicate',
					'runtime' => $runtime,
					'defines' => $count,
				);
			}

			/*
			 * Hosts and older tutorials write the value several ways — 1, '1',
			 * TRUE — and all are literals. Only something we cannot evaluate by
			 * reading it (a variable, a call, a ternary) is dynamic.
			 */
			$literal = trim( strtolower( trim( (string) $matches[1][0] ) ), "'\"" );
			if ( in_array( $literal, array( 'true', '1' ), true ) ) {
				$state = 'true';
			} elseif ( in_array( $literal, array( 'false', '0', '', 'null' ), true ) ) {
				$state = 'false';
			} else {
				$state = 'dynamic';
			}

			return array(
				'state'   => $state,
				'runtime' => $runtime,
				'defines' => 1,
			);
		}

		/** wp-config.php, in the site root or one level up (WP supports both). */
		private static function wp_config_path(): string {
			if ( ! defined( 'ABSPATH' ) ) {
				return '';
			}
			foreach ( array( ABSPATH . 'wp-config.php', dirname( ABSPATH ) . '/wp-config.php' ) as $path ) {
				if ( file_exists( $path ) ) {
					return $path;
				}
			}
			return '';
		}

		/**
		 * Read a file for inspection. Null on any failure — callers treat null
		 * as "unknown", never as "empty". An empty string would read as "no
		 * marker found", which is exactly the wrong conclusion.
		 */
		private static function read( string $path ): ?string {
			if ( ! is_readable( $path ) ) {
				return null;
			}
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Read-only inspection of a local file; WP_Filesystem would need credentials we must not prompt for on a read path.
			$contents = @file_get_contents( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- A failed read is a valid answer here ("unknown"), not an error to surface.
			return is_string( $contents ) ? $contents : null;
		}

		private static function verdict( string $state, array $blockers, array $notes, array $report ): array {
			return array(
				'state'    => $state,
				'blockers' => array_values( $blockers ),
				'notes'    => array_values( $notes ),
				'revision' => (string) ( $report['revision'] ?? '' ),
			);
		}
	}
}

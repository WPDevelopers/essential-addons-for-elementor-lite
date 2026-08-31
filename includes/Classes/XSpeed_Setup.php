<?php
namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * EA's adapter over the portable page-cache-safety pair in
 * `includes/page-cache-safety/`.
 *
 * Those two files are copied verbatim from the xSpeed Free repo and must stay
 * that way — improvements go back there and get re-copied, they are not forked
 * per plugin. Everything EA-specific lives here instead:
 *
 * - Loading them. They sit under `includes/`, but they are namespaced
 *   `WPDeveloper\PageCacheSafety` rather than `Essential_Addons_Elementor\`, so
 *   EA's PSR-4 autoloader never resolves them however they are named — they
 *   need an explicit require.
 * - The PHP floor. Both files use `public const` and `?string`, which are PHP
 *   7.1 syntax — a parse error, not a catchable one, on the PHP 7.0 EA still
 *   claims to support. Requiring them behind a version check keeps that error
 *   from ever being reached; below the floor this class simply reports xSpeed
 *   as not offerable, which is the right answer anyway since xSpeed needs 7.4.
 * - Slug/basename dispatch, so the generic plugin installer can call through
 *   for every plugin and only xSpeed gets the cache-specific treatment.
 *
 * Two questions, two owners:
 *
 * - `Detector` (read-only) answers "does anything already own this site's page
 *   cache?" — that gates whether EA may OFFER xSpeed at all.
 * - `Setup` (writes) answers "what state should xSpeed come up in?" — that runs
 *   around activation, and the ORDER MATTERS: settings before `activate_plugin()`,
 *   because xSpeed's activation reads what is already stored rather than
 *   stamping over it. Configuring afterwards silently does nothing.
 *
 */
class XSpeed_Setup {

	/**
	 * wordpress.org slug and plugin file, as the installer sees them.
	 */
	const SLUG     = 'xspeed';
	const BASENAME = 'xspeed/xspeed.php';

	/**
	 * Parse floor of the portable files, not of xSpeed. See the class docblock.
	 */
	const REQUIRES_PHP = '7.1';

	/**
	 * Tri-state load result: null = not attempted, bool = the answer.
	 *
	 * @var bool|null
	 */
	private static $available = null;

	/**
	 * Memoized offer decision. Detector memoizes its own inspection per request,
	 * but `can_offer()` is asked once per wizard surface and each miss walks
	 * `get_plugins()` as well.
	 *
	 * @var bool|null
	 */
	private static $can_offer = null;

	/**
	 * Load the portable pair, once per request.
	 *
	 * @return bool True when both classes are usable.
	 */
	public static function is_available() {
		if ( null !== self::$available ) {
			return self::$available;
		}

		self::$available = false;

		if ( version_compare( PHP_VERSION, self::REQUIRES_PHP, '<' ) ) {
			return self::$available;
		}

		$dir = EAEL_PLUGIN_PATH . 'includes/page-cache-safety/';

		foreach ( [ 'class-page-cache-safety.php', 'class-page-cache-setup.php' ] as $file ) {
			if ( ! file_exists( $dir . $file ) ) {
				return self::$available;
			}

			// Both files self-guard with class_exists(), so a sibling
			// WPDeveloper plugin that already loaded its own copy wins and this
			// require is a no-op rather than a redeclaration fatal.
			require_once $dir . $file;
		}

		self::$available = class_exists( '\WPDeveloper\PageCacheSafety\Detector' )
			&& class_exists( '\WPDeveloper\PageCacheSafety\Setup' );

		return self::$available;
	}

	/**
	 * May EA offer to install xSpeed AND hand it this site's page cache?
	 *
	 * The strict question. Surfaces that merely offer the plugin ask
	 * can_install() instead; this one is for anything that promises page
	 * caching specifically, and for before_activation()'s prepare() flag.
	 *
	 * False whenever we cannot tell. "We could not read the site's state" and
	 * "the field is clear" are different answers and only one of them licenses
	 * an install — so an unreadable wp-config.php, an unattributable drop-in or
	 * a missing portable file all land here as "do not offer".
	 *
	 * @return bool
	 */
	public static function can_offer() {
		if ( null !== self::$can_offer ) {
			return self::$can_offer;
		}

		self::$can_offer = false;

		if ( ! self::is_available() ) {
			return self::$can_offer;
		}

		// WordPress refuses an activation that fails the plugin's own
		// requirements. Promising a cache and then failing mid-wizard is worse
		// than never offering.
		if ( ! \WPDeveloper\PageCacheSafety\Setup::is_supported() ) {
			return self::$can_offer;
		}

		// On disk at all, active or not. A site that already has xSpeed has
		// made its own decision about it; re-offering one the user deactivated
		// is nagging.
		if ( \WPDeveloper\PageCacheSafety\Setup::is_installed() ) {
			return self::$can_offer;
		}

		self::$can_offer = \WPDeveloper\PageCacheSafety\Detector::is_field_clear();

		return self::$can_offer;
	}

	/**
	 * May EA offer to INSTALL xSpeed, ignoring who owns the page cache?
	 *
	 * Deliberately looser than can_offer(). can_offer() refuses whenever the
	 * page-cache field is occupied, because it gates a promise of "a page
	 * cache". This gates a promise of "xSpeed" — asset optimization, CDN,
	 * browser caching, its own dashboard — none of which conflict with an
	 * incumbent cache plugin. The conflict is confined to advanced-cache.php,
	 * and that is handled at install time instead: before_activation() passes
	 * Detector::is_field_clear() to prepare(), so on an occupied site xSpeed
	 * comes up with its page cache switched off rather than overwriting the
	 * drop-in it found.
	 *
	 * Still false when we cannot load the portable pair, when xSpeed's own
	 * PHP/WP floor is not met (WordPress would refuse the activation), or when
	 * xSpeed is already on disk.
	 *
	 * @return bool
	 */
	public static function can_install() {
		if ( ! self::is_available() ) {
			return false;
		}

		if ( ! \WPDeveloper\PageCacheSafety\Setup::is_supported() ) {
			return false;
		}

		return ! \WPDeveloper\PageCacheSafety\Setup::is_installed();
	}

	/**
	 * Should xSpeed appear in the Integrations plugin list?
	 *
	 * Looser than can_offer(), because that list manages plugins rather than
	 * promoting them: an xSpeed already installed but switched off belongs there
	 * even though re-offering an install would be nagging, and one already
	 * running stays listed so the user can switch it back off. What the list
	 * must not do is hand someone a toggle that would put a second page cache
	 * in play — so an incumbent cache, an unreadable site state, or a PHP/WP
	 * floor xSpeed cannot meet all drop the row.
	 *
	 * @return bool
	 */
	public static function can_list() {
		if ( ! self::is_available() ) {
			return false;
		}

		if ( \WPDeveloper\PageCacheSafety\Setup::is_active() ) {
			return true;
		}

		if ( ! \WPDeveloper\PageCacheSafety\Setup::is_supported() ) {
			return false;
		}

		// Not gated on the page-cache field, for the same reason as
		// can_install(): an incumbent cache does not make xSpeed unlistable, it
		// only makes before_activation() bring xSpeed up with its own page
		// cache switched off. A PHP/WP floor xSpeed cannot meet still drops the
		// row, since the toggle would fail.
		return true;
	}

	/**
	 * Label of whatever already owns the page cache, for a UI that wants to say
	 * what it found instead of silently dropping the row.
	 *
	 * @return string Empty when nothing owns it, or when we cannot name the owner.
	 */
	public static function page_cache_owner() {
		if ( ! self::is_available() ) {
			return '';
		}

		$owner = \WPDeveloper\PageCacheSafety\Detector::dropin_owner_label();

		if ( is_string( $owner ) && '' !== $owner ) {
			return $owner;
		}

		$active = \WPDeveloper\PageCacheSafety\Detector::active_page_caches();

		return empty( $active ) ? '' : (string) reset( $active );
	}

	/**
	 * Write the state xSpeed should come up in. Call immediately BEFORE
	 * `activate_plugin()`.
	 *
	 * Not a style choice: xSpeed's activation seeds only the option rows that do
	 * not already exist, and `Cache::restore_dropin_if_enabled()` sees the
	 * page-cache flag already true and installs `advanced-cache.php` and the
	 * `WP_CACHE` constant itself. Writing the same settings after activation
	 * instead does nothing at all and reports no error, because
	 * `Settings_Manager::update()` resolves modules through a `Module_Registry`
	 * that is empty for a plugin activated part-way through the request.
	 *
	 * @param string $slug Plugin slug being installed/activated.
	 * @return bool True when settings were written — pass this to rollback().
	 */
	public static function before_activation( $slug ) {
		if ( self::SLUG !== $slug || ! self::is_available() ) {
			return false;
		}

		// prepare() returns false when xSpeed is already active, i.e. we were
		// called too late. That is a bug in the caller's ordering rather than
		// something to retry, and it must not be treated as "rows written".
		// is_field_clear() decides whether xSpeed comes up OWNING the page
		// cache — not whether it is installed at all. On a site that already
		// has a cache drop-in this writes xSpeed's settings with page caching
		// off, which is the whole point of prepare()'s parameter: skipping the
		// call instead would let xSpeed's own set_defaults() seed its
		// recommended profile on next activation and switch caching on anyway.
		$take_page_cache = (bool) \WPDeveloper\PageCacheSafety\Detector::is_field_clear();

		return (bool) \WPDeveloper\PageCacheSafety\Setup::prepare( $take_page_cache );
	}

	/**
	 * Take the rows back out when the activation never happened.
	 *
	 * A stranded `cache_enabled => true` on a site with no xSpeed is worse than
	 * litter: it would tell a later hand-install to bring up page caching nobody
	 * asked for.
	 *
	 * @param string $slug     Plugin slug that failed to activate.
	 * @param bool   $prepared Return value of before_activation().
	 * @return void
	 */
	public static function activation_failed( $slug, $prepared ) {
		if ( self::SLUG !== $slug || ! $prepared || ! self::is_available() ) {
			return;
		}

		\WPDeveloper\PageCacheSafety\Setup::rollback();
	}

	/**
	 * Finish up. Call AFTER a successful activation.
	 *
	 * Cancels the setup-wizard redirect xSpeed arms unconditionally on
	 * activation — someone who accepted a cache inside EA's Quick Setup did not
	 * ask to be dropped into xSpeed's onboarding on their next page load — and
	 * verifies page caching actually took rather than merely being requested,
	 * falling back to the long way if the drop-in or wp-config.php write was
	 * refused.
	 *
	 * @param string $slug Plugin slug that was just activated.
	 * @return bool True when xSpeed is active and page caching is live.
	 */
	public static function after_activation( $slug ) {
		if ( self::SLUG !== $slug || ! self::is_available() ) {
			return false;
		}

		$live = \WPDeveloper\PageCacheSafety\Setup::finish();

		// Site state just changed under Detector's feet; drop the per-request
		// memo so a later question in this request does not get the
		// pre-install answer. Also invalidates the memo above.
		\WPDeveloper\PageCacheSafety\Detector::invalidate();
		self::$can_offer = null;

		return $live;
	}

	/**
	 * May EA offer to switch a already-installed, currently-inactive xSpeed
	 * back on?
	 *
	 * Distinct from can_offer() and can_list(), both of which answer false here
	 * for reasons that do not apply to reactivation:
	 *
	 * - can_offer() is false the moment xSpeed is on disk. Right for a banner
	 *   selling an install; wrong for a button that activates what is already
	 *   there.
	 * - can_list() and can_install() both answer false once xSpeed is on disk,
	 *   which is exactly the state a reactivation starts from.
	 *
	 * The blocker walk below is what keeps a reactivation honest: a deactivated
	 * xSpeed normally leaves its OWN drop-in behind, and the detector — generic
	 * by design, and blind to which plugin is asking — reports that leftover as
	 * a foreign drop-in. So: every blocker counts except one owned by xSpeed
	 * itself. Reactivating
	 * xSpeed over xSpeed's own residue puts exactly one page cache in play,
	 * which is the entire thing the safety check exists to guarantee. An active
	 * competitor, an unknown or unreadable drop-in, a duplicate or dynamic
	 * WP_CACHE, or a drop-in belonging to any other plugin all still block.
	 *
	 * @return bool
	 */
	public static function can_reactivate() {
		if ( ! self::is_available() ) {
			return false;
		}

		// Nothing to switch back on, or it is already running — both are some
		// other method's question.
		if ( ! \WPDeveloper\PageCacheSafety\Setup::is_installed()
			|| \WPDeveloper\PageCacheSafety\Setup::is_active() ) {
			return false;
		}

		if ( ! \WPDeveloper\PageCacheSafety\Setup::is_supported() ) {
			return false;
		}

		$verdict  = \WPDeveloper\PageCacheSafety\Detector::classify();
		$blockers = isset( $verdict['blockers'] ) ? (array) $verdict['blockers'] : [];

		foreach ( $blockers as $blocker ) {
			$owner = isset( $blocker['plugin'] ) ? $blocker['plugin'] : null;

			// Our own leftovers are not a competitor.
			if ( self::BASENAME === $owner ) {
				continue;
			}

			return false;
		}

		return true;
	}

	/**
	 * Is xSpeed on disk at all, active or not?
	 *
	 * The difference between "we could install this" and "this is already here,
	 * just switched off" — which decides whether a CTA installs or activates.
	 *
	 * @return bool
	 */
	public static function is_on_disk() {
		if ( ! self::is_available() ) {
			return false;
		}

		return (bool) \WPDeveloper\PageCacheSafety\Setup::is_installed();
	}

	/**
	 * Is xSpeed's page cache actually serving, right now?
	 *
	 * The difference between "xSpeed is installed" and "xSpeed is working" —
	 * activation alone does not guarantee the drop-in and WP_CACHE write were
	 * accepted, which is the whole reason after_activation() verifies rather
	 * than assumes. The Speed Check widget shows a different state for each.
	 *
	 * False whenever we cannot tell, matching the rest of this class.
	 *
	 * @return bool
	 */
	public static function page_cache_live() {
		if ( ! self::is_available() ) {
			return false;
		}

		return (bool) \WPDeveloper\PageCacheSafety\Setup::page_cache_is_live();
	}

	/**
	 * Slug for a plugin file, for the activate-only path where the installer
	 * only knows the basename.
	 *
	 * @param string $basename Plugin basename, e.g. `xspeed/xspeed.php`.
	 * @return string Slug, or empty string when it is not a plugin we handle.
	 */
	public static function slug_for_basename( $basename ) {
		return self::BASENAME === $basename ? self::SLUG : '';
	}
}

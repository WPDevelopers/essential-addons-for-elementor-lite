<?php
namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Everything EA needs to know or do about xSpeed Cache.
 *
 * EA used to copy-vendor a `Detector` + `Setup` pair out of the xSpeed repo
 * into `includes/page-cache-safety/`, decide for itself whether the site's page
 * cache was free, and write xSpeed's settings rows before activating it. That
 * directory is gone: xSpeed now owns those decisions and publishes a single
 * integration contract in its place. See
 * `installing-from-another-plugin.md` in the xSpeed repo.
 *
 * What replaced it:
 *
 * - **Installing.** One option write, immediately before `activate_plugin()`:
 *   `update_option( 'xspeed_installed_by', 'essential-addons' )`. No settings
 *   writes, no enable call, no rollback. xSpeed reads that one-shot trigger on
 *   activation and brings itself up with every Free feature off and page
 *   caching on — or, when another plugin already owns the page cache, off and
 *   refused. Either outcome is a success, and a host-claimed install also skips
 *   xSpeed's own setup wizard so it will not interrupt EA's.
 * - **Asking.** `\XSpeed\Host`, once xSpeed is active. It exists only from the
 *   release that introduced it, so every call is guarded and every answer
 *   degrades to "we cannot tell" rather than a guess.
 * - **Gating.** There is none left. xSpeed installs beside anything, so an
 *   incumbent cache plugin no longer suppresses the offer — it only changes
 *   which profile xSpeed picks for itself.
 *
 * The PHP floor here is xSpeed's own (7.4 / WP 6.0), not a parse floor: nothing
 * in this file needs more than PHP 7.0, and `\XSpeed\Host` is only ever reached
 * through `class_exists()`.
 */
class XSpeed_Setup {

	/**
	 * wordpress.org slug and plugin file, as the installer sees them.
	 */
	const SLUG     = 'xspeed';
	const BASENAME = 'xspeed/xspeed.php';

	/**
	 * xSpeed's own requirements. WordPress refuses an activation that fails a
	 * plugin's requirements, and promising a cache then failing mid-wizard is
	 * worse than never offering — so EA checks them before it offers.
	 */
	const REQUIRES_PHP = '7.4';
	const REQUIRES_WP  = '6.0';

	/**
	 * The one-shot trigger xSpeed reads on activation, and the slug EA claims
	 * the install with.
	 *
	 * A plain option rather than a constant, a filter or a class, because it is
	 * written at a moment when no xSpeed code has loaded and none can be relied
	 * on to exist.
	 */
	const INSTALLED_BY_OPTION = 'xspeed_installed_by';
	const INSTALLER_SLUG      = 'essential-addons';

	/**
	 * The site's answer about xSpeed, shared by every WPDeveloper plugin that
	 * offers it (EmbedPress, Essential Addons, Templately).
	 *
	 * Shape: [ offered_by, offered_at, outcome, outcome_at ], outcome one of
	 * `offered` | `accepted` | `declined`. Per site (get_option, not
	 * get_site_option), never autoloaded.
	 *
	 * Deliberately outside the `xspeed_` namespace: xSpeed's uninstaller owns
	 * that prefix, and this row has to survive xSpeed being deleted — that is
	 * the whole point of it. `accepted` with nothing on disk is how every
	 * plugin reads "they had it and removed it".
	 */
	const OFFER_OPTION     = 'wpdeveloper_xspeed_offer';
	const OUTCOME_OFFERED  = 'offered';
	const OUTCOME_ACCEPTED = 'accepted';
	const OUTCOME_DECLINED = 'declined';

	/**
	 * Plugin files whose presence means "this site has xSpeed", Free or Pro.
	 */
	const PRESENCE_BASENAMES = [ 'xspeed/xspeed.php', 'xspeed-pro/xspeed-pro.php' ];

	/**
	 * Register the listeners that keep the shared record honest for installs
	 * and removals EA did not drive itself.
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_action( 'activated_plugin', [ __CLASS__, 'on_activated_plugin' ] );
		add_action( 'deleted_plugin', [ __CLASS__, 'on_deleted_plugin' ], 10, 2 );
	}

	/**
	 * xSpeed was activated — by EA, a sibling plugin, or by hand. The site has
	 * it, so the answer is `accepted`; that is what lets a later deletion read
	 * as a removal instead of as "never asked".
	 *
	 * @param string $basename Plugin basename.
	 * @return void
	 */
	public static function on_activated_plugin( $basename ) {
		if ( in_array( $basename, self::PRESENCE_BASENAMES, true ) ) {
			self::record_outcome( self::OUTCOME_ACCEPTED );
		}
	}

	/**
	 * xSpeed was deleted from the Plugins screen. Its uninstaller has just
	 * wiped `xspeed_options`, so without this a site that installed xSpeed
	 * before this record existed would look like one that was never asked.
	 *
	 * @param string $basename Plugin basename.
	 * @param bool   $deleted  Whether the files were actually removed.
	 * @return void
	 */
	public static function on_deleted_plugin( $basename, $deleted = true ) {
		if ( $deleted && in_array( $basename, self::PRESENCE_BASENAMES, true ) ) {
			self::record_outcome( self::OUTCOME_ACCEPTED );
		}
	}

	/**
	 * May ANY promotional surface put an xSpeed offer in front of this site?
	 *
	 * Copied from the integration contract on purpose — three plugins reading
	 * one table must read it identically:
	 *
	 * | Record says                     | On disk | Offer?            |
	 * |---------------------------------|---------|-------------------|
	 * | anything                        | yes     | no                |
	 * | `accepted`                      | no      | no — they removed it |
	 * | `declined`                      | either  | no                |
	 * | `offered` / absent / unreadable | no      | our own pacing    |
	 *
	 * This governs promos only. An explicit user action — the Integrations
	 * toggle — always proceeds and records `accepted`.
	 *
	 * @return bool
	 */
	public static function may_offer() {
		// Presence beats any record. Free or Pro, active or not.
		foreach ( self::PRESENCE_BASENAMES as $basename ) {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $basename ) ) {
				return false;
			}
		}

		$outcome = self::offer_outcome();

		// 'accepted' with nothing on disk is a removal. Anything unrecognised,
		// or a corrupt row, counts as no answer at all.
		return ! in_array( $outcome, [ self::OUTCOME_ACCEPTED, self::OUTCOME_DECLINED ], true );
	}

	/**
	 * Note that an offer went up — only when the site has no record at all.
	 *
	 * add_option() rather than update_option(): it fails when the row exists,
	 * so two promos racing on one page load cannot clobber each other, and a
	 * stale read can never bury a sibling's `declined`.
	 *
	 * @return void
	 */
	public static function record_offered() {
		$now = time();

		add_option(
			self::OFFER_OPTION,
			[
				'offered_by' => self::INSTALLER_SLUG,
				'offered_at' => $now,
				'outcome'    => self::OUTCOME_OFFERED,
				'outcome_at' => $now,
			],
			'',
			false
		);
	}

	/**
	 * Record the site's answer: `accepted` or `declined`.
	 *
	 * - Never downgrades: nothing takes a terminal answer back to `offered`,
	 *   and a `declined` never overwrites an `accepted`.
	 * - `accepted` does overwrite `declined`, because it only ever comes from
	 *   the user acting — installing or activating xSpeed — which answers the
	 *   question again.
	 * - Keeps `offered_at`: it is when the site was first asked, and a sibling
	 *   pacing itself off it needs it to age.
	 *
	 * @param string $outcome One of the OUTCOME_ACCEPTED / OUTCOME_DECLINED constants.
	 * @return bool True when the record changed.
	 */
	public static function record_outcome( $outcome ) {
		if ( ! in_array( $outcome, [ self::OUTCOME_ACCEPTED, self::OUTCOME_DECLINED ], true ) ) {
			return false;
		}

		$record  = get_option( self::OFFER_OPTION, [] );
		$record  = is_array( $record ) ? $record : [];
		$current = self::offer_outcome();

		if ( $current === $outcome
			|| ( self::OUTCOME_DECLINED === $outcome && self::OUTCOME_ACCEPTED === $current ) ) {
			return false;
		}

		$now = time();

		return update_option(
			self::OFFER_OPTION,
			[
				'offered_by' => self::INSTALLER_SLUG,
				'offered_at' => ( isset( $record['offered_at'] ) && is_numeric( $record['offered_at'] ) && $record['offered_at'] > 0 )
					? (int) $record['offered_at']
					: $now,
				'outcome'    => $outcome,
				'outcome_at' => $now,
			],
			false
		);
	}

	/**
	 * Record `accepted` once an install has put xSpeed's files on disk.
	 *
	 * Never before: `accepted` with nothing on disk reads as "the user removed
	 * it", so writing it ahead of a download that 404s would leave the site
	 * permanently unaskable, by every sibling plugin, having never been asked.
	 *
	 * @param string $slug Plugin slug that was just installed.
	 * @return void
	 */
	public static function after_install( $slug ) {
		if ( self::SLUG !== $slug || ! file_exists( WP_PLUGIN_DIR . '/' . self::BASENAME ) ) {
			return;
		}

		self::record_outcome( self::OUTCOME_ACCEPTED );
	}

	/**
	 * The stored outcome, or '' for an absent or corrupt record.
	 *
	 * @return string
	 */
	private static function offer_outcome() {
		$record = get_option( self::OFFER_OPTION, [] );

		return ( is_array( $record ) && isset( $record['outcome'] ) && is_scalar( $record['outcome'] ) )
			? (string) $record['outcome']
			: '';
	}

	/**
	 * May EA offer to INSTALL xSpeed?
	 *
	 * Nothing about the site's page cache enters into this. xSpeed installs
	 * beside anything and works out for itself how to come up: on an occupied
	 * site it takes the `conflict-safe` profile — everything off, page caching
	 * refused, the incumbent's drop-in untouched — which is a correct outcome
	 * rather than a failed one.
	 *
	 * So only three things say no: a PHP/WP floor xSpeed cannot meet, xSpeed
	 * already being on disk (active or not — a site that has it has made its own
	 * decision, and re-offering an install is nagging), and the shared offer
	 * record saying the site already answered — declined, or accepted and then
	 * removed it. See may_offer().
	 *
	 * @return bool
	 */
	public static function can_install() {
		return self::is_supported() && ! self::is_on_disk() && self::may_offer();
	}

	/**
	 * Should xSpeed appear in the Integrations plugin list?
	 *
	 * Looser than can_install(), because that list manages plugins rather than
	 * promoting them: an xSpeed already installed but switched off belongs there
	 * even though re-offering an install would be nagging, and one already
	 * running stays listed so the user can switch it back off. Only a PHP/WP
	 * floor xSpeed cannot meet drops the row, since the toggle would fail.
	 *
	 * @return bool
	 */
	public static function can_list() {
		return self::is_active() || self::is_supported();
	}

	/**
	 * May EA offer to switch an already-installed, currently-inactive xSpeed
	 * back on?
	 *
	 * Distinct from can_install(), which answers false the moment xSpeed is on
	 * disk — right for a banner selling an install, wrong for a button that
	 * activates what is already there.
	 *
	 * No blocker walk any more. The old one existed to keep a reactivation from
	 * putting a second page cache in play; xSpeed decides that for itself now,
	 * and a deactivated xSpeed's own leftover drop-in — which the generic
	 * detector used to read as a foreign cache and refuse — is no longer
	 * anybody's problem here.
	 *
	 * @return bool
	 */
	public static function can_reactivate() {
		return self::is_on_disk() && ! self::is_active() && self::is_supported();
	}

	/**
	 * Claim the install, immediately BEFORE `activate_plugin()`.
	 *
	 * This is the entire integration. The option is a one-shot **trigger**, not
	 * a record: activation spends it, moving the value to `xspeed_installer`.
	 * That is why it must be written immediately before activating and never
	 * speculatively — an install that dies in between arms the *next*
	 * activation on this site, whoever starts it.
	 *
	 * Refuses when xSpeed is already active, i.e. we were called too late.
	 * That is a bug in the caller's ordering rather than something to retry.
	 *
	 * @param string $slug Plugin slug being installed/activated.
	 * @return bool True when the install was claimed.
	 */
	public static function before_activation( $slug ) {
		if ( self::SLUG !== $slug || self::is_active() ) {
			return false;
		}

		update_option( self::INSTALLED_BY_OPTION, self::INSTALLER_SLUG );

		return true;
	}

	/**
	 * Label of whatever owns this site's page cache, for a UI that wants to say
	 * what it found.
	 *
	 * Only answerable once xSpeed is active — `\XSpeed\Host` is the only place
	 * this knowledge lives now, and EA does not carry a second copy of it.
	 * Callers must treat `''` as "we cannot tell", NOT as "nothing owns it":
	 * on a site with WP Rocket and no xSpeed, both look identical from here.
	 *
	 * @return string Empty when xSpeed is not active, or when nothing owns it.
	 */
	public static function page_cache_owner() {
		if ( ! self::host_available() ) {
			return '';
		}

		$owner = \XSpeed\Host::page_cache_owner();

		return is_string( $owner ) ? $owner : '';
	}

	/**
	 * Can we name the page cache's owner at all?
	 *
	 * The companion to page_cache_owner()'s ambiguous `''`: false means the
	 * question is unanswerable and the caller should stay quiet rather than
	 * report an absence it did not verify.
	 *
	 * @return bool
	 */
	public static function page_cache_owner_is_knowable() {
		return self::host_available();
	}

	/**
	 * Is xSpeed's page cache actually serving, right now?
	 *
	 * The difference between "xSpeed is installed" and "xSpeed is working" —
	 * activation alone does not guarantee the drop-in and the `WP_CACHE` write
	 * were accepted. The Speed Check widget shows a different state for each.
	 *
	 * False whenever we cannot tell, matching the rest of this class.
	 *
	 * @return bool
	 */
	public static function page_cache_live() {
		return self::host_available() && (bool) \XSpeed\Host::page_cache_is_live();
	}

	/**
	 * Everything xSpeed can say about how the install came up, or null.
	 *
	 * One call rather than four, for a surface reporting the outcome. Keys:
	 * `installed`, `active`, `installed_by`, `profile`, `page_cache_live`,
	 * `page_cache_owner`, `page_cache_blocked_reason`, `page_cache_manual_snippet`.
	 *
	 * `profile` records a decision made once at activation — `host-page-cache`
	 * when EA claimed the install on a clear site, `conflict-safe` when
	 * something else owned the page cache, `recommended` when the user
	 * installed it by hand. `conflict-safe` is a success: render
	 * `page_cache_owner` and `page_cache_blocked_reason` and leave it there.
	 *
	 * `page_cache_manual_snippet` is not tied to `page_cache_live` — a managed
	 * host with a read-only `wp-config.php` serves every hit while `WP_CACHE`
	 * never landed, so the cache is live AND there is still a line to paste.
	 * Render either whenever it is non-null.
	 *
	 * @return array|null Null when xSpeed is not active, or predates Host.
	 */
	public static function status() {
		return self::host_available() ? (array) \XSpeed\Host::status() : null;
	}

	/**
	 * Is xSpeed on disk at all, active or not?
	 *
	 * The difference between "we could install this" and "this is already here,
	 * just switched off" — which decides whether a CTA installs or activates.
	 *
	 * Reads the plugin list rather than asking xSpeed, because the question is
	 * asked precisely when xSpeed is not loaded.
	 *
	 * @return bool
	 */
	public static function is_on_disk() {
		self::load_plugin_functions();

		if ( ! function_exists( 'get_plugins' ) ) {
			return false;
		}

		return array_key_exists( self::BASENAME, (array) get_plugins() );
	}

	/**
	 * Is xSpeed active — on this site, or network-wide?
	 *
	 * @return bool
	 */
	public static function is_active() {
		self::load_plugin_functions();

		return function_exists( 'is_plugin_active' ) && (bool) is_plugin_active( self::BASENAME );
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

	/**
	 * Can this site run xSpeed at all?
	 *
	 * EA's own check against xSpeed's published floor, rather than a call into
	 * xSpeed — which by definition is not loaded when the question matters.
	 *
	 * @return bool
	 */
	private static function is_supported() {
		global $wp_version;

		return version_compare( (string) $wp_version, self::REQUIRES_WP, '>=' )
			&& version_compare( PHP_VERSION, self::REQUIRES_PHP, '>=' );
	}

	/**
	 * Is `\XSpeed\Host` there to be called?
	 *
	 * Guarded on every use: it exists only once xSpeed is active, and only from
	 * the release that introduced it, so an older xSpeed is the same answer as
	 * no xSpeed at all.
	 *
	 * @return bool
	 */
	private static function host_available() {
		return class_exists( '\XSpeed\Host' );
	}

	/**
	 * Pull in get_plugins()/is_plugin_active(), which are admin-only.
	 *
	 * These get asked from front-end and AJAX requests where the file is not
	 * loaded; the callers above degrade rather than fatal if it is not there at
	 * all, because a missing answer must not take the site down.
	 *
	 * @return void
	 */
	private static function load_plugin_functions() {
		if ( function_exists( 'get_plugins' ) && function_exists( 'is_plugin_active' ) ) {
			return;
		}

		$file = ABSPATH . 'wp-admin/includes/plugin.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}

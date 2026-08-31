<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ThinkRank cross-promotion controller.
 *
 * Surfaces WPDeveloper's AI SEO plugin (ThinkRank) inside the Essential Addons
 * admin experience. The admin banner also carries xSpeed (performance) when the
 * site can take it — see banner_copy(); every other surface is ThinkRank only.
 * Every surface is:
 *   - native to WordPress admin,
 *   - clearly attributed to Essential Addons, so admins always know which
 *     plugin the suggestion comes from,
 *   - permanently dismissible — "Never show me again" hides EVERY ThinkRank
 *     surface for the whole installation, all users, forever,
 *   - scoped to relevant, high-intent moments — never a global banner, and
 *     never a redirect,
 *   - switchable off entirely via the EAEL_DISABLE_PROMOTIONS constant or the
 *     `eael/disable_promotions` filter (see promotions_disabled()).
 *
 * Install is delegated to the shared WPDeveloper_Plugin_Installer AJAX
 * endpoint (`wpdeveloper_install_plugin`), the same pipeline Quick Setup uses
 * to install Essential Blocks / Templately.
 *
 * @since 6.7.1
 */
class ThinkRank_Promotion {

	/**
	 * ThinkRank's WordPress.org slug — used by the shared installer.
	 *
	 * NOTE: confirm the final wp.org slug before release. All install CTAs
	 * across every surface read from this one constant.
	 */
	const SLUG = 'thinkrank';

	/**
	 * ThinkRank main-file basename — used to detect an active install.
	 * Verified against wp.org (ThinkRank – AI SEO Assistant).
	 */
	const ACTIVE_BASENAMES = [ 'thinkrank/thinkrank.php' ];

	/**
	 * ThinkRank top-level admin page (add_menu_page slug 'thinkrank').
	 */
	const ADMIN_PAGE = 'thinkrank';

	/**
	 * xSpeed top-level admin page, for the banner that offers xSpeed alone.
	 *
	 * The slug and basename themselves live on XSpeed_Setup, which owns every
	 * other xSpeed decision (including whether this site may be offered a page
	 * cache at all) — only this landing page is promo-specific.
	 *
	 * NOTE: confirm the final menu slug before release, as with self::SLUG.
	 */
	const XSPEED_ADMIN_PAGE = 'xspeed';

	/**
	 * Hash route within xSpeed's admin page for the image/lazy-load settings —
	 * where the Speed Check widget's "Optimize images" CTA lands, rather than
	 * dropping the user on the dashboard to find it themselves.
	 */
	const XSPEED_IMAGES_ROUTE = '#/performance/lazy';

	/**
	 * How long "Maybe later" / "Skip for 30 days" hides the promo for, in seconds.
	 */
	const SNOOZE_DURATION = 30 * DAY_IN_SECONDS;

	/**
	 * Site option — ThinkRank's permanent "Never show me again". When set,
	 * every ThinkRank surface is hidden for all users of this installation,
	 * forever. xSpeed has its own; see state_key(), which derives both.
	 *
	 * Kept as a constant because it is the key EA already shipped, and the one
	 * outside code would look for.
	 */
	const NEVER_SHOW_OPTION = 'eael_thinkrank_never_show';

	/**
	 * Site option — timestamp until which ThinkRank is skipped site-wide
	 * ("Skip for 30 days" on the EA Dashboard banner). Per plugin; see
	 * NEVER_SHOW_OPTION.
	 */
	const SKIP_UNTIL_OPTION = 'eael_thinkrank_skip_until';

	/**
	 * Transient holding the unminified-asset count shown in the Speed Check
	 * widget. Cached because producing it walks the filesystem; see
	 * unminified_asset_count().
	 */
	const UNMINIFIED_TRANSIENT = 'eael_xspeed_unminified_assets';

	/**
	 * How long that count stays cached.
	 */
	const UNMINIFIED_TTL = 12 * HOUR_IN_SECONDS;

	/**
	 * Transient holding the last front-page TTFB measurement, and option
	 * holding the last one taken while the page cache was OFF — the "before"
	 * the Speed Check widget compares against once caching is on.
	 */
	const TTFB_TRANSIENT = 'eael_xspeed_ttfb';
	const TTFB_BASELINE_OPTION = 'eael_xspeed_ttfb_uncached';
	const TTFB_TTL = 6 * HOUR_IN_SECONDS;

	/**
	 * Transient holding the count of images not yet in a modern format.
	 */
	const LEGACY_IMAGES_TRANSIENT = 'eael_xspeed_legacy_images';

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		// Surface 3 — WP Dashboard "SEO Check" widget.
		add_action( 'wp_dashboard_setup', [ $this, 'register_dashboard_widget' ] );

		// Attributed, dismissible banner on EA's own pages + content list screens.
		add_action( 'admin_notices', [ $this, 'render_dashboard_banner' ] );
		// On the EA Dashboard (toplevel_page_eael-settings) EA strips all
		// admin_notices and re-dispatches its own via `eael_admin_notices`, so
		// the banner is registered there too. That hook only fires on the EA
		// Dashboard, so this never double-renders on other screens.
		add_action( 'eael_admin_notices', [ $this, 'render_dashboard_banner' ] );
		add_action( 'wp_ajax_eael_thinkrank_dismiss', [ $this, 'ajax_dismiss_banner' ] );
		add_action( 'wp_ajax_eael_thinkrank_snooze', [ $this, 'ajax_snooze_banner' ] );
		add_action( 'wp_ajax_eael_thinkrank_never_show', [ $this, 'ajax_never_show' ] );
		add_action( 'wp_ajax_eael_thinkrank_skip', [ $this, 'ajax_skip' ] );

		// Surface 5 — Gutenberg editor "Configure SEO" document panel.
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_gutenberg_panel' ] );
	}

	/**
	 * Global kill switch for ALL Essential Addons promotional surfaces.
	 *
	 * Operators managing fleets can disable every promo surface for an entire
	 * installation, without touching per-user or per-site dismissals:
	 *
	 *     // wp-config.php
	 *     define( 'EAEL_DISABLE_PROMOTIONS', true );
	 *
	 *     // or from an mu-plugin
	 *     add_filter( 'eael/disable_promotions', '__return_true' );
	 *
	 * Checked per-surface (not once in the constructor) so late-registered
	 * filters are still honored.
	 */
	public static function promotions_disabled() {
		if ( defined( 'EAEL_DISABLE_PROMOTIONS' ) && EAEL_DISABLE_PROMOTIONS ) {
			return true;
		}

		return (bool) apply_filters( 'eael/disable_promotions', false );
	}

	/**
	 * Should any editor-context promo render for the current user?
	 */
	private function can_promote() {
		return ! $this->is_thinkrank_active() && ! $this->is_hidden() && current_user_can( 'install_plugins' );
	}

	/**
	 * Enqueue the build-free Gutenberg panel (uses the wp.* editor globals).
	 */
	public function enqueue_gutenberg_panel() {
		if ( ! $this->can_promote() ) {
			return;
		}

		wp_enqueue_script(
			'eael-thinkrank-gutenberg',
			EAEL_PLUGIN_URL . 'assets/admin/js/thinkrank-gutenberg.js',
			[ 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-i18n' ],
			defined( 'EAEL_PLUGIN_VERSION' ) ? EAEL_PLUGIN_VERSION : false,
			true
		);

		wp_localize_script( 'eael-thinkrank-gutenberg', 'eaelThinkRank', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'essential-addons-elementor' ),
			'slug'    => self::SLUG,
			'openUrl' => admin_url( 'admin.php?page=' . self::ADMIN_PAGE ),
		] );

		wp_register_style( 'eael-thinkrank-gb', false );
		wp_enqueue_style( 'eael-thinkrank-gb' );
		wp_add_inline_style( 'eael-thinkrank-gb',
			'.eael-tr-gb__desc{font-size:12.5px;line-height:1.5;color:#3c434a;margin:0 0 10px;}'
			. '.eael-tr-gb__err{font-size:12px;color:#d63638;margin:0 0 8px;}'
			. '.eael-tr-gb__cta.components-button.is-primary{background:#4451ff;justify-content:center;width:100%;}'
			. '.eael-tr-gb__cta.components-button.is-primary:hover:not(:disabled){background:#3742d6;}'
			. '.eael-tr-gb__later.components-button.is-link{display:block;margin:8px 0 2px;color:#50575e;font-size:12px;}'
		);
	}

	/**
	 * Every plugin this class can promote, in banner order.
	 *
	 * Each has its OWN dismiss/snooze/never/skip state — skipping one must
	 * never silence the other. See state_key().
	 *
	 * @return array
	 */
	private static function promoted_plugins() {
		return [ self::SLUG, XSpeed_Setup::SLUG ];
	}

	/**
	 * Option / user-meta key holding one plugin's promo state.
	 *
	 * Deliberately derived from the slug rather than listed per plugin,
	 * because for 'thinkrank' the pattern reproduces the four key names EA
	 * already shipped, byte for byte:
	 *
	 *     eael_thinkrank_dismissed      eael_thinkrank_never_show
	 *     eael_thinkrank_snoozed_until  eael_thinkrank_skip_until
	 *
	 * That is the whole backward-compatibility story: a site that dismissed or
	 * skipped the ThinkRank-only banner shipped in an earlier release keeps
	 * exactly that state under exactly those keys, while xSpeed starts clean on
	 * its own `eael_xspeed_*` keys — so those users are still eligible for the
	 * xSpeed banner, which is the point.
	 *
	 * @param string $plugin Slug, one of promoted_plugins().
	 * @param string $what   dismissed | snoozed | never | skip.
	 * @return string
	 */
	private static function state_key( $plugin, $what ) {
		$suffix = [
			'dismissed' => '_dismissed',
			'snoozed'   => '_snoozed_until',
			'never'     => '_never_show',
			'skip'      => '_skip_until',
		];

		return 'eael_' . $plugin . $suffix[ $what ];
	}

	/**
	 * Normalize a plugin slug, defaulting to ThinkRank.
	 *
	 * Every state method defaults to ThinkRank rather than requiring a slug:
	 * the Gutenberg panel and the dashboard widget are ThinkRank-only surfaces
	 * and call these with no argument, and the AJAX endpoints are hit by JS
	 * from an earlier release that sends no plugin at all. In both cases
	 * "unspecified" has always meant ThinkRank, and must keep meaning it.
	 *
	 * @param string $plugin
	 * @return string
	 */
	private static function plugin_or_default( $plugin ) {
		$plugin = sanitize_key( (string) $plugin );

		return in_array( $plugin, self::promoted_plugins(), true ) ? $plugin : self::SLUG;
	}

	/**
	 * Has the current user permanently dismissed this plugin's promo banner?
	 *
	 * @param string $plugin Slug; defaults to ThinkRank.
	 */
	public function is_dismissed( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );

		return (bool) get_user_meta( get_current_user_id(), self::state_key( $plugin, 'dismissed' ), true );
	}

	/**
	 * Has the current user snoozed this plugin's promo via "Maybe later", and
	 * is that snooze still running? Unlike is_dismissed() this expires on its own.
	 *
	 * @param string $plugin Slug; defaults to ThinkRank.
	 */
	public function is_snoozed( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );
		$until  = (int) get_user_meta( get_current_user_id(), self::state_key( $plugin, 'snoozed' ), true );

		return $until > time();
	}

	/**
	 * Has ANY admin permanently hidden this plugin's promo for the whole site?
	 *
	 * @param string $plugin Slug; defaults to ThinkRank.
	 */
	public function is_never_shown( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );

		return (bool) get_option( self::state_key( $plugin, 'never' ) );
	}

	/**
	 * Is this plugin's site-wide "Skip for 30 days" snooze still running?
	 *
	 * @param string $plugin Slug; defaults to ThinkRank.
	 */
	public function is_skipped( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );

		return (int) get_option( self::state_key( $plugin, 'skip' ) ) > time();
	}

	/**
	 * Is this plugin's promo hidden right now, for any reason? Site-wide
	 * switches (kill switch, never-show, skip) win over per-user state.
	 *
	 * The kill switch is the only part that is not per-plugin: it turns off
	 * every promotional surface EA has, by design.
	 *
	 * @param string $plugin Slug; defaults to ThinkRank.
	 */
	public function is_hidden( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );

		return self::promotions_disabled()
			|| $this->is_never_shown( $plugin )
			|| $this->is_skipped( $plugin )
			|| $this->is_dismissed( $plugin )
			|| $this->is_snoozed( $plugin );
	}

	/**
	 * Which plugins is this dismiss/snooze/never/skip request about?
	 *
	 * The banner sends the slugs it actually showed, so dismissing a combined
	 * banner silences both plugins while dismissing a single-plugin banner
	 * silences only that one. Anything unrecognized — including the empty
	 * request sent by the pre-xSpeed Gutenberg panel — falls back to ThinkRank,
	 * which is what "no plugin specified" meant when those callers were written.
	 *
	 * @return array Slugs, always non-empty.
	 */
	private static function requested_plugins() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- every caller runs check_ajax_referer() first.
		$raw = isset( $_POST['plugins'] ) ? sanitize_text_field( wp_unslash( $_POST['plugins'] ) ) : '';

		$requested = array_map( 'sanitize_key', explode( ',', $raw ) );
		$valid     = array_values( array_intersect( self::promoted_plugins(), $requested ) );

		return empty( $valid ) ? [ self::SLUG ] : $valid;
	}

	/**
	 * Permanent per-user dismiss of the banner, for the plugins it showed.
	 */
	public function ajax_dismiss_banner() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}
		foreach ( self::requested_plugins() as $plugin ) {
			update_user_meta( get_current_user_id(), self::state_key( $plugin, 'dismissed' ), 1 );
		}
		wp_send_json_success();
	}

	/**
	 * Per-user snooze of the banner via "Maybe later". Stores the timestamp the
	 * promo becomes eligible again, so it lapses on its own without any cleanup.
	 */
	public function ajax_snooze_banner() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}
		foreach ( self::requested_plugins() as $plugin ) {
			update_user_meta( get_current_user_id(), self::state_key( $plugin, 'snoozed' ), time() + self::SNOOZE_DURATION );
		}
		wp_send_json_success();
	}

	/**
	 * Site-wide, permanent "Never show me again". One click by any admin hides
	 * every surface for the plugins the banner showed — notices, and for
	 * ThinkRank the Gutenberg panel and dashboard widget too — for all users of
	 * this installation, forever. The other plugin's banner is untouched.
	 */
	public function ajax_never_show() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		foreach ( self::requested_plugins() as $plugin ) {
			update_option( self::state_key( $plugin, 'never' ), 1, true );
		}
		wp_send_json_success();
	}

	/**
	 * Site-wide "Skip for 30 days" from the EA Dashboard banner. The promo may
	 * come back after SNOOZE_DURATION — unless "Never show me again" was used,
	 * which always wins.
	 */
	public function ajax_skip() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		foreach ( self::requested_plugins() as $plugin ) {
			update_option( self::state_key( $plugin, 'skip' ), time() + self::SNOOZE_DURATION, true );
		}
		wp_send_json_success();
	}

	/**
	 * Which context should the banner render in?
	 *  - 'ea'      : Essential Addons' own admin pages (page slug starts eael).
	 *  - 'content' : Posts / Pages / CPT list screens.
	 *  - ''        : nowhere (keeps it off unrelated admin screens).
	 *
	 * Scoped to LIST screens (screen base 'edit') on purpose: classic admin
	 * notices don't render reliably inside the block editor (post.php), and the
	 * editor itself is already covered by the Gutenberg "Configure SEO" panel.
	 */
	private function banner_context() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( 0 === strpos( $page, 'eael' ) ) {
			return 'ea';
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && 'edit' === $screen->base && ! empty( $screen->post_type ) ) {
			$obj = get_post_type_object( $screen->post_type );
			if ( $obj && ! empty( $obj->public ) && 'attachment' !== $screen->post_type ) {
				return 'content';
			}
		}

		return '';
	}

	/**
	 * Dismissible, attributed prompt for whichever of ThinkRank / xSpeed this
	 * site does not have yet — see banner_copy() for which that is.
	 *
	 * Secondary action depends on context:
	 *  - 'content' (Posts/Pages/CPT list screens): "Never show me again" —
	 *    permanent, SITE-WIDE. One click hides every promo surface for all
	 *    users of this installation, forever.
	 *  - 'ea' (EA Dashboard): "Skip for 30 days" — site-wide snooze; the promo
	 *    may return after 30 days unless never-show was used.
	 *
	 * Never global; see banner_context() for scope.
	 */
	public function render_dashboard_banner() {
		// Kill switch only. Per-plugin dismiss/skip state is NOT checked here:
		// each plugin carries its own, and banner_copy() drops just the ones
		// that are hidden rather than suppressing the whole banner.
		if ( self::promotions_disabled() ) {
			return;
		}
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$context = $this->banner_context();
		if ( ! $context ) {
			return;
		}

		// Empty when nothing is left to offer — every plugin is either already
		// in play, off the table on this site, or individually dismissed.
		$copy = $this->banner_copy();
		if ( ! $copy ) {
			return;
		}

		$later_action = 'ea' === $context ? 'eael_thinkrank_skip' : 'eael_thinkrank_never_show';
		$later_label  = 'ea' === $context
			? __( 'Skip', 'essential-addons-for-elementor-lite' )
			: __( 'Never show me again', 'essential-addons-for-elementor-lite' );

		$nonce = wp_create_nonce( 'essential-addons-elementor' );
		$open  = esc_url( $copy['open'] );
		?>
		<div class="notice eael-tr-banner" data-slug="<?php echo esc_attr( $copy['slugs'] ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-open="<?php echo $open; ?>">
			<div class="eael-tr-banner__icon" aria-hidden="true">
				<img src="<?php echo esc_url( $copy['icon'] ); ?>" width="<?php echo esc_attr( $copy['icon_w'] ); ?>" height="<?php echo esc_attr( $copy['icon_h'] ); ?>" alt="">
			</div>
			<div class="eael-tr-banner__body">
				<strong class="eael-tr-banner__title"><?php echo esc_html( $copy['title'] ); ?></strong>
				<span class="eael-tr-banner__desc"><?php echo esc_html( $copy['desc'] ); ?></span>
			</div>
			<div class="eael-tr-banner__actions">
				<button type="button" class="button button-primary eael-tr-banner__install"><?php echo esc_html( $copy['cta'] ); ?></button>
				<button type="button" class="eael-tr-banner__later" data-action="<?php echo esc_attr( $later_action ); ?>"><?php echo esc_html( $later_label ); ?></button>
			</div>
		</div>
		<?php
		$this->banner_assets( $copy );
	}

	/**
	 * What the banner promotes right now — or an empty array when nothing is
	 * left to promote and the banner should not render at all.
	 *
	 * ThinkRank (SEO) and xSpeed (performance) are decided independently: each
	 * is on offer when the site does not have it AND that plugin's own state
	 * (see state_key()) has not been dismissed, snoozed, skipped or
	 * never-shown. Whatever survives decides the banner — both, one, or none —
	 * and the copy, icon and CTA follow it, so the banner never promises
	 * something it will not install.
	 *
	 * The independence is the point: skipping the combined banner's xSpeed half
	 * must leave ThinkRank's own banner free to appear later, and a site that
	 * dismissed the ThinkRank-only banner shipped in an earlier release is
	 * still eligible for the xSpeed one.
	 *
	 * 'slugs' is what the install button walks, in order: wp.org slugs, comma
	 * separated, one AJAX install each.
	 *
	 * @return array
	 */
	private function banner_copy() {
		$offer = [];

		// ThinkRank: on offer while it is not running. An installed-but-
		// deactivated copy still counts as on offer — the CTA activates it.
		if ( ! $this->is_thinkrank_active() && ! $this->is_hidden( self::SLUG ) ) {
			$offer[] = self::SLUG;
		}

		// xSpeed: can_install() is false once xSpeed is on disk at all, or when
		// this site cannot meet its PHP/WP floor. An incumbent page cache does
		// NOT suppress the offer — it only means before_activation() installs
		// xSpeed with its own page cache off, leaving the incumbent's
		// advanced-cache.php untouched.
		if ( XSpeed_Setup::can_install() && ! $this->is_hidden( XSpeed_Setup::SLUG ) ) {
			$offer[] = XSpeed_Setup::SLUG;
		}

		$both = [ self::SLUG, XSpeed_Setup::SLUG ];

		if ( $offer === $both ) {
			return [
				'slugs'      => implode( ',', $offer ),
				'icon'       => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/thinkrankxspeed.svg',
				'icon_w'     => 40,
				'icon_h'     => 40,
				'open'       => admin_url( 'admin.php?page=' . self::ADMIN_PAGE ),
				'title'      => __( 'Get found on Google & AI answers - and load fast', 'essential-addons-for-elementor-lite' ),
				'desc'       => __( 'Let AI handle titles, meta, schema & sitemaps, while smart caching and asset optimization keep every page quick. Free with ThinkRank & xSpeed.', 'essential-addons-for-elementor-lite' ),
				'cta'        => __( 'Enable SEO & Speed', 'essential-addons-for-elementor-lite' ),
				'installing' => __( 'Enabling SEO & Speed…', 'essential-addons-for-elementor-lite' ),
				'done'       => __( 'Enabled! Opening SEO Tool…', 'essential-addons-for-elementor-lite' ),
			];
		}

		if ( [ self::SLUG ] === $offer ) {
			return [
				'slugs'      => self::SLUG,
				'icon'       => EAEL_PLUGIN_URL . 'assets/admin/images/thinkrank/icon.svg',
				'icon_w'     => 40,
				'icon_h'     => 40,
				'open'       => admin_url( 'admin.php?page=' . self::ADMIN_PAGE ),
				'title'      => __( 'Get found on Google & AI answers - configure your SEO', 'essential-addons-for-elementor-lite' ),
				'desc'       => __( 'Let AI handle titles, meta, schema, LLM answers & sitemaps so every page ranks. Free with ThinkRank.', 'essential-addons-for-elementor-lite' ),
				'cta'        => __( 'Enable SEO Tool', 'essential-addons-for-elementor-lite' ),
				'installing' => __( 'Enabling SEO Tool…', 'essential-addons-for-elementor-lite' ),
				'done'       => __( 'Enabled! Opening SEO Tool…', 'essential-addons-for-elementor-lite' ),
			];
		}

		if ( [ XSpeed_Setup::SLUG ] === $offer ) {
			return [
				'slugs'      => XSpeed_Setup::SLUG,
				'icon'       => EAEL_PLUGIN_URL . 'assets/admin/images/xspeed/icon.svg',
				// The xSpeed mark is taller than it is wide (223x256); squaring
				// it here would stretch it.
				'icon_w'     => 40,
				'icon_h'     => 40,
				'open'       => admin_url( 'admin.php?page=' . self::XSPEED_ADMIN_PAGE ),
				'title'      => __( 'Speed up every page you build', 'essential-addons-for-elementor-lite' ),
				'desc'       => __( 'Smart caching, asset optimization & CDN keep your pages fast and lift Core Web Vitals - without touching your design. Free with xSpeed.', 'essential-addons-for-elementor-lite' ),
				'cta'        => __( 'Enable xSpeed', 'essential-addons-for-elementor-lite' ),
				'installing' => __( 'Enabling xSpeed…', 'essential-addons-for-elementor-lite' ),
				'done'       => __( 'Enabled! Opening xSpeed…', 'essential-addons-for-elementor-lite' ),
			];
		}

		return [];
	}

	/**
	 * Inline styles + behaviour for the banner. Reuses the shared installer
	 * AJAX for install and the dismiss endpoint for permanent dismissal.
	 *
	 * The installer endpoint takes one slug per request, so a banner offering
	 * both plugins walks its slugs one at a time and stops at the first
	 * failure. A partial install is not a dead end: the next page load
	 * recomputes banner_copy() and offers whatever is still missing.
	 *
	 * @param array $copy Return value of banner_copy().
	 */
	private function banner_assets( $copy ) {
		// JSON, not esc_js(): these labels contain "&", and esc_js() would
		// HTML-encode it into an &amp; that a <script> block never decodes
		// back. wp_json_encode() emits its own quotes — hence none below.
		$flags      = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
		$installing = wp_json_encode( $copy['installing'], $flags );
		$done       = wp_json_encode( $copy['done'], $flags );
		$failed     = wp_json_encode( __( 'Could not enable automatically. Try Plugins → Add New.', 'essential-addons-for-elementor-lite' ), $flags );
		$label      = wp_json_encode( $copy['cta'], $flags );
		?>
		<style>
			.eael-tr-banner.notice { display:flex; align-items:center; gap:16px; padding:14px 16px; border-left-color:#4451ff; position:relative; }
			.eael-tr-banner__icon img { display:block; border-radius:8px; }
			.eael-tr-banner__body { display:flex; flex-direction:column; gap:2px; min-width:0; }
			.eael-tr-banner__title { font-size:14px; color:#1d2327; }
			.eael-tr-banner__desc { font-size:13px; color:#50575e; }
			.eael-tr-banner__actions { display:flex; align-items:center; gap:10px; margin-left:auto; flex:none; }
			.eael-tr-banner__install.button-primary { background:#4451ff; border-color:#4451ff; box-shadow:none; text-shadow:none; }
			.eael-tr-banner__install.button-primary:hover { background:#3742d6; border-color:#3742d6; }
			.eael-tr-banner__later { background:none; border:none; color:#50575e; cursor:pointer; font-size:13px; text-decoration:underline; }
		</style>
		<script>
		( function () {
			var el = document.querySelector( '.eael-tr-banner' );
			if ( ! el || el.dataset.bound ) { return; }
			el.dataset.bound = '1';
			function post( action, slug ) {
				var b = new URLSearchParams();
				b.append( 'action', action );
				b.append( 'security', el.dataset.nonce );
				if ( slug ) { b.append( 'slug', slug ); }
				// Which plugins this banner actually showed. Skip/never-show
				// applies to exactly those, so silencing a combined banner
				// never silences a plugin the user was not offered.
				else { b.append( 'plugins', el.dataset.slug ); }
				return window.fetch( window.ajaxurl, { method:'POST', credentials:'same-origin', headers:{ 'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8' }, body:b.toString() } ).then( function(r){ return r.json(); } );
			}
			var later = el.querySelector( '.eael-tr-banner__later' );
			later.addEventListener( 'click', function () { post( later.dataset.action || 'eael_thinkrank_snooze' ); el.parentNode && el.parentNode.removeChild( el ); } );
			el.querySelector( '.eael-tr-banner__install' ).addEventListener( 'click', function () {
				var btn = this; btn.setAttribute( 'disabled', 'disabled' ); btn.textContent = <?php echo $installing; ?>;
				var slugs = ( el.dataset.slug || '' ).split( ',' ).filter( Boolean );
				// One install at a time, carrying the first error forward: the
				// endpoint takes a single slug, and a cache install must not
				// start while the previous activation is still settling.
				slugs.reduce( function ( chain, slug ) {
					return chain.then( function ( err ) {
						if ( err ) { return err; }
						return post( 'wpdeveloper_install_plugin', slug ).then( function ( res ) {
							if ( res && res.success ) { return ''; }
							return ( res && res.data ) ? res.data : <?php echo $failed; ?>;
						} );
					} );
				}, window.Promise.resolve( '' ) ).then( function ( err ) {
					if ( ! err ) { btn.textContent = <?php echo $done; ?>; window.setTimeout( function () { window.location.href = el.dataset.open; }, 800 ); }
					else { btn.removeAttribute( 'disabled' ); btn.textContent = <?php echo $label; ?>; window.alert( err ); }
				} ).catch( function () { btn.removeAttribute( 'disabled' ); btn.textContent = <?php echo $label; ?>; window.alert( <?php echo $failed; ?> ); } );
			} );
		} )();
		</script>
		<?php
	}

	/**
	 * Register one dashboard widget per plugin — "SEO Check" (ThinkRank) and
	 * "Speed Check" (xSpeed).
	 *
	 * The two are fully independent: each has its own widget id, its own
	 * never-show state, and its own eligibility. Removing or silencing one
	 * leaves the other alone.
	 *
	 * Gated to users who can install plugins so the CTA is actionable. Being
	 * real dashboard widgets, both are removable by the user via Screen Options.
	 *
	 * A promo (not-installed) state honors every opt-out — kill switch,
	 * site-wide never-show/skip and per-user dismiss/snooze. Once the plugin is
	 * actually active its widget is functional, not promotional, so it stays.
	 */
	public function register_dashboard_widget() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		foreach ( self::promoted_plugins() as $plugin ) {
			if ( ! $this->widget_eligible( $plugin ) ) {
				continue;
			}

			$spec = $this->widget_spec( $plugin );

			// A closure, not [ $this, 'render_dashboard_widget' ]: WP invokes a
			// dashboard callback with ( $screen, $box ), so a method taking a
			// plugin slug would receive the screen object instead.
			wp_add_dashboard_widget(
				$spec['id'],
				$spec['widget_title'],
				function () use ( $plugin ) {
					$this->render_dashboard_widget( $plugin );
				}
			);
		}
	}

	/**
	 * Should this plugin's dashboard widget exist at all?
	 *
	 * Active plugin  → yes, always: the widget is functional at that point, not
	 *                  promotional, and silencing a promo should not take away
	 *                  a working panel.
	 * Not active     → only while the plugin is genuinely on offer and this
	 *                  user/site has not opted out of it.
	 *
	 * @param string $plugin Slug.
	 * @return bool
	 */
	private function widget_eligible( $plugin ) {
		// "Never show me again" is an explicit, permanent request to remove
		// this widget — the active-state Speed Check offers it too, so it has
		// to win even when the plugin is running. Skip/snooze/dismiss are only
		// ever about the promo, and stay below.
		if ( $this->is_never_shown( $plugin ) ) {
			return false;
		}

		if ( $this->is_plugin_running( $plugin ) ) {
			return true;
		}

		// xSpeed additionally has to clear the page-cache-safety check: a site
		// that already has a page cache must never be handed a second one.
		//
		// Two ways to be eligible, because the CTA does two different things:
		// install a copy that isn't here (can_install), or switch back on one
		// that is (can_reactivate). Gating on the install check alone made the Speed
		// Check widget vanish for anyone who deactivated xSpeed, while the SEO
		// Check widget stayed put — and can_list() does not rescue that case,
		// because a deactivated xSpeed's own leftover drop-in reads to the
		// generic detector as a foreign cache occupying the field.
		if ( XSpeed_Setup::SLUG === $plugin
			&& ! XSpeed_Setup::can_install()
			&& ! XSpeed_Setup::can_reactivate() ) {
			return false;
		}

		return ! $this->is_hidden( $plugin );
	}

	/**
	 * Is this plugin installed AND active?
	 *
	 * @param string $plugin Slug.
	 * @return bool
	 */
	public function is_plugin_running( $plugin ) {
		$plugin = self::plugin_or_default( $plugin );

		if ( XSpeed_Setup::SLUG === $plugin ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return is_plugin_active( XSpeed_Setup::BASENAME );
		}

		return $this->is_thinkrank_active();
	}

	/**
	 * Is ThinkRank installed AND active?
	 */
	public function is_thinkrank_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach ( self::ACTIVE_BASENAMES as $basename ) {
			if ( is_plugin_active( $basename ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Everything that differs between the two widgets, in one place: ids, copy,
	 * accent colour and the glyph in the ring.
	 *
	 * Widget ids are load-bearing and must not be renamed — WordPress keys
	 * "hidden via Screen Options" on them per user, so a rename silently
	 * un-hides the widget for everyone who had already dismissed it.
	 * 'eael_thinkrank_seo_check' is the id EA already shipped.
	 *
	 * @param string $plugin Slug.
	 * @return array
	 */
	private function widget_spec( $plugin ) {
		if ( XSpeed_Setup::SLUG === $plugin ) {
			return [
				'slug'          => XSpeed_Setup::SLUG,
				'id'            => 'eael_xspeed_speed_check',
				// Raw HTML on purpose: WP echoes a dashboard widget title
				// unescaped inside its <h2>, which is how the icon gets in.
				// Wrapped in ONE element on purpose: core styles h2.hndle as a
				// flex container with space-between, so a bare "<img> + text"
				// title becomes two flex items and the words get shoved to the
				// far right of the header.
				'widget_title'  => '<span class="eael-xs-title"><img class="eael-xs-title-icon" src="'
					. esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/xspeed/icon.svg' ) . '" width="16" height="18" alt="" />'
					. esc_html__( 'Speed Check', 'essential-addons-for-elementor-lite' ) . '</span>',
				'open_url'      => admin_url( 'admin.php?page=' . self::XSPEED_ADMIN_PAGE ),
				// The prompt state uses the two-column "check" layout rather
				// than the centred ring ThinkRank uses; see render_prompt_state().
				'layout'        => 'check',
				'prompt_title'  => __( 'Improve your website speed', 'essential-addons-for-elementor-lite' ),
				'ai_line'       => __( "AI finds what's slowing you down and fixes it.", 'essential-addons-for-elementor-lite' ),
				// Already on disk? Then the button activates rather than
				// installs, and the caption should not claim otherwise.
				'note'          => XSpeed_Setup::is_on_disk()
					? __( 'Activates xSpeed Cache — already installed, free from WPDeveloper', 'essential-addons-for-elementor-lite' )
					: __( 'Installs xSpeed Cache — free, from WPDeveloper', 'essential-addons-for-elementor-lite' ),
				'trust'         => __( 'Trusted by 6M+ websites', 'essential-addons-for-elementor-lite' ),
				'prompt_desc'   => __( 'xSpeed caches your pages, trims unused CSS & JS and serves assets from a CDN, then shows what it saved.', 'essential-addons-for-elementor-lite' ),
				'cta'           => __( 'Fix this now', 'essential-addons-for-elementor-lite' ),
				'installing'    => __( 'Enabling xSpeed…', 'essential-addons-for-elementor-lite' ),
				'active_title'  => __( 'xSpeed is active', 'essential-addons-for-elementor-lite' ),
				'active_desc'   => __( 'Open xSpeed to see your cache status and what each optimization saved.', 'essential-addons-for-elementor-lite' ),
				'active_cta'    => __( 'Open xSpeed', 'essential-addons-for-elementor-lite' ),
				'failed'        => __( 'Could not enable automatically. Please try from Plugins → Add New.', 'essential-addons-for-elementor-lite' ),
				'modifier'      => 'eael-tr-widget--xspeed',
				// Lightning bolt — performance.
				'glyph'         => '<svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 4.5 13.5H11l-1 8.5 8.5-11.5H12l1-8.5Z"></path></svg>',
			];
		}

		return [
			'slug'          => self::SLUG,
			'id'            => 'eael_thinkrank_seo_check',
			'widget_title'  => esc_html__( 'SEO Check', 'essential-addons-for-elementor-lite' ),
			'open_url'      => admin_url( 'admin.php?page=' . self::ADMIN_PAGE ),
			'layout'        => 'ring',
			'prompt_title'  => __( 'Analyze your SEO with AI', 'essential-addons-for-elementor-lite' ),
			'prompt_desc'   => __( "ThinkRank's AI reviews your titles, meta, schema and readability, then shows the quick wins. See how your pages score.", 'essential-addons-for-elementor-lite' ),
			'cta'           => __( 'Analyze my SEO', 'essential-addons-for-elementor-lite' ),
			'installing'    => __( 'Analyzing… enabling ThinkRank', 'essential-addons-for-elementor-lite' ),
			'active_title'  => __( 'ThinkRank is active', 'essential-addons-for-elementor-lite' ),
			'active_desc'   => __( 'Open ThinkRank to see your AI SEO score and page-by-page fixes.', 'essential-addons-for-elementor-lite' ),
			'active_cta'    => __( 'Open ThinkRank', 'essential-addons-for-elementor-lite' ),
			'failed'        => __( 'Could not enable automatically. Please try from Plugins → Add New.', 'essential-addons-for-elementor-lite' ),
			'modifier'      => '',
			// Magnifier with a tick — an SEO audit.
			'glyph'         => '<svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="7"></circle><path d="m21 21-4.3-4.3"></path><path d="M7.4 10.2 9.4 12.2 12.8 8.6"></path></svg>',
		];
	}

	/**
	 * Render one widget body.
	 *
	 * @param string $plugin Slug; defaults to ThinkRank so any pre-existing
	 *                       direct caller keeps the behaviour it had.
	 */
	public function render_dashboard_widget( $plugin = self::SLUG ) {
		$plugin = self::plugin_or_default( $plugin );
		$spec   = $this->widget_spec( $plugin );

		$this->widget_styles();

		printf( '<div class="%s">', esc_attr( trim( 'eael-tr-widget ' . $spec['modifier'] ) ) );

		if ( $this->is_plugin_running( $plugin ) ) {
			$this->render_active_state( $spec );
		} else {
			$this->render_prompt_state( $spec );
		}

		echo '</div>';
	}

	/**
	 * Not-installed prompt — the acquisition state. The primary CTA runs the
	 * check the widget is named for, which installs the plugin that performs it.
	 *
	 * @param array $spec Return value of widget_spec().
	 */
	private function render_prompt_state( $spec ) {
		if ( 'check' === $spec['layout'] ) {
			$this->render_check_prompt( $spec );
			$this->widget_script( $spec );

			return;
		}

		$nonce = wp_create_nonce( 'essential-addons-elementor' );
		?>
		<div class="eael-tr-widget__body eael-tr-widget__body--center">
			<div class="eael-tr-ring" aria-hidden="true">
				<?php echo $spec['glyph']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup from widget_spec(). ?>
			</div>
			<div class="eael-tr-widget__title"><?php echo esc_html( $spec['prompt_title'] ); ?></div>
			<p class="eael-tr-widget__desc">
				<?php echo esc_html( $spec['prompt_desc'] ); ?>
			</p>
			<button type="button" class="button button-primary eael-tr-cta eael-tr-install"
				data-slug="<?php echo esc_attr( $spec['slug'] ); ?>"
				data-nonce="<?php echo esc_attr( $nonce ); ?>">
				<span class="eael-tr-cta__label"><?php echo esc_html( $spec['cta'] ); ?></span>
			</button>
			<div class="eael-tr-notice" role="status" style="display:none;"></div>
			<button type="button" class="button-link eael-tr-never"
				data-plugins="<?php echo esc_attr( $spec['slug'] ); ?>"
				data-nonce="<?php echo esc_attr( $nonce ); ?>">
				<?php esc_html_e( 'Never show me again', 'essential-addons-for-elementor-lite' ); ?>
			</button>
		</div>
		<?php
		$this->widget_script( $spec );
	}

	/**
	 * The "Speed Check" prompt: what we actually found on this site, next to
	 * the one button that fixes it.
	 *
	 * Both findings are measured, not decorative — see page_cache_line() and
	 * unminified_asset_count(). A finding we cannot measure is dropped rather
	 * than guessed, which is why the list is built up instead of hardcoded.
	 *
	 * Keeps the .eael-tr-install / .eael-tr-never / .eael-tr-notice hooks the
	 * shared widget_script() binds to, so only the presentation differs.
	 *
	 * @param array $spec Return value of widget_spec().
	 */
	private function render_check_prompt( $spec ) {
		$nonce = wp_create_nonce( 'essential-addons-elementor' );

		$findings = [ $this->page_cache_line() ];

		$unminified = $this->unminified_asset_count();
		if ( $unminified > 0 ) {
			$findings[] = sprintf(
				/* translators: %s: number of unminified CSS and JS files found. */
				_n( '%s unminified CSS and JS file', '%s unminified CSS and JS files', $unminified, 'essential-addons-for-elementor-lite' ),
				number_format_i18n( $unminified )
			);
		}

		$findings = array_values( array_filter( $findings ) );
		?>
		<div class="eael-xs-check">
			<h3 class="eael-xs-check__heading"><?php echo esc_html( $spec['prompt_title'] ); ?></h3>
			<div class="eael-xs-check__cols">
				<div class="eael-xs-check__main">
					<ul class="eael-xs-check__list">
						<?php foreach ( $findings as $finding ) : ?>
							<li class="eael-xs-check__item"><?php echo esc_html( $finding ); ?></li>
						<?php endforeach; ?>
						<li class="eael-xs-check__item eael-xs-check__item--ai">
							<svg class="eael-xs-check__spark" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l1.9 5.3 5.3 1.9-5.3 1.9L12 16.9l-1.9-5.3-5.3-1.9 5.3-1.9L12 2.5zM18.5 15l.9 2.4 2.4.9-2.4.9-.9 2.4-.9-2.4-2.4-.9 2.4-.9.9-2.4z"></path></svg>
							<span><?php echo esc_html( $spec['ai_line'] ); ?></span>
						</li>
					</ul>
					<p class="eael-xs-check__trust"><?php echo esc_html( $spec['trust'] ); ?></p>
				</div>
				<div class="eael-xs-check__aside">
					<button type="button" class="eael-tr-cta eael-tr-install eael-xs-check__cta"
						data-slug="<?php echo esc_attr( $spec['slug'] ); ?>"
						data-nonce="<?php echo esc_attr( $nonce ); ?>">
						<span class="eael-tr-cta__label"><?php echo esc_html( $spec['cta'] ); ?></span>
					</button>
					<p class="eael-xs-check__note"><?php echo esc_html( $spec['note'] ); ?></p>
					<div class="eael-tr-notice" role="status" style="display:none;"></div>
					<!-- No .button-link here: this layout resets the chrome itself,
					     and core's class would also pull in the ring layout's
					     .eael-tr-never.button-link rule, which outranks ours. -->
					<button type="button" class="eael-tr-never eael-xs-check__never"
						data-plugins="<?php echo esc_attr( $spec['slug'] ); ?>"
						data-nonce="<?php echo esc_attr( $nonce ); ?>">
						<?php esc_html_e( 'Never show me again', 'essential-addons-for-elementor-lite' ); ?>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * xSpeed is running — show what it is actually doing, not a pitch.
	 *
	 * Two states, chosen by whether the page cache is genuinely serving
	 * (XSpeed_Setup::page_cache_live(), which verifies the drop-in rather than
	 * trusting activation):
	 *
	 *  - cache OFF: xSpeed is installed but idle. The site's current TTFB is
	 *    the "before" number, and the CTA finishes setup.
	 *  - cache ON:  caching is working. The TTFB is compared against the one
	 *    recorded while it was off, and the CTA moves on to the next win.
	 *
	 * Every number here is measured — see measure_ttfb() and
	 * legacy_image_count(). A measurement we could not take is left out of the
	 * sentence rather than filled in with a plausible one.
	 *
	 * @param array $spec Return value of widget_spec().
	 */
	private function render_xspeed_active( $spec ) {
		/**
		 * Which of the two active states to render.
		 *
		 * Filterable purely so the "caching is off" state can be previewed on a
		 * site where caching is on — EA's own installer enables page caching
		 * before activation, so that state is otherwise only reachable when
		 * xSpeed arrived by some other route, or when the drop-in write was
		 * refused. Drop this in an mu-plugin to see it:
		 *
		 *     add_filter( 'eael/xspeed_page_cache_live', '__return_false' );
		 *
		 * @param bool $live Whether xSpeed's page cache is verifiably serving.
		 */
		$live  = (bool) apply_filters( 'eael/xspeed_page_cache_live', XSpeed_Setup::page_cache_live() );
		$nonce = wp_create_nonce( 'essential-addons-elementor' );
		$ttfb  = $this->measure_ttfb( $live );

		if ( $live ) {
			$heading = __( 'Page cache on', 'essential-addons-for-elementor-lite' );

			// Only claim an improvement when there is a real "before" to
			// compare with, and it actually got faster.
			$before = (int) get_option( self::TTFB_BASELINE_OPTION );
			if ( $ttfb && $before > 0 && $ttfb['ms'] < $before ) {
				$heading = sprintf(
					/* translators: %s: current time to first byte, e.g. "210ms". */
					__( 'Page cache on — TTFB down to %s', 'essential-addons-for-elementor-lite' ),
					$this->format_ms( $ttfb['ms'] )
				);
			}

			$images = $this->legacy_image_count();
			$body   = $images > 0
				? sprintf(
					/* translators: %s: number of images not yet in a modern format. */
					_n(
						'%s image is still JPEG or PNG — the biggest win left.',
						'%s images are still JPEG or PNG — the biggest win left.',
						$images,
						'essential-addons-for-elementor-lite'
					),
					number_format_i18n( $images )
				)
				: __( 'Your images are already in a modern format — nothing big left to convert.', 'essential-addons-for-elementor-lite' );

			$this->render_active_shell( [
				'spec'     => $spec,
				'nonce'    => $nonce,
				// Straight to the image settings when that is what the CTA
				// offers; the plain dashboard when there is nothing to fix.
				'cta_url'  => $images > 0
					? $spec['open_url'] . self::XSPEED_IMAGES_ROUTE
					: $spec['open_url'],
				'dot'      => true,
				'heading'  => $heading,
				'measure'  => '',
				'metric'   => '',
				'body'     => $body,
				'ai'       => __( 'AI resizes on upload; existing images convert in the background.', 'essential-addons-for-elementor-lite' ),
				'cta'      => $images > 0
					? __( 'Optimize images', 'essential-addons-for-elementor-lite' )
					: __( 'Open xSpeed', 'essential-addons-for-elementor-lite' ),
				'foot_url' => $spec['open_url'],
				'foot'     => __( 'All xSpeed settings', 'essential-addons-for-elementor-lite' ),
			] );

			return;
		}

		// Cache off. The TTFB we just took is the honest "before" — remember it
		// so the cache-on state has something real to compare against.
		$metric = '';
		$measure = '';
		if ( $ttfb ) {
			$measure = sprintf(
				/* translators: %s: time to first byte, e.g. "840ms". */
				__( 'TTFB %s', 'essential-addons-for-elementor-lite' ),
				$this->format_ms( $ttfb['ms'] )
			);
			$metric = sprintf(
				/* translators: %s: how long ago the measurement was taken, e.g. "5 mins ago". */
				__( ' · measured %s', 'essential-addons-for-elementor-lite' ),
				$this->measured_ago( $ttfb['at'] )
			);
		}

		$this->render_active_shell( [
			'spec'     => $spec,
			'nonce'    => $nonce,
			'cta_url'  => $spec['open_url'],
			'dot'      => false,
			'heading'  => __( 'xSpeed is installed — caching is still off', 'essential-addons-for-elementor-lite' ),
			'measure'  => $measure,
			'metric'   => $metric,
			'body'     => '',
			'ai'       => __( 'Setup takes a minute — AI picks the settings.', 'essential-addons-for-elementor-lite' ),
			'cta'      => __( 'Configure xSpeed', 'essential-addons-for-elementor-lite' ),
			'foot_url' => '',
			'foot'     => __( 'Nothing is cached until setup finishes', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * Shared markup for both active states — same two-column shell as the
	 * promo, so one stylesheet covers all three.
	 *
	 * @param array $v Prepared view data; see render_xspeed_active().
	 */
	private function render_active_shell( $v ) {
		$spec = $v['spec'];
		?>
		<div class="eael-xs-check eael-xs-check--active">
			<h3 class="eael-xs-check__heading">
				<?php if ( $v['dot'] ) : ?>
					<span class="eael-xs-check__dot" aria-hidden="true"></span>
				<?php endif; ?>
				<?php echo esc_html( $v['heading'] ); ?>
			</h3>
			<div class="eael-xs-check__cols">
				<div class="eael-xs-check__main">
					<?php if ( '' !== $v['measure'] ) : ?>
						<p class="eael-xs-check__metric"><strong><?php echo esc_html( $v['measure'] ); ?></strong><?php echo esc_html( $v['metric'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $v['body'] ) : ?>
						<p class="eael-xs-check__body"><?php echo esc_html( $v['body'] ); ?></p>
					<?php endif; ?>
					<p class="eael-xs-check__ai">
						<svg class="eael-xs-check__spark" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l1.9 5.3 5.3 1.9-5.3 1.9L12 16.9l-1.9-5.3-5.3-1.9 5.3-1.9L12 2.5zM18.5 15l.9 2.4 2.4.9-2.4.9-.9 2.4-.9-2.4-2.4-.9 2.4-.9.9-2.4z"></path></svg>
						<span><?php echo esc_html( $v['ai'] ); ?></span>
					</p>
					<?php if ( '' !== $v['foot_url'] ) : ?>
						<p class="eael-xs-check__trust"><a class="eael-xs-check__settings" href="<?php echo esc_url( $v['foot_url'] ); ?>"><?php echo esc_html( $v['foot'] ); ?></a></p>
					<?php else : ?>
						<p class="eael-xs-check__trust"><?php echo esc_html( $v['foot'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="eael-xs-check__aside">
					<a class="eael-xs-check__cta" href="<?php echo esc_url( $v['cta_url'] ); ?>"><?php echo esc_html( $v['cta'] ); ?></a>
					<button type="button" class="eael-tr-never eael-xs-check__never"
						data-plugins="<?php echo esc_attr( $spec['slug'] ); ?>"
						data-nonce="<?php echo esc_attr( $v['nonce'] ); ?>">
						<?php esc_html_e( 'Never show me again', 'essential-addons-for-elementor-lite' ); ?>
					</button>
				</div>
			</div>
		</div>
		<?php
		// Only the never-show half of the script is needed here (there is
		// nothing left to install), and it is a no-op without an install button.
		$this->widget_script( $spec );
	}

	/**
	 * Time to first byte for the site's own front page.
	 *
	 * Genuinely TTFB, not total response time: the two differ by however long
	 * the body takes to stream, which on a slow page is most of the number.
	 * cURL reports it directly, so the measurement is only taken when the cURL
	 * transport is in play — with any other transport the metric is dropped
	 * rather than mislabelled.
	 *
	 * Cached for TTFB_TTL, because this makes a real HTTP request. A failed or
	 * unavailable measurement is cached too, so a broken loopback costs one
	 * request every TTFB_TTL rather than one per dashboard load.
	 *
	 * @param bool $cache_live Whether the page cache is currently serving. When
	 *                         it is not, the reading is also stored as the
	 *                         uncached baseline.
	 * @return array|false [ 'ms' => int, 'at' => int ], or false when unavailable.
	 */
	private function measure_ttfb( $cache_live ) {
		$cached = get_transient( self::TTFB_TRANSIENT );
		if ( false !== $cached ) {
			return $this->remember_baseline(
				( is_array( $cached ) && ! empty( $cached['ms'] ) ) ? $cached : false,
				$cache_live
			);
		}

		$capture = function ( $handle ) {
			// Runs after cURL is configured but before the transfer; the handle
			// is still valid when we read the timing back below.
			$this->ttfb_handle = $handle;
		};

		add_action( 'http_api_curl', $capture, 10, 1 );

		$response = wp_remote_get(
			home_url( '/' ),
			[
				'timeout'    => 8,
				'redirection' => 2,
				'sslverify'  => false,
				'headers'    => [ 'Cache-Control' => 'no-cache' ],
				// A cache should not learn about this request, and xSpeed must
				// not serve us a copy of our own probe.
				'user-agent' => 'EssentialAddons-SpeedCheck/1.0',
			]
		);

		remove_action( 'http_api_curl', $capture, 10 );

		$ms = 0;
		if ( ! is_wp_error( $response )
			&& wp_remote_retrieve_response_code( $response ) < 400
			&& isset( $this->ttfb_handle )
			&& function_exists( 'curl_getinfo' ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_getinfo -- reading timing off the handle WP itself just used.
			$seconds = @curl_getinfo( $this->ttfb_handle, CURLINFO_STARTTRANSFER_TIME );
			$ms      = ( is_numeric( $seconds ) && $seconds > 0 ) ? (int) round( $seconds * 1000 ) : 0;
		}

		$this->ttfb_handle = null;

		$reading = [ 'ms' => $ms, 'at' => time() ];
		set_transient( self::TTFB_TRANSIENT, $reading, self::TTFB_TTL );

		return $this->remember_baseline( $ms > 0 ? $reading : false, $cache_live );
	}

	/**
	 * Keep the most recent reading taken while the page cache was OFF.
	 *
	 * Applied to cached readings as well as fresh ones, so the rule is simply
	 * "any cache-off reading is the baseline". Doing it only on the fresh path
	 * would miss the case where caching is switched off while the measurement
	 * transient is still warm, leaving the cache-on state with nothing to
	 * compare against for up to TTFB_TTL.
	 *
	 * @param array|false $reading    Measurement, or false when unavailable.
	 * @param bool        $cache_live Whether the page cache is serving.
	 * @return array|false The reading, unchanged — returned for chaining.
	 */
	private function remember_baseline( $reading, $cache_live ) {
		if ( $reading && ! $cache_live && (int) get_option( self::TTFB_BASELINE_OPTION ) !== (int) $reading['ms'] ) {
			update_option( self::TTFB_BASELINE_OPTION, (int) $reading['ms'], false );
		}

		return $reading;
	}

	/**
	 * cURL handle of the in-flight TTFB probe. Held only for the duration of
	 * measure_ttfb().
	 *
	 * @var resource|\CurlHandle|null
	 */
	private $ttfb_handle = null;

	/**
	 * Milliseconds as "840ms" or "1.4s", whichever reads better.
	 *
	 * @param int $ms
	 * @return string
	 */
	private function format_ms( $ms ) {
		$ms = (int) $ms;

		if ( $ms < 1000 ) {
			/* translators: %s: a number of milliseconds. */
			return sprintf( __( '%sms', 'essential-addons-for-elementor-lite' ), number_format_i18n( $ms ) );
		}

		/* translators: %s: a number of seconds, to one decimal place. */
		return sprintf( __( '%ss', 'essential-addons-for-elementor-lite' ), number_format_i18n( $ms / 1000, 1 ) );
	}

	/**
	 * "just now" for a fresh reading, "5 mins ago" for a cached one.
	 *
	 * @param int $at Unix timestamp of the measurement.
	 * @return string
	 */
	private function measured_ago( $at ) {
		$age = time() - (int) $at;

		if ( $age < MINUTE_IN_SECONDS ) {
			return __( 'just now', 'essential-addons-for-elementor-lite' );
		}

		return sprintf(
			/* translators: %s: human-readable time difference, e.g. "5 mins". */
			__( '%s ago', 'essential-addons-for-elementor-lite' ),
			human_time_diff( (int) $at )
		);
	}

	/**
	 * How many uploaded images are still JPEG or PNG — i.e. still convertible
	 * to a modern format.
	 *
	 * One indexed COUNT against post_mime_type, cached for UNMINIFIED_TTL. No
	 * meta unserialising and no filesystem walk, so it stays cheap on a big
	 * media library.
	 *
	 * @return int
	 */
	private function legacy_image_count() {
		$cached = get_transient( self::LEGACY_IMAGES_TRANSIENT );
		if ( false !== $cached ) {
			return (int) $cached;
		}

		global $wpdb;

		$count = 0;

		if ( isset( $wpdb ) && is_object( $wpdb ) ) {
			$count = (int) $wpdb->get_var(
				"SELECT COUNT(*) FROM {$wpdb->posts}
				 WHERE post_type = 'attachment'
				   AND post_mime_type IN ( 'image/jpeg', 'image/jpg', 'image/png' )"
			);
		}

		set_transient( self::LEGACY_IMAGES_TRANSIENT, $count, self::UNMINIFIED_TTL );

		return $count;
	}

	/**
	 * First finding: what owns this site's page cache.
	 *
	 * Now that the promo survives on a site that already has a cache plugin,
	 * this genuinely names the incumbent — which is the honest thing to show,
	 * since an install here leaves that incumbent's drop-in alone.
	 *
	 * @return string
	 */
	private function page_cache_line() {
		$owner = XSpeed_Setup::page_cache_owner();

		if ( '' !== $owner ) {
			return sprintf(
				/* translators: %s: name of the plugin currently handling the page cache. */
				__( 'Page cache handled by %s', 'essential-addons-for-elementor-lite' ),
				$owner
			);
		}

		return __( 'No page cache detected', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * Second finding: how many unminified CSS/JS files the active theme and
	 * active plugins ship.
	 *
	 * A real count, with three deliberate limits, because a dashboard widget
	 * has no business walking a whole wp-content:
	 *
	 * - Only asset-shaped directories are looked at (the package root plus
	 *   assets/css/js/dist/build/public), not every file in every plugin.
	 * - The walk is depth- and budget-capped, and bails the moment the budget
	 *   runs out. A partial walk undercounts; it never invents.
	 * - The result is cached for UNMINIFIED_TTL, so the scan runs about twice
	 *   a day per site rather than on every dashboard load.
	 *
	 * Returns 0 when nothing was found or the filesystem could not be read, and
	 * the caller drops the line entirely rather than showing a zero.
	 *
	 * @return int
	 */
	private function unminified_asset_count() {
		$cached = get_transient( self::UNMINIFIED_TRANSIENT );
		if ( false !== $cached ) {
			return (int) $cached;
		}

		$roots = [];

		if ( function_exists( 'get_stylesheet_directory' ) ) {
			$roots[] = get_stylesheet_directory();
			$roots[] = get_template_directory();
		}

		foreach ( (array) get_option( 'active_plugins', [] ) as $basename ) {
			$dir = dirname( (string) $basename );

			// Single-file plugins (dirname '.') have no asset tree to walk.
			if ( '' === $dir || '.' === $dir ) {
				continue;
			}

			$roots[] = WP_PLUGIN_DIR . '/' . $dir;
		}

		$budget = 4000;
		$count  = 0;

		foreach ( array_unique( array_filter( $roots ) ) as $root ) {
			foreach ( [ '', '/assets', '/css', '/js', '/dist', '/build', '/public' ] as $sub ) {
				if ( $budget <= 0 ) {
					break 2;
				}

				$count += $this->count_unminified_in( $root . $sub, '' === $sub ? 0 : 3, $budget );
			}
		}

		set_transient( self::UNMINIFIED_TRANSIENT, $count, self::UNMINIFIED_TTL );

		return $count;
	}

	/**
	 * Count unminified .css/.js files under one directory.
	 *
	 * @param string $dir    Absolute path.
	 * @param int    $depth  Remaining directory levels to descend; 0 = this one only.
	 * @param int    $budget Shared directory-entry budget, decremented by reference.
	 * @return int
	 */
	private function count_unminified_in( $dir, $depth, &$budget ) {
		if ( $budget <= 0 || $depth < 0 || ! is_dir( $dir ) ) {
			return 0;
		}

		// Directories that hold build inputs or third-party trees rather than
		// the assets a visitor actually downloads.
		$skip = [ 'node_modules', 'vendor', 'test', 'tests', 'languages', 'lang', 'bin', 'docs', 'src' ];

		$handle = @opendir( $dir ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- unreadable dir is a valid, expected outcome.
		if ( ! $handle ) {
			return 0;
		}

		$count = 0;

		while ( false !== ( $entry = readdir( $handle ) ) ) {
			if ( '.' === $entry || '..' === $entry || '.' === $entry[0] ) {
				continue;
			}

			if ( --$budget <= 0 ) {
				break;
			}

			$path = $dir . '/' . $entry;

			if ( is_dir( $path ) ) {
				if ( ! in_array( strtolower( $entry ), $skip, true ) ) {
					$count += $this->count_unminified_in( $path, $depth - 1, $budget );
				}

				continue;
			}

			if ( ! preg_match( '/\.(css|js)$/i', $entry ) ) {
				continue;
			}

			// Already minified — .min.css, -min.js and friends.
			if ( preg_match( '/[.\-]min\.(css|js)$/i', $entry ) ) {
				continue;
			}

			$count++;
		}

		closedir( $handle );

		return $count;
	}

	/**
	 * Installed/active — the plugin is present, point users into it.
	 * No fabricated score: we don't invent data we can't read.
	 *
	 * @param array $spec Return value of widget_spec().
	 */
	private function render_active_state( $spec ) {
		if ( XSpeed_Setup::SLUG === $spec['slug'] ) {
			$this->render_xspeed_active( $spec );

			return;
		}
		?>
		<div class="eael-tr-widget__body eael-tr-widget__body--center">
			<div class="eael-tr-ring eael-tr-ring--active" aria-hidden="true">
				<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
			</div>
			<div class="eael-tr-widget__title"><?php echo esc_html( $spec['active_title'] ); ?></div>
			<p class="eael-tr-widget__desc">
				<?php echo esc_html( $spec['active_desc'] ); ?>
			</p>
			<a class="button button-primary eael-tr-cta" href="<?php echo esc_url( $spec['open_url'] ); ?>">
				<?php echo esc_html( $spec['active_cta'] ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Scoped, native-feeling styles shared by both widgets (light + dark aware
	 * via the admin colour scheme body classes WP already sets).
	 *
	 * Printed once however many widgets render; per-widget differences ride on
	 * the --eael-tr-accent / --eael-tr-ring custom properties instead.
	 */
	private function widget_styles() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
		<style>
			#eael_thinkrank_seo_check .inside,
			#eael_xspeed_speed_check .inside { margin: 0; padding: 0; }
			.eael-tr-widget { --eael-tr-accent: #4451ff; --eael-tr-accent-hover: #3742d6; --eael-tr-ring: #7c86ff; }
			.eael-tr-widget--xspeed { --eael-tr-accent: #34c0b7; --eael-tr-accent-hover: #2aa199; --eael-tr-ring: #34c0b7; }
			.eael-tr-widget__body { padding: 22px 20px 16px; }
			.eael-tr-widget__body--center { text-align: center; }
			.eael-tr-ring {
				width: 108px; height: 108px; margin: 2px auto 14px;
				border-radius: 50%; border: 10px solid #e6e7ea;
				display: flex; align-items: center; justify-content: center;
				color: var(--eael-tr-ring);
			}
			.eael-tr-ring--active { border-color: #edfaef; color: #00a32a; }
			.eael-tr-widget__title { font-size: 16px; font-weight: 600; color: #1d2327; margin-bottom: 6px; }
			.eael-tr-widget__desc { font-size: 13px; line-height: 1.5; color: #50575e; margin: 0 auto 16px; max-width: 260px; }
			.eael-tr-cta.button.button-primary {
				background: var(--eael-tr-accent); border-color: var(--eael-tr-accent);
				box-shadow: none; text-shadow: none; font-weight: 600;
			}
			.eael-tr-cta.button.button-primary:hover,
			.eael-tr-cta.button.button-primary:focus { background: var(--eael-tr-accent-hover); border-color: var(--eael-tr-accent-hover); }
			.eael-tr-cta[disabled] { opacity: .7; cursor: default; }
			.eael-tr-notice { margin-top: 12px; font-size: 12.5px; color: #50575e; }
			.eael-tr-notice.is-error { color: #d63638; }
			.eael-tr-notice.is-success { color: #00a32a; }
			.eael-tr-never.button-link { display: block; margin: 10px auto 0; color: #787c82; font-size: 12px; text-decoration: underline; cursor: pointer; }
			.eael-tr-never.button-link:hover { color: #50575e; }

			/* "Speed Check" — findings on the left, the one fix on the right.
			   Every selector is scoped to the widget id AND names its element:
			   core ships #dashboard-widgets h3 / p / ul rules that outrank a
			   bare class, which silently flattens the heading and the margins. */
			#eael_xspeed_speed_check .eael-xs-title { display: inline-flex; align-items: center; gap: 8px; margin-right: auto; }
			#eael_xspeed_speed_check .eael-xs-title-icon { display: block; }
			#eael_xspeed_speed_check .eael-xs-check { --eael-xs-green: #1c6b41; --eael-xs-green-hover: #155433; padding: 18px 20px 16px; }
			#eael_xspeed_speed_check h3.eael-xs-check__heading { font-size: 16px; font-weight: 700; color: #1d2327; margin: 0 0 14px; padding: 0; line-height: 1.3; }
			#eael_xspeed_speed_check .eael-xs-check__cols { display: flex; flex-wrap: wrap; align-items: stretch; gap: 14px 24px; }
			#eael_xspeed_speed_check .eael-xs-check__main,
			#eael_xspeed_speed_check .eael-xs-check__aside { display: flex; flex-direction: column; }
			#eael_xspeed_speed_check .eael-xs-check__main { flex: 1 1 240px; min-width: 0; }
			#eael_xspeed_speed_check .eael-xs-check__aside { flex: 1 1 190px; max-width: 100%; text-align: center; }
			#eael_xspeed_speed_check ul.eael-xs-check__list { margin: 0 0 12px; padding: 0; list-style: none; }
			#eael_xspeed_speed_check li.eael-xs-check__item { position: relative; padding-left: 20px; margin: 0 0 9px; font-size: 13.5px; line-height: 1.45; color: #1d2327; }
			#eael_xspeed_speed_check li.eael-xs-check__item::before { content: ""; position: absolute; left: 4px; top: 7px; width: 6px; height: 6px; border-radius: 50%; background: #8c8f94; }
			#eael_xspeed_speed_check li.eael-xs-check__item--ai { color: #50575e; }
			#eael_xspeed_speed_check li.eael-xs-check__item--ai::before { display: none; }
			#eael_xspeed_speed_check .eael-xs-check__spark { position: absolute; left: 0; top: 3px; color: #8c8f94; }
			#eael_xspeed_speed_check p.eael-xs-check__trust { margin: auto 0 0; padding-top: 8px; font-size: 12.5px; line-height: 1.4; color: #a7aaad; }
			#eael_xspeed_speed_check button.eael-xs-check__cta {
				display: block; width: 100%; box-sizing: border-box;
				/* Capped so a full-width (1-column) dashboard doesn't stretch
				   it across half the screen; centred so it still looks placed
				   once the columns wrap. */
				max-width: 420px; margin: 0 auto;
				padding: 12px 14px; border: 0; border-radius: 6px;
				background: var(--eael-xs-green); color: #fff;
				font-size: 14.5px; font-weight: 600; line-height: 1.3;
				cursor: pointer; text-align: center;
			}
			#eael_xspeed_speed_check button.eael-xs-check__cta:hover:not([disabled]),
			#eael_xspeed_speed_check button.eael-xs-check__cta:focus:not([disabled]) { background: var(--eael-xs-green-hover); color: #fff; }
			#eael_xspeed_speed_check button.eael-xs-check__cta:focus { outline: 2px solid var(--eael-xs-green); outline-offset: 2px; }
			#eael_xspeed_speed_check button.eael-xs-check__cta[disabled] { opacity: .7; cursor: default; }
			#eael_xspeed_speed_check p.eael-xs-check__note { margin: 10px 0 0; padding: 0; font-size: 12.5px; line-height: 1.45; color: #a7aaad; }
			/* Chrome reset written out rather than leaning on core's
			   .button-link, so the control looks the same wherever it lands. */
			#eael_xspeed_speed_check button.eael-xs-check__never {
				display: block; width: 100%; margin: auto 0 0; padding: 12px 0 0;
				background: none; border: 0; box-shadow: none;
				color: #50575e; font-size: 13px; line-height: 1.4;
				text-align: center; text-decoration: none; cursor: pointer;
			}
			#eael_xspeed_speed_check button.eael-xs-check__never:hover,
			#eael_xspeed_speed_check button.eael-xs-check__never:focus { background: none; color: #1d2327; text-decoration: underline; }
			#eael_xspeed_speed_check .eael-xs-check .eael-tr-notice { margin-top: 10px; }

			/* Active states — same shell, different furniture. */
			#eael_xspeed_speed_check .eael-xs-check__dot { display: inline-block; width: 9px; height: 9px; margin-right: 8px; border-radius: 50%; background: var(--eael-xs-green); vertical-align: middle; }
			#eael_xspeed_speed_check p.eael-xs-check__metric { margin: 0 0 8px; padding: 0; font-size: 13.5px; line-height: 1.45; color: #646970; }
			#eael_xspeed_speed_check p.eael-xs-check__metric strong { color: var(--eael-xs-green); font-weight: 700; }
			#eael_xspeed_speed_check p.eael-xs-check__body { margin: 0 0 8px; padding: 0; font-size: 13.5px; line-height: 1.45; color: #1d2327; }
			#eael_xspeed_speed_check p.eael-xs-check__ai { position: relative; margin: 0 0 8px; padding: 0 0 0 20px; font-size: 13px; line-height: 1.45; color: #646970; }
			#eael_xspeed_speed_check a.eael-xs-check__settings { color: #2271b1; text-decoration: none; }
			#eael_xspeed_speed_check a.eael-xs-check__settings:hover { color: #135e96; text-decoration: underline; }
			/* The active CTA is an anchor, not a button — same skin. */
			#eael_xspeed_speed_check a.eael-xs-check__cta {
				display: block; width: 100%; box-sizing: border-box;
				max-width: 420px; margin: 0 auto;
				padding: 12px 14px; border: 0; border-radius: 6px;
				background: var(--eael-xs-green); color: #fff;
				font-size: 14.5px; font-weight: 600; line-height: 1.3;
				text-align: center; text-decoration: none;
			}
			#eael_xspeed_speed_check a.eael-xs-check__cta:hover,
			#eael_xspeed_speed_check a.eael-xs-check__cta:focus { background: var(--eael-xs-green-hover); color: #fff; }
		</style>
		<?php
	}

	/**
	 * Inline install handler for ONE widget — POSTs to the shared installer
	 * AJAX and swaps that widget into a success/open state without a reload.
	 *
	 * Every selector is rooted at the widget's own id, and never-show posts
	 * only this widget's slug, so the two widgets cannot bind each other's
	 * buttons or silence each other.
	 *
	 * @param array $spec Return value of widget_spec().
	 */
	private function widget_script( $spec ) {
		$flags    = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
		$id       = wp_json_encode( '#' . $spec['id'], $flags );
		$open_url = wp_json_encode( $spec['open_url'], $flags );
		// JSON rather than esc_js(): these labels can contain "&", which
		// esc_js() turns into an &amp; that a <script> block never decodes.
		$installing = wp_json_encode( $spec['installing'], $flags );
		$label      = wp_json_encode( $spec['cta'], $flags );
		$failed     = wp_json_encode( $spec['failed'], $flags );
		?>
		<script>
		( function () {
			var root = document.querySelector( <?php echo $id; ?> );
			if ( ! root ) { return; }
			function post( body ) {
				return window.fetch( window.ajaxurl, {
					method: 'POST',
					credentials: 'same-origin',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: body.toString()
				} ).then( function ( r ) { return r.json(); } );
			}
			var never = root.querySelector( '.eael-tr-never' );
			if ( never && ! never.dataset.bound ) {
				never.dataset.bound = '1';
				never.addEventListener( 'click', function () {
					// Permanent, SITE-WIDE opt-out — but only for THIS
					// plugin; the other widget keeps its own state.
					var body = new URLSearchParams();
					body.append( 'action', 'eael_thinkrank_never_show' );
					body.append( 'security', never.dataset.nonce );
					body.append( 'plugins', never.dataset.plugins );
					post( body );
					root.parentNode && root.parentNode.removeChild( root );
				} );
			}
			var btn = root.querySelector( '.eael-tr-install' );
			if ( ! btn || btn.dataset.bound ) { return; }
			btn.dataset.bound = '1';
			btn.addEventListener( 'click', function () {
				var notice = root.querySelector( '.eael-tr-notice' );
				var label  = btn.querySelector( '.eael-tr-cta__label' );
				btn.setAttribute( 'disabled', 'disabled' );
				if ( label ) { label.textContent = <?php echo $installing; ?>; }
				if ( notice ) { notice.style.display = 'none'; notice.className = 'eael-tr-notice'; }

				var body = new URLSearchParams();
				body.append( 'action', 'wpdeveloper_install_plugin' );
				body.append( 'slug', btn.dataset.slug );
				body.append( 'security', btn.dataset.nonce );

				post( body ).then( function ( res ) {
					if ( res && res.success ) {
						window.setTimeout( function () { window.location.href = <?php echo $open_url; ?>; }, 900 );
					} else {
						btn.removeAttribute( 'disabled' );
						if ( label ) { label.textContent = <?php echo $label; ?>; }
						if ( notice ) { notice.className = 'eael-tr-notice is-error'; notice.style.display = 'block'; notice.textContent = ( res && res.data ) ? res.data : <?php echo $failed; ?>; }
					}
				} ).catch( function () {
					btn.removeAttribute( 'disabled' );
					if ( label ) { label.textContent = <?php echo $label; ?>; }
					if ( notice ) { notice.className = 'eael-tr-notice is-error'; notice.style.display = 'block'; notice.textContent = <?php echo $failed; ?>; }
				} );
			} );
		} )();
		</script>
		<?php
	}
}

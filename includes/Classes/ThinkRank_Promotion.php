<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ThinkRank cross-promotion controller.
 *
 * Surfaces WPDeveloper's AI SEO plugin (ThinkRank) inside the Essential Addons
 * admin experience. Every surface is:
 *   - native to WordPress admin,
 *   - framed as "configure / analyze SEO" — no Essential Addons branding in the
 *     surface itself (product decision),
 *   - permanently dismissible where it's a notice/prompt,
 *   - scoped to relevant, high-intent moments — never a global banner.
 *
 * Install is delegated to the shared WPDeveloper_Plugin_Installer AJAX
 * endpoint (`wpdeveloper_install_plugin`), the same pipeline Quick Setup uses
 * to install Essential Blocks / Templately.
 *
 * Phase 1 implements the WordPress Dashboard widget ("SEO Check"). Other
 * surfaces (Quick Setup step, EA Dashboard banner, Integrations card, editor
 * entries) are added incrementally on this branch.
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
	 * How long "Maybe later" hides the promo for, in seconds.
	 */
	const SNOOZE_DURATION = 30 * DAY_IN_SECONDS;

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		// Surface 3 — WP Dashboard "SEO Check" widget.
		add_action( 'wp_dashboard_setup', [ $this, 'register_dashboard_widget' ] );

		// Surface 4 — after an EA update, bring existing users to the EA
		// Dashboard once and show a dismissible, attributed ThinkRank banner.
		add_action( 'upgrader_process_complete', [ $this, 'flag_after_update' ], 10, 2 );
		add_action( 'admin_init', [ $this, 'maybe_redirect_after_update' ] );
		add_action( 'admin_notices', [ $this, 'render_dashboard_banner' ] );
		// On the EA Dashboard (toplevel_page_eael-settings) EA strips all
		// admin_notices and re-dispatches its own via `eael_admin_notices`, so
		// the banner is registered there too. That hook only fires on the EA
		// Dashboard, so this never double-renders on other screens.
		add_action( 'eael_admin_notices', [ $this, 'render_dashboard_banner' ] );
		add_action( 'wp_ajax_eael_thinkrank_dismiss', [ $this, 'ajax_dismiss_banner' ] );
		add_action( 'wp_ajax_eael_thinkrank_snooze', [ $this, 'ajax_snooze_banner' ] );

		// Surface 5 — Gutenberg editor "Configure SEO" document panel.
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_gutenberg_panel' ] );
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
	 * Has the current user permanently dismissed the ThinkRank promo banner?
	 */
	public function is_dismissed() {
		return (bool) get_user_meta( get_current_user_id(), 'eael_thinkrank_dismissed', true );
	}

	/**
	 * Has the current user snoozed the promo via "Maybe later", and is that
	 * snooze still running? Unlike is_dismissed() this expires on its own.
	 */
	public function is_snoozed() {
		$until = (int) get_user_meta( get_current_user_id(), 'eael_thinkrank_snoozed_until', true );

		return $until > time();
	}

	/**
	 * Is the promo hidden for the current user right now, for any reason?
	 */
	public function is_hidden() {
		return $this->is_dismissed() || $this->is_snoozed();
	}

	/**
	 * Flag a redirect after Essential Addons (Lite) itself is updated.
	 *
	 * Runs only in admin context (the class self-returns otherwise), so cron
	 * auto-updates never set the flag — we never hijack a background update.
	 */
	public function flag_after_update( $upgrader, $options ) {
		if ( empty( $options['action'] ) || 'update' !== $options['action'] ) {
			return;
		}
		if ( empty( $options['type'] ) || 'plugin' !== $options['type'] ) {
			return;
		}

		$plugins = isset( $options['plugins'] ) ? (array) $options['plugins'] : [];
		if ( ! empty( $options['plugin'] ) ) {
			$plugins[] = $options['plugin'];
		}

		if ( ! in_array( EAEL_PLUGIN_BASENAME, $plugins, true ) ) {
			return;
		}
		if ( $this->is_thinkrank_active() || $this->is_hidden() ) {
			return;
		}

		set_transient( 'eael_thinkrank_after_update', 1, 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * One-time, guarded redirect to the EA Dashboard after an EA update.
	 */
	public function maybe_redirect_after_update() {
		if ( ! get_transient( 'eael_thinkrank_after_update' ) ) {
			return;
		}
		if ( wp_doing_ajax() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// Never hijack the bulk-update result screen.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate-multi'] ) || ( isset( $_GET['action'] ) && 'do-plugin-upgrade' === $_GET['action'] ) ) {
			return;
		}

		delete_transient( 'eael_thinkrank_after_update' );
		wp_safe_redirect( admin_url( 'admin.php?page=eael-settings&eael-thinkrank=1' ) );
		exit;
	}

	/**
	 * Permanent per-user dismiss of the banner.
	 */
	public function ajax_dismiss_banner() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}
		update_user_meta( get_current_user_id(), 'eael_thinkrank_dismissed', 1 );
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
		update_user_meta( get_current_user_id(), 'eael_thinkrank_snoozed_until', time() + self::SNOOZE_DURATION );
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
	 * Dismissible "configure your SEO" prompt. Unbranded (no EA mention).
	 * Never global; permanent per-user dismiss. See banner_context() for scope.
	 */
	public function render_dashboard_banner() {
		if ( $this->is_thinkrank_active() || $this->is_hidden() ) {
			return;
		}
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( ! $this->banner_context() ) {
			return;
		}

		$nonce = wp_create_nonce( 'essential-addons-elementor' );
		$open  = esc_url( admin_url( 'admin.php?page=' . self::ADMIN_PAGE ) );
		?>
		<div class="notice eael-tr-banner" data-slug="<?php echo esc_attr( self::SLUG ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-open="<?php echo $open; ?>">
			<div class="eael-tr-banner__icon" aria-hidden="true">
				<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/thinkrank/icon.svg' ); ?>" width="40" height="40" alt="">
			</div>
			<div class="eael-tr-banner__body">
				<strong class="eael-tr-banner__title"><?php esc_html_e( 'Get found on Google & AI answers — configure your SEO', 'essential-addons-for-elementor-lite' ); ?></strong>
				<span class="eael-tr-banner__desc"><?php esc_html_e( 'Let AI handle titles, meta, schema, LLM answers & sitemaps so every page ranks. Free with ThinkRank.', 'essential-addons-for-elementor-lite' ); ?></span>
			</div>
			<div class="eael-tr-banner__actions">
				<button type="button" class="button button-primary eael-tr-banner__install"><?php esc_html_e( 'Enable SEO Tool', 'essential-addons-for-elementor-lite' ); ?></button>
				<button type="button" class="eael-tr-banner__later"><?php esc_html_e( 'Maybe later', 'essential-addons-for-elementor-lite' ); ?></button>
			</div>
			<button type="button" class="notice-dismiss eael-tr-banner__dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'essential-addons-for-elementor-lite' ); ?></span></button>
		</div>
		<?php
		$this->banner_assets();
	}

	/**
	 * Inline styles + behaviour for the banner. Reuses the shared installer
	 * AJAX for install and the dismiss endpoint for permanent dismissal.
	 */
	private function banner_assets() {
		$installing = esc_js( __( 'Enabling ThinkRank…', 'essential-addons-for-elementor-lite' ) );
		$done       = esc_js( __( 'Enabled! Opening ThinkRank…', 'essential-addons-for-elementor-lite' ) );
		$failed     = esc_js( __( 'Could not enable automatically. Try Plugins → Add New.', 'essential-addons-for-elementor-lite' ) );
		$label      = esc_js( __( 'Enable SEO Tool', 'essential-addons-for-elementor-lite' ) );
		?>
		<style>
			.eael-tr-banner.notice { display:flex; align-items:center; gap:16px; padding:14px 40px 14px 16px; border-left-color:#4451ff; position:relative; }
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
			function post( action ) {
				var b = new URLSearchParams();
				b.append( 'action', action );
				b.append( 'security', el.dataset.nonce );
				if ( 'wpdeveloper_install_plugin' === action ) { b.append( 'slug', el.dataset.slug ); }
				return window.fetch( window.ajaxurl, { method:'POST', credentials:'same-origin', headers:{ 'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8' }, body:b.toString() } ).then( function(r){ return r.json(); } );
			}
			function dismiss() { post( 'eael_thinkrank_dismiss' ); el.parentNode && el.parentNode.removeChild( el ); }
			el.querySelector( '.eael-tr-banner__dismiss' ).addEventListener( 'click', dismiss );
			el.querySelector( '.eael-tr-banner__later' ).addEventListener( 'click', function () { post( 'eael_thinkrank_snooze' ); el.parentNode && el.parentNode.removeChild( el ); } );
			el.querySelector( '.eael-tr-banner__install' ).addEventListener( 'click', function () {
				var btn = this; btn.setAttribute( 'disabled', 'disabled' ); btn.textContent = '<?php echo $installing; ?>';
				post( 'wpdeveloper_install_plugin' ).then( function ( res ) {
					if ( res && res.success ) { btn.textContent = '<?php echo $done; ?>'; window.setTimeout( function () { window.location.href = el.dataset.open; }, 800 ); }
					else { btn.removeAttribute( 'disabled' ); btn.textContent = '<?php echo $label; ?>'; window.alert( ( res && res.data ) ? res.data : '<?php echo $failed; ?>' ); }
				} ).catch( function () { btn.removeAttribute( 'disabled' ); btn.textContent = '<?php echo $label; ?>'; window.alert( '<?php echo $failed; ?>' ); } );
			} );
		} )();
		</script>
		<?php
	}

	/**
	 * Register the "SEO Check" dashboard widget.
	 *
	 * Gated to users who can install plugins so the CTA is actionable. Being a
	 * real dashboard widget, it is removable by the user via Screen Options.
	 */
	public function register_dashboard_widget() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			'eael_thinkrank_seo_check',
			esc_html__( 'SEO Check', 'essential-addons-for-elementor-lite' ),
			[ $this, 'render_dashboard_widget' ]
		);
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
	 * Absolute URL to a staged ThinkRank brand asset.
	 */
	private function asset( $file ) {
		return EAEL_PLUGIN_URL . 'assets/admin/images/thinkrank/' . $file;
	}

	/**
	 * Render the dashboard widget body.
	 */
	public function render_dashboard_widget() {
		$active = $this->is_thinkrank_active();
		$this->widget_styles();

		echo '<div class="eael-tr-widget">';

		if ( $active ) {
			$this->render_active_state();
		} else {
			$this->render_prompt_state();
		}

		echo '</div>';
	}

	/**
	 * Not-installed prompt — the acquisition state.
	 * AI-focused copy: the primary CTA analyzes SEO, which installs ThinkRank.
	 */
	private function render_prompt_state() {
		?>
		<div class="eael-tr-widget__body eael-tr-widget__body--center">
			<div class="eael-tr-ring" aria-hidden="true">
				<svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="10" cy="10" r="7"></circle><path d="m21 21-4.3-4.3"></path><path d="M7.4 10.2 9.4 12.2 12.8 8.6"></path>
				</svg>
			</div>
			<div class="eael-tr-widget__title"><?php esc_html_e( 'Analyze your SEO with AI', 'essential-addons-for-elementor-lite' ); ?></div>
			<p class="eael-tr-widget__desc">
				<?php esc_html_e( "ThinkRank's AI reviews your titles, meta, schema and readability, then shows the quick wins. See how your pages score.", 'essential-addons-for-elementor-lite' ); ?>
			</p>
			<button type="button" class="button button-primary eael-tr-cta eael-tr-install"
				data-slug="<?php echo esc_attr( self::SLUG ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'essential-addons-elementor' ) ); ?>">
				<span class="eael-tr-cta__label"><?php esc_html_e( 'Analyze my SEO', 'essential-addons-for-elementor-lite' ); ?></span>
			</button>
			<div class="eael-tr-notice" role="status" style="display:none;"></div>
		</div>
		<?php
		$this->widget_script();
	}

	/**
	 * Installed/active — ThinkRank is present, point users into it.
	 * No fabricated score: we don't invent data we can't read.
	 */
	private function render_active_state() {
		?>
		<div class="eael-tr-widget__body eael-tr-widget__body--center">
			<div class="eael-tr-ring eael-tr-ring--active" aria-hidden="true">
				<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
			</div>
			<div class="eael-tr-widget__title"><?php esc_html_e( 'ThinkRank is active', 'essential-addons-for-elementor-lite' ); ?></div>
			<p class="eael-tr-widget__desc">
				<?php esc_html_e( 'Open ThinkRank to see your AI SEO score and page-by-page fixes.', 'essential-addons-for-elementor-lite' ); ?>
			</p>
			<a class="button button-primary eael-tr-cta" href="<?php echo esc_url( admin_url( 'admin.php?page=thinkrank' ) ); ?>">
				<?php esc_html_e( 'Open ThinkRank', 'essential-addons-for-elementor-lite' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Scoped, native-feeling styles for the widget (light + dark aware via
	 * the admin colour scheme body classes WP already sets).
	 */
	private function widget_styles() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
		<style>
			#eael_thinkrank_seo_check .inside { margin: 0; padding: 0; }
			.eael-tr-widget { --eael-tr-accent: #4451ff; }
			.eael-tr-widget__body { padding: 22px 20px 16px; }
			.eael-tr-widget__body--center { text-align: center; }
			.eael-tr-ring {
				width: 108px; height: 108px; margin: 2px auto 14px;
				border-radius: 50%; border: 10px solid #e6e7ea;
				display: flex; align-items: center; justify-content: center;
				color: #7c86ff;
			}
			.eael-tr-ring--active { border-color: #edfaef; color: #00a32a; }
			.eael-tr-widget__title { font-size: 16px; font-weight: 600; color: #1d2327; margin-bottom: 6px; }
			.eael-tr-widget__desc { font-size: 13px; line-height: 1.5; color: #50575e; margin: 0 auto 16px; max-width: 260px; }
			.eael-tr-cta.button.button-primary {
				background: var(--eael-tr-accent); border-color: var(--eael-tr-accent);
				box-shadow: none; text-shadow: none; font-weight: 600;
			}
			.eael-tr-cta.button.button-primary:hover,
			.eael-tr-cta.button.button-primary:focus { background: #3742d6; border-color: #3742d6; }
			.eael-tr-cta[disabled] { opacity: .7; cursor: default; }
			.eael-tr-notice { margin-top: 12px; font-size: 12.5px; color: #50575e; }
			.eael-tr-notice.is-error { color: #d63638; }
			.eael-tr-notice.is-success { color: #00a32a; }
		</style>
		<?php
	}

	/**
	 * Inline install handler — POSTs to the shared installer AJAX and swaps the
	 * widget into a success/open state without a page reload.
	 */
	private function widget_script() {
		$open_url = esc_url( admin_url( 'admin.php?page=thinkrank' ) );
		$installing = esc_js( __( 'Analyzing… enabling ThinkRank', 'essential-addons-for-elementor-lite' ) );
		$failed     = esc_js( __( 'Could not enable automatically. Please try from Plugins → Add New.', 'essential-addons-for-elementor-lite' ) );
		?>
		<script>
		( function () {
			var btn = document.querySelector( '#eael_thinkrank_seo_check .eael-tr-install' );
			if ( ! btn || btn.dataset.bound ) { return; }
			btn.dataset.bound = '1';
			btn.addEventListener( 'click', function () {
				var notice = document.querySelector( '#eael_thinkrank_seo_check .eael-tr-notice' );
				var label  = btn.querySelector( '.eael-tr-cta__label' );
				btn.setAttribute( 'disabled', 'disabled' );
				if ( label ) { label.textContent = '<?php echo $installing; ?>'; }
				if ( notice ) { notice.style.display = 'none'; notice.className = 'eael-tr-notice'; }

				var body = new URLSearchParams();
				body.append( 'action', 'wpdeveloper_install_plugin' );
				body.append( 'slug', btn.dataset.slug );
				body.append( 'security', btn.dataset.nonce );

				window.fetch( window.ajaxurl, {
					method: 'POST',
					credentials: 'same-origin',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: body.toString()
				} ).then( function ( r ) { return r.json(); } ).then( function ( res ) {
					if ( res && res.success ) {
						window.setTimeout( function () { window.location.href = '<?php echo $open_url; ?>'; }, 900 );
					} else {
						btn.removeAttribute( 'disabled' );
						if ( label ) { label.textContent = '<?php echo esc_js( __( 'Analyze my SEO', 'essential-addons-for-elementor-lite' ) ); ?>'; }
						if ( notice ) { notice.className = 'eael-tr-notice is-error'; notice.style.display = 'block'; notice.textContent = ( res && res.data ) ? res.data : '<?php echo $failed; ?>'; }
					}
				} ).catch( function () {
					btn.removeAttribute( 'disabled' );
					if ( label ) { label.textContent = '<?php echo esc_js( __( 'Analyze my SEO', 'essential-addons-for-elementor-lite' ) ); ?>'; }
					if ( notice ) { notice.className = 'eael-tr-notice is-error'; notice.style.display = 'block'; notice.textContent = '<?php echo $failed; ?>'; }
				} );
			} );
		} )();
		</script>
		<?php
	}
}

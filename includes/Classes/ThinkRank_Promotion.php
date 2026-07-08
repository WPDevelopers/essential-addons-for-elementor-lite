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
 *   - attributed to Essential Addons ("Recommended by Essential Addons"),
 *   - permanently dismissible where it's a notice/prompt,
 *   - scoped to relevant, high-intent moments — never a global banner,
 *     never disguised.
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
		add_action( 'wp_ajax_eael_thinkrank_dismiss', [ $this, 'ajax_dismiss_banner' ] );
	}

	/**
	 * Has the current user permanently dismissed the ThinkRank promo banner?
	 */
	public function is_dismissed() {
		return (bool) get_user_meta( get_current_user_id(), 'eael_thinkrank_dismissed', true );
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
		if ( $this->is_thinkrank_active() || $this->is_dismissed() ) {
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
	 * Dismissible, attributed banner — scoped to EA's own admin pages only.
	 * Never a global banner; carries a permanent dismiss and honest sourcing.
	 */
	public function render_dashboard_banner() {
		if ( $this->is_thinkrank_active() || $this->is_dismissed() ) {
			return;
		}
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		// Scope strictly to Essential Addons' own screens (page slug starts eael).
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( 0 !== strpos( $page, 'eael' ) ) {
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
				<strong class="eael-tr-banner__title"><?php esc_html_e( 'New: pair Essential Addons with ThinkRank AI SEO', 'essential-addons-for-elementor-lite' ); ?></strong>
				<span class="eael-tr-banner__desc"><?php esc_html_e( 'Turn the pages you build into pages that rank. ThinkRank’s AI handles titles, meta, schema, LLM answers & sitemaps — free.', 'essential-addons-for-elementor-lite' ); ?></span>
			</div>
			<div class="eael-tr-banner__actions">
				<button type="button" class="button button-primary eael-tr-banner__install"><?php esc_html_e( 'Install ThinkRank', 'essential-addons-for-elementor-lite' ); ?></button>
				<button type="button" class="eael-tr-banner__later"><?php esc_html_e( 'Maybe later', 'essential-addons-for-elementor-lite' ); ?></button>
			</div>
			<span class="eael-tr-banner__attr"><span class="eael-tr-attr__mark">EA</span><?php esc_html_e( 'Recommended by Essential Addons', 'essential-addons-for-elementor-lite' ); ?></span>
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
		$installing = esc_js( __( 'Installing ThinkRank…', 'essential-addons-for-elementor-lite' ) );
		$done       = esc_js( __( 'Installed! Opening ThinkRank…', 'essential-addons-for-elementor-lite' ) );
		$failed     = esc_js( __( 'Could not install automatically. Try Plugins → Add New.', 'essential-addons-for-elementor-lite' ) );
		$label      = esc_js( __( 'Install ThinkRank', 'essential-addons-for-elementor-lite' ) );
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
			.eael-tr-banner__attr { display:flex; align-items:center; gap:6px; font-size:11.5px; color:#8a8f94; flex:none; }
			.eael-tr-banner .eael-tr-attr__mark { width:15px; height:15px; border-radius:4px; background:linear-gradient(150deg,#e6316f,#92003b); color:#fff; font-size:7px; font-weight:800; display:flex; align-items:center; justify-content:center; }
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
			el.querySelector( '.eael-tr-banner__later' ).addEventListener( 'click', function () { el.parentNode && el.parentNode.removeChild( el ); } );
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
	 * Small inline EA attribution mark + the required attribution line.
	 * Kept identical across every ThinkRank surface for consistency.
	 */
	private function attribution_footer() {
		?>
		<div class="eael-tr-attr">
			<span class="eael-tr-attr__mark">EA</span>
			<?php esc_html_e( 'Recommended by Essential Addons', 'essential-addons-for-elementor-lite' ); ?>
		</div>
		<?php
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

		$this->attribution_footer();
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
			.eael-tr-attr {
				display: flex; align-items: center; gap: 6px;
				padding: 10px 14px; border-top: 1px solid #f0f0f1; background: #fbfbfc;
				font-size: 11.5px; color: #8a8f94;
			}
			.eael-tr-attr__mark {
				width: 16px; height: 16px; border-radius: 4px; flex: none;
				background: linear-gradient(150deg, #e6316f, #92003b);
				color: #fff; font-size: 8px; font-weight: 800;
				display: flex; align-items: center; justify-content: center;
			}
		</style>
		<?php
	}

	/**
	 * Inline install handler — POSTs to the shared installer AJAX and swaps the
	 * widget into a success/open state without a page reload.
	 */
	private function widget_script() {
		$open_url = esc_url( admin_url( 'admin.php?page=thinkrank' ) );
		$installing = esc_js( __( 'Analyzing… installing ThinkRank', 'essential-addons-for-elementor-lite' ) );
		$done       = esc_js( __( 'ThinkRank installed. Opening your SEO analysis…', 'essential-addons-for-elementor-lite' ) );
		$failed     = esc_js( __( 'Could not install automatically. Please try from Plugins → Add New.', 'essential-addons-for-elementor-lite' ) );
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
						if ( notice ) { notice.className = 'eael-tr-notice is-success'; notice.style.display = 'block'; notice.textContent = '<?php echo $done; ?>'; }
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

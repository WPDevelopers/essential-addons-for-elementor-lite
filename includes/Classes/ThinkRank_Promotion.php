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
	 * Candidate main-file basenames used to detect an active ThinkRank install.
	 * Confirm the real basename at release and trim this list.
	 */
	const ACTIVE_BASENAMES = [ 'thinkrank/thinkrank.php', 'thinkrank/plugin.php' ];

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'wp_dashboard_setup', [ $this, 'register_dashboard_widget' ] );
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

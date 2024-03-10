<?php

namespace Essential_Addons_Elementor\Classes;

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

class WPDeveloper_Setup_Wizard {
	public $templately_status;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'setup_wizard_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_save_setup_wizard_data', [ $this, 'save_setup_wizard_data' ] );
		add_action( 'wp_ajax_save_eael_elements_data', [ $this, 'save_eael_elements_data' ] );
		add_action( 'in_admin_header', [ $this, 'remove_notice' ], 1000 );
		$this->templately_status = $this->templately_active_status();
	}

	/**
	 * templately_active_status
	 * @return bool
	 */
	public function templately_active_status() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( 'templately/templately.php' );
	}

	/**
	 * Remove all notice in setup wizard page
	 */
	public function remove_notice() {
		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'eael-setup-wizard' ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	/**
	 * setup_wizard_scripts
	 * @param $hook
	 * @return array
	 */
	public function setup_wizard_scripts( $hook ) {
		if ( isset( $hook ) && $hook == 'admin_page_eael-setup-wizard' ) {
			wp_enqueue_style( 'essential_addons_elementor-setup-wizard-css', EAEL_PLUGIN_URL . 'assets/admin/css/admin.css', false, EAEL_PLUGIN_VERSION );
			wp_enqueue_style( 'sweetalert2-css', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, EAEL_PLUGIN_VERSION );
			wp_enqueue_script( 'sweetalert2-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'sweetalert2-core-js' ), EAEL_PLUGIN_VERSION, true );
			wp_enqueue_script( 'sweetalert2-core-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/core.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
			wp_enqueue_script( 'essential_addons_elementor-setup-wizard-js', EAEL_PLUGIN_URL . 'assets/admin/js/admin.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
			wp_localize_script( 'essential_addons_elementor-setup-wizard-js', 'localize', array(
				'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'         => wp_create_nonce( 'essential-addons-elementor' ),
				'success_image' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/success.gif',
			) );
		}
		return [];
	}

	/**
	 * Create admin menu for setup wizard
	 */
	public function admin_menu() {

		add_submenu_page(
			'',
			__( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
			__( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
			'manage_options',
			'eael-setup-wizard',
			[ $this, 'render_wizard' ]
		);
	}


	/**
	 * render_wizard
	 */
	public function render_wizard() {
		?>
        <div class="eael-quick-setup-wizard-wrap">
			<?php
			$this->change_site_title();
			$this->tab_step();
			$this->tab_content();
			$this->setup_wizard_footer();
			?>
        </div>
		<?php
	}

	/**
	 * Render tab
	 */
	public function tab_step() {
		!$this->templately_status ? $wizard_column = 'five' : $wizard_column = 'four';
		$items = [
			__( 'Configuration', 'essential-addons-for-elementor-lite' ),
			__( 'Elements', 'essential-addons-for-elementor-lite' ),
			__( 'Go PRO', 'essential-addons-for-elementor-lite' ),
			__( 'Templately', 'essential-addons-for-elementor-lite' ),
			__( 'Integrations', 'essential-addons-for-elementor-lite' ),
			__( 'Finalize', 'essential-addons-for-elementor-lite' ),
		];
		$i     = 0;
		?>
        <ul class="eael-quick-setup-wizard <?php echo esc_attr( $wizard_column ); ?>" data-step="1">
			<?php foreach ( $items as $item ): ?>
				<?php if ( $item == 'Templately' && $this->templately_status || ( $this->get_local_plugin_data( 'templately/templately.php' ) !== false && $item == 'Templately' ) ) continue; ?>
                <li class="eael-quick-setup-step active <?php echo esc_attr( strtolower($item) ); ?>">
                    <div class="eael-quick-setup-icon"><?php echo ++$i; ?></div>
                    <div class="eael-quick-setup-name"><?php echo esc_html( $item ); ?></div>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php
	}

	/**
	 * Tav view content
	 */
	public function tab_content() {
		?>
        <div class="eael-quick-setup-body">
            <form class="eael-setup-wizard-form eael-quick-setup-wizard-form" method="post">
				<?php
				$this->configuration_tab();
				$this->eael_elements();
				$this->go_pro();
				$this->templately_integrations();
				$this->eael_integrations();
				$this->final_step();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Footer content
	 */
	public function setup_wizard_footer() {
		?>
        <div class="eael-quick-setup-footer">
            <button id="eael-prev" class="button eael-quick-setup-btn eael-quick-setup-prev-button">
                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/left-arrow.svg' ); ?>"
                     alt="<?php _e( 'Go Pro Logo', 'essential-addons-for-elementor-lite' ); ?>">
				<?php _e( 'Previous', 'essential-addons-for-elementor-lite' ) ?>
            </button>
            <button id="eael-next"
                    class="button  eael-quick-setup-btn eael-quick-setup-next-button"><?php _e( 'Next', 'essential-addons-for-elementor-lite' ) ?>
                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/right-arrow.svg' ); ?>"
                     alt="<?php _e( 'Right', 'essential-addons-for-elementor-lite' ); ?>"></button>
            <button id="eael-save" style="display: none"
                    class="button eael-quick-setup-btn eael-quick-setup-next-button eael-setup-wizard-save"><?php _e( 'Finish', 'essential-addons-for-elementor-lite' ) ?></button>
        </div>
		<div class="eael-quick-setup-footer" style="display: none;">
			<button id="eael-next" class="button eael-quick-setup-btn eael-quick-setup-prev-button">
				<?php _e( 'Skip', 'essential-addons-for-elementor-lite' ) ?>
			</button>
			<button class="button eael-quick-setup-btn eael-quick-setup-next-button wpdeveloper-plugin-installer" data-action="install" data-slug="templately"><?php _e( 'Enable Templates', 'essential-addons-for-elementor-lite' ) ?>
				<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/right-arrow.svg' ); ?>" alt="<?php _e( 'Right', 'essential-addons-for-elementor-lite' ); ?>">
			</button>
		</div>
		<?php
	}

	public function configuration_tab() {
		?>
        <div id="configuration" class="eael-quick-setup-tab-content configuration setup-content">
            <div class="eael-quick-setup-intro">
                <div class="eael-quick-setup-logo">
                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ea.svg' ); ?>"
                         alt="<?php _e( 'EA Logo', 'essential-addons-for-elementor-lite' ); ?>">
                </div>
                <h2 class="eael-quick-setup-title">
					<?php _e( 'Get Started with Essential Addons 🚀', 'essential-addons-for-elementor-lite' ); ?>
                </h2>
                <p class="eael-quick-setup-text">
					<?php _e( 'Enhance your Elementor page building experience with 50+ amazing
                        elements & extensions 🔥', 'essential-addons-for-elementor-lite' ); ?>
                </p>
            </div>
            <div class="eael-quick-setup-input-group">
                <label class="eael-quick-setup-input config-list">
                    <input id="basic" value="basic" class="eael_preferences" name="eael_preferences" type="radio"
                           checked/>
                    <span class="eael-quick-setup-content">
                  <h3 class="eael-quick-setup-title"><?php _e( 'Basic (Recommended)', 'essential-addons-for-elementor-lite' ); ?></h3>
                  <p class="eael-quick-setup-text">
                    <?php _e( 'For websites where you want to only use the basic features
                    and keep your site lightweight. Most basic elements are
                    activated in this option.', 'essential-addons-for-elementor-lite' ); ?>
                  </p>
                </span>
                </label>
                <label class="eael-quick-setup-input config-list">
                    <input id="advance" value="advance" class="eael_preferences" name="eael_preferences"
                           type="radio"/>
                    <span class="eael-quick-setup-content">
                  <h3 class="eael-quick-setup-title"><?php _e( 'Advanced', 'essential-addons-for-elementor-lite' ); ?></h3>
                  <p class="eael-quick-setup-text">
                    <?php _e( 'For advanced users who are trying to build complex websites
                    with advanced functionalities with Elementor. All the
                    dynamic elements will be activated in this option.', 'essential-addons-for-elementor-lite' ); ?>
                  </p>
                </span>
                </label>
                <label class="eael-quick-setup-input config-list">
                    <input id="custom" value="custom" class="eael_preferences" name="eael_preferences"
                           type="radio"/>
                    <span class="eael-quick-setup-content">
                  <h3 class="eael-quick-setup-title"><?php _e( 'Custom', 'essential-addons-for-elementor-lite' ); ?></h3>
                  <p class="eael-quick-setup-text">
                    <?php _e( 'Pick this option if you want to configure the elements as
                    per your wish.', 'essential-addons-for-elementor-lite' ); ?>
                  </p>
                </span>
                </label>
            </div>
        </div>
		<?php
	}

	/**
	 * EAEL elements list
	 */
	public function eael_elements() {
        $init = 0;
		?>
        <div id="elements" class="eael-quick-setup-tab-content elements setup-content" style="display:none">
            <div class="eael-quick-setup-intro">
                <h2 class="eael-quick-setup-title">
					<?php _e( 'Turn on the Elements that you need', 'essential-addons-for-elementor-lite' ); ?>
                </h2>
                <p class="eael-quick-setup-text">
					<?php _e( 'Enable/Disable the elements anytime you want from Essential
                    Addons Dashboard', 'essential-addons-for-elementor-lite' ); ?>
                </p>
            </div>
            <div class="eael-quick-setup-elements-body">
				<?php foreach ( $this->get_element_list() as $key => $item ):
					$init++;
					$disable = ( $init > 2 ) ? 'eael-quick-setup-post-grid-panel-disable' : '';
				?>
                    <div class="eael-quick-setup-post-grid-panel <?php echo esc_attr( $disable ); ?>">
                        <h3 class="eael-quick-setup-post-grid-panel-title"><?php echo esc_html( $item[ 'title' ] ); ?></h3>
                        <div class="eael-quick-setup-post-grid-wrapper eael-<?php echo esc_attr( $key ); ?>">
							<?php foreach ( $item[ 'elements' ] as $element ):
								$preferences = $checked = '';
								if ( isset( $element[ 'preferences' ] ) ) {
									$preferences = $element[ 'preferences' ];
									if ( $element[ 'preferences' ] == 'basic' ) {
										$checked = 'checked';
									}
								}
								?>
                                <div class="eael-quick-setup-post-grid">
                                    <h3 class="eael-quick-setup-title"><?php echo esc_html( $element[ 'title' ] ); ?></h3>
                                    <label class="eael-quick-setup-toggler">
                                        <input data-preferences="<?php echo esc_attr( $preferences ); ?>" type="checkbox"
                                               class="eael-element" id="<?php echo esc_attr( $element[ 'key' ] ); ?>"
                                               name="eael_element[<?php echo esc_attr( $element[ 'key' ] ); ?>]"
											<?php echo esc_attr( $checked ); ?> >
                                        <span class="eael-quick-setup-toggler-icons"></span>
                                    </label>
                                </div>
							<?php endforeach; ?>
                        </div>
                    </div>
				<?php endforeach; ?>
                <div class="eael-quick-setup-overlay">
                    <button type="button" id="eael-elements-load-more" class="button eael-quick-setup-btn">
	                    <?php _e( 'View All', 'essential-addons-for-elementor-lite' ); ?>
                        <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/el-load.svg' ); ?>"
                             alt="<?php _e( 'View All', 'essential-addons-for-elementor-lite' ); ?>">
                    </button>
                </div>
            </div>
        </div>
		<?php
	}

	public function go_pro() {
		?>
        <div id="go-pro" class="eael-quick-setup-tab-content go_pro setup-content" style="display:none">
            <div class="eael-quick-setup-intro">
                <div class="eael-quick-setup-logo">
                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/go-pro.svg' ); ?>"
                         alt="<?php _e( 'Go Pro Logo', 'essential-addons-for-elementor-lite' ); ?>">
                </div>
                <h2 class="eael-quick-setup-title">
					<?php _e( 'Enhance Your Elementor Experience By Unlocking 35+ Advanced PRO Elements', 'essential-addons-for-elementor-lite' ); ?>
                </h2>
            </div>
            <div class="eael-quick-setup-input-group">
				<?php foreach ( $this->pro_elements() as $key => $elements ): ?>
                    <a target="_blank" href="<?php echo esc_url( $elements[ 'link' ] ); ?>"
                       class="eael-quick-setup-content">
                            <span class="eael-quick-setup-icon">
                                <img src="<?php echo esc_url( $elements[ 'logo' ] ); ?>"
                                     alt="<?php echo esc_attr( $elements[ 'title' ] ); ?>">
                            </span>
                        <p class="eael-quick-setup-title"><?php echo esc_html( $elements[ 'title' ] ); ?></p>
                    </a>

				<?php endforeach; ?>
            </div>
            <div class="eael-quick-setup-pro-button-wrapper">
                <a target="_blank" href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor"
                   class="button eael-quick-setup-btn eael-quick-setup-pro-button">
					<?php _e( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ); ?>
                </a>
            </div>
        </div>
		<?php
	}

	public function templately_integrations() {

		if ( $this->templately_status || $this->get_local_plugin_data( 'templately/templately.php' ) !== false ) {
			return false;
		}

		?>
		<div id="templately" class="eael-quick-setup-tab-content templately setup-content" style="display: none;">
			<div>
			<div class="eael-quick-setup-title">
				<?php printf( __( '<span class="eael-quick-setup-highlighted-red">%s</span> %s', 'essential-addons-for-elementor-lite' ), '5000+', 'Ready Templates' ); ?>
			</div>
			<div class="eael-quick-setup-text">
				<?php _e( 'Unlock an extensive collection of ready WordPress templates, along with full site import & cloud collaboration features', 'essential-addons-for-elementor-lite' ); ?>
			</div>
			<ul class="eael-quick-setup-list">
				<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.700012 1.60117C0.700012 1.36248 0.794833 1.13356 0.963616 0.964776C1.1324 0.795993 1.36132 0.701172 1.60001 0.701172H12.4C12.6387 0.701172 12.8676 0.795993 13.0364 0.964776C13.2052 1.13356 13.3 1.36248 13.3 1.60117V3.40117C13.3 3.63987 13.2052 3.86878 13.0364 4.03757C12.8676 4.20635 12.6387 4.30117 12.4 4.30117H1.60001C1.36132 4.30117 1.1324 4.20635 0.963616 4.03757C0.794833 3.86878 0.700012 3.63987 0.700012 3.40117V1.60117ZM0.700012 7.00117C0.700012 6.76248 0.794833 6.53356 0.963616 6.36478C1.1324 6.19599 1.36132 6.10117 1.60001 6.10117H7.00001C7.23871 6.10117 7.46763 6.19599 7.63641 6.36478C7.80519 6.53356 7.90001 6.76248 7.90001 7.00117V12.4012C7.90001 12.6399 7.80519 12.8688 7.63641 13.0376C7.46763 13.2064 7.23871 13.3012 7.00001 13.3012H1.60001C1.36132 13.3012 1.1324 13.2064 0.963616 13.0376C0.794833 12.8688 0.700012 12.6399 0.700012 12.4012V7.00117ZM10.6 6.10117C10.3613 6.10117 10.1324 6.19599 9.96362 6.36478C9.79483 6.53356 9.70001 6.76248 9.70001 7.00117V12.4012C9.70001 12.6399 9.79483 12.8688 9.96362 13.0376C10.1324 13.2064 10.3613 13.3012 10.6 13.3012H12.4C12.6387 13.3012 12.8676 13.2064 13.0364 13.0376C13.2052 12.8688 13.3 12.6399 13.3 12.4012V7.00117C13.3 6.76248 13.2052 6.53356 13.0364 6.36478C12.8676 6.19599 12.6387 6.10117 12.4 6.10117H10.6Z"
	  fill="url(#paint0_linear_810_832)"/>
<defs>
<linearGradient id="paint0_linear_810_832" x1="7.00001" y1="0.701172" x2="7.00001" y2="13.3012" gradientUnits="userSpaceOnUse">
<stop stop-color="#9373FF"/>
<stop offset="1" stop-color="#7650F6"/>
</linearGradient>
</defs>
</svg>
					</span>
					<?php _e( 'Stunning, Ready Website Templates', 'essential-addons-for-elementor-lite' ); ?>
				</li>
				<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3 0.75C1.75736 0.75 0.75 1.75736 0.75 3V14.25C0.75 15.4927 1.75736 16.5 3 16.5H15C16.2427 16.5 17.25 15.4927 17.25 14.25V5.25C17.25 4.00736 16.2427 3 15 3H9L7.40901 1.40901C6.98705 0.987053 6.41476 0.75 5.81802 0.75H3ZM9 5.25C9.41422 5.25 9.75 5.58578 9.75 6V10.9394L10.7197 9.96968C11.0126 9.6768 11.4874 9.6768 11.7803 9.96968C12.0732 10.2626 12.0732 10.7374 11.7803 11.0303L9.53032 13.2803C9.23745 13.5732 8.76255 13.5732 8.46968 13.2803L6.21967 11.0303C5.92678 10.7374 5.92678 10.2626 6.21967 9.96968C6.51256 9.6768 6.98744 9.6768 7.28033 9.96968L8.25 10.9394V6C8.25 5.58578 8.58577 5.25 9 5.25Z" fill="url(#paint0_linear_922_1148)"/>
<defs>
<linearGradient id="paint0_linear_922_1148" x1="9" y1="0.75" x2="9" y2="16.5" gradientUnits="userSpaceOnUse">
<stop stop-color="#FFCD91"/>
<stop offset="1" stop-color="#FAAD50"/>
</linearGradient>
</defs>
</svg>
					</span>
					<?php _e( 'One-Click Full Site Import', 'essential-addons-for-elementor-lite' ); ?>
				</li>
				<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.5 16.5C0.5 13.1863 3.18629 10.5 6.5 10.5C9.81373 10.5 12.5 13.1863 12.5 16.5H0.5ZM6.5 9.75C4.01375 9.75 2 7.73625 2 5.25C2 2.76375 4.01375 0.75 6.5 0.75C8.98625 0.75 11 2.76375 11 5.25C11 7.73625 8.98625 9.75 6.5 9.75ZM12.0221 11.4249C14.3362 12.0163 16.0759 14.0426 16.2377 16.5H14C14 14.5427 13.2502 12.7604 12.0221 11.4249ZM10.5051 9.71767C11.7296 8.61915 12.5 7.02455 12.5 5.25C12.5 4.187 12.2235 3.18856 11.7387 2.32265C13.4565 2.66548 14.75 4.18099 14.75 6C14.75 8.07188 13.0719 9.75 11 9.75C10.8322 9.75 10.667 9.73897 10.5051 9.71767Z"
	  fill="url(#paint0_linear_810_846)"/>
<defs>
<linearGradient id="paint0_linear_810_846" x1="8.36885" y1="0.75" x2="8.36885" y2="16.5" gradientUnits="userSpaceOnUse">
<stop stop-color="#FFBAC4"/>
<stop offset="1" stop-color="#FF7B8E"/>
</linearGradient>
</defs>
</svg>
					</span>
					<?php _e( 'Add Team Members & Collaborate', 'essential-addons-for-elementor-lite' ); ?>
				</li>
				<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.75 14.75H5.25C2.76472 14.75 0.75 12.7353 0.75 10.25C0.75 8.28845 2.0051 6.62 3.75603 6.00393C3.75202 5.91979 3.75 5.83513 3.75 5.75C3.75 2.85051 6.10051 0.5 9 0.5C11.8995 0.5 14.25 2.85051 14.25 5.75C14.25 5.83513 14.248 5.91979 14.244 6.00393C15.9949 6.62 17.25 8.28845 17.25 10.25C17.25 12.7353 15.2353 14.75 12.75 14.75Z"
	  fill="url(#paint0_linear_810_854)"/>
<defs>
<linearGradient id="paint0_linear_810_854" x1="9" y1="0.5" x2="9" y2="14.75" gradientUnits="userSpaceOnUse">
<stop stop-color="#6CC7FF"/>
<stop offset="1" stop-color="#2FA7F1"/>
</linearGradient>
</defs>
</svg>
					</span>
					<?php _e( 'Templates Cloud with Workspace', 'essential-addons-for-elementor-lite' ); ?>
				</li>
			</ul>
			</div><img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-qs-img.png' )?>" alt="">
		</div>
		<?php
	}

	/**
	 * EAEL plugin integrations
	 */
	public function eael_integrations() {
		?>
        <div id="integrations" class="eael-quick-setup-tab-content integrations setup-content" style="display: none">
            <div class="eael-quick-setup-admin-block-wrapper">
				<?php foreach ( $this->get_plugin_list() as $plugin ) { ?>
                    <div class=" eael-quick-setup-admin-block eael-quick-setup-admin-block-integrations">
                        <span class="eael-quick-setup-logo">
                            <img src="<?php echo esc_url( $plugin[ 'logo' ] ); ?>" alt="logo"/>
                        </span>
                        <h4 class="eael-quick-setup-title"><?php echo esc_html( $plugin[ 'title' ] ); ?></h4>
                        <p class="eael-quick-setup-text"><?php echo esc_textarea( $plugin[ 'desc' ] ) ; ?></p>

						<?php if ( $this->get_local_plugin_data( $plugin[ 'basename' ] ) === false ) { ?>
                            <button class="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer"
                                    data-action="install"
                                    data-slug="<?php echo esc_attr( $plugin[ 'slug' ] ); ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></button>
						<?php } else { ?>
							<?php if ( is_plugin_active( $plugin[ 'basename' ] ) ) { ?>
                                <button class="wpdeveloper-plugin-installer button__white-not-hover eael-quick-setup-wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></button>
							<?php } else { ?>
                                <button class="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer"
                                        data-action="activate"
                                        data-basename="<?php echo esc_attr( $plugin[ 'basename' ] ); ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></button>
							<?php } ?>
						<?php } ?>
                    </div>
				<?php } ?>
            </div>
        </div>
		<?php
	}

	public function final_step() {
		?>
        <div id="finalize" class="eael-quick-setup-tab-content finalize setup-content" style="display: none">
            <div class="eael-quick-setup-modal">
                <div class="eael-quick-setup-modal-content">
                    <div class="eael-quick-setup-modal-header">
                        <div class="eael-quick-setup-intro">
                            <h2 class="eael-quick-setup-title">
	                            <?php _e( '💪 Make Essential Addons more awesome by being our Contributor', 'essential-addons-for-elementor-lite' ); ?>
                            </h2>
                        </div>
                    </div>
                    <div class="eael-quick-setup-modal-body">
                        <div class="eael-quick-setup-message-wrapper">
                            <div class="eael-quick-setup-message">
	                            <?php _e( 'We collect non-sensitive diagnostic data and plugin usage
                    information. Your site URL, WordPress & PHP version, plugins &
                    themes and email address to send you the discount coupon. This
                    data lets us make sure this plugin always stays compatible with
                    the most popular plugins and themes. No spam, we promise.', 'essential-addons-for-elementor-lite' ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="eael-quick-setup-modal-footer">
                        <button  class="eael-button eael-quick-setup-button eael-setup-wizard-save"><?php _e('No, Thanks','essential-addons-for-elementor-lite') ?></button>
                        <button id="eael-count-me-bt" class="eael-setup-wizard-save eael-button eael-quick-setup-button eael-quick-setup-filled-button">
                            <?php _e('Count me in','essential-addons-for-elementor-lite') ?>
                        </button>
                    </div>
                    <input type="hidden" value="0" id="eael_user_email_address" name="eael_user_email_address">
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * get_plugin_list
	 * @return array
	 */
	public function get_plugin_list() {
		return [
			[
				'slug'     => 'betterdocs',
				'basename' => 'betterdocs/betterdocs.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/bd-new.svg',
				'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'embedpress',
				'basename' => 'embedpress/embedpress.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ep-logo.png',
				'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'notificationx',
				'basename' => 'notificationx/notificationx.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/nx-logo.svg',
				'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'easyjobs',
				'basename' => 'easyjobs/easyjobs.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/easy-jobs-logo.svg',
				'title'    => __( 'easy.jobs', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'wp-scheduled-posts',
				'basename' => 'wp-scheduled-posts/wp-scheduled-posts.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/wscp.svg',
				'title'    => __( 'SchedulePress', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Best Content Marketing Tool For WordPress – Schedule, Organize, & Auto Share Blog Posts. Take a quick glance at your content planning with Schedule Calendar, Auto & Manual Scheduler and  more.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'betterlinks',
				'basename' => 'betterlinks/betterlinks.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/btl.svg',
				'title'    => __( 'BetterLinks', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Best Link Shortening tool to create, shorten and manage any URL to help you cross-promote your brands & products. Gather analytics reports, run successfully marketing campaigns easily & many more.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'essential-blocks',
				'basename' => 'essential-blocks/essential-blocks.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/eb-new.svg',
				'title'    => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Enhance your Gutenberg experience with 40+ unique blocks (more coming soon). Add power to the block editor using our easy-to-use blocks which are designed to make your next WordPress page or posts design easier and prettier than ever before.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'better-payment',
				'basename' => 'better-payment/better-payment.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bp.svg',
				'title'    => __( 'Better Payment', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Better Payment streamlines transactions in Elementor, integrating PayPal, Stripe, advanced analytics, validation, and Elementor forms for the most secure & efficient payments.', 'essential-addons-for-elementor-lite' ),
			],
		];
	}

	/**
	 * get_local_plugin_data
	 *
	 * @param mixed $basename
	 * @return array|false
	 */
	public function get_local_plugin_data( $basename = '' ) {

		if ( empty( $basename ) ) {
			return false;
		}

		if ( !function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		if ( !isset( $plugins[ $basename ] ) ) {
			return false;
		}

		return $plugins[ $basename ];
	}

	/**
	 * Save setup wizard data
	 */
	public function save_setup_wizard_data() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields );

		if ( isset( $fields[ 'eael_user_email_address' ] ) && intval( $fields[ 'eael_user_email_address' ] ) == 1 ) {
			$this->wpins_process();
		}
		update_option( 'eael_setup_wizard', 'complete' );
		if ( $this->save_element_list( $fields ) ) {
			wp_send_json_success( [ 'redirect_url' => esc_url( admin_url( 'admin.php?page=eael-settings' ) ) ] );
		}
		wp_send_json_error();
	}

	/**
	 * save_eael_elements_data
	 */
	public function save_eael_elements_data() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields );

		if ( $this->save_element_list( $fields ) ) {
			wp_send_json_success();
		}
		wp_send_json_error();
	}

	/**
	 * save_element_list
	 * @param $fields
	 * @return bool
	 */
	public function save_element_list( $fields ) {
		if ( !empty( $fields ) ) {

			$el_list      = $fields[ 'eael_element' ];
			$save_element = [];
			foreach ( $GLOBALS[ 'eael_config' ][ 'elements' ] as $key => $item ) {
				$save_element[ $key ] = ( isset( $el_list[ $key ] ) ) ? 1 : '';
			}
			$save_element = array_merge( $save_element, $this->get_dummy_widget() );
			update_option( 'eael_save_settings', $save_element );
			return true;
		}
		return false;
	}

	/**
	 * get_element_list
	 * @return array[]
	 */
	public function get_element_list() {
		return [
			'content-elements'         => [
				'title'    => __( 'Content Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'creative-btn',
						'title'       => __( 'Creative Button', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'team-members',
						'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'testimonials',
						'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'flip-box',
						'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'info-box',
						'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'dual-header',
						'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'tooltip',
						'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'adv-accordion',
						'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'adv-tabs',
						'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'feature-list',
						'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',

					],
					[
						'key'         => 'sticky-video',
						'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'event-calendar',
						'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'         => 'simple-menu',
						'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
				]
			],
			'dynamic-content-elements' => [
				'title'    => __( 'Dynamic Content Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'post-grid',
						'title'       => __( 'Post Grid', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'   => 'post-timeline',
						'title' => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'         => 'data-table',
						'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'   => 'advanced-data-table',
						'title' => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'         => 'content-ticker',
						'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'nft-gallery',
						'title'       => __( 'NFT Gallery', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'business-reviews',
						'title'       => __( 'Business Reviews', 'essential-addons-for-elementor-lite' ),
					],
				]
			],
			'creative-elements'        => [
				'title'    => __( 'Creative Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'count-down',
						'title'       => __( 'Countdown', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'fancy-text',
						'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'filter-gallery',
						'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'image-accordion',
						'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'progress-bar',
						'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'interactive-circle',
						'title'       => __( 'Interactive Circle', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
                    [
                        'key'         => 'svg-draw',
                        'title'       => __( 'SVG Draw', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
					[
						'key'         => 'fancy-chart',
						'title'       => __( 'Fancy Chart', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
				]
			],
			'marketing-elements'       => [
				'title'    => __( 'Marketing & Social Feed Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'call-to-action',
						'title'       => __( 'Call To Action', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'price-table',
						'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'basic',
					],
					[
						'key'         => 'twitter-feed',
						'title'       => __( 'Twitter Feed', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'         => 'facebook-feed',
						'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],

				]
			],
			'form-styler-elements'     => [
				'title'    => __( 'Form Styler Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'contact-form-7',
						'title'       => __( 'Contact Form 7', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'   => 'weforms',
						'title' => __( 'weForms', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'ninja-form',
						'title' => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'gravity-form',
						'title' => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'caldera-form',
						'title' => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'wpforms',
						'title' => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'fluentform',
						'title' => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'formstack',
						'title' => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'typeform',
						'title' => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'         => 'login-register',
						'title'       => __( 'Login Register Form', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
				]
			],
			'documentation-elements'   => [
				'title'    => __( 'Documentation Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'   => 'betterdocs-category-grid',
						'title' => __( 'BetterDocs Category Grid', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'betterdocs-category-box',
						'title' => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),

					],
					[
						'key'   => 'betterdocs-search-form',
						'title' => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
					]
				]
			],
			'woocommerce-elements'     => [
				'title'    => __( 'WooCommerce Elements', 'essential-addons-for-elementor-lite' ),
				'elements' => [
					[
						'key'         => 'product-grid',
						'title'       => __( 'Woo Product Grid', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'         => 'woo-product-list',
						'title'       => __( 'Woo Product List', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'   => 'woo-product-carousel',
						'title' => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'woo-checkout',
						'title' => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'woo-cart',
						'title' => __( 'Woo Cart', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'   => 'woo-cross-sells',
						'title' => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
					],
					[
						'key'         => 'woo-product-compare',
						'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					],
					[
						'key'         => 'woo-product-gallery',
						'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
						'preferences' => 'advance',
					]
				]
			]
		];
	}

	public function pro_elements() {
		return [
			'event-calendar'     => [
				'title' => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/event-calendar/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/event-cal.svg' ),
			],
			'toggle'             => [
				'title' => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/content-toggle/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/toggle.svg' ),
			],
			'adv-google-map'     => [
				'title' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/advanced-google-map/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/adv-google-map.svg' ),
			],
			'dynamic-gallery'    => [
				'title' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/dynamic-gallery/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/dynamic-gallery.svg' ),
			],
			'image-hotspots'     => [
				'title' => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/image-hotspots/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/image-hotspots.svg' ),
			],
			'lightbox-and-modal' => [
				'title' => __( 'Lightbox and Modal', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/lightbox-modal/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/lightbox-and-modal.svg' ),
			],
			'mailchimp'          => [
				'title' => __( 'Mailchimp', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/mailchimp/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/mailchimp.svg' ),
			],
			'instagram-feed'     => [
				'title' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
				'link'  => esc_url( 'https://essential-addons.com/elementor/instagram-feed/' ),
				'logo'  => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/instagram-feed.svg' ),
			]
		];
	}

	public static function redirect() {
		update_option( 'eael_setup_wizard', 'init' );
		wp_redirect( admin_url( 'admin.php?page=eael-setup-wizard' ) );
	}

	public function change_site_title() {
		?>
        <script>
			document.title = "<?php _e( 'Quick Setup Wizard- Essential Addons', 'essential-addons-for-elementor-lite' ); ?>"
        </script>
		<?php
	}

	public function wpins_process() {
		$plugin_name = basename( EAEL_PLUGIN_FILE, '.php' );
		if ( class_exists( '\Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker' ) ) {
			$tracker = \Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker::get_instance( EAEL_PLUGIN_FILE, [
				'opt_in'       => true,
				'goodbye_form' => true,
				'item_id'      => '760e8569757fa16992d8'
			] );
			$tracker->set_is_tracking_allowed( true );
			$tracker->do_tracking( true );
		}
	}

	public function get_dummy_widget() {
		return [
			'embedpress'                  => 1,
			'woocommerce-review'          => 1,
			'career-page'                 => 1,
			'crowdfundly-single-campaign' => 1,
			'crowdfundly-organization'    => 1,
			'crowdfundly-all-campaign'    => 1,
			'better-payment'              => 1,
		];
	}
}



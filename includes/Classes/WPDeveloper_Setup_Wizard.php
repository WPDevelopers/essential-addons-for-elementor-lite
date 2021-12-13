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
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'essential-addons-elementor' ),
				'success_image' => EAEL_PLUGIN_URL . 'assets/admin/images/success.gif',
			) );
		}
		return [];
	}

	/**
	 * Create admin menu for setup wizard
	 */
	public function admin_menu() {

		add_submenu_page(
			null,
			__( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
			__( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
			'manage_options',
			'eael-setup-wizard',
			[ $this, 'render_wizard' ]
		);
	}

	/**
	 * Render tav step
	 */
	public function tab_step() {
		!$this->templately_status ? $wizard_column = 'five' : $wizard_column = 'four';
		?>
        <ul class="eael-quick-setup-wizard <?php echo $wizard_column; ?>" data-step="1">
            <li class="eael-quick-setup-step active">
                <div class="eael-quick-setup-icon">1</div>
                <div class="eael-quick-setup-name"><?php _e( 'Configuration', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
            <li class="eael-quick-setup-step">
                <div class="eael-quick-setup-icon">2</div>
                <div class="eael-quick-setup-name"><?php _e( 'Elements', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
            <li class="eael-quick-setup-step">
                <div class="eael-quick-setup-icon">3</div>
                <div class="eael-quick-setup-name"><?php _e( 'Go PRO', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
			<?php if ( !$this->templately_status ): ?>
                <li class="eael-quick-setup-step">
                    <div class="eael-quick-setup-icon">4</div>
                    <div class="eael-quick-setup-name"><?php _e( 'Templately', 'essential-addons-for-elementor-lite' ); ?></div>
                </li>
			<?php endif; ?>
            <li class="eael-quick-setup-step">
                <div class="eael-quick-setup-icon">5</div>
                <div class="eael-quick-setup-name"></div>
            </li>
            <li class="eael-quick-setup-step">
                <div class="eael-quick-setup-icon">6</div>
                <div class="eael-quick-setup-name">
					<?php _e( 'Finalize', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
        </ul>
		<?php
	}

	public function configuration_tab() {
		?>
        <div class="eael-quick-setup-tab-content configuration">
            <div class="eael-quick-setup-intro">
                <div class="eael-quick-setup-logo">

                </div>
                <h2 class="eael-quick-setup-title">
					<?php _e( 'Get Started with Essential Addons 🚀', 'essential-addons-for-elementor-lite' ); ?>
                </h2>
                <p class="eael-quick-setup-text">
					<?php _e( 'Enhance your Elementor page building experience with 70+ amazing
                        elements & extensions 🔥', 'essential-addons-for-elementor-lite' ); ?>
                </p>
                <div class="eael-quick-setup-input-group">
                    <label class="eael-quick-setup-input config-list">
                        <input type="radio" name="eael_preferances" checked/>
                        <span class="eael-quick-setup-content">
                  <h3 class="eael-quick-setup-title"><?php _e( 'Basic', 'essential-addons-for-elementor-lite' ); ?></h3>
                  <p class="eael-quick-setup-text">
                    <?php _e( 'For websites where you want to only use the basic features
                    and keep your site lightweight. Most basic elements are
                    activated in this option.', 'essential-addons-for-elementor-lite' ); ?>
                  </p>
                </span>
                    </label>
                    <label class="eael-quick-setup-input config-list">
                        <input type="radio" name="eael_preferances"/>
                        <span class="eael-quick-setup-content">
                  <h3 class="eael-quick-setup-title"><?php _e( 'Advanced (Recommended)', 'essential-addons-for-elementor-lite' ); ?></h3>
                  <p class="eael-quick-setup-text">
                    <?php _e( 'For advanced users who are trying to build complex websites
                    with advanced functionalities with Elementor. All the
                    dynamic elements will be activated in this option.', 'essential-addons-for-elementor-lite' ); ?>
                  </p>
                </span>
                    </label>
                    <label class="eael-quick-setup-input config-list">
                        <input type="radio" name="eael_preferances"/>
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
        </div>
		<?php
	}

	/**
	 * Tav view content
	 */
	public function tab_content() {
		?>
        <div class="eael-setup-body">
            <form class="eael-setup-wizard-form" method="post">
                <div id="configuration" class="setup-content">
                    <div class="eael-input-group config-list">
                        <input id="basic"
                               value="basic"
                               class="eael_preferences" name="eael_preferences" type="radio" checked>
                        <label for="basic">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Basic (Recommended)', 'essential-addons-for-elementor-lite' ); ?></strong>
                                <p> <?php _e( 'For websites where you want to only use the basic features and keep your site
                                    lightweight. Most basic elements are activated in this option. ', 'essential-addons-for-elementor-lite' ); ?></p>
                            </div>
                        </label>
                    </div>
                    <div class="eael-input-group config-list">
                        <input id="advance" value="advance"
                               class="eael_preferences"
                               name="eael_preferences"
                               type="radio">
                        <label for="advance">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Advanced', 'essential-addons-for-elementor-lite' ) ?></strong>
                                <p> <?php _e( 'For advanced users who are trying to build complex websites with advanced
                                    functionalities with Elementor. All the dynamic elements will be activated in this
                                    option.', 'essential-addons-for-elementor-lite' ) ?> </p>
                            </div>
                        </label>
                    </div>
                    <div class="eael-input-group config-list">
                        <input id="custom" value="custom" class="eael_preferences" name="eael_preferences" name="radio"
                               type="radio">
                        <label for="custom">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Custom', 'essential-addons-for-elementor-lite' ); ?></strong>
                                <p> <?php _e( 'Pick this option if you want to configure the elements as per your wish.', 'essential-addons-for-elementor-lite' ); ?> </p>
                            </div>
                        </label>
                    </div>
                </div>
				<?php $this->eael_elements(); ?>
				<?php if ( !$this->templately_status ): ?>
                    <div id="templately" class="setup-content eael-box eael-templately-popup" style="background-image:
                            url('<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately.jpg'; ?>');">
						<?php if ( !is_plugin_active( 'templately/templately.php' ) ) $this->eael_templately_plugin_popup(); ?>
                    </div>
				<?php endif; ?>
				<?php $this->eael_integrations(); ?>
                <div id="finalize" class="setup-content eael-box">

                    <h2><?php _e( "Getting Started", "essential-addons-for-elementor-lite" ) ?></h2>
                    <p><?php _e( "Complete the Setup Wizard and Check out the walk-through tutorials to enhance your Elementor page
                        building experience", "essential-addons-for-elementor-lite" ) ?> 🔥</p>

                    <div class="eael-iframe">
                        <iframe src="https://www.youtube.com/embed/uuyXfUDqRZM" frameborder="0"></iframe>
                    </div>
                    <div class="eael-setup-final-info">
                        <div>
                            <div class="eael-input-group">
                                <input type="checkbox" id="eael_user_email_address" name="eael_user_email_address"
                                >
                                <label for="eael_user_email_address"><?php _e( 'Share non-sensitive diagnostic data and plugin
                                    usage
                                    information', 'essential-addons-for-elementor-lite' ) ?></label>
                            </div>
                            <p style="display: none"
                               class="eael-whatwecollecttext"><?php _e( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress &amp; PHP version, plugins &amp; themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.', 'essential-addons-for-elementor-lite' ) ?></p>
                            <button type="button"
                                    class="btn-collect"><?php _e( 'What Do We Collect?', 'essential-addons-for-elementor-lite' ); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
		<?php
	}

	/**
	 * Footer content
	 */
	public function setup_wizard_footer() {
		?>
        <div class="eael-setup-footer">
            <button id="eael-prev"
                    class="button eael-btn"><?php _e( '< Previous', 'essential-addons-for-elementor-lite' ) ?></button>
            <button id="eael-next"
                    class="button eael-btn"><?php _e( 'Next >', 'essential-addons-for-elementor-lite' ) ?></button>
            <button id="eael-save" style="display: none"
                    class="button eael-btn eael-setup-wizard-save"><?php _e( 'Finish', 'essential-addons-for-elementor-lite' ) ?></button>
        </div>
		<?php
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
	 * EAEL elements list
	 */
	public function eael_elements() {

		?>
        <div class="eael-quick-setup-tab-content elements">
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
				<?php foreach ( $this->get_element_list() as $key => $item ): ?>
                <div class="eael-quick-setup-post-grid-panel">
                    <h3 class="eael-quick-setup-post-grid-panel-title"><?php echo esc_html( $item[ 'title' ] ); ?></h3>
                </div>
                <div class="eael-quick-setup-post-grid-wrapper eael-<?php echo $key; ?>">
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
                        <h3 class="eael-quick-setup-title"><?php echo $element[ 'title' ]; ?></h3>
                        <label class="eael-quick-setup-toggler">
                            <input data-preferences="<?php echo $preferences; ?>" type="checkbox"
                                   class="eael-element" id="<?php echo $element[ 'key' ]; ?>"
                                   name="eael_element[<?php echo $element[ 'key' ]; ?>]"
								<?php echo $checked; ?> >
                            <span class="eael-quick-setup-toggler-icons"></span>
                        </label>
                    </div>
                </div>
			<?php endforeach; ?>
            </div>
			<?php endforeach; ?>
        </div>
		<?php
	}

	public function go_pro() {
		?>
        <div class="eael-quick-setup-tab-content go_pro">
            <div class="eael-quick-setup-intro">
                <div class="eael-quick-setup-logo">
                </div>
                <h2 class="eael-quick-setup-title">
					<?php _e( 'Get Access to 30+ Advanced PRO Elements to Enhance Your
                    Elementor Site Building Experience', 'essential-addons-for-elementor-lite' ); ?>
                </h2>
                <div class="eael-quick-setup-input-group">
                    <label class="eael-quick-setup-input config-list">
                        <input type="radio" name="items" checked/>
                        <span class="eael-quick-setup-content">
                            <span class="eael-quick-setup-icon">

                            </span>
                            <p class="eael-quick-setup-title">Event Calendar</p>
                        </span>
                    </label>
                    <label class="eael-quick-setup-input config-list">
                        <input type="radio" name="items" checked/>
                        <span class="eael-quick-setup-content">
                            <span class="eael-quick-setup-icon">

                            </span>
                            <p class="eael-quick-setup-title">Event Calendar</p>
                        </span>
                    </label>
                </div>
                <div class="eael-quick-setup-pro-button-wrapper">
                    <button class="button eael-quick-setup-btn eael-quick-setup-pro-button">
						<?php _e( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ); ?>
                    </button>
                </div>
            </div>
        </div>
		<?php
	}

	public function templately_integrations() {
		?>
        <div class="eael-quick-setup-tab-content templately">
            <div class="eael-quick-setup-logo">
                <button class="button eael-quick-setup-templately-button">
					<?php _e( 'Install Templately', 'essential-addons-for-elementor-lite' ); ?>
                </button>
                <div class="eael-tm-logo">

                </div>

            </div>
            <div class="eael-quick-setup-title">
				<?php printf( __( '%s <span class="eael-quick-setup-highlighted-red">%s</span> %s', 'essential-addons-for-elementor-lite' ), 'Unlock', '1600+', 'Ready Templates Built With Elementor & Essential Addons From
                    Templately.' ); ?>
            </div>
            <div class="eael-quick-setup-text">
				<?php _e( 'Get Access to amazing features and boost your Elementor page
                    building experience with Templately 👇', 'essential-addons-for-elementor-lite' ); ?>
            </div>
            <ul class="eael-quick-setup-list">
                <li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">🌟</span>
					<?php _e( 'Access Thousands Of Stunning, Ready Website Templates', 'essential-addons-for-elementor-lite' ); ?>
                </li>
                <li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">🔥</span>
					<?php _e( 'Save Your Design Anywhere With MyCloud Storage Space', 'essential-addons-for-elementor-lite' ); ?>
                </li>
                <li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">🚀</span>
					<?php _e( 'Add Team Members & Collaborate On Cloud With Templately WorkSpace', 'essential-addons-for-elementor-lite' ); ?>
                </li>
            </ul>
        </div>
		<?php
	}

	/**
	 * EAEL plugin integrations
	 */
	public function eael_integrations() {
		?>
        <div id="integrations" class="setup-content eael-box">
            <div class="row">
				<?php foreach ( $this->get_plugin_list() as $plugin ) { ?>
                    <div class="col-one-third">
                        <div class="eael-admin-block-wrapper">
                            <div class="eael-admin-block eael-admin-block-integrations">
                                <div class="eael-admin-block-content">
                                    <div class="eael-admin-block-integrations-logo">
                                        <img src="<?php echo $plugin[ 'logo' ]; ?>" alt="logo"/>
                                    </div>
                                    <h2 class="eael-admin-block-integrations-title"><?php echo $plugin[ 'title' ]; ?></h2>
                                    <p class="eael-admin-block-integrations-text"><?php echo $plugin[ 'desc' ]; ?></p>
                                    <div class="eael-admin-block-integrations-btn-wrap">
										<?php if ( $this->get_local_plugin_data( $plugin[ 'basename' ] ) === false ) { ?>
                                            <a class="ea-button wpdeveloper-plugin-installer" data-action="install"
                                               data-slug="<?php echo $plugin[ 'slug' ]; ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
										<?php } else { ?>
											<?php if ( is_plugin_active( $plugin[ 'basename' ] ) ) { ?>
                                                <a class="ea-button wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
											<?php } else { ?>
                                                <a class="ea-button wpdeveloper-plugin-installer" data-action="activate"
                                                   data-basename="<?php echo $plugin[ 'basename' ]; ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
											<?php } ?>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php } ?>
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
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd-logo.png',
				'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'embedpress',
				'basename' => 'embedpress/embedpress.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep-logo.png',
				'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'reviewx',
				'basename' => 'reviewx/reviewx.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/review-logo.png',
				'title'    => __( 'ReviewX', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'ReviewX lets you get instant customer ratings and multi criteria reviews to add credibility to your WooCommerce Store and increase conversion rates.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'notificationx',
				'basename' => 'notificationx/notificationx.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
				'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'easyjobs',
				'basename' => 'easyjobs/easyjobs.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/easy-jobs-logo.png',
				'title'    => __( 'EasyJobs', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'slug'     => 'crowdfundly',
				'basename' => 'crowdfundly/crowdfundly.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/crowdfundly-logo.png',
				'title'    => __( 'Crowdfundly', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Crowdfundly is a Software as a Service (SaaS) digital crowdfunding solution. Best fundraising solution in WordPress with Elementor & WooCommerce support.', 'essential-addons-for-elementor-lite' ),
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
	 * Templately promotion popup
	 */
	public function eael_templately_plugin_popup() {
		?>
        <div class="eael-popup__wrapper">
            <div class="eael-popup__block">
                <div class="eael-popup__logo">
                    <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately/logo.svg'; ?>" alt="">
                </div>
                <p><?php _e( 'Get the best out of Essential Addons & boost your Elementor design experience with Templately. Deploy in hundreds of websites with 1-click, push to cloud and collaborate with your whole team to build sites faster than ever.', 'essential-addons-for-elementor-lite' ) ?></p>

				<?php if ( $this->get_local_plugin_data( 'templately/templately.php' ) === false ) { ?>
                    <a class="eael-popup__button wpdeveloper-plugin-installer" data-action="install"
                       data-slug="<?php echo 'templately'; ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
				<?php } else { ?>
					<?php if ( is_plugin_active( 'templately/templately.php' ) ) { ?>
                        <a class="eael-popup__button wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
					<?php } else { ?>
                        <a class="eael-popup__button wpdeveloper-plugin-installer" data-action="activate"
                           data-basename="<?php echo 'templately/templately.php'; ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
					<?php } ?>
				<?php } ?>
            </div>
        </div>
		<?php
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

		parse_str( $_POST[ 'fields' ], $fields );

		if ( isset( $fields[ 'eael_user_email_address' ] ) ) {
			$this->wpins_process();
		}
		update_option( 'eael_setup_wizard', 'complete' );
		if ( $this->save_element_list( $fields ) ) {
			wp_send_json_success( [ 'redirect_url' => admin_url( 'admin.php?page=eael-settings' ) ] );
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

		parse_str( $_POST[ 'fields' ], $fields );

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
					]
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
						'title'       => __( 'Product Grid', 'essential-addons-for-elementor-lite' ),
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
		];
	}
}



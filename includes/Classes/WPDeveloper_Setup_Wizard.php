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
		<section id="ea__onboard--wrapper" class="ea__onboard--wrapper">
        <section class="ea__onboard-main-wrapper">
            <div class="ea__onboard-nav-list flex justify-between">
                <div class="ea__onboard-nav active">
                    <span class="ea__nav-count">
                        1
                    </span>
                    <span class="ea__nav-text">Getting Started</span>
                </div>
                <div class="ea__onboard-nav">
                    <span class="ea__nav-count">
                        2
                    </span>
                    <span class="ea__nav-text">Configuration</span>
                </div>
                <div class="ea__onboard-nav">
                    <span class="ea__nav-count">
                        3
                    </span>
                    <span class="ea__nav-text">Elements</span>
                </div>

                <div class="ea__onboard-nav">
                    <span class="ea__nav-count">
                        4
                    </span>
                    <span class="ea__nav-text">Go PRO</span>
                </div>

                <div class="ea__onboard-nav">
                    <span class="ea__nav-count">
                        5
                    </span>
                    <span class="ea__nav-text">Templately</span>
                </div>

                <div class="ea__onboard-nav">
                    <span class="ea__nav-count">
                        6
                    </span>
                    <span class="ea__nav-text">Integrations</span>
                </div>
            </div>
            <div class="ea__onboard-content-wrapper min-h-538">
                <div class="ea__onboard-content">
                    <div class="ea__onboard-content-top">
						<a href="https://www.youtube.com/watch?v=ZISSbnHo0rE&ab_channel=WPDeveloper" target="_blank">
							<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/youtube-promo.png' )?>" alt="Youtube Promo">
						</a>
                        <h3>Getting Started</h3>
                        <p>Easily get started with this easy setup wizard and complete setting up your Knowledge Base.
                        </p>
                    </div>
                    <div class="ea__next-step-wrapper" id="ea__dashboard--wrapper">
                        <p>By clicking this button I am allowing this app to
                            collect my information. <span class="collect-info">What We Collect?</span></p>
                        <button class="primary-btn install-btn">
                            Proceed to Next Step
                            <i class="ea-dash-icon ea-install"></i>
                        </button>
                        <span class="skip-item">Skip This Step</span>
                    </div>
                </div>
            </div>


			<div class="ea__onboard-content-wrapper mb-4 min-h-538">
                <div class="ea__onboard-content">
                    <div class="ea__onboard-content-top">
						<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ea-new.png' ); ?>"
                         	alt="<?php _e( 'EA Logo', 'essential-addons-for-elementor-lite' ); ?>">

                        <h3>Get Started with Essential Addons 🚀</h3>
                        <p>Enhance your Elementor page building experience with 50+ amazing elements & extensions 🔥</p>
                    </div>
                    <div class="ea__onboard-content-select">
                        <label class="flex-1 checkbox--label">
                            <input name="choose-provider" id="1" type="radio" checked class="eael-d-none">
                            <span class="select--wrapper">
                                <span class="check-mark"></span>
                                <h4>Basic <span>(Recommended)</span></h4>
                                <p>For websites where you want to only use the basic features and keep your site
                                    lightweight. Most basic elements are activated in this option.</p>
                            </span>
                        </label>
                        <label class="flex-1 checkbox--label">
                            <input name="choose-provider" id="2" type="radio" class="eael-d-none">
                            <span class="select--wrapper">
                                <span class="check-mark"></span>
                                <h4>Advanced</h4>
                                <p>For advanced users who are trying to build complex websites with advanced
                                    functionalities with Elementor. All the dynamic elements will be activated in this
                                    option.</p>
                            </span>
                        </label>
                        <label class="flex-1 checkbox--label">
                            <input name="choose-provider" id="3" type="radio" class="eael-d-none">
                            <span class="select--wrapper">
                                <span class="check-mark"></span>
                                <h4>Custom</h4>
                                <p>Pick this option if you want to configure the elements as per your wish.</p>
                            </span>
                        </label>

                    </div>
                </div>
            </div>
            <div id="ea__dashboard--wrapper" class="ea__section-wrapper flex flex-end">
                <button class="primary-btn install-btn flex gap-2 items-center">
                    Next
                    <i class="ea-dash-icon ea-right-arrow-long"></i>
                </button>
            </div>


        </section>
    </section>

	<section id="ea__dashboard--wrapper" class="ea__onboard--wrapper">
        <section class="ea__onboard-main-wrapper">
            
            <div class="ea__onboard-content-wrapper ea__onboard-elements mb-4 min-h-538">
                <div class="ea__connect-others flex gap-4 justify-between items-start mb-10">
                    <div class="flex gap-4 flex-1">
                        <div class="ea__others-icon eaicon-1">
                            <i class="ea-dash-icon ea-elements"></i>
                        </div>
                        <div class="max-w-454">
                            <h4>Turn on the Elements that you need</h4>
                            <p>Enable/Disable the elements anytime you want from Essential Addons Dashboard</p>
                        </div>
                    </div>
                    <button class="primary-btn changelog-btn flex items-center gap-2">
                        View All
                        <i class="ea-dash-icon ea-right-arrow-long"></i>
                    </button>
                </div>
                <div class="onBoard-scroll-wrap">
                    <div id="Content" class="ea__contents">
                        <div class="flex items-center gap-2 justify-between mb-4">
                            <h3 class="ea__content-title">Content Elements</h3>
                        </div>
                        <div class="ea__content-wrapper mb-10">
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Creative Button</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Team Member</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Testimonial</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox" checked="checked">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Flip Box</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox" checked="checked">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Info Box</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Dual Color Heading</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox" checked="checked">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Tooltip</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Advanced Accordion</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Advanced Tabs</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Feature List</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Offcanvas</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox" checked="checked">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Advanced Menu</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Toggle</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Testimonial Slider</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Static Product</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Team Member Carousel</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Sticky Video</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Event Calendar</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Simple Menu</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="ea__content-items">
                                <div class="ea__content-head">
                                    <h5 class="toggle-label">Advanced Search</h5>
                                    <label class="toggle-wrap">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ea__section-overlay"></div>
            </div>
            <div class="ea__section-wrapper flex flex-end gap-4">
                <button class="previous-btn flex gap-2 items-center">
                    <i class="ea-dash-icon ea-left-arrow-long"></i>
                    Previous
                </button>
                <button class="primary-btn install-btn flex gap-2 items-center">
                    Next
                    <i class="ea-dash-icon ea-right-arrow-long"></i>
                </button>
            </div>
        </section>
    </section>

	<section id="ea__dashboard--wrapper" class="ea__onboard--wrapper">
        <section class="ea__onboard-main-wrapper">
            
            <div class="ea__onboard-content-wrapper ea__onboard-pro mb-4 min-h-538">
                <div class="ea__connect-others flex gap-4 justify-between items-start mb-10">
                    <div class="flex gap-4 flex-1">
                        <div class="ea__others-icon eaicon-1">
                            <i class="ea-dash-icon ea-lock"></i>
                        </div>
                        <div class="max-w-454">
                            <h4>Unlocking 35+ Advanced PRO Elements</h4>
                            <p>Lorem ipsum is placeholder text commonly used in the graphic, </p>
                        </div>
                    </div>
                    <a href="#">
                        <button class="upgrade-button">
                            <i class="ea-dash-icon ea-crown-1"></i>
                            Upgrade to PRO
                        </button>
                    </a>
                </div>
                <div class="ea__pro-features flex justify-between items-center">
                    <div class="ea__features-content">
                        <h2>Explore Premiere Pro features</h2>
                        <p class="mb-7">Learn all about the tools and techniques you can use to edit videos,
                            animate titles,
                            add effects, mix sound, and more.
                        </p>
                        <a href="#">
                            <button class="primary-btn changelog-btn">
                                <i class="ea-dash-icon ea-link"></i>
                                View More
                            </button>
                        </a>
                    </div>
                    <div class="features-widget-wrapper">
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/event-calendar/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/event-calendar.svg' ); ?>" alt="<?php _e( 'Event Calendar', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Event Calendar</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/image-hotspots/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/image-hotspots.svg' ); ?>" alt="<?php _e( 'Image Hotspots', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Image Hotspots</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/learndash-course-list/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/learndash-course-list.svg' ); ?>" alt="<?php _e( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">LearnDash Course List</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/particle-effect/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/particle-effect.svg' ); ?>" alt="<?php _e( 'Particle Effect', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Particle Effect</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/instagram-feed/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/instagram-feed.svg' ); ?>" alt="<?php _e( 'Instagram Feed', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Instagram Feed</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/dynamic-gallery/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/dynamic-gallery.svg' ); ?>" alt="<?php _e( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Dynamic Gallery</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/parallax-scrolling/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/parallax-scrolling.svg' ); ?>" alt="<?php _e( 'Parallax Effect', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Parallax Effect</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/mailchimp/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/mailchimp.svg' ); ?>" alt="<?php _e( 'Mailchimp', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Mailchimp</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/advanced-google-map/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/advanced-google-map.svg' ); ?>" alt="<?php _e( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Advanced Google Map</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/advanced-tooltip/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/advanced-tooltip.svg' ); ?>" alt="<?php _e( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Advanced Tooltip</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/content-toggle/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/content-toggle.svg' ); ?>" alt="<?php _e( 'Content Toggle', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Content Toggle</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/lightbox-modal/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/lightbox-modal.svg' ); ?>" alt="<?php _e( 'Lightbox & Modal', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Lightbox & Modal</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/logo-carousel/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/logo-carousel.svg' ); ?>" alt="<?php _e( 'Logo Carousel', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Logo Carousel</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/woo-product-slider/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/woo-product-slider.svg' ); ?>" alt="<?php _e( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Woo Product Slider</span>
                            </a>
                        </div>
                        <div class="features-widget-item">
                            <a href="https://essential-addons.com/woo-cross-sells/" target="_blank">
								<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/woo-cross-sells.svg' ); ?>" alt="<?php _e( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ); ?>">
                                <span class="ea__tooltip">Woo Cross Sells</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ea__section-wrapper flex flex-end gap-4">
                <button class="previous-btn flex gap-2 items-center">
                    <i class="ea-dash-icon ea-left-arrow-long"></i>
                    Previous
                </button>
                <button class="primary-btn install-btn flex gap-2 items-center">
                    Next
                    <i class="ea-dash-icon ea-right-arrow-long"></i>
                </button>
            </div>
        </section>
    </section>

	<section id="ea__dashboard--wrapper" class="ea__onboard--wrapper">
        <section class="ea__onboard-main-wrapper">
            
            <div class="ea__onboard-content-wrapper ea__onboard-templately mb-4 min-h-538">
                <div class="ea__general-content-item templates flex justify-between items-center">
                    <div class="templates-content">
                        <h2> <span class="title-color-2">5000+</span> Ready Templates </h2>
                        <p class="mb-10">Get Access to amazing features and boost your Elementor page building
                            experience with Templately
                        </p>
                        <div class="ea__templately-details flex flex-col gap-4">
                            <div class="ea__content-details flex gap-2 items-center">
                                <span>
									<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-1.svg' ); ?>" alt="<?php _e( 'Templately Icon 1', 'essential-addons-for-elementor-lite' ); ?>">
                                </span>
                                Stunning, Ready Website Templates
                            </div>
                            <div class="ea__content-details flex gap-2 items-center">
                                <span>
									<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-2.svg' ); ?>" alt="<?php _e( 'Templately Icon 2', 'essential-addons-for-elementor-lite' ); ?>">
                                </span>
                                Add Team Members & Collaborate
                            </div>
                            <div class="ea__content-details flex gap-2 items-center">
                                <span>
									<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-3.svg' ); ?>" alt="<?php _e( 'Templately Icon 3', 'essential-addons-for-elementor-lite' ); ?>">
                                </span>
                                Design With MyCloud Storage Space
                            </div>
                            <div class="ea__content-details flex gap-2 items-center">
                                <span>
									<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-4.svg' ); ?>" alt="<?php _e( 'Templately Icon 4', 'essential-addons-for-elementor-lite' ); ?>">
                                </span>
                                Cloud With Templately WorkSpace
                            </div>
                        </div>
                    </div>
                    <div class="templates-img">
						<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-qs-img.png' ); ?>" alt="<?php _e( 'Templately Promo', 'essential-addons-for-elementor-lite' ); ?>">
                    </div>

                </div>
            </div>
            <div class="ea__section-wrapper flex flex-end gap-4">
                <button class="previous-btn flex gap-2 items-center">
                    <i class="ea-dash-icon ea-left-arrow-long"></i>
                    Skip
                </button>
                <button class="primary-btn install-btn flex gap-2 items-center">
                    Enable Templates
                </button>
            </div>
        </section>
    </section>

	<section id="ea__dashboard--wrapper" class="ea__onboard--wrapper">
        <section class="ea__onboard-main-wrapper">
            
            <div class="ea__onboard-content-wrapper ea__onboard-integrations min-h-538 mb-4">
                <div class="ea__connect-others flex gap-4 justify-between items-start mb-10">
                    <div class="flex gap-4 flex-1">
                        <div class="ea__others-icon eaicon-1">
                            <i class="ea-dash-icon ea-plug"></i>
                        </div>
                        <div class="max-w-454">
                            <h4>Integration</h4>
                            <p>Enable/Disable the elements anytime you want from Essential Addons Dashboard</p>
                        </div>
                    </div>
                    <div class="toggle-wrapper flex items-center gap-2">
                        <h5>Enable All Integrations</h5>
                        <label class="toggle-wrap">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="ea__integration-content-wrapper onBoard-scroll-wrap">
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="24" viewBox="0 0 29 24"
                                fill="none">
                                <path
                                    d="M19.154 8.56343H12.5429C11.8953 8.56343 11.3711 8.03925 11.3711 7.39163C11.3711 6.74401 11.8953 6.21983 12.5429 6.21983H19.154C19.8016 6.21983 20.3258 6.74401 20.3258 7.39163C20.3258 8.03925 19.8016 8.56343 19.154 8.56343Z"
                                    fill="#00B682"></path>
                                <path
                                    d="M16.4861 13.1292H9.87883C9.23121 13.1292 8.70703 12.6051 8.70703 11.9574C8.70703 11.3098 9.23121 10.7856 9.87883 10.7856H16.4861C17.1337 10.7856 17.6579 11.3098 17.6579 11.9574C17.6579 12.6051 17.1337 13.1292 16.4861 13.1292Z"
                                    fill="#00B682"></path>
                                <path
                                    d="M13.7946 17.7745H7.18352C6.53589 17.7745 6.01172 17.2503 6.01172 16.6027C6.01172 15.9551 6.53589 15.4309 7.18352 15.4309H13.7946C14.4422 15.4309 14.9664 15.9551 14.9664 16.6027C14.9664 17.2503 14.4422 17.7745 13.7946 17.7745Z"
                                    fill="#00B682"></path>
                                <path
                                    d="M24.0314 11.2185C23.9896 11.1311 23.9364 11.0514 23.8775 10.9773L25.6419 7.93859C26.6028 6.2863 26.6085 4.30925 25.6552 2.65126C24.6999 0.991375 22.9887 0 21.0724 0H13.0901C11.2195 0 9.4684 1.00847 8.52071 2.63227L0.724557 16.0614C-0.236431 17.7137 -0.242128 19.6908 0.711262 21.3487C1.66655 23.0086 3.37772 24 5.294 24H18.9719C21.4086 24 23.6382 22.7693 24.9354 20.7049C26.2325 18.6424 26.3768 16.0994 25.3209 13.9039L24.0314 11.2185ZM7.31093 21.5216H5.2921C4.27603 21.5216 3.36632 20.9955 2.85924 20.1143C2.35406 19.2349 2.35596 18.1866 2.86684 17.3092L10.6668 3.87814C11.1701 3.01401 12.1007 2.47844 13.0939 2.47844H21.0762C22.0923 2.47844 23.002 3.00451 23.5091 3.88573C24.0143 4.76505 24.0124 5.81341 23.5015 6.69083L15.6977 20.1238C15.2001 20.9841 14.2733 21.5197 13.2782 21.5197H7.31093V21.5216Z"
                                    fill="#00B682"></path>
                            </svg>
                            <h5>BetterDocs</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="22" height="24" viewBox="0 0 22 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1518_16346)">
                                    <path
                                        d="M14.3654 0.171432H0V15.1658H5.90379V14.8799H11.1067V9.09075H5.90379V5.4456H14.7518V15.0225C18.3683 14.3509 21.0987 11.1915 21.0987 7.3891V6.90339C21.098 3.18762 18.0818 0.171432 14.3654 0.171432Z"
                                        fill="#D047DF"></path>
                                    <path
                                        d="M14.7518 9.1627V18.5251H5.90379V14.8799H11.1067V9.09074H0V23.8279H14.7518C18.2824 23.652 21.0987 20.7271 21.0987 17.1526V16.4663C21.0987 12.7352 18.3397 9.6624 14.7518 9.16204V9.1627Z"
                                        fill="#6C3BFF"></path>
                                    <path d="M14.7504 18.5157H5.90234V23.8286H14.7504V18.5157Z" fill="#9975FF"></path>
                                    <path
                                        d="M14.7518 9.1627V18.5251H5.90379V14.8799H11.1067V9.09074H0V23.8279H14.7518C18.2824 23.652 21.0987 20.7271 21.0987 17.1526V16.4663C21.0987 12.7352 18.3397 9.6624 14.7518 9.16204V9.1627Z"
                                        fill="#6C3BFF"></path>
                                    <path
                                        d="M14.3646 0.171432H5.90234V5.50357H14.7504V0.182759C14.6231 0.17543 14.4945 0.171432 14.3646 0.171432Z"
                                        fill="#EE9AFF"></path>
                                    <path d="M14.7504 18.5157H5.90234V23.8286H14.7504V18.5157Z" fill="#9975FF"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1518_16346">
                                        <rect width="21.2571" height="24" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                            <h5>Essential Blocks</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create & organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class="toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.14072 0H0V4.44587H1.14072V0Z" fill="#988FBD"></path>
                                <path d="M0 0.0103134L0 1.15103L4.4459 1.15103L4.4459 0.0103134L0 0.0103134Z"
                                    fill="#988FBD"></path>
                                <path d="M22.8593 24L24 24L24 19.5541L22.8593 19.5541L22.8593 24Z" fill="#988FBD">
                                </path>
                                <path d="M24 23.9963L24 22.8556L19.5541 22.8556L19.5541 23.9963L24 23.9963Z"
                                    fill="#988FBD"></path>
                                <path
                                    d="M9.18497 10.3835L7.37152 9.85696L5.79206 14.4198C5.73356 14.5661 5.82131 14.7123 5.9383 14.7416L7.66401 15.2973C7.78101 15.3266 7.92726 15.2681 7.98576 15.1511L9.47747 10.9392C9.53597 10.6759 9.41897 10.4419 9.18497 10.3835Z"
                                    fill="#5B4E96"></path>
                                <path
                                    d="M5.29274 21.4981C3.39153 21.2934 1.72432 20.1527 0.788344 18.5147C0.027862 17.14 -0.147632 15.5313 0.261858 14.0103C0.700597 12.4894 1.69508 11.2024 3.09904 10.4419C3.21604 10.3834 3.33304 10.325 3.45003 10.2665C4.44451 9.79847 5.58523 9.59372 6.6967 9.73997C7.01844 9.76922 7.31094 9.82771 7.60343 9.91546L9.15365 10.3834C9.38764 10.4419 9.50464 10.7052 9.41689 10.9099L8.62716 13.1329L6.90145 12.5771C6.72596 12.5186 6.52121 12.4894 6.34571 12.4601C5.73148 12.4016 5.14649 12.4894 4.59075 12.7526C4.53226 12.7819 4.47376 12.8111 4.41526 12.8404C3.68403 13.2499 3.12829 13.9226 2.89429 14.7416C2.6603 15.5605 2.74805 16.4088 3.15754 17.1693C3.65478 18.0467 4.56151 18.661 5.58523 18.778C6.17022 18.8365 6.78445 18.7487 7.31094 18.4855C7.36944 18.4562 7.42794 18.427 7.48644 18.3977C8.18842 18.0175 8.74416 17.3155 9.0074 16.4965L12.2541 7.25379C12.6636 5.79133 13.6873 4.53361 15.062 3.77313C15.179 3.71464 15.296 3.65614 15.413 3.59764C16.4075 3.12965 17.5482 2.92491 18.6304 3.07116C20.5316 3.2759 22.1988 4.41662 23.1348 6.05457C24.7143 8.89174 23.6905 12.5186 20.8241 14.1273C20.7071 14.1858 20.5901 14.2443 20.4731 14.3028C19.4787 14.7708 18.3672 14.9756 17.2557 14.8293C16.934 14.8001 16.6415 14.7416 16.349 14.6538L15.1205 14.3028C15.0035 14.2736 14.945 14.1566 14.9743 14.0396L15.764 11.8459C15.7932 11.7289 15.9102 11.6704 16.0272 11.6997L17.0217 11.9922H17.051C17.2265 12.0507 17.4019 12.0799 17.6067 12.1091C18.2209 12.1676 18.8059 12.0799 19.3617 11.8167C19.4202 11.7874 19.4787 11.7582 19.5371 11.7289C21.0874 10.8807 21.6431 8.92099 20.7949 7.40003C20.2976 6.52256 19.3909 5.90833 18.3672 5.79133C17.7822 5.73283 17.168 5.82058 16.6415 6.08382C16.583 6.11307 16.5245 6.14232 16.466 6.17157C15.7932 6.55181 15.3253 7.13679 14.945 8.07277V8.10201L11.6983 17.3155C11.2596 18.778 10.2359 20.0357 8.8904 20.7962C8.7734 20.8547 8.65641 20.9131 8.53941 20.9716C7.51568 21.4396 6.40421 21.6444 5.29274 21.4981Z"
                                    fill="#5B4E96"></path>
                            </svg>
                            <h5>EmbedPress</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class="toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1518_16371)">
                                    <path
                                        d="M8.31641 21.9973H15.2952C15.1702 22.8054 13.5861 23.8343 12.2374 23.9796C10.6824 24.1482 8.796 23.2065 8.31931 21.9973H8.31641Z"
                                        fill="#5614D5"></path>
                                    <path
                                        d="M22.8634 18.7481C22.721 18.7365 22.5728 18.7423 22.4303 18.7423C22.0844 18.7423 21.7415 18.7423 21.3142 18.7423V18.1086C21.3142 15.2427 21.3258 12.3768 21.3026 9.51091C21.2968 8.86274 21.2386 8.20584 21.1107 7.5722C20.044 2.29672 14.7337 -1.02553 9.50467 0.288258C5.33079 1.33754 2.32536 5.14228 2.3428 9.47022C2.35443 12.0745 2.3428 14.6818 2.3428 17.2861C2.3428 17.5215 2.3428 18.2569 2.3428 18.7452C2.02598 18.7452 1.1104 18.7365 0.787768 18.7568C0.313991 18.7859 -0.00573523 19.1521 7.79779e-05 19.623C0.00589118 20.0822 0.308178 20.4078 0.776141 20.4485C0.906938 20.4601 1.03774 20.4572 1.16563 20.4572C8.27227 20.4572 15.3789 20.4601 22.4856 20.463C22.6164 20.463 22.7472 20.463 22.875 20.4514C23.3343 20.4049 23.6482 20.0677 23.6569 19.6201C23.6656 19.1666 23.3227 18.7917 22.8605 18.751L22.8634 18.7481Z"
                                        fill="#5614D5"></path>
                                    <path
                                        d="M17.2903 14.7337C17.8164 14.7337 18.2408 15.161 18.2408 15.6842C18.2408 16.2074 17.8135 16.6347 17.2903 16.6347C16.7671 16.6347 16.3398 16.2074 16.3398 15.6842C16.3398 15.161 16.7671 14.7337 17.2903 14.7337Z"
                                        fill="#836EFF"></path>
                                    <path
                                        d="M18.1573 8.57175L18.1719 8.56303C18.1602 8.48164 18.1486 8.40026 18.1312 8.32178C17.5528 5.4704 15.0938 3.36602 12.2046 3.19453C12.2017 3.19453 12.1959 3.19453 12.193 3.19453C12.068 3.18581 11.9459 3.18291 11.8209 3.18291H11.8035C11.6785 3.18291 11.5535 3.18872 11.4315 3.19453C11.4285 3.19453 11.4227 3.19453 11.4198 3.19453C8.53066 3.36602 6.07168 5.4704 5.49326 8.32178C5.47873 8.40026 5.4671 8.48164 5.45257 8.56303L5.4671 8.57175C5.41188 8.90892 5.38281 9.2548 5.38281 9.60359C5.38281 9.87972 5.38281 15.7365 5.38281 16.5329L7.3564 18.6635C7.3564 17.1724 7.3564 10.4436 7.35349 9.59487C7.34477 7.59222 8.76319 5.78141 10.7252 5.28729C10.8879 5.24659 11.0536 5.21462 11.2164 5.19428C11.2454 5.19137 11.2716 5.18556 11.3007 5.18265C11.3646 5.17393 11.4256 5.17102 11.4867 5.16521C11.5768 5.1594 11.6669 5.15358 11.757 5.15358C11.7744 5.15358 11.789 5.15358 11.8064 5.15358C11.8238 5.15358 11.8384 5.15358 11.8558 5.15358C11.9459 5.15358 12.036 5.1594 12.1261 5.16521C12.1901 5.17102 12.2511 5.17684 12.3122 5.18265C12.3412 5.18556 12.3674 5.19137 12.3964 5.19428C12.5621 5.21753 12.7249 5.24659 12.8877 5.28729C14.8496 5.78141 16.268 7.59222 16.2593 9.59487C16.2593 9.91169 16.2593 12.6846 16.2593 13.9809L18.2329 12.6642C18.2329 11.9143 18.2329 9.68789 18.2329 9.60359C18.2329 9.2548 18.2038 8.90892 18.1486 8.57175H18.1573Z"
                                        fill="#836EFF"></path>
                                    <path
                                        d="M21.3009 9.51349C21.3009 9.88263 21.3038 10.2489 21.3067 10.618L23.7105 9.02227L22.1613 6.69699L21.0742 7.41492C21.0858 7.46724 21.1004 7.51956 21.112 7.57478C21.2399 8.20842 21.298 8.86822 21.3038 9.51349H21.3009Z"
                                        fill="#00F9AC"></path>
                                    <path opacity="0.9"
                                        d="M5.34403 12.3765L3.28906 14.2687L8.08205 19.4424L21.31 10.6209C21.31 10.2518 21.31 9.88553 21.3042 9.51639C21.2984 8.86822 21.2402 8.21133 21.1124 7.57769C21.1007 7.52246 21.0862 7.47014 21.0746 7.41782L8.51804 15.7917L5.34403 12.3765Z"
                                        fill="#21D8A3"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1518_16371">
                                        <rect width="23.9999" height="24" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                            <h5>NotificationX</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22.5984 22.1334C23.2651 22.1334 23.7984 21.6001 23.7984 20.9334C23.7984 20.2667 23.2651 19.7334 22.5984 19.7334C21.9318 19.7334 21.3984 20.2667 21.3984 20.9334C21.3984 21.6001 21.9318 22.1334 22.5984 22.1334Z"
                                    fill="#597DFC" />
                                <path
                                    d="M18.8008 19.8C18.2674 19.8 17.8008 20.2667 17.8008 20.8C17.8008 21.3334 18.2674 21.8 18.8008 21.8C19.3341 21.8 19.8008 21.3334 19.8008 20.8C19.7341 20.2667 19.3341 19.8 18.8008 19.8Z"
                                    fill="#597DFC" />
                                <path
                                    d="M25.201 22.5334C24.9344 22.5334 24.7344 22.7335 24.7344 23.0001C24.7344 23.2668 24.9344 23.4668 25.201 23.4668C25.4677 23.4668 25.6677 23.2668 25.6677 23.0001C25.6677 22.7335 25.4677 22.5334 25.201 22.5334Z"
                                    fill="#597DFC" />
                                <path
                                    d="M20.7331 24.0001C21.0664 24.0001 21.3997 23.7334 21.3997 23.3334C21.3997 23.0001 21.1331 22.6667 20.7331 22.6667C20.3997 22.6667 20.0664 22.9334 20.0664 23.3334C20.1331 23.7334 20.3997 24.0001 20.7331 24.0001Z"
                                    fill="#597DFC" />
                                <path
                                    d="M9.33177 6.40002C9.4651 6.40002 9.59844 6.46668 9.6651 6.46668C10.3984 6.46668 10.9318 5.86668 10.9318 5.20002C10.9318 4.46668 10.3318 3.93335 9.6651 3.93335C8.93177 3.93335 8.39844 4.53335 8.39844 5.20002C8.39844 5.66668 8.6651 6.13335 9.13177 6.33335C9.19844 6.33335 9.2651 6.40002 9.33177 6.40002Z"
                                    fill="#597DFC" />
                                <path
                                    d="M18.0667 14.8667C19 13.4 19.5333 11.6 19.5333 9.73333C19.5333 5.06667 16.2 1.13333 11.7333 0.199999C11.0667 0.0666662 10.4 0 9.66667 0C4.33333 0 0 4.33333 0 9.73333C0 14.2667 3.06667 18.1333 7.26667 19.2C6.6 18.8667 1.93333 15.6667 2 9.46667C2 5.46666 5.4 2.2 9.4 2.06667C13.7333 1.93333 17.3333 5.4 17.3333 9.73333C17.3333 12.9333 15.4 15.6667 12.6 16.8C12 17 10 17.4667 8.26667 15.1333C7.13333 13.4667 7.26667 11.6667 7.33333 11.1333C7.4 10.1333 8.2 9.4 9.13334 9.4C9.2 9.4 9.26667 9.4 9.33333 9.4C10.4667 9.46667 11.2 10.2667 11.1333 11.3333C11.0667 12.2 10.8667 13.1333 10.4667 13.9333L10.4 14C10.3333 14.1333 10.2667 14.2667 10.2 14.4C10.1333 14.5333 10.1333 14.6667 10.1333 14.8C10.2 15.1333 10.5333 15.3333 10.8667 15.2667C11.0667 15.2667 11.2 15.1333 11.2667 15C12.1333 13.7333 12.7333 12.4667 12.7333 10.9333C12.7333 10 12.4 9.13333 11.8 8.53333C11.1333 7.86667 10.2667 7.53333 9.26667 7.53333C9.2 7.53333 9.13334 7.53333 9.06667 7.53333C8.53333 7.53333 8.06667 7.66667 7.66667 7.86667C7.66667 7.86667 5.53333 8.53333 5.33333 11C5.13333 12.4 5.26667 14.6667 7.33333 16.9333C7.6 17.2 7.8 17.4667 8.13334 17.7333C8.46667 18 8.8 18.2 9.2 18.4667C9.4 18.6 9.6 18.6667 9.73333 18.7333C10.0667 18.8667 10.4667 19 10.8667 19.0667C11.7333 19.2 12.6 19.0667 13.4 18.7333C14.0667 18.4667 14.6667 18.1333 15.2 17.7333L16.8 19.2C17.2 18.8 17.8 18.6 18.4 18.6C19.3333 18.6 20.2 19.2 20.5333 20C20.7333 19.4667 21.2 19.0667 21.7333 18.8667L18.0667 14.8667Z"
                                    fill="#597DFC" />
                            </svg>
                            <h5>easy.jobs</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="29" height="24" viewBox="0 0 29 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.1223 23.7899C5.62248 23.7899 0.222656 18.5389 0.222656 11.9999C0.222656 5.46095 5.52248 0.209961 12.1223 0.209961C14.2222 0.209961 16.2221 0.705336 18.0221 1.79516C18.522 2.09239 18.722 2.78592 18.422 3.28129C18.1221 3.77667 17.4221 3.97482 16.9221 3.6776C15.5221 2.88499 13.8222 2.38962 12.2223 2.38962C6.92243 2.38962 2.62258 6.64986 2.62258 11.9008C2.62258 17.1518 6.92243 21.4121 12.2223 21.4121C14.9222 21.4121 17.4221 20.3223 19.222 18.4398C19.622 17.9444 20.422 17.9444 20.822 18.3407C21.322 18.737 21.322 19.5296 20.922 19.926C18.622 22.5019 15.4221 23.7899 12.1223 23.7899Z"
                                    fill="url(#paint0_radial_2448_19440)" />
                                <path
                                    d="M10.722 9.82026L8.32207 7.64061C7.82209 7.24431 7.12211 7.24431 6.72213 7.73969C6.32214 8.23506 6.32214 8.92859 6.82212 9.32489L9.42203 11.7027C9.52203 10.811 10.022 10.2166 10.722 9.82026Z"
                                    fill="#24E2AC" />
                                <path
                                    d="M25.1223 2.78595C24.7223 2.29057 24.0223 2.1915 23.5223 2.5878L14.2227 9.91936C14.8226 10.4147 15.3226 11.0092 15.4226 11.8018L24.9223 4.37115C25.5223 3.97485 25.5223 3.28132 25.1223 2.78595Z"
                                    fill="#24E2AC" />
                                <path
                                    d="M13.4241 10.6129C13.1241 10.4148 12.8241 10.4148 12.4241 10.4148C12.1242 10.4148 11.8242 10.5139 11.5242 10.6129C10.8242 10.9102 10.3242 11.6037 10.3242 12.4954V12.5945C10.4242 13.6843 11.3242 14.4769 12.4241 14.4769C13.5241 14.4769 14.3241 13.6843 14.4241 12.6935C14.4241 12.5945 14.4241 12.4954 14.4241 12.4954C14.4241 11.6037 14.0241 10.9102 13.4241 10.6129Z"
                                    fill="#3DEAB5" />
                                <path
                                    d="M27.821 9.42407H24.9211C24.7211 9.42407 24.6211 9.62222 24.6211 9.7213V12.1982C24.6211 12.3963 24.8211 12.4954 24.9211 12.4954H27.821C28.021 12.4954 28.121 12.2973 28.121 12.1982V9.7213C28.221 9.62222 28.021 9.42407 27.821 9.42407Z"
                                    fill="#6C62FF" />
                                <path
                                    d="M23.2233 9.42407H20.3234C20.1234 9.42407 20.0234 9.62222 20.0234 9.7213V12.1982C20.0234 12.3963 20.2234 12.4954 20.3234 12.4954H23.2233C23.4233 12.4954 23.5233 12.2973 23.5233 12.1982V9.7213C23.5233 9.62222 23.4233 9.42407 23.2233 9.42407Z"
                                    fill="#CCCCFF" />
                                <path
                                    d="M23.2233 13.5852H20.3234C20.1234 13.5852 20.0234 13.7834 20.0234 13.8824V16.3593C20.0234 16.5575 20.2234 16.6565 20.3234 16.6565H23.2233C23.4233 16.6565 23.5233 16.4584 23.5233 16.3593V13.8824C23.5233 13.7834 23.4233 13.5852 23.2233 13.5852Z"
                                    fill="#CCCCFF" />
                                <path
                                    d="M27.821 13.5852H24.9211C24.7211 13.5852 24.6211 13.7834 24.6211 13.8824V16.3593C24.6211 16.5575 24.8211 16.6565 24.9211 16.6565H27.821C28.021 16.6565 28.121 16.4584 28.121 16.3593V13.8824C28.221 13.7834 28.021 13.5852 27.821 13.5852Z"
                                    fill="#CCCCFF" />
                                <defs>
                                    <radialGradient id="paint0_radial_2448_19440" cx="0" cy="0" r="1"
                                        gradientUnits="userSpaceOnUse"
                                        gradientTransform="translate(20.3019 18.6108) scale(9.8318 9.74122)">
                                        <stop stop-color="#F3F3FF" />
                                        <stop offset="0.09" stop-color="#E3E2FF" />
                                        <stop offset="0.4" stop-color="#B0ACFF" />
                                        <stop offset="0.66" stop-color="#8B84FF" />
                                        <stop offset="0.87" stop-color="#746CFF" />
                                        <stop offset="1" stop-color="#6C63FF" />
                                    </radialGradient>
                                </defs>
                            </svg>
                            <h5>SchedulePress</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="20" height="24" viewBox="0 0 20 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.0326 13.2924C15.0155 13.2924 14.9812 13.2924 14.9641 13.2924C14.4507 13.2753 13.9543 12.9843 13.6976 12.4709C13.3895 11.8547 13.5607 11.1188 14.057 10.708C12.9445 11.2728 8.118 13.6518 7.03973 14.1653C6.97127 14.1995 6.91993 14.2166 6.8857 14.2338C6.2182 14.5418 5.87589 15.3291 6.16685 16.0651C6.23531 16.2191 6.32089 16.3561 6.4407 16.493C6.83435 16.9038 7.41627 17.0578 7.92973 16.8866C7.96396 16.8695 7.99819 16.8524 8.04954 16.8524L8.25492 16.7668L8.68281 16.5957C8.76838 16.5614 15.4605 13.2753 15.4605 13.2753C15.5289 13.2411 15.5803 13.224 15.6487 13.1897C15.4605 13.2411 15.238 13.2924 15.0326 13.2924Z"
                                    fill="#FF1F1F" />
                                <path
                                    d="M19.1939 6.9082C19.1426 5.98397 18.9714 5.12821 18.6805 4.3409C18.5264 3.93014 18.3211 3.53648 18.0643 3.17706C17.8247 2.83476 17.568 2.52668 17.277 2.2186C16.4384 1.34572 15.4286 0.712456 14.2647 0.335919C12.8784 -0.109079 11.4578 -0.109078 10.0373 0.318805C9.98591 0.33592 9.93456 0.353036 9.8661 0.370151C9.18149 0.592651 8.5311 0.952071 7.89784 1.41418C7.11053 2.01322 6.44304 2.7663 5.94669 3.65629C5.6215 4.23821 5.41612 4.8886 5.34766 5.60744C5.45035 5.21379 5.72419 4.85436 6.11785 4.64898C6.8538 4.28956 7.7438 4.58052 8.10322 5.31648C8.18879 5.48763 8.24014 5.65878 8.25726 5.82994C8.25726 5.77859 8.27437 5.72725 8.27437 5.6759C8.39418 4.85436 8.89053 4.16975 9.78052 3.58783C10.1228 3.36533 10.4822 3.19418 10.8417 3.07437C11.783 2.76629 12.7757 2.81764 13.7684 3.22841C15.343 3.87879 16.4042 5.57321 16.3015 7.26762C16.2501 8.03781 16.079 8.63685 15.7538 9.15031C15.4286 9.68088 15.0178 10.1088 14.5386 10.4168C15.2232 10.1943 15.9763 10.5024 16.3015 11.1699C16.6609 11.8888 16.3699 12.7616 15.6511 13.1382C17.1059 12.4022 18.2184 11.1186 18.7318 9.56107C18.8174 9.33858 18.8859 9.13319 18.9372 8.91069C19.1597 8.2432 19.2282 7.5757 19.1939 6.9082Z"
                                    fill="#AA02D3" />
                                <path
                                    d="M16.3367 11.17C16.0115 10.5025 15.2585 10.2115 14.5739 10.4169C14.5054 10.434 14.4369 10.4682 14.3685 10.5025C14.2658 10.5538 14.1631 10.6223 14.0604 10.7079C13.564 11.1357 13.3929 11.8546 13.701 12.4707C13.9406 12.9671 14.4369 13.2752 14.9675 13.2923C14.9846 13.2923 15.0188 13.2923 15.036 13.2923C15.2413 13.2923 15.4638 13.2409 15.6692 13.1382C15.6692 13.1382 15.6863 13.1382 15.6863 13.1211C16.4052 12.7617 16.6961 11.8888 16.3367 11.17Z"
                                    fill="#EF726C" />
                                <path
                                    d="M8.25852 5.84688C8.2414 5.62439 8.15582 5.40188 8.0189 5.1965C7.64236 4.61458 6.88929 4.37497 6.25603 4.61458C5.7768 4.80285 5.46872 5.17938 5.34892 5.62438C5.29757 5.82977 5.28045 6.05227 5.31468 6.25765C5.3318 6.39457 5.38314 6.5315 5.4516 6.66842C5.60564 6.95938 5.82814 7.18188 6.10199 7.33591C6.51276 7.5413 7.0091 7.55841 7.43698 7.35303C8.0189 7.04496 8.32698 6.44592 8.25852 5.84688Z"
                                    fill="#FF7878" />
                                <path
                                    d="M13.0342 7.83225C12.6747 7.11341 11.819 6.82244 11.1001 7.18186C11.0488 7.2161 10.9803 7.25033 10.929 7.28456L10.5011 7.45571C10.4155 7.48994 3.72343 10.7761 3.72343 10.7761C3.65496 10.8103 3.60362 10.8274 3.53516 10.8617C3.74054 10.759 3.94592 10.7076 4.16842 10.7076C4.18554 10.7076 4.21977 10.7076 4.23689 10.7076C4.75034 10.7247 5.24669 11.0157 5.50342 11.5292C5.81149 12.1453 5.64034 12.8813 5.144 13.292C6.25649 12.7272 11.083 10.3482 12.1442 9.83474C12.2297 9.81762 12.2982 9.78339 12.3667 9.74916C13.0855 9.40686 13.3765 8.53398 13.0342 7.83225Z"
                                    fill="#2D2F54" />
                                <path
                                    d="M0.00833367 17.0918C0.0596796 18.016 0.230833 18.8718 0.521793 19.6591C0.675831 20.0699 0.881216 20.4635 1.13795 20.8229C1.37756 21.1652 1.63429 21.4733 1.92525 21.7814C2.7639 22.6543 3.7737 23.2875 4.93754 23.6641C6.32388 24.1091 7.74446 24.1091 9.16503 23.6812C9.21637 23.6641 9.26772 23.647 9.33618 23.6298C10.0208 23.4073 10.6712 23.0479 11.3044 22.5858C12.0917 21.9868 12.7592 21.2337 13.2556 20.3437C13.5808 19.7618 13.7862 19.1114 13.8546 18.3926C13.7519 18.7862 13.4781 19.1456 13.0844 19.351C12.3485 19.7104 11.4585 19.4195 11.0991 18.6835C11.0135 18.5124 10.9621 18.3412 10.945 18.1701C10.945 18.2214 10.9279 18.2727 10.9279 18.3241C10.8081 19.1456 10.3118 19.8302 9.42176 20.4122C9.07945 20.6347 8.72003 20.8058 8.36061 20.9256C7.41926 21.2337 6.42658 21.1824 5.43389 20.7716C3.85928 20.1212 2.79813 18.4268 2.90082 16.7324C2.95217 15.9622 3.12332 15.3631 3.44851 14.8497C3.7737 14.3191 4.18447 13.8912 4.6637 13.5832C3.97909 13.8056 3.22601 13.4976 2.90082 12.8301C2.5414 12.1112 2.83236 11.2384 3.55121 10.8618C2.0964 11.5978 0.983909 12.8814 0.470449 14.4389C0.384873 14.6614 0.316409 14.8668 0.265063 15.0893C0.0425643 15.7568 -0.025897 16.4243 0.00833367 17.0918Z"
                                    fill="#0847F9" />
                                <path
                                    d="M5.50463 11.5291C5.26502 11.0327 4.76868 10.7246 4.2381 10.7075C4.22099 10.7075 4.18675 10.7075 4.16964 10.7075C3.96425 10.7075 3.74176 10.7589 3.53637 10.8616C3.53637 10.8616 3.51926 10.8616 3.51926 10.8787C2.80041 11.2381 2.50946 12.111 2.86888 12.8469C3.19407 13.5144 3.94714 13.8054 4.63175 13.6C4.70021 13.5829 4.76868 13.5487 4.83714 13.5144C4.93983 13.4631 5.04252 13.3946 5.14521 13.3091C5.64156 12.8812 5.81271 12.1452 5.50463 11.5291Z"
                                    fill="#8761FF" />
                                <path
                                    d="M13.8908 17.7592C13.8737 17.6223 13.8223 17.4853 13.7539 17.3484C13.5998 17.0575 13.3773 16.835 13.1035 16.6809C12.6927 16.4755 12.1964 16.4584 11.7685 16.6638C11.2037 16.9548 10.8956 17.5538 10.9469 18.1528C10.9641 18.3753 11.0496 18.5978 11.1866 18.8032C11.5631 19.3851 12.3162 19.6248 12.9494 19.3852C13.4287 19.1969 13.7367 18.8203 13.8565 18.3753C13.9079 18.1871 13.925 17.9646 13.8908 17.7592Z"
                                    fill="#25BF88" />
                                <path opacity="0.12"
                                    d="M13.8908 17.7592C13.8737 17.6223 13.8223 17.4853 13.7539 17.3484C13.5998 17.0575 13.3773 16.835 13.1035 16.6809C12.6927 16.4755 12.1964 16.4584 11.7685 16.6638C11.2037 16.9548 10.8956 17.5538 10.9469 18.1528C10.9641 18.3753 11.0496 18.5978 11.1866 18.8032C11.5631 19.3851 12.3162 19.6248 12.9494 19.3852C13.4287 19.1969 13.7367 18.8203 13.8565 18.3753C13.9079 18.1871 13.925 17.9646 13.8908 17.7592Z"
                                    fill="#0847F9" />
                            </svg>
                            <h5>BetterLinks</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ea__integration-item">
                        <div class="ea__integration-header flex gap-2 items-center">
                            <svg width="21" height="24" viewBox="0 0 21 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.98438 23.8796C4.30765 23.9525 4.64397 23.991 4.98928 23.991H14.053C17.6239 23.991 20.5188 21.0959 20.5188 17.5246C20.5188 14.6295 18.6165 12.1788 15.9938 11.3545L3.98438 23.8796Z"
                                    fill="#6E58F7" />
                                <path
                                    d="M0 4.28153C0 1.91691 1.91671 0 4.28109 0H14.3195C16.6839 0 18.6006 1.91691 18.6006 4.28153V9.77449C18.6006 10.9178 18.1434 12.0135 17.3309 12.8177L7.29246 22.7531C4.58915 25.4287 0 23.5136 0 19.7099V4.28153Z"
                                    fill="url(#paint0_linear_2448_30511)" />
                                <path
                                    d="M8.26562 6.42237C8.26562 5.07698 9.35617 3.98633 10.7014 3.98633H18.5993V8.85842H10.7014C9.35617 8.85842 8.26562 7.76776 8.26562 6.42237Z"
                                    fill="white" />
                                <path
                                    d="M10.7611 7.60344C11.3734 7.60344 11.8698 7.07464 11.8698 6.42233C11.8698 5.77001 11.3734 5.24121 10.7611 5.24121C10.1487 5.24121 9.65234 5.77001 9.65234 6.42233C9.65234 7.07464 10.1487 7.60344 10.7611 7.60344Z"
                                    fill="#6E58F7" />
                                <defs>
                                    <linearGradient id="paint0_linear_2448_30511" x1="2.62042" y1="21.6552" x2="18.6027"
                                        y2="0.001578" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#A293FF" />
                                        <stop offset="1" stop-color="#6E58F7" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <h5>Better Payment</h5>
                        </div>
                        <div class="ea__integration-footer">
                            <p>BetterDocs will help you to create &amp; organize your
                                documentation page in a beautiful way that will make your visitors find any help
                                article
                                easily.
                            </p>
                            <div class="integration-settings flex justify-between items-center">
                                <h5 class="toggle-label">Enable / Disable Integration</h5>
                                <label class=" toggle-wrap">
                                    <input type="checkbox" checked="checked">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ea__section-overlay"></div>
            </div>
            <div class="ea__section-wrapper flex flex-end gap-4">
                <button class="previous-btn flex gap-2 items-center">
                    <i class="ea-dash-icon ea-left-arrow-long"></i>
                    Previous
                </button>
                <button class="primary-btn install-btn flex gap-2 items-center">
                    Finish
                </button>
            </div>
        </section>
    </section>

	<section class="ea__modal-wrapper eael-d-none">
            <div class="ea__modal-content-wrapper ea__onboard-modal">
                <div class="">
                    <h5>What we collect? </h5>
                    <p>We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress &
                        PHP version, plugins & themes and email address to send you the discount coupon. This data lets
                        us make sure this plugin always stays compatible with the most popular plugins and themes. No
                        spam, we promise.</p>
                    <div class="congrats--wrapper">
                        <h6>You are done!</h6>
                        <h4 class="congrats--title">Congratulations!</h4>
						<img class="ea__modal-map-img" src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/success-2.png' ); ?>" alt="<?php _e( 'Success Image', 'essential-addons-for-elementor-lite' ); ?>">
                    </div>
                </div>
                <div class="ea__modal-close-btn">
                    <i class="ea-dash-icon ea-close"></i>
                </div>
            </div>
        </section>
	
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



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
		add_action( 'wp_ajax_enable_wpins_process', [ $this, 'enable_wpins_process' ] );
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
			wp_enqueue_style( 'essential_addons_elementor-setup-wizard-css', EAEL_PLUGIN_URL . 'assets/admin/css/quick-setup.css', false, EAEL_PLUGIN_VERSION );
            wp_enqueue_style( 'essential_addons_elementor-setup-wizard-fonts', EAEL_PLUGIN_URL . 'includes/templates/admin/icons/style.css', false, EAEL_PLUGIN_VERSION );
			wp_enqueue_style( 'sweetalert2-css', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, EAEL_PLUGIN_VERSION );
			wp_enqueue_script( 'sweetalert2-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'sweetalert2-core-js' ), EAEL_PLUGIN_VERSION, true );
			wp_enqueue_script( 'sweetalert2-core-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/core.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
			// wp_enqueue_script( 'essential_addons_elementor-setup-wizard-js', EAEL_PLUGIN_URL . 'assets/admin/js/admin.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
			// wp_enqueue_script( 'essential_addons_elementor-setup-wizard-react-css', EAEL_PLUGIN_URL . 'includes/templates/admin/quick-setup/dist/quick-setup.min.css', array(), EAEL_PLUGIN_VERSION, true );
			wp_enqueue_script( 'essential_addons_elementor-setup-wizard-react-js', EAEL_PLUGIN_URL . 'includes/templates/admin/quick-setup/dist/quick-setup.min.js', array(), EAEL_PLUGIN_VERSION, true );
			
			wp_localize_script( 'essential_addons_elementor-setup-wizard-react-js', 'localize', array(
				'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'         => wp_create_nonce( 'essential-addons-elementor' ),
				'success_image' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/success.gif',
				'eael_quick_setup_data' => $this->eael_quick_setup_data(),
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
		<section id="eael-onboard--wrapper" class="eael-onboard--wrapper">
		</section>
		<?php
	}

	public function eael_quick_setup_data() {
		$eael_quick_setup_data = [
			'is_quick_setup' => 1,
			'menu_items' => $this->data_menu_items(),
			'getting_started_content' => $this->data_getting_started_content(),
			'configuration_content' => $this->data_configuration_content(),
			'elements_content' => $this->data_elements_content(),
			'go_pro_content' => $this->data_go_pro_content(),
			'templately_content' => $this->data_templately_content(),
			'integrations_content' => $this->data_integrations_content(),
			'modal_content' => $this->data_modal_content(),
		];

		return $eael_quick_setup_data;
	}

	public function data_menu_items(){
		$items = [
			__( 'Getting Started', 'essential-addons-for-elementor-lite' ),
			__( 'Configuration', 'essential-addons-for-elementor-lite' ),
			__( 'Elements', 'essential-addons-for-elementor-lite' ),
			__( 'Go PRO', 'essential-addons-for-elementor-lite' ),
			__( 'Templately', 'essential-addons-for-elementor-lite' ),
			__( 'Integrations', 'essential-addons-for-elementor-lite' ),
		];

		$menu_items = [
			'templately_status' => $this->templately_status,
			'wizard_column' => !$this->templately_status ? 'five' : 'four',
			'items' => $items,
			'templately_local_plugin_data' => $this->get_local_plugin_data( 'templately/templately.php' ),
			'ea_pro_local_plugin_data' => $this->get_local_plugin_data( 'essential-addons-elementor/essential_adons_elementor.php' ),
		];

		return $menu_items;
	}
	
	public function data_getting_started_content(){
		$getting_started_content = [
			'youtube_promo_src' => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/youtube-promo.png' ),
			'is_tracking_allowed' => $this->get_is_tracking_allowed(),
		];

		return $getting_started_content;
	}
	
	public function data_configuration_content(){
		$configuration_content = [
			'ea_logo_src' => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ea-new.png' ),
		];

		return $configuration_content;
	}

	public function data_elements_content(){
		$elements_content = [
			'elements_list' => $this->get_element_list(),
		];

		return $elements_content;
	}
	
	public function data_go_pro_content(){
		$feature_items = [
			[
				'title' => 'Smart Post List',
				'link' => 'https://essential-addons.com/post-list/',
				'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/smart-post-list.svg',
			],
            [
                'title' => 'Dynamic Gallery',
                'link' => 'https://essential-addons.com/dynamic-gallery/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/dynamic-gallery.svg',
            ],
            [
                'title' => 'Custom JS',
                'link' => 'https://essential-addons.com/custom-js/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/custom-js.svg',
            ],
            [
                'title' => 'Protected Content',
                'link' => 'https://essential-addons.com/protected-content/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/protected-content.svg',
            ],
            [
                'title' => 'Interactive Animations',
                'link' => 'https://essential-addons.com/interactive-animations/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/lightbox-modal.svg',
            ],
            [
                'title' => 'Advanced Google Map',
                'link' => 'https://essential-addons.com/advanced-google-map/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/advanced-google-map.svg',
            ],
            [
                'title' => 'Mailchimp',
                'link' => 'https://essential-addons.com/mailchimp/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/mailchimp.svg',
            ],
            [
                'title' => 'Instagram Feed',
                'link' => 'https://essential-addons.com/instagram-feed/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/instagram-feed.svg',
            ],
            [
                'title' => 'Woo Product Slider',
                'link' => 'https://essential-addons.com/woo-product-slider/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/woo-product-slider.svg',
            ],
            [
                'title' => 'Parallax',
                'link' => 'https://essential-addons.com/parallax-scrolling/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/parallax-scrolling.svg',
            ],
            [
                'title' => 'Post Carousel',
                'link' => 'https://essential-addons.com/post-carousel/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/post-carousel.svg',
            ],
            [
                'title' => 'LearnDash Course List',
                'link' => 'https://essential-addons.com/learndash-course-list/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/learndash-course-list.svg',
            ],
            [
                'title' => 'Particle Effect',
                'link' => 'https://essential-addons.com/particle-effect/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/particle-effect.svg',
            ],
			[
				'title' => 'Logo Carousel',
				'link' => 'https://essential-addons.com/logo-carousel/',
				'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/logo-carousel.svg',
			],
            [
                'title' => 'Image Hotspots',
                'link' => 'https://essential-addons.com/image-hotspots/',
                'img_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/image-hotspots.svg',
            ]
		];

		$go_pro_content = [
			'feature_items' => $feature_items,
		];

		return $go_pro_content;
	}

	public function data_templately_content(){
		$templately_content = [
			'templately_icon_1_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-1.svg',
			'templately_icon_2_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-2.svg',
			'templately_icon_3_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-3.svg',
			'templately_icon_4_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-icon-4.svg',
			'templately_promo_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-qs-img.png'
		];

		return $templately_content;
	}
	
	public function data_integrations_content(){
		$integrations_content = [
			'plugin_list' => $this->get_plugin_list(),
		];

		return $integrations_content;
	}
	
	public function data_modal_content(){
		$modal_content = [
			'success_2_src' => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/success-2.png',
		];

		return $modal_content;
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
				'desc'     => __( 'Create and organize your knowledge base, FAQ & documentation page efficiently, making it easy for visitors to find any helpful article quickly and effortlessly.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'betterdocs/betterdocs.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'betterdocs/betterdocs.php' ),
			],
			[
				'slug'     => 'betterlinks',
				'basename' => 'betterlinks/betterlinks.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/btl.svg',
				'title'    => __( 'BetterLinks', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Link Shortening tool to create, shorten & manage any URL. It helps to cross promote brands & products and gather analytics reports while running marketing campaigns.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'betterlinks/betterlinks.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'betterlinks/betterlinks.php' ),
			],
			[
				'slug'     => 'better-payment',
				'basename' => 'better-payment/better-payment.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bp.svg',
				'title'    => __( 'Better Payment', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Streamline transactions in Elementor by integrating PayPal & Stripe. Experience advanced analytics, validation, and Elementor forms for secure & efficient payments.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'better-payment/better-payment.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'better-payment/better-payment.php' ),
			],
			[
				'slug'     => 'notificationx',
				'basename' => 'notificationx/notificationx.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/nx-logo.svg',
				'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Best FOMO & social proof plugin to boost sales conversion by creating stunning sales popups, growth & discount alerts, flashing tabs, notification bars & more.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'notificationx/notificationx.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'notificationx/notificationx.php' ),
			],
			[
				'slug'     => 'wp-scheduled-posts',
				'basename' => 'wp-scheduled-posts/wp-scheduled-posts.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/wscp.svg',
				'title'    => __( 'SchedulePress', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Advanced content marketing tool for WordPress to schedule posts & pages with Schedule Calendar, Auto & Manual Scheduler, etc. It also allows auto-social sharing.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'wp-scheduled-posts/wp-scheduled-posts.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'wp-scheduled-posts/wp-scheduled-posts.php' ),
			],
			[
				'slug'     => 'easyjobs',
				'basename' => 'easyjobs/easyjobs.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/easy-jobs-logo.svg',
				'title'    => __( 'easy.jobs', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Job recruitment tool to attract, manage, and hire the right talent faster. This talent recruitment solution lets you manage jobs and career pages in Elementor.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'easyjobs/easyjobs.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'easyjobs/easyjobs.php' ),
			],
			[
				'slug'     => 'embedpress',
				'basename' => 'embedpress/embedpress.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ep-logo.png',
				'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Embed videos, images, gifs, charts, docs, maps, audio, live streams, pdf & more from 150+ sources into your WordPress site and get seamless customization options.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'embedpress/embedpress.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'embedpress/embedpress.php' ),
			],
			[
				'slug'     => 'essential-blocks',
				'basename' => 'essential-blocks/essential-blocks.php',
				'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/eb-new.svg',
				'title'    => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
				'desc'     => __( 'Enhance Gutenberg experience with 50+ unique blocks (more coming soon). Boost your block editor with easy-to-use blocks for a simpler WordPress page or post design.', 'essential-addons-for-elementor-lite' ),
				'is_active' => is_plugin_active( 'essential-blocks/essential-blocks.php' ),
				'local_plugin_data' => $this->get_local_plugin_data( 'essential-blocks/essential-blocks.php' ),
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

	public function enable_wpins_process() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( !isset( $_POST[ 'fields' ] ) ) {
			return;
		}

		wp_parse_str( $_POST[ 'fields' ], $fields );
		
		$this->wpins_process();

		wp_send_json_success();
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

	public function get_is_tracking_allowed( $plugin = 'essential_adons_elementor' ){
		/**
		 * Get All Tracked Plugin List using this Tracker.
		 */
		$allow_tracking = get_option( 'wpins_allow_tracking' );
		/**
		 * Check user is opted out for tracking or not.
		 */
		return intval( isset( $allow_tracking[$plugin] ) );
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



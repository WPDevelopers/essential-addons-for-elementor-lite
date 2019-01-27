<?php
/**
 * Admin Settings Page
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Eael_Admin_Settings {

	private $is_pro = false;

	/**
	 * Contains Default Component keys
	 * @var array
	 * @since 2.3.0
	 */
	public $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'interactive-promo', 'post-grid', 'post-block', 'post-timeline', 'product-grid', 'team-members', 'testimonials', 'weforms', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table','ninja-form', 'gravity-form', 'caldera-form', 'wpforms', 'twitter-feed', 'facebook-feed', 'data-table', 'filter-gallery', 'image-accordion', 'content-ticker', 'tooltip', 'adv-accordion', 'adv-tabs', 'progress-bar', 'section-particles'];

	/**
	 * Will Contain All Components Default Values
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_default_settings;

	/**
	 * Will Contain User End Settings Value
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_settings;

	/**
	 * Will Contains Settings Values Fetched From DB
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_get_settings;

	/**
	 * Initializing all default hooks and functions
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'create_eael_admin_menu' ), 600 );
		add_action( 'init', array( $this, 'enqueue_eael_admin_scripts' ) );
		add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'eael_save_settings_with_ajax' ) );
		add_action( 'wp_ajax_add_action_with_ajax', array( $this, 'add_action_with_ajax' ) );

	}

	public function add_action_with_ajax(){

		global $wp_version;
		$post_types = [];
		$remoteargs = array(
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
			'sslverify'   => false,
		);
		$otherurl = $_POST['url'];

		$otherurl = $otherurl . 'wp-json/wp/v2/types';

		$response = wp_remote_get( $otherurl, $remoteargs );    
		$response = json_decode( $response['body'] );
		// echo '<pre>', print_r( $response, 1 ), '</pre>';
		foreach( $response as $type ){
			$post_types[ $type->rest_base ] = $type->name;
		}
		$eael_exclude_cpts = array( 'elementor_library', 'media', 'product' );
		foreach ( $eael_exclude_cpts as $exclude_cpt ) {
			unset($post_types[$exclude_cpt]);
		}
		// echo '<pre>', print_r( $post_types, 1 ), '</pre>';
		echo json_encode( $post_types );

		add_action( 'wp_ajax_save_facebook_feed_settings', array( $this, 'eael_save_facebook_feed_settings' ) );

	}

	/**
	 * Loading all essential scripts
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function enqueue_eael_admin_scripts() {

		wp_enqueue_style( 'essential_addons_elementor-notice-css', plugins_url( '/', __FILE__ ).'assets/css/eael-notice.css' );
		if( isset( $_GET['page'] ) && $_GET['page'] == 'eael-settings' ) {
			wp_enqueue_style( 'essential_addons_elementor-admin-css', plugins_url( '/', __FILE__ ).'assets/css/admin.css' );
			wp_enqueue_style( 'essential_addons_elementor-sweetalert2-css', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/css/sweetalert2.min.css' );
			wp_enqueue_script( 'essential_addons_elementor-admin-js', plugins_url( '/', __FILE__ ).'assets/js/admin.js', array( 'jquery'), '1.0', true );
			wp_enqueue_script( 'essential_addons_core-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/core.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'essential_addons_sweetalert2-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'essential_addons_core-js' ), '1.0', true );
		}

	}

	/**
	 * Create an admin menu.
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function create_eael_admin_menu() {

		add_submenu_page(
			'elementor',
			'Essential Addons',
			'Essential Addons',
			'manage_options',
			'eael-settings',
			array( $this, 'eael_admin_settings_page' )
		);

	}

	/**
	 * Create settings page.
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function eael_admin_settings_page() {

		$js_info = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'essential_addons_elementor-admin-js', 'js_eael_lite_settings', $js_info );

	   /**
	    * This section will handle the "eael_save_settings" array. If any new settings options is added
	    * then it will matches with the older array and then if it founds anything new then it will update the entire array.
	    */
	   $this->eael_default_settings = array_fill_keys( $this->eael_default_keys, true );
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
	   $eael_new_settings = array_diff_key( $this->eael_default_settings, $this->eael_get_settings );

	   if( ! empty( $eael_new_settings ) ) {
			$eael_updated_settings = array_merge( $this->eael_get_settings, $eael_new_settings );
			update_option( 'eael_save_settings', $eael_updated_settings );
	   }
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
	?>
		<div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/ea-logo.svg'; ?>">
						</div>
						<h2 class="title"><?php _e( 'Essential Addons Settings', 'essential-addons-elementor' ); ?></h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-settings.svg'; ?>"><span>General</span></a></li>
				      	<li><a href="#elements"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-modules.svg'; ?>"><span>Elements</span></a></li>
						<li><a href="#extensions"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-extensions.svg'; ?>"><span>Extensions</span></a></li>
				      	<li><a href="#version-control"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-version-control.svg'; ?>"><span>Version Control</span></a></li>
						<li><a href="#go-pro"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-upgrade.svg'; ?>"><span>Go Premium</span></a></li>
			    	</ul>
					<?php
						include('partials/general.php');
						include('partials/elements.php');
						include('partials/extensions.php');
						include('partials/version-control.php');
						include('partials/go-pro.php');
					?>
			  	</div>
		  	</form>
		</div>
		<?php

	}

	/**
	 * Saving data with ajax request
	 * @param
	 * @return  array
	 * @since 1.1.2
	 */
	public function eael_save_settings_with_ajax() {

		if( isset( $_POST['fields'] ) ) {
			parse_str( $_POST['fields'], $settings );
		}else {
			return;
		}

		$this->eael_settings = [];

		foreach( $this->eael_default_keys as $key ){
			if( isset( $settings[ $key ] ) ) {
				$this->eael_settings[ $key ] = 1;
			} else {
				$this->eael_settings[ $key ] = 0;
			}
		}
		update_option( 'eael_save_settings', $this->eael_settings );
		return true;
		die();

	}

}

new Eael_Admin_Settings();
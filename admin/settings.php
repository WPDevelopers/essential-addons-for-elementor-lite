<?php 
/**
 * Admin Settings Page
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Eael_Admin_Settings {
	protected $is_pro = FALSE;
	private $eael_default_settings = array(
		'contact-form-7'     => true,
	   'count-down'         => true,
	   'creative-btn'       => true,
	   'fancy-text'         => true,
	   'post-grid'          => true,
	   'post-timeline'      => true,
	   'product-grid'       => true,
	   'team-members'       => true,
	   'testimonials'       => true,
	   'weforms'            => true,
	);

	private $eael_settings;
	private $eael_get_settings;

	/**
	 * Initializing all default hooks and functions
	 * @param 
	 * @return void
	 * @since 1.1.2
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'create_eael_admin_menu' ) );	
		add_action( 'init', array( $this, 'enqueue_eael_admin_scripts' ) );
		add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'eael_save_settings_with_ajax' ) );
		add_action( 'wp_ajax_nopriv_save_settings_with_ajax', array( $this, 'eael_save_settings_with_ajax' ) );
		add_action( 'wp_head', array( $this, 'eael_add_custom_code_in_wp_head' ), 9999 );
		add_action( 'wp_footer', array( $this, 'eael_add_custom_js_in_wp_footer' ), 9999 );

	}

	/**
	 * Loading all essential scripts
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function enqueue_eael_admin_scripts() {

		if( isset( $_GET['page'] ) && $_GET['page'] == 'eael-settings' ) {
			wp_enqueue_style( 'essential_addons_elementor-admin-css', plugins_url( '/', __FILE__ ).'assets/css/admin.css' );
			wp_enqueue_style( 'font-awesome-css', plugins_url( '/', __FILE__ ).'assets/vendor/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_style( 'essential_addons_elementor-sweetalert2-css', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/css/sweetalert2.min.css' );

			wp_enqueue_script( "jquery-ui-tabs" );
			wp_enqueue_script( 'essential_addons_elementor-admin-js', plugins_url( '/', __FILE__ ).'assets/js/admin.js', array( 'jquery', 'jquery-ui-tabs' ), '1.0', true );
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

		add_menu_page( 
			'Essential Addon Elementor Lite', 
			'Essential Addon Elementor Lite', 
			'manage_options', 
			'eael-settings', 
			array( $this, 'eael_admin_settings_page' ), 
			'dashicons-admin-generic', 
			199  
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
		wp_localize_script( 'essential_addons_elementor-admin-js', 'settings', $js_info );

	   /**
	    * This section will handle the "eael_save_settings" array. If any new settings options is added
	    * then it will matches with the older array and then if it founds anything new then it will update the entire array.
	    */
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
	   $eael_new_settings = array_diff_key( $this->eael_default_settings, $this->eael_get_settings );
	   if( ! empty( $eael_new_settings ) ) {
	   	$eael_updated_settings = array_merge( $this->eael_get_settings, $eael_new_settings );
	   	update_option( 'eael_save_settings', $eael_updated_settings );
	   }
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
		?>
		<div class="wrap">
		  	<h2><?php _e( 'Essential Elementor Addon Settings', 'essential-addons-elementor' ); ?></h2> <hr>
		  	<div class="response-wrap"></div>
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
			  	<div class="eael-settings-tabs">
			    	<ul>
				      <li><a href="#general"><i class="fa fa-cogs"></i> General</a></li>
				      <li><a href="#elements"><i class="fa fa-cubes"></i> Elements</a></li>
				      <li><a href="#custom-css"><i class="fa fa-code"></i> Custom Code</a></li>
				      <li><a href="#go-pro"><i class="fa fa-bolt"></i> Go Pro</a></li>
				      <li><a href="#support"><i class="fa fa-ticket"></i> Support</a></li>
			    	</ul>
			    	<div id="general" class="eael-settings-tab">
			      	<p>General Settings</p>
			    	</div>
			    	<div id="elements" class="eael-settings-tab">
			      	<div class="row">
			      		<div class="col-full">
			      			<table class="form-table">
									<tr>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Contact Form 7', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Contact Form 7', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="contact-form-7" name="contact-form-7" <?php checked( 1, $this->eael_get_settings['contact-form-7'], true ); ?> >
				                        <label for="contact-form-7"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Count Down', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Count Down', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="count-down" name="count-down" <?php checked( 1, $this->eael_get_settings['count-down'], true ); ?> >
				                        <label for="count-down"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Creative Button', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Creative Button', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="creative-btn" name="creative-btn" <?php checked( 1, $this->eael_get_settings['creative-btn'], true ); ?> >
				                        <label for="creative-btn"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Fancy Text', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Fancy Text', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="fancy-text" name="fancy-text" <?php checked( 1, $this->eael_get_settings['fancy-text'], true ); ?> >
				                        <label for="fancy-text"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Post Grid', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Grid', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="post-grid" name="post-grid" <?php checked( 1, $this->eael_get_settings['post-grid'], true ); ?> >
				                        <label for="post-grid"></label>
				                    	</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Post Timeline', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Timeline', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="post-timeline" name="post-timeline" <?php checked( 1, $this->eael_get_settings['post-timeline'], true ); ?> >
				                        <label for="post-timeline"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Product Grid', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Product Grid', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="product-grid" name="product-grid" <?php checked( 1, $this->eael_get_settings['product-grid'], true ); ?> >
				                        <label for="product-grid"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Team Member', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Team Member', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="team-members" name="team-members" <?php checked( 1, $this->eael_get_settings['team-members'], true ); ?> >
				                        <label for="team-members"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Testimonials', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Testimonials', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="testimonials" name="testimonials" <?php checked( 1, $this->eael_get_settings['testimonials'], true ); ?> >
				                        <label for="testimonials"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'We-Forms', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive WeForms', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="weforms" name="weforms" <?php checked( 1, $this->eael_get_settings['weforms'], true ); ?> >
				                        <label for="weforms"></label>
				                    	</div>
										</td>
									</tr>
					      	</table>
			      		</div>
			      		<div class="col-full">
			      			<h2 class="section-title">Pro Version Components!</h2>
			      			<table class="form-table">
									<tr>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Image Comparison', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Image Comparison', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="img-comparison" name="img-comparison" disabled >
				                        <label for="img-comparison" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Instagram Gallery', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Instagram Gallery', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="instagram-gallery" name="instagram-gallery" disabled >
				                        <label for="instagram-gallery" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Interactive Promo', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Interactive Promo', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="interactive-promo" name="interactive-promo" disabled >
				                        <label for="interactive-promo" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Lightbox', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Lightbox', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="lightbox" name="lightbox" disabled >
				                        <label for="lightbox" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Post Block', 'essential-addons-elementor' ); ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Post Block', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="post-block" name="post-block" disabled >
				                        <label for="post-block" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Testimonial Slider', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Testimonial Slider', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="testimonial-slider" name="testimonial-slider" disabled >
				                        <label for="testimonial-slider" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Static Product', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Static Product', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="static-product" name="static-product" disabled >
				                        <label for="static-product" class="<?php if( (bool) $is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
				                    	</div>
										</td>
									</tr>
					      	</table>
			      		</div>
			      	</div>
			    	</div>
			    	<div id="custom-css" class="eael-settings-tab">
			      	<div class="row">
			      		<div class="col-half">
			      			<p class="title">Custom Css</p>
			      			<p class="desc">Add your custom css code here.</p>
								<textarea name="eael-custom-css" id="eael-custom-css" class="eael-form-control" rows="10"><?php if( !empty( $this->eael_get_settings['eael-custom-css'] ) ) : echo $this->eael_get_settings['eael-custom-css']; else : $this->eael_get_settings['eael-custom-css'] = ''; endif; ?></textarea>
			      		</div>
			      	</div>
			      	<div class="row">
			      		<div class="col-half">
			      			<p class="title">Custom Js</p>
			      			<p class="desc">Add your custom javascript code here.</p>
			      			<textarea name="eael-custom-js" id="eael-custom-js" class="eael-form-control" rows="10"><?php if( !empty( $this->eael_get_settings['eael-custom-js'] ) ) : echo $this->eael_get_settings['eael-custom-js']; else: $this->eael_get_settings['eael-custom-js'] = ''; endif;  ?></textarea>
			      		</div>
			      	</div>
			    	</div>
			    	<div id="go-pro" class="eael-settings-tab">
			      	<div class="col-half">
			      		<h4>Why upgrade to Premium Version of the plugin?!</h4>
			      		<p>The premium version helps us to continue development of this plugin incorporating even more features and enhancements along with offering more responsive support. Following are some of the reasons why you may want to upgrade to the premium version of this plugin.</p>
			      		<a href="#" class="button button-primary">Purhcase Premium Version</a>
			      	</div>
			    	</div>
			    	<div id="support" class="eael-settings-tab">
			      	<div class="row">
			      		<div class="col-half">
				      		<h4>Need any help? Open a ticket!</h4>
				      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi maxime quis deleniti iure placeat ducimus voluptate perspiciatis nam eveniet eos accusantium maiores nulla temporibus fuga sunt tenetur error, delectus veniam.</p>
				      		<a href="#" class="button button-primary">Get Help</a>
				      	</div>
				      	<div class="col-half">
				      		<h4>Need any help? Open a ticket!</h4>
				      		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi maxime quis deleniti iure placeat ducimus voluptate perspiciatis nam eveniet eos accusantium maiores nulla temporibus fuga sunt tenetur error, delectus veniam.</p>
				      		<a href="#" class="button button-primary">Get Help</a>
				      	</div>
			      	</div>
			    	</div>
			  	</div>
			  	<div class="eael-settings-footer">
			  		<input type="submit" value="Save settings" class="button button-primary"/>
			  	</div>
		  	</form>
		</div>
		<?php
		
	}

	/**
	 * Saving data with ajax request
	 * @param 
	 * @return  array in json
	 * @since 1.1.2 
	 */
	public function eael_save_settings_with_ajax() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->eael_settings = array(
				'contact-form-7' 		=> intval( $_POST['contactForm7'] ? 1 : 0 ),
				'count-down' 			=> intval( $_POST['countDown'] ? 1 : 0 ),
				'creative-btn' 		=> intval( $_POST['creativeBtn'] ? 1 : 0 ),
				'fancy-text' 			=> intval( $_POST['fancyText'] ? 1 : 0 ),
				'post-grid' 			=> intval( $_POST['postGrid'] ? 1 : 0 ),
				'post-timeline' 		=> intval( $_POST['postTimeline'] ? 1 : 0 ),
				'product-grid' 		=> intval( $_POST['productGrid'] ? 1 : 0 ),
				'team-members' 		=> intval( $_POST['teamMembers'] ? 1 : 0 ),
				'testimonials' 		=> intval( $_POST['testimonials'] ? 1 : 0 ),
				'weforms' 				=> intval( $_POST['weForms'] ? 1 : 0 ),

				'eael-custom-css'		=> wp_unslash( $_POST['customCss'] ),
				'eael-custom-js'		=> wp_unslash( $_POST['customJs'] ),
			);
			update_option( 'eael_save_settings', $this->eael_settings );
			return true;
			die();
		}

	}

	/**
	 * Saving custom css in the header
	 * @param 
	 * @return  string
	 * @since 1.1.2 
	 */
	public function eael_add_custom_code_in_wp_head() {

		$this->eael_get_settings = get_option( 'eael_save_settings', false );
		?>
		<style>
			<?php echo( $this->eael_get_settings['eael-custom-css'] ); ?>
		</style>
		<?php 

	} 

	/**
	 * Saving custom js in the footer
	 * @param 
	 * @return  string
	 * @since 1.1.2 
	 */
	public function eael_add_custom_js_in_wp_footer() {

		$this->eael_get_settings = get_option( 'eael_save_settings', false );
		?>
		<script>
			( function($) {
				<?php echo ( $this->eael_get_settings['eael-custom-js'] ); ?>
			} )(jQuery);
		</script>
		<?php

	} 

}

new Eael_Admin_Settings();

	



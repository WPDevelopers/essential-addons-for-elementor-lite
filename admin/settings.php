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
	   'call-to-action'     => true,
	   'flip-box'     		=> true,
	   'info-box'     		=> true,
	   'dual-header'     	=> true,
	   'price-table'     	=> true,
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
			'Essential Addons Elementor', 
			'Essential Addons Elementor', 
			'manage_options', 
			'eael-settings', 
			array( $this, 'eael_admin_settings_page' ), 
			plugins_url( '/', __FILE__ ).'/assets/images/ea-icon.png',
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
			<div class="response-wrap"></div>
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<h4 class="title"><?php _e( 'Essential Addons Settings', 'essential-addons-elementor' ); ?></h4>
					</div>
					<div class="eael-header-right">
						<input type="submit" value="Save settings" class="button eael-btn"/>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul>
				      <li><a href="#general"><i class="fa fa-cogs"></i> General</a></li>
				      <li><a href="#elements"><i class="fa fa-cubes"></i> Elements</a></li>
				      <li><a href="#custom-css"><i class="fa fa-code"></i> Custom Code</a></li>
				      <li><a href="#go-pro"><i class="fa fa-bolt"></i> Go Premium</a></li>
				      <li><a href="#support"><i class="fa fa-ticket"></i> Support</a></li>
			    	</ul>
			    	<div id="general" class="eael-settings-tab">
						<div class="row">
			      			<div class="col-half">

			      				<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/eael-featured.png'; ?>">
			      			</div>
			      			<div class="col-half">
			      				<a href="https://essential-addons.com/elementor/" target="_blank" class="button eael-btn eael-demo-btn">Explore Demos</a>
			      				<a href="https://essential-addons.com/elementor/buy.php" target="_blank" class="button eael-btn eael-license-btn">Get Pro License</a>
			      				
			      				<div class="eael-notice">
			      					<h5>Troubleshooting Info</h5>
			      					<p>After update, if you see any element is not working properly, go to <strong>Elements</strong> Tab, toggle the element and save changes.</p>
			      				</div>
			    			</div>
			    		</div>
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
									<tr>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Call To Action', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Call To Action', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="call-to-action" name="call-to-action" <?php checked( 1, $this->eael_get_settings['call-to-action'], true ); ?> >
				                        <label for="call-to-action"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Flip Box', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Flip Box', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="flip-box" name="flip-box" <?php checked( 1, $this->eael_get_settings['flip-box'], true ); ?> >
				                        <label for="flip-box"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Info Box', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Info Box', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="info-box" name="info-box" <?php checked( 1, $this->eael_get_settings['info-box'], true ); ?> >
				                        <label for="info-box"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Dual Color Header', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Dual Color Header', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="dual-header" name="dual-header" <?php checked( 1, $this->eael_get_settings['dual-header'], true ); ?> >
				                        <label for="dual-header"></label>
				                    	</div>
										</td>
										<td>
											<div class="eael-checkbox">
												<p class="title"><?php _e( 'Pricing Table', 'essential-addons-elementor' ) ?></p>
												<p class="desc"><?php _e( 'Activate / Deactive Pricing Table', 'essential-addons-elementor' ); ?></p>
				                        <input type="checkbox" id="price-table" name="price-table" <?php checked( 1, $this->eael_get_settings['price-table'], true ); ?> >
				                        <label for="price-table"></label>
				                    	</div>
										</td>
									</tr>
					      	</table>
			      		</div>
			      		<div class="col-full">
			      			<div class="premium-elements-title">
			      				<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/lock-icon.png'; ?>">
			      				<h4 class="section-title">Premium Elements</h4>
			      			</div>
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
						  	<div class="eael-save-btn-wrap">
						  		<input type="submit" value="Save settings" class="button eael-btn"/>
						  	</div>
			      		</div>
			      	</div>
			    	</div>
			    	<div id="custom-css" class="eael-settings-tab">
			      	<div class="row">
			      		<div class="col-half">
			      			<p class="title">Custom CSS</p>
			      			<p class="desc">Add your custom CSS code here without <code>style</code> tag.</p>
								<textarea name="eael-custom-css" id="eael-custom-css" class="eael-form-control" rows="10"><?php if( !empty( $this->eael_get_settings['eael-custom-css'] ) ) : echo $this->eael_get_settings['eael-custom-css']; else : $this->eael_get_settings['eael-custom-css'] = ''; endif; ?></textarea>
			      		</div>
			      	</div>
			      	<div class="row">
			      		<div class="col-half">
			      			<p class="title">Custom JavaScript</p>
			      			<p class="desc">Add your custom JavaScript code here without <code>script</code> tag.</p>
			      			<textarea name="eael-custom-js" id="eael-custom-js" class="eael-form-control" rows="10"><?php if( !empty( $this->eael_get_settings['eael-custom-js'] ) ) : echo $this->eael_get_settings['eael-custom-js']; else: $this->eael_get_settings['eael-custom-js'] = ''; endif;  ?></textarea>
			      		</div>
			      	</div>
				  	<div class="eael-save-btn-wrap">
				  		<input type="submit" value="Save settings" class="button eael-btn"/>
				  	</div>
			    	</div>
			    	<div id="go-pro" class="eael-settings-tab">
			    		<div class="row go-premium">
			      			<div class="col-half">
			      				<h4>Why upgrade to Premium Version?</h4>
			      				<p>The premium version helps us to continue development of the product incorporating even more features and enhancements.</p>

			      				<p>You will also get world class support from our dedicated team, 24/7.</p>

			      				<a href="https://essential-addons.com/elementor/buy.php" target="_blank" class="button eael-btn eael-license-btn">Get Premium Version</a>
			      			</div>
			      			<div class="col-half">
			      				<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/unlock-gif.gif'; ?>">
			      			</div>
			      		</div>
			    	</div>
			    	<div id="support" class="eael-settings-tab">
			      	<div class="row">
			      		<div class="col-half">
				      		<h4>Need help? Open a support ticket!</h4>
				      		<p>You can always get support from the community.</p>
				      		<a href="https://wordpress.org/support/plugin/essential-addons-for-elementor-lite" target="_blank" class="button eael-btn">Get Help</a>
				      	</div>
			      		<div class="col-half">
				      		<h4>Need Premium Support?</h4>
				      		<p>Purchasing a license entitles you to receive premium support.</p>
				      		<a href="https://essential-addons.com/elementor/buy.php" target="_blank" class="button eael-btn">Get a license</a>
				      	</div>
			      	</div>
			      	<div class="row">
			      		<div class="col-half">
			      			<div class="essential-addons-community-link">
			      				<a href="https://www.facebook.com/groups/essentialaddons/" target="_blank"><i class="fa fa-facebook-official fa-2x fa-fw" aria-hidden="true"></i> <span>Join the Facebook Community</span></a>
			      			</div>
			      		</div>
			      	</div>
			    	</div>
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
				'call-to-action' 		=> intval( $_POST['callToAction'] ? 1 : 0 ),
				'flip-box' 				=> intval( $_POST['flipBox'] ? 1 : 0 ),
				'info-box' 				=> intval( $_POST['infoBox'] ? 1 : 0 ),
				'dual-header' 			=> intval( $_POST['dualHeader'] ? 1 : 0 ),
				'price-table' 			=> intval( $_POST['priceTable'] ? 1 : 0 ),

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
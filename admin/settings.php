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
	public $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'gravity-form', 'caldera-form', 'wisdom_registered_setting', 'twitter-feed', 'facebook-feed', 'twitter-feed-carousel', 'facebook-feed-carousel', 'data-table', 'filter-gallery', 'dynamic-filter-gallery' ];

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

		add_action( 'admin_menu', array( $this, 'create_eael_admin_menu' ) );
		add_action( 'init', array( $this, 'enqueue_eael_admin_scripts' ) );
		add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'eael_save_settings_with_ajax' ) );

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
	   $this->eael_default_settings = array_fill_keys( $this->eael_default_keys, true );
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
						<input type="submit" value="Save settings" class="button eael-btn js-eael-settings-save"/>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><i class="fa fa-cogs"></i> General</a></li>
				      	<li><a href="#elements"><i class="fa fa-cubes"></i> Elements</a></li>
				      	<li><a href="#go-pro"><i class="fa fa-bolt"></i> Go Premium</a></li>
				      	<li><a href="#support"><i class="fa fa-ticket"></i> Support</a></li>
			    	</ul>
			    	<div id="general" class="eael-settings-tab active">
						<div class="row">
			      			<div class="col-half">
			      				<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/eael-featured.png'; ?>">
			      			</div>
			      			<div class="col-half">
			      				<a href="https://essential-addons.com/elementor/" target="_blank" class="button eael-btn eael-demo-btn">Explore Demos</a>
			      				<a href="https://wpdeveloper.net/review-essential-addons-elementor" target="_blank" class="button eael-btn eael-review-btn">Leave a review</a>
			      				<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" class="button eael-btn eael-license-btn">Get Pro License</a>

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
				      			<p class="eael-elements-control-notice">You can disable the elements you are not using on your site. That will disable all associated assets of those widgets to improve your site loading.</p>
				      			<table class="form-table">
										<tr>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Contact Form 7', 'essential-addons-elementor' ); ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Contact Form 7', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="contact-form-7" name="contact-form-7" <?php checked( 1, $this->eael_get_settings['contact-form-7'], true ); ?> >
					                        <label for="contact-form-7"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Count Down', 'essential-addons-elementor' ); ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Count Down', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="count-down" name="count-down" <?php checked( 1, $this->eael_get_settings['count-down'], true ); ?> >
					                        <label for="count-down"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Creative Button', 'essential-addons-elementor' ); ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Creative Button', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="creative-btn" name="creative-btn" <?php checked( 1, $this->eael_get_settings['creative-btn'], true ); ?> >
					                        <label for="creative-btn"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Fancy Text', 'essential-addons-elementor' ); ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Fancy Text', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="fancy-text" name="fancy-text" <?php checked( 1, $this->eael_get_settings['fancy-text'], true ); ?> >
					                        <label for="fancy-text"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Post Grid', 'essential-addons-elementor' ); ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Post Grid', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="post-grid" name="post-grid" <?php checked( 1, $this->eael_get_settings['post-grid'], true ); ?> >
					                        <label for="post-grid"></label>
					                    	</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Post Timeline', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Post Timeline', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="post-timeline" name="post-timeline" <?php checked( 1, $this->eael_get_settings['post-timeline'], true ); ?> >
					                        <label for="post-timeline"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Product Grid', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Product Grid', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="product-grid" name="product-grid" <?php checked( 1, $this->eael_get_settings['product-grid'], true ); ?> >
					                        <label for="product-grid"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Team Member', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Team Member', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="team-members" name="team-members" <?php checked( 1, $this->eael_get_settings['team-members'], true ); ?> >
					                        <label for="team-members"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Testimonials', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Testimonials', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="testimonials" name="testimonials" <?php checked( 1, $this->eael_get_settings['testimonials'], true ); ?> >
					                        <label for="testimonials"></label>
					                    	</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'We-Forms', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate WeForms', 'essential-addons-elementor' ); ?></p>
					                        <input type="checkbox" id="weforms" name="weforms" <?php checked( 1, $this->eael_get_settings['weforms'], true ); ?> >
					                        <label for="weforms"></label>
					                    	</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Call To Action', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Call To Action', 'essential-addons-elementor' ); ?></p>
					                        	<input type="checkbox" id="call-to-action" name="call-to-action" <?php checked( 1, $this->eael_get_settings['call-to-action'], true ); ?> >
					                        	<label for="call-to-action"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Flip Box', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Flip Box', 'essential-addons-elementor' ); ?></p>
					                        	<input type="checkbox" id="flip-box" name="flip-box" <?php checked( 1, $this->eael_get_settings['flip-box'], true ); ?> >
					                        	<label for="flip-box"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Info Box', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Info Box', 'essential-addons-elementor' ); ?></p>
					                        	<input type="checkbox" id="info-box" name="info-box" <?php checked( 1, $this->eael_get_settings['info-box'], true ); ?> >
					                        	<label for="info-box"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Dual Color Header', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Dual Color Header', 'essential-addons-elementor' ); ?></p>
					                        	<input type="checkbox" id="dual-header" name="dual-header" <?php checked( 1, $this->eael_get_settings['dual-header'], true ); ?> >
					                        	<label for="dual-header"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Pricing Table', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Pricing Table', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="price-table" name="price-table" <?php checked( 1, $this->eael_get_settings['price-table'], true ); ?> >
					                        		<label for="price-table"></label>
					                    		</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Ninja Form', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Ninja Form', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="ninja-form" name="ninja-form" <?php checked( 1, $this->eael_get_settings['ninja-form'], true ); ?> >
					                        		<label for="ninja-form"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Gravity Form', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Gravity Form', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="gravity-form" name="gravity-form" <?php checked( 1, $this->eael_get_settings['gravity-form'], true ); ?> >
					                        		<label for="gravity-form"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Caldera Form', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Caldera Form', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="caldera-form" name="caldera-form" <?php checked( 1, $this->eael_get_settings['caldera-form'], true ); ?> >
					                        		<label for="caldera-form"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Twitter Feed', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Twitter Feed', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="twitter-feed" name="twitter-feed" <?php checked( 1, $this->eael_get_settings['twitter-feed'], true ); ?> >
					                        		<label for="twitter-feed"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Facebook Feed', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Facebook Feed', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="facebook-feed" name="facebook-feed" <?php checked( 1, $this->eael_get_settings['facebook-feed'], true ); ?> >
					                        		<label for="facebook-feed"></label>
					                    		</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Data Table', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Data Table', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="data-table" name="data-table" <?php checked( 1, $this->eael_get_settings['data-table'], true ); ?> >
					                        		<label for="data-table"></label>
					                    		</div>
											</td>
											<td>
												<div class="eael-checkbox">
													<p class="title"><?php _e( 'Filterable Gallery', 'essential-addons-elementor' ) ?></p>
													<p class="desc"><?php _e( 'Activate / Deactivate Filterable Gallery', 'essential-addons-elementor' ); ?></p>
					                       	 		<input type="checkbox" id="filter-gallery" name="filter-gallery" <?php checked( 1, $this->eael_get_settings['filter-gallery'], true ); ?> >
					                        		<label for="filter-gallery"></label>
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
								                <p class="title">
								                    <?php _e( 'Image Comparison', 'essential-addons-elementor' ); ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Image Comparison', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="img-comparison" name="img-comparison" disabled>
								                <label for="img-comparison" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Instagram Gallery', 'essential-addons-elementor' ); ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Instagram Gallery', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="instagram-gallery" name="instagram-gallery" disabled>
								                <label for="instagram-gallery" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Interactive Promo', 'essential-addons-elementor' ); ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Interactive Promo', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="interactive-promo" name="interactive-promo" disabled>
								                <label for="interactive-promo" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Lightbox', 'essential-addons-elementor' ); ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Lightbox', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="lightbox" name="lightbox" disabled>
								                <label for="lightbox" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Post Block', 'essential-addons-elementor' ); ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Post Block', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="post-block" name="post-block" disabled>
								                <label for="post-block" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								    </tr>
								    <tr>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Testimonial Slider', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Testimonial Slider', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="testimonial-slider" name="testimonial-slider" disabled>
								                <label for="testimonial-slider" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Static Product', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Static Product', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="static-product" name="static-product" disabled>
								                <label for="static-product" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Flip Carousel', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Flip Carousel', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="flip-carousel" name="flip-carousel" disabled>
								                <label for="flip-carousel" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Interactive Cards', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Interactive Cards', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="interactive-cards" name="interactive-cards" disabled>
								                <label for="interactive-cards" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Content Timeline', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Content Timeline', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="content-timeline" name="content-timeline" disabled>
								                <label for="content-timeline" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								    </tr>
								    <tr>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Data Table', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Data Table', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="data-table" name="data-table" disabled>
								                <label for="data-table" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Twitter Feed Carousel', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Twitter Feed Carousel', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="twitter-feed-carousel" name="twitter-feed-carousel" disabled>
								                <label for="twitter-feed-carousel" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Facebook Feed Carousel', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Facebook Feed Carousel', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="facebook-feed-carousel" name="facebook-feed-carousel" disabled>
								                <label for="facebook-feed-carousel" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								        <td>
								            <div class="eael-checkbox">
								                <p class="title">
								                    <?php _e( 'Dynamic Filter Gallery', 'essential-addons-elementor' ) ?>
								                </p>
								                <p class="desc">
								                    <?php _e( 'Activate / Deactivate Dynamic Filter Gallery', 'essential-addons-elementor' ); ?>
								                </p>
								                <input type="checkbox" id="dynamic-filter-gallery" name="dynamic-filter-gallery" disabled>
								                <label for="dynamic-filter-gallery" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
								            </div>
								        </td>
								    </tr>
								</table>
							  	<div class="eael-save-btn-wrap">
							  		<input type="submit" value="Save settings" class="button eael-btn js-eael-settings-save"/>
							  	</div>
				      		</div>
				      	</div>
			    	</div>
			    	<div id="go-pro" class="eael-settings-tab">
			    		<div class="row go-premium">
			      			<div class="col-half">
			      				<h4>Why upgrade to Premium Version?</h4>
			      				<p>The premium version helps us to continue development of the product incorporating even more features and enhancements.</p>

			      				<p>You will also get world class support from our dedicated team, 24/7.</p>

			      				<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" class="button eael-btn eael-license-btn">Get Premium Version</a>
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
					      		<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" class="button eael-btn">Get a license</a>
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
	 * @return  array
	 * @since 1.1.2
	 */
	public function eael_save_settings_with_ajax() {

		if( isset( $_POST['fields'] ) ) {
			parse_str( $_POST['fields'], $settings );
		}else {
			return;
		}

		$this->eael_settings = array(
		    'contact-form-7' 	=> intval( $settings['contact-form-7'] ? 1 : 0 ),
		    'count-down' 		=> intval( $settings['count-down'] ? 1 : 0 ),
		    'creative-btn' 		=> intval( $settings['creative-btn'] ? 1 : 0 ),
		    'fancy-text' 		=> intval( $settings['fancy-text'] ? 1 : 0 ),
		    'post-grid' 		=> intval( $settings['post-grid'] ? 1 : 0 ),
		    'post-timeline' 	=> intval( $settings['post-timeline'] ? 1 : 0 ),
		    'product-grid' 		=> intval( $settings['product-grid'] ? 1 : 0 ),
		    'team-members' 		=> intval( $settings['team-members'] ? 1 : 0 ),
		    'testimonials' 		=> intval( $settings['testimonials'] ? 1 : 0 ),
		    'weforms' 			=> intval( $settings['weforms'] ? 1 : 0 ),
		    'call-to-action' 	=> intval( $settings['call-to-action'] ? 1 : 0 ),
		    'flip-box' 			=> intval( $settings['flip-box'] ? 1 : 0 ),
		    'info-box' 			=> intval( $settings['info-box'] ? 1 : 0 ),
		    'dual-header' 		=> intval( $settings['dual-header'] ? 1 : 0 ),
		    'price-table' 		=> intval( $settings['price-table'] ? 1 : 0 ),
		    'ninja-form' 		=> intval( $settings['ninja-form'] ? 1 : 0 ),
		    'gravity-form' 		=> intval( $settings['gravity-form'] ? 1 : 0 ),
		    'caldera-form' 		=> intval( $settings['gravity-form'] ? 1 : 0 ),
		    'twitter-feed' 		=> intval( $settings['twitter-feed'] ? 1 : 0 ),
		    'facebook-feed' 	=> intval( $settings['facebook-feed'] ? 1 : 0 ),
		    'data-table' 		=> intval( $settings['data-table'] ? 1 : 0 ),
		    'filter-gallery' 	=> intval( $settings['filter-gallery'] ? 1 : 0 ),
		    'wisdom_registered_setting' => 1,
		);
		update_option( 'eael_save_settings', $this->eael_settings );
		return true;
		die();


	}

}

new Eael_Admin_Settings();
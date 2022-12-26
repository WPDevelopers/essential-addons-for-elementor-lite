<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Business_Reviews extends Widget_Base {
	
	public function get_name() {
		return 'eael-business-reviews';
	}

	public function get_title() {
		return esc_html__( 'Business Reviews', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-business-reviews';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'reviews',
			'ea reviews',
			'business reviews',
			'ea business reviews',
			'google reviews',
			'ea google reviews',
			'ea',
			'essential addons'
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/business-reviews/';
	}

	public function get_style_depends()
	{
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		];
	}

	public function get_script_depends()
	{
		return [
			'font-awesome-4-shim'
		];
	}

	protected function register_controls() {

		/**
		 * Business Reviews Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_general_settings',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_sources',
			[
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'google-reviews',
				'options' => [
					'google-reviews' => __( 'Google Reviews', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

        if (empty(get_option('eael_br_google_place_api_key'))) {
            $this->add_control('eael_br_google_place_api_key_missing', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('Google Place API key is missing. Please add it from EA Dashboard » Elements » <a href="%s" target="_blank">Business Reviews Settings</a>', 'essential-addons-elementor'), esc_attr( site_url('/wp-admin/admin.php?page=eael-settings') )),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'eael_business_reviews_sources' => 'google-reviews',
                ],
            ]);
        }

		$this->end_controls_section();

		/**
		 * Business Reviews Layout Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_layout_settings',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Business Reviews Content
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();
	}

	/**
     * API Call to Get Business Reviews
     */
	public function fetch_business_reviews_from_api(){
		$settings = $this->get_settings();
		$settings['eael_business_reviews_source_key'] = get_option( 'eael_br_google_place_api_key' );

		$response                        	  = [];
		$business_reviews                     = [];
		$business_reviews['source']           = ! empty( $settings['eael_business_reviews_sources'] ) ? esc_html( $settings['eael_business_reviews_sources'] ) : 'google-reviews';
		$business_reviews['api_key']          = ! empty( $settings['eael_business_reviews_source_key'] ) ? esc_html( $settings['eael_business_reviews_source_key'] ) : '';
		$business_reviews['reviews_sort']	  = sanitize_text_field( 'most_relevant' );

		$expiration 	= DAY_IN_SECONDS;
		$md5        	= md5( $business_reviews['api_key'] . $this->get_id() );
		$cache_key  	= "eael_{$business_reviews['source']}_{$expiration}_{$md5}_brev_cache";
		$items      	= get_transient( $cache_key );

		$error_message 	= '';

		if ( false === $items && 'google-reviews' === $business_reviews['source'] ) {
			$url   = "https://maps.googleapis.com/maps/api/place/details/json";
			$param = array();

			$args = array(
				'key' 	  => sanitize_text_field( $business_reviews['api_key'] ),
				'placeid' => sanitize_text_field( 'ChIJ0cpDbNvBVTcRGX9JNhhpC8I' ),
				'fields'  => sanitize_text_field( 'formatted_address,international_phone_number,name,rating,reviews,url,user_ratings_total,website,photos' ),
			);

			if( ! empty( $business_reviews['reviews_sort'] ) ){
				$args['reviews_sort'] = $business_reviews['reviews_sort'];
			}
			
			$param = array_merge( $param, $args );

			$headers = array(
				'headers' => array(
					'Content-Type' => 'application/json',
				)
			);
			$options = array(
				'timeout' => 240
			);

			$options = array_merge( $headers, $options );

			if ( empty( $error_message ) ) {
				$response = wp_remote_get(
					esc_url_raw( add_query_arg( $param, $url ) ),
					$options
				);

				$body     = json_decode( wp_remote_retrieve_body( $response ) );
				$response = 'OK' === $body->status ? $body->result : false;				

				if ( ! empty( $response ) ) {
					set_transient( $cache_key, $response, $expiration );
				} else {
					$error_message = $this->fetch_api_response_error_message($body->status);
				}
			}

			$data = [
				'items'         => $response,
				'error_message' => $error_message,
			];

			return $data;
		}

		$response = $items ? $items : $response;

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

	public function fetch_api_response_error_message( $status = 'OK' ){
		$error_message = '';

		switch( $status ){
			case 'OK':
				break;

			case 'ZERO_RESULTS':
				$error_message = esc_html__( 'The referenced location, place_id, was valid but no longer refers to a valid result. This may occur if the establishment is no longer in business.', 'essential-addons-for-elementor-lite' );
				break;

			case 'NOT_FOUND':
				$error_message = esc_html__( 'The referenced location, place_id, was not found in the Places database.', 'essential-addons-for-elementor-lite' );
				break;

			case 'INVALID_REQUEST':
				$error_message = esc_html__( 'The API request was malformed.', 'essential-addons-for-elementor-lite' );
				break;

			case 'OVER_QUERY_LIMIT':
				$error_message = esc_html__( 'You have exceeded the QPS limits. Or, Billing has not been enabled on your account. Or, The monthly $200 credit, or a self-imposed usage cap, has been exceeded. Or, The provided method of payment is no longer valid (for example, a credit card has expired).', 'essential-addons-for-elementor-lite' );
				break;

			case 'REQUEST_DENIED':
				$error_message = esc_html__( 'The request is missing an API key. Or, The key parameter is invalid.', 'essential-addons-for-elementor-lite' );
				break;

			case 'UNKNOWN_ERROR':
				$error_message = esc_html__( 'An unknown error occurred.', 'essential-addons-for-elementor-lite' );
				break;

			default:
				break;								
		}

		return $error_message;
	}

	public function fetch_google_reviews_business_photo($photo_obj){
		// $response                        	  = '';
		// $error_message   					  = '';
		// $business_reviews                     = [];
		// $business_reviews['api_key']          = esc_html( get_option( 'eael_br_google_place_api_key' ) );
		
		// $expiration 	= DAY_IN_SECONDS;
		// $md5        	= md5( $business_reviews['api_key'] . $this->get_id() );
		// $cache_key  	= "eael_google_reviews_{$expiration}_{$md5}_brev_photo_cache";
		// $image      	= get_transient( $cache_key );
		
		// // if ( false === $image ) {
		// 	$url   = "https://maps.googleapis.com/maps/api/place/photo";
		// 	if( ! empty( $photo_obj->photo_reference ) ){
		// 		$param = array(
		// 			'key' 	  			=> sanitize_text_field( $business_reviews['api_key'] ),
		// 			'photo_reference' 	=> sanitize_text_field( $photo_obj->photo_reference ),
		// 			'maxwidth' 			=> 400
		// 		);

		// 		$options = array(
		// 			'headers' => array(
		// 				// 'Content-Type' => 'application/json',
		// 			),
		// 			'timeout' => 240,
		// 		);

		// 		$response = download_url(
		// 			esc_url_raw( add_query_arg( $param, $url ) ),
		// 			// $options
		// 		);

		// 		echo $response;
		// 		// Photo Fetch: https://stackoverflow.com/questions/31368072/error-in-fetching-image-using-wp-remote-get-function

		// 		// $body     = json_decode( wp_remote_retrieve_body( $response ) );
		// 		// echo "<pre>";
		// 		// print_r($response);
		// 		// $response = 200 === $body->status ? $body->result : false;				

		// 		if ( ! empty( $response ) ) {
		// 			set_transient( $cache_key, $response, $expiration );
		// 		} else {
		// 			// $error_message = 
		// 		}
		// 	}

		// 	$data = [
		// 		'image'         => $response,
		// 		'error_message' => $error_message,
		// 	];

		// 	return $data;
		// // }

		// $response = $image ? $image : $response;

		// $data = [
		// 	'items'         => $response,
		// 	'error_message' => $error_message,
		// ];

		// return $data;
	}

	public function print_google_reviews_slider( $business_reviews, $google_reviews_data ){
		if ( is_array( $google_reviews_data ) && count( $google_reviews_data ) ){
			//$photo_obj = ! empty( $google_reviews_data['photos'] ) && ! empty( $google_reviews_data['photos'][5] ) ? $google_reviews_data['photos'][5] : $google_reviews_data['photos'][0];
			//$photo_response = $this->fetch_google_reviews_business_photo($photo_obj);
			?>
			<div class="eael-business-reviews-content-main">
				<div class="eael-business-reviews-header">
					Header section 
					<?php 
						// echo "<pre>";
						// print_r($photo_response); 
					?>
				</div>

				<div class="eael-business-reviews-body">
					<div class="eael-business-reviews-sinlge">

					</div>
				</div>
			</div>
			<?php 
			if( is_array( $google_reviews_data['reviews'] ) && count( $google_reviews_data['reviews'] ) ){
				$item_formatted = [];
				
				foreach( $google_reviews_data['reviews'] as $single_review ){
					$item_formatted['review_author_name'] = ! empty( $single_review->author_name ) ? $single_review->author_name : '';
					$item_formatted['review_author_url'] = ! empty( $single_review->author_url ) ? $single_review->author_url : '';
					$item_formatted['review_profile_photo_url'] = ! empty( $single_review->profile_photo_url ) ? $single_review->profile_photo_url : '';
					$item_formatted['review_rating'] = ! empty( $single_review->rating ) ? $single_review->rating : '';
					$item_formatted['review_relative_time_description'] = ! empty( $single_review->relative_time_description ) ? $single_review->relative_time_description : '';
					$item_formatted['review_text'] = ! empty( $single_review->text ) ? $single_review->text : '';
				}
			}
		}
	}

	public function print_business_reviews_google( $business_review_items ){
		$settings = $this->get_settings();
		ob_start();

		$business_reviews     					= [];
		$google_reviews_data  					= [];
		$business_review_obj  					= isset( $business_review_items['items'] ) ? $business_review_items['items'] : false;
		$error_message 		  					= ! empty( $business_review_items['error_message'] ) ? $business_review_items['error_message'] : "";

		$business_reviews['source']            	= ! empty( $settings['eael_business_reviews_sources'] ) ? esc_html( $settings['eael_business_reviews_sources'] ) : 'opensea';
		$business_reviews['layout']            	= ! empty( $settings['eael_business_reviews_items_layout'] ) ? $settings['eael_business_reviews_items_layout'] : 'grid';
		$business_reviews['preset']            	= ! empty( $settings['eael_business_reviews_style_preset'] ) && 'grid' === $business_reviews['layout'] ? $settings['eael_busines$business_reviews_style_preset'] : 'preset-1';
		
		$this->add_render_attribute( 'eael-business-reviews-wrapper', [
			'class'                 => [
				'eael-business-reviews-wrapper',
				'eael-business-reviews-' . $this->get_id(),
				'clearfix',
			],
		] );

		$this->add_render_attribute(
			'eael-business-reviews-items',
			[
				'id'    => 'eael-business-reviews-' . esc_attr( $this->get_id() ),
				'class' => [
					'eael-business-reviews-items',
					'eael-reviews-' . esc_attr( $business_reviews['layout'] ),
					esc_attr( $business_reviews['preset'] ),
				],
			]
		);
		?>

		<div <?php echo $this->get_render_attribute_string('eael-business-reviews-wrapper') ?> >
			<?php if ( is_object( $business_review_obj ) && ! is_null( $business_review_obj ) ) : ?>
			<div <?php echo $this->get_render_attribute_string('eael-business-reviews-items'); ?> >
				<?php 
					$google_reviews_data['formatted_address'] 			= ! empty( $business_review_obj->formatted_address ) ? $business_review_obj->formatted_address : '';
					$google_reviews_data['international_phone_number'] 	= ! empty( $business_review_obj->international_phone_number ) ? $business_review_obj->international_phone_number : '';
					$google_reviews_data['name'] 						= ! empty( $business_review_obj->name ) ? $business_review_obj->name : '';
					$google_reviews_data['photos'] 						= ! empty( $business_review_obj->photos ) ? $business_review_obj->photos : [];
					$google_reviews_data['rating'] 						= ! empty( $business_review_obj->rating ) ? $business_review_obj->rating : '';
					$google_reviews_data['reviews'] 					= ! empty( $business_review_obj->reviews ) ? $business_review_obj->reviews : [];
					$google_reviews_data['url'] 						= ! empty( $business_review_obj->url ) ? $business_review_obj->url : '#';
					$google_reviews_data['user_ratings_total'] 			= ! empty( $business_review_obj->user_ratings_total ) ? $business_review_obj->user_ratings_total : 0;
					$google_reviews_data['website'] 					= ! empty( $business_review_obj->website ) ? $business_review_obj->website : '#';
					
					$this->print_google_reviews_slider($business_reviews, $google_reviews_data);
					//$this->print_google_reviews_grid($business_reviews, $google_reviews_data);
				?>
				<!-- /.column  -->
			</div>
			<?php else: ?>
				<?php printf( '<div class="eael-business-reviews-error-message">%s</div>', esc_html( $error_message ) ); ?>
			<?php endif; ?>
		</div>

		<?php

		// Slider
		$this->add_render_attribute('business-reviews-wrap', 'class', 'swiper-container-wrap');
		$this->add_render_attribute('business-reviews-wrap', 'class', 'eael-arrow-box');
		$this->add_render_attribute('business-reviews-wrap', 'class', 'swiper-container-wrap-dots-outside');

		$this->add_render_attribute('business-reviews-wrap', [
			'class' => ['eael-business-reviews', 'default-style'],
			'id'    => 'eael-business-reviews-' . esc_attr($this->get_id()),
		]);

		$this->add_render_attribute('business-reviews', [
			'class' => [
				'swiper-container',
				'eael-business-reviews-main',
				'swiper-container-' . esc_attr($this->get_id())
			],
			'data-pagination'   => '.swiper-pagination-' . esc_attr($this->get_id()),
			'data-arrow-next'   => '.swiper-button-next-' . esc_attr($this->get_id()),
			'data-arrow-prev'   => '.swiper-button-prev-' . esc_attr($this->get_id())
		]);

		$this->add_render_attribute('business-reviews', 'data-items', 1);
		$this->add_render_attribute('business-reviews', 'data-items-tablet', 1);
		$this->add_render_attribute('business-reviews', 'data-items-mobile', 1);
		$this->add_render_attribute('business-reviews', 'data-margin', 10);
		$this->add_render_attribute('business-reviews', 'data-margin-tablet', 10);
		$this->add_render_attribute('business-reviews', 'data-margin-mobile', 10);
		$this->add_render_attribute('business-reviews', 'data-effect', 'slide');
		$this->add_render_attribute('business-reviews', 'data-speed', 1000);
		$this->add_render_attribute('business-reviews', 'data-loop', 1);
		$this->add_render_attribute('business-reviews', 'data-grab-cursor', 1);
		$this->add_render_attribute('business-reviews', 'data-arrows', 1);
		$this->add_render_attribute('business-reviews', 'data-dots', 1);
		$this->add_render_attribute('business-reviews', 'data-autoplay_speed', 2000);
		$this->add_render_attribute('business-reviews', 'data-pause-on-hover', 'true');
		?>

        <div <?php echo $this->get_render_attribute_string('business-reviews-wrap'); ?>>
			<?php
			$this->render_arrows();
			?>
            <div <?php echo $this->get_render_attribute_string('business-reviews'); ?>>

                <div class="swiper-wrapper">
					<?php
					$i = 0;
					$items = [1, 2, 3];
					foreach ($items as $item) :
						$this->add_render_attribute('business-reviews-content-wrapper' . $i, [
							'class' => ['eael-business-reviews-content', 'rating-five'],
						]);

						$this->add_render_attribute('business-reviews-slide' . $i, [
							'class' => ['eael-business-reviews-item', 'clearfix', 'swiper-slide']
						]);
						?>

                        <div <?php echo $this->get_render_attribute_string('business-reviews-slide' . $i); ?>>
                            <div class="eael-business-reviews-item-inner clearfix">
								<?php //$this->_render_user_avatar($item); ?>
                                <div <?php echo $this->get_render_attribute_string('business-reviews-content-wrapper' . $i); ?>>
									<?php //$this->_render_quote();
									?>
                                    <div class="default-style-business-reviews-content">
										<?php
										//$this->_render_user_description($item, $settings);
										//$this->_render_user_ratings($item);
										//$this->_render_user_meta($item);
										echo $item_formatted['website'] . ' ' . $i;
										?>
                                    </div>
                                </div>
                            </div>
                        </div>

						<?php $i++;
					endforeach; ?>
                </div>
				<?php
				$this->render_dots();
				?>
            </div>
        </div>

		<?php
		echo ob_get_clean();
	}

	/**
	 * Render logo carousel dots output on the frontend.
	 */
	protected function render_dots()
	{
		$settings = $this->get_settings_for_display();
			?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr($this->get_id()); ?>"></div>
			<?php 
	}

	/**
	 * Render logo carousel arrows output on the frontend.
	 */
	protected function render_arrows()
	{
		$settings = $this->get_settings_for_display();

		$pa_next_arrow = 'fa fa-angle-right';
		$pa_prev_arrow = 'fa fa-angle-left';
		?>
		<!-- Add Arrows -->
		<div class="swiper-button-next swiper-button-next-<?php echo esc_attr($this->get_id()); ?>">
			<i class="<?php echo esc_attr($pa_next_arrow); ?>"></i>
		</div>
		<div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr($this->get_id()); ?>">
			<i class="<?php echo esc_attr($pa_prev_arrow); ?>"></i>
		</div>
		<?php 
	}

	protected function render() {
		$business_review_items = $this->fetch_business_reviews_from_api(); 
		$this->print_business_reviews_google( $business_review_items );
	}
}
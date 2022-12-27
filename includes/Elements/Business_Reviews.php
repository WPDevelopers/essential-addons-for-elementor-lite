<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use Wpmet\Libs\Rating;

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

		$this->add_control(
			'eael_business_reviews_items_layout',
			[
				'label'   => esc_html__( 'Layout Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => [
					'slider' => esc_html__( 'Slider', 'essential-addons-for-elementor-lite' ),
					'grid' => esc_html__( 'Grid', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$this->add_control(
			'eael_business_reviews_style_preset_slider',
			[
				'label'     => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'preset-1',
				'options'   => [
					'preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
					'preset-3' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_style_preset_grid',
			[
				'label'     => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'preset-1',
				'options'   => [
					'preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'grid'
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_column',
			[
				'label'     => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider'
				],
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

	public function get_business_reviews_settings(){
		$settings = $this->get_settings();
		$settings['eael_business_reviews_source_key'] = get_option( 'eael_br_google_place_api_key' );

		$business_reviews                  	= [];
		$business_reviews['source']         = ! empty( $settings['eael_business_reviews_sources'] ) ? esc_html( $settings['eael_business_reviews_sources'] ) : 'google-reviews';
		$business_reviews['api_key']        = ! empty( $settings['eael_business_reviews_source_key'] ) ? esc_html( $settings['eael_business_reviews_source_key'] ) : '';

		$business_reviews['expiration'] 	= DAY_IN_SECONDS;
		$business_reviews['md5']        	= md5( $business_reviews['api_key'] . $this->get_id() );
		$business_reviews['cache_key']  	= "eael_{$business_reviews['source']}_{$business_reviews['expiration']}_{$business_reviews['md5']}_brev_cache";

		$business_reviews['layout']        	= ! empty( $settings['eael_business_reviews_items_layout'] ) ? $settings['eael_business_reviews_items_layout'] : 'slider';
		$business_reviews['preset']        	= ! empty( $settings['eael_business_reviews_style_preset_slider'] ) && 'slider' === $business_reviews['layout'] ? $settings['eael_business_reviews_style_preset_slider'] : 'preset-1';
		$business_reviews['preset']        	= ! empty( $settings['eael_business_reviews_style_preset_grid'] ) && 'grid' === $business_reviews['layout'] ? $settings['eael_business_reviews_style_preset_grid'] : $business_reviews['preset'];
		
		return $business_reviews;
	}

	/**
     * API Call to Get Business Reviews
     */
	public function fetch_business_reviews_from_api(){
		$settings					= $this->get_settings();
		$response					= [];
		$error_message 				= '';

		$business_reviews			= $this->get_business_reviews_settings();
		$items      				= get_transient( $business_reviews['cache_key'] );

		if ( false === $items ) {
			switch( $business_reviews['source'] ){
				case 'google-reviews':
					$data = $this->fetch_google_reviews_from_api($business_reviews);
					break;
				default:
					$data = $this->fetch_google_reviews_from_api($business_reviews);
					break;
			}

			return $data;
		}

		$response = $items ? $items : $response;

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

	public function fetch_google_reviews_from_api( $business_reviews_settings ){
		$business_reviews 					= $business_reviews_settings;
		$business_reviews['reviews_sort']	= sanitize_text_field( 'most_relevant' );
		
		$url   	= "https://maps.googleapis.com/maps/api/place/details/json";
		$param 	= array();

		$args 	= array(
			'key' 	  => sanitize_text_field( $business_reviews['api_key'] ),
			'placeid' => sanitize_text_field( 'ChIJ0cpDbNvBVTcRGX9JNhhpC8I' ),
			'fields'  => sanitize_text_field( 'formatted_address,international_phone_number,name,rating,reviews,url,user_ratings_total,website,photos' ),
		);

		if( ! empty( $business_reviews['reviews_sort'] ) ){
			$args['reviews_sort'] = $business_reviews['reviews_sort'];
		}
		
		$param 		= array_merge( $param, $args );
		
		$headers	= array(
			'headers' => array(
				'Content-Type' => 'application/json',
			)
		);
		$options 	= array(
			'timeout' => 240
		);

		$options 	= array_merge( $headers, $options );

		if ( empty( $error_message ) ) {
			$response = wp_remote_get(
				esc_url_raw( add_query_arg( $param, $url ) ),
				$options
			);

			$body     = json_decode( wp_remote_retrieve_body( $response ) );
			$response = 'OK' === $body->status ? $body->result : false;				

			if ( ! empty( $response ) ) {
				set_transient( $business_reviews['cache_key'], $response, $business_reviews['expiration'] );
			} else {
				$error_message = $this->fetch_google_place_response_error_message($body->status);
			}
		}

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

	public function fetch_google_place_response_error_message( $status = 'OK' ){
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

	public function print_business_reviews( $business_reviews_items ){
		$settings 				= $this->get_settings();
		$business_reviews		= $this->get_business_reviews_settings();

		ob_start();
		
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
					'eael-business-reviews-' . esc_attr( $business_reviews['layout'] ),
					esc_attr( $business_reviews['preset'] ),
				],
			]
		);
		?>

		<div <?php echo $this->get_render_attribute_string('eael-business-reviews-wrapper') ?> >
			<div <?php echo $this->get_render_attribute_string('eael-business-reviews-items'); ?> >
				<?php 
				switch( $business_reviews['source'] ){
					case 'google-reviews':
						$this->print_business_reviews_google($business_reviews_items);
						break;
					default:
						$this->print_business_reviews_google($business_reviews_items);
						break;
				}
				?>
			</div>
		</div>

		<?php 
		echo ob_get_clean();
	}

	public function print_business_reviews_google( $business_reviews_items ){
		$settings 						= $this->get_settings();
		$business_reviews				= $this->get_business_reviews_settings();

		$google_reviews_data  			= [];
		$business_review_obj  			= isset( $business_reviews_items['items'] ) ? $business_reviews_items['items'] : false;
		$error_message 		  			= ! empty( $business_reviews_items['error_message'] ) ? $business_reviews_items['error_message'] : "";
		
		if ( is_object( $business_review_obj ) && ! is_null( $business_review_obj ) ) {
			$google_reviews_data['formatted_address'] 			= ! empty( $business_review_obj->formatted_address ) ? $business_review_obj->formatted_address : '';
			$google_reviews_data['international_phone_number'] 	= ! empty( $business_review_obj->international_phone_number ) ? $business_review_obj->international_phone_number : '';
			$google_reviews_data['name'] 						= ! empty( $business_review_obj->name ) ? $business_review_obj->name : '';
			$google_reviews_data['photos'] 						= ! empty( $business_review_obj->photos ) ? $business_review_obj->photos : [];
			$google_reviews_data['rating'] 						= ! empty( $business_review_obj->rating ) ? $business_review_obj->rating : '';
			$google_reviews_data['reviews'] 					= ! empty( $business_review_obj->reviews ) ? $business_review_obj->reviews : [];
			$google_reviews_data['url'] 						= ! empty( $business_review_obj->url ) ? $business_review_obj->url : '#';
			$google_reviews_data['user_ratings_total'] 			= ! empty( $business_review_obj->user_ratings_total ) ? $business_review_obj->user_ratings_total : 0;
			$google_reviews_data['website'] 					= ! empty( $business_review_obj->website ) ? $business_review_obj->website : '#';
			
			switch( $business_reviews['layout'] ){
				case 'slider':
					$this->print_google_reviews_slider($google_reviews_data);
					break;
				case 'grid':
					$this->print_google_reviews_grid($google_reviews_data);
					break;
				default:
					$this->print_google_reviews_slider($google_reviews_data);
					break;
			}
		} else {
			printf( '<div class="eael-business-reviews-error-message">%s</div>', esc_html( $error_message ) );
		}
	}

	public function print_google_reviews_slider( $google_reviews_data ){
		$business_reviews				= $this->get_business_reviews_settings();

		if( ! empty( $google_reviews_data['reviews'] ) && count( $google_reviews_data['reviews'] ) ){
			$single_review_data = [];
			?>
			<div class="eael-google-reviews-wrapper">
				<div class="eael-google-reviews-items eael-google-reviews-slider">
					<div class="eael-google-reviews-arrows eael-google-reviews-arrows-outside">
						<?php $this->render_arrows(); ?>
					</div>

					<div class="eael-google-reviews-dots eael-google-reviews-dots-outside">
					
					</div>

					<div class="eael-google-reviews-content">
						<div class="eael-google-reviews-slider-header">

						</div>
						<div class="eael-google-reviews-slider-body">
							<?php
							foreach( $google_reviews_data['reviews'] as $single_review ){
								$single_review_data['review_author_name'] = ! empty( $single_review->author_name ) ? $single_review->author_name : '';
								$single_review_data['review_author_url'] = ! empty( $single_review->author_url ) ? $single_review->author_url : '';
								$single_review_data['review_profile_photo_url'] = ! empty( $single_review->profile_photo_url ) ? $single_review->profile_photo_url : '';
								$single_review_data['review_rating'] = ! empty( $single_review->rating ) ? $single_review->rating : '';
								$single_review_data['review_relative_time_description'] = ! empty( $single_review->relative_time_description ) ? $single_review->relative_time_description : '';
								$single_review_data['review_text'] = ! empty( $single_review->text ) ? $single_review->text : '';
							}
							?>
						</div>

						<div class="eael-google-reivews-slider-footer">
							<div class="eael-google-reviews-arrows eael-google-reviews-arrows-inside">
							
							</div>

							<div class="eael-google-reviews-dots eael-google-reviews-dots-inside">
								<?php $this->render_dots(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		if ( ! empty( $google_reviews_data['reviews'] ) && count( $google_reviews_data['reviews'] ) ){
			// $this->add_render_attribute('business-reviews-wrap', 'class', 'swiper-container-wrap');
			// $this->add_render_attribute('business-reviews-wrap', 'class', 'eael-arrow-box');
			// $this->add_render_attribute('business-reviews-wrap', 'class', 'swiper-container-wrap-dots-outside');

			// $this->add_render_attribute('business-reviews-wrap', [
			// 	'class' => ['eael-business-reviews', 'default-style'],
			// 	'id'    => 'eael-business-reviews-' . esc_attr($this->get_id()),
			// ]);

			// $this->add_render_attribute('business-reviews', [
			// 	'class' => [
			// 		'swiper-container',
			// 		'eael-business-reviews-main',
			// 		'swiper-container-' . esc_attr($this->get_id())
			// 	],
			// 	'data-pagination'   => '.swiper-pagination-' . esc_attr($this->get_id()),
			// 	'data-arrow-next'   => '.swiper-button-next-' . esc_attr($this->get_id()),
			// 	'data-arrow-prev'   => '.swiper-button-prev-' . esc_attr($this->get_id())
			// ]);

			// $this->add_render_attribute('business-reviews', 'data-items', 1);
			// $this->add_render_attribute('business-reviews', 'data-items-tablet', 1);
			// $this->add_render_attribute('business-reviews', 'data-items-mobile', 1);
			// $this->add_render_attribute('business-reviews', 'data-margin', 10);
			// $this->add_render_attribute('business-reviews', 'data-margin-tablet', 10);
			// $this->add_render_attribute('business-reviews', 'data-margin-mobile', 10);
			// $this->add_render_attribute('business-reviews', 'data-effect', 'slide');
			// $this->add_render_attribute('business-reviews', 'data-speed', 1000);
			// $this->add_render_attribute('business-reviews', 'data-loop', 1);
			// $this->add_render_attribute('business-reviews', 'data-grab-cursor', 1);
			// $this->add_render_attribute('business-reviews', 'data-arrows', 1);
			// $this->add_render_attribute('business-reviews', 'data-dots', 1);
			// $this->add_render_attribute('business-reviews', 'data-autoplay_speed', 2000);
			// $this->add_render_attribute('business-reviews', 'data-pause-on-hover', 'true');
			?>

			<div <?php echo $this->get_render_attribute_string('business-reviews-wrap'); ?>>
				<?php
				// $this->render_arrows();
				?>
				<div <?php echo $this->get_render_attribute_string('business-reviews'); ?>>

					<div class="swiper-wrapper">
						<?php
						$i = 0;
						
						foreach ( $google_reviews_data['reviews'] as $google_review) :
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
											<div class="eael-google-review-reviewer">
												<div class="eael-google-review-reviewer-photo">
													<img src="<?php echo esc_url_raw($google_review->profile_photo_url); ?>" alt="">
												</div>

												<div class="eael-google-review-reviewer-name">
													<a href="<?php echo ! empty ( $google_review->author_url ) ? esc_url_raw($google_review->author_url) : '#'; ?>" target="_blank"><?php echo esc_html( $google_review->author_name ); ?></a>
												</div>
												
												<div class="eael-google-review-time">
													<?php echo esc_html( $google_review->relative_time_description ); ?>
												</div>

												<div class="eael-google-review-text">
													<?php echo esc_html( $google_review->text ); ?>
												</div>
												
												<div class="eael-google-review-rating">
													<?php 
														//echo esc_html( $google_review->rating ); 
														$this->print_business_reviews_ratings($google_review->rating);
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php $i++;
						endforeach; ?>
					</div>
					<?php
					//$this->render_dots();
					?>
				</div>
			</div>

			<?php 
		}
	}

	public function print_google_reviews_grid( $google_reviews_data ){
		$business_reviews				= $this->get_business_reviews_settings();
	
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

	public function print_business_reviews_ratings($rating){
		// $rating = intval($rating);

		if( empty( $rating ) || intval( $rating ) > 5 ){
			return false;
		}

		// $rating = is_int($rating) ? $rating : floor($rating) + 0.5;
		$rating_svg = '<svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M7.37499 10.6517L3.26074 12.9547L4.17949 8.33008L0.717407 5.12875L5.39982 4.57341L7.37499 0.291748L9.35016 4.57341L14.0326 5.12875L10.5705 8.33008L11.4892 12.9547L7.37499 10.6517Z" fill="#F4BB4C"/>
		</svg>';

		$rating_svg_half = '<svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clip-path="url(#clip0_51_21)">
		<path d="M7.88891 9.31475L10.3663 10.7013L9.81274 7.91708L11.897 5.98916L9.07774 5.65491L7.88891 3.07716V9.31475ZM7.88891 10.6517L3.77466 12.9547L4.69341 8.33008L1.23132 5.12875L5.91374 4.57341L7.88891 0.291748L9.86407 4.57341L14.5465 5.12875L11.0844 8.33008L12.0032 12.9547L7.88891 10.6517Z" fill="#F4BB4C"/>
		</g>
		<defs>
		<clipPath id="clip0_51_21">
		<rect width="14" height="14" fill="white" transform="translate(0.888916)"/>
		</clipPath>
		</defs>
		</svg>
		';
		
		for( $i = 1; $i <= floor( $rating ); $i++){
			printf("%s", $rating_svg);
		}

		if( ! is_int( $rating ) ){
			printf("%s", $rating_svg_half);
		}

		return true;
	}

	protected function render() {
		$business_reviews_items = $this->fetch_business_reviews_from_api(); 
		$this->print_business_reviews( $business_reviews_items );
	}
}
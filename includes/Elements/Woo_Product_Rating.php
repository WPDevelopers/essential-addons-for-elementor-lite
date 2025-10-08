<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
   exit;
}

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Rating extends Widget_Base {
   public function get_name() {
		return 'eael-woo-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Rating', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-rating';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [ 
			'woocommerce',
			'rating',
			'review',
			'comments',
			'product',
			'ea',
         'essential addons',
			'ea product',
			'ea rating',
			'ea review',
			'woo',
			'woo product',
			'woo rating',
			'woo review',
			'Product Rating', 
			'EA Woo Product Rating',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-rating';
	}

	protected function register_controls() {
		$this->eael_wc_notice_controls();
		if ( !function_exists( 'WC' ) ) {
			return;
		}

		//
		$this->eael_product_rating_content();

		// Style Tab Start
		$this->start_controls_section(
			'eael_section_title_style',
			[
				'label' => esc_html__( 'Rating', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

      $this->add_responsive_control(
			'eael_star_align',
			[
				'label'   => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
               'prefix_class' => 'eael-product-rating--align-',
			]
		);

		$this->add_control(
			'eael_rating_color',
			[
				'label'     => esc_html__( 'Rating Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap .eael-product-rating.unfilled svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_rating_count_color',
			[
				'label'     => esc_html__( 'Rating Given Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap .eael-product-rating.filled svg path' => 'fill: {{VALUE}};',
				],
			]
		);

      $this->add_control(
			'eael_empty_star_color',
			[
				'label'     => esc_html__( 'Empty Star Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap .eael-product-rating svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'show_empty_review' => 'yes',
				]
			]
		);

      $this->add_control(
			'eael_star_size',
			[
				'label'      => esc_html__( 'Star Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
                  'rem' => [
						'max' => 50,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap .eael-product-rating svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_star_gap',
			[
				'label'      => esc_html__( 'Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
                  'rem' => [
						'max' => 50,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-single-product-rating .star-rating' => 'letter-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_rating_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_rating_hr1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

      $this->add_control(
			'eael_star_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-rating .woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

      $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_star_text_typography',
				'selector' => '{{WRAPPER}} .eael-single-product-rating .woocommerce-review-link',
			]
		);

      $this->add_control(
			'eael_star_text_spaceing',
			[
				'label'      => esc_html__( 'Space Between', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
                  'rem' => [
						'max' => 50,
					],
					'em' => [
						'max' => 50,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-rating .eael-product-rating-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// Style Tab End

	}

	/**
	 * WC Notice
	 *
	 * @return void
	 */
	protected function eael_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section( 'eael_global_warning', [
				'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
			] );
			$this->add_control( 'eael_global_warning_text', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
			] );
			$this->end_controls_section();

			return;
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function eael_product_rating_content() {
		$this->start_controls_section(
			'rating_content_section',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_review_count',
			[
				'label'        => esc_html__( 'Show Review Count', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'rating_style',
			[
				'label'   => esc_html__( 'Rating Style', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1' => esc_html__( 'Style 1', 'essential-addons-for-elementor-lite' ),
					'style_2' => esc_html__( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'style_3' => esc_html__( 'Style 3', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'rating_caption',
			[
				'label'       => esc_html__( 'Rating Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Customer Rating', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
				'condition' => [
					'show_review_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'before_rating_caption',
			[
				'label'       => esc_html__( 'Before Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '( ', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
				'condition' => [
					'show_review_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'after_rating_caption',
			[
				'label'       => esc_html__( 'After Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ' )', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
				'condition' => [
					'show_review_count' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_rating_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'show_empty_review',
			[
				'label'        => esc_html__( 'Show Empty Review', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'empty_rating_caption',
			[
				'label'       => esc_html__( 'Empty Rating Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'No Customer Rating', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
				'condition' => [
					'show_empty_review' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();
	}

	public function eael_star_solid( $filled ) {
		?>
		<span class="eael-product-rating <?php echo $filled ? 'filled' : 'unfilled'; ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
			</svg>
		</span>
		<?php
	}

	public function eael_star_classic( $filled ) {
		?>
		<span class="eael-product-rating <?php echo $filled ? 'filled' : 'unfilled'; ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/>
			</svg>
		</span>
		<?php
	}

	public function eael_star_half_stroke( $filled ) {
      ?>
		<span class="eael-product-rating <?php echo $filled ? 'filled' : 'unfilled'; ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path d="M309.5 13.5C305.5 5.2 297.1 0 287.9 0s-17.6 5.2-21.6 13.5L197.7 154.8 44.5 177.5c-9 1.3-16.5 7.6-19.3 16.3s-.5 18.1 5.9 24.5L142.2 328.4 116 483.9c-1.5 9 2.2 18.1 9.7 23.5s17.3 6 25.3 1.7l137-73.2 137 73.2c8.1 4.3 17.9 3.7 25.3-1.7s11.2-14.5 9.7-23.5L433.6 328.4 544.8 218.2c6.5-6.4 8.7-15.9 5.9-24.5s-10.3-14.9-19.3-16.3L378.1 154.8 309.5 13.5zM288 384.7l0-305.6 52.5 108.1c3.5 7.1 10.2 12.1 18.1 13.3l118.3 17.5L391 303c-5.5 5.5-8.1 13.3-6.8 21l20.2 119.6L299.2 387.5c-3.5-1.9-7.4-2.8-11.2-2.8z"/>
			</svg>
		</span>
      <?php
   }
	
	public function eael_rating_style( $settings, $average, $rating_count ) {
		$style_methods = [
			'style_1' => 'eael_star_solid',
			'style_2' => 'eael_star_classic',
			'style_3' => 'eael_star_half_stroke',
		];
		if( isset( $style_methods[ $settings['rating_style'] ] ) ) {
			$method = $style_methods[ $settings['rating_style'] ];
			for ( $i=1; $i <=5 ; $i++ ) { 
				$filled = ( $i <= ceil( $average ) ) ? true : false;
				$this->$method( $filled );
			}
		}
	}

	protected function render() {
		if ( !function_exists( 'WC' ) ) {
			return;
		}
		
      global $product;
		$settings     = $this->get_settings_for_display();

		if ( Plugin::$instance->editor->is_edit_mode() || get_post_type( get_the_ID() ) === 'templately_library' ) {
			?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<div class="eael-product-rating-wrap">
						<?php $this->eael_rating_style( $settings, $average = 3, $rating_count = 1 ); ?>
					</div>
					<?php if ( 'yes' === $settings['show_review_count'] ) { ?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
							<span class="before-rating">
								<?php echo wp_kses( $settings['before_rating_caption'], Helper::eael_allowed_tags() ); ?>
							</span>
							<span class="count">
								<?php esc_html_e( '1', 'essential-addons-for-elementor-lite' ); ?>
							</span> 
							<?php echo wp_kses( $settings['rating_caption'], Helper::eael_allowed_tags() ); ?>
							<span class="after-rating">
								<?php echo wp_kses( $settings['after_rating_caption'], Helper::eael_allowed_tags() ); ?>
							</span>
						</a>
					<?php } ?>
				</div>
			</div>
			<?php
		} else {
			if ( ! $product ) {
				return;
			}
			if ( ! wc_review_ratings_enabled() ) {
				return;
			}
			
			$product      = Helper::get_product();
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();

			$average = ( $rating_count > 0 ) ? $average : 0;
			if ( $rating_count > 0  || 'yes' === $settings['show_empty_review'] ) {
				$review_caption = ( $rating_count > 0 ) ? $settings['rating_caption'] : $settings['empty_rating_caption'];
				$caption_suffix = ( $rating_count > 1 ) ? esc_html__( 's', 'essential-addons-for-elementor-lite' ) : '';
				$review_caption .= $caption_suffix;
			?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<div class="eael-product-rating-wrap">
						<?php $this->eael_rating_style( $settings, $average, $rating_count ); ?>
					</div>
					<?php if ( comments_open() && 'yes' === $settings['show_review_count'] ) { ?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
								<span class="before-rating"><?php echo wp_kses( $settings['before_rating_caption'], Helper::eael_allowed_tags() ); ?></span>
								<span class="count">
									<?php 
										if ( $review_count > 0 ) {
											?>
											<span class="count_number">
												<?php echo esc_html( $review_count ); ?>
											</span>
											<?php
										}
									?>
									<span class="count_text">
										<?php echo wp_kses( $review_caption, Helper::eael_allowed_tags() ); ?>
									</span>
								</span>
								<span class="after-rating"><?php echo wp_kses( $settings['after_rating_caption'], Helper::eael_allowed_tags() ); ?></span>
						</a>
					<?php } elseif ( $rating_count === 0 && 'yes' === $settings['show_empty_review'] ) {
						?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
							<?php echo wp_kses( $review_caption, Helper::eael_allowed_tags() ); ?>
						</a>
						<?php
					} ?>
				</div>
			</div>
			<?php
			}
		}
	}
}
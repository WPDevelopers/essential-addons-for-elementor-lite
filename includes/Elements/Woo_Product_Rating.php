<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

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
		return [ 'woocommerce', 'shop', 'store', 'rating', 'review', 'product' ];
	}

	protected function register_controls() {

		//
		$this->eael_product_rating_content();

		// Style Tab Start
		$this->start_controls_section(
			'eael_section_title_style',
			[
				'label' => esc_html__( 'Rating', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'eael_star_align',
			[
				'label'   => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
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
			'eael_star_color',
			[
				'label'     => esc_html__( 'Star Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'eael_empty_star_color',
			[
				'label'     => esc_html__( 'Empty Star Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating:before' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'eael_star_size',
			[
				'label'      => esc_html__( 'Star Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
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
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_star_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_star_text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-rating .woocommerce-review-link',
			]
		);

        $this->add_control(
			'eael_star_text_spaceing',
			[
				'label'      => esc_html__( 'Text Spaceing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
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
					'.woocommerce {{WRAPPER}} .eael-single-product-rating .star-rating' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// Style Tab End

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
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_review_count',
			[
				'label'        => esc_html__( 'Show Review Count', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'rating_caption',
			[
				'label'       => esc_html__( 'Rating Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Customer Review', 'essential-addons-for-elementor-lite' ),
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
				'type'        => \Elementor\Controls_Manager::TEXT,
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
				'type'        => \Elementor\Controls_Manager::TEXT,
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
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'empty_rating_caption',
			[
				'label'       => esc_html__( 'Empty Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Empty Caption', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
      global $product;
		$settings = $this->get_settings_for_display();
      $product = Helper::get_product();
		if ( ! wc_review_ratings_enabled() ) {
			return;
		}
		
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

      if ( ! $product ) {
         return;
      }

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<div class="star-rating" role="img" aria-label="Rated 3.00 out of 5">
						<span style="width:60%"></span>
					</div>
					<?php if ( 'yes' === $settings['show_review_count'] ) { ?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
							<span class="before-rating">
								<?php echo Helper::eael_wp_kses( $settings['before_rating_caption'] ); ?>
							</span>
							<span class="count">
								<?php esc_html_e( '1', 'essential-addons-for-elementor-lite' ); ?>
							</span> 
							<?php echo Helper::eael_wp_kses( $settings['rating_caption'] ); ?>
							<span class="after-rating">
								<?php echo Helper::eael_wp_kses( $settings['after_rating_caption'] ); ?>
							</span>
						</a>
					<?php } ?>
				</div>
			</div>
			<?php
		} else {
			?>
			<!-- <div class="eael-single-product-rating"> -->
				<?php //wc_get_template( 'single-product/rating.php' ); ?>
			<!-- </div> -->
			<?php

			if ( $rating_count > 0 ) { ?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<?php echo wc_get_rating_html( $average, $rating_count ); ?>
					<?php if ( comments_open() && 'yes' === $settings['show_review_count'] ) { ?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
								<span class="before-rating">
									<?php echo Helper::eael_wp_kses( $settings['before_rating_caption'] ); ?>
								</span>
									<?php //esc_html( $review_count ); 
									// var_dump($review_count);
									?>
								<span class="count"><?php echo Helper::eael_wp_kses( $settings['rating_caption'] ); ?></span>
								<span class="after-rating">
									<?php echo Helper::eael_wp_kses( $settings['after_rating_caption'] ); ?>
								</span>
						</a>
					<?php } ?>
				</div>
			</div>
			<?php
			}
		}
	}
}
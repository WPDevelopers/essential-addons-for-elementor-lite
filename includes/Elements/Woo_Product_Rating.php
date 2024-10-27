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
			'rating_style',
			[
				'label'   => esc_html__( 'Rating Style', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
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
			'show_empty_review',
			[
				'label'        => esc_html__( 'Show Empty Review', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
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
				'condition' => [
					'show_empty_review' => 'yes',
				],
			]
		);
		
		$this->end_controls_section();
	}

	public function eael_star_classic() {
		?>
		<span class="eael-product-rating filled">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
				<path d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.7 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/>
			</svg>
		</span>
		<?php
	}
	
	public function eael_rating_style( $settings, $average, $rating_count ) {
		if( 'style_2' === $settings['rating_style'] ) {
			?>
			<div class="rating-style-2">
				<?php 
					for ( $i=1; $i <=5 ; $i++ ) { 
						$this->eael_star_classic();
					}
				?>
			</div>
			<?php
		} else {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				?>
				<div class="rating-style-1">
					<div class="star-rating" role="img" aria-label="Rated 3.00 out of 5">
						<span style="width:60%"></span>
					</div>
				</div>
				<?php
			} else {
				echo wc_get_rating_html( $average, $rating_count ); 
			}
		}
	}

	protected function render() {
      global $product;
		if ( ! $product ) {
         return;
      }
		if ( ! wc_review_ratings_enabled() ) {
			return;
		}
		
		$product = Helper::get_product();
		$settings = $this->get_settings_for_display();
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<?php $this->eael_rating_style( $settings, $average, $rating_count ); ?>
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
			if ( $rating_count > 0 ) { ?>
			<div class="eael-single-product-rating">
				<div class="woocommerce-product-rating">
					<?php $this->eael_rating_style( $settings, $average, $rating_count ); ?>
					<?php if ( comments_open() && 'yes' === $settings['show_review_count'] ) { ?>
						<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
								<span class="before-rating">
									<?php echo Helper::eael_wp_kses( $settings['before_rating_caption'] ); ?>
								</span>
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
		?>
		<?php
	}
}
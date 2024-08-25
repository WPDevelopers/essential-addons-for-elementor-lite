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
		return 'eicon-elementor-circle';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'rating', 'review', 'product' ];
	}

	protected function register_controls() {

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
                    'justify' => [
						'title' => esc_html__( 'Justified', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
                'prefix_class' => 'elementor-product-rating--align-',
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

	protected function render() {
        global $product;

        $product = Helper::get_product();

        if ( ! $product ) {
            return;
        }
        ?>
        <div class="eael-single-product-rating">
            <?php wc_get_template( 'single-product/rating.php' ); ?>
        </div>
        <?php
	}
}
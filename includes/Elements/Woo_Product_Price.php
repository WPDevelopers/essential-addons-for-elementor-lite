<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;

class Woo_Product_Price extends Widget_Base {
    public function get_name() {
		return 'eael-woo-product-price';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Price', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-meetup';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'sale', 'product' ];
	}

	protected function register_controls() {

		// Style Tab Start
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hello-world' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		// Style Tab End

	}

	protected function render() {
        global $product;
		$settings = $this->get_settings_for_display();
        if ( ! $product ) {
            return;
        }

        wc_get_template( '/single-product/price.php' );
	}
}
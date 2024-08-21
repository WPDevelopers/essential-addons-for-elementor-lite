<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;

class Woo_Product_Images extends Widget_Base {
    public function get_name() {
		return 'eael-woo-product-images';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Images', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-elementor-circle';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'images', 'product' ];
	}

	protected function register_controls() {

		// Style Tab Start
		$this->start_controls_section(
			'eael_images_section_title_style',
			[
				'label' => esc_html__( 'Images', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_images_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		// Style Tab End

	}

	protected function render() {
        global $product;

        if ( ! $product ) {
            return;
        }
        ?>
        <div class="eael-single-product-images">
            <?php wc_get_template( 'single-product/product-image.php' ); ?>
        </div>
        <?php
	}
}
<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;

class Woo_Product_Add_To_Cart extends Widget_Base {
    public function get_name() {
		return 'eael-woo-product-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Add To Cart', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-elementor-circle';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'cart', 'product' ];
	}

	protected function register_controls() {

		// Style Tab Start
		$this->start_controls_section(
			'eael_add_to_cart_title_style',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        //Start Layout
        $this->add_control(
			'eael_add_to_cart_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''        => esc_html__( 'Inline', 'essential-addons-for-elementor-lite' ),
					'stacked' => esc_html__( 'Stacked', 'essential-addons-for-elementor-lite' ),
					'auto'    => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
				],
			]
		);
        //Start Layout

		$this->end_controls_section();
		// Style Tab End

	}

	protected function render() {
        global $product;

        if ( ! $product ) {
            return;
        }

        $settings = $this->get_settings_for_display();

        ?>
        <div class="eael-single-product-add-to-cart">
            <div class="elementor-add-to-cart elementor-product-<?php echo esc_attr( $product->get_type() ); ?>">
                <?php 
                    woocommerce_template_single_add_to_cart();
                ?>
            </div>
        </div>
        <?php
	}
}
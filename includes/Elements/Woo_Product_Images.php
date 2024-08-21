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

        //Start image
        $this->add_control(
			'eael_image_sale_flash',
			[
				'label'        => esc_html__( 'Sale Flash', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'render_type'  => 'template',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => '',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_image_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport',
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'eael_image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
                    '.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

        $this->add_control(
			'eael_image_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
        //End image

        //Start thumbnail
        $this->add_control(
			'eael_thumb_heading',
			[
				'label'     => esc_html__( 'Thumbnails', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_thumb_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs img',
			]
		);

        $this->add_responsive_control(
			'eael_thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

        $this->add_control(
			'eael_thumb_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
        //End thumbnail

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
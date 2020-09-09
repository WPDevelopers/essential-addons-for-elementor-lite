<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Woo_Product_Compare
 * @package namespace Essential_Addons_Elementor\Pro\Elements;
 */
class Woo_Product_Compare extends Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'woo-product-compare';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Woo Product Compare', 'essential-addons-elementor' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}


	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'woocommerce product compare',
			'woocommerce product comparison',
			'product compare',
			'product comparison',
			'products compare',
			'products comparison',
			'wc',
			'woocommerce',
			'products',
			'compare',
			'comparison',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-product-comparison/';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get an array of field types.
	 * @return array
	 */
	protected function get_field_types() {
		return apply_filters( 'eael/wcpc/default-fields', [
			'image'    => __( 'Image', 'essential-addons-elementor' ),
			'title'        => __( 'Title', 'essential-addons-elementor' ),
			'price'     => __( 'Price', 'essential-addons-elementor' ),
			'add_to_cart' => __( 'Add to cart', 'essential-addons-elementor' ),
			'description'   => __( 'Description', 'essential-addons-elementor' ),
			'sku'    => __( 'SKU', 'essential-addons-elementor' ),
			'availability'      => __( 'Availability', 'essential-addons-elementor' ),
			'weight'      => __( 'weight', 'essential-addons-elementor' ),
			'dimension'      => __( 'Dimension', 'essential-addons-elementor' ),
			'color'      => __( 'Color', 'essential-addons-elementor' ),
			'size'      => __( 'Size', 'essential-addons-elementor' ),
		] );
	}

	/**
	 * Get default fields value for the repeater's default value
	 */
	protected function get_default_rf_fields() {
		return apply_filters( 'eael/wcpc/default-rf-fields', [
			[
				'field_type'  => 'image',
				'field_label' => __( 'Image', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'title',
				'field_label' => __( 'Title', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'description',
				'field_label' => __( 'Description', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'add_to_cart',
				'field_label' => __( 'Add to cart', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'sku',
				'field_label' => __( 'SKU', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'availability',
				'field_label' => __( 'Availability', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'weight',
				'field_label' => __( 'Weight', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'dimension',
				'field_label' => __( 'Dimension', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'color',
				'field_label' => __( 'Color', 'essential-addons-elementor' ),
			],
			[
				'field_type'  => 'size',
				'field_label' => __( 'Size', 'essential-addons-elementor' ),
			],
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {
		/*----Content Tab----*/
		do_action( 'eael/wcpc/before-content-controls', $this );
        $this->init_content_general_controls();
		do_action( 'eael/wcpc/after-content-controls', $this );

		/*----Style Tab----*/
		do_action( 'eael/wcpc/before-style-controls', $this );

		do_action( 'eael/wcpc/after-style-controls', $this );

	}

	public function init_content_general_controls() {
		$this->start_controls_section( 'section_content_general', [
			'label'      => __( 'General', 'essential-addons-elementor' ),
		] );
		$this->add_control( "table_title", [
			'label'       => __( 'Table Title', 'essential-addons-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default' => __( 'Compare Products', 'essential-addons-elementor' ),
			'placeholder' => __( 'Compare Products', 'essential-addons-elementor' ),
		] );
		$repeater = new Repeater();
		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', 'essential-addons-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_field_types(),
			'default' => 'title',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', 'essential-addons-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'fields', [
			'label'       => __( 'Fields to show', 'essential-addons-elementor' ),
			'description'       => __( 'Select the fields to show in the comparison table', 'essential-addons-elementor' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => apply_filters( 'eael/wcpc/rf-fields', $repeater->get_controls() ),
			'default'     => $this->get_default_rf_fields(),
			'title_field' => '{{{ field_label }}}',
		] );

		$this->add_control( 'repeat_price', [
			'label' => __( 'Repeat "Price" field', 'essential-addons-elementor' ),
			'description' => __( 'Repeat the "Price" field at the end of the table', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
            'default' => 'yes'
		] );
		$this->add_control( 'repeat_add_to_cart', [
			'label' => __( 'Repeat "Add to cart" field', 'essential-addons-elementor' ),
			'description' => __( 'Repeat the "Add to cart" field at the end of the table', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
		$this->end_controls_section();
	}


	protected function render() {
		?>
		<div class="eael-wcpc-wrapper">
            Woo Product Compare
		</div>
		<?php
	}

	/**
	 * It will apply value like Elementor's dimension control to a property and return it.
	 *
	 * @param string $css_property CSS property name
	 *
	 * @return string
	 */
	public function apply_dim( $css_property ) {
		return "{$css_property}: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};";
	}

}

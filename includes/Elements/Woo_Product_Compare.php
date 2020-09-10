<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Repeater;
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
		return esc_html__( 'Woo Product Compare', 'essential-addons-for-elementor-lite' );
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
		return [ 'essential-addons-for-elementor-lite' ];
	}

	/**
	 * Get an array of field types.
	 * @return array
	 */
	protected function get_field_types() {
		return apply_filters( 'eael/wcpc/default-fields', [
			'image'        => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'title'        => __( 'Title', 'essential-addons-for-elementor-lite' ),
			'price'        => __( 'Price', 'essential-addons-for-elementor-lite' ),
			'add_to_cart'  => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			'description'  => __( 'Description', 'essential-addons-for-elementor-lite' ),
			'sku'          => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			'availability' => __( 'Availability', 'essential-addons-for-elementor-lite' ),
			'weight'       => __( 'weight', 'essential-addons-for-elementor-lite' ),
			'dimension'    => __( 'Dimension', 'essential-addons-for-elementor-lite' ),
			'color'        => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'size'         => __( 'Size', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * Get default fields value for the repeater's default value
	 */
	protected function get_default_rf_fields() {
		return apply_filters( 'eael/wcpc/default-rf-fields', [
			[
				'field_type'  => 'image',
				'field_label' => __( 'Image', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'title',
				'field_label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'description',
				'field_label' => __( 'Description', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'add_to_cart',
				'field_label' => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'sku',
				'field_label' => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'availability',
				'field_label' => __( 'Availability', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'weight',
				'field_label' => __( 'Weight', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'dimension',
				'field_label' => __( 'Dimension', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'color',
				'field_label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'size',
				'field_label' => __( 'Size', 'essential-addons-for-elementor-lite' ),
			],
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {

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

		/*----Content Tab----*/
		do_action( 'eael/wcpc/before-content-controls', $this );
		$this->init_content_content_controls();
		do_action( 'eael/wcpc/after-content-controls', $this );

		/*----Style Tab----*/
		do_action( 'eael/wcpc/before-style-controls', $this );

		do_action( 'eael/wcpc/after-style-controls', $this );

	}

	public function init_content_content_controls() {
		$this->start_controls_section( 'section_content_content', [
			'label' => __( 'Content', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( "product_ids", [
			'label'       => __( 'Product IDs', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Enter Product IDs separated by a comma', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => __( 'Eg. 123, 456 etc.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->end_controls_section();
		$this->start_controls_section( 'section_content_table', [
			'label' => __( 'Table Settings', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "table_title", [
			'label'       => __( 'Table Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Compare Products', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Compare Products', 'essential-addons-for-elementor-lite' ),
		] );
		$repeater = new Repeater();
		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_field_types(),
			'default' => 'title',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'fields', [
			'label'       => __( 'Fields to show', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Select the fields to show in the comparison table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => apply_filters( 'eael/wcpc/rf-fields', $repeater->get_controls() ),
			'default'     => $this->get_default_rf_fields(),
			'title_field' => '{{{ field_label }}}',
		] );

		$this->add_control( 'repeat_price', [
			'label'       => __( 'Repeat "Price" field', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Repeat the "Price" field at the end of the table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
		] );
		$this->add_control( 'repeat_add_to_cart', [
			'label'       => __( 'Repeat "Add to cart" field', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Repeat the "Add to cart" field at the end of the table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SWITCHER,
		] );
		$this->end_controls_section();
	}


	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}
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

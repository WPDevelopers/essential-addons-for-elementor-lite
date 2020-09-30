<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Traits\Woo_Product_Comparable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Woo_Product_Compare
 * @package namespace Essential_Addons_Elementor\Pro\Elements;
 */
class Woo_Product_Compare extends Widget_Base {
	use Woo_Product_Comparable;

	protected $products_list = [];
	protected $remove_action = 'eael-wcpc-remove-product';

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'eael-woo-product-compare';
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
		$this->init_content_table_settings_controls();
		do_action( 'eael/wcpc/after-content-controls', $this );

		/*----Style Tab----*/
		do_action( 'eael/wcpc/before-style-controls', $this );
		$this->init_style_content_controls();
		$this->init_style_table_controls();
		do_action( 'eael/wcpc/after-style-controls', $this );

	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}
		$ds                     = $this->get_settings_for_display();
		$product_ids            = $this->get_settings_for_display( 'product_ids' );
		$product_ids            = ! empty( $product_ids ) ? array_filter( array_map( 'trim', explode( ',', $product_ids ) ), function ( $id ) {
			return ( ! empty( $id ) && is_numeric( $id ) );
		} ) : [];
		$products               = $this->get_products_list( $product_ids );
		$fields                 = $this->fields();
		$title                  = isset( $ds['table_title']) ? $ds['table_title']: '';
		$highlighted_product_id = !empty( $ds['highlighted_product_id']) ? $ds['highlighted_product_id']: null;
		$theme_wrap_class       = $theme = '';
		if ( ! empty( $ds['theme'] ) ) {
			$theme            = esc_attr( $ds['theme'] );
			$theme_wrap_class = " custom {$theme}";
		}
		$this->render_compare_table( compact( 'products', 'fields', 'title', 'highlighted_product_id', 'theme_wrap_class', 'theme' ) );
	}

}

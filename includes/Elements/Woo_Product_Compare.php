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
		return 'eaicon-product-compare';
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
		return 'https://essential-addons.com/elementor/docs/woo-product-compare/';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->init_content_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		/*----Content Tab----*/
		do_action( 'eael/wcpc/before-content-controls', $this );
		$this->init_content_product_compare_controls();
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
		$products               = $this->get_products_list( $product_ids );
		$fields                 = $this->fields();

		$this->render_compare_table( compact( 'products', 'fields', 'ds' ) );
	}

}

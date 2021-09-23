<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Repeater;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Cart extends Widget_Base {

	use \Essential_Addons_Elementor\Template\Woocommerce\Cart\Woo_Cart_Helper;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists( 'woocommerce' ) ) {

			if ( is_null( WC()->cart ) ) {
				include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
				include_once WC_ABSPATH . 'includes/class-wc-cart.php';
				wc_load_cart();
			}

			// Added 'eael-woo-cart' class to body
			add_filter( 'body_class', [ $this, 'add_cart_body_class' ] );

			// Remove default 'woocommerce_cart_totals' callback from 'woocommerce_cart_collaterals'
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

			// Hooked our cart totals section in woocommerce_cart_collaterals
			add_action( 'woocommerce_cart_collaterals', [ $this, 'eael_woo_cart_totals' ], 10 );

			// Remove default 'woocommerce_button_proceed_to_checkout' callback from 'woocommerce_proceed_to_checkout'
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

			// Hooked our proceed to checkout button in 'woocommerce_proceed_to_checkout'
			add_action( 'woocommerce_proceed_to_checkout', [ $this, 'eael_cart_button_proceed_to_checkout' ], 20 );
		}
	}

	public function get_name() {
		return 'eael-woo-cart';
	}

	public function get_title() {
		return esc_html__( 'Woo Cart', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-woo-cart';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since  3.5.2
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [
			'cart',
			'woo cart',
			'ea cart',
			'ea woo cart',
			'woocommerce',
			'woocommerce cart',
			'ea',
			'essential addons',
			'essential addons cart',
			'essential addons woocommerce cart',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-cart/';
	}

	protected function _register_controls() {
		if ( ! class_exists( 'woocommerce' ) ) {
			$this->start_controls_section(
				'eael_global_warning',
				[
					'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
				]
			);

			$this->add_control(
				'eael_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.',
						'essential-addons-for-elementor-lite' ),
					'content_classes' => 'eael-warning',
				]
			);

			$this->end_controls_section();

			return;
		}

		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'ea_section_woo_cart_general_settings',
			[
				'label' => esc_html__( 'General Settings', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_cart_layout',
			[
				'label'       => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'label_block' => false,
				'options'     => apply_filters( 'eael/woo-cart/layout', [
					'default' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
					'style-2' => esc_html__( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'style-3' => esc_html__( 'Style 3 (Pro)', 'essential-addons-for-elementor-lite' ),
					'style-4' => esc_html__( 'Style 4 (Pro)', 'essential-addons-for-elementor-lite' ),
					'style-5' => esc_html__( 'Style 5 (Pro)', 'essential-addons-for-elementor-lite' ),
				] ),
			]
		);

		if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
			$this->add_control(
				'eael_woo_cart_pro_enable_warning',
				[
					'label'     => sprintf( '<a target="_blank" href="https://wpdeveloper.net/upgrade/ea-pro">%s</a>',
						esc_html__( 'Only Available in Pro Version!', 'essential-addons-for-elementor-lite' ) ),
					'type'      => Controls_Manager::RAW_HTML,
					'condition' => [
						'ea_woo_cart_layout' => [ 'style-3', 'style-4', 'style-5' ],
					],
				]
			);
		}

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style General Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_cart_general_style',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ea_woo_cart_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-woo-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ea_section_woo_cart_table_builder',
			[
				'label' => esc_html__( 'Table Builder', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'column_type',
			[
				'label'   => __( 'Column Item', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'remove'    => __( 'Remove', 'essential-addons-for-elementor-lite' ),
					'thumbnail' => __( 'Product Image', 'essential-addons-for-elementor-lite' ),
					'name'      => __( 'Product Title', 'essential-addons-for-elementor-lite' ),
					'price'     => __( 'Price', 'essential-addons-for-elementor-lite' ),
					'quantity'  => __( 'Quantity', 'essential-addons-for-elementor-lite' ),
					'subtotal'  => __( 'Subtotal', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$repeater->add_control(
			'column_heading_title',
			[
				'label'       => esc_html__( 'Heading Title', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Product Title', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_remove_icon',
			[
				'label'                  => __( 'Remove Icon', 'elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => [
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				],
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available'     => true,
				'condition'              => [
					'column_type' => 'remove'
				]
			]
		);

		$this->add_control(
			'table_items',
			[
				'label'       => __( 'Table Items', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'column_type'          => 'remove',
						'column_heading_title' => esc_html__( '', 'essential-addons-for-elementor-lite' ),
					],
					[
						'column_type'          => 'thumbnail',
						'column_heading_title' => esc_html__( 'Product', 'essential-addons-for-elementor-lite' ),
					],
					[
						'column_type'          => 'name',
						'column_heading_title' => esc_html__( '', 'essential-addons-for-elementor-lite' ),
					],
					[
						'column_type'          => 'price',
						'column_heading_title' => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
					],
					[
						'column_type'          => 'quantity',
						'column_heading_title' => esc_html__( 'Quantity', 'essential-addons-for-elementor-lite' ),
					],
					[
						'column_type'          => 'subtotal',
						'column_heading_title' => esc_html__( 'Total', 'essential-addons-for-elementor-lite' ),
					],
				],
				'title_field' => '{{{ column_heading_title || column_type }}}',
			]
		);

		$this->end_controls_section();

	}

	public function add_cart_body_class( $classes ) {
		$classes[] = 'eael-woo-cart';

		return $classes;
	}

	protected function render() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$this->ea_woo_cart_add_actions( $settings );

		if ( in_array( $settings['ea_woo_cart_layout'], [ 'style-3', 'style-4', 'style-5' ] ) ) {
			if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
				return;
			}
		}

		$this->ea_cart_render();
	}

}

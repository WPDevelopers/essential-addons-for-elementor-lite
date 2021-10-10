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
		 * Table Builder
		 */
		$this->start_controls_section(
			'ea_section_woo_cart_table_builder',
			[
				'label'     => esc_html__( 'Table Builder', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'ea_woo_cart_layout' => 'default'
				]
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
				'label'                  => __( 'Remove Icon', 'essential-addons-for-elementor-lite' ),
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

		$repeater->add_responsive_control(
			'column_item_align',
			[
				'label'                => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => 'text-align: left; justify-content: flex-start;',
					'center' => 'text-align: center; justify-content: center;',
					'right'  => 'text-align: right; justify-content: flex-end;',
				],
				'selectors'            => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table {{CURRENT_ITEM}}'           => '{{VALUE}};',
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table {{CURRENT_ITEM}} .quantity' => '{{VALUE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'column_item_thumbnail_width',
			[
				'label'      => esc_html__( 'Thumbnail Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'column_type' => 'thumbnail'
				]
			]
		);

		$repeater->add_responsive_control(
			'column_item_thumbnail_border_radius',
			[
				'label'      => esc_html__( 'Thumbnail Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table {{CURRENT_ITEM}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'column_type' => 'thumbnail'
				],
			]
		);

		$repeater->add_responsive_control(
			'column_item_width',
			[
				'label'      => esc_html__( 'Column Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
				],
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

		/**
		 * Table Components
		 */
		$this->start_controls_section(
			'eael_woo_cart_table_components_section',
			[
				'label'     => esc_html__( 'Table Components', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'ea_woo_cart_layout' => 'style-2'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_thumbnail',
			[
				'label'        => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_thumbnail_title',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_table_components_thumbnail' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_thumbnail_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-thead .eael-woo-cart-tr .eael-woo-cart-tr-left > .product-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tbody .eael-woo-cart-tr .eael-woo-cart-tr-left > .product-thumbnail' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-tr-left > .product-name'                           => 'width: calc(100% - {{SIZE}}{{UNIT}} - 28px);',
				],
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_name',
			[
				'label'        => esc_html__( 'Name', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_table_components_thumbnail' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_name_alignment',
			[
				'label'                => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'  => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'  => 'text-align: left; flex-direction: row;',
					'right' => 'text-align: right; flex-direction: row-reverse; margin-left: -10px; margin-right: 10px;',
				],
				'selectors'            => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr-left' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_left_side_width',
			[
				'label'      => esc_html__( 'Thumbnail and Title area width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 45
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-tr-left'  => 'width: {{SIZE}}{{UNIT}};',
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-tr-right' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_price',
			[
				'label'        => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_price_title',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_table_components_price' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_price_alignment',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-price' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_price_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-td.product-price' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_qty',
			[
				'label'        => esc_html__( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_qty_title',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_table_components_qty' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_qty_alignment',
			[
				'label'                => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => 'text-align: left; justify-content: flex-start;',
					'center' => 'text-align: center; justify-content: center;',
					'right'  => 'text-align: right; justify-content: flex-end;',
				],
				'selectors'            => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-quantity'           => '{{VALUE}};',
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-quantity .quantity' => '{{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_qty_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-td.product-quantity' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_subtotal',
			[
				'label'        => esc_html__( 'Subtotal', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_subtotal_title',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Subtotal', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_table_components_subtotal' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_subtotal_alignment',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-subtotal' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_subtotal_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-td.product-subtotal' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_remove',
			[
				'label'        => esc_html__( 'Remove', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_table_components_remove_icon',
			[
				'label'                  => __( 'Remove Icon', 'essential-addons-for-elementor-lite' ),
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
					'eael_woo_cart_table_components_remove' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_remove_alignment',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-remove' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_woo_cart_table_components_remove_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default'    => [
					'unit' => '%',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper.eael-woo-style-2 form.eael-woo-cart-form .eael-woo-cart-table .eael-woo-cart-tr .eael-woo-cart-td.product-remove' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Cart Components
		 */
		$this->start_controls_section(
			'eael_woo_cart_components_section',
			[
				'label' => esc_html__( 'Cart Components', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_update_button',
			[
				'label'        => esc_html__( 'Cart Update Button', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_update_button_text',
			[
				'label'     => esc_html__( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Update cart', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_components_cart_update_button' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_coupon',
			[
				'label'        => esc_html__( 'Coupon Form', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_coupon_button_text',
			[
				'label'     => esc_html__( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Apply coupon', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_components_cart_coupon' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_coupon_placeholder',
			[
				'label'     => esc_html__( 'Placeholder', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Coupon code', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_components_cart_coupon' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_continue_shopping',
			[
				'label'        => esc_html__( 'Continue Shopping', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_components_continue_shopping_text',
			[
				'label'     => esc_html__( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Continue Shopping', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_components_continue_shopping' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_continue_shopping_icon',
			[
				'label'                  => __( 'Button Icon', 'essential-addons-for-elementor-lite' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => [
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available'     => true,
				'condition'              => [
					'eael_woo_cart_components_continue_shopping' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals',
			[
				'label'        => esc_html__( 'Cart Totals Section', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_subtotal',
			[
				'label'        => esc_html__( 'Sub Totals', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_coupon',
			[
				'label'        => esc_html__( 'Coupons', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_shipping',
			[
				'label'        => esc_html__( 'Shipping', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_fees',
			[
				'label'        => esc_html__( 'Fees', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_tax',
			[
				'label'        => esc_html__( 'Tax', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_total',
			[
				'label'        => esc_html__( 'Total', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_totals_checkout_button',
			[
				'label'        => esc_html__( 'Checkout Button', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_woo_cart_components_cart_totals' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_woo_cart_components_cart_checkout_button_text',
			[
				'label'     => esc_html__( 'Checkout Button Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Proceed to checkout', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_woo_cart_components_cart_totals'                 => 'yes',
					'eael_woo_cart_components_cart_totals_checkout_button' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style General Style
		 * -------------------------------------------
		 */
		$this->woo_cart_style_controllers( $this );
	}

	public function woo_cart_style_controllers( $obj ) {
		$obj->start_controls_section(
			'ea_section_woo_cart_general_style',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$obj->add_control(
			'ea_woo_cart_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-cart-wrapper,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table .product-quantity .quantity input[type=number]' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_cart_layout!' => 'style-2'
				]
			]
		);

		$obj->end_controls_section();

		$obj->start_controls_section(
			'ea_section_woo_cart_table_style',
			[
				'label' => esc_html__( 'Table', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$obj->add_control(
			'ea_section_woo_cart_table_style_thead_heading',
			[
				'label' => __( 'Table Head', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$obj->add_control(
			'eael_woo_cart_table_style_thead_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table thead th' => 'color: {{VALUE}};',
				],
			]
		);

		$obj->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_woo_cart_table_style_thead_typography',
				'selector' => '{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table thead th',
			]
		);

		$obj->add_control(
			'ea_section_woo_cart_table_style_tbody_heading',
			[
				'label' => __( 'Table Body', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$obj->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'ea_woo_cart_table_row_bg',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Table Row Background', 'essential-addons-for-elementor-lite' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table tbody tr::after',
				'condition'      => [
					'ea_woo_cart_layout' => 'default'
				]
			]
		);

		$obj->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'ea_woo_cart_table_row_box_shadow',
				'label'    => __( 'Table Row Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table tbody tr::after',
			]
		);

		$obj->add_responsive_control(
			'ea_woo_cart_table_row_border_radius',
			[
				'label'      => esc_html__( 'Table Row Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table tbody tr::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$obj->add_control(
			'eael_woo_cart_table_style_name_color',
			[
				'label'     => esc_html__( 'Primary Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-remove a:hover,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-name,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-name a,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-subtotal,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity input[type=number]' => 'color: {{VALUE}};',
				],
			]
		);

		$obj->add_control(
			'eael_woo_cart_table_style_name_secondary_color',
			[
				'label'     => esc_html__( 'Secondary Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-price,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity .eael-cart-qty-minus,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity .eael-cart-qty-plus,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-name dl,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-remove a' => 'color: {{VALUE}};',
				],
			]
		);

		$obj->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_woo_cart_table_style_name_typography',
				'selector' => '{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td:not(.product-thumbnail),
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td a,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity .eael-cart-qty-minus,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity .eael-cart-qty-plus,
					{{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table td.product-quantity .quantity input[type=number]',
			]
		);

		$obj->add_responsive_control(
			'eael_woo_cart_table_border_spacing',
			[
				'label'      => esc_html__( 'Row Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
				],
				'selectors'  => [
					'.eael-woo-cart {{WRAPPER}} .eael-woo-cart-wrapper form.eael-woo-cart-form .eael-woo-cart-table' => 'border-spacing: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$obj->end_controls_section();
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

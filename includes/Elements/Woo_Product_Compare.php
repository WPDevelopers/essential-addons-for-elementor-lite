<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use WC_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Woo_Product_Compare
 * @package namespace Essential_Addons_Elementor\Pro\Elements;
 */
class Woo_Product_Compare extends Widget_Base {
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
	 * Get an array of field types.
	 * @return array
	 */
	protected function get_field_types() {
		return apply_filters( 'eael/wcpc/default-fields', [
			'image'       => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'title'       => __( 'Title', 'essential-addons-for-elementor-lite' ),
			'price'       => __( 'Price', 'essential-addons-for-elementor-lite' ),
			'add-to-cart' => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Description', 'essential-addons-for-elementor-lite' ),
			'sku'         => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			'stock'       => __( 'Availability', 'essential-addons-for-elementor-lite' ),
			'weight'      => __( 'weight', 'essential-addons-for-elementor-lite' ),
			'dimension'   => __( 'Dimension', 'essential-addons-for-elementor-lite' ),
			'pa_color'    => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'pa_size'     => __( 'Size', 'essential-addons-for-elementor-lite' ),
		] );
	}

	protected function get_themes() {
		return apply_filters( 'eael/wcpc/default-themes', [
			''        => __( 'Theme Default', 'essential-addons-for-elementor-lite' ),
			'theme-1' => __( 'Theme 1', 'essential-addons-for-elementor-lite' ),
			'theme-2' => __( 'Theme 2', 'essential-addons-for-elementor-lite' ),
			'theme-3' => __( 'Theme 3', 'essential-addons-for-elementor-lite' ),
			'theme-4' => __( 'Theme 4', 'essential-addons-for-elementor-lite' ),
			'theme-5' => __( 'Theme 5', 'essential-addons-for-elementor-lite' ),
			'theme-6' => __( 'Theme 6', 'essential-addons-for-elementor-lite' ),
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
				'field_type'  => 'price',
				'field_label' => __( 'Price', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'description',
				'field_label' => __( 'Description', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'add-to-cart',
				'field_label' => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'sku',
				'field_label' => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'stock',
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
				'field_type'  => 'pa_color',
				'field_label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'pa_size',
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
		$this->init_content_table_settings_controls();
		do_action( 'eael/wcpc/after-content-controls', $this );

		/*----Style Tab----*/
		do_action( 'eael/wcpc/before-style-controls', $this );
		$this->init_style_content_controls();
		$this->init_style_table_controls();
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
			'label_block' => true,
			'placeholder' => __( 'Eg. 123, 456 etc.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'theme', [
			'label'   => __( 'Presets', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_themes(),
			'default' => '',
		] );
		$this->end_controls_section();
	}

	public function init_content_table_settings_controls() {

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

	public function init_style_content_controls() {
		$this->start_controls_section( 'section_style_general', [
			'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$container_class = '{{WRAPPER}} .eael-wcpc-wrapper';
		$this->add_responsive_control( "eael_container_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 20,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$container_class => 'width: {{SIZE}}{{UNIT}};',
			],

		] );

		$this->add_responsive_control( "eael_container_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'margin' ),
			],
		] );
		$this->add_responsive_control( "eael_container_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'padding' ),
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_container_border",
			'selector' => $container_class,
		] );
		$this->add_control( "eael_container_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "eael_container_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => $container_class,
		] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Container Box Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => 'eael_container_shadow',
			'selector' => $container_class,
			'exclude'  => [
				'box_shadow_position',
			],
		] );
		$this->end_controls_section();
	}

	public function init_style_table_controls() {
		$wrap           = "{{WRAPPER}} .eael-wcpc-wrapper";
		$tbl            = "{$wrap} table";
		$img_class      = "{$tbl} tr.image td";
		$h_col          = "{$tbl} tr th:not(.first-th)";
		$second_row     = "{$tbl} tr:nth-child(2) th, {$tbl} tr:nth-child(2) td";
		$second_row_th  = "{$tbl} tr:nth-child(2) th";
		$second_row_td  = "{$tbl} tr:nth-child(2) td";
		$btn            = "{$tbl} a.button";
		$col1_btn       = "{$tbl} td.col_0 a.button";
		$col2_btn       = "{$tbl} td.col_1 a.button";
		$col3_btn       = "{$tbl} td.col_2 a.button";
		$col1_btn_hover = "{$tbl} td.col_0 a.button:hover";
		$col2_btn_hover = "{$tbl} td.col_1 a.button:hover";
		$col3_btn_hover = "{$tbl} td.col_2 a.button:hover";
		$col1_img_bg    = "{$tbl} tr.image td.col_0";
		$col2_img_bg    = "{$tbl} tr.image td.col_1";
		$col3_img_bg    = "{$tbl} tr.image td.col_2";
		$this->start_controls_section( 'section_style_table', [
			'label' => __( 'Table Style', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'separate_col_style', [
			'label' => __( 'Style Content Column Separately', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
		$this->add_control( 'h_col_style_po_toggle', [
			'label'        => __( 'Header Column Style', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',

		] );
		$this->start_popover();
		$this->add_responsive_control( "h_col_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$h_col => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'h_col_style_po_toggle' => 'yes', ],
		] );
		$this->add_responsive_control( "h_col_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$h_col => $this->apply_dim( 'padding' ),
			],
			'condition'  => [ 'h_col_style_po_toggle' => 'yes', ],
		] );
		$this->add_control( 'h_col_clr_heading', [
			'label'     => __( 'Colors', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'h_col_style_po_toggle' => 'yes', ],
		] );
		$this->add_control( 'h_col_bg', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $h_col => 'background-color:{{VALUE}}' ],
			'separator' => 'before',
			'condition' => [ 'h_col_style_po_toggle' => 'yes', ],
		] );
		$this->add_control( 'h_col_color', [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $h_col => 'color:{{VALUE}}' ],
			'separator' => 'before',
			'condition' => [ 'h_col_style_po_toggle' => 'yes', ],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "h_col_border",
			'selector'  => $h_col,
			'condition' => [
				"h_col_style_po_toggle" => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => "h_col_typo",
			'label'     => __( 'Header Typography', 'essential-addons-for-elementor-lite' ),
			'selector'  => $h_col,
			'condition' => [ 'h_col_style_po_toggle' => 'yes', ],
		] );

		// Column 1 style
		$this->add_control( 'col1_style_po_toggle', [
			'label'        => __( 'Product Column 1', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'separate_col_style' => 'yes',
			],
		] );
		$this->start_popover();
		$this->add_control( 'col1_clr_heading', [
			'label'     => __( 'Colors', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'col1_style_po_toggle' => 'yes', ],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "col1_img_bg",
			'label'     => __( 'Image Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $col1_img_bg,
			'condition' => [
				'col1_style_po_toggle' => 'yes',
			],
			'exclude'   => [ 'image' ],
		] );
		$this->add_control( 'col1_btn_color', [
			'label'     => __( 'Button Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $col1_btn => 'color:{{VALUE}}' ],
			'condition' => [
				'col1_style_po_toggle' => 'yes',
			],
			'separator' => 'before',
		] );
		$this->add_control( 'col1_btn_bg', [
			'label'     => __( 'Button Background', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $col1_btn => 'background-color:{{VALUE}}' ],
			'condition' => [
				'col1_style_po_toggle' => 'yes',
			],
		] );

		$this->end_popover();

		// Column 2 style
		$this->add_control( 'col2_style_po_toggle', [
			'label'        => __( 'Product Column 2', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'separate_col_style' => 'yes',
			],
		] );
		$this->start_popover();
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "col2_img_bg",
			'label'     => __( 'Image Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $col2_img_bg,
			'condition' => [
				'col2_style_po_toggle' => 'yes',
			],
			'exclude'   => [ 'image' ],
		] );
		$this->add_control( 'col2_btn_color', [
			'label'     => __( 'Button Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $col2_btn => 'color:{{VALUE}}' ],
			'condition' => [
				'col2_style_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "col2_btn_bg",
			'label'     => __( 'Button Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $col2_btn,
			'condition' => [
				'col2_style_po_toggle' => 'yes',
			],
			'exclude'   => [ 'image' ],
		] );
		$this->end_popover();

		// Column 3 style
		$this->add_control( 'col3_style_po_toggle', [
			'label'        => __( 'Product Column 3', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'separate_col_style' => 'yes',
			],
		] );
		$this->start_popover();
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "col3_img_bg",
			'label'     => __( 'Image Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $col3_img_bg,
			'condition' => [
				'col3_style_po_toggle' => 'yes',
			],
			'exclude'   => [ 'image' ],
		] );
		$this->add_control( 'col3_btn_color', [
			'label'     => __( 'Button Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $col3_btn => 'color:{{VALUE}}' ],
			'condition' => [
				'col3_style_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "col3_btn_bg",
			'label'     => __( 'Button Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $col3_btn,
			'condition' => [
				'col3_style_po_toggle' => 'yes',
			],
			'exclude'   => [ 'image' ],
		] );
		$this->end_popover();

		// combined column styling
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "image_bg",
			'label'     => __( 'Image Background', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $img_class,
			'condition' => [
				'separate_col_style!' => 'yes',
			],
		] );
		$this->add_control( 'btn_color', [
			'label'     => __( 'Button Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $btn => 'color:{{VALUE}}' ],
			'condition' => [
				'separate_col_style!' => 'yes',
			],
		] );
		$this->add_control( 'btn_bg_color', [
			'label'     => __( 'Button Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ $btn => 'background:{{VALUE}}' ],
			'condition' => [
				'separate_col_style!' => 'yes',
			],
		] );


		$this->end_controls_section();
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
		$title                  = $this->get_settings_for_display( 'table_title' );
		$highlighted_product_id = 317; //@todo; make it dynamic
		$theme_wrap_class       = $theme = '';
		if ( ! empty( $ds['theme'] ) ) {
			$theme            = esc_attr( $ds['theme'] );
			$theme_wrap_class = " custom {$theme}";
		}
		?>
		<?php do_action( 'eael/wcpc/before_content_wrapper' ); ?>
        <div class="eael-wcpc-wrapper woocommerce <?php echo esc_attr( $theme_wrap_class ); ?>">
			<?php do_action( 'eael/wcpc/before_main_table' ); ?>
            <table class="eael-wcpc-table table-responsive">
                <tbody>
				<?php if ( empty( $products ) ) { ?>

                    <tr class="no-products">
                        <td><?php esc_html_e( 'No products added to compare.', 'essential-addons-for-elementor-lite' ) ?></td>
                    </tr>

				<?php } else {
					$count = 1;
					foreach ( $fields as $field => $name ) {
						$f_heading_class = 1 === $count ? 'first-th' : '';
						$count ++;
						?>
                        <tr class="<?php echo esc_attr( $field ); ?>">
                            <th class="thead <?php echo esc_attr( $f_heading_class ); ?>">
                                <div>
									<?php if ( $field === 'image' ) {
										if ( ! empty( $title ) ) {
											printf( "<h1 class='wcpc-title'>%s</h1>", esc_html( $title ) );
										}
									} else {
										if ( 'theme-4' === $theme ) {
											$this->print_icon();
										}
										if ( 'theme-5' === $theme && $field === 'title' ) {
											echo '&nbsp;';
										} else {
											printf( '<span class="field-name">%s</span>', esc_html( $name ) );

										}
									} ?>
                                </div>
                            </th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) {
								$highlighted   = $product_id === $highlighted_product_id ? 'featured' : '';
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted" ?>
                                <td class="<?php echo esc_attr( $product_class ); ?>">
                                    <span>
                                    <?php
                                    if ( $field === 'image' ) {
	                                    echo '<span class="img-inner">';
	                                    if ( 'theme-4' === $theme ) {
		                                    echo '<span class="ribbon">New</span>';
	                                    }
                                    }
                                    echo ! empty( $product->fields[ $field ] ) ? $product->fields[ $field ] : '&nbsp;';
                                    if ( $field === 'image' ) {
	                                    if ( 'theme-4' === $theme ) {
		                                    echo ! empty( $product->fields['title'] ) ? sprintf( "<p class='product-title'>%s</p>", esc_html( $product->fields['title'] ) ) : '&nbsp;';
		                                    echo ! empty( $product->fields['price'] ) ? $product->fields['price'] : '&nbsp;';
	                                    }
	                                    echo '</span>';
                                    }
                                    ?>
                                    </span>
                                </td>

								<?php
								++ $index;
							}
							?>

                        </tr>

					<?php } ?>

					<?php if ( $this->get_settings_for_display( 'repeat_price' ) == 'yes' && isset( $fields['price'] ) ) : ?>
                        <tr class="price repeated">
                            <th>
                                <div>
									<?php
									if ( 'theme-4' === $theme ) {
										$this->print_icon();
									}
									echo wp_kses_post( $fields['price'] ) ?>
                                </div>
                            </th>

							<?php
							$index             = 0;
							foreach ( $products as $product_id => $product ) :
								$highlighted = $product_id === $highlighted_product_id ? 'featured' : '';
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted" ?>
                                <td class="<?php echo esc_attr( $product_class ) ?>"><?php echo wp_kses_post( $product->fields['price'] ); ?></td>
								<?php
								++ $index;
							endforeach; ?>

                        </tr>
					<?php endif; ?>

					<?php if ( $this->get_settings_for_display( 'repeat_add_to_cart' ) == 'yes' && isset( $fields['add-to-cart'] ) ) : ?>
                        <tr class="add-to-cart repeated">
                            <th>
                                <div>
									<?php
									if ( 'theme-4' === $theme ) {
										$this->print_icon();
									}
									echo wp_kses_post( $fields['add-to-cart'] ); ?>
                                </div>
                            </th>

							<?php
							$index             = 0;
							foreach ( $products as $product_id => $product ) :
								$highlighted = $product_id === $highlighted_product_id ? 'featured' : '';
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted" ?>
                                <td class="<?php echo esc_attr( $product_class ); ?>">
									<?php woocommerce_template_loop_add_to_cart(); ?>
                                </td>
								<?php
								++ $index;
							endforeach; ?>

                        </tr>
					<?php endif; ?>

				<?php } ?>
                </tbody>
            </table>
			<?php do_action( 'eael/wcpc/after_main_table' ); ?>
        </div>
		<?php do_action( 'eael/wcpc/after_content_wrapper' ); ?>
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

	/**
	 * Return the array with all products and all attributes values
	 *
	 * @param array $products ids of wc product
	 *
	 * @return array The complete list of products with all attributes value
	 */
	public function get_products_list( $products = [] ) {
		$products_list = [];
		if ( empty( $products ) ) {
			$products = $this->products_list;
		}

		$products = apply_filters( 'eael/wcpc/products_ids', $products );
		$fields   = $this->fields( $products );
		global $product;
		if ( ! empty( $products ) && is_array( $products ) ) {
			foreach ( $products as $product_id ) {
				/** @type WC_Product $product WooCommerce Product */
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}

				$product->fields = [];

				// custom attributes
				foreach ( $fields as $field => $name ) {
					switch ( $field ) {
						case 'title':
							$product->fields[ $field ] = $product->get_title();
							break;
						case 'price':
							$product->fields[ $field ] = $product->get_price_html();
							break;
						case 'add-to-cart':
							ob_start();
							woocommerce_template_loop_add_to_cart();
							$product->fields[ $field ] = ob_get_clean();
							break;
						case 'image':
							$product->fields[ $field ] = $product->get_image();
							break;
						case 'description':
							$description               = apply_filters( 'woocommerce_short_description', $product->get_short_description() ? $product->get_short_description() : wc_trim_string( $product->get_description(), 400 ) );
							$product->fields[ $field ] = apply_filters( 'eael/wcpc/woocommerce_short_description', $description );
							break;
						case 'stock':
							$availability = $product->get_availability();
							if ( empty( $availability['availability'] ) ) {
								$availability['availability'] = __( 'In stock', 'essential-addons-for-elementor-lite' );
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) );
							break;
						case 'sku':
							$sku = $product->get_sku();
							! $sku && $sku = '-';
							$product->fields[ $field ] = $sku;
							break;
						case 'weight':
							if ( $weight = $product->get_weight() ) {
								$weight = wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							} else {
								$weight = '-';
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $weight ) );
							break;
						case 'dimensions':
							$dimensions = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : $product->get_dimensions();
							! $dimensions && $dimensions = '-';
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $dimensions ) );
							break;
						default:
							if ( taxonomy_exists( $field ) ) {
								$product->fields[ $field ] = [];
								$terms                     = get_the_terms( $product_id, $field );
								if ( ! empty( $terms ) && is_array( $terms ) ) {
									foreach ( $terms as $term ) {
										$term                        = sanitize_term( $term, $field );
										$product->fields[ $field ][] = $term->name;
									}
								}
								$product->fields[ $field ] = implode( ', ', $product->fields[ $field ] );
							} else {
								do_action( 'eael/wcpc/compare_field_' . $field, [
									$product,
									&$product->fields,
								] );
							}
							break;
					}
				}

				$products_list[ $product_id ] = $product;
			}
		}

		return apply_filters( 'eael/wcpc/products_list', $products_list );
	}

	/**
	 * Get the fields to show in the comparison table
	 *
	 * @param array $products Optional array of products ids
	 *
	 * @return array $fields it returns an array of fields to show on the comparison table
	 */
	public function fields( $products = [] ) {
		$fields = $this->get_settings_for_display( 'fields' );
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return apply_filters( 'eael/wcpc/products_fields_none', [] );
		}

		$df             = $this->get_field_types();
		$fields_to_show = [];
		foreach ( $fields as $field ) {
			if ( isset( $df[ $field['field_type'] ] ) ) {
				$fields_to_show[ $field['field_type'] ] = $field['field_label'];
			} else {
				if ( taxonomy_exists( $field['field_type'] ) ) {
					$fields_to_show[ $field['field_type'] ] = wc_attribute_label( $field['field_type'] );
				}
			}
		}

		return apply_filters( 'eael/wcpc/products_fields_to_show', $fields_to_show, $products );
	}

	public function get_product_remove_url( $product_id ) {
		$url_args = [
			'action' => $this->remove_action,
			'id'     => $product_id,
		];

		return apply_filters( 'eael/wcpc/get_product_remove_url', esc_url_raw( add_query_arg( $url_args, site_url() ) ), $this->remove_action );
	}

	protected function print_icon() {
		?>
        <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"/>
        </svg>
		<?php
	}


}

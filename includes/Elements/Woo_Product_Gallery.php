<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Product_Gallery extends Widget_Base {
	use Helper;

	/**
	 * @var int
	 */
	protected $page_id;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( !$is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists( 'woocommerce' ) ) {
			$this->load_quick_view_asset();
		}
	}

	public function get_name() {
		return 'eael-woo-product-gallery';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-gallery';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [
			'woo',
			'woocommerce',
			'ea woocommerce',
			'ea woo product gallery',
			'ea woocommerce product gallery',
			'product gallery',
			'woocommerce gallery',
			'gallery',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-product-gallery/';
	}

	public function get_style_depends() {
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		];
	}

	public function get_script_depends() {
		return [
			'font-awesome-4-shim',
		];
	}

	protected function init_content_wc_notice_controls() {
		if ( !function_exists( 'WC' ) ) {
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
	}


	protected function eael_get_product_orderby_options() {
		return apply_filters( 'eael/product-gallery/orderby-options', [
			'ID'         => __( 'Product ID', 'essential-addons-for-elementor-lite' ),
			'title'      => __( 'Product Title', 'essential-addons-for-elementor-lite' ),
			'_price'     => __( 'Price', 'essential-addons-for-elementor-lite' ),
			'_sku'       => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			'date'       => __( 'Date', 'essential-addons-for-elementor-lite' ),
			'modified'   => __( 'Last Modified Date', 'essential-addons-for-elementor-lite' ),
			'parent'     => __( 'Parent Id', 'essential-addons-for-elementor-lite' ),
			'rand'       => __( 'Random', 'essential-addons-for-elementor-lite' ),
			'menu_order' => __( 'Menu Order', 'essential-addons-for-elementor-lite' ),
		] );
	}

	protected function eael_get_product_filterby_options() {
		return apply_filters( 'eael/product-gallery/filterby-options', [
			'recent-products'       => esc_html__( 'Recent Products', 'essential-addons-for-elementor-lite' ),
			'featured-products'     => esc_html__( 'Featured Products', 'essential-addons-for-elementor-lite' ),
			'best-selling-products' => esc_html__( 'Best Selling Products', 'essential-addons-for-elementor-lite' ),
			'sale-products'         => esc_html__( 'Sale Products', 'essential-addons-for-elementor-lite' ),
			'top-products'          => esc_html__( 'Top Rated Products', 'essential-addons-for-elementor-lite' ),
			'manual'                => esc_html__('Manual Selection', 'essential-addons-for-elementor-lite'),
		] );
	}

	protected function register_controls() {
		$this->init_content_wc_notice_controls();

		if ( !function_exists( 'WC' ) ) {
			return;
		}
		// Content Controls
		$this->init_content_layout_controls();
		$this->init_content_product_settings_controls();
		$this->eael_product_badges();
		$this->init_content_load_more_controls();

		// Style Controls---------------
		$this->init_style_gallery_controls();
		$this->init_style_product_controls();
		$this->init_style_color_typography_controls();
		$this->init_style_addtocart_controls();
		$this->eael_product_action_buttons();
		$this->eael_product_action_buttons_style();
		do_action( 'eael/controls/load_more_button_style', $this );
		$this->eael_product_view_popup_style();

	}

	protected function init_content_layout_controls() {
		$this->start_controls_section(
			'eael_section_product_gallery_layouts',
			[
				'label' => esc_html__( 'Layouts', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_woo_product_gallery_cat_heading',
			[
				'label' => __( 'Gallery', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_terms_position',
			[
				'label'   => __( 'Position', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'eael-terms-layout-horizontal' => [
						'title' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-justify-start-v',
					],
					'eael-terms-layout-vertical' => [
						'title' => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-justify-start-h',
					],
				],
				'default' => 'eael-terms-layout-horizontal',
				'toggle'  => false,
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_terms_horizontal_align',
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
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-cat-tab' => 'text-align: {{VALUE}};',
				],
				'condition' => [
                    'eael_product_gallery_terms_position' => 'eael-terms-layout-horizontal',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_terms_vertical_align',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'         => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'row-reverse' => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'row',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
                    'eael_product_gallery_terms_position' => 'eael-terms-layout-vertical',
				],
			]
		);

		$this->add_control(
			'eael_woo_product_gallery_terms_show_all',
			[
				'label'        => __( 'Show All Category Tab', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'eael_woo_product_gallery_terms_all_text',
			[
				'label'       => esc_html__( 'Change All Text', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'label_block' => false,
				'default'     => esc_html__( 'All', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'eael_woo_product_gallery_terms_show_all' => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_woo_product_gallery_terms_thumb',
			[
				'label'        => __( 'Show Terms Thumbnail', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'description'  => __( 'Display thumbnail if a term (Category/Tag) has a thumbnail.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_all_tab_thumb',
			[
				'label' => esc_html__( 'Choose All Tab Thumb', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'   => [
					'eael_woo_product_gallery_terms_show_all' => 'yes',
					'eael_woo_product_gallery_terms_thumb' => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_woo_product_gallery_product_heading',
			[
				'label' => __( 'Product', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_items_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'    => esc_html__( 'Grid', 'essential-addons-for-elementor-lite' ),
					'masonry' => esc_html__( 'Masonry', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$image_path = EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/woo-product-gallery-';
		$this->add_control(
			'eael_product_gallery_style_preset',
			[
				'label'       => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'eael-product-preset-1' => [
						'title' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
						'image' => $image_path . 'preset-1.png'
					],
					'eael-product-preset-2' => [
						'title' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
						'image' => $image_path . 'preset-2.png'
					],
					'eael-product-preset-3' => [
						'title' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
						'image' => $image_path . 'preset-3.png'
					],
					'eael-product-preset-4' => [
						'title' => esc_html__( 'Preset 4', 'essential-addons-for-elementor-lite' ),
						'image' => $image_path . 'preset-4.png'
					]
				],
				'default'     => 'eael-product-preset-1',
				'label_block' => true,
                'toggle'      => false,
                'image_choose'=> true,
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_column',
			[
				'label'        => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '3',
				'options'      => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
					'5' => esc_html__( '5', 'essential-addons-for-elementor-lite' ),
					'6' => esc_html__( '6', 'essential-addons-for-elementor-lite' ),
				],
				'toggle'       => true,
				'prefix_class' => 'eael-product-gallery-column%s-',
			]
		);

		$this->add_control(
			'eael_wc_loop_hooks',
			[
				'label'        => esc_html__( 'WooCommerce Loop Hooks', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'ON', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'OFF', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => '',
				'description'  => __( 'This will enable WooCommerce loop Before and After hooks. It may break your layout.', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->end_controls_section();
	}

	protected function init_content_product_settings_controls() {
		$this->start_controls_section( 'eael_section_product_gallery_settings', [
			'label' => esc_html__( 'Product Settings', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'product' => [
						'title' => esc_html__( 'Products', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-product-related',
					],
					'source_dynamic' => [
						'title' => esc_html__( 'Dynamic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-archive',
					],
					'archive' => [
						'title' => esc_html__( 'Archive', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-archive-posts',
					],
				],
				'default' => 'product',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'eael_global_dynamic_source_warning_text',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
				'condition'       => [
					'post_type' => 'archive',
				],
			]
		);

		$this->add_control( 'eael_product_gallery_product_filter', [
			'label'     => esc_html__( 'Filter By', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'recent-products',
			'options'   => $this->eael_get_product_filterby_options(),
			'condition' => [
				'post_type' => 'product',
			],
		] );

		$this->add_control( 'orderby', [
			'label'   => __( 'Order By', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->eael_get_product_orderby_options(),
			'default' => 'date',
			'condition' => [
				'post_type' => 'product',
			],

		] );

		$this->add_control(
			'order',
			[
				'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'asc' => [
						'title' => esc_html__( 'Ascending', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fas fa-sort-amount-up-alt',
					],
					'desc' => [
						'title' => esc_html__( 'Descending', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fas fa-sort-amount-down',
					],
				],
				'default' => 'desc',
				'toggle'  => false,
			]
		);

		$this->add_control( 'eael_product_gallery_products_count', [
			'label'   => __( 'Products Count', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 3,
			'min'     => 1,
			'max'     => 1000,
			'step'    => 1,
			'condition' => [
				'post_type' => 'product',
				'eael_product_gallery_product_filter!' => 'manual'
			],
		] );

		$this->add_control( 'product_offset', [
			'label'     => __( 'Offset', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 0,
			'condition' => [
				'post_type'                            => 'product',
				'eael_product_gallery_product_filter!' => 'manual'
			],
		] );

		$this->add_control(
			'eael_product_gallery_products_in',
			[
				'label'       => esc_html__( 'Select Products', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'product',
				'condition'   => [
					'eael_product_gallery_product_filter' => 'manual',
					'post_type'                           => 'product'
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_categories', [
				'label'       => __( 'Product Categories', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'taxonomy',
				'source_type' => 'product_cat',
				'label_block' => true,
				'multiple'    => true,
				'condition' => [
					'post_type' => 'product',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_tags', [
				'label'       => __( 'Product Tags', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'taxonomy',
				'source_type' => 'product_tag',
				'label_block' => true,
				'multiple'    => true,
				'condition'   => [
					'post_type'                            => 'product',
					'eael_product_gallery_product_filter!' => 'manual'
				],
			]
		);

		$this->add_control(
			'product_type_logged_users',
			[
				'label'       => __('Purchase Type', 'essential-addons-for-elementor-lite'),
				'type'        => Controls_Manager::SELECT,
				'description' => __('For logged in users only!', 'essential-addons-for-elementor-lite'),
				'options'     => [
					'both'          => __('Both', 'essential-addons-for-elementor-lite'),
					'purchased'     => __('Purchased Only', 'essential-addons-for-elementor-lite'),
					'not-purchased' => __('Not Purchased Only', 'essential-addons-for-elementor-lite'),
				],
				'default' => 'both',
			'condition' => [
				'post_type' => 'product',
			],]
		);

		$this->add_control(
			'eael_product_gallery_dynamic_template',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => $this->get_template_list_for_dropdown(),
			]
		);

		$this->add_control(
			'eael_product_gallery_title_html_tag',
			[
				'label'       => __( 'Title HTML Tag', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'h1' => [
						'title' => esc_html__( 'H1', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h1',
					],
					'h2' => [
						'title' => esc_html__( 'H2', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h2',
					],
					'h3' => [
						'title' => esc_html__( 'H3', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h3',
					],
					'h4' => [
						'title' => esc_html__( 'H4', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h4',
					],
					'h5' => [
						'title' => esc_html__( 'H5', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h5',
					],
					'h6' => [
						'title' => esc_html__( 'H6', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h6',
					],
					'div' => [
						'title' => esc_html__( 'Div', 'essential-addons-for-elementor-lite' ),
						'text'  => 'div',
					],
					'span' => [
						'title' => esc_html__( 'Span', 'essential-addons-for-elementor-lite' ),
						'text'  => 'span',
					],
					'p' => [
						'title' => esc_html__( 'P', 'essential-addons-for-elementor-lite' ),
						'text'  => 'P',
					],
				],
				'default' => 'h2',
				'toggle'  => false,
			]
		);

		$this->add_control( 'eael_product_gallery_rating', [
			'label'        => esc_html__( 'Show Product Rating?', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [
				'eael_product_gallery_style_preset!' => [ 'eael-product-preset-1' ],
			],

		] );

		$this->add_control(
			'eael_product_out_of_stock_show',
			[
				'label'        => esc_html__( 'Show Stock Out Products?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_show_secondary_image',
			[
				'label'        => __( 'Show Secondary Image on Hover', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'description'  => __( 'Enable to show a secondary image from the product gallery on hover.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_product_gallery_price',
			[
				'label'        => esc_html__( 'Show Product Price?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_product_gallery_style_preset!' => 'eael-product-default',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'        => 'eael_product_gallery_image_size',
				'exclude'     => [ 'custom' ],
				'default'     => 'medium',
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function init_content_load_more_controls() {
		$this->start_controls_section( 'eael_product_gallery_load_more_section', [
			'label' => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
            'show_load_more',
            [
                'label'   => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'no' => [
                        'title' => esc_html__( 'Disable', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'true' => [
                        'title' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-button',
                    ],
                    'infinity' => [
                        'title' => esc_html__( 'Infinity Scroll', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-image-box',
                    ],
                ],
                'default'   => 'no',
                'toggle'    => false,
            ]
        );

        $this->add_control(
            'load_more_infinityscroll_offset',
            [
                'label'       => esc_html__('Scroll Offset (px)', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::NUMBER,
                'dynamic'     => [ 'active' => false ],
                'label_block' => false,
                'default'     => '-200',
                'description' => esc_html__('Set the position of loading to the viewport before it ends from view', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'show_load_more' => 'infinity',
                ],
            ]
        );

		$this->add_control( 'show_load_more_text', [
			'label'       => esc_html__( 'Label Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'show_load_more' => [ 'yes', '1', 'true' ],
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->end_controls_section(); # end of section 'Load More'
	}

	protected function init_style_product_controls() {
		$this->start_controls_section(
			'eael_product_gallery_styles',
			[
				'label' => esc_html__( 'Products', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_content_alignment',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'eael_product_gallery_style_preset' => 'eael-product-preset-4',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_background_color',
			[
				'label'     => esc_html__( 'Content Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product, {{WRAPPER}} .eael-product-gallery .icons-wrap.block-box-style' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_peoduct_gallery_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_product_gallery_style_preset' => 'eael-product-preset-4',
				],
			]
		);

		$this->start_controls_tabs( 'eael_product_gallery_tabs' );

		$this->start_controls_tab( 'eael_product_gallery_tabs_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'eael_peoduct_gallery_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => false,
						],
					],
					'color'  => [
						'default' => '#eee',
					],
				],
				'selector'       => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_peoduct_gallery_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_gallery_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'eael_peoduct_gallery_border_border!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_gallery_box_shadow_hover',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_peoduct_gallery_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => [
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product'                                    => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_inner_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'top'      => '15',
					'right'    => '15',
					'bottom'   => '15',
					'left'     => '15',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_product_gallery_style_preset!' => 'eael-product-preset-4',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function init_style_color_typography_controls() {

		$this->start_controls_section(
			'eael_section_product_gallery_typography',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_product_gallery_product_title_heading',
			[
				'label' => __( 'Product Title', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_product_title_color',
			[
				'label'     => esc_html__( 'Product Title Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .eael-product-title *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_product_title_typography',
				'selector' => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .eael-product-title *',
			]
		);

		$this->add_control(
			'eael_product_gallery_product_price_heading',
			[
				'label' => __( 'Product Price', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_product_price_color',
			[
				'label'     => esc_html__( 'Price Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .price, {{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .eael-product-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_product_sale_price_color',
			[
				'label'     => esc_html__( 'Sale Price Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .price ins, {{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .eael-product-price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_product_price_typography',
				'selector' => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .price,{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .eael-product-price',
			]
		);

		$this->add_control(
			'eael_product_gallery_product_rating_heading',
			[
				'label' => __( 'Star Rating', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_product_rating_color',
			[
				'label'     => esc_html__( 'Rating Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2b01e',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce .star-rating::before'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-product-gallery .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_product_gallery_product_rating_typography',
				'selector'  => '{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .star-rating',
				'condition' => [
					'eael_product_gallery_style_preset!' => [
						'eael-product-preset-3',
						'eael-product-preset-2',
						'eael-product-preset-1',
					],
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_product_rating_size',
			[
				'label'     => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce ul.products li.product .star-rating' => 'font-size: {{SIZE}}px!important;',
				],
				'condition' => [
					'eael_product_gallery_style_preset' => [
						'eael-product-preset-3',
						'eael-product-preset-2',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_sale_badge_heading',
			[
				'label' => __( 'Sale Badge', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_sale_badge_color',
			[
				'label'     => esc_html__( 'Sale Badge Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_sale_badge_background',
			[
				'label'     => esc_html__( 'Sale Badge Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => [
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock).sale-preset-4:after'                     => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_sale_badge_typography',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock)',
			]
		);

		// stock out badge
		$this->add_control(
			'eael_product_gallery_stock_out_badge_heading',
			[
				'label' => __( 'Stock Out Badge', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_product_gallery_stock_out_badge_color',
			[
				'label'     => esc_html__( 'Stock Out Badge Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_stock_out_badge_background',
			[
				'label'     => esc_html__( 'Stock Out Badge Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => [
					'{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock.sale-preset-4:after'                                                => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_stock_out_badge_typography',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock',
			]
		);

		$this->end_controls_section();
	}

	// add to cart button
	protected function init_style_addtocart_controls() {

		$this->start_controls_section(
			'eael_section_product_gallery_add_to_cart_styles',
			[
				'label'     => esc_html__( 'Add to Cart Button Styles', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_product_gallery_style_preset!' => [
						'eael-product-preset-3',
						'eael-product-preset-2',
						'eael-product-preset-1',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_add_to_cart_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_add_to_cart_radius',
			[
				'label'      => __( 'Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'eael_product_gallery_add_to_cart_is_gradient_bg',
			[
				'label'        => __( 'Use Gradient Background', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->start_controls_tabs( 'eael_product_gallery_add_to_cart_style_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_add_to_cart_color',
			[
				'label'     => esc_html__( 'Button Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button, 
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button, 
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'eael_product_gallery_add_to_cart_gradient_background',
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart',
				'condition' => [
					'eael_product_gallery_add_to_cart_is_gradient_bg' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_product_gallery_add_to_cart_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4045AE',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button, 
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button, 
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_product_gallery_add_to_cart_is_gradient_bg' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_gallery_add_to_cart_border',
				'selector' => '{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button, 
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_product_gallery_add_to_cart_typography',
				'selector'  => '{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart',
				'condition' => [
					'eael_product_gallery_style_preset' => [ 'eael-product-preset-4' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_gallery_add_to_cart_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_add_to_cart_hover_color',
			[
				'label'     => esc_html__( 'Button Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'eael_product_gallery_add_to_cart_hover_gradient_background',
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button:hover,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button:hover,
                {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart:hover',
				'condition' => [
					'eael_product_gallery_add_to_cart_is_gradient_bg' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_product_gallery_add_to_cart_hover_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4045AE',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_product_gallery_add_to_cart_is_gradient_bg' => '',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_add_to_cart_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .button.add_to_cart_button:hover,
                    {{WRAPPER}} .eael-product-gallery .woocommerce li.product .added_to_cart:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function init_style_gallery_controls() {
        // add to cart button
		$this->start_controls_section(
			'eael_section_product_gallery',
			[
				'label' => esc_html__( 'Gallery Styles', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_product_gallery_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_terms',
			[
				'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab' => 'width: {{SIZE}}%;',
					'{{WRAPPER}} .woocommerce'  => 'width: calc(100% - {{SIZE}}%);',
				],
				'condition'  => [
					'eael_product_gallery_terms_position' => 'eael-terms-layout-vertical',
				]
			]
		);

		$this->add_control(
			'eael_product_gallery_terms_gap',
			[
				'label'      => __( 'Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-gallery' => 'gap: {{SIZE}}%;',
				],
				'condition'  => [
					'eael_product_gallery_terms_position' => 'eael-terms-layout-vertical',
				]
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_margin',
			[
				'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_radius',
			[
				'label'      => __( 'Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_gallery_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-cat-tab',
			]
		);

		$this->add_control(
			'eael_product_gallery_item_heading',
			[
				'label'     => __( 'Items', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_item_typography',
				'selector' => '{{WRAPPER}} .eael-cat-tab a',
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_item_margin',
			[
				'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_item_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_gallery_item_radius',
			[
				'label'      => __( 'Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_product_gallery_item_style_tabs' );

		$this->start_controls_tab( 'eael_product_gallery_item_style_tabs_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_item_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_item_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_gallery_item_border',
				'selector' => '{{WRAPPER}} .eael-cat-tab a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_gallery_item_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_item_hover_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_item_hover_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_item_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_gallery_item_active_styles', [ 'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_item_active_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_item_active_background',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_item_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-cat-tab a.active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_gallery_item_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-cat-tab a',
			]
		);

		$this->add_control(
			'eael_product_gallery_item_thumb_heading',
			[
				'label'     => __( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_woo_product_gallery_terms_thumb' => 'yes',
				]
			]
		);

		$this->add_control(
			'eael_product_gallery_item_thumb_width',
			[
				'label'      => __( 'Width (PX)', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-cat-tab img' => 'width: {{SIZE}}px;',
				],
				'condition'  => [
					'eael_woo_product_gallery_terms_thumb' => 'yes',
				]
			]
		);

		$this->add_control(
			'eael_product_gallery_item_thumb_space',
			[
				'label'      => __( 'Space Between', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-terms-layout-horizontal .eael-cat-tab img' => 'margin-bottom: {{SIZE}}px};',
					'{{WRAPPER}} .eael-terms-layout-vertical .eael-cat-tab img'   => 'margin-right: {{SIZE}}px};',
				],
				'condition'  => [
					'eael_woo_product_gallery_terms_thumb' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function eael_product_badges() {
		$this->start_controls_section(
			'eael_section_product_badges',
			[
				'label' => esc_html__( 'Sale / Stock Out Badge', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'eael_product_sale_badge_preset',
			[
				'label'   => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sale-preset-1',
				'options' => [
					'sale-preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'sale-preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
					'sale-preset-3' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
					'sale-preset-4' => esc_html__( 'Preset 4', 'essential-addons-for-elementor-lite' ),
					'sale-preset-5' => esc_html__( 'Preset 5', 'essential-addons-for-elementor-lite' ),

				]
			]
		);

		$this->add_control(
			'eael_product_sale_badge_alignment',
			[
				'label'   => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'  => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_sale_text',
			[
				'label'     => esc_html__( 'Sale Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
				'ai' => [
					'active' => false,
				],
			]
		);
		$this->add_control(
			'eael_product_gallery_stockout_text',
			[
				'label' => esc_html__( 'Stock Out Text', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::TEXT,
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function eael_product_action_buttons() {
		$this->start_controls_section(
			'eael_section_product_action_buttons',
			[
				'label' => esc_html__( 'Buttons', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_product_gallery_quick_view',
			[
				'label'        => esc_html__( 'Show Quick view?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_product_gallery_style_preset!' => [
						'eael-product-preset-4',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_quick_view_title_tag',
			[
				'label'       => __( 'Quick view Title Tag', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'h1' => [
						'title' => esc_html__( 'H1', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h1',
					],
					'h2' => [
						'title' => esc_html__( 'H2', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h2',
					],
					'h3' => [
						'title' => esc_html__( 'H3', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h3',
					],
					'h4' => [
						'title' => esc_html__( 'H4', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h4',
					],
					'h5' => [
						'title' => esc_html__( 'H5', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h5',
					],
					'h6' => [
						'title' => esc_html__( 'H6', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h6',
					],
					'div' => [
						'title' => esc_html__( 'Div', 'essential-addons-for-elementor-lite' ),
						'text'  => 'div',
					],
					'span' => [
						'title' => esc_html__( 'Span', 'essential-addons-for-elementor-lite' ),
						'text'  => 'span',
					],
					'p' => [
						'title' => esc_html__( 'P', 'essential-addons-for-elementor-lite' ),
						'text'  => 'P',
					],
				],
				'default'   => 'h1',
				'toggle'    => false,
				'separator' => 'after',
				'condition' => [
					'eael_product_gallery_style_preset!' => [
						'eael-product-preset-4',
					],
					'eael_product_gallery_quick_view'    => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_addtocart_show',
			[
				'label'        => esc_html__( 'Show Add to Cart?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_product_gallery_link_show',
			[
				'label'        => esc_html__( 'Show Link?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_product_gallery_style_preset!' => [
                        'eael-product-preset-4',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_image_clickable',
			[
				'label'        => esc_html__( 'Image Clickable?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();
	}

	protected function eael_product_action_buttons_style() {
		$this->start_controls_section(
			'eael_section_product_gallery_buttons_styles',
			[
				'label'     => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_product_gallery_style_preset' => [
						'eael-product-preset-3',
						'eael-product-preset-2',
						'eael-product-preset-1',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_gallery_buttons_typography',
				'selector' => '{{WRAPPER}} .eael-product-gallery .icons-wrap li.add-to-cart a',
			]
		);

		$this->start_controls_tabs( 'eael_product_gallery_buttons_style_tabs' );

		$this->start_controls_tab( 'eael_product_gallery_buttons_style_tabs_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_buttons_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_buttons_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap li a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_buttons_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap:not(.details-block-style-2) li a'       => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap.details-block-style-2 li:only-child a'  => 'border-radius: {{SIZE}}px!important;',
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap.details-block-style-2 li:last-child a'  => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_gallery_buttons_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_gallery_buttons_hover_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5EAFF',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_gallery_buttons_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4045AE',
				'selectors' => [
					'{{WRAPPER}} .eael-product-gallery .eael-product-wrap .icons-wrap li a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function eael_product_view_popup_style() {
		$this->start_controls_section(
			'eael_product_popup',
			[
				'label'     => __( 'Popup', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_product_gallery_quick_view'    => 'yes',
					'eael_product_gallery_style_preset!' => [
						'eael-product-preset-4',
					],
				],
			]
		);

		$this->add_control(
			'eael_product_popup_title',
			[
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_title_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .product_title',
			]
		);

		$this->add_control(
			'eael_product_popup_title_color',
			[
				'label'     => __( 'Title Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#252525',
				'selectors' => [
                        '.eael-popup-details-render{{WRAPPER}} div.product .product_title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_price',
			[
				'label'     => __( 'Price', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_price_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .price',
			]
		);

		$this->add_control(
			'eael_product_popup_price_color',
			[
				'label'     => __( 'Price Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0242e4',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_sale_price_color',
			[
				'label'     => __( 'Sale Price Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_content',
			[
				'label'     => __( 'Content', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_content_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .woocommerce-product-details__short-description',
			]
		);

		$this->add_control(
			'eael_product_popup_content_color',
			[
				'label'     => __( 'Content Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_review_link_color',
			[
				'label'     => __( 'Review Link Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .product_meta a.woocommerce-review-link, .eael-popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_product_popup_review_link_hover',
			[
				'label'     => __( 'Review Link Hover', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} a.woocommerce-review-link:hover, .eael-popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_table_border_color',
			[
				'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ccc',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product table tbody tr, {{WRAPPER}} .eael-product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
				],
			]
		);

		// Sale
		$this->add_control(
			'eael_product_popup_sale_style',
			[
				'label'     => __( 'Sale Badge', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_sale_typo',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock)',
			]
		);

		$this->add_control(
			'eael_product_popup_sale_color',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock)' => 'color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_control(
			'eael_product_popup_sale_bg_color',
			[
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock)'                                      => 'background-color: {{VALUE}}!important;',
					'.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock).sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				],
			]
		);

        // Stock out
        $this->add_control(
            'eael_product_popup_stockout_style',
            [
                'label'     => __( 'Stock Out Badge', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_stockout_typo',
                'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} .eael-onsale.outofstock',
            ]
        );

        $this->add_control(
            'eael_product_popup_stockout_color',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale.outofstock' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_stockout_bg_color',
            [
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale.outofstock'                                      => 'background-color: {{VALUE}}!important;',
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale.outofstock.sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

		// Quantity
		$this->add_control(
			'eael_product_popup_quantity',
			[
				'label'     => __( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_quantity_typo',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a',
			]
		);

		$this->add_control(
			'eael_product_popup_quantity_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_quantity_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_quantity_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
					// OceanWP
					'.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus'                                                                                                                                                                         => 'border-color: {{VALUE}};',
				],
			]
		);

		// Cart Button
		$this->add_control(
			'eael_product_popup_cart_button',
			[
				'label'     => __( 'Cart Button', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_cart_button_typo',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
			]
		);

		$this->start_controls_tabs( 'eael_product_popup_cart_button_style_tabs' );

		$this->start_controls_tab( 'eael_product_popup_cart_button_style_tabs_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_popup_cart_button_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_cart_button_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_popup_cart_button_border',
				'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
			]
		);
		$this->add_control(
			'eael_product_popup_cart_button_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_popup_cart_button_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_popup_cart_button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5EAFF',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_cart_button_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F12DE0',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_cart_button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'eael_product_popup_cart_button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// SKU
		$this->add_control(
			'eael_product_popup_sku_style',
			[
				'label'     => __( 'SKU', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_popup_sku_typo',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} .product_meta',
			]
		);


		$this->add_control(
			'eael_product_popup_sku_title_color',
			[
				'label'     => __( 'Title Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_sku_content_color',
			[
				'label'     => __( 'Content Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .product_meta .sku, .eael-popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_sku_hover_color',
			[
				'label'     => __( 'Hover Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_close_button_style',
			[
				'label'     => __( ' Close Button', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'eael_product_popup_close_button_icon_size',
			[
				'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_popup_close_button_size',
			[
				'label'      => __( 'Button Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_close_button_color',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_close_button_bg',
			[
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'background-color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'eael_product_popup_close_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_popup_close_button_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close',
			]
		);

		$this->add_responsive_control(
			'eael_product_popup_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_product_popup_background',
				'label'    => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
				'exclude'  => [
					'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_popup_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-product-popup .eael-product-popup-details',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( !function_exists( 'WC' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// normalize for load more fix
		$settings[ 'layout_mode' ]    = $settings[ 'eael_product_gallery_items_layout' ];
		$widget_id                    = esc_attr( $this->get_id() );
		$settings[ 'eael_widget_id' ] = $widget_id;
		$is_product_archive = is_product_tag() || is_product_category() || is_shop() || is_product_taxonomy();

		if ( $settings[ 'post_type' ] === 'source_dynamic' && is_archive() || !empty( $_REQUEST[ 'post_type' ] ) ) {
			$settings[ 'posts_per_page' ] = $settings[ 'eael_product_gallery_products_count' ] ?: 3;
			$settings[ 'offset' ]         = $settings[ 'product_offset' ];
			$args                         = HelperClass::get_query_args( $settings );
			$args                         = HelperClass::get_dynamic_args( $settings, $args );
		} else {
			$args = $this->build_product_query( $settings );
		}

		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}
		// render dom
		$this->add_render_attribute( 'wrap', [
			'class'          => [
				"eael-product-gallery",
				$settings[ 'eael_product_gallery_style_preset' ],
				$settings[ 'eael_product_gallery_items_layout' ]
			],
			'id'             => 'eael-product-gallery',
			'data-widget-id' => $widget_id,
			'data-page-id'   => $this->page_id,
			'data-nonce'     => wp_create_nonce( 'eael_product_gallery' ),
		] );

		$this->add_render_attribute( 'wrap', 'class', $settings[ 'eael_product_gallery_terms_position' ] );

		$no_products_found = 0;

        if ( is_user_logged_in() ) {
            $product_purchase_type = ! empty( $settings['product_type_logged_users'] ) ? sanitize_text_field( $settings['product_type_logged_users'] ) : '';

            if (  in_array( $product_purchase_type, ['purchased', 'not-purchased'] ) ) {
                $user_ordered_products = HelperClass::eael_get_all_user_ordered_products();
                $no_products_found = empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ? 1 : 0;
 
                if ( ! empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ){
                    $args['post__in'] = $user_ordered_products;
                }

                if ( ! empty( $user_ordered_products ) && 'not-purchased' === $product_purchase_type ){
                    $args['post__not_in'] = $user_ordered_products;
                }
            }
        }

		?>

        <div <?php $this->print_render_attribute_string( 'wrap' ); ?> >
			<?php

			$this->eael_product_terms_render( $settings, $args );

			?>

            <div class="woocommerce">
				<?php
				do_action( 'eael_woo_before_product_loop' );
				$template                         = $this->get_template( $settings[ 'eael_product_gallery_dynamic_template' ] );
				$settings[ 'loadable_file_name' ] = $this->get_filename_only( $template );
				$dir_name                         = $this->get_temp_dir_name( $settings[ 'loadable_file_name' ] );
				$found_posts                      = 0;

				if ( file_exists( $template ) ) {
					$settings['eael_page_id'] = $this->page_id ? $this->page_id : get_the_ID();
					$show_secondary_image     = isset( $settings['eael_product_gallery_show_secondary_image'] ) && 'yes' === $settings['eael_product_gallery_show_secondary_image'];

					if( $settings['post_type'] === 'archive' && is_archive() && $is_product_archive ){
                        global $wp_query;
                        $query = $wp_query;
                        $args  = $wp_query->query_vars;
                    } else {
	                    $query = new \WP_Query( $args );
                    }

					$this->add_render_attribute( 'eael-post-appender', 'class', 'products eael-post-appender eael-post-appender-' . $this->get_id() );
					$this->add_render_attribute( 'eael-post-appender', 'data-layout-mode', $settings["eael_product_gallery_items_layout"] );

					if ( method_exists( Plugin::$instance->breakpoints, 'get_breakpoints_config' ) && ! empty( $breakpoints = Plugin::$instance->breakpoints->get_breakpoints_config() ) ) {
						foreach ( $breakpoints as $key => $breakpoint ){
							if ($breakpoint['is_enabled']) {
								if( isset( $settings['eael_product_gallery_show_secondary_image_' . $key] ) ){
									$value = "yes" === $settings['eael_product_gallery_show_secondary_image_' . $key] ? 'yes' : 'no';
									$this->add_render_attribute( 'eael-post-appender', 'data-ssi-'.$key, $value); // ssi = show secondary image
								}
							}
						}
					}

					$this->add_render_attribute( 'eael-post-appender', 'data-ssi-desktop', $show_secondary_image ? 'yes' : 'no' );

					echo '<ul '; $this->print_render_attribute_string( 'eael-post-appender' ); echo ' >';
					if ( $query->have_posts() ) {
						$found_posts         = $query->found_posts;
						$max_page            = ceil( $found_posts / absint( $args['posts_per_page'] ) );
						$args['max_page']    = $max_page;
						$args['found_posts'] = $query->found_posts;

						while ( $query->have_posts() ) {
							$query->the_post();
							include( $template );
						}
						wp_reset_postdata();
					} else {
						echo '<h2 class="eael-product-not-found">' . __( 'No Product Found', 'essential-addons-for-elementor-lite' ) . '</h2>';
					}
					echo '</ul>';
					do_action( 'eael_woo_after_product_loop' );

				} else {
					echo '<h2 class="eael-product-not-found">' . __( 'No Layout Found', 'essential-addons-for-elementor-lite' ) . '</h2>';
				}
				
				do_action( 'eael_woo_after_product_loop' );

				$this->print_load_more_button( $settings, $args, $dir_name );
				?>
            </div>
        </div>
        <script type="text/javascript">
			jQuery(document).ready(function ($) {
				var $scope = jQuery(".elementor-element-<?php echo esc_js( $this->get_id() ); ?>");
				var $products = $('.products', $scope);
				var $layout_mode = $products.data('layout-mode');

				if ($layout_mode === 'masonry') {
					// init isotope
					var $isotope_products = $products.isotope({
						                                          itemSelector: "li.product",
						                                          layoutMode: $layout_mode,
						                                          percentPosition: true
					                                          });

					$isotope_products.imagesLoaded().progress(function () {
						$isotope_products.isotope('layout');
					})

					$(window).on('resize', function () {
						$isotope_products.isotope('layout');
					});
				}
			});
        </script>
		<?php
	}

	/**
	 * build_product_query
	 * @param $settings
	 * @return array
	 */
	public function build_product_query( $settings ) {
		$get_product_cats = $settings[ 'eael_product_gallery_categories' ] ?: '';
		$product_cats     = ! empty( $get_product_cats ) ? str_replace( ' ', '', $get_product_cats ) : '';

		$get_product_tags = $settings[ 'eael_product_gallery_tags' ] ?: '';
		$product_tags_items = ! empty( $get_product_tags ) ? str_replace( ' ', '', $get_product_tags ) : '';

		// Category retrieve
		$cat_args            = array(
			'order'      => 'ASC',
			'hide_empty' => false,
			'include'    => $product_cats,
			'orderby'    => 'include',
		);
		$product_categories = get_terms( 'product_cat', $cat_args );

		// Tag retrieve
		$tag_args            = array(
			'order'      => 'ASC',
			'hide_empty' => false,
			'include'    => $product_tags_items,
			'orderby'    => 'include',
		);
		$product_tags = get_terms( 'product_tag', $tag_args );
		
		$args = [
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'future' ),
			'posts_per_page' => $settings[ 'eael_product_gallery_products_count' ] ?: 4,
			'order'          => ( isset( $settings[ 'order' ] ) ? $settings[ 'order' ] : 'desc' ),
			'offset'         => $settings[ 'product_offset' ],
			'tax_query'      => [
				'relation' => 'AND',
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
					'operator' => 'NOT IN',
				],
			],
		];
		// price & sku filter
		if ( $settings[ 'orderby' ] == '_price' ) {
			$args[ 'orderby' ]  = 'meta_value_num';
			$args[ 'meta_key' ] = '_price';
		} else if ( $settings[ 'orderby' ] == '_sku' ) {
			$args[ 'orderby' ]  = 'meta_value_num';
			$args[ 'meta_key' ] = '_sku';
		} else {
			$args[ 'orderby' ] = ( isset( $settings[ 'orderby' ] ) ? $settings[ 'orderby' ] : 'date' );
		}

		if ( !empty( $settings[ 'eael_product_gallery_categories' ] ) ) {
			$args_tax_query_combined['relation'] = 'OR';

			if ( $settings[ 'eael_woo_product_gallery_terms_show_all' ] == '' ) {
				if ( !empty( $product_cats ) && count( $product_categories ) > 0 ) {
					$args_tax_query_combined[] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $product_categories[ 0 ]->term_id,
						'operator' => 'IN',
					];
				}
			} else {
				$args_tax_query_combined[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $settings[ 'eael_product_gallery_categories' ],
					'operator' => 'IN',
				];
			}
		}

		if ( ! empty( $settings[ 'eael_product_gallery_tags' ] ) ) {
			$args_tax_query_combined['relation'] = 'OR';

			if ( $settings[ 'eael_woo_product_gallery_terms_show_all' ] == '' ) {
				if ( ! empty( $product_tags_items ) && count( $product_tags ) > 0 ) {
					$args_tax_query_combined[] = [
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $product_tags[ 0 ]->term_id,
						'operator' => 'IN',
					];
				}
			} else {
				$args_tax_query_combined[] = [
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $settings[ 'eael_product_gallery_tags' ],
					'operator' => 'IN',
				];
			}
		}

		$args[ 'meta_query' ] = [ 'relation' => 'AND' ];
		$show_stock_out_products = isset( $settings['eael_product_out_of_stock_show'] ) ? $settings['eael_product_out_of_stock_show'] : 'yes';

		if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' || 'yes' !== $show_stock_out_products  ) {
			$args[ 'meta_query' ][] = [
				'key'   => '_stock_status',
				'value' => 'instock'
			];
		}

		if ( $settings[ 'eael_product_gallery_product_filter' ] == 'featured-products' ) {
			$args[ 'tax_query' ] = [
				'relation' => 'AND',
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				],
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
					'operator' => 'NOT IN',
				],
			];
		} else if ( $settings[ 'eael_product_gallery_product_filter' ] == 'best-selling-products' ) {
			$args[ 'meta_key' ] = 'total_sales';
			$args[ 'orderby' ]  = 'meta_value_num';
			$args[ 'order' ]    = 'DESC';
		} else if ( $settings[ 'eael_product_gallery_product_filter' ] == 'sale-products' ) {
			$args[ 'post__in' ] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		} else if ( $settings[ 'eael_product_gallery_product_filter' ] == 'top-products' ) {
			$args[ 'meta_key' ] = '_wc_average_rating';
			$args[ 'orderby' ]  = 'meta_value_num';
			$args[ 'order' ]    = 'DESC';
		} else if( $settings[ 'eael_product_gallery_product_filter' ] == 'manual' ) {
			$args['post__in'] = ! empty( $settings['eael_product_gallery_products_in'] ) ? $settings['eael_product_gallery_products_in'] : [ 0 ];
		} else if ( $settings[ 'eael_product_gallery_product_filter' ] == 'related-products' ) {
		    $current_product_id = get_the_ID();
            $product_categories = wp_get_post_terms( $current_product_id, 'product_cat', array( 'fields' => 'ids' ) );
            $product_tags       = wp_get_post_terms( $current_product_id, 'product_tag', array( 'fields' => 'names' ) );
            $args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $product_categories,
                    'operator' => 'IN',
                ),
                array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'name',
                    'terms'    => $product_tags,
                    'operator' => 'IN',
                ),
            );
	    }

		if( isset( $args_tax_query_combined ) ){
			$args[ 'tax_query' ][] = $args_tax_query_combined;
		}

		return $args;
	}

	public function load_quick_view_asset() {
		add_action( 'wp_footer', function () {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
					wp_enqueue_script( 'zoom' );
				}
				if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
					wp_enqueue_script( 'flexslider' );
				}
				if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
					wp_enqueue_script( 'photoswipe-ui-default' );
					wp_enqueue_style( 'photoswipe-default-skin' );
					if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
						add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
					}
				}
				wp_enqueue_script( 'wc-add-to-cart-variation' );
				wp_enqueue_script( 'wc-single-product' );
			}
		} );
	}

	public function eael_product_terms_render( $settings, $args ) {
		$get_product_cats = $settings[ 'eael_product_gallery_categories' ] ?: '';
		$product_cats     = $get_product_cats ? str_replace( ' ', '', $get_product_cats ) : '';

		$get_product_tags = $settings[ 'eael_product_gallery_tags' ] ?: '';
		$product_tags_items = $get_product_tags ? str_replace( ' ', '', $get_product_tags ) : '';

		if ( $settings[ 'eael_woo_product_gallery_terms_show_all' ] == '' && empty( $get_product_cats ) && empty( $get_product_tags ) ) {
			return;
		}

		$template       = $this->get_template( $this->get_settings( 'eael_product_gallery_dynamic_template' ) );
		$dir_name       = method_exists( $this, 'get_temp_dir_name' ) ? $this->get_temp_dir_name( $this->get_filename_only( $template ) ) : "pro";
		$show_cat_thumb = isset( $settings[ 'eael_woo_product_gallery_terms_thumb' ] ) && 'yes' === $settings[ 'eael_woo_product_gallery_terms_thumb' ];

		$this->add_render_attribute( 'eael_product_gallery_product_ul', [
			'class'          => 'eael-cat-tab',
			'data-layout'    => $settings["eael_product_gallery_items_layout"],
			'data-template'  => json_encode( [ 'dir' => $dir_name, 'file_name' => $this->get_filename_only( $template ), 'name' => $this->process_directory_name() ], 1 ),
			'data-nonce'     => wp_create_nonce( 'eael_product_gallery' ),
			'data-page-id'   => $this->page_id,
			'data-widget-id' => $this->get_id(),
			'data-widget'    => $this->get_id(),
			'data-class'     => get_class( $this ),
			'data-args'      => http_build_query( $args ),
			'data-page'      => 1
		] );

		echo '<ul ' . $this->get_render_attribute_string( 'eael_product_gallery_product_ul' ) . '>';

		if ( $settings[ 'eael_woo_product_gallery_terms_show_all' ] == 'yes' ) {
			$all_taxonomy = 'all';
			if ( ! empty( $product_cats ) && ! empty( $product_tags_items ) ) {
				$all_taxonomy = 'product_cat|product_tag';
			} else if ( ! empty( $product_cats ) ) {
				$all_taxonomy = 'product_cat';
			} else if ( ! empty( $product_tags_items ) ) {
				$all_taxonomy = 'product_tag';
			}

			if ( $show_cat_thumb && !empty($settings['eael_all_tab_thumb']['url'])) {
				$show_all_cat_thumb = '<img src="' . esc_url( $settings['eael_all_tab_thumb']['url'] ) . '" />';
			} else {
				$show_all_cat_thumb = '';
			}

			$product_cats_data = ! empty( $product_cats ) ? json_encode( $product_cats ) : '';
			$product_tags_items_data = ! empty( $product_tags_items ) ? json_encode( $product_tags_items ) : '';

			echo '<li><a href="javascript:;" data-taxonomy="' . esc_attr( $all_taxonomy ) . '" data-page="1" data-tagid="' . esc_attr( $product_tags_items_data ) . '" data-id="' . esc_attr( $product_cats_data ) .
			     '" class="active post-list-filter-item post-list-cat-'
			     . esc_attr( $this->get_id() ) . '">' .$show_all_cat_thumb. '' . __( $settings[ 'eael_woo_product_gallery_terms_all_text' ], 'essential-addons-for-elementor-lite' ) . '</a></li>';
		}

		// Category and tag retrieve
		$product_categories = $product_tags = [];

		if ( ! empty( $product_cats ) ) {
			$catargs	= array(
				'order'      => 'ASC',
				'hide_empty' => false,
				'include'    => $product_cats,
				'orderby'    => 'include',
			);
			$product_categories = get_terms( 'product_cat', $catargs );

			if ( count( $product_categories ) > 0 ) {
				foreach ( $product_categories as $category ) {
					$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$image_url    = wp_get_attachment_url( $thumbnail_id );

					if ( $show_cat_thumb && $image_url ) {
						$show_cat_thumb_tag = '<img src="' . esc_url( $image_url ) . '" />';
					} else {
						$show_cat_thumb_tag = '';
					}

					echo '<li><a href="javascript:;" data-page="1" data-taxonomy="product_cat" data-terms=\''
						 . json_encode
						 ( [ $category->slug ] ) . '\' data-id="'
						 . $category->term_id . '" class="post-list-filter-item ">' . $show_cat_thumb_tag . '' . $category->name . '</a></li>';
				}
			}
		}

		if ( ! empty( $product_tags_items ) ) {
			$tagargs	= array(
				'order'      => 'ASC',
				'hide_empty' => false,
				'include'    => $product_tags_items,
				'orderby'    => 'include',
			);
			$product_tags = get_terms( 'product_tag', $tagargs );

			if ( count( $product_tags ) > 0 ) {
				foreach ( $product_tags as $product_tag ) {
					$thumbnail_id = get_term_meta( $product_tag->term_id, 'thumbnail_id', true );
					$image_url    = wp_get_attachment_url( $thumbnail_id );

					if ( $show_cat_thumb && $image_url ) {
						$show_cat_thumb_tag = '<img src="' . esc_url( $image_url ) . '" />';
					} else {
						$show_cat_thumb_tag = '';
					}

					echo '<li><a href="javascript:;" data-page="1" data-taxonomy="product_tag" data-terms=\''
						 . json_encode
						 ( [ $product_tag->slug ] ) . '\' data-id="'
						 . $product_tag->term_id . '" class="post-list-filter-item ">' . $show_cat_thumb_tag . '' . $product_tag->name . '</a></li>';
				}
			}
		}

		echo '</ul>';
	}
}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as ClassesHelper;
use Essential_Addons_Elementor\Traits\Helper;

#[\AllowDynamicProperties]
class Woo_Product_List extends Widget_Base
{
    use Helper;

    protected $settings = [];
    protected $woo_product_list_settings = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists('woocommerce')) {
		    $this->load_quick_view_asset();
		}
	}

    public function get_name()
    {
        return 'eael-woo-product-list';
    }

    public function get_title()
    {
        return esc_html__('Woo Product List', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-woo-product-list';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'woo',
            'woocommerce',
            'ea woocommerce',
            'ea woo product list',
            'ea woocommerce product list',
            'product gallery',
            'woocommerce list',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function has_widget_inner_wrapper(): bool {
        return ! ClassesHelper::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/ea-woo-product-list/';
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends()
    {
        return [
            'font-awesome-4-shim',
        ];
    }

    public function add_to_cart_button_custom_text( $default ) {
        $text = $default;
        $woo_product_list = $this->woo_product_list_settings;
        
        if ( ! empty( $woo_product_list['add_to_cart_custom_text_show'] ) ) {
            global $product;

            switch ( $product->get_type() ) {
                case 'external':
                    $text = $woo_product_list['add_to_cart_external_text'];
                    break;

                case 'grouped':
                    $text = $woo_product_list['add_to_cart_grouped_text'];
                    break;

                case 'simple':
                    if ( ! $product->is_in_stock() ) {
                        $text = $woo_product_list['add_to_cart_default_text'];
                    } else {
                        $text = $woo_product_list['add_to_cart_simple_text'];
                    }
                    break;

                case 'variable':
                    $text = $woo_product_list['add_to_cart_variable_text'];
                    break;

                default:
                    $text = $woo_product_list['add_to_cart_default_text'];
                    break;
            }
        }

	    if( 'Read more' === $default ) {
		    $text = esc_html__( 'View More', 'essential-addons-for-elementor-lite' );
	    }

        return $text;
    }

    protected function eael_get_product_orderby_options()
    {
        return apply_filters('eael/woo-product-list/orderby-options', [
            'ID'            => __('Product ID', 'essential-addons-for-elementor-lite'),
            'title'         => __('Product Title', 'essential-addons-for-elementor-lite'),
            '_price'        => __('Price', 'essential-addons-for-elementor-lite'),
            '_sku'          => __('SKU', 'essential-addons-for-elementor-lite'),
            'date'          => __('Date', 'essential-addons-for-elementor-lite'),
            'modified'      => __('Last Modified Date', 'essential-addons-for-elementor-lite'),
            'parent'        => __('Parent Id', 'essential-addons-for-elementor-lite'),
            'rand'          => __('Random', 'essential-addons-for-elementor-lite'),
            'menu_order'    => __('Menu Order', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function eael_get_product_filterby_options()
    {
        return apply_filters('eael/woo-product-list/filterby-options', [
            'recent-products'       => esc_html__('Recent Products', 'essential-addons-for-elementor-lite'),
            'featured-products'     => esc_html__('Featured Products', 'essential-addons-for-elementor-lite'),
            'best-selling-products' => esc_html__('Best Selling Products', 'essential-addons-for-elementor-lite'),
            'sale-products'         => esc_html__('Sale Products', 'essential-addons-for-elementor-lite'),
            'top-products'          => esc_html__('Top Rated Products', 'essential-addons-for-elementor-lite'),
            'manual'                => esc_html__('Manual Selection', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function register_controls() {
        $this->init_content_wc_notice_controls();

        if ( !function_exists( 'WC' ) ) {
            return;
        }

        $this->eael_product_list_layout();
        $this->eael_product_list_query();
        $this->eael_product_list_image();
        $this->eael_product_list_content();
        $this->eael_product_list_load_more();

        $this->eael_product_list_container_style();
        $this->eael_product_list_item_style();
        $this->eael_product_list_item_image_style();
        $this->eael_product_list_item_content_style();
        do_action('eael/controls/load_more_button_style', $this);
        $this->eael_product_list_color_typography_style();
        $this->eael_product_list_popup_style();
    }

    protected function init_content_wc_notice_controls() {
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
	}

    protected function eael_product_list_layout() {
        $this->start_controls_section(
            'eael_section_woo_product_list_layouts',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
            ]
        );

        $template_list = $this->get_template_list_for_dropdown(true);
        $layout_options = [];

        if( ! empty( $template_list ) ){
            $image_dir_url = EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/';
            $image_dir_path = EAEL_PLUGIN_PATH . 'assets/admin/images/layout-previews/';
            foreach( $template_list as $key => $label ){
                $image_url = $image_dir_url . 'woo-product-list-' . $key . '.png';
                $image_url =  file_exists( $image_dir_path . 'woo-product-list-' . $key . '.png' ) ? $image_url : $image_dir_url . 'custom-layout.png';
                $layout_options[ $key ] = [
                    'title' => $label,
                    'image' => $image_url
                ];
            }
        }

        $this->add_control(
			'eael_dynamic_template_layout',
			[
				'label'       => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => $layout_options,
				'default'     => 'preset-1',
				'label_block' => true,
                'toggle'      => false,
                'image_choose'=> true,
			]
		);

        $this->add_control(
            'eael_product_list_layout_general_heading',
            [
                'label' => __('General', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

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
                    'yes' => [
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

        $this->add_control(
            'eael_product_list_layout_content_header_heading',
            [
                'label'     => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_badge_show', [
            'label'         => esc_html__('Badge', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_rating_show', [
            'label'         => esc_html__('Rating', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_review_count_show', [
            'label'         => esc_html__('Review Count', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_category_show', [
            'label'         => esc_html__('Category', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control(
            'eael_product_list_layout_content_body_heading',
            [
                'label'     => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_title_show', [
            'label'         => esc_html__('Title', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_excerpt_show', [
            'label'         => esc_html__('Excerpt', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_price_show', [
            'label'         => esc_html__('Price', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control(
            'eael_product_list_layout_content_footer_heading',
            [
                'label'     => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_total_sold_show', [
            'label'         => esc_html__('Total Sold', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => '',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-1',
            ]
        ]);

        $this->add_control('eael_woo_product_list_total_sold_preset_2_3_show', [
            'label'         => esc_html__('Total Sold', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout!' => 'preset-1',
            ]
        ]);

        $this->add_control('eael_woo_product_list_add_to_cart_button_show', [
            'label'         => esc_html__('Add to Cart', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_quick_view_button_show', [
            'label'         => esc_html__('Quick View', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_link_button_show', [
            'label'         => esc_html__('Link', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_query() {
        $this->start_controls_section('eael_section_woo_product_list_query', [
            'label' => esc_html__('Query', 'essential-addons-for-elementor-lite'),
        ]);

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
					'source_archive' => [
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
                    'post_type' => 'source_archive',
                ],
            ]
        );

        if ( ! apply_filters( 'eael/is_plugin_active', 'woocommerce/woocommerce.php' ) ) {
            $this->add_control(
                'ea_woo_product_list_woo_required',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
                    'content_classes' => 'eael-warning',
                ]
            );
        }

        $this->add_control('eael_product_list_product_filter', [
            'label'     => esc_html__('Filter By', 'essential-addons-for-elementor-lite'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'recent-products',
            'options'   => $this->eael_get_product_filterby_options(),
            'condition' => [
              'post_type!' => [ 'source_dynamic', 'source_archive' ]
            ],
        ]);

        $this->add_control('orderby', [
            'label'     => __('Order By', 'essential-addons-for-elementor-lite'),
            'type'      => Controls_Manager::SELECT,
            'options'   => $this->eael_get_product_orderby_options(),
            'default'   => 'date',
            'condition' => [
                'eael_product_list_product_filter!' => [ 'best-selling-products', 'top-products' ],
                'post_type!' => 'source_archive'
            ]
        ]);

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
                'condition' => [
                    'eael_product_list_product_filter!' => [ 'best-selling-products' ],
                ]
			]
		);

        $this->add_control('eael_woo_product_list_products_count', [
            'label'     => __('Count', 'essential-addons-for-elementor-lite'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 4,
            'min'       => 1,
            'max'       => 1000,
            'step'      => 1,
            'separator' => 'before',
            'condition' => [
                'post_type!' => 'source_archive'
            ]
        ]);

        $this->add_control('product_offset', [
            'label'     => __('Offset', 'essential-addons-for-elementor-lite'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 0,
            'condition' => [
                'eael_product_list_product_filter!' => 'manual',
                'post_type!' => 'source_archive'
            ],
        ]);

        $this->add_control(
            'eael_product_list_products_status',
            [
                'label'         => __( 'Status', 'essential-addons-for-elementor-lite' ),
                'type'          => Controls_Manager::SELECT2,
                'label_block'   => true,
                'multiple'      => true,
                'default'       => [ 'publish', 'pending', 'future' ],
                'options'       => $this->eael_get_product_statuses(),
                'condition'     => [
                    'eael_product_list_product_filter!' => 'manual',
                    'post_type!' => 'source_archive'
                ],
                'separator'     => 'before',
            ]
        );

        $this->add_control('eael_product_list_categories', [
            'label'         => esc_html__('Categories', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::SELECT2,
            'label_block'   => true,
            'multiple'      => true,
            'options'       => ClassesHelper::get_terms_list('product_cat', 'slug'),
            'condition'     => [
              'post_type!'                          => [ 'source_dynamic', 'source_archive' ],
              'eael_product_list_product_filter!'   => 'manual'
            ],
        ]);

        $this->add_control('eael_product_list_products_in', [
            'label'         => esc_html__('Select Products', 'essential-addons-for-elementor-lite'),
            'type'          => 'eael-select2',
            'label_block'   => true,
            'multiple'      => true,
            'source_name'   => 'post_type',
            'source_type'   => 'product',
            'condition'     => [
                'post_type!'                        => [ 'source_dynamic', 'source_archive' ],
                'eael_product_list_product_filter'  => 'manual'
            ],
        ]);

        $this->add_control('product_type_logged_users', [
            'label' => __('Product Type', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'description' => __('For logged in users only!', 'essential-addons-for-elementor-lite'),
            'options' => [
                'both' => __('Both', 'essential-addons-for-elementor-lite'),
                'purchased' => __('Purchased Only', 'essential-addons-for-elementor-lite'),
                'not-purchased' => __('Not Purchased Only', 'essential-addons-for-elementor-lite'),
            ],
            'default' => 'both',
            'condition'     => [
                'post_type!'                        => [ 'source_dynamic', 'source_archive' ],
            ]
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_image() {
        $this->start_controls_section('eael_section_woo_product_list_image', [
            'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'eael_product_list_image_size',
                'exclude'     => ['custom'],
                'default'     => 'medium',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'eael_product_list_image_clickable',
            [
                'label'         => esc_html__('Clickable', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );

        $this->add_control(
            'eael_product_list_image_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'image-alignment-left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'image-alignment-right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_list_content() {
        $this->start_controls_section('eael_section_woo_product_list_content', [
            'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_product_list_content_general_heading',
            [
                'label' => __('General', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_content_general_button_position',
            [
                'label'         => __( 'Buttons', 'essential-addons-for-elementor-lite' ),
                'description'   => __( 'Add to Cart, Quick View, Link buttons/icons on content footer or on image hover or in both positions', 'essential-addons-for-elementor-lite' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'both',
                'options'       => [
                    'both'          => esc_html__( 'Both', 'essential-addons-for-elementor-lite' ),
                    'static'        => esc_html__( 'Static', 'essential-addons-for-elementor-lite' ),
                    'on-hover'      => esc_html__( 'On Hover', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_heading',
            [
                'label'     => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
			'eael_product_list_content_header_position',
			[
				'label'       => __( 'Position', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
                'description' => __( 'Whether to show content header before or after the title', 'essential-addons-for-elementor-lite' ),
				'options'     => [
					'before-title' => [
						'title' => esc_html__( 'Before Title', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-top',
					],
					'after-title' => [
						'title' => esc_html__( 'After Title', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'before-title',
				'toggle'    => false, 
                'condition' => [
				    'eael_dynamic_template_layout' => 'preset-1',
			    ],
			]
		);

        $this->add_control(
			'eael_product_list_content_header_position_preset_2_3',
			[
				'label'       => __( 'Position', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
                'description'   => __( 'Content header items before or after the title', 'essential-addons-for-elementor-lite' ),
				'options'     => [
					'before-title' => [
						'title' => esc_html__( 'Before Title', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-top',
					],
					'after-title' => [
						'title' => esc_html__( 'After Title', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'after-title',
				'toggle'    => false, 
                'condition' => [
				    'eael_dynamic_template_layout!' => 'preset-1',
			    ],
			]
		);

        $this->add_control(
			'eael_product_list_content_header_direction',
			[
				'label'       => __( 'Direction', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
                'description'   => __( 'Content header items alignment', 'essential-addons-for-elementor-lite' ),
				'options'     => [
					'ltr' => [
						'title' => esc_html__( 'Left to Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-arrow-right',
					],
					'rtl' => [
						'title' => esc_html__( 'Right to Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-arrow-left',
					],
				],
				'default'   => 'ltr',
				'toggle'    => false,
			]
		);

        $this->add_control(
            'eael_product_list_content_header_badge_heading',
            [
                'label'         => __('Badge', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::HEADING,
                'condition'     => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_badge_preset',
            [
                'label'   => __( 'Preset', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'badge-preset-1',
                'options' => [
                    'badge-preset-1'  => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
                    'badge-preset-2'  => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
                    'badge-preset-3'  => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
				    'eael_woo_product_list_badge_show' => 'yes',
				    'eael_dynamic_template_layout!' => 'preset-2',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_badge_preset_2',
            [
                'label'   => __( 'Preset', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'badge-preset-2',
                'options' => [
                    'badge-preset-1'  => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
                    'badge-preset-2'  => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
                    'badge-preset-3'  => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
				    'eael_woo_product_list_badge_show' => 'yes',
				    'eael_dynamic_template_layout' => 'preset-2',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_badge_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'badge-alignment-left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'badge-alignment-right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_list_content_header_badge_sale_text',
		    [
			    'label'       => esc_html__( 'Sale Text', 'essential-addons-for-elementor-lite' ),
			    'default'     => esc_html__( 'Sale', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
                'ai' => [
					'active' => false,
				],
                'condition'     => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ]
		    ]
	    );

	    $this->add_control(
		    'eael_product_list_content_header_badge_stock_out_text',
		    [
			    'label'       => esc_html__( 'Stock Out Text', 'essential-addons-for-elementor-lite' ),
			    'default'     => esc_html__( 'Stock Out', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
                'ai' => [
					'active' => false,
				],
                'condition'     => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ]
		    ]
	    );

        $this->add_control(
            'eael_product_list_content_body_heading',
            [
                'label'     => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
			'eael_product_list_content_body_title_tag',
			[
				'label'       => __( 'HTML Tag', 'essential-addons-for-elementor-lite' ),
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
				'default'   => 'h2',
				'toggle'    => false,
                'condition' => [
				    'eael_woo_product_list_title_show' => 'yes',
			    ],
			]
		);

        $this->add_control(
            'eael_product_list_content_body_title_clickable',
            [
                'label'         => esc_html__('Clickable', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'yes',
                'default'       => 'yes',
                'condition'     => [
				    'eael_woo_product_list_title_show' => 'yes',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_excerpt_heading',
            [
                'label' => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_excerpt_words_count',
            [
                'label'     => __( 'Words Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '30',
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_content_body_excerpt_expanison_indicator',
            [
                'label'       => esc_html__( 'Expansion Indicator', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => '...',
                'condition'   => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_heading',
            [
                'label'     => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_heading',
            [
                'label'         => __('Total Sold', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::HEADING,
                'conditions'    => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_remaining_show',
            [
                'label'         => __('Remaining', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'     => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value'  => 'yes',
                'default'       => 'yes',
                'conditions'    => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_text', 
            [
                'label'         => esc_html__('Total Sold Text', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => false,
                'default'       => esc_html__('Total Sold:', 'essential-addons-for-elementor-lite'),
                'conditions'    => $this->total_sold_conditions(),
                'ai'            => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_remaining_text', 
            [
                'label'         => esc_html__('Remaining Text', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => false,
                'default'       => esc_html__('Remaining:', 'essential-addons-for-elementor-lite'),
                'conditions'    => $this->total_sold_conditions(),
                'ai'            => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_heading',
            [
                'label'         => __('Add to Cart', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::HEADING,
                'condition'     => [
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_custom_text_show',
            [
                'label'         => __('Custom Text', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'     => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_simple_text',
            [
                'label'         => esc_html__('Simple Product', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => false,
                'default'       => esc_html__('Buy Now', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show'                  => 'yes',
                    'eael_product_list_content_footer_add_to_cart_custom_text_show' => 'yes',
                ],
                'ai'            => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_variable_text',
            [
                'label'         => esc_html__('Variable Product', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => false,
                'default'       => esc_html__('Select options', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show'                  => 'yes',
                    'eael_product_list_content_footer_add_to_cart_custom_text_show' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_grouped_text',
            [
                'label'         => esc_html__('Grouped Product', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => false,
                'default'       => esc_html__('View products', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show'                  => 'yes',
                    'eael_product_list_content_footer_add_to_cart_custom_text_show' => 'yes',
                ],
                'ai'            => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_external_text',
            [
                'label'         => esc_html__('External Product', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => false,
                'default'       => esc_html__('Buy Now', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show'                  => 'yes',
                    'eael_product_list_content_footer_add_to_cart_custom_text_show' => 'yes',
                ],
                'ai'            => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_default_text',
            [
                'label'         => esc_html__('Default Product', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => false,
                'default'       => esc_html__('Read More', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                    'eael_product_list_content_footer_add_to_cart_custom_text_show' => 'yes',
                ],
                'ai'            => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_quick_view_heading',
            [
                'label'         => __('Quick View', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::HEADING,
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'eael_product_quick_view_title_tag',
			[
				'label'       => __( 'Title Tag', 'essential-addons-for-elementor-lite' ),
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
                'condition' => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_quick_view_text', 
            [
                'label'         => esc_html__('Button Text', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => false,
                'default'       => esc_html__('View Product', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
                'ai'            => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_not_found_heading',
            [
                'label'         => __('Not Found', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::HEADING,
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_not_found_text', 
            [
                'label'         => esc_html__('Products Not Found', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => false,
                'default'       => esc_html__('No products found!', 'essential-addons-for-elementor-lite'),
                'condition'     => [
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
                'ai'            => [
                    'active' => false,
                ],
            ]
        );

        $this->end_controls_section();
    }
    
    protected function eael_product_list_load_more() {
        $this->start_controls_section('eael_section_woo_product_list_load_more', [
            'label' => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
            'condition' => [
                'show_load_more' => 'yes',
            ],
        ]);

        $this->add_control('show_load_more_text', [
            'label'         => esc_html__('Button Text', 'essential-addons-for-elementor-lite'),
            'type'          => Controls_Manager::TEXT,
            'label_block'   => false,
            'default'       => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
            'condition'     => [
                'show_load_more' => 'yes',
            ],
            'ai'            => [
                'active' => false,
            ],
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_container_style() {

	    $this->start_controls_section(
			'eael_section_product_list_container_style',
			[
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_list_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_list_container_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
        
		$this->add_control(
			'eael_product_list_container_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
                'default'   => '#F4F5F7',
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_container_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_container_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container',
			]
		);

		$this->end_controls_section();
    }
    
    protected function eael_product_list_item_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_style',
			[
				'label' => esc_html__( 'Item', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_list_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
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
					'top'      => 30,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'unit'     => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .product:not(:first-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
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
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_list_item_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_product_list_item_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_item_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_item_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item',
			]
		);

		$this->end_controls_section();
    }

    protected function eael_product_list_item_image_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_image_style',
			[
				'label' => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'eael_product_list_item_image_wrapper_heading_style',
            [
                'label' => __('Image Wrapper', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
		    'eael_product_list_item_image_wrapper_width',
		    [
			    'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    '%'  => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap' => 'width: {{SIZE}}%;',
			    ],
		    ]
	    );

        $this->add_control(
			'eael_product_list_item_image_normal_overlay_color',
			[
				'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap' => 'background: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'eael_product_list_item_image_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
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
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_item_image_heading_style',
            [
                'label' => __('Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_control(
			'eael_product_list_item_image_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
    }

    protected function eael_product_list_item_content_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_content_style',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'eael_product_list_content_wrapper_heading_style',
            [
                'label' => __('Content Wrapper', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
		    'eael_product_list_content_wrapper_width',
		    [
			    'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    '%'  => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-wrap' => 'width: {{SIZE}}%;',
			    ],
		    ]
	    );

        $this->add_responsive_control(
			'eael_product_list_content_wrapper_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_header_heading_style',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control('eael_product_list_content_header_general_custom_spacing', [
            'label' => esc_html__('Custom Spacing', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control( 'eael_product_list_content_body_title_style_info', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( 'Title spacing can be added from Style => Content => Content Body => Title ', 'essential-addons-for-elementor-lite' ),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            'conditions' => $this->content_header_position_conditions(
                [
                'name'     => 'eael_product_list_content_header_general_custom_spacing',
                'operator' => '===',
                'value'    => 'yes',
                ]
            ),
        ] );

		$this->add_responsive_control(
			'eael_product_list_content_header_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_product_list_content_header_general_custom_spacing' => 'yes'
                ],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_product_list_content_header_general_custom_spacing' => 'yes'
                ],
			]
		);

        $this->add_control(
            'eael_product_list_content_body_heading_style',
            [
                'label'     => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_content_body_title_excerpt_price_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_content_body_title_excerpt_price_tabs_title',
            [
                'label' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
            ] 
        );

        $this->add_responsive_control(
			'eael_product_list_content_body_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_content_body_title_excerpt_price_tabs_excerpt',
            [
                'label' => esc_html__( 'Excerpt', 'essential-addons-for-elementor-lite' ),
            ] 
        );

        $this->add_responsive_control(
			'eael_product_list_content_body_excerpt_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_excerpt_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
			]
		);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 
            'eael_product_list_content_body_title_excerpt_price_tabs_price',
            [
                'label' => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
            ] 
        );

        $this->add_responsive_control(
			'eael_product_list_content_body_price_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_price_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
            'eael_product_list_content_footer_heading_style',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_heading_style',
            [
                'label' => __('Total Sold', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions'    => $this->total_sold_conditions(),
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_content_footer_total_sold_progress_height',
            [
                'label' => esc_html__('Progress Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'height: {{SIZE}}px;',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
			'eael_product_list_content_footer_total_sold_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
                'default'   => [
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'border-radius: {{SIZE}}px;',
				],
                'conditions'    => $this->total_sold_conditions(),
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_static_buttons_heading_style',
            [
                'label' => __('Static Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_content_footer_static_buttons_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_content_footer_static_buttons_tabs_add_to_cart',
            [
                'label' => esc_html__( 'Add to Cart', 'essential-addons-for-elementor-lite' ),
            ] 
        );

        $this->add_responsive_control(
			'eael_product_list_content_footer_add_to_cart_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_add_to_cart_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_add_to_cart_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_add_to_cart_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_add_to_cart_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_content_footer_static_buttons_tabs_view_product',
            [
                'label' => esc_html__( 'View Product', 'essential-addons-for-elementor-lite' ),
            ] 
        );

        $this->add_responsive_control(
			'eael_product_list_content_footer_quick_view_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_quick_view_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_quick_view_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_quick_view_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_quick_view_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a',
			]
		);

        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
            'eael_product_list_content_footer_on_hover_buttons_heading_style',
            [
                'label' => __('On Hover Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_footer_on_hover_buttons_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_on_hover_buttons_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_on_hover_buttons_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'border-radius: {{SIZE}}px;',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_on_hover_buttons_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a',
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_on_hover_buttons_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a',
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->end_controls_section();
    }

	protected function eael_product_list_color_typography_style() {

        $this->start_controls_section(
            'eael_section_product_list_color_typography_style',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_header_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_badge_heading',
            [
                'label' => __('Badge', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_badge_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_badge_color_tabs_sale',
            [
                'label' => esc_html__( 'Sale', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_badge_color_sale',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.is-on-sale p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.is-on-sale.badge-preset-3' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_badge_bg_color_sale',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-2.is-on-sale p'  => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-3.is-on-sale'    => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.is-on-sale svg path'          => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-2.is-on-sale.badge-alignment-left::after'    => 'border-right: 10px solid {{VALUE}}; filter: brightness(0.7);',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-2.is-on-sale::before'                        => 'border-bottom: 10px solid {{VALUE}}; filter: brightness(0.7);',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-2.is-on-sale.badge-alignment-right::after'   => 'border-left: 10px solid {{VALUE}}; filter: brightness(0.7);',
                ],
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_badge_typography_sale',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.is-on-sale p',
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                    'eael_product_list_content_header_badge_preset!' => 'badge-preset-2',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_badge_color_tabs_stock_out',
            [
                'label' => esc_html__( 'Stock Out', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_badge_color_stock_out',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.stock-out p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_badge_bg_color_stock_out',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-2.stock-out' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-3.stock-out' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.stock-out svg path'       => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_badge_typography_stock_out',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.stock-out p',
                'condition' => [
                    'eael_woo_product_list_badge_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'eael_product_list_badge_size',
            [
                'label'     => esc_html__('Badge Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'default'   => [
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-1 .eael-product-list-badge-bg svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-badge-wrap.badge-preset-1' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_rating_heading',
            [
                'label' => __('Star Rating', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_rating_color',
            [
                'label' => esc_html__('Rating Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF9900',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating span::before' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_rating_size',
            [
                'label'     => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
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
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating::before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating span::before' => 'font-size: {{SIZE}}px;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating' => 'font-size: {{SIZE}}px;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating::before' => 'font-size: {{SIZE}}px;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating span::before' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_review_count_heading',
            [
                'label'     => __('Review Count', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_review_count_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_review_count_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5F6368',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-review-count' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_review_count_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_review_count_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-review-count',
                'condition' => [
                    'eael_woo_product_list_review_count_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_category_heading',
            [
                'label'     => __('Category', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_category_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_category_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-notice p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-notice p i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_category_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_category_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-notice p',
                'condition' => [
                    'eael_woo_product_list_category_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_body_heading',
            [
                'label' => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_title_heading',
            [
                'label'     => __('Title', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_title_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_title_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_title_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-title a' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .product_title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_title_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                    'eael_product_list_content_body_title_clickable' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_title_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-title a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                    'eael_product_list_content_body_title_clickable' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_title_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-title, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-title a, .eael-popup-details-render{{WRAPPER}} div.product .product_title',
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_excerpt_heading',
            [
                'label' => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5F6368',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-excerpt' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_excerpt_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-excerpt, .eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description',
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_regular_price_heading',
            [
                'label' => __( 'Regular Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_regular_price_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#757C86',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price del' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_regular_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price del, .eael-popup-details-render{{WRAPPER}} div.product .price',
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_sale_price_heading',
            [
                'label' => __( 'Sale Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_sale_price_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price ins' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price > .amount' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_sale_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price ins, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price > .amount, .eael-popup-details-render{{WRAPPER}} div.product .price ins',
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_footer_heading',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_heading',
            [
                'label' => __('Total Sold', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_total_sold_text_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_total_sold_text',
            [
                'label' => esc_html__( 'Total Sold', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->total_sold_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_color',
            [
                'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_count_color',
            [
                'label'     => esc_html__( 'Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count span' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_total_sold_remaining_text',
            [
                'label' => esc_html__( 'Remaining', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->total_sold_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_remaining_color',
            [
                'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_remaining_count_color',
            [
                'label'     => esc_html__( 'Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining span' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
            'eael_product_list_color_typography_total_sold_progress_color',
            [
                'label'     => esc_html__( 'Progress Outer', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#EFE4E4',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_progress_inner_color',
            [
                'label'     => esc_html__( 'Progress Inner', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#C29F9D',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_total_sold_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining',
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_heading',
            [
                'label' => __('Add to Cart', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_add_to_cart_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_add_to_cart_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'background: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_add_to_cart_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a:hover' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a:hover' => 'background: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_add_to_cart_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a, .eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_buttons_quick_view_heading',
            [
                'label' => __('Quick View', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_quick_view_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_quick_view_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_quick_view_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_quick_view_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_quick_view_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a',
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_heading',
            [
                'label' => __('On Hover Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_on_hover_buttons_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_on_hover_buttons_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->on_hover_buttons_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_on_hover_buttons_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->on_hover_buttons_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:hover' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:hover' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_on_hover_buttons_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:not(.add_to_cart_button), {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li .add_to_cart_button::before',
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_list_popup_style() {

        $this->start_controls_section(
            'eael_product_popup',
            [
                'label' => __('Popup', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
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
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus' => 'border-color: {{VALUE}};',
			    ],
		    ]
	    );

        // Cart Button
        $this->add_control(
            'eael_product_popup_cart_button',
            [
                'label' => __('Cart Button', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('eael_product_popup_cart_button_style_tabs');

        $this->start_controls_tab('eael_product_popup_cart_button_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

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
		    'eael_product_popup_cart_button_background_preset_1',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#758F4D',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
			    ],
                'condition'     => [
                    'eael_dynamic_template_layout' => 'preset-1',
                ]
		    ]
	    );

        $this->add_control(
		    'eael_product_popup_cart_button_background_preset_2',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#BC5C5C',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
			    ],
                'condition'     => [
                    'eael_dynamic_template_layout' => 'preset-2',
                ]
		    ]
	    );

        $this->add_control(
		    'eael_product_popup_cart_button_background_preset_3',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#A66C46',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
			    ],
                'condition'     => [
                    'eael_dynamic_template_layout' => 'preset-3',
                ]
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

        $this->start_controls_tab('eael_product_popup_cart_button_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

	    $this->add_control(
		    'eael_product_popup_cart_button_hover_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
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
			    'default'   => '#000',
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
                'label' => __('SKU', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
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
			    'size_units' => ['px', 'em', '%'],
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
			    'size_units' => ['px', 'em', '%'],
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
			    'size_units' => ['px', '%'],
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
			    'size_units' => ['px', '%'],
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
			    'types'    => ['classic', 'gradient'],
			    'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
			    'exclude'  => [
				    'image',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_product_popup_box_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup .eael-product-popup-details',
            ]
        );

        $this->end_controls_section();
    }

    protected function on_hover_buttons_conditions(){
        $conditions =   
        [
            'relation' => 'and',
            'terms' => [
                [
                    'name' => 'eael_product_list_content_general_button_position',
                    'operator' => '!==',
                    'value' => 'static',
                ],
                [
                'relation' => 'or',
                'terms' => [
                        [
                            'name' => 'eael_woo_product_list_add_to_cart_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'eael_woo_product_list_quick_view_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'eael_woo_product_list_link_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ],
        ];
        
        return $conditions;
    }
    
    protected function total_sold_conditions(){
        $conditions =
        [
            'relation' => 'or',
            'terms' => [
                [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'eael_woo_product_list_total_sold_show',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'eael_dynamic_template_layout',
                            'operator' => '==',
                            'value'    => 'preset-1',
                        ],
                    ]
                ],
                [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'eael_woo_product_list_total_sold_preset_2_3_show',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'eael_dynamic_template_layout',
                            'operator' => '!==',
                            'value'    => 'preset-1',
                        ],
                    ]
                ],
            ],
        ];
        
        return $conditions;
    }
    
    protected function content_header_position_conditions( $extra_conditions = [] ){
        $conditions_wrap = [];
        $conditions =
        [
            'relation' => 'or',
            'terms' => [
                [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'eael_product_list_content_header_position',
                            'operator' => '===',
                            'value'    => 'after-title',
                        ],
                        [
                            'name'     => 'eael_dynamic_template_layout',
                            'operator' => '==',
                            'value'    => 'preset-1',
                        ],
                    ]
                ],
                [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'eael_product_list_content_header_position_preset_2_3',
                            'operator' => '===',
                            'value'    => 'after-title',
                        ],
                        [
                            'name'     => 'eael_dynamic_template_layout',
                            'operator' => '!==',
                            'value'    => 'preset-1',
                        ],
                    ]
                ],
            ],
        ];

        if ( is_array( $extra_conditions ) && count( $extra_conditions ) ) {
            $conditions_wrap['relation'] = 'and';
            $conditions_wrap['terms'][] = $conditions;
            $conditions_wrap['terms'][] = $extra_conditions;
        }
        
        return ! empty( $conditions_wrap ) ? $conditions_wrap : $conditions;
    }

    protected function eael_get_product_statuses() {
        return apply_filters( 'eael/woo-woo-product-list/product-statuses', [
            'publish'       => esc_html__( 'Publish', 'essential-addons-for-elementor-lite' ),
            'draft'         => esc_html__( 'Draft', 'essential-addons-for-elementor-lite' ),
            'pending'       => esc_html__( 'Pending Review', 'essential-addons-for-elementor-lite' ),
            'future'        => esc_html__( 'Schedule', 'essential-addons-for-elementor-lite' ),
        ] );
    }

    public function load_quick_view_asset(){
	    add_action('wp_footer',function (){
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
	    });
    }

    public static function eael_print_produt_badge_html( $woo_product_list, $product ){
        $badge_text = ''; 
        $stock_onsale_class = '';
        
        if ( ! $product->is_in_stock() ) {
            $badge_text = $woo_product_list['stock_out_text']; 
            $stock_onsale_class = esc_html('stock-out'); 

        } elseif ( $product->is_on_sale() ) {
            $badge_text = $woo_product_list['sale_text'];
            $stock_onsale_class = esc_html('is-on-sale'); 
        }

        if ( $woo_product_list['badge_show'] && ! empty( $badge_text ) ) :
            
            switch( $woo_product_list['badge_preset'] ){
                case 'badge-preset-1':
                    ?>
                    <div class="eael-product-list-badge-wrap badge-preset-1 <?php echo esc_attr( $woo_product_list['badge_alignment_class'] ) ?> <?php echo esc_attr( $stock_onsale_class ) ?>">
                        <div class="eael-product-list-badge-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                                <path d="M50 0L59.861 13.1982L75 6.69873L76.9408 23.0592L93.3013 25L86.8018 40.139L100 50L86.8018 59.861L93.3013 75L76.9408 76.9408L75 93.3013L59.861 86.8018L50 100L40.139 86.8018L25 93.3013L23.0592 76.9408L6.69873 75L13.1982 59.861L0 50L13.1982 40.139L6.69873 25L23.0592 23.0592L25 6.69873L40.139 13.1982L50 0Z" fill="#DBEC73"/>
                                </svg>
                        </div>
                        <p><strong><?php echo esc_html( $badge_text ); ?></strong></p>
                    </div>
                    <?php
                    break;

                case 'badge-preset-2':
                    ?>
                    <div class="eael-product-list-badge-wrap badge-preset-2 <?php echo esc_attr( $woo_product_list['badge_alignment_class'] ) ?> <?php echo esc_attr( $stock_onsale_class ) ?> ">
                        <p><?php echo esc_html( $badge_text ); ?></p>
                    </div>
                    <?php
                    break;

                case 'badge-preset-3':
                    ?>
                    <div class="eael-product-list-badge-wrap badge-preset-3 <?php echo esc_attr( $woo_product_list['badge_alignment_class'] ) ?> <?php echo esc_attr( $stock_onsale_class ) ?> ">
                        <p><?php echo esc_html( $badge_text ); ?></p>
                    </div>
                    <?php
                    break;
                    
                default:
                    ?>
                    <div class="eael-product-list-badge-wrap badge-preset-1 <?php echo esc_attr( $woo_product_list['badge_alignment_class'] ) ?> <?php echo esc_attr( $stock_onsale_class ) ?> ">
                        <div class="eael-product-list-badge-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                                <path d="M50 0L59.861 13.1982L75 6.69873L76.9408 23.0592L93.3013 25L86.8018 40.139L100 50L86.8018 59.861L93.3013 75L76.9408 76.9408L75 93.3013L59.861 86.8018L50 100L40.139 86.8018L25 93.3013L23.0592 76.9408L6.69873 75L13.1982 59.861L0 50L13.1982 40.139L6.69873 25L23.0592 23.0592L25 6.69873L40.139 13.1982L50 0Z" fill="#DBEC73"/>
                                </svg>
                        </div>
                        <p><strong><?php echo esc_html($badge_text); ?></strong></p>
                    </div>
                    <?php
                    break;
            }
        endif;
    }

    public static function eael_print_product_title_html( $woo_product_list, $product ){
        if ( $woo_product_list['title_show'] ) : 
            $title_tag = ClassesHelper::eael_validate_html_tag( $woo_product_list['title_tag'] );
        ?>
            <<?php echo esc_html( $title_tag );  ?> class="eael-product-list-title">
                <?php if ( $woo_product_list['title_clickable'] ) : ?>
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" target="_blank">
                    <?php echo wp_kses( $product->get_title(), ClassesHelper::eael_allowed_tags() ); ?>
                </a>
                <?php else : ?>
                    <?php echo wp_kses( $product->get_title(), ClassesHelper::eael_allowed_tags() ); ?>
                <?php endif; ?>
            </<?php echo esc_html( $title_tag );  ?>>
        <?php 
        endif;
    }

    public static function eael_get_product_category_name( $terms ){
        $category_name = '';

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $category_name = 'uncategorized' !== $terms[0]->slug ? $terms[0]->name : '';
        }

        return $category_name;
    }

    public static function get_woo_product_list_loop_settings( $product, $settings, $woo_product_list ) {
		$woo_product_list_loop  = [];
        $product_id             = $product->get_id();

        $woo_product_list_loop['quick_view_setting'] = [
            'widget_id'     => $settings['eael_widget_id'],
            'product_id'    => $product_id,
            'page_id'       => $settings['eael_page_id'],
        ];
        
        $woo_product_list_loop['direction_rtl_class']            = $woo_product_list['content_header_direction_rtl'] ? 'eael-direction-rtl' : '';
        
        $woo_product_list_loop['total_sales_count']              = intval( get_post_meta( $product_id, 'total_sales', true ) );
        $woo_product_list_loop['stock_quantity_count']           = intval( $product->get_stock_quantity() );
        
        $woo_product_list_loop['progress_cent_percentage']       = $woo_product_list_loop['total_sales_count'] + $woo_product_list_loop['stock_quantity_count'];
        $woo_product_list_loop['total_sold_progress_percentage'] = $woo_product_list_loop['progress_cent_percentage'] > 0 ? intval( ( $woo_product_list_loop['total_sales_count'] / $woo_product_list_loop['progress_cent_percentage'] ) * 100 ) : 100; // in percentage
        
        $woo_product_list_loop['review_count']                   = intval( $product->get_review_count() );
        
        $woo_product_list_loop['terms']         = get_the_terms( $product_id, 'product_cat' ); 
        $woo_product_list_loop['has_terms']     = 0;
        if ( ! empty( $woo_product_list_loop['terms'] ) && ! is_wp_error( $woo_product_list_loop['terms'] ) ) {
            $woo_product_list_loop['has_terms'] = 'uncategorized' !== $woo_product_list_loop['terms'][0]->slug ? 1 : 0;
        }

        return $woo_product_list_loop;
	}

    public static function get_woo_product_list_settings( $settings ) {
		$woo_product_list 					        = [];
		$woo_product_list['layout'] 		        = ! empty( $settings['eael_dynamic_template_layout'] ) ? $settings['eael_dynamic_template_layout'] : 'preset-1';
		
        $woo_product_list['badge_show']             = ! empty( $settings['eael_woo_product_list_badge_show'] ) && 'yes' === $settings['eael_woo_product_list_badge_show'] ? 1 : 0;
        $woo_product_list['rating_show']            = ! empty( $settings['eael_woo_product_list_rating_show'] ) && 'yes' === $settings['eael_woo_product_list_rating_show'] ? 1 : 0;
        $woo_product_list['review_count_show']      = ! empty( $settings['eael_woo_product_list_review_count_show'] ) && 'yes' === $settings['eael_woo_product_list_review_count_show'] ? 1 : 0;
        $woo_product_list['category_show']          = ! empty( $settings['eael_woo_product_list_category_show'] ) && 'yes' === $settings['eael_woo_product_list_category_show'] ? 1 : 0;
		$woo_product_list['title_show']             = ! empty( $settings['eael_woo_product_list_title_show'] ) && 'yes' === $settings['eael_woo_product_list_title_show'] ? 1 : 0;
		$woo_product_list['excerpt_show']           = ! empty( $settings['eael_woo_product_list_excerpt_show'] ) && 'yes' === $settings['eael_woo_product_list_excerpt_show'] ? 1 : 0;
		$woo_product_list['price_show']             = ! empty( $settings['eael_woo_product_list_price_show'] ) && 'yes' === $settings['eael_woo_product_list_price_show'] ? 1 : 0;
		$woo_product_list['total_sold_show']                = ! empty( $settings['eael_woo_product_list_total_sold_show'] ) && 'yes' === $settings['eael_woo_product_list_total_sold_show'] ? 1 : 0;
		$woo_product_list['add_to_cart_button_show']        = ! empty( $settings['eael_woo_product_list_add_to_cart_button_show'] ) && 'yes' === $settings['eael_woo_product_list_add_to_cart_button_show'] ? 1 : 0;
		$woo_product_list['quick_view_button_show']         = ! empty( $settings['eael_woo_product_list_quick_view_button_show'] ) && 'yes' === $settings['eael_woo_product_list_quick_view_button_show'] ? 1 : 0;
		$woo_product_list['link_button_show']               = ! empty( $settings['eael_woo_product_list_link_button_show'] ) && 'yes' === $settings['eael_woo_product_list_link_button_show'] ? 1 : 0;
		$woo_product_list['show_load_more']                 = ! empty( $settings['show_load_more'] ) && 'yes' === $settings['show_load_more'] ? 1 : 0;
		
		$woo_product_list['badge_preset']                   = ! empty( $settings['eael_product_list_content_header_badge_preset'] ) ? esc_html( $settings['eael_product_list_content_header_badge_preset'] ) : esc_html('badge-preset-1');
		$woo_product_list['badge_alignment_class']          = ! empty( $settings['eael_product_list_content_header_badge_alignment'] ) ? esc_html( $settings['eael_product_list_content_header_badge_alignment'] ) : esc_html('badge-alignment-left');
		$woo_product_list['sale_text']                      = ! empty( $settings['eael_product_list_content_header_badge_sale_text'] ) ? esc_html( $settings['eael_product_list_content_header_badge_sale_text'] ) : esc_html__( 'Sale', 'essential-addons-for-elementor-lite' );
		$woo_product_list['stock_out_text']                 = ! empty( $settings['eael_product_list_content_header_badge_stock_out_text'] ) ? esc_html( $settings['eael_product_list_content_header_badge_stock_out_text'] ) : esc_html__( 'Stock Out', 'essential-addons-for-elementor-lite' );
		$woo_product_list['image_size']                     = ! empty( $settings['eael_product_list_image_size_size'] ) ? esc_html( $settings['eael_product_list_image_size_size'] ) : esc_html( 'medium' );
		$woo_product_list['image_clickable']                = ! empty( $settings['eael_product_list_image_clickable'] ) && 'yes' === $settings['eael_product_list_image_clickable'] ? 1 : 0;
		$woo_product_list['image_alignment']                = ! empty( $settings['eael_product_list_image_alignment'] ) ? esc_html( $settings['eael_product_list_image_alignment'] ) : '';
        $woo_product_list['button_position_static']         = ! empty( $settings['eael_product_list_content_general_button_position'] ) && 'on-hover' !== $settings['eael_product_list_content_general_button_position'] ? 1 : 0;
        $woo_product_list['button_position_on_hover']       = ! empty( $settings['eael_product_list_content_general_button_position'] ) && 'static' !== $settings['eael_product_list_content_general_button_position'] ? 1 : 0;
        $woo_product_list['content_header_position']        = ! empty( $settings['eael_product_list_content_header_position'] ) ? esc_html( $settings['eael_product_list_content_header_position'] ) : esc_html( 'before-title' );
		$woo_product_list['content_header_direction_rtl']   = ! empty( $settings['eael_product_list_content_header_direction'] ) && 'rtl' === $settings['eael_product_list_content_header_direction'] ? 1 : 0;
		$woo_product_list['title_tag']                      = ! empty( $settings['eael_product_list_content_body_title_tag'] ) ? ClassesHelper::eael_validate_html_tag( $settings['eael_product_list_content_body_title_tag'] ) : 'div';
		$woo_product_list['title_clickable']                = ! empty( $settings['eael_product_list_content_body_title_clickable'] ) && 'yes' === $settings['eael_product_list_content_body_title_clickable'] ? 1 : 0;
		$woo_product_list['excerpt_words_count']            = ! empty( $settings['eael_product_list_content_body_excerpt_words_count'] ) ? intval( $settings['eael_product_list_content_body_excerpt_words_count'] ) : 0;
		$woo_product_list['excerpt_expanison_indicator']    = ! empty( $settings['eael_product_list_content_body_excerpt_expanison_indicator'] ) ? esc_html( $settings['eael_product_list_content_body_excerpt_expanison_indicator'] ) : esc_html('...');
		$woo_product_list['total_sold_remaining_show']      = ! empty( $settings['eael_product_list_content_footer_total_sold_remaining_show'] ) && 'yes' === $settings['eael_product_list_content_footer_total_sold_remaining_show'] ? 1 : 0;
		$woo_product_list['total_sold_text']                = ! empty( $settings['eael_product_list_content_footer_total_sold_text'] ) ? esc_html( $settings['eael_product_list_content_footer_total_sold_text'] ) : esc_html__( 'Total Sold:', 'essential-addons-for-elementor-lite' );
		$woo_product_list['total_sold_remaining_text']      = ! empty( $settings['eael_product_list_content_footer_total_sold_remaining_text'] ) ? esc_html( $settings['eael_product_list_content_footer_total_sold_remaining_text'] ) : esc_html__( 'Remaining:', 'essential-addons-for-elementor-lite' );
		$woo_product_list['quick_view_text']                = ! empty( $settings['eael_product_list_content_footer_quick_view_text'] ) ? esc_html( $settings['eael_product_list_content_footer_quick_view_text'] ) : esc_html__( 'View Product', 'essential-addons-for-elementor-lite' );
		$woo_product_list['products_not_found_text']        = ! empty( $settings['eael_product_list_content_footer_not_found_text'] ) ? esc_html( $settings['eael_product_list_content_footer_not_found_text'] ) : esc_html__( 'No products found!', 'essential-addons-for-elementor-lite' );
		
        $woo_product_list['add_to_cart_custom_text_show']   = ! empty( $settings['eael_product_list_content_footer_add_to_cart_custom_text_show'] ) && 'yes' ===  $settings['eael_product_list_content_footer_add_to_cart_custom_text_show'] ? 1 : 0;
	    $woo_product_list['add_to_cart_simple_text']        = ! empty( $settings['eael_product_list_content_footer_add_to_cart_simple_text'] ) ? esc_html( $settings['eael_product_list_content_footer_add_to_cart_simple_text'] ) : esc_html__( 'Buy Now', 'essential-addons-for-elementor-lite' );
	    $woo_product_list['add_to_cart_variable_text']      = ! empty( $settings['eael_product_list_content_footer_add_to_cart_variable_text'] ) ? esc_html( $settings['eael_product_list_content_footer_add_to_cart_variable_text'] ) : esc_html__( 'Buy Now', 'essential-addons-for-elementor-lite' );
	    $woo_product_list['add_to_cart_grouped_text']       = ! empty( $settings['eael_product_list_content_footer_add_to_cart_grouped_text'] ) ? esc_html( $settings['eael_product_list_content_footer_add_to_cart_grouped_text'] ) : esc_html__( 'Buy Now', 'essential-addons-for-elementor-lite' );
	    $woo_product_list['add_to_cart_external_text']      = ! empty( $settings['eael_product_list_content_footer_add_to_cart_external_text'] ) ? esc_html( $settings['eael_product_list_content_footer_add_to_cart_external_text'] ) : esc_html__( 'Buy Now', 'essential-addons-for-elementor-lite' );
	    $woo_product_list['add_to_cart_default_text']       = ! empty( $settings['eael_product_list_content_footer_add_to_cart_default_text'] ) ? esc_html( $settings['eael_product_list_content_footer_add_to_cart_default_text'] ) : esc_html__( 'Buy Now', 'essential-addons-for-elementor-lite' );

        if( 'preset-2' === $woo_product_list['layout'] || 'preset-3' === $woo_product_list['layout'] ){
            $woo_product_list['content_header_position']    = ! empty( $settings['eael_product_list_content_header_position_preset_2_3'] ) ? esc_html( $settings['eael_product_list_content_header_position_preset_2_3'] ) : esc_html( 'after-title' );
            $woo_product_list['total_sold_show']    = ! empty( $settings['eael_woo_product_list_total_sold_preset_2_3_show'] ) && 'yes' === esc_html( $settings['eael_woo_product_list_total_sold_preset_2_3_show'] ) ? 1 : 0;
        }

        if( 'preset-2' === $woo_product_list['layout'] ){
		    $woo_product_list['badge_preset']   = ! empty( $settings['eael_product_list_content_header_badge_preset_2'] ) ? esc_html( $settings['eael_product_list_content_header_badge_preset_2'] ) : esc_html('badge-preset-2');
        }

        return $woo_product_list;
	}

    /**
     * Prepare product query
     * @param $settings
     * @return array
     */
    public function eael_prepare_product_query( $settings ) {
        $args = [
            'post_type'         => 'product',
            'order'             => ! empty( $settings['order'] )  ? sanitize_text_field( $settings['order'] ) : 'desc',
            'post_status'       => ! empty( $settings['eael_product_list_products_status'] ) ? $settings['eael_product_list_products_status'] : [ 'publish', 'pending', 'future' ],
            'posts_per_page'    => ! empty( $settings['eael_woo_product_list_products_count'] )  ? intval( $settings['eael_woo_product_list_products_count'] ) : 4,
            'offset'            => ! empty( $settings['product_offset'] )  ? intval( $settings['product_offset'] ) : 0,
            'tax_query' => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                    'operator' => 'NOT IN',
                ],
            ],
        ];

        // Order by
        if ( '_price' === $settings['orderby'] ) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
        } else if ( '_sku' === $settings['orderby'] ) {
            $args['orderby'] = 'meta_value meta_value_num';
            $args['meta_key'] = '_sku';
        } else {
            $args['orderby'] = ! empty( $settings['orderby'] ) ? sanitize_text_field( $settings['orderby'] ) : 'date';
        }

        // Categories
        if ( ! empty( $settings['eael_product_list_categories'] ) && is_array( $settings['eael_product_list_categories'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $settings['eael_product_list_categories'],
                'operator' => 'IN',
            ];
        }

        $args['meta_query'] = [
            'relation' => 'AND',
        ];

        // Stock settings
        if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
            $args['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'instock'
            ];
        }

        // Filter by
        switch( $settings['eael_product_list_product_filter'] ){
            case 'featured-products':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ];
                break;

            case 'best-selling-products':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;
                
            case 'sale-products':
                $args['post__in']  = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                break;
                
            case 'top-products':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;
            
            case 'manual':
                $args['post__in'] = ! empty( $settings['eael_product_list_products_in'] ) ? $settings['eael_product_list_products_in'] : [ 0 ];
                break;

            default:
                break;
        }

        return $args;
    }

    protected function render() {
		if ( ! function_exists( 'WC' ) ) {
            return;
        }

        $settings           = $this->settings                   = $this->get_settings_for_display();
		$woo_product_list   = $this->woo_product_list_settings  = self::get_woo_product_list_settings( $settings );
        $is_product_archive = is_product_tag() || is_product_category() || is_shop() || is_product_taxonomy();

        if ( 'source_dynamic' === $settings['post_type'] && is_archive() || ! empty( $_REQUEST['post_type'] ) ) {
		    $settings['posts_per_page'] = ! empty( $settings['eael_woo_product_list_products_count'] )  ? intval( $settings['eael_woo_product_list_products_count'] ) : 4;
		    $settings['offset']         = ! empty( $settings['product_offset'] )  ? intval( $settings['product_offset'] ) : 0;
		    $args                       = ClassesHelper::get_query_args( $settings );
		    $args                       = ClassesHelper::get_dynamic_args( $settings, $args );
	    } else {
            $args = $this->eael_prepare_product_query( $settings );
	    }

        $no_products_found = 0;

        if ( is_user_logged_in() ) {
            $product_purchase_type = ! empty( $settings['product_type_logged_users'] ) ? sanitize_text_field( $settings['product_type_logged_users'] ) : '';

            if (  in_array( $product_purchase_type, ['purchased', 'not-purchased'] ) ) {
                $user_ordered_products = ClassesHelper::eael_get_all_user_ordered_products();
                $no_products_found = empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ? 1 : 0;
 
                if ( ! empty( $user_ordered_products ) && 'purchased' === $product_purchase_type ){
                    $args['post__in'] = $user_ordered_products;
                }

                if ( ! empty( $user_ordered_products ) && 'not-purchased' === $product_purchase_type ){
                    $args['post__not_in'] = $user_ordered_products;
                }
            }
        }

        add_filter( 'woocommerce_product_add_to_cart_text', [$this, 'add_to_cart_button_custom_text'] );
        ?>

        <div class="eael-product-list-wrapper <?php echo esc_attr( $woo_product_list['layout'] ) ?>">
            <div class="eael-product-list-body woocommerce">
                <div class="eael-product-list-container">
                    <div class="eael-post-appender">
                        <?php
                        do_action( 'eael/woo-product-list/before-product-loop' );

                        // Load more data
                        $settings['eael_widget_id']         = $this->get_id();
                        $settings['eael_page_id']           = Plugin::$instance->documents->get_current() ? Plugin::$instance->documents->get_current()->get_main_id() : get_the_ID();
                        $settings['layout_mode']            = $woo_product_list['layout'];
                        $template                           = $this->get_template( $settings['layout_mode'] );
                        $settings['loadable_file_name']     = $this->get_filename_only( $template );
                        $dir_name                           = $this->get_temp_dir_name( $settings['loadable_file_name'] );
                        $found_posts                        = 0;
                        

                        if ( file_exists( $template ) ) {
                            if( $settings['post_type'] === 'source_archive' && is_archive() && $is_product_archive ){
                                global $wp_query;
                                $query = $wp_query;
                                $args  = $wp_query->query_vars;
                            } else {
                                $query = new \WP_Query( $args );
                            }
                            
                            if ( $query->have_posts() ) {
                                // Load more data
                                $found_posts                        = $query->found_posts;
                                $max_page                           = ceil( $found_posts / absint( $args['posts_per_page'] ) );
                                $args['max_page']                   = $max_page;
                                $args['total_post']                 = $found_posts;

                                while ( $query->have_posts() ) {
                                    $query->the_post();
                                    include( realpath( $template ) );
                                }
                                wp_reset_postdata();
                            } else {
                                printf( '<p class="no-posts-found">%s</p>', esc_html( $woo_product_list['products_not_found_text'] ) );
                            }
                        } else {
                            echo '<p class="eael-no-posts-found">' . esc_html__( 'No layout found!', 'essential-addons-for-elementor-lite' ) . '</p>';
                        }

                        do_action( 'eael/woo-product-list/after-product-loop' );
                        ?>
                    </div>

                    <?php 
                    if ( ! empty( $args['posts_per_page'] ) && $found_posts > $args['posts_per_page'] ) {
                        $this->print_load_more_button( $settings, $args, $dir_name );
                    }
                    ?>

                </div>
            </div>
        </div>

		<?php
        remove_filter('woocommerce_product_add_to_cart_text', [$this, 'add_to_cart_button_custom_text' ]);
        
    }
}

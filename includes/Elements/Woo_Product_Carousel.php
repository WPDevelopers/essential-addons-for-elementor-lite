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
use Elementor\Widget_Base;

use Essential_Addons_Elementor\Traits\Helper;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Woo_Product_Carousel extends Widget_Base {
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

	/**
	 * get widget name
     *
     * Retrieve Woo Product Carousel widget name.
     *
	 * @return string
	 */
    public function get_name() {
        return 'eael-woo-product-carousel';
    }

	/**
	 * get widget title
     *
     * Retrieve Woo Product Carousel widget title.
     *
	 * @return string
	 */
    public function get_title() {
        return esc_html__( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' );
    }

	/**
	 * get widget icon
	 *
	 * Retrieve Woo Product Carousel widget icon.
	 *
	 * @return string
	 */
    public function get_icon() {
        return 'eaicon-product-carousel';
    }
    
    public function get_categories() {
        return ['essential-addons-elementor'];
    }

	/**
	 * get widget keywords
	 *
	 * Retrieve list of keywords the widget belongs to.
     *
	 * @return string[]
	 */
    public function get_keywords() {
        return [
            'woo',
            'woocommerce',
            'ea woocommerce',
            'ea woo product carousel',
            'ea woocommerce product carousel',
            'product gallery',
            'woocommerce carousel',
            'gallery',
            'ea',
            'essential addons',
        ];
    }
    
    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/woo-product-carousel/';
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
    
    protected function eael_get_product_orderby_options() {
        return apply_filters( 'eael/woo-product-carousel/orderby-options', [
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
        return apply_filters( 'eael/woo-product-carousel/filterby-options', [
            'recent-products'       => esc_html__( 'Recent Products', 'essential-addons-for-elementor-lite' ),
            'featured-products'     => esc_html__( 'Featured Products', 'essential-addons-for-elementor-lite' ),
            'best-selling-products' => esc_html__( 'Best Selling Products', 'essential-addons-for-elementor-lite' ),
            'sale-products'         => esc_html__( 'Sale Products', 'essential-addons-for-elementor-lite' ),
            'top-products'          => esc_html__( 'Top Rated Products', 'essential-addons-for-elementor-lite' ),
        ] );
    }

    protected function eael_get_product_statuses() {
        return apply_filters( 'eael/woo-product-carousel/product-statuses', [
            'publish'       => esc_html__( 'Publish', 'essential-addons-for-elementor-lite' ),
            'draft'         => esc_html__( 'Draft', 'essential-addons-for-elementor-lite' ),
            'pending'       => esc_html__( 'Pending Review', 'essential-addons-for-elementor-lite' ),
            'future'        => esc_html__( 'Schedule', 'essential-addons-for-elementor-lite' ),
        ] );
    }

	/**
     * Register Woo Product carousel widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
	 * register_controls
	 */
    protected function register_controls() {
	    $this->init_content_wc_notice_controls();
        if ( !function_exists( 'WC' ) ) {
            return;
        }
        // Content Controls
        $this->eael_woo_product_carousel_layout();
        $this->eael_woo_product_carousel_content();
        $this->eael_woo_product_carousel_options();
        $this->eael_woo_product_carousel_query();

        $this->eael_product_action_buttons();
        $this->eael_product_badges();
        
        // Style Controls---------------
        $this->init_style_product_controls();
        $this->style_color_typography();

        $this->eael_woo_product_carousel_buttons_style();
        $this->eael_product_view_popup_style();
        $this->eael_woo_product_carousel_dots();
        $this->eael_woo_product_carousel_image_dots();
        $this->eael_woo_product_carousel_arrows();
	    do_action( 'eael/controls/nothing_found_style', $this );
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
    
    protected function eael_woo_product_carousel_layout() {

    }
    
    protected function eael_woo_product_carousel_content() {

	    $this->start_controls_section(
		    'eael_section_product_carousel_layouts',
		    [
			    'label' => esc_html__( 'Layout Settings', 'essential-addons-for-elementor-lite' ),
		    ]
	    );

	    $this->add_control(
		    'eael_dynamic_template_layout',
		    [
			    'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'preset-1',
			    'options' => $this->get_template_list_for_dropdown(true),
		    ]
	    );

	    $this->add_control(
		    'eael_product_carousel_show_title',
		    [
			    'label' => __('Show Title', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
			    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
			    'return_value' => 'yes',
			    'default' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'eael_product_carousel_title_tag',
		    [
			    'label' => __('Title Tag', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'h2',
			    'options' => [
				    'h1' => __('H1', 'essential-addons-for-elementor-lite'),
				    'h2' => __('H2', 'essential-addons-for-elementor-lite'),
				    'h3' => __('H3', 'essential-addons-for-elementor-lite'),
				    'h4' => __('H4', 'essential-addons-for-elementor-lite'),
				    'h5' => __('H5', 'essential-addons-for-elementor-lite'),
				    'h6' => __('H6', 'essential-addons-for-elementor-lite'),
				    'span' => __('Span', 'essential-addons-for-elementor-lite'),
				    'p' => __('P', 'essential-addons-for-elementor-lite'),
				    'div' => __('Div', 'essential-addons-for-elementor-lite'),
			    ],
			    'condition' => [
				    'eael_product_carousel_show_title' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_carousel_title_length',
		    [
			    'label' => __('Title Length', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => [
				    'eael_product_carousel_show_title' => 'yes',
			    ],
		    ]
	    );
        
        $this->add_control( 'eael_product_carousel_rating', [
            'label'        => esc_html__( 'Show Product Rating?', 'essential-addons-for-elementor-lite' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        
        $this->add_control(
            'eael_product_carousel_price',
            [
                'label'        => esc_html__( 'Show Product Price?', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'eael_product_carousel_excerpt',
            [
                'label'        => esc_html__( 'Short Description?', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );
        $this->add_control(
            'eael_product_carousel_excerpt_length',
            [
                'label'     => __( 'Excerpt Words', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '10',
                'condition' => [
                    'eael_product_carousel_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_excerpt_expanison_indicator',
            [
                'label'       => esc_html__( 'Expansion Indicator', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => '...',
                'condition'   => [
                    'eael_product_carousel_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'eael_product_carousel_image_size',
                'exclude'     => ['custom'],
                'default'     => 'medium',
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'eael_woo_product_carousel_image_stretch',
            [
                'label'        => __( 'Image Stretch', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'true',
                'default'      => 'yes',
            ]
        );
        
        $this->add_control(
            'eael_post_terms',
            [
                'label'     => __( 'Show Terms From', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'category' => __( 'Category', 'essential-addons-for-elementor-lite' ),
                    'tags'     => __( 'Tags', 'essential-addons-for-elementor-lite' ),
                ],
                'default'   => 'category',
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_post_terms_max_length',
            [
                'label'     => __( 'Max Terms to Show', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    1 => __( '1', 'essential-addons-for-elementor-lite' ),
                    2 => __( '2', 'essential-addons-for-elementor-lite' ),
                    3 => __( '3', 'essential-addons-for-elementor-lite' ),
                ],
                'default'   => 1,
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_button_appearance',
            [
                'label' => __('Button Appears', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hover',
                'separator' => 'before',
                'options' => [
                    'hover' => __('On Hover', 'essential-addons-for-elementor-lite'),
                    'static' => __('Static', 'essential-addons-for-elementor-lite'),
                    'hide' => __('No Buttons', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_product_button_appearance_note_for_preset_4',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'Static option will not work for Preset 4.', 'essential-addons-for-elementor-lite' ),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'eael_dynamic_template_layout' => 'preset-4',
                    'eael_product_button_appearance' => 'static',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_carousel_quick_view',
		    [
			    'label'        => esc_html__( 'Show Quick View?', 'essential-addons-for-elementor-lite' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default'      => 'yes',
		    ]
	    );
    
        $this->add_control(
            'eael_product_quick_view_title_tag',
            [
                'label' => __('Quick View Title Tag', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2' => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3' => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4' => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5' => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6' => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p' => __('P', 'essential-addons-for-elementor-lite'),
                    'div' => __('Div', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_product_carousel_quick_view' => 'yes',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_carousel_image_clickable',
		    [
			    'label' => esc_html__('Image Clickable?', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default' => 'no',
                'separator' => 'before',
		    ]
	    );


        $this->add_control(
            'eael_product_carousel_title_clickable',
            [
                'label' => esc_html__('Title Clickable?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'eael_product_carousel_not_found_msg',
            [
                'label'     => __( 'Not Found Message', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => __( 'Products Not Found', 'essential-addons-for-elementor-lite' ),
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();
    }
    
    protected function eael_woo_product_carousel_options() {
        
        $this->start_controls_section(
            'section_additional_options',
            [
                'label' => __( 'Carousel Settings', 'essential-addons-for-elementor-lite' ),
            ]
        );

	    $this->add_control(
		    'carousel_effect',
		    [
			    'label'       => __('Effect', 'essential-addons-for-elementor-lite'),
			    'description' => __('Sets transition effect', 'essential-addons-for-elementor-lite'),
			    'type'        => Controls_Manager::SELECT,
			    'default'     => 'slide',
			    'options'     => [
				    'slide'     => __('Slide', 'essential-addons-for-elementor-lite'),
				    'coverflow'  => __('Coverflow', 'essential-addons-for-elementor-lite'),
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'items',
		    [
			    'label'   => __( 'Visible Product', 'essential-addons-for-elementor-lite' ),
			    'type'    => Controls_Manager::SELECT,
			    'options' => [
				    '1' => __( '1', 'essential-addons-for-elementor-lite' ),
				    '2' => __( '2', 'essential-addons-for-elementor-lite' ),
				    '3' => __( '3', 'essential-addons-for-elementor-lite' ),
				    '4' => __( '4', 'essential-addons-for-elementor-lite' ),
				    '5' => __( '5', 'essential-addons-for-elementor-lite' ),
				    '6' => __( '6', 'essential-addons-for-elementor-lite' ),
			    ],
			    'default' => 3,
			    'tablet_default' => 2,
			    'mobile_default' => 1,
                'condition' => [
                    'carousel_effect' => 'slide',
                ]
		    ]
	    );

	    $this->add_control(
		    'carousel_rotate',
		    [
			    'label'       => __( 'Rotate', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::SLIDER,
			    'default'     => ['size' => 50],
			    'range'       => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'condition' => [
				    'carousel_effect' => 'coverflow',
			    ]
		    ]
	    );
	    $this->add_control(
		    'carousel_depth',
		    [
			    'label'       => __( 'Depth', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::SLIDER,
			    'default'     => ['size' => 100],
			    'range'       => [
				    'px' => [
					    'min'  => 100,
					    'max'  => 1000,
					    'step' => 10,
				    ],
			    ],
			    'condition' => [
				    'carousel_effect' => 'coverflow',
			    ]
		    ]
	    );
	    $this->add_control(
		    'carousel_stretch',
		    [
			    'label'       => __( 'Stretch', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::SLIDER,
			    'default'     => ['size' => 10],
			    'range'       => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 200,
					    'step' => 10,
				    ],
			    ],
			    'condition' => [
				    'carousel_effect' => 'coverflow',
			    ]
		    ]
	    );

	    $this->add_responsive_control(
		    'margin',
		    [
			    'label'      => __('Items Gap', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'default'    => ['size' => 10],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
		    ]
	    );
        
        $this->add_control(
            'slider_speed',
            [
                'label'       => __( 'Speed', 'essential-addons-for-elementor-lite' ),
                'description' => __( 'Duration of transition between slides (in ms)',
                    'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => ['size' => 400],
                'range'       => [
                    'px' => [
                        'min'  => 100,
                        'max'  => 3000,
                        'step' => 1,
                    ],
                ],
                'size_units'  => '',
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'        => __( 'Autoplay', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'      => __( 'Autoplay Speed', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 2000],
                'range'      => [
                    'px' => [
                        'min'  => 500,
                        'max'  => 5000,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'condition'  => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label'        => __( 'Pause On Hover', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'condition'    => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'        => __( 'Infinite Loop', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'        => __( 'Grab Cursor', 'essential-addons-for-elementor-lite' ),
                'description'  => __( 'Shows grab cursor when you hover over the slider',
                    'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'     => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'        => __( 'Arrows', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'        => __( 'Dots', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

	    $this->add_control(
		    'image_dots',
		    [
			    'label'                 => __('Image Dots', 'essential-addons-for-elementor-lite'),
			    'type'                  => Controls_Manager::SWITCHER,
			    'label_on'              => __('Yes', 'essential-addons-for-elementor-lite'),
			    'label_off'             => __('No', 'essential-addons-for-elementor-lite'),
			    'return_value'          => 'yes',
			    'condition' => [
				    'dots'    => 'yes'
			    ]
		    ]
	    );


	    $this->add_responsive_control(
		    'image_dots_visibility',
		    [
			    'label' => __('Image Dots Visibility', 'essential-addons-for-elementor-lite'),
			    'type' => \Elementor\Controls_Manager::SWITCHER,
			    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
			    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
			    'return_value' => 'yes',
			    'default' => 'yes',
			    'condition' => [
				    'dots'    => 'yes',
				    'image_dots'    => 'yes'
			    ]
		    ]
	    );

	    $this->add_control(
		    'direction',
		    [
			    'label'     => __( 'Direction', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::SELECT,
			    'default'   => 'left',
			    'options'   => [
				    'left'  => __( 'Left', 'essential-addons-for-elementor-lite' ),
				    'right' => __( 'Right', 'essential-addons-for-elementor-lite' ),
			    ],
			    'separator' => 'before',
		    ]
	    );


        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_carousel_query() {
        $this->start_controls_section( 'eael_section_product_carousel_query', [
            'label' => esc_html__( 'Query', 'essential-addons-for-elementor-lite' ),
        ] );
        
        $this->add_control( 'eael_product_carousel_product_filter', [
            'label'   => esc_html__( 'Filter By', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'recent-products',
            'options' => $this->eael_get_product_filterby_options(),
        ] );
        
        $this->add_control( 'orderby', [
            'label'   => __( 'Order By', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::SELECT,
            'options' => $this->eael_get_product_orderby_options(),
            'default' => 'date',
        
        ] );
        
        $this->add_control( 'order', [
            'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'asc'  => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
        
        ] );
        
        $this->add_control( 'eael_product_carousel_products_count', [
            'label'   => __( 'Products Count', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 4,
            'min'     => 1,
            'max'     => 1000,
            'step'    => 1,
        ] );
        
        $this->add_control( 'product_offset', [
            'label'   => __( 'Offset', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 0,
        ] );

        $this->add_control(
            'eael_product_carousel_products_status',
            [
                'label' => __( 'Product Status', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' => [ 'publish', 'pending', 'future' ],
                'options' => $this->eael_get_product_statuses(),
            ]
        );
	    $taxonomies = get_taxonomies(['object_type' => ['product']], 'objects');
	    foreach ($taxonomies as $taxonomy => $object) {
		    if (!isset($object->object_type[0])) {
			    continue;
		    }

		    $this->add_control(
			    $taxonomy . '_ids',
			    [
				    'label' => $object->label,
				    'type' => Controls_Manager::SELECT2,
				    'label_block' => true,
				    'multiple' => true,
				    'object_type' => $taxonomy,
				    'options' => wp_list_pluck(get_terms($taxonomy), 'name', 'term_id'),
			    ]
		    );
	    }
        
        $this->end_controls_section();
    }
    
    protected function eael_product_action_buttons() {

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
                'default' => 'sale-preset-5',
                'options' => [
                    'sale-preset-5' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
                    'sale-preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
                    'sale-preset-3' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
                    'sale-preset-4' => esc_html__( 'Preset 4', 'essential-addons-for-elementor-lite' ),
                    'sale-preset-1' => esc_html__( 'Preset 5', 'essential-addons-for-elementor-lite' ),

                ]
            ]
        );
        
        $this->add_control(
            'eael_product_sale_badge_alignment',
            [
                'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => 'preset-2',
                ]
            ]
        );

	    $this->add_control(
		    'eael_product_carousel_sale_text',
		    [
			    'label'       => esc_html__( 'Sale Text', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
                'separator' => 'before',
		    ]
	    );
	    $this->add_control(
		    'eael_product_carousel_stockout_text',
		    [
			    'label'       => esc_html__( 'Stock Out Text', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
		    ]
	    );
        
        $this->end_controls_section();
    }
    
    protected function init_style_product_controls() {
        $this->start_controls_section(
            'eael_product_carousel_styles',
            [
                'label' => esc_html__( 'Products', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_carousel_alignment',
            [
                'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .eael-product-carousel .product-details-wrap' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => 'preset-3',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-carousel-container .eael-product-carousel' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => ['preset-2','preset-4'],
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'description'   => __( 'Use opacity color for overlay design.', 'essential-addons-for-elementor-lite' ),
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-carousel-container .eael-product-carousel .carousel-overlay' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => ['preset-2','preset-4'],
                ]
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_carousel_margin',
            [
                'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-product-carousel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'eael_product_carousel_tabs' );
        
        $this->start_controls_tab( 'eael_product_carousel_tabs_normal',
            ['label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' )] );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'           => 'eael_product_carousel_border',
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
                'selector'       => '{{WRAPPER}} .eael-product-carousel',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_carousel_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .eael-product-carousel',
            ]
        );
        
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'eael_product_carousel_hover_styles',
            ['label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' )] );
        
        $this->add_control(
            'eael_product_carousel_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_carousel_border_border!' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_carousel_box_shadow_hover',
                'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .eael-product-carousel:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'eael_product_carousel_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .eael-product-carousel .image-wrap img, {{WRAPPER}} .eael-product-carousel > .product-image-wrap'
                                                         => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
                    '{{WRAPPER}} .eael-product-carousel.product-details-none .image-wrap img, {{WRAPPER}} .eael-product-carousel > .product-image-wrap'
                                                         => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .eael-product-carousel.product-details-none-overlay .image-wrap img, {{WRAPPER}} .eael-product-carousel > .product-image-wrap'
                                                         => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_details_heading',
            [
                'label'     => __( 'Product Details', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_carousel_inner_padding',
            [
                'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
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
                'default'    => [
                    'top'      => '15',
                    'right'    => '15',
                    'bottom'   => '15',
                    'left'     => '15',
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-product-carousel .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function style_color_typography() {
        
        $this->start_controls_section(
            'eael_section_product_carousel_typography',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_product_title_heading',
            [
                'label' => __( 'Product Title', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_product_title_color',
            [
                'label'     => esc_html__( 'Product Title Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-carousel .eael-product-title *' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_carousel_title_typo',
                'selector' => '{{WRAPPER}} .eael-product-carousel .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-carousel .eael-product-title *',
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_product_price_heading',
            [
                'label' => __( 'Product Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_price_color',
            [
                'label'     => esc_html__( 'Regular Price Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .price, {{WRAPPER}} .eael-product-carousel .eael-product-price .amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_carousel_sale_price_color',
            [
                'label'     => esc_html__( 'Sale Price Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .price del, {{WRAPPER}} .eael-product-carousel .eael-product-price del .amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_carousel_product_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-carousel .eael-product-price',
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_rating_heading',
            [
                'label' => __( 'Star Rating', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_rating_color',
            [
                'label'     => esc_html__( 'Rating Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .star-rating::before'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-carousel .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_carousel_rating_size',
            [
                'label'     => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default'   => [
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-carousel-container .woocommerce ul.products .product .star-rating' => 'font-size: {{SIZE}}px!important;',
                    '{{WRAPPER}} .eael-woo-product-carousel-container .woocommerce ul.products .product .star-rating::before' => 'font-size: {{SIZE}}px!important;',
                    '{{WRAPPER}} .eael-woo-product-carousel-container .woocommerce ul.products .product .star-rating span::before' => 'font-size: {{SIZE}}px!important;',
                ],
            
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_desc_heading',
            [
                'label'     => __( 'Product Description', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_carousel_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_desc_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .eael-product-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_carousel_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_product_carousel_desc_typography',
                'selector'  => '{{WRAPPER}} .eael-product-carousel .eael-product-excerpt',
                'condition' => [
                    'eael_product_carousel_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_sale_badge_heading',
            [
                'label' => __( 'Sale Badge', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_sale_badge_color',
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
            'eael_product_carousel_sale_badge_background',
            [
                'label'     => esc_html__( 'Sale Badge Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .onsale, {{WRAPPER}} .eael-product-carousel .eael-onsale' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-carousel .eael-onsale:not(.outofstock).sale-preset-4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_carousel_sale_badge_typo',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock)',
            ]
        );
        
        // stock out badge
        $this->add_control(
            'eael_product_carousel_stock_out_badge_heading',
            [
                'label' => __( 'Stock Out Badge', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_stock_out_badge_color',
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
            'eael_product_carousel_stock_out_badge_background',
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
                'name'     => 'eael_product_carousel_stock_out_badge_typo',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_carousel_buttons_style() {
        $this->start_controls_section(
            'eael_section_product_carousel_buttons_styles',
            [
                'label' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_width',
            [
                'label'     => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => 'preset-3',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_height',
            [
                'label'     => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap' => 'height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => 'preset-3',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_icon_size',
            [
                'label'     => esc_html__( 'Icons Size', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a i, {{WRAPPER}} .eael-product-carousel .icons-wrap li.add-to-cart a:before' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_preset3_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => 'preset-3',
                ]
            ]
        );
        
        $this->start_controls_tabs( 'eael_product_carousel_buttons_style_tabs' );
        
        $this->start_controls_tab( 'eael_product_carousel_buttons_style_tabs_normal',
            ['label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' )] );
        
        $this->add_control(
            'eael_product_carousel_buttons_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap.block-style' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a'        => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'eael_product_carousel_buttons_border',
                'selector'  => '{{WRAPPER}} .eael-product-carousel .button.add_to_cart_button, {{WRAPPER}} .eael-product-carousel .icons-wrap li a',
                'condition' => [
                    'eael_dynamic_template_layout!' => 'preset-3',
                ]
            ]
        );
        $this->add_control(
            'eael_product_carousel_buttons_border_radius',
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
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap:not(.details-block-style-2) li a'       => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap.details-block-style-2 li:only-child a'  => 'border-radius: {{SIZE}}px!important;',
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap.details-block-style-2 li:last-child a'  => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => 'preset-3',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_top_spacing',
            [
                'label'     => esc_html__( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => 'preset-4',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'eael_product_carousel_buttons_hover_styles',
            ['label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' )] );
        
        $this->add_control(
            'eael_product_carousel_buttons_hover_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_hover_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_carousel_buttons_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-carousel .icons-wrap li a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_carousel_buttons_border_border!' => '',
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
                'label' => __( 'Popup', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
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
                    '.eael-popup-details-render{{WRAPPER}} .eael-product-quick-view-title.product_title.entry-title' => 'color: {{VALUE}};',
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
		    'eael_product_popup_review_color',
		    [
			    'label'     => __( 'Review Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#0274be',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating::before, .eael-popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating span::before' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
        
        $this->add_control(
            'eael_product_popup_review_link_color',
            [
                'label'     => __( 'Review Link Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}}  a.woocommerce-review-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_review_link_hover',
            [
                'label'     => __( 'Review Link Hover', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0274be',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}}  a.woocommerce-review-link:hover' => 'color: {{VALUE}};',
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
                'label'     => __( 'Sale', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_sale_typo',
                'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} .eael-onsale',
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_color',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_bg_color',
            [
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'background-color: {{VALUE}}!important;',
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock).sale-preset-4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
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
        
        $this->start_controls_tab( 'eael_product_popup_cart_button_style_tabs_normal',
            ['label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' )] );
        
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
        
        $this->start_controls_tab( 'eael_product_popup_cart_button_hover_styles',
            ['label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' )] );
        
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
                'name'     => 'eael_product_popup_box_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_carousel_dots() {
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'     => __( 'Dots', 'essential-addons-for-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'dots' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'dots_preset',
            [
                'label'   => __( 'Preset', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'dots-preset-1'  => __( 'Preset 1', 'essential-addons-for-elementor-lite' ),
                    'dots-preset-2'  => __( 'Preset 2', 'essential-addons-for-elementor-lite' ),
                    'dots-preset-3'  => __( 'Preset 3', 'essential-addons-for-elementor-lite' ),
                    'dots-preset-4'  => __( 'Preset 4', 'essential-addons-for-elementor-lite' ),
                ],
                'default' => 'dots-preset-1',
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'   => __( 'Position', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'inside'  => __( 'Inside', 'essential-addons-for-elementor-lite' ),
                    'outside' => __( 'Outside', 'essential-addons-for-elementor-lite' ),
                ],
                'default' => 'outside',
            ]
        );

        $this->add_control(
            'is_use_dots_custom_width_height',
            [
                'label'        => __( 'Use Custom Width/Height?', 'essential-addons-for-elementor-lite' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'dots_width',
            [
                'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_height',
            [
                'label'      => __( 'Height', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label'      => __( 'Size', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => '',
                    'dots_preset!' => 'dots-preset-1',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'      => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'dots_border_normal',
                'label'       => __( 'Border', 'essential-addons-for-elementor-lite' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
            ]
        );

        $this->add_control(
            'dots_border_radius_normal',
            [
                'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_padding',
            [
                'label'              => __( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors'          => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_hover',
            [
                'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [
                'label' => __( 'Active', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'active_dot_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'active_dots_width',
            [
                'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'active_dots_height',
            [
                'label'      => __( 'Height', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'active_dots_radius',
            [
                'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'active_dots_shadow',
                'label'    => __( 'Shadow', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function eael_woo_product_carousel_image_dots(){
	    /**
	     * Image Dots
	     */
	    $this->start_controls_section(
		    'section_image_dots_style',
		    [
			    'label'                 => __('Images Dots', 'essential-addons-for-elementor-lite'),
			    'tab'                   => Controls_Manager::TAB_STYLE,
			    'condition'             => [
				    'image_dots'      => 'yes',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_image_dots_width',
		    [
			    'label' => __('Width', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 350,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-carousel-gallary-pagination' => 'width: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_image_dots_height',
		    [
			    'label' => __('Height', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 100,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-carousel-gallary-pagination' => 'height: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_image_dots_image_size',
		    [
			    'label' => __('Image Size', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 500,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 100,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-carousel-gallary-pagination img' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );
	    $this->add_control(
		    'eael_image_dots_image_border_radius',
		    [
			    'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-carousel-gallary-pagination img' => 'border-radius: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function eael_woo_product_carousel_arrows() {
        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'     => __( 'Arrows', 'essential-addons-for-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow',
            [
                'label'       => __( 'Choose Arrow', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'default'     => 'fa fa-angle-right',
                'options'     => [
                    'fa fa-angle-right'          => __( 'Angle', 'essential-addons-for-elementor-lite' ),
                    'fa fa-angle-double-right'   => __( 'Double Angle', 'essential-addons-for-elementor-lite' ),
                    'fa fa-chevron-right'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
                    'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'essential-addons-for-elementor-lite' ),
                    'fa fa-arrow-right'          => __( 'Arrow', 'essential-addons-for-elementor-lite' ),
                    'fa fa-long-arrow-right'     => __( 'Long Arrow', 'essential-addons-for-elementor-lite' ),
                    'fa fa-caret-right'          => __( 'Caret', 'essential-addons-for-elementor-lite' ),
                    'fa fa-caret-square-o-right' => __( 'Caret Square', 'essential-addons-for-elementor-lite' ),
                    'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'essential-addons-for-elementor-lite' ),
                    'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'essential-addons-for-elementor-lite' ),
                    'fa fa-toggle-right'         => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
                    'fa fa-hand-o-right'         => __( 'Hand', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label'      => __( 'Arrows Size', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => '40'],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_icon_size',
            [
                'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => '22'],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'      => __( 'Align Left Arrow', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'      => __( 'Align Right Arrow', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'arrows_border_normal',
                'label'       => __( 'Border', 'essential-addons-for-elementor-lite' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
            ]
        );

        $this->add_control(
            'arrows_border_radius_normal',
            [
                'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_carousel_arrow_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
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
        $widget_id = $this->get_id();
        $settings[ 'eael_widget_id' ] = $widget_id;

        $args = $this->product_query_builder();
        if ( Plugin::$instance->documents->get_current() ) {
            $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
        }

        // render dom
        $this->add_render_attribute( 'container', [
            'class'          => [
                'swiper-container-wrap',
                'eael-woo-product-carousel-container',
                $settings[ 'eael_dynamic_template_layout' ],
            ],
            'id'             => 'eael-product-carousel-' . esc_attr( $this->get_id() ),
            'data-widget-id' => $widget_id,
        ] );

        if ( $settings[ 'dots_position' ] ) {
            $this->add_render_attribute( 'container', 'class',
                'swiper-container-wrap-dots-' . $settings[ 'dots_position' ] );
        }
        
        $swiper_version_class = '';
        if ( class_exists( 'Elementor\Plugin' ) ) {
            $swiper_class           = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
            $swiper_version_class   = 'swiper' === $swiper_class ? 'swiper-8' : 'swiper-8-lower';
        }

        $this->add_render_attribute(
            'eael-woo-product-carousel-wrap',
            [
                'class'           => [
                    'woocommerce',
                    'swiper',
                    esc_attr( $swiper_version_class ),
                    'swiper-container',
                    'eael-woo-product-carousel',
                    'swiper-container-' . esc_attr( $this->get_id() ),
                    'eael-product-appender-' . esc_attr( $this->get_id() ),
                    $settings['eael_product_button_appearance'] ? 'eael-'.esc_attr( $settings['eael_product_button_appearance'] ).'-buttons' : ''
                ],
                'data-pagination' => '.swiper-pagination-' . esc_attr( $this->get_id() ),
                'data-arrow-next' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
                'data-arrow-prev' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
            ]
        );

        if ( $settings[ 'eael_dynamic_template_layout' ] ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-type',
                $settings[ 'eael_dynamic_template_layout' ] );
        }

        if ( $settings[ 'eael_woo_product_carousel_image_stretch' ] ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'class', 'swiper-image-stretch' );
        }

	    if ($settings['carousel_effect']) {
		    $this->add_render_attribute('eael-woo-product-carousel-wrap', 'data-effect', $settings['carousel_effect']);
	    }

	    if($settings['carousel_effect'] == 'slide'){
		    if ( !empty( $settings[ 'items' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-items', $settings[ 'items' ] );
		    }
		    if ( !empty( $settings[ 'items_tablet' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-items-tablet',
				    $settings[ 'items_tablet' ] );
		    }
		    if ( !empty( $settings[ 'items_mobile' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-items-mobile',
				    $settings[ 'items_mobile' ] );
		    }
	    }

	    if($settings['carousel_effect'] == 'coverflow') {
		    if ( !empty( $settings[ 'carousel_depth' ][ 'size' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-depth', $settings[ 'carousel_depth' ][ 'size' ] );
		    }
		    if ( !empty( $settings[ 'carousel_rotate' ][ 'size' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-rotate',
				    $settings[ 'carousel_rotate' ][ 'size' ] );
		    }
		    if ( !empty( $settings[ 'carousel_stretch' ][ 'size' ] ) ) {
			    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-stretch',
				    $settings[ 'carousel_stretch' ][ 'size' ] );
		    }
	    }

        if ( !empty( $settings[ 'margin' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-margin',
                $settings[ 'margin' ][ 'size' ] );
        }
        if ( !empty( $settings[ 'margin_tablet' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-margin-tablet',
                $settings[ 'margin_tablet' ][ 'size' ] );
        }
        if ( !empty( $settings[ 'margin_mobile' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-margin-mobile',
                $settings[ 'margin_mobile' ][ 'size' ] );
        }

        if ( !empty( $settings[ 'slider_speed' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-speed',
                $settings[ 'slider_speed' ][ 'size' ] );
        }

        if ( $settings[ 'autoplay' ] == 'yes' && !empty( $settings[ 'autoplay_speed' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-autoplay',
                $settings[ 'autoplay_speed' ][ 'size' ] );
        } else {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-autoplay', '0' );
        }

        if ( $settings[ 'pause_on_hover' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-pause-on-hover', 'true' );
        }

        if ( $settings[ 'infinite_loop' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-loop', '1' );
        }
        if ( $settings[ 'grab_cursor' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-grab-cursor', '1' );
        }
        if ( $settings[ 'arrows' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-arrows', '1' );
        }
        if ( $settings[ 'dots' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'data-dots', '1' );
        }

	    if ( $settings['direction'] == 'right' ) {
		    $this->add_render_attribute( 'eael-woo-product-carousel-wrap', 'dir', 'rtl' );
	    }
	    $settings['eael_product_carousel_title_tag'] = HelperClass::eael_validate_html_tag($settings['eael_product_carousel_title_tag']);
	    $settings['eael_product_carousel_sale_text'] = HelperClass::eael_wp_kses($settings['eael_product_carousel_sale_text']);
	    $settings['eael_product_carousel_stockout_text'] = HelperClass::eael_wp_kses($settings['eael_product_carousel_stockout_text']);
        ?>

        <div <?php $this->print_render_attribute_string( 'container' ); ?> >
            <?php
                $template = $this->get_template( $settings[ 'eael_dynamic_template_layout' ] );
                if ( file_exists( $template ) ):
	                $query = new \WP_Query( $args );
	                if ( $query->have_posts() ):
                        echo '<div '.$this->get_render_attribute_string( 'eael-woo-product-carousel-wrap' ).'>';
                            do_action( 'eael_woo_before_product_loop' );
		                    $settings['eael_page_id'] = $this->page_id ? $this->page_id : get_the_ID();
                            echo '<ul class="swiper-wrapper products">';
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                include( $template );
                            }
                            wp_reset_postdata();
                            echo '</ul>';
                        echo '</div>';
                    else:
	                    echo '<p class="eael-no-posts-found">'.HelperClass::eael_wp_kses($settings['eael_product_carousel_not_found_msg']).'</p>';
                    endif;
                else:
	                _e( '<p class="eael-no-posts-found">No layout found!</p>', 'essential-addons-for-elementor-lite' );
                endif;
            /**
             * Render Slider Dots!
             */

            if (file_exists( $template ) && $settings['image_dots'] === 'yes') {
                $this->render_image_dots($query);
            } else {
	            $this->render_dots();
            }


            /**
             * Render Slider Navigations!
             */
            $this->render_arrows();
            ?>
        </div>
        <?php
    }

    //changes
    protected function render_dots() {
        $settings = $this->get_settings_for_display();

        if ( $settings[ 'dots' ] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ) .' '. $settings['dots_preset'];
            ?>"></div>
        <?php }
    }

	protected function render_image_dots($query)
	{
		$settings = $this->get_settings_for_display();

		$visibility = '';
		if ( $settings['image_dots_visibility'] !== 'yes' ) {
			$visibility .= ' eael_gallery_pagination_hide_on_desktop';
		}

		if ( empty( $settings['image_dots_visibility_mobile'] ) || $settings['image_dots_visibility_mobile'] !== 'yes' ) {
			$visibility .= ' eael_gallery_pagination_hide_on_mobile';
		}

		if ( empty( $settings['image_dots_visibility_tablet'] ) || $settings['image_dots_visibility_tablet'] !== 'yes' ) {
			$visibility .= ' eael_gallery_pagination_hide_on_tablet';
		}

		$this->add_render_attribute('eael_gallery_pagination_wrapper', [
			'class' => ['swiper swiper-container eael-woo-product-carousel-gallary-pagination', $visibility]
		]);

		if ( $settings['direction'] == 'right' ) {
			$this->add_render_attribute( 'eael_gallery_pagination_wrapper', 'dir', 'rtl' );
		}


		if ($settings['image_dots'] === 'yes') : ?>

            <div <?php echo $this->get_render_attribute_string('eael_gallery_pagination_wrapper'); ?>>

            <?php
			if ( $query->have_posts() ) {
				echo '<div class="swiper-wrapper">';
				while ( $query->have_posts() ) {
					$query->the_post();
					$image_arr = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),'full');
					if(empty($image_arr)){
						$image_arr[0] = wc_placeholder_img_src( 'full' );
					}

                    echo '<div class="swiper-slide">';
                        echo '<div class="swiper-slide-container">';
                            echo '<div class="eael-pagination-thumb">';
                                echo '<img class="eael-thumbnail" src="'.esc_url(current($image_arr)).'">';
                            echo '</div>';
                        echo '</div>';
					echo '</div>';
				}
				wp_reset_postdata();
				echo '</div>';
			}
			?>

            </div>
		<?php
		endif;
	}
    
    /**
     * Render logo carousel arrows output on the frontend.
     */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();
        
        if ( $settings[ 'arrows' ] == 'yes' ) { ?>
            <?php
            if ( $settings[ 'arrow' ] ) {
                $pa_next_arrow = $settings[ 'arrow' ];
                $pa_prev_arrow = str_replace( "right", "left", $settings[ 'arrow' ] );
            } else {
                $pa_next_arrow = 'fa fa-angle-right';
                $pa_prev_arrow = 'fa fa-angle-left';
            }
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
            <?php
        }
    }

	/**
	 * Build proper query to fetch product data from wp query
	 * @return array
	 */
    public function product_query_builder(){
	    $settings                     = $this->get_settings_for_display();
	    $widget_id                    = $this->get_id();
	    $settings[ 'eael_widget_id' ] = $widget_id;
	    $order_by                     = $settings[ 'orderby' ];
	    $filter                        = $settings[ 'eael_product_carousel_product_filter' ];
	    $args                         = [
		    'post_type'      => 'product',
		    'post_status'    => !empty( $settings['eael_product_carousel_products_status'] ) ? $settings['eael_product_carousel_products_status'] : ['publish'],
		    'posts_per_page' => $settings[ 'eael_product_carousel_products_count' ] ?: 4,
		    'order'          => $settings[ 'order' ],
		    'offset'         => $settings[ 'product_offset' ],
		    'tax_query'      => [
			    'relation' => 'AND',
			    [
				    'taxonomy' => 'product_visibility',
				    'field'     => 'name',
				    'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
				    'operator' => 'NOT IN',
			    ],
		    ],
	    ];

	    if ( $order_by == '_price' || $order_by == '_sku' ) {
		    $args[ 'orderby' ]  = 'meta_value meta_value_num';
		    $args[ 'meta_key' ] = $order_by;
	    } else {
		    $args[ 'orderby' ] = $order_by;
	    }

	    if ( $filter == 'featured-products' ) {
		    $count                         = isset( $args[ 'tax_query' ] ) ? count( $args[ 'tax_query' ] ) : 0;
		    $args[ 'tax_query' ][ $count ] =
			    [
				    'taxonomy' => 'product_visibility',
				    'field'     => 'name',
				    'terms'    => 'featured',
			    ];
	    }

	    if ( $filter == 'best-selling-products' ) {
		    $args[ 'meta_key' ] = 'total_sales';
		    $args[ 'orderby' ]  = 'meta_value_num';
		    $args[ 'order' ]    = 'DESC';
	    }

	    if ( $filter == 'top-products' ) {
		    $args[ 'meta_key' ] = '_wc_average_rating';
		    $args[ 'orderby' ]  = 'meta_value_num';
		    $args[ 'order' ]    = 'DESC';
	    }

	    if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
		    $args[ 'meta_query' ]   = [ 'relation' => 'AND' ];
		    $args[ 'meta_query' ][] = [
			    'key'   => '_stock_status',
			    'value' => 'instock'
		    ];
	    }

	    if ( $filter == 'sale-products' ) {
		    $args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
	    }


	    $taxonomies      = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );
	    $tax_query_count = isset( $args[ 'meta_query' ] ) ? count( $args[ 'meta_query' ] ) : 0;
	    foreach ( $taxonomies as $object ) {
		    $setting_key = $object->name . '_ids';
		    if ( !empty( $settings[ $setting_key ] ) ) {
			    $args[ 'tax_query' ][ $tax_query_count ] = [
				    'taxonomy' => $object->name,
				    'field'     => 'term_id',
				    'terms'    => $settings[ $setting_key ],
			    ];
		    }
		    $tax_query_count++;
	    }

	    return $args;
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
}

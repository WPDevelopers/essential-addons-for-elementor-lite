<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Plugin;
use \Elementor\Widget_Base;
use \Elementor\Repeater;
use \Elementor\Group_Control_Background;
use \Elementor\Utils;

use \Essential_Addons_Elementor\Classes\Helper;

class Filterable_Gallery extends Widget_Base
{
    private $popup_status = false;
    private $default_control_key = 0;
    private $custom_default_control = false;

    public function get_name()
    {
        return 'eael-filterable-gallery';
    }
    
    public function get_title()
    {
        return esc_html__('Filterable Gallery', 'essential-addons-for-elementor-lite');
    }
    
    public function get_icon()
    {
        return 'eaicon-filterable-gallery';
    }
    
    public function get_categories()
    {
        return ['essential-addons-elementor'];
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
            'font-awesome-4-shim'
        ];
    }
    
    public function get_keywords()
    {
        return [
            'gallery',
            'ea filter gallery',
            'ea filterable gallery',
            'image gallery',
            'media gallery',
            'media',
            'photo gallery',
            'portfolio',
            'ea portfolio',
            'media grid',
            'responsive gallery',
            'photo gallery',
            'ea',
            'essential addons'
        ];
    }
    
    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/filterable-gallery/';
    }
    
    protected function register_controls()
    {
        /**
         * Filter Gallery Settings
         */
        $this->start_controls_section(
            'eael_section_fg_settings',
            [
                'label' => esc_html__('Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'eael_fg_items_to_show',
            [
                'label' => esc_html__('Items to show', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
                'default' => 6,
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'eael_fg_filter_duration',
            [
                'label' => esc_html__('Animation Duration (ms)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => 500,
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_grid_style',
            [
                'label' => esc_html__('Grid Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                    'masonry' => esc_html__('Masonry', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_grid_item_height',
            [
                'label' => esc_html__('Image Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => '300',
                'condition' => [
                    'eael_fg_grid_style' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item .gallery-item-thumbnail-wrap' => 'height: {{VALUE}}px;',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'eael_fg_caption_style',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hoverer',
                'options' => [
                    'hoverer' => __('Overlay', 'essential-addons-for-elementor-lite'),
                    'card' => __('Card', 'essential-addons-for-elementor-lite'),
                    'layout_3'  => esc_html__('Search & Filter', 'essential-addons-for-elementor-lite')
                ],
            ]
        );

        $this->add_control(
            'eael_search_among_all',
            [
                'label' => __('Search Full Gallery ?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                      'eael_fg_caption_style' =>  'layout_3'
                ]
            ]
        );

        $this->add_control(
			'eael_search_among_note',
			[
				'label' => esc_html__( '', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Enabling this will load all prior items up to the one you searched for.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
				'condition' => [
                        'eael_search_among_all' => 'yes',
                        'eael_fg_caption_style' =>  'layout_3'
                ]
			]
		);

        $this->add_control(
			'eael_fg_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'No Items Found', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Not Found Text', 'essential-addons-for-elementor-lite' ),
				'condition' => [
                        'eael_fg_caption_style' =>  'layout_3'
                ],
                'ai' => [
					'active' => false,
				],
			]
		);

        $this->add_control(
            'eael_fg_grid_hover_style',
            [
                'label' => esc_html__('Hover Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-slide-up',
                'options' => [
                    'eael-none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'eael-slide-up' => esc_html__('Slide In Up', 'essential-addons-for-elementor-lite'),
                    'eael-fade-in' => esc_html__('Fade In', 'essential-addons-for-elementor-lite'),
                    'eael-zoom-in' => esc_html__('Zoom In ', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'hoverer',
                ],
            
            ]
        );
        $this->add_control(
            'eael_fg_grid_hover_transition',
            [
                'label' => esc_html__('Hover Transition', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap' => 'transition: {{SIZE}}ms;',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'hoverer',
                    'eael_fg_grid_hover_style!' => 'eael-none',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_show_popup',
            [
                'label' => esc_html__('Link to', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'buttons',
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'media' => esc_html__('Media', 'essential-addons-for-elementor-lite'),
                    'buttons' => esc_html__('Buttons', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_fg_caption_style!'    => 'layout_3'
                ]
            ]
        );

        $this->add_control(
            'eael_title_clickable',
            [
                'label' => __('Title Clickable', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Enable', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Disable', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => ''
            ]
        );

        $this->add_control(
            'eael_section_fg_full_image_clickable',
            [
                'label' => __('Image Clickable', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Enable', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Disable', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => ''
            ]
        );

        $this->add_control(
            'eael_section_fg_mfp_caption',
            [
                'label' => __('Show Popup Caption', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => ''
            ]
        );
        
        $this->add_control(
            'eael_section_fg_zoom_icon_new',
            [
                'label' => esc_html__('Lightbox Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_section_fg_zoom_icon',
                'default' => [
                    'value' => 'fas fa-search-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_fg_show_popup' => 'buttons',
                    'eael_section_fg_full_image_clickable!' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_section_fg_link_icon_new',
            [
                'label' => esc_html__('Link Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_section_fg_link_icon',
                'default' => [
                    'value' => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_fg_show_popup' => 'buttons',
                    'eael_section_fg_full_image_clickable!' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_section_fg_full_image_action',
            [
                'label' => esc_html__('Full Image Action', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => [
                    'lightbox' => esc_html__('Lightbox', 'essential-addons-for-elementor-lite'),
                    'link' => esc_html__('Link', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_section_fg_full_image_clickable'    => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Filter Gallery Control Settings
         */
        $this->start_controls_section(
            'eael_section_fg_control_settings',
            [
                'label' => esc_html__('Filterable Controls', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'filter_enable',
            [
                'label' => __('Enable Filter', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'eael_fg_all_label_text',
            [
                'label' => esc_html__('Gallery All Label', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default' => esc_html__('All', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'filter_enable' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'fg_all_label_icon',
            [
                'label' => __('All label icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        
        $this->add_control(
            'title_tag',
            [
                'label' => __('Select Title Tag', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h5',
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
            ]
        );
        
        $this->add_control(
            'eael_fg_controls',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['eael_fg_control' => 'Gallery Item'],
                ],
                'fields' => [
                    [
                        'name' => 'eael_fg_control',
                        'label' => esc_html__('List Item', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => ['active' => true],
                        'label_block' => true,
                        'default' => esc_html__('Gallery Item', 'essential-addons-for-elementor-lite'),
                        'ai' => [
                            'active' => false,
                        ],
                    ],
                    [
                        'name' => 'eael_fg_control_custom_id',
                        'label' => esc_html__('Custom ID', 'essential-addons-for-elementor-lite'),
                        'description' => esc_html__('Adding a custom ID will function as an anchor tag. For instance, if you input "test" as your custom ID, the link will change to "https://www.example.com/#test" and it will immediately open the corresponding tab.', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => ['active' => true],
                        'label_block' => true,
                        'default' => esc_html__('', 'essential-addons-for-elementor-lite'),
                        'ai' => [
                            'active' => false,
                        ],
                    ],
                    [
                        'name' => 'eael_fg_custom_label',
                        'label' => __('Custom Label', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'dynamic' => ['active' => true],
                        'return' => 'yes',
                        'default' => '',
                    ],
                    [
                        'name' => 'eael_fg_control_label',
                        'label' => esc_html__('Item Label', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => ['active' => true],
                        'label_block' => true,
                        'condition' => [
                            'eael_fg_custom_label' => 'yes',
                        ],
                        'ai' => [
                            'active' => false,
                        ],
                    ],
                    [
                        'name' => 'eael_fg_control_active_as_default',
                        'label' => __('Active as Default', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'dynamic' => ['active' => true],
                        'return' => 'yes',
                        'default' => '',
                    ],
                ],
                'title_field' => '{{eael_fg_control}}',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Filter Gallery Grid Settings
         */
        $this->start_controls_section(
            'eael_section_fg_grid_settings',
            [
                'label' => esc_html__('Gallery Items', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'photo_gallery',
            [
                'label' => __('Enable Photo Gallery', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        // YouTube.
		$this->add_control(
			'video_gallery_yt_privacy',
			[
				'label' => esc_html__( 'Video Privacy Mode', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'If enabled, YouTube won\'t store information about visitors unless they play the video.', 'essential-addons-for-elementor-lite' ),
				'frontend_available' => true,
                'default' => '',
			]
		);

        //Youtube video privacy notice
        $this->add_control(
			'eael_privacy_notice_control',
			[
				'label'        => esc_html__( 'Display Consent Notice', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
                'description'  => esc_html__( 'If enabled, The consent motice will appear before playing the video.', 'essential-addons-for-elementor-lite' ),
				'default'      => '',
			]
		);

        $this->add_control(
			'eael_privacy_notice',
			[
				'label'       => esc_html__( 'Privacy Notice', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
                'ai'          => [ 'active' => false, ],
                'condition'   => ['eael_privacy_notice_control' => 'yes' ]
			]
		);

        $this->add_control(
            'eael_item_randomize',
            [
                'label' => __('Randomize Item', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('YES', 'essential-addons-for-elementor-lite'),
                'label_off' => __('NO', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => '',
                'description' => __( 'Items will be displayed in a random order.', 'essential-addons-for-elementor-lite' )
            ]
        );

        $repeater = new Repeater();
        
        $repeater->add_control(
            'fg_video_gallery_switch',
            [
                'label' => __('Video Gallery?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'false',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_item_video_link',
            [
                'label' => esc_html__('Video Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'https://www.youtube.com/watch?v=kB4U67tiQLA',
                'condition' => [
                    'fg_video_gallery_switch' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_control_name',
            [
                'label' => esc_html__('Control Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => '',
                'description' => __('Use the gallery control name from Control Settings. Separate multiple items with comma (e.g. <strong>Gallery Item, Gallery Item 2</strong>)', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_item_name',
            [
                'label' => esc_html__('Item Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Gallery item name', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        
        $repeater->add_control(
            'fg_item_price_switch',
            [
                'label' => __('Enable Price ?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default'   => 'false',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value'  => 'true'
            ]
        );
        
        $repeater->add_control(
            'fg_item_price',
            [
                'label' => esc_html__('Item Price', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => esc_html__('$20.00', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'fg_item_price_switch' => 'true'
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $repeater->add_control(
            'fg_item_ratings_switch',
            [
                'label' => __('Enable Ratings ?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default'   => 'false',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value'  => 'true'
            ]
        );
        
        $repeater->add_control(
            'fg_item_ratings',
            [
                'label' => esc_html__('Item Ratings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'fg_item_ratings_switch' => 'true'
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $repeater->add_control(
            'fg_item_cat_switch',
            [
                'label' => __('Enable Category ?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default'   => 'false',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value'  => 'true'
            ]
        );
        
        $repeater->add_control(
            'fg_item_cat',
            [
                'label' => esc_html__('Item Category', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Essential Addons', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'fg_item_cat_switch' => 'true'
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_item_content',
            [
                'label' => esc_html__('Item Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, provident.', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_img',
            [
                'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'default' => [
                    'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/flexia-preview.jpg',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        
        $repeater->add_control(
            'fg_video_gallery_play_icon',
            [
                'label' => __('Video play icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => EAEL_PLUGIN_URL . 'assets/front-end/img/play-icon.png',
                ],
                'condition' => [
                    'fg_video_gallery_switch' => 'true',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_lightbox',
            [
                'label' => __('Gallery Lightbox Button?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'fg_video_gallery_switch!' => 'true',
                ],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_link',
            [
                'label' => __('Gallery Link Button?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'label_on' => esc_html__('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => esc_html__('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'fg_video_gallery_switch!' => 'true',
                ],
            ]
        );
        
        $repeater->add_control(
            'eael_fg_gallery_img_link',
            [
                'type' => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => '',
                ],
                'show_external' => true
            ]
        );
        
        $this->add_control(
            'eael_fg_gallery_items',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                    ['eael_fg_gallery_item_name' => 'Gallery Item Name'],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{eael_fg_gallery_item_name}}',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Gallery Load More Button
         */
        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'pagination',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'false',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'images_per_page',
            [
                'label' => __('Images Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => 6,
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'load_more_text',
            [
                'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('Load More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'nomore_items_text',
            [
                'label' => __('No More Items Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('No more items!', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'button_size',
            [
                'label' => __('Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __('Extra Small', 'essential-addons-for-elementor-lite'),
                    'sm' => __('Small', 'essential-addons-for-elementor-lite'),
                    'md' => __('Medium', 'essential-addons-for-elementor-lite'),
                    'lg' => __('Large', 'essential-addons-for-elementor-lite'),
                    'xl' => __('Extra Large', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_icon_new',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'load_more_icon',
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label' => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => __('After', 'essential-addons-for-elementor-lite'),
                    'before' => __('Before', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'load_more_align',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-loadmore' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        if (!apply_filters('eael/pro_enabled', false)) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __('Go Premium for More Features', 'essential-addons-for-elementor-lite')
                ]
            );
            
            $this->add_control(
                'eael_control_get_pro',
                [
                    'label' => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => '',
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
                ]
            );
            
            $this->end_controls_section();
        }
        
        /**
         * -------------------------------------------
         * Tab Style (Filterable Gallery Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_style_settings',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_fg_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_container_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-filter-gallery-wrapper',
            ]
        );
        
        $this->add_control(
            'eael_fg_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_shadow',
                'selector' => '{{WRAPPER}} .eael-filter-gallery-wrapper',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Filterable Gallery Control Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_control_style_settings',
            [
                'label' => esc_html__('Control', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style!' => 'layout_3'
                ]
            ]
        );
        $this->add_responsive_control(
            'eael_fg_control_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_control_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_fg_control_typography',
                'selector' => '{{WRAPPER}} .eael-filter-gallery-control ul li.control',
            ]
        );
        // Tabs
        $this->start_controls_tabs('eael_fg_control_tabs');
        
        // Normal State Tab
        $this->start_controls_tab('eael_fg_control_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);
        
        $this->add_control(
            'eael_fg_control_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_control_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control' => 'background: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_control_normal_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-filter-gallery-control ul > li.control',
            ]
        );
        
        $this->add_control(
            'eael_fg_control_normal_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul > li.control' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_control_shadow',
                'selector' => '{{WRAPPER}} .eael-filter-gallery-control ul li.control',
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_tab();
        
        // Active State Tab
        $this->start_controls_tab('eael_cta_btn_hover', ['label' => esc_html__('Active', 'essential-addons-for-elementor-lite')]);
        
        $this->add_control(
            'eael_fg_control_active_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_control_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control.active' => 'background: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_control_active_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-filter-gallery-control ul > li.control.active',
            ]
        );
        
        $this->add_control(
            'eael_fg_control_active_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-filter-gallery-control ul li.control.active' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_control_active_shadow',
                'selector' => '{{WRAPPER}} .eael-filter-gallery-control ul li.control.active',
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Filterable Gallery Item Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_item_style_settings',
            [
                'label' => esc_html__('Item', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_container_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_item_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_item_shadow',
                'selector' => '{{WRAPPER}} .eael-filterable-gallery-item-wrap .eael-gallery-grid-item',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Filterable Gallery Hoverer Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_item_cap_style_settings',
            [
                'label' => esc_html__('Mouseover Effect', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => ['hoverer']
                ],
            ]
        );
        
        $this->add_control( 'eael_section_fg_item_card_hover_note_hoverer', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'These controls will be in effect when the mouse hovers over the items.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

        $this->add_control(
            'eael_fg_item_cap_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.7)',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-hoverer-bg' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_cap_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_hover_title_typography_heading',
            [
                'label' => esc_html__('Title Typography', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_hover_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer .fg-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_hover_title_hover_color',
            [
                'label' => esc_html__('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer .fg-item-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_fg_item_hover_title_typography',
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer .fg-item-title',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_hover_content_typography_heading',
            [
                'label' => esc_html__('Content Typography', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_hover_content_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer .fg-item-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_fg_item_hover_content_typography',
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer .fg-item-content',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_item_cap_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-hoverer',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_item_cap_shadow',
                'selector' => '{{WRAPPER}} .gallery-item-thumbnail-wrap .gallery-item-caption-wrap',
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_hoverer_content_alignment',
            [
                'label' => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'eael-fg-hoverer-content-align-',
            ]
        );
        
        $this->end_controls_section();
        
        #only for layout 3
        $this->start_controls_section(
            'fg_item_thumb_style',
            [
                'label' => esc_html__('Thumbnail', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'fg_item_thubm_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fg-layout-3-item-thumb',
            ]
        );
        
        $this->add_responsive_control(
            'fg_item_thubm_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-item-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fg-layout-3-item .gallery-item-caption-wrap.card-hover-bg.caption-style-hoverer'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Filterable Gallery card Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_item_card_hover_style',
            [
                'label' => esc_html__('Mouseover Effect', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => ['card', 'layout_3']
                ],
            ]
        );
        
        $this->add_control( 'eael_section_fg_item_card_hover_note_card', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'These controls will be in effect when the mouse hovers over the items.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

        $this->add_control(
            'eael_fg_item_card_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.7)',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.card-hover-bg' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Video item Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_video_item_style',
            [
                'label' => esc_html__('Video', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style!' => 'layout_3'
                ]
            ]
        );
        
        $this->add_control(
            'eael_section_fg_video_item_mouseover_effect_heading',
            [
                'label' => esc_html__('Mouseover Effects', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_fg_video_item_hover_bg',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, .7)',
                'selectors' => [
                    '{{WRAPPER}} .video-popup-bg' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_video_item_hover_bg_trans',
            [
                'label' => esc_html__('Background transition', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'px' => 350,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-popup-bg' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_video_item_hover_icon_size',
            [
                'label' => esc_html__('Icon size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default' => [
                    'px' => 62,
                ],
                'range' => [
                    'px' => [
                        'max' => 150,
                    ],
                    'em' => [
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-popup > img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_video_item_icon_hover_scale',
            [
                'label' => esc_html__('Hover icon scale', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => '1.1',
                'selectors' => [
                    '{{WRAPPER}} .video-popup:hover > img' => 'transform: scale({{VALUE}});',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'eael_fg_video_item_icon_hover_scale_transition',
            [
                'label' => esc_html__('Icon transition', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'px' => 350,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-popup > img' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'eael_section_fg_lightbox_custom_width',
            [
                'label'     => __('Custom Width', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'   => '',
                'separator' => 'before',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
			'eael_section_fg_lightbox_video_width',
			[
				'label' => esc_html__( 'Video Content Width', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
                'widescreen_default' => [
                    'unit' => '%',
                ],
                'laptop_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'tablet_extra_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'mobile_extra_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
				'range' => [
					'%' => [
						'min' => 30,
					],
				],
                'devices' => [ 'widescreen', 'desktop', 'laptop', 'tablet', 'tablet_extra', 'mobile', 'mobile_extra' ],
                'selectors' => [
					'.mfp-container.mfp-iframe-holder .mfp-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'eael_section_fg_lightbox_custom_width' => 'yes',
                ]
			]
		);

        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Card Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_item_content_style_settings',
            [
                'label' => esc_html__('Item Card', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => ['card', 'layout_3']
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f2f9',
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .gallery-item-caption-wrap.caption-style-card' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .fg-layout-3-item-content' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'card'
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_layout_3_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-item-content' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_content_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-filterable-gallery-item-wrap .gallery-item-caption-wrap.caption-style-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .fg-layout-3-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_item_content_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-filterable-gallery-item-wrap .gallery-item-caption-wrap.caption-style-card, {{WRAPPER}} .fg-layout-3-item-content',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_fg_item_content_shadow',
                'selector' => '{{WRAPPER}} .eael-filterable-gallery-item-wrap .gallery-item-caption-wrap.caption-style-card, {{WRAPPER}} .fg-layout-3-item-content',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_title_typography_settings',
            [
                'label' => esc_html__('Title Typography', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F56A6A',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-title' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'card'
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_layout_3_content_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#031d3c',
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-item-content .fg-item-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_title_hover_color',
            [
                'label' => esc_html__('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fg-layout-3-item-content .fg-item-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_fg_item_content_title_typography',
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-title, {{WRAPPER}} .fg-layout-3-item-content .fg-item-title',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_text_typography_settings',
            [
                'label' => esc_html__('Content Typography', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_content_text_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-content' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'card'
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_layout_3_content_text_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7f8995',
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-item-content .fg-item-content p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_fg_item_content_text_typography',
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap.caption-style-card .fg-item-content, {{WRAPPER}} .fg-layout-3-item-content .fg-item-content p',
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_content_alignment',
            [
                'label' => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'eael-fg-card-content-align-',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * -------------------------------------------
         * Tab Style (Hoverer Icon Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_fg_item_hover_icons_style',
            [
                'label' => esc_html__('Icons', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs('fg_icons_style');
        
        $this->start_controls_tab(
            'fg_icons_style_normal',
            [
                'label'        => __('Normal', 'essential-addons-for-elementor-lite')
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff622a',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'background: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_icon_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_fg_item_icon_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_exact_size',
            [
                'label' => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 120,
                    ],
                    'em' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_size',
            [
                'label' => esc_html__('Icon Font Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                    'em' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_item_icon_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'fg_icons_style_hover',
            [
                'label'        => __('Hover', 'essential-addons-for-elementor-lite')
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff622a',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_color_hover',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_fg_item_icon_border_hover',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span:hover',
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span:hover' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_fg_item_icon_transition',
            [
                'label' => esc_html__('Transition', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gallery-item-caption-wrap .gallery-item-buttons > a span' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'fg_item_price_style',
            [
                'label' => esc_html__('Price', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_control(
            'fg_item_price_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-caption-head .fg-item-price' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'fg_item_price_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .fg-caption-head .fg-item-price'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'fg_item_ratings_style',
            [
                'label' => esc_html__('Ratings', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_control(
            'fg_item_ratings_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-caption-head .fg-item-ratings' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control(
            'fg_item_ratings_star_color',
            [
                'label' => __('Star Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-caption-head .fg-item-ratings i' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'fg_item_ratings_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .fg-caption-head .fg-item-ratings'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'fg_item_category_style',
            [
                'label' => esc_html__('Category', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_control(
            'fg_item_category_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-item-category span' => 'color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'fg_item_category_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .fg-item-category span'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'fg_item_category_background',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .fg-item-category span',
            ]
        );
        
        $this->add_responsive_control(
            'fg_item_category_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-item-category span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        
        $this->start_controls_section(
            'fg_search_form_style',
            [
                'label' => esc_html__('Search Form', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fg_caption_style' => 'layout_3'
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf_controls',
            [
                'label' => esc_html__('Controls', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'fg_sf_controls_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .fg-filter-trigger > span'
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_controls_icon_space',
            [
                'label' => esc_html__('Icon Space', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-trigger > i' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fg-filter-trigger img' => 'margin-left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        
        $this->add_responsive_control(
            'fg_sf_controls_icon_size',
            [
                'label' => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-trigger > i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .fg-filter-trigger img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_controls_width',
            [
                'label' => esc_html__('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                    '%' => [
                        'max'   => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap' => 'flex-basis: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf_controls_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#7f8995',
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap button' => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf_controls_background',
            [
                'label' => __('Controls Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap button' => 'background: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_controls_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_controls_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'fg_sf_controls_box_shadow',
                'selector' => '{{WRAPPER}} .fg-filter-wrap button'
            ]
        );
        
        $this->add_control(
            'fg_sf_separator',
            [
                'label' => esc_html__('Separator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'sf_left_border_size',
            [
                'label' => esc_html__('Separator Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap button' => 'border-right: {{SIZE}}px solid;',
                ]
            ]
        );
        
        $this->add_control(
            'sf_left_border_color',
            [
                'label' => __('Separator Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#abb5ff',
                'selectors' => [
                    '{{WRAPPER}} .fg-filter-wrap button' => 'border-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf',
            [
                'label' => esc_html__('Form', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        
        $this->add_control(
            'fg_sf_background',
            [
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filters-wrap .fg-layout-3-search-box' => 'background: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf_placeholder',
            [
                'label' => esc_html__('Placeholder', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default'   => __('Search Gallery Item...', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'fg_sf_placeholder_color',
            [
                'label' => __('Placeholder Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#858e9a',
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-search-box input[type="text"]::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fg-layout-3-search-box input[type="text"]::-moz-placeholder'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fg-layout-3-search-box input[type="text"]:-ms-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fg-layout-3-search-box input[type="text"]:-moz-placeholder'   => 'color: {{VALUE}}',
                    '{{WRAPPER}} .fg-layout-3-search-box input'   => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_form_width',
            [
                'label' => esc_html__('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                    '%' => [
                        'max'   => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-search-box' => 'flex-basis: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_form_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filters-wrap .fg-layout-3-search-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'fg_sf_form_box_shadow',
                'selector' => '{{WRAPPER}} .fg-layout-3-filters-wrap .fg-layout-3-search-box'
            ]
        );
        
        $this->add_control(
            'fg_sf_dropdown',
            [
                'label' => esc_html__('Dropdown', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'fg_sf_dropdown_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filter-controls li.control' => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control(
            'fg_sf_dropdown_hover_color',
            [
                'label' => __('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filter-controls li.control:hover' => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'    => 'fg_sf_dropdown_bg',
                'types'   => ['classic', 'gradient'],
                'exclude' => [
                    'image',
                ],
                'selector' => '{{WRAPPER}} .fg-layout-3-filter-controls',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'fg_sf_dropdown_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .fg-layout-3-filter-controls li.control'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'fg_sf_dropdown_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'selector' => '{{WRAPPER}} .fg-layout-3-filter-controls li.control'
            ]
        );
        
        $this->add_responsive_control(
            'fg_sf_dropdown_spacing',
            [
                'label' => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filter-controls li.control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        
        $this->add_responsive_control(
            'fg_sf_dropdown_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fg-layout-3-filter-controls.open-filters' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Not found text
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_not_found_text_style',
            [
                'label' => esc_html__('Not found text', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_fg_not_found_text_typography',
				'selector' => '{{WRAPPER}} #eael-fg-no-items-found',
			]
		);

        $this->add_control(
			'eael_fg_not_found_text_align',
			[
				'label' => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} #eael-fg-no-items-found' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'eael_fg_not_found_text_bg_color',
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} #eael-fg-no-items-found',
			]
		);

        $this->add_control(
            'eael_fg_not_found_text_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#858e9a',
                'selectors' => [
                    '{{WRAPPER}} #eael-fg-no-items-found' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_not_found_text_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} #eael-fg-no-items-found' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_not_found_text_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} #eael-fg-no-items-found' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_not_found_text_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} #eael-fg-no-items-found',
            ]
        );

        $this->add_control(
            'eael_not_found_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #eael-fg-no-items-found' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_not_found_text_shadow',
                'selector' => '{{WRAPPER}} #eael-fg-no-items-found',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Load More Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_loadmore_button_style',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_margin_top',
            [
                'label' => __('Top Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs('tabs_eael_load_more_button_style');
        
        $this->start_controls_tab(
            'tab_load_more_button_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_bg_color_normal',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_text_color_normal',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_button_border_normal',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-gallery-load-more',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_button_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .eael-gallery-load-more .eael-filterable-gallery-load-more-text',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more .eael-filterable-gallery-load-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-gallery-load-more img.eael-filterable-gallery-load-more-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_spacing',
            [
                'label' => __('Icon Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more .fg-load-more-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-gallery-load-more .fg-load-more-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'load_more_button_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_button_box_shadow',
                'selector' => '{{WRAPPER}} .eael-gallery-load-more',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_heading',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_icon!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'load_more_button_icon_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'placeholder' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more .eael-filterable-gallery-load-more-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_icon!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more:hover' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-gallery-load-more:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .eael-gallery-load-more:hover',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

	public function sorter_class( $string ) {
		$sorter_class = strtolower( $string );
		$sorter_class = str_replace( ' ', '-', $sorter_class );
		$sorter_class = str_replace( ',-', ' eael-cf-', $sorter_class );
		$sorter_class = str_replace( ',', 'comma', $sorter_class );
		$sorter_class = str_replace( '&', 'and', $sorter_class );
		$sorter_class = str_replace( '+', 'plus', $sorter_class );
		$sorter_class = str_replace( 'amp;', '', $sorter_class );
		$sorter_class = str_replace( '/', 'slash', $sorter_class );
		$sorter_class = str_replace( "'", 'apostrophe', $sorter_class );
		$sorter_class = str_replace( '"', 'apostrophe', $sorter_class );
		$sorter_class = str_replace( '.', '-', $sorter_class );
		$sorter_class = str_replace( '~', 'tilde', $sorter_class );
		$sorter_class = str_replace( '!', 'exclamation', $sorter_class );
		$sorter_class = str_replace( '@', 'at', $sorter_class );
		$sorter_class = str_replace( '#', 'hash', $sorter_class );
		$sorter_class = str_replace( '(', 'parenthesis', $sorter_class );
		$sorter_class = str_replace( ')', 'parenthesis', $sorter_class );
		$sorter_class = str_replace( '=', 'equal', $sorter_class );
		$sorter_class = str_replace( ';', 'semicolon', $sorter_class );
		$sorter_class = str_replace( ':', 'colon', $sorter_class );
		$sorter_class = str_replace( '<', 'lessthan', $sorter_class );
		$sorter_class = str_replace( '>', 'greaterthan', $sorter_class );
		$sorter_class = str_replace( '|', 'pipe', $sorter_class );
		$sorter_class = str_replace( '\\', 'backslash', $sorter_class );
		$sorter_class = str_replace( '^', 'caret', $sorter_class );
		$sorter_class = str_replace( '*', 'asterisk', $sorter_class );
		$sorter_class = str_replace( '$', 'dollar', $sorter_class );
		$sorter_class = str_replace( '%', 'percent', $sorter_class );
		$sorter_class = str_replace( '`', 'backtick', $sorter_class );
		$sorter_class = str_replace( '[', 'bracket', $sorter_class );
		$sorter_class = str_replace( ']', 'bracket', $sorter_class );
		$sorter_class = str_replace( '{', 'curlybracket', $sorter_class );
		$sorter_class = str_replace( '}', 'curlybracket', $sorter_class );
		$sorter_class = str_replace( '?', 'questionmark', $sorter_class );

        if ( function_exists('mb_convert_encoding') ) {
            $sorter_class = mb_convert_encoding( $sorter_class, 'UTF-8' );
        } else {
            $sorter_class = utf8_encode( $sorter_class );
        }

		return $sorter_class;
	}
    
    protected function render_filters()
    {
        $settings = $this->get_settings_for_display();
        $all_text = ($settings['eael_fg_all_label_text'] != '') ? Helper::eael_wp_kses($settings['eael_fg_all_label_text']) : esc_html__('All', 'essential-addons-for-elementor-lite');
        
        if ($settings['filter_enable'] == 'yes') {
            ?>
            <div class="eael-filter-gallery-control">
                <ul><?php
                    if ($settings['eael_fg_all_label_text']) {
                        ?><li data-load-more-status="0" data-first-init="1" class="control all-control <?php if( ! $this->custom_default_control ) : ?> active <?php endif; ?>" data-filter="*"><?php echo $all_text; ?></li><?php
                    }

                    foreach ($settings['eael_fg_controls'] as $key => $control) :
                        $sorter_filter = $this->sorter_class($control['eael_fg_control']);
                        $sorter_label  = $control['eael_fg_control_label'] != '' ? $control['eael_fg_control_label'] : $control['eael_fg_control'];
                        $custom_id = sanitize_text_field( $control['eael_fg_control_custom_id'] ) ?? "";

                    ?><li <?php if ( $custom_id ) : ?> id="<?php echo esc_attr( $custom_id ); ?>" <?php endif; ?> data-load-more-status="0" data-first-init="0"
                        class="control <?php if ( $this->custom_default_control ) {
                            if ( $this->default_control_key === $key ){
                                echo 'active';
                            }
                        } ?>" data-filter=".eael-cf-<?php echo esc_attr($sorter_filter); ?>"><?php echo esc_html( $sorter_label ); ?></li><?php
                    endforeach;
                ?></ul>
            </div>
            <?php
        }
    }
    
    protected function render_layout_3_filters()
    {
        $settings = $this->get_settings_for_display();
        if ($settings['filter_enable'] == 'yes') {
            ?>
            <div class="fg-layout-3-filters-wrap">
                <div class="fg-filter-wrap">
                    <button id="fg-filter-trigger" class="fg-filter-trigger">
                        <span>
                            <?php
                            if ($settings['eael_fg_all_label_text']) {
                                echo Helper::eael_wp_kses($settings['eael_fg_all_label_text']);
                            } elseif (isset($settings['eael_fg_controls']) && !empty($settings['eael_fg_controls'])) {
                                echo $settings['eael_fg_controls'][0]['eael_fg_control'];
                            }
                            ?>
                        </span>
                        <?php
                        if (isset($settings['fg_all_label_icon']) && !empty($settings['fg_all_label_icon'])) {
                            if (isset($settings['fg_all_label_icon']['value']['url'])) {
                                echo '<img src="' . esc_url( $settings['fg_all_label_icon']['value']['url'] ) . '" alt="' . esc_attr(get_post_meta($settings['fg_all_label_icon']['value']['id'], '_wp_attachment_image_alt', true)) . '" />';
                            } else {
                                echo '<i class="' . esc_attr( $settings['fg_all_label_icon']['value'] ) . '"></i>';
                            }
                        } else {
                            echo '<i class="fas fa-angle-down"></i>';
                        }
                        ?>

                    </button>
                    <ul class="fg-layout-3-filter-controls">
                        <?php if ($settings['eael_fg_all_label_text']) { ?>
                            <li class="control <?php if( ! $this->custom_default_control ) : ?> active <?php endif; ?>" data-filter="*"><?php echo Helper::eael_wp_kses($settings['eael_fg_all_label_text']); ?></li>
                        <?php } ?>
                        
                        <?php foreach ($settings['eael_fg_controls'] as $key => $control) :
                            $sorter_filter = $this->sorter_class($control['eael_fg_control']);
                            $custom_id = sanitize_text_field( $control['eael_fg_control_custom_id'] ) ?? "";
                        ?>
                            <li <?php if ( $custom_id ) : ?> id="<?php echo esc_attr( $custom_id ); ?>" <?php endif; ?> class="control <?php if ( $this->custom_default_control ) {
                                if ( $this->default_control_key === $key ){
                                    echo 'active';
                                }
                            } ?>" data-filter=".eael-cf-<?php echo esc_attr($sorter_filter); ?>"><?php echo esc_html__($control['eael_fg_control']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <form class="fg-layout-3-search-box" id="fg-layout-3-search-box" autocomplete="off">
                    <input type="text" id="fg-search-box-input" name="fg-frontend-search" placeholder="<?php echo esc_attr( $settings['fg_sf_placeholder'] ); ?>" />
                </form>

            </div>
            <?php
        }
    }
    
    protected function render_loadmore_button()
    {
        $settings = $this->get_settings_for_display();
        $icon_migrated = isset($settings['__fa4_migrated']['load_more_icon_new']);
        $icon_is_new = empty($settings['load_more_icon']);
        
        $this->add_render_attribute('load-more-button', 'class', [
            'eael-gallery-load-more',
            'elementor-button',
            'elementor-size-' . $settings['button_size'],
        ]);
        
        if ($settings['pagination'] == 'yes') { ?>
            <div class="eael-filterable-gallery-loadmore">
                <a href="#" <?php echo $this->get_render_attribute_string('load-more-button'); ?>>
                    <span class="eael-btn-loader"></span>
                    <?php if ($settings['button_icon_position'] == 'before') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-filterable-gallery-load-more-icon fg-load-more-icon-left" src="<?php echo esc_url($settings['load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-filterable-gallery-load-more-icon fg-load-more-icon-left <?php echo esc_attr($settings['load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-filterable-gallery-load-more-icon fg-load-more-icon-left <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                    <span class="eael-filterable-gallery-load-more-text">
                        <?php echo Helper::eael_wp_kses($settings['load_more_text']); ?>
                    </span>
                    <?php if ($settings['button_icon_position'] == 'after') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-filterable-gallery-load-more-icon fg-load-more-icon-right" src="<?php echo esc_url($settings['load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-filterable-gallery-load-more-icon fg-load-more-icon-right <?php echo esc_attr($settings['load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-filterable-gallery-load-more-icon fg-load-more-icon-right <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                </a>
            </div>
        <?php }
    }
    
    protected function gallery_item_store()
    {
        $settings = $this->get_settings_for_display();
        $gallery_items = $settings['eael_fg_gallery_items'];
        $gallery_store = [];
        $counter = 0;
        $video_gallery_yt_privacy = ! empty( $settings['video_gallery_yt_privacy'] ) && 'yes' === $settings['video_gallery_yt_privacy'] ? 1 : 0;
        
        foreach ($gallery_items as $gallery) {
            $gallery_store[$counter]['title'] = Helper::eael_wp_kses($gallery['eael_fg_gallery_item_name']);
            $gallery_store[$counter]['content'] = $gallery['eael_fg_gallery_item_content'];
            $gallery_store[$counter]['id'] = $gallery['_id'];
            $gallery_store[$counter]['image'] = $gallery['eael_fg_gallery_img'];
            $gallery_store[$counter]['image'] = sanitize_url( $gallery['eael_fg_gallery_img']['url'] );
            $gallery_store[$counter]['image_id'] = $gallery['eael_fg_gallery_img']['id'];
            $gallery_store[$counter]['maybe_link'] = $gallery['eael_fg_gallery_link'];
            $gallery_store[$counter]['link'] = $gallery['eael_fg_gallery_img_link'];
            
            $gallery_store[$counter]['video_gallery_switch'] = $gallery['fg_video_gallery_switch'];

            $gallery['eael_fg_gallery_item_video_link'] = empty( $gallery['eael_fg_gallery_item_video_link'] ) ? '' : $gallery['eael_fg_gallery_item_video_link'];
            if (strpos($gallery['eael_fg_gallery_item_video_link'], 'youtu.be') != false) {
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $gallery['eael_fg_gallery_item_video_link'], $matches);
                $video_link = !empty($matches) ? sprintf('https://www.youtube.com/watch?v=%s', $matches[1]) : '';
                $gallery_store[$counter]['video_link'] = $video_link;
            } else {
                $gallery_store[$counter]['video_link'] = $gallery['eael_fg_gallery_item_video_link'];
            }
            
            if ( $video_gallery_yt_privacy ){
                if ( strpos( $gallery_store[$counter]['video_link'], 'youtube' ) != false ) {
                    $gallery_store[$counter]['video_link'] = str_replace('youtube.com/watch?v=', 'youtube-nocookie.com/embed/', $gallery_store[$counter]['video_link']);
                }

                if ( strpos( $gallery_store[$counter]['video_link'], 'vimeo' ) != false ) {
                    $gallery_store[$counter]['video_link'] = esc_url( add_query_arg( [ 'dnt' => 1 ], $gallery_store[$counter]['video_link'] ) );
                }
            }

            $gallery_store[$counter]['show_lightbox'] = $gallery['eael_fg_gallery_lightbox'];
            $gallery_store[$counter]['play_icon'] = $gallery['fg_video_gallery_play_icon'];
            $gallery_store[$counter]['controls'] = $this->sorter_class($gallery['eael_fg_gallery_control_name']);
            $gallery_store[$counter]['price_switch'] = $gallery['fg_item_price_switch'];
            $gallery_store[$counter]['price'] = $gallery['fg_item_price'];
            $gallery_store[$counter]['ratings_switch'] = $gallery['fg_item_ratings_switch'];
            $gallery_store[$counter]['ratings'] = $gallery['fg_item_ratings'];
            $gallery_store[$counter]['category_switch'] = $gallery['fg_item_cat_switch'];
            $gallery_store[$counter]['category'] = $gallery['fg_item_cat'];
            $counter++;
        }
        
        return $gallery_store;
    }

    /**
     * Generating gallery item full image clickable content
     *
     * @since 4.7.5
     * @param array $settings : Elementor provided settings
     * @param array $item : Gallery item
     * @param boolean $check_popup_status
     * @return string : Html markup
     */
    public function gallery_item_full_image_clickable_content($settings, $item, $check_popup_status=true){
        $html = '';
        $magnific_class = "eael-magnific-link eael-magnific-link-clone active";
        $title = '';
        
        if ( $settings['eael_section_fg_mfp_caption'] === 'yes' ){
            $title = $item['title'];
        }

        if ( $settings['eael_fg_show_popup'] === 'media' && $settings['eael_section_fg_full_image_action'] === 'link'  ){
            $magnific_class = '';
        }

        if($check_popup_status){
            if ($settings['eael_section_fg_full_image_action'] === 'lightbox' && !$this->popup_status) {
                $this->popup_status = true;
                $html .= '<a href="' . esc_url($item['image']) . '" class="'. $magnific_class .' media-content-wrap active" data-elementor-open-lightbox="no" title="' . esc_attr( $title ) . '">';
            }
        }else {
            if ($settings['eael_section_fg_full_image_action'] === 'lightbox') {
                $html .= '<a href="' . esc_url($item['image']) . '" class="'. $magnific_class .' media-content-wrap active" data-elementor-open-lightbox="no" title="' . esc_attr( $title ) . '">';
            }
        }

		if ( $settings['eael_section_fg_full_image_action'] === 'link' ) {
            static $ea_link_repeater_index = 0;
            $link_key = 'link_' . $ea_link_repeater_index++;

            if ( ! empty( $item['link'] ) && is_array( $item['link'] ) ) {
                $this->add_link_attributes( $link_key, $item['link'] );
            }

            $html .= '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
        }

        return $html;
    }

    /**
     * Generating video gallery item thumbnail content
     *
     * @since 4.7.5
     * @param array $settings : Elementor provided settings
     * @param array $item : Gallery item
     * @return string : Html markup
     */
    protected function gallery_item_thumbnail_content($settings, $item){
        

        $html = '<img src="' . esc_url( $item['image'] ) . '" data-lazy-src="' . esc_url( $item['image'] ) . '" alt="' . esc_attr( get_post_meta( $item['image_id'], '_wp_attachment_image_alt', true ) ) . '" class="gallery-item-thumbnail">';

        if ( empty($settings['eael_section_fg_full_image_clickable']) && $item['video_gallery_switch'] !== 'true' ) {
            if ($settings['eael_fg_show_popup'] == 'buttons' && $settings['eael_fg_caption_style'] === 'card') {
                $html .= '<div class="gallery-item-caption-wrap card-hover-bg caption-style-hoverer ' . esc_attr( $settings['eael_fg_grid_hover_style'] ) . '">
                            ' . $this->render_fg_buttons($settings, $item) . '
                        </div>';
            } elseif ( $settings['eael_fg_show_popup'] === 'media' && $settings['eael_fg_caption_style'] === 'card' ) {
                $html .= '<div class="gallery-item-caption-wrap card-hover-bg caption-style-hoverer ' . esc_attr( $settings['eael_fg_grid_hover_style'] ) . '"></div>';
            }
        }

        if (isset($item['video_gallery_switch']) && ($item['video_gallery_switch'] === 'true')) {
            $html .= $this->video_gallery_switch_content( $item, true, $settings );
        }

        return $html;
    }

    /**
     * Generating video gallery switch content
     *
     * @since 4.7.5
     * @param array $item : Gallery item
     * @param boolean $show_video_popup_bg
     * @return string : Html markup
     */
    protected function video_gallery_switch_content( $item, $show_video_popup_bg=true, $settings = null ) {
        $html = '';

        $icon_url = isset($item['play_icon']['url']) ? $item['play_icon']['url'] : '';
        $video_url = isset($item['video_link']) ? $item['video_link'] : '#';
        $eael_privacy_notice = isset( $settings['eael_privacy_notice'] ) ? $settings['eael_privacy_notice'] : '';

        $html .= '<a title="'.esc_attr( $eael_privacy_notice ).'" aria-label="eael-magnific-video-link" href="' . esc_url($video_url) . '" class="video-popup eael-magnific-link eael-magnific-link-clone active eael-magnific-video-link mfp-iframe">';

        if( $show_video_popup_bg ){
            $html .= '<div class="video-popup-bg"></div>';
        }

        if (!empty($icon_url)) {
            $html .= '<img width="62" height="62" src="' . esc_url($icon_url) . '" alt="eael-fg-video-play-icon" >';
        }

        $html .= '</a>';

        return $html;
    }

    /**
     * Generating caption content for gallery item
     *
     * @since 4.7.5
     * @param array $settings : Elementor provided settings
     * @param array $item : Gallery item
     * @param string $caption_style
     * @return string : Html markup
     */
    protected function gallery_item_caption_content($settings, $item, $caption_style){
        $html = '<div class="gallery-item-caption-wrap ' . esc_attr( $caption_style . ' ' . $settings['eael_fg_grid_hover_style'] ) . '">';

        if ('hoverer' == $settings['eael_fg_caption_style']) {
            $html .= '<div class="gallery-item-hoverer-bg"></div>';
        }

        $html .= '<div class="gallery-item-caption-over">';
        if (isset($item['title']) && !empty($item['title']) || isset($item['content']) && !empty($item['content'])) {
            if (!empty($item['title'])) {
                $title_link_open = $title_link_close = '';
                if ( $settings['eael_title_clickable'] === 'yes' ){
                    static $ea_link_repeater_index = 0;
	                $link_key = 'link_' . $ea_link_repeater_index++;
                    if ( empty( $this->get_render_attribute_string( $link_key ) ) ){
	                    $link_key = 'eael_link_' . $ea_link_repeater_index++;
                        $this->add_link_attributes( $link_key, $item['link'] );
                    }
                    $title_link_open = '<a '. $this->get_render_attribute_string( $link_key ) . '>';
                    $title_link_close = '</a>';
                }

                $html .= $title_link_open . '<' . Helper::eael_validate_html_tag($settings['title_tag']) . ' class="fg-item-title">' . $item['title'] . '</' . Helper::eael_validate_html_tag($settings['title_tag']) . '>' . $title_link_close;
            }

            if (!empty($item['content'])) {
                $html .= '<div class="fg-item-content">' . wpautop($item['content']) . '</div>';
            }
        }

        if ($settings['eael_fg_show_popup'] == 'buttons' && $settings['eael_fg_caption_style'] !== 'card') {
            if (empty($settings['eael_section_fg_full_image_clickable'])) {
                $html .= ($this->render_fg_buttons($settings, $item));
            }
        }
        $html .= '</div>';

        $html .= '</div>';

        if ($settings['eael_section_fg_full_image_clickable']) {
            $html .= '</a>';
        }

        return $html;
    }

    protected function render_fg_buttons($settings, $item)
    {
        $zoom_icon_migrated = isset($settings['__fa4_migrated']['eael_section_fg_zoom_icon_new']);
        $zoom_icon_is_new = empty($settings['eael_section_fg_zoom_icon']);
        $link_icon_migrated = isset($settings['__fa4_migrated']['eael_section_fg_link_icon_new']);
        $link_icon_is_new = empty($settings['eael_section_fg_link_icon']);
        $title = '';
        
        if ( $settings['eael_section_fg_mfp_caption'] === 'yes' ){
            $title = $item['title'];
        }
        
        ob_start();
        
        echo '<div class="gallery-item-buttons">';
        
        if ($item['show_lightbox'] == true) {
            echo '<a aria-label="eael-magnific-link" href="' . esc_url($item['image']) . '" class="eael-magnific-link eael-magnific-link-clone active" data-elementor-open-lightbox="no" title="' . esc_attr( $title ) . '">';

            echo '<span class="fg-item-icon-inner">';
            if ($zoom_icon_is_new || $zoom_icon_migrated) {
                if (isset($settings['eael_section_fg_zoom_icon_new']['value']['url'])) {
                    echo '<img src="' . esc_url( $settings['eael_section_fg_zoom_icon_new']['value']['url'] ) . '" alt="' . esc_attr(get_post_meta($settings['eael_section_fg_zoom_icon_new']['value']['id'], '_wp_attachment_image_alt', true)) . '" />';
                } else if (isset($settings['eael_section_fg_zoom_icon_new']['value'])) {
                    echo '<i class="' . esc_attr( $settings['eael_section_fg_zoom_icon_new']['value'] ) . '" aria-hidden="true"></i>';
                }
            } else {
                echo '<i class="' . esc_attr( $settings['eael_section_fg_zoom_icon'] ) . '" aria-hidden="true"></i>';
            }
            echo '</span>
            </a>';
        }
        
        if ($item['maybe_link'] == 'true') {
            if ( !empty( $item['link']['url'] ) ) {
                static $ea_link_repeater_index = 0;
	            $link_key = 'link_' . $ea_link_repeater_index++;

	            $this->add_link_attributes( $link_key, $item['link'] );
                $this->add_render_attribute( $link_key, 'aria-label', 'eael-item-maybe-link' );
                ?>
                <a <?php $this->print_render_attribute_string( $link_key ); ?>> <?php
                echo '<span class="fg-item-icon-inner">';
                
                if ($link_icon_is_new || $link_icon_migrated) {
                    if (isset($settings['eael_section_fg_link_icon_new']['value']['url'])) {
                        echo '<img src="' . esc_url( $settings['eael_section_fg_link_icon_new']['value']['url'] ) . '" alt="' . esc_attr(get_post_meta($settings['eael_section_fg_link_icon_new']['value']['id'], '_wp_attachment_image_alt', true)) . '" />';
                    } else {
                        echo '<i class="' . esc_attr( $settings['eael_section_fg_link_icon_new']['value'] ) . '" aria-hidden="true"></i>';
                    }
                } else {
                    echo '<i class="' . esc_attr( $settings['eael_section_fg_link_icon'] ) . '" aria-hidden="true"></i>';
                }
                
                echo '</span>';
                echo '</a>';
            }
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }
    
    protected function render_layout_3_gallery_items($init_show = 0)
    {
        $settings = $this->get_settings_for_display();
        $gallery = $this->gallery_item_store();
        $gallery_markup = [];
        
        foreach ($gallery as $item) {
            $html = '<div class="eael-filterable-gallery-item-wrap eael-cf-' . $item['controls'] . '" data-search-key="' . strtolower(str_replace(" ", "-", $item['title'])) . '">';
            $html .= '<div class="fg-layout-3-item eael-gallery-grid-item">';
            
            if ($settings['eael_section_fg_full_image_clickable']) {
                $html .= $this->gallery_item_full_image_clickable_content($settings, $item, false);
            }
            
            if (isset($item['video_gallery_switch']) && ($item['video_gallery_switch'] === 'true') 
            && isset($settings['eael_section_fg_full_image_clickable']) && $settings['eael_section_fg_full_image_clickable'] === 'yes') {
                $html .= '<div class="gallery-item-thumbnail-wrap fg-layout-3-item-thumb video_gallery_switch_on">';
            } else {
                $html .= '<div class="gallery-item-thumbnail-wrap fg-layout-3-item-thumb">';
            }
            
            $html .= '<img src="' . esc_url( $item['image'] ) . '" data-lazy-src="' . esc_url( $item['image'] ) . '" alt="' . esc_attr(get_post_meta($item['image_id'], '_wp_attachment_image_alt', true)) . '" class="gallery-item-thumbnail">';
            
            $html .= '<div class="gallery-item-caption-wrap card-hover-bg caption-style-hoverer">';
            $html .= '<div class="fg-caption-head">';
            if (isset($item['price_switch']) && $item['price_switch'] == 'true') {
                $html .= '<div class="fg-item-price">' . $item['price'] . '</div>';
            }
            if (isset($item['ratings_switch']) && $item['ratings_switch'] == 'true') {
                $html .= '<div class="fg-item-ratings"><i class="fas fa-star"></i> ' . $item['ratings'] . '</div>';
            }
            $html .= '</div>';

            if (isset($item['video_gallery_switch']) && ($item['video_gallery_switch'] === 'true')) {
                $html .= $this->video_gallery_switch_content( $item, false );
            } else {
                if (empty($settings['eael_section_fg_full_image_clickable'])) {
                    $html .= $this->render_fg_buttons($settings, $item);
                }
            }
            
            $html .= '</div>';
            
            $html .= '</div>';
            
            if ($settings['eael_section_fg_full_image_clickable']) $html .= '</a>';
            
            $html .= '<div class="fg-layout-3-item-content">';
            
            if (isset($item['category_switch']) && $item['category_switch'] == 'true') {
                $html .= '<div class="fg-item-category"><span>' . $item['category'] . '</span></div>';
            }
            $title_link_open = $title_link_close = '';
            if ( $settings['eael_title_clickable'] === 'yes' ){
                static $ea_link_repeater_index = 0;
                $link_key = 'link_' . $ea_link_repeater_index++;
                if ( empty( $this->get_render_attribute_string( $link_key ) ) ){
                    $link_key = 'eael_link_' . $ea_link_repeater_index++;
                    $this->add_link_attributes( $link_key, $item['link'] );
                }
                $title_link_open = '<a '. $this->get_render_attribute_string( $link_key ) . '>';
                $title_link_close = '</a>';
            }

            $html .= $title_link_open . '<' . Helper::eael_validate_html_tag($settings['title_tag']) . ' class="fg-item-title">' . $item['title'] . '</' . Helper::eael_validate_html_tag($settings['title_tag']) . '>' . $title_link_close;
            $html .= '<div class="fg-item-content">' . wpautop($item['content']) . '</div>';
            $html .= '</div>';
            
            $html .= '</div>';
            $html .= '</div>';
            
            $gallery_markup[] = $html;
        }
        return $gallery_markup;
    }
    
    protected function render_gallery_items($init_show = 0)
    {
        $settings = $this->get_settings_for_display();
        $gallery = $this->gallery_item_store();
        $gallery_markup = [];
        $caption_style = $settings['eael_fg_caption_style'] == 'card' ? 'caption-style-card' : 'caption-style-hoverer';
        $magnific_class = "eael-magnific-link eael-magnific-link-clone active";

        if( $settings['eael_fg_show_popup'] === 'media' && $settings['eael_section_fg_full_image_action'] === 'link' ){
            $magnific_class = '';
        }

        foreach ($gallery as $item) {
            $this->popup_status = false;
            $close_media_content_wrap = false;

            $title = '';
        
            if ( $settings['eael_section_fg_mfp_caption'] === 'yes' ){
                $title = $item['title'];
            }

            if ($item['controls'] != '') {
                $html = '<div class="eael-filterable-gallery-item-wrap eael-cf-' . $item['controls'] . '">
				<div class="eael-gallery-grid-item">';
            } else {
                $html = '<div class="eael-filterable-gallery-item-wrap">
				<div class="eael-gallery-grid-item">';
            }
            
            if (
                $settings['eael_fg_caption_style'] === 'card'
                && $item['video_gallery_switch'] != 'true'
                && $settings['eael_fg_show_popup'] === 'media'
            ) {
                $this->popup_status = true;
                $close_media_content_wrap = true;
                $html .= '<a  href="' . esc_url($item['image']) . '" class="'. $magnific_class .' media-content-wrap" data-elementor-open-lightbox="no" title="' . esc_attr( $title ) . '">';
            }

            if ($settings['eael_section_fg_full_image_clickable']) {
                $html .= $this->gallery_item_full_image_clickable_content($settings, $item);
            }

            if (isset($item['video_gallery_switch']) && ($item['video_gallery_switch'] === 'true')
            && isset($settings['eael_section_fg_full_image_clickable']) && $settings['eael_section_fg_full_image_clickable'] === 'yes') {
                $html .= '<div class="gallery-item-thumbnail-wrap video_gallery_switch_on">';
            } else {
                $html .= '<div class="gallery-item-thumbnail-wrap">';
            }

            $html .= $this->gallery_item_thumbnail_content($settings, $item);

            $html .= '</div>';

            if ($close_media_content_wrap) {
                $html .= '</a>';
            }

            if ($settings['eael_fg_show_popup'] == 'media' && $settings['eael_fg_caption_style'] !== 'card' && !$this->popup_status) {
                $html .= '<a href="' . esc_url($item['image']) . '" class="'. $magnific_class .' media-content-wrap" data-elementor-open-lightbox="no" title="' . esc_attr( $title ) . '">';
            }


            if ($item['video_gallery_switch'] != 'true' || $settings['eael_fg_caption_style'] == 'card') {
                $html .= $this->gallery_item_caption_content($settings, $item, $caption_style);
            }

            if ($settings['eael_fg_show_popup'] == 'media') {
                $html .= '</a>';
            }

            if ($settings['eael_section_fg_full_image_clickable']) {
                $html .= '</a>';
            }

            $html .= '</div></div>';

            $gallery_markup[] = $html;
        }

        return $gallery_markup;
    }

    protected function render_media_query( $settings ){
        $media_query = '';
        $section_id  = $this->get_id();
        $breakpoints = method_exists( Plugin::$instance->breakpoints, 'get_breakpoints_config' ) ? Plugin::$instance->breakpoints->get_breakpoints_config() : [];
        $brp_desktop = isset( $breakpoints['widescreen'] ) ? $breakpoints['widescreen']['value'] - 1 : 2400;

        $media_query .= '@media only screen and (max-width: '. $brp_desktop .'px) {
					.elementor-element.elementor-element-'. $section_id .' .eael-filterable-gallery-item-wrap {
					        width: '. 100/$settings["columns"] .'%;
					    }
					}';
        if ( !empty( $breakpoints ) ){
            $breakpoints = array_reverse( $breakpoints );
            foreach ( $breakpoints as $device => $breakpoint ){
                if ( empty( $settings['columns_'.$device] ) && in_array( $device, ['mobile', 'tablet'] ) ) {
                    $settings['columns_'.$device] = $device === 'mobile' ? 1 : 2;
                }
                if ( !empty( $settings['columns_'.$device] ) && $breakpoint['is_enabled'] ){
                    $media_query .= '@media only screen and ('. $breakpoint['direction'] .'-width: '. $breakpoint['value'] .'px) {
					.elementor-element.elementor-element-'. $section_id .'  .eael-filterable-gallery-item-wrap {
					        width: '. 100/$settings["columns_".$device] .'%;
					    }
					}';
                }
            }
        }

        echo '<style id="eael-fg-inline-css-'. $section_id .'">'. __( $media_query ) .'</style>';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (!empty($settings['eael_fg_filter_duration'])) {
            $filter_duration = $settings['eael_fg_filter_duration'];
        } else {
            $filter_duration = 500;
        }
        
        $this->add_render_attribute(
            'gallery',
            [
                'id' => 'eael-filter-gallery-wrapper-' . esc_attr($this->get_id()),
                'class' => 'eael-filter-gallery-wrapper',
                'data-layout-mode'  => $settings['eael_fg_caption_style']
            ]
        );
        
        $gallery_settings = [
            'grid_style' => $settings['eael_fg_grid_style'],
            'popup' => $settings['eael_fg_show_popup'],
            'duration' => $filter_duration,
            'gallery_enabled' => $settings['photo_gallery'],
            'video_gallery_yt_privacy' => $settings['video_gallery_yt_privacy'],
            'control_all_text' => $settings['eael_fg_all_label_text'],
        ];
        
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $gallery_settings['post_id'] = \Elementor\Plugin::$instance->editor->get_post_id();
        } else {
            $gallery_settings['post_id'] = get_the_ID();
        }
        if ( method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_breakpoints_config' ) && ! empty( $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_breakpoints_config() ) ) {

            $this->add_render_attribute('gallery', 'data-breakpoints', wp_json_encode( $breakpoints ) );
        }

        $gallery_settings['widget_id'] = $this->get_id();
        
        $no_more_items_text = Helper::eael_wp_kses($settings['nomore_items_text']);
        $grid_class = $settings['eael_fg_grid_style'] == 'grid' ? 'eael-filter-gallery-grid' : 'masonry';

        if ('layout_3' == $settings['eael_fg_caption_style']) {
            $gallery_items = $items = $this->render_layout_3_gallery_items();
        }
        else {
            $gallery_items = $items = $this->render_gallery_items();
        }

        if ( $settings['eael_item_randomize'] === 'yes' ){
            shuffle($gallery_items);
        }

        $this->add_render_attribute('gallery-items-wrap', [
            'class' => [
                'eael-filter-gallery-container',
                $grid_class
            ],
            'data-images-per-page' => $settings['images_per_page'],
            'data-total-gallery-items' => count($settings['eael_fg_gallery_items']),
            'data-nomore-item-text' => $no_more_items_text,
        ]);

        $this->add_render_attribute('gallery-items-wrap', 'data-settings', wp_json_encode($gallery_settings));
        $this->add_render_attribute('gallery-items-wrap', 'data-search-all', esc_attr( $settings['eael_search_among_all'] ));
        $this->add_render_attribute( 'gallery-items-wrap', 'data-gallery-items', wp_json_encode( $gallery_items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ) );
        $this->add_render_attribute('gallery-items-wrap', 'data-init-show', esc_attr($settings['eael_fg_items_to_show']));
        $this->render_media_query( $settings );

        $this->custom_default_control = empty( $settings['eael_fg_all_label_text'] ) ? true : false;

        foreach ( $settings['eael_fg_controls'] as $key_default => $control_default ) :
            if ( ! empty( $control_default['eael_fg_control_active_as_default'] ) && 'yes' === $control_default['eael_fg_control_active_as_default'] ) {
                $this->default_control_key = $key_default;  
                $this->custom_default_control = true;
            }
        endforeach;

        $this->add_render_attribute('gallery', 'data-default_control_key', esc_attr( $this->default_control_key ) );
        $this->add_render_attribute('gallery', 'data-custom_default_control', esc_attr( $this->custom_default_control ) );
        ?>
        <div <?php echo $this->get_render_attribute_string('gallery'); ?>>
            
            <?php
            if ('layout_3' == $settings['eael_fg_caption_style'])
                $this->render_layout_3_filters();
            else
                $this->render_filters();
            ?>

            <div <?php echo $this->get_render_attribute_string('gallery-items-wrap'); ?>>
                <?php
                $init_show = absint($settings['eael_fg_items_to_show']);

                for ($i = 0; $i < $init_show; $i++) {

                    if (array_key_exists($i, $gallery_items)) {
                        echo $gallery_items[$i];
                    }
                }
                if ( $settings['eael_fg_caption_style'] === 'layout_3' ):
                ?>
                <div id="eael-fg-no-items-found" style="display:none;">
                    <?php
                       echo Helper::eael_wp_kses( $settings['eael_fg_not_found_text'] );
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php
            if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
                $this->render_editor_script();
            }
            $this->render_loadmore_button();
            ?>
        </div>
        
        <?php
    }
    
    /**
     * Render masonry script
     *
     * @access protected
     */
    protected function render_editor_script()
    { ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.eael-filter-gallery-container').each(function() {
                    var $node_id = '<?php echo $this->get_id(); ?>',
                        $scope = $('[data-id="' + $node_id + '"]'),
                        $gallery = $(this),
                        $settings = $gallery.data('settings'),
						fg_items = $gallery_items = $gallery.data('gallery-items'),
                        $layout_mode = ($settings.grid_style == 'masonry' ? 'masonry' : 'fitRows'),
                        $gallery_enabled = ($settings.gallery_enabled == 'yes' ? true : false),
                        input = $scope.find('#fg-search-box-input'),
                        searchRegex, buttonFilter, timer;
					    $init_show_setting     = $gallery.data("init-show");
					fg_items.splice(0, $init_show_setting)
                    var filterControls = $scope.find(".fg-layout-3-filter-controls").eq(0)

                    if ($gallery.closest($scope).length < 1) {
                        return;
                    }

                    // init isotope
                    var layoutMode = $('.eael-filter-gallery-wrapper').data('layout-mode');

                    let $galleryWrap = $(".eael-filter-gallery-wrapper", $scope);
                    var custom_default_control 	= $galleryWrap.data('custom_default_control');
                    var default_control_key 	= $galleryWrap.data('default_control_key');
                    custom_default_control 		= typeof(custom_default_control) 	!== 'undefined' ? parseInt( custom_default_control ) 	: 0; 
                    default_control_key 		= typeof(default_control_key) 		!== 'undefined' ? parseInt( default_control_key ) 		: 0; 
                    
                    var $isotope_gallery = $gallery.isotope({
                        itemSelector: '.eael-filterable-gallery-item-wrap',
                        layoutMode: $layout_mode,
                        percentPosition: true,
                        filter: function() {
                            var $this = $(this);
                            var $result = searchRegex ? $this.text().match(searchRegex) : true;

                            if (buttonFilter == undefined) {
                                if (layoutMode != 'layout_3') {
                                    buttonFilter = $scope.find('.eael-filter-gallery-control ul li').first().data('filter');
                                } else {
                                    buttonFilter = $scope.find('.fg-layout-3-filter-controls li').first().data('filter');
                                }
                            }

                            var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
                            return $result && buttonResult;
                        }
                    });

                    // Popup
                    $($scope).magnificPopup({
                        delegate: ".eael-filterable-gallery-item-wrap:not([style*='display: none']) .eael-magnific-link-clone.active",
                        type: "image",
                        gallery: {
                            enabled: $gallery_enabled
                        },
                        iframe: {
                            markup: `<div class="mfp-iframe-scaler">
                                        <div class="mfp-close"></div>
                                        <iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>
                                        <div class="mfp-title eael-privacy-message"></div>
                                    </div>`
                        },
                        callbacks: {
                            markupParse: function(template, values, item) {
                                if( item.el.attr('title') !== "" ) {
                                    values.title = item.el.attr('title');
                                }
                            },
                            open: function() {
                                setTimeout(() => {
                                    $(".eael-privacy-message").remove();
                                }, 5000);
                            },
                        }
                    });

                    // filter
                    $scope.on("click", ".control", function() {
                        var $this = $(this);
	                    const firstInit = parseInt($this.data('first-init'));
                        buttonFilter = $(this).attr('data-filter');

                        if ($scope.find('#fg-filter-trigger > span')) {
                            $scope.find('#fg-filter-trigger > span').text($this.text());
                        }

	                    if(!firstInit){
		                    $this.data('first-init', 1);
		                    let item_found = 0;
		                    let index_list = $items =  [];
		                    for (const [index, item] of fg_items.entries()){
			                    if (buttonFilter !== '' && buttonFilter !== '*') {
				                    let element = $($(item)[0]);
				                    if (element.is(buttonFilter)) {
					                    ++item_found;
					                    $items.push($(item)[0]);
					                    index_list.push(index);
				                    }
			                    }

			                    if (item_found === $init_show_setting) {
				                    break;
			                    }
		                    }

		                    if(index_list.length>0){
			                    fg_items = fg_items.filter(function (item, index){
				                    return !index_list.includes(index);
			                    });
		                    }
	                    }

                        $this.siblings().removeClass("active");
                        $this.addClass("active");
	                    if (!firstInit && $items.length > 0) {
                            $isotope_gallery.isotope();
		                    $gallery.append($items);
		                    $isotope_gallery.isotope('appended', $items);
		                    $isotope_gallery.isotope({filter: buttonFilter});
		                    $isotope_gallery.imagesLoaded().progress(function () {
			                    $isotope_gallery.isotope("layout");
		                    });
	                    } else {
		                    $isotope_gallery.isotope();
	                    }

                        if($this.hasClass('all-control')){
                            //All items are active
                            $('.eael-filterable-gallery-item-wrap .eael-magnific-link-clone').removeClass('active').addClass('active');
                        }else {
                            $('.eael-filterable-gallery-item-wrap .eael-magnific-link-clone').removeClass('active');
                            $(buttonFilter + ' .eael-magnific-link-clone').addClass('active');
                        }
                    });

                    //quick search
                    input.on('input', function() {
                        var $this = $(this);

                        clearTimeout(timer);
                        timer = setTimeout(function() {
                            searchRegex = new RegExp($this.val(), 'gi');
                            $isotope_gallery.isotope();
                        }, 600);

                    });

                    // not necessary, just in case
                    $isotope_gallery.imagesLoaded().progress(function() {
                        $isotope_gallery.isotope('layout');
                    });

                    $(window).on("load", function () {
                        $isotope_gallery.isotope("layout");
                    });

                    // layout gal, on click tabs
                    $isotope_gallery.on("arrangeComplete", function () {
                        $isotope_gallery.isotope("layout");
                        let notFoundDiv = $('#eael-fg-no-items-found', $scope),
					        minHeight = notFoundDiv.css('font-size');

				        $('.eael-filter-gallery-container', $scope).css('min-height', parseInt(minHeight)*2+'px');

                        if (!$isotope_gallery.data('isotope').filteredItems.length) {
                            $('#eael-fg-no-items-found').show();
                        } else {
                            $('#eael-fg-no-items-found').hide();
                        }
                    });

                    // resize
                    $('.eael-filterable-gallery-item-wrap', $gallery).resize(function() {
                        $isotope_gallery.isotope('layout');
                    });

                    // Load more button
					$scope.on("click", ".eael-gallery-load-more", function (e) {
						e.preventDefault();
						var $this            = $(this),
							// $init_show       = $(".eael-filter-gallery-container", $scope).children(".eael-filterable-gallery-item-wrap").length,
							// $total_items     = $gallery.data("total-gallery-items"),
							$images_per_page = $gallery.data("images-per-page"),
							$nomore_text     = $gallery.data("nomore-item-text"),
							filter_enable = $(".eael-filter-gallery-control",$scope).length,
							$items           = [];
						var filter_name      = $(".eael-filter-gallery-control li.active", $scope).data('filter');
						if (filterControls.length > 0) {
							filter_name = $(".fg-layout-3-filter-controls li.active", $scope).data('filter');
						}

						let item_found = 0;
						let index_list = []
						for (const [index, item] of fg_items.entries()){
							if (filter_name !== '' && filter_name !== '*' && filter_enable) {
								let element = $($(item)[0]);
								if (element.is(filter_name)) {
									++item_found;
									$items.push($(item)[0]);
									index_list.push(index);
								}
								if((fg_items.length-1)===index){
									$(".eael-filter-gallery-control li.active", $scope).data('load-more-status',1)
									$this.hide()
								}
							}else {
								++item_found;
								$items.push($(item)[0]);
								index_list.push(index);
							}

							if (item_found === $images_per_page) {
								break;
							}
						}

						if (index_list.length > 0) {
							fg_items = fg_items.filter(function (item, index) {
								return !index_list.includes(index);
							});
						}

						if (fg_items.length<1) {
							$this.html('<div class="no-more-items-text">' + $nomore_text + "</div>");
							setTimeout(function () {
								$this.fadeOut("slow");
							}, 600);
						}

						// append items
						$gallery.append($items);
						$isotope_gallery.isotope("appended", $items);
						$isotope_gallery.imagesLoaded().progress(function () {
							$isotope_gallery.isotope("layout");
						});

                        if( custom_default_control ) {
    					let increment = $settings.control_all_text ? 2 : 1;
                            default_control_key = default_control_key + increment;
                            jQuery(`.eael-filter-gallery-control li:nth-child(${default_control_key})` ).trigger('click');
                        }
					});
                });
            });
        </script>
        <?php
    }
}

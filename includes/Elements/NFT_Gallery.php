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
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

class NFT_Gallery extends Widget_Base
{
    private $nft_gallery_items_count = 0;

    public function get_name()
    {
        return 'eael-nft-gallery';
    }

    public function get_title()
    {
        return esc_html__('NFT Gallery', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-info-box';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'gallery',
            'nft gallery',
            'ea nft gallery',
            'image gallery',
            'photo gallery',
            'portfolio',
            'ea portfolio',
            'image grid',
            'photo grid',
            'responsive gallery',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/nft-gallery/';
    }

    protected function register_controls()
    {

        /**
         * NFT Settings
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_general_settings',
            [
                'label' => esc_html__('Query', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_sources',
            [
                'label' => __('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'opensea',
                'options' => [
                    'opensea' => __('OpenSea', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_source_key',
            [
                'label' => __('APi Key', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html( 'b61c8a54123d4dcb9acc1b9c26a01cd1' ),
                'description' => sprintf( __('<a href="https://docs.opensea.io/reference/request-an-api-key" class="eael-btn" target="_blank">%s</a> API key is required to fetch data from OpenSea.',
                    'essential-addons-for-elementor-lite'), esc_html( 'Get API Key ' ) ),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_type',
            [
                'label'   => esc_html__('Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'assets',
                'options' => [
                    'assets'    => esc_html__('Assets', 'essential-addons-for-elementor-lite'),
                    'collections' => esc_html__('Collections', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_sources' => 'opensea'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_slug',
            [
                'label' => __('Collection Slug', 'essential-addons-for-elementor-lite'),
                'description' => sprintf( __('Sample collection: <a target="_blank" href="%s">cryptopunks</a>', 'essential-addons-for-elementor-lite'), esc_url('https://opensea.io/collection/cryptopunks') ),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => 'cryptopunks',
                'condition' => [
                    'eael_nft_gallery_opensea_type' => 'assets',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_wallet',
            [
                'label' => __('Wallet Address', 'essential-addons-for-elementor-lite'),
                'description' => sprintf( __('Go to user profile and you will find the address code below the username. Sample wallet address: <a target="_blank" href="%s">0xC352B534e8b987e036A93539Fd6897F53488e56a</a>', 'essential-addons-for-elementor-lite'), esc_url('https://opensea.io/0xC352B534e8b987e036A93539Fd6897F53488e56a') ),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => '0x1......',
            ]
        );

        $this->add_control('eael_nft_gallery_opensea_order', [
            'label' => __('Order', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
            'condition' => [
                'eael_nft_gallery_opensea_type!' => 'collections'
            ],
        ]);

        $this->add_control(
            'eael_nft_gallery_opensea_item_limit',
            [
                'label' => __('Item Limit', 'essential-addons-for-elementor-lite'),
                'description' => __( 'Total number of items to fetch and cache at a time', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '9',
                'min' => '1',
            ]
        );

        $this->add_control(
			'eael_nft_gallery_opensea_data_cache_enable',
			[
				'label' => __( 'Cache API Response', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

	    $this->add_control(
		    'eael_nft_gallery_opensea_data_cache_time',
		    [
			    'label'       => __( 'Data Cache Time', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::NUMBER,
			    'min'         => 1,
			    'default'     => 60,
			    'description' => __( 'Cache expiration time (Minutes)', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_nft_gallery_opensea_data_cache_enable' => 'yes',
                ],
		    ]
	    );

        $this->end_controls_section();

        /**
         * NFT Settings
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_settings',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_items_layout',
            [
                'label'   => esc_html__('Layout Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid'    => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                    'list' => esc_html__('List', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

        $this->add_control(
            'eael_nft_gallery_style_preset',
            [
                'label'   => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset-1',
                'options' => [
                    'preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                    'preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'grid'
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_column',
            [
                'label'        => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => '3',
                'options'      => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    '3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    '4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    '5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    '6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-grid' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);',
                ]
            ]
        );

        $this->add_responsive_control(
            'eael_nft_list_gallery_column',
            [
                'label'        => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => '1',
                'options'      => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    '3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    '4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    '5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    '6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'list'
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);'
                ]
            ]
        );

        $this->add_control(
			'eael_nft_gallery_show_image',
			[
				'label' => __( 'NFT Image', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_image_clickable',
			[
				'label' => __( 'Image Clickable?', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_title',
			[
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_owner',
			[
				'label' => __( 'Current Owner', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_creator',
			[
				'label' => __( 'Creator', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_button',
			[
				'label' => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->end_controls_section();

        /**
         * NFT Content
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_content',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_section_nft_gallery_content_label',
            [
                'label' => esc_html__('Label'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control('eael_nft_gallery_content_owned_by_label', [
            'label' => esc_html__('Owned By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Owned By', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control('eael_nft_gallery_content_created_by_label', [
            'label' => esc_html__('Created By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Created By', 'essential-addons-for-elementor-lite'),
        ]);
        
        $this->add_control('eael_nft_gallery_content_view_details_label', [
            'label' => esc_html__('View Details', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('View Details', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control('eael_nft_gallery_content_no_items_label', [
            'label' => esc_html__('No Items', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('No Items Found!', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_section_nft_gallery_content_error_messages',
            [
                'label' => esc_html__('Error Messages'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_nft_gallery_content_invalid_type', [
            'label' => esc_html__('Invalid Wallet Address', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Please provide a valid Type!', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control('eael_nft_gallery_content_invalid_wallet_address', [
            'label' => esc_html__('Invalid Wallet Address', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Please provide a valid Wallet Address!', 'essential-addons-for-elementor-lite'),
        ]);

        $this->end_controls_section();

        /**
         * Content Tab: Load More Button
         */
        $this->start_controls_section(
            'eael_nft_gallery_section_pagination',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_pagination',
            [
                'label' => __('Show Load More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'false',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_posts_per_page',
            [
                'label' => __('Items Per Page', 'essential-addons-for-elementor-lite'),
                'description' => __('Make sure this value is less than <b>Query >> Post Limit</b>', ''),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => 6,
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_load_more_text',
            [
                'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('Load More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_nomore_items_text',
            [
                'label' => __('No More Items Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('No more items!', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_button_size',
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
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_icon_new',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_nft_gallery_load_more_icon',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_button_icon_position',
            [
                'label' => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => __('After', 'essential-addons-for-elementor-lite'),
                    'before' => __('Before', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_nft_gallery_load_more_align',
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
                    '{{WRAPPER}} .eael-nft-gallery-loadmore-wrap' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (NFT Gallery Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_gallery_style',
            [
                'label' => esc_html__('Gallery', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_wrap_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_wrap_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_wrap_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs('eael_nft_gallery_wrap_controls_tabs');

        $this->start_controls_tab('eael_nft_gallery_wrap_control_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_wrap_normal_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_wrap_normal_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_wrap_normal_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_nft_gallery_wrap_control_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_wrap_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_wrap_hover_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_wrap_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Gallery Item Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_gallery_item_style',
            [
                'label' => esc_html__('Items', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_item_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_item_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_item_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-3 .eael-nft-item .eael-nft-main-content' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'grid',
                    'eael_nft_gallery_style_preset' => 'preset-3',
                ],
            ]
        );

        $this->start_controls_tabs('eael_nft_gallery_item_controls_tabs');

        $this->start_controls_tab('eael_nft_gallery_item_control_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_item_normal_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_item_normal_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_item_normal_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_nft_gallery_item_control_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_item_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_item_hover_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_item_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( NFT Image Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_nft_image_style',
            [
                'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_nft_gallery_show_image' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control( "eael_nft_gallery_nft_image_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
        
        $this->add_responsive_control( "eael_nft_gallery_nft_image_height", [
			'label'           => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_nft_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_nft_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_nft_gallery_nft_image_border",
			'selector'  => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img",
		] );

        $this->add_control(
            'eael_nft_gallery_nft_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Created By Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_created_by_style',
            [
                'label' => esc_html__('Creator', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_nft_gallery_show_creator' => 'yes',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_created_by_image', [
			'label'     => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
		] );

        $this->add_responsive_control( "eael_nft_gallery_created_by_image_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
        
        $this->add_responsive_control( "eael_nft_gallery_created_by_image_height", [
			'label'           => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_nft_gallery_created_by_image_border",
			'selector'  => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img",
		] );

        $this->add_control(
            'eael_nft_gallery_created_by_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_created_by_label', [
			'label'     => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_created_by_label_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span',
            ]
        );

        $this->add_control(
            'eael_nft_gallery_created_by_label_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_created_by_label_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_created_by_label_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_created_by_link', [
			'label'     => __( 'Link', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_created_by_link_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a',
            ]
        );

        $this->start_controls_tabs('eael_nft_gallery_created_by_controls_tabs');

        $this->start_controls_tab('eael_nft_gallery_created_by_control_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_created_by_link_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_nft_gallery_created_by_control_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_created_by_link_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'eael_nft_gallery_created_by_link_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_created_by_link_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Owned By Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_owned_by_style',
            [
                'label' => esc_html__('Owner', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_nft_gallery_show_owner' => 'yes',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_owned_by_image', [
			'label'     => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
		] );

        $this->add_responsive_control( "eael_nft_gallery_owned_by_image_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
        
        $this->add_responsive_control( "eael_nft_gallery_owned_by_image_height", [
			'label'           => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_nft_gallery_owned_by_image_border",
			'selector'  => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img",
		] );

        $this->add_control(
            'eael_nft_gallery_owned_by_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_owned_by_label', [
			'label'     => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_owned_by_label_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span',
            ]
        );

        $this->add_control(
            'eael_nft_gallery_owned_by_label_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_owned_by_label_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_owned_by_label_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control( 'eael_nft_gallery_owned_by_link', [
			'label'     => __( 'Link', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_owned_by_link_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a',
            ]
        );

        $this->start_controls_tabs('eael_nft_gallery_owned_by_controls_tabs');

        $this->start_controls_tab('eael_nft_gallery_owned_by_control_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_owned_by_link_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_nft_gallery_owned_by_control_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_owned_by_link_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'eael_nft_gallery_owned_by_link_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_owned_by_link_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Button Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_button_style',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_nft_gallery_show_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_button_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
            ]
        );

        $this->add_responsive_control( "eael_nft_gallery_button_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

        $this->add_responsive_control(
            'eael_nft_gallery_button_alignment',
            [
                'label' => esc_html__('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
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
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_button_padding',
            [
                'label' => esc_html__('Button Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-2 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-3 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_nft_gallery_style_preset!' => 'preset-1'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_button_border_radius_preset_1',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-1 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_nft_gallery_style_preset' => 'preset-1'
                ],
            ]
        );

        $this->start_controls_tabs('eael_nft_gallery_button_controls_tabs');

        $this->start_controls_tab('eael_nft_gallery_button_control_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_button_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_button_normal_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_button_normal_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_button_normal_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_nft_gallery_button_control_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_nft_gallery_button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_button_hover_border',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Load More Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_load_more_style',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_nft_gallery_load_more_margin_top',
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
                    '{{WRAPPER}} .eael-nft-gallery-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs('tabs_eael_nft_gallery_load_more_button_style');
        
        $this->start_controls_tab(
            'tab_nft_gallery_load_more_button_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_bg_color_normal',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_text_color_normal',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_nft_gallery_load_more_border_normal',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-load-more',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_load_more_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-text',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_icon_size',
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
                    '{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-nft-gallery-load-more img.eael-nft-gallery-load-more-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_icon_spacing',
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
                    '{{WRAPPER}} .eael-nft-gallery-load-more .nft-gallery-load-more-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-nft-gallery-load-more .nft-gallery-load-more-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'eael_nft_gallery_load_more_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_nft_gallery_load_more_box_shadow',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-load-more',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'eael_nft_gallery_load_more_icon_heading',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_icon_new!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_nft_gallery_load_more_icon_margin',
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
                    '{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_icon_new!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'tab_nft_gallery_load_more_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'nft_gallery_load_more_bg_color_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'nft_gallery_load_more_text_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'nft_gallery_load_more_border_color_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nft_gallery_load_more_box_shadow_hover',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-load-more:hover',
                'condition' => [
                    'eael_nft_gallery_pagination' => 'yes',
                    'eael_nft_gallery_load_more_text!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Error Message Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_error_message_style',
            [
                'label' => esc_html__('Error Message', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_nft_gallery_error_message_label_typography',
                'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-error-message',
            ]
        );

        $this->add_control(
            'eael_nft_gallery_error_message_label_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-error-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_error_message_label_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-error-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_error_message_label_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-error-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function print_nft_gallery( $opensea_items )
    {
        $settings = $this->get_settings();
        ob_start();

        $nft_gallery = [];
        $opensea_item_formatted = [];
        $items = isset( $opensea_items['items'] ) ? $opensea_items['items'] : false;
        $error_message = ! empty( $opensea_items['error_message'] ) ? $opensea_items['error_message'] : "";

        $post_per_page = ! empty($settings['eael_nft_gallery_posts_per_page']) ? absint( $settings['eael_nft_gallery_posts_per_page'] ) : 6;
        $post_limit = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? $settings['eael_nft_gallery_opensea_item_limit'] : 9;
        $no_more_items_text = Helper::eael_wp_kses($settings['eael_nft_gallery_nomore_items_text']);
        
        $counter = 0;
        $current_page = 1;

        $nft_gallery['source'] = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
        $nft_gallery['layout'] = !empty($settings['eael_nft_gallery_items_layout']) ? $settings['eael_nft_gallery_items_layout'] : 'grid';
        $nft_gallery['opensea_type'] = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'assets';
        $nft_gallery['preset'] = !empty($settings['eael_nft_gallery_style_preset']) && 'grid' === $nft_gallery['layout'] ? $settings['eael_nft_gallery_style_preset'] : 'preset-2';
        $nft_gallery['preset'] = 'list' === $nft_gallery['layout'] && !empty($settings['eael_nft_gallery_list_style_preset']) ? $settings['eael_nft_gallery_list_style_preset'] : $nft_gallery['preset'];
        $nft_gallery['owned_by_label'] = ! empty( $settings['eael_nft_gallery_content_owned_by_label'] ) ? $settings['eael_nft_gallery_content_owned_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['created_by_label'] = ! empty( $settings['eael_nft_gallery_content_created_by_label'] ) ? $settings['eael_nft_gallery_content_created_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['view_details_text'] =  ! empty( $settings['eael_nft_gallery_content_view_details_label'] ) ? $settings['eael_nft_gallery_content_view_details_label'] : __('View Details', 'essential-addons-for-elementor-lite');       
        
        $nft_gallery['api_url'] = '';
        $nft_gallery['api_url'] = 'opensea' === $nft_gallery['source'] ? 'https://opensea.io' : ''; 
        $nft_gallery['show_thumbnail'] = ! empty( $settings['eael_nft_gallery_show_image'] ) && 'yes' === $settings['eael_nft_gallery_show_image'] ? true : false; 
        $nft_gallery['thumbnail_clickable'] = ! empty( $settings['eael_nft_gallery_image_clickable'] ) && 'yes' === $settings['eael_nft_gallery_image_clickable'] ? true : false; 
        $nft_gallery['show_title'] = ! empty( $settings['eael_nft_gallery_show_title'] ) && 'yes' === $settings['eael_nft_gallery_show_title'] ? true : false; 
        $nft_gallery['show_owner'] = ! empty( $settings['eael_nft_gallery_show_owner'] ) && 'yes' === $settings['eael_nft_gallery_show_owner'] ? true : false; 
        $nft_gallery['show_creator'] = ! empty( $settings['eael_nft_gallery_show_creator'] ) && 'yes' === $settings['eael_nft_gallery_show_creator'] ? true : false; 
        $nft_gallery['show_button'] = ! empty( $settings['eael_nft_gallery_show_button'] ) && 'yes' === $settings['eael_nft_gallery_show_button'] ? true : false; 
        $nft_gallery['button_alignment_class'] = ! empty( $settings['eael_nft_gallery_button_alignment'] ) ? 'eael-nft-gallery-button-align-' . $settings['eael_nft_gallery_button_alignment'] : ' '; 

        $this->add_render_attribute('eael-nft-gallery-wrapper', [
            'class' => [
                'eael-nft-gallery-wrapper',
                'eael-nft-gallery-' . $this->get_id(),
                'clearfix',
            ],
            'data-posts-per-page' => $post_per_page,
            'data-total-posts' => $post_limit,
            'data-nomore-item-text' => $no_more_items_text,
            'data-next-page' => 2,
        ]);

        $this->add_render_attribute(
            'eael-nft-gallery-items',
            [
                'id' => 'eael-nft-gallery-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-nft-gallery-items',
                    'eael-nft-' . esc_attr($nft_gallery['layout']),
                    esc_attr($nft_gallery['preset']),
                ],
            ]
        );

        $this->add_render_attribute(
            'eael-nft-gallery-button',
            [
                'class' => [
                    esc_attr( $nft_gallery['button_alignment_class'] ),
                ],
            ]
        );
?>
        <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-wrapper') ?> >
            <?php if ( is_array( $items ) && count( $items ) ) : ?>
            <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-items'); ?> >
                    <?php foreach ($items as $item) : ?>
                        <?php
                        $counter++;
                        if ($post_per_page > 0) {
                            $current_page = ceil($counter / $post_per_page);
                        }

                        $show_pagination = ! empty($settings['eael_nft_gallery_pagination']) && 'yes' === $settings['eael_nft_gallery_pagination'] ? true : false;
            
                        if($show_pagination){
                            $pagination_class = ' page-' . $current_page;
                            $pagination_class .= 1 === intval( $current_page ) ? ' eael-d-block' : ' eael-d-none';
                        } else {
                            $pagination_class = 'page-1 eael-d-block';
                        }

                        if ($counter == count($opensea_items)) {
                            $pagination_class .= ' eael-last-nft-gallery-item';
                        }

                        $item_formatted['thumbnail'] = ! empty( $item->image_url ) ? $item->image_url : EAEL_PLUGIN_URL . '/assets/front-end/img/flexia-preview.jpg';
                        $item_formatted['title'] = ! empty( $item->name ) ? $item->name : '';
                        $item_formatted['creator_thumbnail'] = ! empty( $item->creator ) && ! empty( $item->creator->profile_img_url ) ? $item->creator->profile_img_url : '';
                        $item_formatted['created_by_link'] = ! empty( $item->creator ) && ! empty( $item->creator->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->creator->address ) : '#';
                        $item_formatted['created_by_link_text'] = ! empty( $item->creator ) && ! empty( $item->creator->user ) && ! empty( $item->creator->user->username ) ? esc_html( $item->creator->user->username ) : '';
                        $item_formatted['show_created_by_content'] = ! empty( $item_formatted['created_by_link_text'] ) && 'NullAddress' !== $item_formatted['created_by_link_text'];

                        $item_formatted['owner_thumbnail'] = ! empty( $item->owner ) && ! empty( $item->owner->profile_img_url ) ? $item->owner->profile_img_url : '';
                        $item_formatted['owned_by_link'] = ! empty( $item->owner ) && ! empty( $item->owner->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->owner->address ) : '#';
                        $item_formatted['owned_by_link_text'] = ! empty( $item->owner ) && ! empty( $item->owner->user ) && ! empty( $item->owner->user->username ) ? esc_html( $item->owner->user->username ) : '';
                        $item_formatted['show_owned_by_content'] = ! empty( $item_formatted['owned_by_link_text'] ) && 'NullAddress' !== $item_formatted['owned_by_link_text'];

                        $item_formatted['view_details_link'] = ! empty( $item->permalink ) ? $item->permalink : '#';
                        if( 'collections' === $nft_gallery['opensea_type'] ){
                            $item_formatted['view_details_link'] = ! empty( $item->slug ) ? esc_url( "{$nft_gallery['api_url']}/collection/{$item->slug}" ) : '#'; 
                        }
                        ?>
                        <div class="eael-nft-item <?php echo esc_attr( $pagination_class ); ?> ">
                            <!-- Thumbnail -->
                            <div class="eael-nft-thumbnail">
                                <?php
                                if( $nft_gallery['show_thumbnail'] ) {
                                    if ( ! empty( $item_formatted['thumbnail'] ) ) {
                                        if ( $nft_gallery['thumbnail_clickable'] ) {
                                            printf('<a href="%s">', esc_url( $item_formatted['view_details_link'] ));
                                        }
                                        printf('<img src="%s" alt="%s">', esc_attr($item_formatted['thumbnail']), esc_attr('NFT Gallery'));
                                        if ( $nft_gallery['thumbnail_clickable'] ) {
                                            printf('</a>');
                                        }
                                    }
                                }
                                ?>
                            </div>

                            <div class="eael-nft-main-content">
                                <!-- Content  -->
                                <div class="eael-nft-content">
                                    <!-- Title  -->
                                    <?php if( $nft_gallery['show_title'] ) : ?>
                                    <h3 class="eael-nft-title"><?php printf('%s', esc_html( $item_formatted['title'] ) ); ?></h3>
                                    <?php endif; ?>

                                    <!-- Creator -->
                                    <?php if( $nft_gallery['show_creator'] && $item_formatted['show_created_by_content'] ) : ?>
                                    <div class="eael-nft-creator-wrapper">
                                        <div class="eael-nft-creator-img">
                                            <?php
                                            if (!empty($item_formatted['creator_thumbnail'])) {
                                                printf('<img src="%s" alt="%s">', esc_attr($item_formatted['creator_thumbnail']), esc_attr__('EA NFT Creator Thumbnail', 'essential-addons-for-elementor-lite'));
                                            } else {
                                                // default creator svg
                                            }
                                            ?>
                                        </div>
                                        <div class="eael-nft-created-by">
                                            <div><span><?php printf('%s', esc_html( $nft_gallery['created_by_label'] ) ); ?> </span></div>
                                            <div><?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['created_by_link'] ), esc_html( $item_formatted['created_by_link_text'] ) ); ?></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Owner -->
                                    <?php if( $nft_gallery['show_owner'] && $item_formatted['show_owned_by_content'] ) : ?>
                                    <div class="eael-nft-owner-wrapper">
                                        <div class="eael-nft-owner-img">
                                            <?php
                                            if (!empty($item_formatted['owner_thumbnail'])) {
                                                printf('<img src="%s" alt="%s">', esc_url( $item_formatted['owner_thumbnail'] ), esc_attr__('EA NFT Owner Thumbnail', 'essential-addons-for-elementor-lite') );
                                            } else {
                                                // default owner svg
                                            }
                                            ?>
                                        </div>
                                        <div class="eael-nft-owned-by">
                                            <div><span><?php printf('%s', esc_html( $nft_gallery['owned_by_label'] ) ); ?> </span></div>
                                            <div><?php printf('<a target="_blank" href="%s">%s</a>', esc_url( $item_formatted['owned_by_link'] ), esc_html( $item_formatted['owned_by_link_text'] ) ); ?></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Price -->
                                    <div class="eael-nft-price-wrapper">
                                        
                                    </div>

                                    <!-- Last Sale -->
                                    <div class="eael-nft-last-sale-wrapper eael-d-none">
                                        
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="eael-nft-button">
                                    <?php if( $nft_gallery['show_button'] ) : ?>
                                    <button <?php echo $this->get_render_attribute_string('eael-nft-gallery-button'); ?>>
                                        <?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['view_details_link'] ), esc_html__( $nft_gallery['view_details_text'] ) ) ?>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <!-- /.column  -->
            </div>
            <?php else: ?>
                <?php printf( '<div class="eael-nft-gallery-error-message">%s</div>', esc_html__($error_message, 'essential-addons-for-elementor-lite') ); ?>
            <?php endif; ?>
        </div>

        <div class="clearfix">
            <?php echo $this->render_loadmore_button() ?>
        </div>
<?php
        echo ob_get_clean();
    }

    /**
     * API Call to Get NFT Data
     */
    public function fetch_nft_gallery_from_api()
    {
        $settings = $this->get_settings();
        
        $response = [];
        $nft_gallery = [];
        $nft_gallery['source'] = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
        $nft_gallery['api_key'] = ! empty( $settings['eael_nft_gallery_source_key'] ) ? esc_html( $settings['eael_nft_gallery_source_key'] ) : '';
        $nft_gallery['opensea_type'] = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'assets';
        $nft_gallery['order'] = ! empty( $settings['eael_nft_gallery_opensea_order'] ) ? esc_html( $settings['eael_nft_gallery_opensea_order'] ) : 'desc';
        $nft_gallery['item_limit'] = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? esc_html( $settings['eael_nft_gallery_opensea_item_limit'] ) : 9;

	    $expiration = ! empty( $settings['eael_nft_gallery_opensea_data_cache_time'] ) ? absint( $settings['eael_nft_gallery_opensea_data_cache_time'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
	    $cache_key = 'eael_nft_gallery_' . $this->get_id() . '_items_cache';
        $items = get_transient( $cache_key );

        $expires = (int) get_option( '_transient_timeout_eael_nft_gallery_5fade82_items_cache', 0 );
        $time_left = $expires - time();
        $error_message = '';
        
        if( empty( $settings['eael_nft_gallery_opensea_data_cache_enable'] ) ){
            $items = false;
        }
        
        if ( false === $items && 'opensea' === $nft_gallery['source'] ) {
            $nft_gallery['api_key'] = $nft_gallery['api_key'] ? $nft_gallery['api_key'] :  'b61c8a54123d4dcb9acc1b9c26a01cd1';
            $nft_gallery['filterby_slug'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_slug'] ) ? $settings['eael_nft_gallery_opensea_filterby_slug'] : '';
            $nft_gallery['filterby_wallet'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_wallet'] : '';
                
            $url = "https://api.opensea.io/api/v1";
            $param = array();

            if ( 'collections' === $nft_gallery['opensea_type'] ) {
                $url .= "/collections";
                
                $args = array(
                    'limit' => $nft_gallery['item_limit'],
                    'offset' => 0,
                );
                
                if( ! empty( $nft_gallery['filterby_wallet'] ) ){
                    $args['asset_owner'] = sanitize_text_field( $nft_gallery['filterby_wallet'] );
                }

                $param = array_merge($param, $args);
            } elseif ( 'assets' === $nft_gallery['opensea_type'] ) {
                $url .= "/assets";
                $args = array(
                    'include_orders' => true,
                    'limit' => $nft_gallery['item_limit'],
                    'order_direction' => $nft_gallery['order'],
                );
                
                if( ! empty( $nft_gallery['filterby_slug'] ) ) {
                    $args['collection_slug'] = sanitize_text_field( $nft_gallery['filterby_slug'] );
                }
                
                if( ! empty( $nft_gallery['filterby_wallet'] ) ) {
                    $args['owner'] = sanitize_text_field( $nft_gallery['filterby_wallet'] );                
                }

                $param = array_merge( $param, $args );
            } else {
                $error_message = ! empty( $settings['eael_nft_gallery_content_invalid_type'] ) ? esc_html__( $settings['eael_nft_gallery_content_invalid_type'], 'essential-addons-for-elementor-lite' ) : esc_html__('Please provide a valid Type!', 'essential-addons-for-elementor-lite');
            }

            $headers = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'X-API-KEY' => $nft_gallery['api_key'],
                )
            );
            $options = array(
                'timeout' => 240
            );

            $options = array_merge($headers, $options);
            
            if( empty($error_message) ){
                $response = wp_remote_get(
                    esc_url_raw( add_query_arg( $param, $url ) ), 
                    $options
                );
    
                $body = json_decode( wp_remote_retrieve_body( $response ) );
                $response = 'assets' === $nft_gallery['opensea_type'] && ! empty( $body->assets ) ? $body->assets : $body;
                $response = 'collections' === $nft_gallery['opensea_type'] && ! empty( $response->collections ) ? $response->collections : $response;

                if(is_array($response)){
                    $response = array_splice($response, 0, absint( $settings['eael_nft_gallery_opensea_item_limit'] ));
                    set_transient( $cache_key, $response, $expiration );
                    $this->nft_gallery_items_count = count($response);
                }
            }
            
            $data = [
                'items' => $response,
                'error_message' => $error_message,
            ];

            return $data;
        }

        $response = $items ? $items : $response;
        $this->nft_gallery_items_count = count($response);

        $data = [
            'items' => $response,
            'error_message' => $error_message,
        ];
        return $data;
    }

    protected function render_loadmore_button()
    {
        $settings = $this->get_settings_for_display();
        $icon_migrated = isset($settings['__fa4_migrated']['eael_nft_gallery_load_more_icon_new']);
        $icon_is_new = empty($settings['eael_nft_gallery_load_more_icon']);

        $post_per_page = ! empty($settings['eael_nft_gallery_posts_per_page']) ? intval( $settings['eael_nft_gallery_posts_per_page'] ) : 6;
        $post_limit = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? $settings['eael_nft_gallery_opensea_item_limit'] : 9;
        $load_more_class = $post_per_page < $post_limit ? 'eael-d-block' : 'eael-d-none';
        
        $this->add_render_attribute('nft-gallery-load-more-button', 'class', [
            'eael-nft-gallery-load-more',
            'elementor-button',
            'elementor-size-' . esc_attr( $settings['eael_nft_gallery_button_size'] ),
        ]);
        
        if ( 'yes' === $settings['eael_nft_gallery_pagination'] && $this->nft_gallery_items_count > $post_per_page ) { ?>
            <div class="eael-nft-gallery-loadmore-wrap">
                <a href="#" <?php echo $this->get_render_attribute_string('nft-gallery-load-more-button'); ?>>
                    <span class="eael-btn-loader"></span>
                    <?php if ($settings['eael_nft_gallery_button_icon_position'] == 'before') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['eael_nft_gallery_load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left" src="<?php echo esc_url($settings['eael_nft_gallery_load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_nft_gallery_load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                    <span class="eael-nft-gallery-load-more-text">
                        <?php echo Helper::eael_wp_kses($settings['eael_nft_gallery_load_more_text']); ?>
                    </span>
                    <?php if ($settings['eael_nft_gallery_button_icon_position'] == 'after') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['eael_nft_gallery_load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right" src="<?php echo esc_url($settings['eael_nft_gallery_load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_nft_gallery_load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                </a>
            </div>
        <?php }
    }

    protected function render()
    {
        $nft_gallery_items = $this->fetch_nft_gallery_from_api();
        $this->print_nft_gallery( $nft_gallery_items );
    }
}

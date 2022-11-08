<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
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
                'default' => 'items',
                'options' => [
                    'items'    => esc_html__('Items', 'essential-addons-for-elementor-lite'),
                    'collection' => esc_html__('Collections', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_sources' => 'opensea'
                ],
            ]
        );

        $this->add_control('eael_nft_gallery_opensea_filterby', [
            'label' => esc_html__('Filter By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                '' => __( 'None', 'essential-addons-for-elementor-lite' ),
                'slug' => __( 'Collection Slug', 'essential-addons-for-elementor-lite' ),
                'wallet' => __( 'Wallet Address', 'essential-addons-for-elementor-lite' ),
            ],
            'condition' => [
                'eael_nft_gallery_opensea_type!' => 'collection'
            ],
        ]);

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_slug',
            [
                'label' => __('Collection Slug', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => 'cryptopunks',
                'condition' => [
                    'eael_nft_gallery_opensea_type' => 'items',
                    'eael_nft_gallery_opensea_filterby' => 'slug'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_wallet',
            [
                'label' => __('Wallet Address', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => '0x1......',
                'condition' => [
                    'eael_nft_gallery_opensea_type' => 'items',
                    'eael_nft_gallery_opensea_filterby' => 'wallet'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_collections_wallet',
            [
                'label' => __('Wallet Address', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'eael_nft_gallery_opensea_type' => 'collection'
                ],
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
                'eael_nft_gallery_opensea_type!' => 'collection'
            ],
        ]);

        $this->add_control(
            'eael_nft_gallery_opensea_posts_per_page',
            [
                'label' => __('Items Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '6',
                'min' => '1',
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

        // $this->add_control(
        //     'eael_nft_gallery_list_style_preset',
        //     [
        //         'label'   => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
        //         'type'    => Controls_Manager::SELECT,
        //         'default' => 'preset-2',
        //         'options' => [
        //             'preset-2' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
        //         ],
        //         'condition' => [
        //             'eael_nft_gallery_items_layout' => 'list'
        //         ],
        //     ]
        // );

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
    }

    public function print_nft_gallery( $opensea_items )
    {
        $settings = $this->get_settings();
        ob_start();

        $nft_gallery = [];
        $opensea_item_formatted = [];

        $nft_gallery['source'] = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
        $nft_gallery['layout'] = !empty($settings['eael_nft_gallery_items_layout']) ? $settings['eael_nft_gallery_items_layout'] : 'grid';
        $nft_gallery['preset'] = !empty($settings['eael_nft_gallery_style_preset']) && 'grid' === $nft_gallery['layout'] ? $settings['eael_nft_gallery_style_preset'] : 'preset-2';
        $nft_gallery['preset'] = 'list' === $nft_gallery['layout'] && !empty($settings['eael_nft_gallery_list_style_preset']) ? $settings['eael_nft_gallery_list_style_preset'] : $nft_gallery['preset'];
        $nft_gallery['owned_by_label'] = ! empty( $settings['eael_nft_gallery_content_owned_by_label'] ) ? $settings['eael_nft_gallery_content_owned_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['created_by_label'] = ! empty( $settings['eael_nft_gallery_content_created_by_label'] ) ? $settings['eael_nft_gallery_content_created_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['view_details_text'] =  ! empty( $settings['eael_nft_gallery_content_view_details_label'] ) ? $settings['eael_nft_gallery_content_view_details_label'] : __('View Details', 'essential-addons-for-elementor-lite');       
        
        $nft_gallery['api_url'] = '';
        $nft_gallery['api_url'] = 'opensea' === $nft_gallery['source'] ? 'https://opensea.io/' : ''; 
        $nft_gallery['show_thumbnail'] = ! empty( $settings['eael_nft_gallery_show_image'] ) && 'yes' === $settings['eael_nft_gallery_show_image'] ? true : false; 
        $nft_gallery['thumbnail_clickable'] = ! empty( $settings['eael_nft_gallery_image_clickable'] ) && 'yes' === $settings['eael_nft_gallery_image_clickable'] ? true : false; 
        $nft_gallery['show_title'] = ! empty( $settings['eael_nft_gallery_show_title'] ) && 'yes' === $settings['eael_nft_gallery_show_title'] ? true : false; 
        $nft_gallery['show_owner'] = ! empty( $settings['eael_nft_gallery_show_owner'] ) && 'yes' === $settings['eael_nft_gallery_show_owner'] ? true : false; 
        $nft_gallery['show_creator'] = ! empty( $settings['eael_nft_gallery_show_creator'] ) && 'yes' === $settings['eael_nft_gallery_show_creator'] ? true : false; 
        $nft_gallery['show_button'] = ! empty( $settings['eael_nft_gallery_show_button'] ) && 'yes' === $settings['eael_nft_gallery_show_button'] ? true : false; 
        $nft_gallery['button_alignment_class'] = ! empty( $settings['eael_nft_gallery_button_alignment'] ) ? 'eael-nft-gallery-button-align-' . $settings['eael_nft_gallery_button_alignment'] : ' '; 

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
        <div class="eael-nft-gallery-wrapper">
            <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-items'); ?> >
                <?php if ( is_array( $opensea_items ) && count( $opensea_items ) ) : ?>
                    <?php foreach ($opensea_items as $item) : ?>
                        <?php
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
                        
                        ?>
                        <div class="eael-nft-item">
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
                <?php endif; ?>
                <!-- /.column  -->
            </div>
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
        $nft_gallery['opensea_type'] = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'items';
        $nft_gallery['filterby'] = ! empty( $settings['eael_nft_gallery_opensea_filterby'] ) ? esc_html( $settings['eael_nft_gallery_opensea_filterby'] ) : '';
        $nft_gallery['order'] = ! empty( $settings['eael_nft_gallery_opensea_order'] ) ? esc_html( $settings['eael_nft_gallery_opensea_order'] ) : 'desc';
        $nft_gallery['posts_per_page'] = ! empty( $settings['eael_nft_gallery_opensea_posts_per_page'] ) ? esc_html( $settings['eael_nft_gallery_opensea_posts_per_page'] ) : 6;

        if ( 'opensea' === $nft_gallery['source'] ) {
            $nft_gallery['api_key'] = $nft_gallery['api_key'] ? $nft_gallery['api_key'] :  'b61c8a54123d4dcb9acc1b9c26a01cd1';
            
            $url = "https://api.opensea.io/api/v1";
            $param = array();

            if ( 'collections' === $nft_gallery['opensea_type'] ) {
                $url .= "/collections";
                $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_collections_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_collections_wallet'] : '';

                $args = array(
                    'asset_owner' => sanitize_text_field( $nft_gallery['filterby_value'] ),
                    'limit' => $nft_gallery['posts_per_page'],
                    'offset' => 0,
                );
                $param = array_merge($param, $args);
            } elseif ( 'items' === $nft_gallery['opensea_type'] ) {
                $url .= "/assets";
                $args = array(
                    'include_orders' => true,
                    'limit' => $nft_gallery['posts_per_page'],
                    'order_direction' => $nft_gallery['order'],
                );
                
                if ( ! empty( $nft_gallery['filterby'] ) ) {
                    if ( "slug" === $nft_gallery['filterby'] ) {
                        $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_slug'] ) ? $settings['eael_nft_gallery_opensea_filterby_slug'] : '';
                        $args['collection_slug'] = sanitize_text_field( $nft_gallery['filterby_value'] );
                    } elseif ( "wallet" === $nft_gallery['filterby'] ) {
                        $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_wallet'] : '';
                        $args['owner'] = sanitize_text_field( $nft_gallery['filterby_value'] );
                    }
                }
                $param = array_merge( $param, $args );
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
            $response = wp_remote_get(
                esc_url_raw( add_query_arg( $param, $url ) ), 
                $options
            );

            $body = json_decode( wp_remote_retrieve_body( $response ) );
            $response = ! empty( $body->assets ) ? $body->assets : [];
            return $response;
        }

        return $response;
    }

    protected function render()
    {
        $nft_gallery_items = $this->fetch_nft_gallery_from_api();
        $this->print_nft_gallery( $nft_gallery_items );
    }
}

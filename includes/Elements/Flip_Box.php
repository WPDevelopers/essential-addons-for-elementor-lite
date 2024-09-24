<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Plugin;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Flip_Box extends Widget_Base
{

    public function get_name()
    {
        return 'eael-flip-box';
    }

    public function get_title()
    {
        return esc_html__('Flip Box', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-flip-box';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'flip',
            'ea flipbox',
            'ea flip box',
            'box',
            'flip box',
            'card',
            'flip card',
            'ea flip card',
            'rotate',
            'ea',
            'essential addons',
        ];
    }

    protected function is_dynamic_content():bool {
        return false;
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/flip-box/';
    }

    protected function register_controls()
    {

        /**
         * Flipbox Image Settings
         */
        $this->start_controls_section(
            'eael_section_flipbox_content_settings',
            [
                'label' => esc_html__('Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
			'eael_flipbox_event_type',
			[
				'label'   => esc_html__( 'Choose Event', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'hover' => [
						'title' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-button',
					],
                    'click' => [
						'title' => esc_html__( 'Click', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-click',
					],
				],
				'default' => 'hover',
				'toggle'  => true,
			]
		);

        $this->add_control(
            'eael_flipbox_type',
            [
                'label'       => esc_html__('Flipbox Type', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'animate-left',
                'label_block' => false,
                'options'     => [
                    'animate-left'     => esc_html__('Flip Left', 'essential-addons-for-elementor-lite'),
                    'animate-right'    => esc_html__('Flip Right', 'essential-addons-for-elementor-lite'),
                    'animate-up'       => esc_html__('Flip Top', 'essential-addons-for-elementor-lite'),
                    'animate-down'     => esc_html__('Flip Bottom', 'essential-addons-for-elementor-lite'),
                    'animate-zoom-in'  => esc_html__('Zoom In', 'essential-addons-for-elementor-lite'),
                    'animate-zoom-out' => esc_html__('Zoom Out', 'essential-addons-for-elementor-lite'),
                    'animate-fade-in' => esc_html__('Fade In', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
	    $this->add_control(
		    'eael_flipbox_3d',
		    [
			    'label' => __( '3D Depth', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => __( 'On', 'essential-addons-for-elementor-lite' ),
			    'label_off' => __( 'Off', 'essential-addons-for-elementor-lite' ),
			    'return_value' => 'eael-flip-box--3d',
			    'default' => '',
			    'prefix_class' => '',
			    'condition' => [
				    'eael_flipbox_type' => [
					    'animate-left',
					    'animate-right',
					    'animate-up',
					    'animate-down',
                    ]
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'eael_flipbox_height',
            [
                'label'      => esc_html__('Height', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 1000,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'step' => 3,
                        'max'  => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-container:not(.eael-template)' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-container.eael-template' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Flipbox Content
         */
        $this->start_controls_section(
            'eael_flipbox_content',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->start_controls_tabs('eael_flipbox_content_tabs');

        $this->start_controls_tab(
            'eael_flipbox_content_front',
            [
                'label' => __('Front', 'essential-addons-for-elementor-lite'),
            ]
        );
	    $this->add_control(
		    'eael_flipbox_front_content_type',
		    [
			    'label'                 => __( 'Content Type', 'essential-addons-for-elementor-lite' ),
			    'type'                  => Controls_Manager::SELECT,
			    'options'               => [
				    'content'       => __( 'Content', 'essential-addons-for-elementor-lite' ),
				    'template'      => __( 'Saved Templates', 'essential-addons-for-elementor-lite' ),
			    ],
			    'default'               => 'content',
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_front_templates',
		    [
			    'label'       => __( 'Choose Template', 'essential-addons-for-elementor-lite' ),
			    'type'        => 'eael-select2',
			    'source_name' => 'post_type',
			    'source_type' => 'elementor_library',
			    'label_block' => true,
			    'condition'   => [
				    'eael_flipbox_front_content_type' => 'template',
			    ],
		    ]
	    );



	    $this->add_control(
		    'eael_flipbox_img_or_icon',
		    [
			    'label'   => esc_html__('Icon Type', 'essential-addons-for-elementor-lite'),
			    'type'    => Controls_Manager::SELECT,
			    'options' => [
				    'none' => __('None', 'essential-addons-for-elementor-lite'),
				    'img'  => __('Image', 'essential-addons-for-elementor-lite'),
				    'icon' => __('Icon', 'essential-addons-for-elementor-lite'),
			    ],
			    'default' => 'icon',
			    'condition'             => [
				    'eael_flipbox_front_content_type'      => 'content',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_image',
		    [
			    'label'     => esc_html__('Flipbox Image', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::MEDIA,
			    'default'   => [
				    'url' => Utils::get_placeholder_image_src(),
			    ],
			    'condition' => [
				    'eael_flipbox_img_or_icon' => 'img',
			    ],
                'ai' => [
                    'active' => false,
                ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_icon_new',
		    [
			    'label'            => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
			    'type'             => Controls_Manager::ICONS,
			    'fa4compatibility' => 'eael_flipbox_icon',
			    'default'          => [
				    'value'   => 'fas fa-snowflake',
				    'library' => 'fa-solid',
			    ],
			    'condition'        => [
				    'eael_flipbox_img_or_icon' => 'icon',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_flipbox_image_resizer',
		    [
			    'label'     => esc_html__('Image Resizer', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::SLIDER,
			    'default'   => [
				    'size' => '100',
			    ],
			    'range'     => [
				    'px' => [
					    'max' => 500,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image > img.eael-flipbox-image-as-icon' => 'width: {{SIZE}}{{UNIT}};',
			    ],
			    'condition' => [
				    'eael_flipbox_img_or_icon' => 'img',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Image_Size::get_type(),
		    [
			    'name'      => 'thumbnail',
			    'default'   => 'full',
			    'condition' => [
				    'eael_flipbox_image[url]!' => '',
				    'eael_flipbox_img_or_icon' => 'img',
			    ],
		    ]
	    );

        $this->add_control(
            'eael_flipbox_front_title',
            [
                'label'       => esc_html__('Front Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'   => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html__('Front Title', 'essential-addons-for-elementor-lite'),
	            'condition'             => [
		            'eael_flipbox_front_content_type'      => 'content',
	            ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_flipbox_front_title_tag',
            [
                'label'   => __('Select Front Title Tag', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1'   => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2'   => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3'   => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4'   => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5'   => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6'   => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p'    => __('P', 'essential-addons-for-elementor-lite'),
                    'div'  => __('Div', 'essential-addons-for-elementor-lite'),
                ],
	            'condition'             => [
		            'eael_flipbox_front_content_type'      => 'content',
	            ],
            ]
        );

        $this->add_control(
            'eael_flipbox_front_text',
            [
                'label'       => esc_html__('Front Content', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => __('<p>This is front side content.</p>', 'essential-addons-for-elementor-lite'),
	            'condition'             => [
		            'eael_flipbox_front_content_type'      => 'content',
	            ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

	    $this->add_control(
		    'eael_flipbox_front_vertical_position',
		    [
			    'label' => __( 'Vertical Position', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'top' => [
					    'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-top',
				    ],
				    'middle' => [
					    'title' => __( 'Middle', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-middle',
				    ],
				    'bottom' => [
					    'title' => __( 'Bottom', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-bottom',
				    ],
			    ],
			    'default' => 'middle',
			    'selectors_dictionary' => [
				    'top' => 'flex-start',
				    'middle' => 'center',
				    'bottom' => 'flex-end',
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-elements-flip-box-front-container' => 'align-items: {{VALUE}}',
			    ],
			    'condition'             => [
				    'eael_flipbox_front_content_type'      => 'content',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_content_alignment',
		    [
			    'label'        => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
			    'type'         => Controls_Manager::CHOOSE,
			    'label_block'  => true,
			    'options'      => [
				    'left'   => [
					    'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-left',
				    ],
				    'center' => [
					    'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-center',
				    ],
				    'right'  => [
					    'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-right',
				    ],
			    ],
			    'default'      => 'center',
			    'prefix_class' => 'eael-flipbox-content-align-',
			    'condition'             => [
				    'eael_flipbox_front_content_type'      => 'content',
			    ],
		    ]
	    );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_flipbox_content_back',
            [
                'label' => __('Back', 'essential-addons-for-elementor-lite'),
            ]
        );

	    $this->add_control(
		    'eael_flipbox_back_content_type',
		    [
			    'label'                 => __( 'Content Type', 'essential-addons-for-elementor-lite' ),
			    'type'                  => Controls_Manager::SELECT,
			    'options'               => [
				    'content'       => __( 'Content', 'essential-addons-for-elementor-lite' ),
				    'template'      => __( 'Saved Templates', 'essential-addons-for-elementor-lite' ),
			    ],
			    'default'               => 'content',
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_back_templates',
		    [
			    'label'       => __( 'Choose Template', 'essential-addons-for-elementor-lite' ),
			    'type'        => 'eael-select2',
			    'source_name' => 'post_type',
			    'source_type' => 'elementor_library',
			    'label_block' => true,
			    'condition'   => [
				    'eael_flipbox_back_content_type' => 'template',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_img_or_icon_back',
		    [
			    'label'   => esc_html__('Icon Type', 'essential-addons-for-elementor-lite'),
			    'type'    => Controls_Manager::SELECT,
			    'options' => [
				    'none' => __('None', 'essential-addons-for-elementor-lite'),
				    'img'  => __('Image', 'essential-addons-for-elementor-lite'),
				    'icon' => __('Icon', 'essential-addons-for-elementor-lite'),
			    ],
			    'default' => 'icon',
			    'condition'             => [
				    'eael_flipbox_back_content_type'      => 'content',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_image_back',
		    [
			    'label'     => esc_html__('Flipbox Image', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::MEDIA,
			    'default'   => [
				    'url' => Utils::get_placeholder_image_src(),
			    ],
			    'condition' => [
				    'eael_flipbox_img_or_icon_back' => 'img',
			    ],
                'ai' => [
                    'active' => false,
                ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_icon_back_new',
		    [
			    'label'            => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
			    'type'             => Controls_Manager::ICONS,
			    'fa4compatibility' => 'eael_flipbox_icon_back',
			    'default'          => [
				    'value'   => 'fas fa-snowflake',
				    'library' => 'fa-solid',
			    ],
			    'condition'        => [
				    'eael_flipbox_img_or_icon_back' => 'icon',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_flipbox_image_resizer_back',
		    [
			    'label'     => esc_html__('Image Resizer', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::SLIDER,
			    'default'   => [
				    'size' => '100',
			    ],
			    'range'     => [
				    'px' => [
					    'max' => 500,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image > img.eael-flipbox-image-as-icon' => 'width: {{SIZE}}{{UNIT}};',
			    ],
			    'condition' => [
				    'eael_flipbox_img_or_icon_back' => 'img',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Image_Size::get_type(),
		    [
			    'name'      => 'thumbnail_back',
			    'default'   => 'full',
			    'condition' => [
				    'eael_flipbox_image[url]!'      => '',
				    'eael_flipbox_img_or_icon_back' => 'img',
			    ],
		    ]
	    );

	    $this->add_control(
            'eael_flipbox_back_title',
            [
                'label'       => esc_html__('Back Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'   => [
                    'active' => true,
                ],
                'label_block' => true,
                'default'     => esc_html__('Back Title', 'essential-addons-for-elementor-lite'),
	            'condition'             => [
		            'eael_flipbox_back_content_type'      => 'content',
	            ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_flipbox_back_title_tag',
            [
                'label'   => __('Select Back Title Tag', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1'   => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2'   => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3'   => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4'   => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5'   => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6'   => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p'    => __('P', 'essential-addons-for-elementor-lite'),
                    'div'  => __('Div', 'essential-addons-for-elementor-lite'),
                ],
	            'condition'             => [
		            'eael_flipbox_back_content_type'      => 'content',
	            ],
            ]
        );

        $this->add_control(
            'eael_flipbox_back_text',
            [
                'label'       => esc_html__('Back Content', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => __('<p>This is back side content.</p>', 'essential-addons-for-elementor-lite'),
	            'condition'             => [
		            'eael_flipbox_back_content_type'      => 'content',
	            ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

	    $this->add_control(
		    'eael_flipbox_back_vertical_position',
		    [
			    'label' => __( 'Vertical Position', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'top' => [
					    'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-top',
				    ],
				    'middle' => [
					    'title' => __( 'Middle', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-middle',
				    ],
				    'bottom' => [
					    'title' => __( 'Bottom', 'essential-addons-for-elementor-lite' ),
					    'icon' => 'eicon-v-align-bottom',
				    ],
			    ],
			    'default' => 'middle',
			    'selectors_dictionary' => [
				    'top' => 'flex-start',
				    'middle' => 'center',
				    'bottom' => 'flex-end',
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-elements-flip-box-rear-container' => 'align-items: {{VALUE}}',
			    ],
			    'condition'             => [
				    'eael_flipbox_back_content_type'      => 'content',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_flipbox_back_content_alignment',
		    [
			    'label'        => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
			    'type'         => Controls_Manager::CHOOSE,
			    'label_block'  => true,
			    'options'      => [
				    'left'   => [
					    'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-left',
				    ],
				    'center' => [
					    'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-center',
				    ],
				    'right'  => [
					    'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-right',
				    ],
			    ],
			    'default'  => 'center',
			    'selectors' => [
			        '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-padding' => 'text-align: {{VALUE}}',
                ],
			    'condition'             => [
				    'eael_flipbox_back_content_type'      => 'content',
			    ],
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * ----------------------------------------------
         * Flipbox Link
         * ----------------------------------------------
         */
        $this->start_controls_section(
            'eael_flixbox_link_section',
            [
                'label' => esc_html__('Link', 'essential-addons-for-elementor-lite'),
	            'condition' => [
		            'eael_flipbox_back_content_type' => 'content',
	            ],
            ]
        );

        $this->add_control(
            'flipbox_link_type',
            [
                'label'   => __('Link Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'   => __('None', 'essential-addons-for-elementor-lite'),
                    'box'    => __('Box', 'essential-addons-for-elementor-lite'),
                    'title'  => __('Title', 'essential-addons-for-elementor-lite'),
                    'button' => __('Button', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'flipbox_link',
            [
                'label'       => __('Link', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'placeholder' => 'https://www.your-link.com',
                'default'     => [
                    'url' => '#',
                ],
                'condition'   => [
                    'flipbox_link_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'flipbox_button_text',
            [
                'label'     => __('Button Text', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [
                    'active' => true,
                ],
                'default'   => __('Get Started', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'flipbox_link_type' => 'button',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'button_icon_new',
            [
                'label'            => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'button_icon',
                'condition'        => [
                    'flipbox_link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label'     => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'after',
                'options'   => [
                    'after'  => __('After', 'essential-addons-for-elementor-lite'),
                    'before' => __('Before', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'flipbox_link_type' => 'button',
                ],
            ]
        );

        $this->end_controls_section();

        if (!apply_filters('eael/pro_enabled', false)) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __('Go Premium for More Features', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_control_get_pro',
                [
                    'label'       => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options'     => [
                        '1' => [
                            'title' => '',
                            'icon'  => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default'     => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style (Flipbox Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_flipbox_style_settings',
            [
                'label' => esc_html__('Filp Box Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_flipbox_front_bg_heading',
            [
                'label' => __('Front Background Color', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_flipbox_front_bg_color',
                'label'    => __('Front Background Color', 'essential-addons-for-elementor-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container',
            ]
        );

        $this->add_control(
            'eael_flipbox_back_bg_heading',
            [
                'label'     => __('Back Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'eael_flipbox_back_bg_color',
                'label'     => __('Back Background Color', 'essential-addons-for-elementor-lite'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-elements-flip-box-rear-container',
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'eael_flipbox_front_back_padding',
            [
                'label'      => esc_html__('Content Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_filbpox_border',
                'label'    => esc_html__('Border Style', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container, {{WRAPPER}} .eael-elements-flip-box-rear-container',
            ]
        );

        $this->add_control(
            'eael_flipbox_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_flipbox_shadow',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container, {{WRAPPER}} .eael-elements-flip-box-rear-container',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Flip Box Image)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_flipbox_imgae_style_settings',
            [
                'label'     => esc_html__('Image Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'img',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_img_type',
            [
                'label'        => esc_html__('Image Type', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'default',
                'label_block'  => false,
                'options'      => [
                    'circle'  => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius'  => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                    'default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-flipbox-img-',
                'condition'    => [
                    'eael_flipbox_img_or_icon' => 'img',
                ],
            ]
        );

        /**
         * Condition: 'eael_flipbox_img_type' => 'radius'
         */
        $this->add_control(
            'eael_filpbox_img_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-icon-image img' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-elements-flip-box-icon-image img' => 'border-radius: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'img',
                    'eael_flipbox_img_type'    => 'radius',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Flip Box Icon Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_flipbox_icon_style_settings',
            [
                'label'     => esc_html__('Icon Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->start_controls_tabs('eael_section_icon_style_settings');
        $this->start_controls_tab('eael_section_icon_front_style_settings', [
            'label' => esc_html__('Front', 'essential-addons-for-elementor-lite'),
        ]);

        /**
         * Icon
         */
        $this->add_control(
            'eael_flipbox_front_icon_heading',
            [
                'label'     => esc_html__('Icon Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_front_icon_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image .ea-flipbox-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_front_icon_typography',
            [
                'label'      => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image .ea-flipbox-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image svg.ea-flipbox-icon' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'eael_flipbox_icon_front_border',
                'label'     => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image',
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_flipbox_icon_front_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_icon_front_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 500,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'step' => 3,
                        'max'  => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-icon-image' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_section_icon_back_style_settings', [
            'label' => esc_html__('Back', 'essential-addons-for-elementor-lite'),
        ]);

        /**
         * Icon
         */
        $this->add_control(
            'eael_flipbox_back_icon_heading',
            [
                'label'     => esc_html__('Icon Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_flipbox_img_or_icon_back' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_back_icon_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image .ea-flipbox-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image .ea-flipbox-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_flipbox_img_or_icon_back' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_back_icon_typography',
            [
                'label'      => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 150,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image .ea-flipbox-icon'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image svg.ea-flipbox-icon'   => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_flipbox_img_or_icon_back' => 'icon',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'eael_flipbox_icon_back_border',
                'label'     => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image',
                'condition' => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_flipbox_icon_back_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_icon_back_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 500,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'step' => 3,
                        'max'  => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-icon-image' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_flipbox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Flip Box Title Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_flipbox_title_style_settings',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('eael_section_flipbox_typo_style_settings');
        $this->start_controls_tab('eael_section_flipbox_typo_style_front_settings', [
            'label' => esc_html__('Front', 'essential-addons-for-elementor-lite'),
        ]);

        /**
         * Title
         */
        $this->add_control(
            'eael_flipbox_front_title_heading',
            [
                'label' => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_flipbox_front_title_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_flipbox_front_title_typography',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-heading',
            ]
        );

        /**
         * Content
         */
        $this->add_control(
            'eael_flipbox_front_content_heading',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_flipbox_front_content_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_flipbox_front_content_typography',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-content',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_section_flipbox_typo_style_back_settings', [
            'label' => esc_html__('Back', 'essential-addons-for-elementor-lite'),
        ]);

        /**
         * Title
         */
        $this->add_control(
            'eael_flipbox_back_title_heading',
            [
                'label'     => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_flipbox_back_title_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_flipbox_back_title_typography',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-heading',
            ]
        );

        /**
         * Content
         */
        $this->add_control(
            'eael_flipbox_back_content_heading',
            [
                'label'     => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_flipbox_back_content_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_flipbox_back_content_typography',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-content',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Flip Box Button Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_flipbox_button_style_settings',
            [
                'label'     => esc_html__('Button Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'flipbox_link_type' => 'button',
	                'eael_flipbox_back_content_type' => 'content',
                ],
            ]
        );

        $this->start_controls_tabs('flipbox_button_style_settings');

        $this->start_controls_tab(
            'flipbox_button_normal_style',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_responsive_control(
            'eael_flipbox_button_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_flipbox_button_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_button_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button, {{WRAPPER}} .eael-elements-flip-box-container .flipbox-button .ea-flipbox-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button .ea-flipbox-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_button_bg_color',
            [
                'label'     => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7048ff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'step' => 1,
                        'max'  => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_flipbox_button_typography',
                'selector' => '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button',
            ]
        );

        $this->add_control(
            'eael_flipbox_button_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'step' => 1,
                        'max'  => 200,
                    ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button > i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button > svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_button_hover_style',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_flipbox_button_hover_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button:hover .ea-flipbox-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button:hover .ea-flipbox-icon svg' => 'color: {{VALUE}};  fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_button_hover_bg_color',
            [
                'label'     => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eael-elements-flip-box-container .flipbox-button:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $flipbox_image = $this->get_settings('eael_flipbox_image');
        $flipbox_image_url = Group_Control_Image_Size::get_attachment_image_src($flipbox_image['id'], 'thumbnail', $settings);

        if (empty($flipbox_image_url) && !empty($flipbox_image['url'])) {
            $flipbox_image_url = $flipbox_image['url'];
        }

        // $breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();
        // print_r($breakpoints);

        $flipbox_if_html_tag = 'div';
        $flipbox_if_html_title_tag = Helper::eael_validate_html_tag($settings['eael_flipbox_back_title_tag']);
        $this->add_render_attribute('flipbox-container', 'class', 'eael-elements-flip-box-flip-card');
        $this->add_render_attribute('flipbox-title-container', 'class', 'eael-elements-flip-box-heading');


        if ($settings['flipbox_link_type'] != 'none') {
            if (!empty($settings['flipbox_link']['url'])) {
                if ($settings['flipbox_link_type'] == 'box') {
                    $flipbox_if_html_tag = 'a';

	                $this->add_link_attributes( 'flipbox-container', $settings['flipbox_link'] );
                } elseif ($settings['flipbox_link_type'] == 'title') {
                    $flipbox_if_html_title_tag = 'a';

                    $this->add_render_attribute(
                        'flipbox-title-container',
                        [
                            'class' => 'flipbox-linked-title',
                        ]
                    );

	                $this->add_link_attributes( 'flipbox-title-container', $settings['flipbox_link'] );
                } elseif ($settings['flipbox_link_type'] == 'button') {
                    $this->add_render_attribute(
                        'flipbox-button-container',
                        [
                            'class' => 'flipbox-button',
                        ]
                    );

	                $this->add_link_attributes( 'flipbox-button-container', $settings['flipbox_link'] );
                }
            }
        }

        $flipbox_image_back = $this->get_settings('eael_flipbox_image_back');
        $flipbox_back_image_url = Group_Control_Image_Size::get_attachment_image_src($flipbox_image_back['id'], 'thumbnail_back', $settings);
        $flipbox_back_image_url = empty($flipbox_back_image_url) ? $flipbox_back_image_url : $flipbox_back_image_url;
        if ('img' == $settings['eael_flipbox_img_or_icon_back']) {
            $this->add_render_attribute(
                'flipbox-back-icon-image-container',
                [
                    'src' => $flipbox_back_image_url,
                    'alt' => esc_attr(get_post_meta($flipbox_image_back['id'], '_wp_attachment_image_alt', true)),
                ]
            );
        }

        $this->add_render_attribute(
            'eael_flipbox_main_wrap',
            [
                'class' => [
                    'eael-elements-flip-box-container',
                    'eael-animate-flip',
                    'eael-' . esc_attr($settings['eael_flipbox_type']),
                    'eael-' . esc_attr($settings['eael_flipbox_front_content_type']),
                    'eael-flip-box-' . esc_attr($settings['eael_flipbox_event_type']),
                ],
            ]
        );

?>

        <div <?php echo $this->get_render_attribute_string('eael_flipbox_main_wrap'); ?>>

            <<?php echo $flipbox_if_html_tag, ' ', $this->get_render_attribute_string('flipbox-container'); ?>>
                <div class="eael-elements-flip-box-front-container">

                    <?php
                    if ( $settings['eael_flipbox_front_content_type'] == 'template' ) {
	                    if ( ! empty( $settings['eael_flipbox_front_templates'] ) ) {
		                    // WPML Compatibility
		                    if ( ! is_array( $settings['eael_flipbox_front_templates'] ) ) {
			                    $settings['eael_flipbox_front_templates'] = apply_filters( 'wpml_object_id', $settings['eael_flipbox_front_templates'], 'wp_template', true );
		                    }
		                    echo Plugin::$instance->frontend->get_builder_content( $settings['eael_flipbox_front_templates'], true );
	                    }
                    } else { ?>

                        <div class="eael-elements-slider-display-table">
                            <div class="eael-elements-flip-box-vertical-align">
                                <div class="eael-elements-flip-box-padding">
                                    <div class="eael-elements-flip-box-icon-image">
                                        <?php if ('icon' === $settings['eael_flipbox_img_or_icon']) : ?>
                                            <?php $this->render_icon($settings); ?>
                                        <?php elseif ('img' === $settings['eael_flipbox_img_or_icon']) : ?>
                                            <img class="eael-flipbox-image-as-icon" src="<?php echo esc_url($flipbox_image_url); ?>" alt="<?php echo esc_attr(get_post_meta($flipbox_image['id'], '_wp_attachment_image_alt', true)); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <?php if ( !empty( $settings['eael_flipbox_front_title'] ) ): ?>
                                    <<?php echo Helper::eael_validate_html_tag($settings['eael_flipbox_front_title_tag']); ?> class="eael-elements-flip-box-heading"><?php echo Helper::eael_wp_kses( $settings['eael_flipbox_front_title'] ); ?></<?php echo Helper::eael_validate_html_tag($settings['eael_flipbox_front_title_tag']); ?>>
                                    <?php endif; ?>
                                    <div class="eael-elements-flip-box-content">
	                                    <?php $tagsPresent = preg_match( '/<(h[1-6]|p|pre)>.*<\/(h[1-6]|p|pre)>/i', $settings['eael_flipbox_front_text'] ); ?>
                                        <?php echo $tagsPresent ? $settings['eael_flipbox_front_text'] : '<p>' . $settings['eael_flipbox_front_text'] . '</p>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="eael-elements-flip-box-rear-container">

                    <?php
                    if ( $settings['eael_flipbox_back_content_type'] == 'template' ) {
	                    if ( ! empty( $settings['eael_flipbox_back_templates'] ) ) {
		                    // WPML Compatibility
		                    if ( ! is_array( $settings['eael_flipbox_back_templates'] ) ) {
			                    $settings['eael_flipbox_back_templates'] = apply_filters( 'wpml_object_id', $settings['eael_flipbox_back_templates'], 'wp_template', true );
		                    }
		                    echo Plugin::$instance->frontend->get_builder_content( $settings['eael_flipbox_back_templates'], true );
	                    }
                    } else { ?>
                        <div class="eael-elements-slider-display-table">
                            <div class="eael-elements-flip-box-vertical-align">
                                <div class="eael-elements-flip-box-padding">
                                    <?php if ('none' != $settings['eael_flipbox_img_or_icon_back']) { ?>
                                        <div class="eael-elements-flip-box-icon-image">
                                            <?php if ('img' == $settings['eael_flipbox_img_or_icon_back']) { ?>
                                                <img class="eael-flipbox-image-as-icon" <?php $this->print_render_attribute_string('flipbox-back-icon-image-container'); ?>>
                                            <?php } elseif ('icon' == $settings['eael_flipbox_img_or_icon_back']) {
                                                $this->render_icon($settings, 'back');
                                            } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ( !empty( $settings['eael_flipbox_back_title'] ) ): ?>
                                    <<?php echo $flipbox_if_html_title_tag, ' ', $this->get_render_attribute_string('flipbox-title-container'); ?>><?php echo Helper::eael_wp_kses( $settings['eael_flipbox_back_title'] ); ?></<?php echo $flipbox_if_html_title_tag; ?>>
                                    <?php endif; ?>
                                    <div class="eael-elements-flip-box-content">
                                        <?php $tagsPresent = preg_match( '/<(h[1-6]|p|pre)>.*<\/(h[1-6]|p|pre)>/i', $settings['eael_flipbox_back_text'] ); ?>
                                        <?php echo $tagsPresent ? $settings['eael_flipbox_back_text'] : '<p>' . $settings['eael_flipbox_back_text'] . '</p>'; ?>
                                    </div>

                                    <?php if ($settings['flipbox_link_type'] == 'button' && !empty($settings['flipbox_button_text'])) : ?>
                                        <a <?php echo $this->get_render_attribute_string('flipbox-button-container'); ?>>
                                            <?php if ('before' == $settings['button_icon_position']) {
                                                $this->render_icon($settings, 'button');
                                            } ?>
                                            <?php echo Helper::eael_wp_kses($settings['flipbox_button_text']); ?>
                                            <?php if ('after' == $settings['button_icon_position']) {
                                                $this->render_icon($settings, 'button');
                                            } ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </<?php echo $flipbox_if_html_tag; ?>>
        </div>

        <script>
            jQuery(document).ready(function( $ ) {
                $(".eael-flip-box-click").on( 'click', function() {
                    $(this).toggleClass( '--active' );
                });
            });
        </script>

<?php
    }

    protected function render_icon($settings, $icon_location = 'front')
    {
        $new_icon_key = $old_icon_key = '';
        switch ($icon_location){
            case 'front':
                $new_icon_key = 'eael_flipbox_icon_new';
                $old_icon_key = 'eael_flipbox_icon';
                break;
            case 'back':
                $new_icon_key = 'eael_flipbox_icon_back_new';
                $old_icon_key = 'eael_flipbox_icon_back';
                break;
            case 'button':
                $new_icon_key = 'button_icon_new';
                $old_icon_key = 'button_icon';
                break;
        }

        $is_migrated = isset($settings['__fa4_migrated'][$new_icon_key]);
        $is_new_icon = empty($settings[$old_icon_key]);
        if ($is_new_icon || $is_migrated) {
            if ( 'svg' === $settings[$new_icon_key]['library'] ) {
                echo "<span class='ea-flipbox-icon eael-flipbox-svg-icon eaa-svg'>";
                Icons_Manager::render_icon( $settings[$new_icon_key] );
                echo '</span>';
            }else{
                Icons_Manager::render_icon( $settings[$new_icon_key], [ 'aria-hidden' => 'true', 'class' => "ea-flipbox-icon" ] );
            }
            ?>
        <?php } else { ?>
            <i class="<?php echo esc_attr($settings[$old_icon_key]); ?> ea-flipbox-icon "></i>
        <?php }
    }
}

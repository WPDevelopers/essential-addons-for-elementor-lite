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
use Essential_Addons_Elementor\Traits\Helper as HelperTrait;

class Flip_Box extends Widget_Base
{
    use HelperTrait;

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
            'Glassmorphism',
            'Liquid Glass Effect',
            'Frost Effect',
        ];
    }

    protected function is_dynamic_content():bool {
        if( Plugin::$instance->editor->is_edit_mode() ) {
            return false;
        }
        
        $front_content_type = $this->get_settings('eael_flipbox_front_content_type');
        $back_content_type  = $this->get_settings('eael_flipbox_back_content_type');
        $is_dynamic_content = 'template' === $front_content_type || 'template' === $back_content_type;

        return $is_dynamic_content;
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
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

        $this->add_control(
            'eael_flipbox_flip_speed',
            [
                'label'      => esc_html__('Flip Speed', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['ms', 's'],
                'range'      => [
                    'ms' => [
                        'min'  => 1,
                        'step' => 1,
                        'max'  => 1000,
                    ],
                    's'  => [
                        'min'  => 1,
                        'step' => 1,
                        'max'  => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 500,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-elements-flip-box-flip-card' => 'transition-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_flipbox_height_mode',
            [
                'label'   => esc_html__('Height Mode', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'fixed',
                'options' => [
                    'fixed' => [
                        'title' => esc_html__('Fixed Height', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-field',
                    ],
                    'auto'  => [
                        'title' => esc_html__('Auto Height', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-lightbox-expand',
                    ],
                ],
                'description' => esc_html__('Choose between fixed height or auto height that adjusts to content.', 'essential-addons-for-elementor-lite'),
                'toggle' => false,
            ]
        );

        $this->add_control(
            'eael_flipbox_height_adjustment',
            [
                'label'   => esc_html__('Adjustment', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'maximum',
                'options' => [
                    'maximum' => [
                        'title' => esc_html__('Maximum Content Height', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-align-stretch-v',
                    ],
                    'dynamic' => [
                        'title' => esc_html__('Based on Visible Content', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-align-end-v',
                    ],
                ],
                'condition' => [
                    'eael_flipbox_height_mode' => 'auto',
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
                    '{{WRAPPER}} .eael-flipbox-fixed-height:not(.eael-template)' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-flipbox-fixed-height.eael-template' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_flipbox_height_mode' => 'fixed',
                ],
            ]
        );

        $this->add_control(
            'eael_wd_liquid_glass_effect_switch',
            [
                'label' => __( 'Enable Liquid Glass Effects Front', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::SWITCHER
            ]
        );

        $this->add_control(
            'eael_wd_liquid_glass_effect_switch_rear',
            [
                'label' => __( 'Enable Liquid Glass Effects Back', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::SWITCHER
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
                'label' => esc_html__('Flip Box Style', 'essential-addons-for-elementor-lite'),
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
                'name'           => 'eael_flipbox_front_bg_color',
                'label'          => __('Front Background Color', 'essential-addons-for-elementor-lite'),
                'types'          => ['classic', 'gradient'],
                'selector'       => '{{WRAPPER}} .eael-elements-flip-box-front-container',
                'fields_options' => [
                    'color' => [
                        'default' => '#8a35ff',
                    ],
                ],
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
                'fields_options' => [
                    'color' => [
                        'default' => '#502fc6',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_flipbox_front_back_padding',
            [
                'label'      => esc_html__('Content Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'separator' => 'before',
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

        $this->start_controls_section(
			'eael_wd_liquid_glass_effect_front_section',
			[
				'label' => esc_html__( 'Liquid Glass Effects Front', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'eael_wd_liquid_glass_effect_switch' => 'yes',
                ]
			]
		);

        // Liquid Glass Effects
        $this->eael_liquid_glass_effects();

        //  Liquid Glass Shadow Effects
        $this->eael_liquid_glass_shadow_effects();

        $this->end_controls_section();

        $this->start_controls_section(
			'eael_wd_liquid_glass_effect_back_section',
			[
				'label'       => esc_html__( 'Liquid Glass Effects Back', 'essential-addons-for-elementor-lite' ),
				'tab'         => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
                ]
			]
		);

        // Liquid Glass Effects
        $this->eael_liquid_glass_effects_rear();

        //  Liquid Glass Shadow Effects
        $this->eael_liquid_glass_shadow_effects_rear();

        $this->end_controls_section();
    }

    /**
     * Controller Summary of eael_liquid_glass_effects
     */
    public function eael_pro_lock_icon() {
		if ( !apply_filters('eael/pro_enabled', false ) ) {
			$html = '<span class="e-control-motion-effects-promotion__lock-wrapper"><i class="eicon-lock"></i></span>';
			return $html;
		}
		return;
	}

    public function eael_teaser_template($texts) {
		$html = '<div class="ea-nerd-box">
			<div class="ea-nerd-box-message">' . $texts['messages'] . '</div>
			<a class="ea-nerd-box-link elementor-button elementor-button-default" href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">
			' . __('Upgrade to EA PRO', 'essential-addons-for-elementor-lite') . '
			</a>
		</div>';

		return $html;
    }
    protected function eael_liquid_glass_effects() {
        $this->add_control(
            'eael_wd_liquid_glass_effect_notice',
            [
                'type'        => Controls_Manager::ALERT,
                'alert_type'  => 'info',
                'content'     => esc_html__( 'Liquid glass effect is only visible when a semi-transparent background color is applied.', 'essential-addons-for-elementor-lite' ) . '<a href="https://essential-addons.com/docs/ea-liquid-glass-effects/" target="_blank">' . esc_html__( 'Learn More', 'essential-addons-for-elementor-lite' ) . '</a>',
                'condition'   => [
                    'eael_wd_liquid_glass_effect_switch' => 'yes',
                ]
            ]
        );

        $eael_liquid_glass_effect = apply_filters(
			'eael_liquid_glass_effect_filter',
			[
					'styles' => [
						'effect1' => esc_html__( 'Heavy Frost', 'essential-addons-for-elementor-lite' ),
						'effect2' => esc_html__( 'Soft Mist', 'essential-addons-for-elementor-lite' ),
						'effect4' => esc_html__( 'Light Frost', 'essential-addons-for-elementor-lite' ),
						'effect5' => esc_html__( 'Grain Frost', 'essential-addons-for-elementor-lite' ),
						'effect6' => esc_html__( 'Fine Frost', 'essential-addons-for-elementor-lite' ),
				],
				'conditions' => ['effect4', 'effect5', 'effect6'],
			]
        );

        $this->add_control(
            'eael_wd_liquid_glass_effect',
            [
				'label'       => esc_html__( 'Liquid Glass Presets', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => [
					'effect1' => [
						'title' => esc_html__( $eael_liquid_glass_effect['styles']['effect1'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect['styles']['effect1'], 'essential-addons-for-elementor-lite' ),
					],
					'effect2' => [
						'title' => esc_html__( $eael_liquid_glass_effect['styles']['effect2'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect['styles']['effect2'], 'essential-addons-for-elementor-lite' ),
					],
					'effect4' => [
						'title' => esc_html__( $eael_liquid_glass_effect['styles']['effect4'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect['styles']['effect4'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
					'effect5' => [
						'title' => esc_html__( $eael_liquid_glass_effect['styles']['effect5'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect['styles']['effect5'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
					'effect6' => [
						'title' => esc_html__( $eael_liquid_glass_effect['styles']['effect6'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect['styles']['effect6'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
				],
				'prefix_class' => 'eael_wd_liquid_glass-',
				'condition' => [
					'eael_wd_liquid_glass_effect_switch' => 'yes',
				],
				'default' => 'effect1',
				'multiline' => true,
			]
        );

        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $this->add_control(
				'eael_wd_liquid_glass_effect_pro_alert',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => $this->eael_teaser_template( [
						'messages' => __( "To use this Liquid glass preset, Upgrade to Essential Addons Pro", 'essential-addons-for-elementor-lite' ),
					] ),
					'condition' => [
						'eael_wd_liquid_glass_effect_switch' => 'yes',
						'eael_wd_liquid_glass_effect'        => ['effect4', 'effect5', 'effect6'],
					]
				]
			);
		} else {
			$this->add_control(
				'eael_wd_liquid_glass_effect_settings',
				[
					'label'     => esc_html__( 'Liquid Glass Settings', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'eael_wd_liquid_glass_effect_switch' => 'yes',
					]
				]
			);
		}

        // Background Color Controls
        $this->eael_wd_liquid_glass_effect_bg_color_effect( $this, 'effect1', '#FFFFFF1F', '.eael-elements-flip-box-front-container' );
        $this->eael_wd_liquid_glass_effect_bg_color_effect( $this, 'effect2', '#FFFFFF1F', '.eael-elements-flip-box-front-container' );

        do_action( 'eael_wd_liquid_glass_effect_bg_color_effect4', $this, 'effect4', '#FFFFFF1F', '.eael-elements-flip-box-front-container' );
        do_action( 'eael_wd_liquid_glass_effect_bg_color_effect5', $this, 'effect5', '#FFFFFF1F', '.eael-elements-flip-box-front-container' );
        do_action( 'eael_wd_liquid_glass_effect_bg_color_effect6', $this, 'effect6', '#FFFFFF1F', '.eael-elements-flip-box-front-container' );

        // Backdrop Filter Controls
        $this->eael_wd_liquid_glass_effect_backdrop_filter_effect( $this, 'effect1', '24', '.eael-elements-flip-box-front-container' );
        $this->eael_wd_liquid_glass_effect_backdrop_filter_effect( $this, 'effect2', '20', '.eael-elements-flip-box-front-container' );

        // Brightness Effect Controls
		$this->add_control(
			'eael_wd_liquid_glass_effect_brightness_effect2',
			[
				'label' => esc_html__( 'Brightness', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_wd_liquid_glass-effect2 .eael-elements-flip-box-front-container' => 'backdrop-filter: blur({{eael_wd_liquid_glass_effect_backdrop_filter_effect2.SIZE}}px) brightness({{SIZE}});',
				],
				'condition'    => [
					'eael_wd_liquid_glass_effect_switch' => 'yes',
					'eael_wd_liquid_glass_effect'        => 'effect2',
				]
			]
		);

        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_effect4', $this, 'effect4', '', '.eael-elements-flip-box-front-container' );
        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_effect5', $this, 'effect5', '', '.eael-elements-flip-box-front-container' );
        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_effect6', $this, 'effect6', '', '.eael-elements-flip-box-front-container' );

        // Noise Distortion Settings (Pro)
		do_action( 'eael_wd_liquid_glass_effect_noise_action', $this );
    }

    /**
     * Summary of eael_liquid_glass_shadow_effects
     */
    protected function eael_liquid_glass_shadow_effects() {
        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $this->add_control(
                'eael_wd_liquid_glass_shadow_effect',
                [
                    'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT2,
                    'default'   => 'effect1',
                    'separator' => 'before',
                    'options'   => [
                        '' 		 => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                        'effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
                        'effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
                        'effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
                        'effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
                    ],
                    'prefix_class' => 'eael_wd_liquid_glass_shadow-',
                    'condition'    => [
                        'eael_wd_liquid_glass_effect_switch' => 'yes',
                        'eael_wd_liquid_glass_effect'        => ['effect1', 'effect2'],
                    ]
                ]
            );

            $this->add_control(
                'eael_wd_liquid_glass_shadow_inner',
                [
                    'label'     => esc_html__( 'Shadow Settings', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'eael_wd_liquid_glass_effect_switch'  => 'yes',
                        'eael_wd_liquid_glass_shadow_effect!' => '',
                        'eael_wd_liquid_glass_effect'        => ['effect1', 'effect2'],
                    ],
                ]
            );

            // Liquid Glass Border Effects
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect1', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect2', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect3', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect4', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2'] );

            // Liquid Glass Border Radius Effects
            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect1', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 24,
                    'right'    => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect2', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 16,
                    'right'    => 16,
                    'bottom'   => 16,
                    'left'     => 16,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect3', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'right'    => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect4', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'right'    => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            // Liquid Glass Shadow Effects
            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect1', '.eael-elements-flip-box-front-container',
                [
                    'color'      => 'rgba(0,0,0,0.78)',
                    'horizontal' => 0,
                    'vertical'   => 19,
                    'blur'       => 26,
                    'spread'     => 1,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect2', '.eael-elements-flip-box-front-container',
                [
                    'color'      => '#383C65',
                    'horizontal' => 0,
                    'vertical'   => 0,
                    'blur'       => 33,
                    'spread'     => -2,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect3', '.eael-elements-flip-box-front-container',
                [
                    'color'      => 'rgba(255, 255, 255, 0.4)',
                    'horizontal' => 1,
                    'vertical'   => 1,
                    'blur'       => 10,
                    'spread'     => 5,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect4', '.eael-elements-flip-box-front-container',
                [
                    'color'      => '#00000040',
                    'horizontal' => 0,
                    'vertical'   => 9,
                    'blur'       => 21,
                    'spread'     => 0,
                ],
                ['effect1', 'effect2']
            );
        } else {
            $this->add_control(
                'eael_wd_liquid_glass_shadow_effect',
                [
                    'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT2,
                    'default'   => 'effect1',
                    'separator' => 'before',
                    'options'   => [
                        '' 		 => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                        'effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
                        'effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
                        'effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
                        'effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
                    ],
                    'prefix_class' => 'eael_wd_liquid_glass_shadow-',
                    'condition'    => [
                        'eael_wd_liquid_glass_effect_switch' => 'yes',
                        'eael_wd_liquid_glass_effect'        => ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'],
                    ]
                ]
            );

            $this->add_control(
                'eael_wd_liquid_glass_shadow_inner',
                [
                    'label'     => esc_html__( 'Shadow Settings', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'eael_wd_liquid_glass_effect_switch'  => 'yes',
                        'eael_wd_liquid_glass_shadow_effect!' => '',
                        'eael_wd_liquid_glass_effect'        => ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'],
                    ],
                ]
            );

            // Liquid Glass Border Effects
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect1', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect2', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect3', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect( $this, 'effect4', '#FFFFFF1F', '.eael-elements-flip-box-front-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );

            // Liquid Glass Border Radius Effects
            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect1', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 24,
                    'right'    => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect2', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 16,
                    'right'    => 16,
                    'bottom'   => 16,
                    'left'     => 16,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect3', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'right'    => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect($this, 'effect4', '.eael-elements-flip-box-front-container',
                [
                    'top' 	  => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'right'    => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            // Liquid Glass Shadow Effects
            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect1', '.eael-elements-flip-box-front-container',
                [
                    'color'      => 'rgba(0,0,0,0.78)',
                    'horizontal' => 0,
                    'vertical'   => 19,
                    'blur'       => 26,
                    'spread'     => 1,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect2', '.eael-elements-flip-box-front-container',
                [
                    'color'      => '#383C65',
                    'horizontal' => 0,
                    'vertical'   => 0,
                    'blur'       => 33,
                    'spread'     => -2,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect3', '.eael-elements-flip-box-front-container',
                [
                    'color'      => 'rgba(255, 255, 255, 0.4)',
                    'horizontal' => 1,
                    'vertical'   => 1,
                    'blur'       => 10,
                    'spread'     => 5,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_shadow_effect($this, 'effect4', '.eael-elements-flip-box-front-container',
                [
                    'color'      => '#00000040',
                    'horizontal' => 0,
                    'vertical'   => 9,
                    'blur'       => 21,
                    'spread'     => 0,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );
        }
    }

    /**
     * Controller Summary of eael_liquid_glass_effects
     */
    protected function eael_liquid_glass_effects_rear() {
        $this->add_control(
            'eael_wd_liquid_glass_effect_notice_rear',
            [
                'type'        => Controls_Manager::ALERT,
                'alert_type'  => 'info',
                'content'     => esc_html__( 'Liquid glass effect is only visible when a semi-transparent background color is applied.', 'essential-addons-for-elementor-lite' ) . '<a href="https://essential-addons.com/docs/ea-liquid-glass-effects/" target="_blank">' . esc_html__( 'Learn More', 'essential-addons-for-elementor-lite' ) . '</a>',
                'condition'   => [
                    'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
                ]
            ]
        );

        $eael_liquid_glass_effect_rear = apply_filters(
			'eael_liquid_glass_effect_filter',
			[
					'styles' => [
						'effect1' => esc_html__( 'Heavy Frost', 'essential-addons-for-elementor-lite' ),
						'effect2' => esc_html__( 'Soft Mist', 'essential-addons-for-elementor-lite' ),
						'effect4' => esc_html__( 'Light Frost', 'essential-addons-for-elementor-lite' ),
						'effect5' => esc_html__( 'Grain Frost', 'essential-addons-for-elementor-lite' ),
						'effect6' => esc_html__( 'Fine Frost', 'essential-addons-for-elementor-lite' ),
				],
			]
        );

        $this->add_control(
            'eael_wd_liquid_glass_effect_rear',
            [
				'label'       => esc_html__( 'Liquid Glass Presets', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => [
					'effect1' => [
						'title' => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect1'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect1'], 'essential-addons-for-elementor-lite' ),
					],
					'effect2' => [
						'title' => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect2'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect2'], 'essential-addons-for-elementor-lite' ),
					],
					'effect4' => [
						'title' => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect4'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect4'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
					'effect5' => [
						'title' => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect5'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect5'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
					'effect6' => [
						'title' => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect6'], 'essential-addons-for-elementor-lite' ),
						'text'  => esc_html__( $eael_liquid_glass_effect_rear['styles']['effect6'], 'essential-addons-for-elementor-lite' )  . $this->eael_pro_lock_icon(),
					],
				],
				'prefix_class' => 'eael_wd_liquid_glass_rear-',
				'condition' => [
					'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
				],
				'default' => 'effect1',
				'multiline' => true,
			]
        );

        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $this->add_control(
				'eael_wd_liquid_glass_effect_pro_alert_rear',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => $this->eael_teaser_template( [
						'messages' => __( "To use this Liquid glass preset, Upgrade to Essential Addons Pro", 'essential-addons-for-elementor-lite' ),
					] ),
					'condition' => [
						'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
						'eael_wd_liquid_glass_effect_rear'        => ['effect4', 'effect5', 'effect6'],
					]
				]
			);
		} else {
			$this->add_control(
				'eael_wd_liquid_glass_effect_settings_rear',
				[
					'label'     => esc_html__( 'Liquid Glass Settings', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
					]
				]
			);
		}

        // Background Color Controls
        $this->eael_wd_liquid_glass_effect_bg_color_effect_rear( $this, 'effect1', '#FFFFFF1F', '.eael-elements-flip-box-rear-container' );
        $this->eael_wd_liquid_glass_effect_bg_color_effect_rear( $this, 'effect2', '#FFFFFF1F', '.eael-elements-flip-box-rear-container' );


        do_action( 'eael_wd_liquid_glass_effect_bg_color_rear_effect4', $this, 'effect4', '#FFFFFF1F', '.eael-elements-flip-box-rear-container' );
        do_action( 'eael_wd_liquid_glass_effect_bg_color_rear_effect5', $this, 'effect5', '#FFFFFF1F', '.eael-elements-flip-box-rear-container' );
        do_action( 'eael_wd_liquid_glass_effect_bg_color_rear_effect6', $this, 'effect6', '#FFFFFF1F', '.eael-elements-flip-box-rear-container' );

        // Backdrop Filter Controls
        $this->eael_wd_liquid_glass_effect_backdrop_filter_effect_rear( $this, 'effect1', '24', '.eael-elements-flip-box-rear-container' );
        $this->eael_wd_liquid_glass_effect_backdrop_filter_effect_rear( $this, 'effect2', '20', '.eael-elements-flip-box-rear-container' );

        // Brightness Effect Controls
		$this->add_control(
			'eael_wd_liquid_glass_effect_brightness_effect2_rear',
			[
				'label' => esc_html__( 'Brightness', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_wd_liquid_glass_rear-effect2 .eael-elements-flip-box-rear-container' => 'backdrop-filter: blur({{eael_wd_liquid_glass_effect_backdrop_filter_rear_effect2.SIZE}}px) brightness({{SIZE}});',
				],
				'condition'    => [
					'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
					'eael_wd_liquid_glass_effect_rear'        => 'effect2',
				]
			]
		);

        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_rear_effect4', $this, 'effect4', '', '.eael-elements-flip-box-rear-container' );
        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_rear_effect5', $this, 'effect5', '', '.eael-elements-flip-box-rear-container' );
        do_action( 'eael_wd_liquid_glass_effect_backdrop_filter_rear_effect6', $this, 'effect6', '', '.eael-elements-flip-box-rear-container' );

        // Noise Distortion Settings (Pro)
        do_action( 'eael_wd_liquid_glass_effect_noise_action_rear', $this );
    }

    /**
     * Summary of eael_liquid_glass_shadow_effects
     */
    protected function eael_liquid_glass_shadow_effects_rear() {
        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $this->add_control(
                'eael_wd_liquid_glass_shadow_effect_rear',
                [
                    'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT2,
                    'default'   => 'rear_effect1',
                    'separator' => 'before',
                    'options'   => [
                        '' 		 => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                        'rear_effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
                        'rear_effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
                        'rear_effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
                        'rear_effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
                    ],
                    'prefix_class' => 'eael_wd_liquid_glass_shadow-',
                    'condition'    => [
                        'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
                        'eael_wd_liquid_glass_effect_rear'        => ['effect1', 'effect2'],
                    ]
                ]
            );

            $this->add_control(
                'eael_wd_liquid_glass_shadow_inner_rear',
                [
                    'label'     => esc_html__( 'Shadow Settings', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'eael_wd_liquid_glass_effect_switch_rear'  => 'yes',
                        'eael_wd_liquid_glass_shadow_effect_rear!' => '',
                        'eael_wd_liquid_glass_effect_rear'         => ['effect1', 'effect2'],
                    ],
                ]
            );

            // Liquid Glass Border Effects
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect1', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect2', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect3', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect4', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2'] );

            // Liquid Glass Border Radius Effects
            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect1', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 24,
                    'right'    => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect2', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 16,
                    'right'    => 16,
                    'bottom'   => 16,
                    'left'     => 16,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect3', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'right'    => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect4', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'right'    => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2']
            );

            // Liquid Glass Shadow Effects
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect1', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => 'rgba(0,0,0,0.78)',
                    'horizontal' => 0,
                    'vertical'   => 19,
                    'blur'       => 26,
                    'spread'     => 1,
                ],
                ['effect1', 'effect2']
            );
            
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect2', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => '#383C65',
                    'horizontal' => 0,
                    'vertical'   => 0,
                    'blur'       => 33,
                    'spread'     => -2,
                ],
                ['effect1', 'effect2']
            );
            
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect3', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => 'rgba(255, 255, 255, 0.4)',
                    'horizontal' => 1,
                    'vertical'   => 1,
                    'blur'       => 10,
                    'spread'     => 5,
                ],
                ['effect1', 'effect2']
            );

            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect4', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => '#00000040',
                    'horizontal' => 0,
                    'vertical'   => 9,
                    'blur'       => 21,
                    'spread'     => 0,
                ],
                ['effect1', 'effect2']
            );
        } else {
            $this->add_control(
                'eael_wd_liquid_glass_shadow_effect_rear',
                [
                    'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT2,
                    'default'   => 'rear_effect1',
                    'separator' => 'before',
                    'options'   => [
                        '' 		 => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                        'rear_effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
                        'rear_effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
                        'rear_effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
                        'rear_effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
                    ],
                    'prefix_class' => 'eael_wd_liquid_glass_shadow-',
                    'condition'    => [
                        'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
                        'eael_wd_liquid_glass_effect_rear'        => ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'],
                    ]
                ]
            );

            $this->add_control(
                'eael_wd_liquid_glass_shadow_inner_rear',
                [
                    'label'     => esc_html__( 'Shadow Settings', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'eael_wd_liquid_glass_effect_switch_rear'  => 'yes',
                        'eael_wd_liquid_glass_shadow_effect_rear!' => '',
                        'eael_wd_liquid_glass_effect_rear'         => ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'],
                    ],
                ]
            );

            // Liquid Glass Border Effects
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect1', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect2', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect3', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );
            $this->eael_wd_liquid_glass_border_effect_rear( 'rear_effect4', '#FFFFFF1F', '.eael-elements-flip-box-rear-container', ['effect1', 'effect2', 'effect4', 'effect5', 'effect6'] );

            // Liquid Glass Border Radius Effects
            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect1', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 24,
                    'right'    => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect2', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 16,
                    'right'    => 16,
                    'bottom'   => 16,
                    'left'     => 16,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect3', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 8,
                    'bottom'   => 8,
                    'left'     => 8,
                    'right'    => 8,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_border_radius_effect_rear('rear_effect4', '.eael-elements-flip-box-rear-container',
                [
                    'top' 	  => 24,
                    'bottom'   => 24,
                    'left'     => 24,
                    'right'    => 24,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            // Liquid Glass Shadow Effects
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect1', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => 'rgba(0,0,0,0.78)',
                    'horizontal' => 0,
                    'vertical'   => 19,
                    'blur'       => 26,
                    'spread'     => 1,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );
            
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect2', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => '#383C65',
                    'horizontal' => 0,
                    'vertical'   => 0,
                    'blur'       => 33,
                    'spread'     => -2,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );
            
            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect3', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => 'rgba(255, 255, 255, 0.4)',
                    'horizontal' => 1,
                    'vertical'   => 1,
                    'blur'       => 10,
                    'spread'     => 5,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );

            $this->eael_wd_liquid_glass_shadow_effect_rear('rear_effect4', '.eael-elements-flip-box-rear-container',
                [
                    'color'      => '#00000040',
                    'horizontal' => 0,
                    'vertical'   => 9,
                    'blur'       => 21,
                    'spread'     => 0,
                ],
                ['effect1', 'effect2', 'effect4', 'effect5', 'effect6']
            );
        }
    }

    //Add border effect for Liquid Glass Effect
	public function eael_wd_liquid_glass_border_effect_rear( $effect, $default_color, $selector, $condition ) {
		$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'      => 'eael_wd_liquid_glass_border_'.$effect,
			'fields_options' => [
				'border' => [
					'default' => 'solid',
				],
				'width' => [
					'default' => [
						'top'      => '1',
						'right'    => '1',
						'bottom'   => '1',
						'left'     => '1',
						'isLinked' =>  false,
					],
				],
				'color' => [
					'default' => $default_color,
				],
			],
			'selector'  => '{{WRAPPER}}.eael_wd_liquid_glass_shadow-'.$effect .' '.$selector,
			'condition' => [
				'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
				'eael_wd_liquid_glass_shadow_effect_rear' => $effect,
				'eael_wd_liquid_glass_effect_rear'        => $condition,
			],
		]
	);
	}

    // Add border radius effect for Liquid Glass Effect
	public function eael_wd_liquid_glass_border_radius_effect_rear( $effect, $selector, $default_radius, $condition ) {
		$this->add_control(
		'eael_wd_liquid_glass_border_radius_'.$effect,
		[
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'rem', 'custom' ],
			'default' => $default_radius,
			'selectors'  => [
				'{{WRAPPER}}.eael_wd_liquid_glass_shadow-'.$effect.' '.$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'eael_wd_liquid_glass_effect_switch_rear' => 'yes',
				'eael_wd_liquid_glass_shadow_effect_rear' => $effect,
                'eael_wd_liquid_glass_effect_rear'        => $condition,
			],
		]
	);
	}

    // Add shadow effect for Liquid Glass Effect
	public function eael_wd_liquid_glass_shadow_effect_rear( $effect, $selector, $default_shadow, $condition ) {
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
					'name'      => 'eael_wd_liquid_glass_shadow_' . $effect,
					'fields_options'     => [
						'box_shadow_type' => [ 'default' => 'yes' ],
						'box_shadow'      => [
							'default' => $default_shadow,
						],
					],
					'selector'  => '{{WRAPPER}}.eael_wd_liquid_glass_shadow-'.$effect.' ' . $selector,
					'condition' => [
						'eael_wd_liquid_glass_effect_switch_rear'  => 'yes',
						'eael_wd_liquid_glass_shadow_effect_rear' => $effect,
                        'eael_wd_liquid_glass_effect_rear'        => $condition,
					],
			]
		);
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

        // Add height mode class
        $height_mode_class = 'auto' === $settings['eael_flipbox_height_mode'] ? 'eael-flipbox-auto-height' : 'eael-flipbox-fixed-height';
        $height_adjustment_class = 'maximum' === $settings['eael_flipbox_height_adjustment'] ? 'eael-flipbox-max' : 'eael-flipbox-dynamic';

        $this->add_render_attribute(
            'eael_flipbox_main_wrap',
            [
                'class' => [
                    'eael-elements-flip-box-container',
                    'eael-animate-flip',
                    'eael-' . esc_attr($settings['eael_flipbox_type']),
                    'eael-' . esc_attr($settings['eael_flipbox_front_content_type']),
                    'eael-flip-box-' . esc_attr($settings['eael_flipbox_event_type']),
                    $height_mode_class,
                    $height_adjustment_class,
                ],
            ]
        );
        ?>
        <div <?php $this->print_render_attribute_string('eael_flipbox_main_wrap'); ?>>

            <<?php echo esc_html( $flipbox_if_html_tag ) . ' '; $this->print_render_attribute_string('flipbox-container'); ?>>
                <div class="eael-elements-flip-box-front-container">
                    <?php do_action( 'eael_wd_liquid_glass_effect_svg_pro', $this, $settings, '.eael-elements-flip-box-front-container' ); ?>

                    <?php
                    if ( $settings['eael_flipbox_front_content_type'] == 'template' ) {
	                    if ( ! empty( $settings['eael_flipbox_front_templates'] ) && Helper::is_elementor_publish_template( $settings['eael_flipbox_front_templates'] ) ) {
		                    // WPML Compatibility
		                    if ( ! is_array( $settings['eael_flipbox_front_templates'] ) ) {
			                    $settings['eael_flipbox_front_templates'] = apply_filters( 'wpml_object_id', $settings['eael_flipbox_front_templates'], 'wp_template', true );
		                    }
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		                    echo Plugin::$instance->frontend->get_builder_content( $settings['eael_flipbox_front_templates'], true );
	                    }
                    } else { ?>

                        <div class="eael-elements-slider-display-table">
                            <div class="eael-elements-flip-box-vertical-align">
                                <div class="eael-elements-flip-box-padding">
                                    <div class="eael-elements-flip-box-icon-image">
                                        <?php if ('icon' === $settings['eael_flipbox_img_or_icon']) : ?>
                                            <?php
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            $this->render_icon($settings); ?>
                                        <?php elseif ('img' === $settings['eael_flipbox_img_or_icon']) : ?>
                                            <img class="eael-flipbox-image-as-icon" src="<?php echo esc_url($flipbox_image_url); ?>" alt="<?php echo esc_attr(get_post_meta($flipbox_image['id'], '_wp_attachment_image_alt', true)); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                    if ( !empty( $settings['eael_flipbox_front_title'] ) ){
                                        $title_tag = Helper::eael_validate_html_tag( $settings['eael_flipbox_front_title_tag'] );
                                        $title = '<'. $title_tag . ' class="eael-elements-flip-box-heading">';
                                        $title .= $settings['eael_flipbox_front_title'];
                                        $title .= '</' . $title_tag . '>';
                                        echo wp_kses( $title, Helper::eael_allowed_tags() );
                                    }
                                    ?>
                                    <div class="eael-elements-flip-box-content">
	                                    <?php 
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo $this->parse_text_editor( $settings['eael_flipbox_front_text'] );?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="eael-elements-flip-box-rear-container">
                    <?php do_action( 'eael_wd_liquid_glass_effect_svg_pro_back', $this, $settings, '.eael-elements-flip-box-rear-container' ); ?>
                    <?php
                    if ( $settings['eael_flipbox_back_content_type'] == 'template' ) {
	                    if ( ! empty( $settings['eael_flipbox_back_templates'] ) && Helper::is_elementor_publish_template( $settings['eael_flipbox_back_templates'] ) ) {
		                    // WPML Compatibility
		                    if ( ! is_array( $settings['eael_flipbox_back_templates'] ) ) {
			                    $settings['eael_flipbox_back_templates'] = apply_filters( 'wpml_object_id', $settings['eael_flipbox_back_templates'], 'wp_template', true );
		                    }
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
                                    <<?php echo esc_html( $flipbox_if_html_title_tag ), ' '; $this->print_render_attribute_string('flipbox-title-container'); ?>><?php echo wp_kses( $settings['eael_flipbox_back_title'], Helper::eael_allowed_tags() ); ?></<?php echo esc_html( $flipbox_if_html_title_tag ); ?>>
                                    <?php endif; ?>
                                    <div class="eael-elements-flip-box-content">
                                        <?php
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo $this->parse_text_editor( $settings['eael_flipbox_back_text'] );
                                        ?>
                                    </div>

                                    <?php if ($settings['flipbox_link_type'] == 'button' && !empty($settings['flipbox_button_text'])) : ?>
                                        <a <?php $this->print_render_attribute_string('flipbox-button-container'); ?>>
                                            <?php if ('before' == $settings['button_icon_position']) {
                                                $this->render_icon($settings, 'button');
                                            } ?>
                                            <?php echo wp_kses( $settings['flipbox_button_text'], Helper::eael_allowed_tags() ); ?>
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
            </<?php echo esc_html( $flipbox_if_html_tag ); ?>>
        </div>
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

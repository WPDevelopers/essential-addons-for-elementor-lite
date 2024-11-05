<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper;

class Cta_Box extends Widget_Base
{
    

    public function get_name()
    {
        return 'eael-cta-box';
    }

    public function get_title()
    {
        return esc_html__('Call to Action', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-call-to-action';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'call to action',
            'ea call to action',
            'cta',
            'ea cta',
            'button',
            'buy button',
            'action box',
            'ea',
            'essential addons'
        ];
    }

    protected function is_dynamic_content():bool {
        if( Plugin::$instance->editor->is_edit_mode() ) {
            return false;
        }
        $content_type       = $this->get_settings('eael_cta_title_content_type');
        $is_dynamic_content = 'template' === $content_type;

        return $is_dynamic_content;
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/call-to-action/';
    }

    protected function register_controls()
    {

        /**
         * Call to Action Content Settings
         */
        $this->start_controls_section(
            'eael_section_cta_content_settings',
            [
                'label' => esc_html__('Content Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_cta_type',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-basic',
                'label_block' => false,
                'options' => [
                    'cta-basic' => esc_html__('Basic', 'essential-addons-for-elementor-lite'),
                    'cta-flex' => esc_html__('Flex Grid', 'essential-addons-for-elementor-lite'),
                    'cta-icon-flex' => esc_html__('Flex Grid with Icon', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

	    $this->add_control(
		    'eael_cta_preset',
		    [
			    'label' => esc_html__('Content Preset', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'cta-preset-1',
			    'label_block' => false,
			    'options' => [
				    'cta-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
				    'cta-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
			    ],
		    ]
	    );

        /**
         * Condition: 'eael_cta_type' => 'cta-basic'
         */
        $this->add_responsive_control(
            'eael_cta_content_type',
            [
                'label' => esc_html__('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-default',
                'label_block' => false,
                'options' => [
                    'cta-default' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                    'cta-center' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                    'cta-right' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'prefix_class' => 'content-align-%s',
                'condition' => [
                    'eael_cta_type' => 'cta-basic',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_color_type',
            [
                'label' => esc_html__('Color Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cta-bg-color',
                'label_block' => false,
                'options' => [
                    'cta-bg-color' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                    'cta-bg-img' => esc_html__('Background Image', 'essential-addons-for-elementor-lite'),
                    'cta-bg-img-fixed' => esc_html__('Background Fixed Image', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        /**
         * Condition: 'eael_cta_color_type' => 'cta-bg-img' && 'eael_cta_color_type' => 'cta-bg-img-fixed',
         */
        $this->add_control(
            'eael_cta_bg_image',
            [
                'label' => esc_html__('Background Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.bg-img' => 'background-image: url({{URL}});',
                    '{{WRAPPER}} .eael-call-to-action.bg-img-fixed' => 'background-image: url({{URL}});',
                ],
                'condition' => [
                    'eael_cta_color_type' => ['cta-bg-img', 'cta-bg-img-fixed'],
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
			'eael_cta_bg_image_background_manager',
			[
				'label' => esc_html__( 'Background Image Options', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				'label_on' => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'condition' => [
                    'eael_cta_color_type' => ['cta-bg-img', 'cta-bg-img-fixed'],
                ],
			]
		);

		$this->start_popover();

        $this->add_control(
			'eael_cta_bg_image_repeat',
			[
				'label' => esc_html__( 'Repeat', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no-repeat',
				'options' => [
					'no-repeat' => esc_html__( 'No Repeat', 'essential-addons-for-elementor-lite' ),
					'repeat' => esc_html__( 'Repeat', 'essential-addons-for-elementor-lite' ),
					'repeat-x' => esc_html__( 'Repeat X', 'essential-addons-for-elementor-lite' ),
					'repeat-y' => esc_html__( 'Repeat Y', 'essential-addons-for-elementor-lite' ),
					'round' => esc_html__( 'Round', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action.bg-img' => 'background-repeat: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'eael_cta_bg_image_position',
			[
				'label' => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'top' => esc_html__( 'Top', 'essential-addons-for-elementor-lite' ),
					'right' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
					'center' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'bottom' => esc_html__( 'Bottom', 'essential-addons-for-elementor-lite' ),
					'left' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action.bg-img' => 'background-position: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'eael_cta_bg_image_size',
			[
				'label' => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'essential-addons-for-elementor-lite' ),
					'contain' => esc_html__( 'Contain', 'essential-addons-for-elementor-lite' ),
					'inherit' => esc_html__( 'Inherit', 'essential-addons-for-elementor-lite' ),
					'initial' => esc_html__( 'Initial', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action.bg-img' => 'background-size: {{VALUE}};',
				],
			]
		);

		$this->end_popover();

        $this->add_control(
            'eael_cta_bg_overlay',
            [
                'label' => __('Background Overlay', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'after',
                'prefix_class' => 'eael-cta-overlay-',
                'condition' => [
                    'eael_cta_color_type!' => 'cta-bg-color',
                ],
            ]
        );

        /**
         * Condition: 'eael_cta_type' => 'cta-icon-flex'
         */
        $this->add_control(
            'eael_cta_flex_grid_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_cta_flex_grid_icon',
                'default' => [
                    'value' => 'fas fa-bullhorn',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_cta_type' => 'cta-icon-flex',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_sub_title',
            [
                'label' => esc_html__('Sub Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                    'categories'   => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::TEXT_CATEGORY,
                    ],
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_cta_title',
            [
                'label' => esc_html__('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Sample Call to Action Heading', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Select Tag', 'essential-addons-for-elementor-lite'),
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
            ]
        );

        $this->add_control(
            'eael_cta_title_content_type',
            [
                'label' => __('Content Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => __('Content', 'essential-addons-for-elementor-lite'),
                    'template' => __('Saved Templates', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'content',
            ]
        );

        $this->add_control(
            'eael_primary_templates',
            [
                'label' => __('Choose Template', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => Helper::get_elementor_templates(),
                'condition' => [
                    'eael_cta_title_content_type' => 'template',
                ],
            ]
        );
        $this->add_control(
            'eael_cta_content',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default' => __('<p>Add a strong one liner supporting the heading above and giving users a reason to click on the button below.</p>', 'essential-addons-for-elementor-lite'),
                'separator' => 'after',
                'condition' => [
                    'eael_cta_title_content_type' => 'content',
                ],
            ]
        );

	    // primary button
	    $this->add_control(
		    'eael_cta_btn_preset',
		    [
			    'label' => esc_html__('Button Preset', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'cta-btn-preset-2',
			    'label_block' => false,
			    'options' => [
				    'cta-btn-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
				    'cta-btn-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
			    ],
			    'condition' => [
				    'eael_cta_preset' => 'cta-preset-2',
			    ]
		    ]
	    );

	    $this->add_control(
		    'eael_cta_btn_icon',
		    [
			    'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::ICONS,
			    'default' => [
				    'value' => 'fas fa-bullhorn',
				    'library' => 'fa-solid',
			    ],
			    'condition' => [
				    'eael_cta_preset' => 'cta-preset-2',
				    'eael_cta_btn_preset' => 'cta-btn-preset-2',
			    ]
		    ]
	    );


        $this->add_control(
            'eael_cta_btn_text',
            [
                'label' => esc_html__('Primary Button Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Click Here', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_cta_btn_link',
            [
                'label' => esc_html__('Primary Button Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => '',
                ],
                'show_external' => true,
                'separator' => 'after',
            ]
        );

        // secondary button
        $this->add_control(
			'eael_cta_secondary_btn_is_show',
			[
				'label' => __( 'Show Secondary Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);
        $this->add_control(
            'eael_cta_secondary_btn_text',
            [
                'label' => esc_html__('Secondary Button Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Click Now', 'essential-addons-for-elementor-lite'),
                'condition' => array(
                    'eael_cta_secondary_btn_is_show' => 'yes'
                ),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_cta_secondary_btn_link',
            [
                'label' => esc_html__('Secondary Button Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => 'http://',
                    'is_external' => '',
                ],
                'show_external' => true,
                'separator' => 'after',
                'condition' => array(
                    'eael_cta_secondary_btn_is_show' => 'yes'
                )
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
                    'label' => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => '',
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style (Cta Title Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_cta_style_settings',
            [
                'label' => esc_html__('Call to Action Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_cta_container_width',
            [
                'label' => esc_html__('Set max width for the container?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'eael_cta_container_width_value',
            [
                'label' => __('Container Max Width (% or px)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1170,
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_cta_container_width' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f4f4f4',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action.bg-img:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_bg_color_opacity',
            [
                'label' => esc_html__('Background Color Opacity', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
                'default' => [
					'size' => .8,
				],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.bg-img:after' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'eael_cta_color_type!' => 'cta-bg-color',
                    'eael_cta_bg_overlay' => 'yes',
                    'eael_cta_preset' => 'cta-preset-2',
                ],
            ]
        );

         $this->add_control(
            'eael_cta_bg_color_preset_2',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_responsive_control(
            'eael_cta_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_cta_container_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_cta_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-call-to-action',
            ]
        );

        $this->add_control(
            'eael_cta_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_cta_shadow',
                'selector' => '{{WRAPPER}} .eael-call-to-action',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Cta Title Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_cta_title_style_settings',
            [
                'label' => esc_html__('Color &amp; Typography ', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_cta_title_heading',
            [
                'label' => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_cta_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_cta_title_typography',
                'selector' => '{{WRAPPER}} .eael-call-to-action .title',
            ]
        );

        $this->add_responsive_control(
            'eael_cta_title_margin',
            [
                'label' => esc_html__('Space', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // sub title
        $this->add_control(
            'eael_cta_sub_title_heading',
            [
                'label' => esc_html__('Sub Title Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_cta_sub_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_cta_sub_title_typography',
                'selector' => '{{WRAPPER}} .eael-call-to-action .sub-title',
            ]
        );

        $this->add_responsive_control(
            'eael_cta_sub_title_margin',
            [
                'label' => esc_html__('Space', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // content
        $this->add_control(
            'eael_cta_content_heading',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_cta_content_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_cta_content_typography',
                'selector' => '{{WRAPPER}} .eael-call-to-action p',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Primary Button Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_cta_btn_style_settings',
            [
                'label' => esc_html__('Primary Button Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_cta_btn_effect_type',
            [
                'label' => esc_html__('Effect', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'label_block' => false,
                'options' => [
                    'default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'essential-addons-for-elementor-lite'),
                    'left-to-right' => esc_html__('Left to Right', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_cta_btn_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_cta_btn_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_cta_btn_typography',
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button',
            ]
        );

        $this->add_control(
			'eael_cta_btn_is_used_gradient_bg',
			[
				'label' => __( 'Use Gradient Background', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
        );

	    $this->add_control(
		    'eael_cta_btn_icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
                'default' => [ 'unit' => 'px', 'size' => 20 ],
			    'range' => [
				    'px' => [
					    'max' => 50,
				    ],
			    ],
                'separator' => 'before',
			    'selectors' => [
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2 i' => 'font-size: {{SIZE}}px;',
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2 svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
			    ],
		    ]
	    );

        $this->start_controls_tabs('eael_cta_button_tabs');

        // Normal State Tab
        $this->start_controls_tab('eael_cta_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_cta_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button:not(.cta-secondary-button)' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_normal_text_color_preset_2',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button:not(.cta-secondary-button)' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => '',
                    'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_normal_bg_color_preset_2',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => '',
                    'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'eael_cta_btn_normal_gradient_bg_color',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button:not(.cta-secondary-button)',
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => 'yes'
                ]
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_cat_btn_normal_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button:not(.cta-secondary-button)',
                'conditions' => [
	                'relation' => 'or',
	                'terms'    => [
		                [
			                'name'     => 'eael_cta_preset',
			                'operator' => '==',
			                'value'    => 'cta-preset-1',
		                ],
		                [
			                'relation' => 'and',
			                'terms'    => [
				                [
					                'name'     => 'eael_cta_preset',
					                'operator' => '==',
					                'value'    => 'cta-preset-2',
				                ],
				                [
					                'name'     => 'eael_cta_btn_preset',
					                'operator' => '==',
					                'value'    => 'cta-btn-preset-1',
				                ],
			                ]
		                ],
	                ],
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button:not(.cta-secondary-button)' => 'border-radius: {{SIZE}}px;',
                ],
                'conditions' => [
	                'relation' => 'or',
	                'terms'    => [
		                [
			                'name'     => 'eael_cta_preset',
			                'operator' => '==',
			                'value'    => 'cta-preset-1',
		                ],
		                [
			                'relation' => 'and',
			                'terms'    => [
				                [
					                'name'     => 'eael_cta_preset',
					                'operator' => '==',
					                'value'    => 'cta-preset-2',
				                ],
				                [
					                'name'     => 'eael_cta_btn_preset',
					                'operator' => '==',
					                'value'    => 'cta-btn-preset-1',
				                ],
			                ]
		                ],
	                ],
                ],
            ]
        );

	    $this->add_control(
		    'eael_cta_btn_icon_bg',
		    [
			    'label' => esc_html__('Icon Background Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button) .btn-icon' => 'background: {{VALUE}};',
			    ],
                'separator' => 'before',
			    'condition' => [
				    'eael_cta_btn_preset' => 'cta-btn-preset-2',
				    'eael_cta_preset' => 'cta-preset-2',
			    ]
		    ]
	    );

        $this->add_control(
		    'eael_cta_btn_icon_color',
		    [
			    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button) i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button) svg' =>
                        'fill: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_cta_btn_preset' => 'cta-btn-preset-2',
				    'eael_cta_preset' => 'cta-preset-2',
			    ]
		    ]
	    );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('eael_cta_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_cta_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button:hover:not(.cta-secondary-button)' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_hover_text_color_preset_2',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button:hover:not(.cta-secondary-button)' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#3F51B5',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button:after:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button:hover:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action .cta-button.effect-1:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action .cta-button.effect-2:after' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => '',
                    'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_btn_hover_bg_color_preset_2',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button:after:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button:hover:not(.cta-secondary-button)' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action .cta-button.effect-1:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action .cta-button.effect-2:after' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => '',
                    'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );


        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'eael_cta_btn_hover_gradient_bg_color',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button:hover:not(.cta-secondary-button)',
                'condition' => [
                    'eael_cta_btn_is_used_gradient_bg' => 'yes'
                ]
			]
		);

        $this->add_control(
            'eael_cta_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button:hover:not(.cta-secondary-button)' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_btn_preset!' => 'cta-btn-preset-2',
	                'eael_cta_preset!' => 'cta-preset-2',
                ]
            ]

        );
	    $this->add_control(
		    'eael_cta_btn_icon_bg_hover',
		    [
			    'label' => esc_html__('Icon Background Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button):hover .btn-icon' =>
                        'background: {{VALUE}};',
			    ],
			    'separator' => 'before',
			    'condition' => [
				    'eael_cta_btn_preset' => 'cta-btn-preset-2',
				    'eael_cta_preset' => 'cta-preset-2',
			    ]
		    ]
	    );

	    $this->add_control(
		    'eael_cta_btn_icon_color_hover',
		    [
			    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button):hover i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-btn-preset-2:not(.cta-secondary-button):hover svg' => 'fill: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_cta_btn_preset' => 'cta-btn-preset-2',
				    'eael_cta_preset' => 'cta-preset-2',
			    ]
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_cta_button_shadow',
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button:not(.cta-secondary-button)',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Secondary Button Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_cta_secondary_btn_style_settings',
            [
                'label' => esc_html__('Secondary Button Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_cta_secondary_btn_is_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_secondary_btn_effect_type',
            [
                'label' => esc_html__('Effect', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'label_block' => false,
                'options' => [
                    'default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'essential-addons-for-elementor-lite'),
                    'left-to-right' => esc_html__('Left to Right', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_cta_secondary_btn_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_cta_secondary_btn_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_cta_secondary_btn_typography',
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button',
            ]
        );

        $this->start_controls_tabs('eael_cta_secondary_button_tabs');

        // Normal State Tab
        $this->start_controls_tab('eael_cta_secondary_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_cta_secondary_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button.cta-secondary-button' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_secondary_btn_normal_text_color_preset_2',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button.cta-secondary-button' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'eael_cta_secondary_btn_normal_bg_color',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button',
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_cat_secondary_btn_normal_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button',
            ]
        );

        $this->add_control(
            'eael_cta_secondary_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('eael_cta_secondary_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_cta_secondary_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-1:not(.cta-preset-2) .cta-button.cta-secondary-button:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_cta_secondary_btn_hover_text_color_preset_2',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-preset-2:not(.cta-preset-1) .cta-button.cta-secondary-button:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'eael_cta_secondary_btn_hover_bg_color',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button:after, {{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button:hover',
			]
		);

        $this->add_control(
            'eael_cta_secondary_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_cta_secondary_button_shadow',
                'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button.cta-secondary-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Icon Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_cta_icon_style_settings',
            [
                'label' => esc_html__('Icon Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_cta_type' => 'cta-icon-flex',
                ],
            ]
        );

        $this->add_control(
            'eael_section_cta_icon_size',
            [
                'label' => esc_html__('Font Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 80,
                ],
                'range' => [
                    'px' => [
                        'max' => 160,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_section_cta_icon_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-1',
                ]
            ]
        );

        $this->add_control(
            'eael_section_cta_icon_color_preset_2',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
	                'eael_cta_preset' => 'cta-preset-2',
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $sub_title = Helper::eael_wp_kses($settings['eael_cta_sub_title']);
        $icon_migrated = isset($settings['__fa4_migrated']['eael_cta_flex_grid_icon_new']);
        $icon_is_new = empty($settings['eael_cta_flex_grid_icon']);

        if ('cta-bg-color' == $settings['eael_cta_color_type']) {
            $cta_class = 'bg-lite';
        } else if ('cta-bg-img' == $settings['eael_cta_color_type']) {
            $cta_class = 'bg-img';
        } else if ('cta-bg-img-fixed' == $settings['eael_cta_color_type']) {
            $cta_class = 'bg-img bg-fixed';
        } else {
            $cta_class = '';
        }

        // Primary Button Effect
        $cta_btn_effect = '';
        if ('left-to-right' == $settings['eael_cta_btn_effect_type']) {
            $cta_btn_effect = 'effect-2';
        } elseif ('top-to-bottom' == $settings['eael_cta_btn_effect_type']) {
            $cta_btn_effect = 'effect-1';
        }
        // Secondary Button Effect
        $cta_secondary_btn_effect = '';
        if ('left-to-right' == $settings['eael_cta_secondary_btn_effect_type']) {
            $cta_secondary_btn_effect = 'effect-2';
        } elseif ('top-to-bottom' == $settings['eael_cta_secondary_btn_effect_type']) {
            $cta_secondary_btn_effect = 'effect-1';
        }

        // Heading Markup
        $headingMarkup = '';
        if(!empty($sub_title)){
            $headingMarkup .= '<h4 class="sub-title">' . $sub_title . '</h4>';
        }

        if (!empty($settings['eael_cta_title'])){
            $title_tag = Helper::eael_validate_html_tag( $settings['title_tag'] );
            $headingMarkup .='<' . $title_tag .' class="title">'. $settings['eael_cta_title'] . '</' . $title_tag . '>';
        }

        ob_start();
        echo wp_kses( $headingMarkup, Helper::eael_allowed_tags() );
        $headingMarkup = ob_get_clean();

        // content markup
        $contentMarkup = '';
        if ('content' == $settings['eael_cta_title_content_type']) {
            $contentMarkup .= wp_kses( $settings['eael_cta_content'], Helper::eael_allowed_tags() );
        }else if ('template' == $settings['eael_cta_title_content_type']){
            if (!empty($settings['eael_primary_templates'])) {
                $eael_template_id = $settings['eael_primary_templates'];
                $eael_frontend = new \Elementor\Frontend;
                $contentMarkup .= $eael_frontend->get_builder_content($eael_template_id, true);
            }
        }

        // button attributes
	    if ( ! empty( $settings['eael_cta_btn_link']['url'] ) ) {
		    $this->add_link_attributes( 'button', $settings['eael_cta_btn_link'] );
	    }
	    $this->add_render_attribute( 'button', 'class', "cta-button {$settings['eael_cta_btn_preset']} {$cta_btn_effect}" );

        if($settings['eael_cta_btn_preset'] === 'cta-btn-preset-2'){
            $btn_icon_wrap = '<span class="btn-icon">';
	        ob_start();
	        Icons_Manager::render_icon( $settings['eael_cta_btn_icon'], [ 'aria-hidden' => 'true' ] );
	        $btn_icon = ob_get_clean();
	        $btn_icon_wrap_end = '</span>';
        } else {
	        $btn_icon_wrap = '';
	        $btn_icon = '';
	        $btn_icon_wrap_end = '';
        }


	    // button markup
	    $buttonMarkup = '';
	    $buttonMarkup .= '<a ' . $this->get_render_attribute_string( 'button' ) . '>' . 
                         $btn_icon_wrap . wp_kses( $btn_icon, Helper::eael_allowed_icon_tags() )  .
                         $btn_icon_wrap_end .
                         esc_html( $settings['eael_cta_btn_text'] ) . '</a>';

        if ( $settings['eael_cta_secondary_btn_is_show'] === 'yes' ) {
		    // button attributes
		    if ( ! empty( $settings['eael_cta_secondary_btn_link']['url'] ) ) {
			    $this->add_link_attributes( 'secondary_button', $settings['eael_cta_secondary_btn_link'] );
		    }
		    $this->add_render_attribute( 'secondary_button', 'class', "cta-button cta-secondary-button {$cta_secondary_btn_effect}" );

		    // button markup
		    $buttonMarkup .= '<a ' . $this->get_render_attribute_string( 'secondary_button' ) . '>' . esc_html( $settings['eael_cta_secondary_btn_text'] ) . '</a>';
	    }
    ?>
	<?php if ('cta-basic' == $settings['eael_cta_type']): ?>
	<div class="eael-call-to-action cta-basic <?php echo esc_attr( $cta_class . ' ' . $settings['eael_cta_preset'] ); ?>">
        <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $headingMarkup . $contentMarkup . $buttonMarkup;
        ?>
	</div>
	<?php endif;?>
	<?php if ('cta-flex' == $settings['eael_cta_type']): ?>
	<div class="eael-call-to-action cta-flex <?php echo esc_attr( $cta_class . ' ' . $settings['eael_cta_preset'] ); ?>">
	    <div class="content">
            <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $headingMarkup . $contentMarkup;
            ?>
	    </div>
	    <div class="action">
	        <?php 
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            print $buttonMarkup; ?>
	    </div>
	</div>
	<?php endif;?>
	<?php if ('cta-icon-flex' == $settings['eael_cta_type']): ?>
	<div class="eael-call-to-action cta-icon-flex <?php echo esc_attr( $cta_class . ' ' . $settings['eael_cta_preset'] ); ?>">
	    <div class="icon">
			<?php if ($icon_is_new || $icon_migrated) {?>
				<?php if (isset($settings['eael_cta_flex_grid_icon_new']['value']['url'])): ?>
					<img src="<?php echo esc_url( $settings['eael_cta_flex_grid_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_cta_flex_grid_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
				<?php else:
                    Icons_Manager::render_icon( $settings['eael_cta_flex_grid_icon_new'] );

                endif;
            } else {?>
				<i class="<?php echo esc_attr($settings['eael_cta_flex_grid_icon']); ?>"></i>
			<?php }?>
	    </div>
	    <div class="content">
            <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $headingMarkup . $contentMarkup;
            ?>
	    </div>
	    <div class="action">
            <?php 
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                print $buttonMarkup; ?>
	    </div>
	</div>
	<?php endif;?>
	<?php
    }
}

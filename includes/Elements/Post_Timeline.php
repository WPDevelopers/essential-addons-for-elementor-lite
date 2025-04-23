<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Traits\Helper;

class Post_Timeline extends Widget_Base
{
    use Helper;

	protected $page_id;

    public function get_name()
    {
        return 'eael-post-timeline';
    }

    public function get_title()
    {
        return __('Post Timeline', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-post-timeline';
    }

    public function get_categories()
    {
        return ['essential-addons-for-elementor-lite'];
    }

    public function get_keywords()
    {
        return [
            'post',
            'posts',
            'timeline',
            'ea post timeline',
            'ea posts timeline',
            'blog posts',
            'content marketing',
            'blogger',
            'ea',
            'essential addons',
        ];
    }

	public function has_widget_inner_wrapper(): bool {
        return ! HelperClass::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/post-timeline/';
    }

    protected function register_controls()
    {

        /**
         * Query And Layout Controls!
         * @source includes/elementor-helper.php
         */
        do_action('eael/controls/query', $this);
        do_action('eael/controls/layout', $this);

        $this->start_controls_section(
            'section_post_timeline_links',
            [
                'label' => __('Links', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'timeline_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'timeline_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->end_controls_section();

        if (!apply_filters('eael/pro_enabled', false)) {
            HelperClass::go_premium($this);
        }

        $this->start_controls_section(
            'eael_section_post_timeline_style',
            [
                'label' => __('Timeline Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_timeline_display_overlay',
            [
                'label'        => __('Show Overlay', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .eael-timeline-post-image' => 'opacity: .6',
                ],
	            'condition'=> [
	            	'eael_dynamic_template_Layout!' => 'card',
	            ]
            ]
        );

        $this->add_control(
            'eael_timeline_overlay_color',
            [
                'label'       => __('Overlay Color', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::COLOR,
                'description' => __('Leave blank or Clear to use default gradient overlay', 'essential-addons-for-elementor-lite'),
                'default'     => 'linear-gradient(45deg, #3f3f46 0%, #05abe0 100%) repeat scroll 0 0 rgba(0, 0, 0, 0)',
                'selectors'   => [
                    '{{WRAPPER}} .eael-timeline-post-inner' => 'background: {{VALUE}}',
                ],
                'condition'   => [
                    'eael_timeline_display_overlay' => 'yes',
                    'eael_dynamic_template_Layout!' => 'card',
                ],
            ]
        );


        $this->add_control(
            'eael_timeline_bg_color',
            [
                'label'       => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::COLOR,
				'default'	  => '#3DB1C0',
                'selectors'   => [
                    '{{WRAPPER}} .eael-timeline-post-inner' => 'background: {{VALUE}}',
                ],
                'condition'   => [
                    'eael_dynamic_template_Layout' => 'card',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'eael_post_grid_spacing',
		    [
			    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'em'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
			    'condition'   => [
				    'eael_dynamic_template_Layout' => 'card',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_post_timeline_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			    ],
			    'condition'   => [
				    'eael_dynamic_template_Layout' => 'card',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'eael_post_timeline_box_shadow',
			    'selector' => '{{WRAPPER}} .eael-timeline-post-inner',
			    'condition'   => [
				    'eael_dynamic_template_Layout' => 'card',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_post_timeline_content_heading',
		    [
			    'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::HEADING,
			    'condition'   => [
				    'eael_dynamic_template_Layout' => 'card',
			    ],
			    'separator' => 'before',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_post_timeline_content_spacing',
		    [
			    'label' => esc_html__('Spacing', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'em'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
			    'condition'   => [
				    'eael_dynamic_template_Layout' => 'card',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_post_timeline_image_heading',
		    [
			    'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::HEADING,
			    'separator' => 'before',
			    'condition'   => [
				    'eael_dynamic_template_Layout!' => 'default',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_timeline_image_height',
		    [
			    'label' => esc_html__('Height', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'max' => 500,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post-image' => 'height: {{SIZE}}PX;',
			    ],
			    'condition'   => [
				    'eael_dynamic_template_Layout!' => 'default',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_img_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
			    'condition'   => [
				    'eael_dynamic_template_Layout!' => 'default',
			    ],
		    ]
	    );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_timeline_title_style',
            [
                'label'     => __('Title', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_timeline_title_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-title .eael-timeline-post-title-text' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_timeline_title_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-title .eael-timeline-post-title-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_timeline_title_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .eael-timeline-post-title .eael-timeline-post-title-text',
            ]
        );

        $this->add_control(
            'eael_timeline_excerpt_style',
            [
                'label'     => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_timeline_excerpt_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_timeline_excerpt_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-excerpt p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_timeline_excerpt_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .eael-timeline-post-excerpt p',
            ]
        );

        $this->end_controls_section();

		// Start Arrow Styling

		$this->start_controls_section(
		    'eael_section_arrow',
		    [
			    'label' => __('Arrow', 'essential-addons-for-elementor-lite'),
			    'tab'   => Controls_Manager::TAB_STYLE,
		    ]
	    );

        $this->add_control(
            'eael_timeline_border_color',
            [
                'label'     => __('Border & Arrow Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5eaed',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-inner'                                          => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post-inner::after'                                   => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post:nth-child(2n) .eael-timeline-post-inner::after' => 'border-right-color: {{VALUE}};',
                ],
                'condition'   => [
	                'eael_dynamic_template_Layout' => 'default',
                ],
            ]
        );

        $this->add_control(
            'eael_timeline_arrow_color',
            [
                'label'     => __('Arrow Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
				'default'	=> '#3DB1C0',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-inner'                                          => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post-inner::after'                                   => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}}',
//                    '{{WRAPPER}} .eael-timeline-post:nth-child(2n) .eael-timeline-post-inner::after' => 'border-right-color: {{VALUE}};',
                ],
                'condition'   => [
	                'eael_dynamic_template_Layout!' => 'default',
                ],
            ]
        );

		$this->add_responsive_control(
		    'eael_timeline_arrow_size',
		    [
			    'label' => esc_html__('Size', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'max' => 20,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-post-timeline.timeline-layout-card .eael-timeline-post-inner:after' => 'border-width: {{SIZE}}px; right: -{{SIZE}}px; left: -{{SIZE}}px',
				    '{{WRAPPER}} .eael-post-timeline.timeline-layout-card .eael-timeline-post:nth-child(2n) .eael-timeline-post-inner:after' => 'left: -{{SIZE}}px;',
			    ],

			    'condition'   => [
				    'eael_dynamic_template_Layout!' => 'default',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_arrow_alignment',
		    [
			    'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::CHOOSE,
			    'options'   => [
				    'top'       => [
					    'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'eicon-v-align-top',
				    ],
				    'middle'    => [
					    'title' => __( 'Middle', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'eicon-v-align-middle',
				    ],
				    'bottom'    => [
					    'title' => __( 'Bottom', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'eicon-v-align-bottom',
				    ],
			    ],
			    'default' => 'top',
			    'condition'   => [
				    'eael_dynamic_template_Layout!' => 'default',
			    ],
		    ]
	    );

        $this->end_controls_section();

        // Start Time Styling

	    $this->start_controls_section(
		    'eael_section_time',
		    [
			    'label' => __('Time', 'essential-addons-for-elementor-lite'),
			    'tab'   => Controls_Manager::TAB_STYLE,
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_date_background_color',
		    [
			    'label'     => __('Date Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => 'rgba(0, 0, 0, 0.7)',
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post time'         => 'background-color: {{VALUE}};',
				    '{{WRAPPER}} .eael-timeline-post time::before' => 'border-bottom-color: {{VALUE}};',
			    ],

		    ]
	    );

	    $this->add_control(
		    'eael_timeline_date_color',
		    [
			    'label'     => __('Date Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post time' => 'color: {{VALUE}};',
			    ],

		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_timeline_time_typography',
			    'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} time',
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_time_padding',
		    [
			    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_time_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} time' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

	    $this->start_controls_section(
		    'eael_section_line',
		    [
			    'label' => __('Line & Bullet', 'essential-addons-for-elementor-lite'),
			    'tab'   => Controls_Manager::TAB_STYLE,
		    ]
	    );

	    $this->add_control(
		    'eael_section_post_timeline_line_heading',
		    [
			    'label'      => __('Line', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'eael_section_post_timeline_line_size',
		    [
			    'label'      => __('Line Width', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 20,
					    'step' => 1,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-timeline-post:after' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_control(
		    'eael_section_post_timeline_line_position_from_right',
		    [
			    'label'      => __('Line Position From Right', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 20,
					    'step' => 1,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-timeline-post:after' => 'right: -{{SIZE}}{{UNIT}};',
			    ],
			    'condition'=> [
				    'eael_dynamic_template_Layout!' => 'card',
			    ]
		    ]
	    );

	    $this->add_control(
		    'eael_section_post_timeline_bullet_size',
		    [
			    'label'      => __('Bullet Size', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-timeline-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_control(
		    'eael_section_post_timeline_bullet_position_from_left',
		    [
			    'label'      => __('Left-sided Bullet Positon', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 50,
					    'step' => 1,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-timeline-bullet' => 'right: -{{SIZE}}{{UNIT}};',
			    ],
			    'condition'=> [
				    'eael_dynamic_template_Layout!' => 'card',
			    ]
		    ]
	    );
	    $this->add_control(
		    'eael_section_post_timeline_bullet_position_from_right',
		    [
			    'label'      => __('Right-sided Bullet Position', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 50,
					    'step' => 1,
				    ],
			    ],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-timeline-post:nth-child(2n) .eael-timeline-bullet' => 'left: -{{SIZE}}{{UNIT}};',
			    ],
			    'condition'=> [
				    'eael_dynamic_template_Layout!' => 'card',
			    ]
		    ]
	    );

	    $this->add_control(
		    'eael_timeline_bullet_color',
		    [
			    'label'     => __('Timeline Bullet Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#9fa9af',
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-bullet' => 'background-color: {{VALUE}};',
			    ],

		    ]
	    );

	    $this->add_control(
		    'eael_timeline_bullet_border_color',
		    [
			    'label'     => __('Timeline Bullet Border Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-bullet' => 'border-color: {{VALUE}};',
			    ],

		    ]
	    );

	    $this->add_control(
		    'eael_timeline_vertical_line_color',
		    [
			    'label'     => __('Timeline Vertical Line Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => 'rgba(83, 85, 86, .2)',
			    'selectors' => [
				    '{{WRAPPER}} .eael-timeline-post:after' => 'background-color: {{VALUE}};',
			    ],

		    ]
	    );

        $this->end_controls_section();

        do_action('eael/controls/load_more_button_style', $this);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = HelperClass::fix_old_query($settings);
        $args = HelperClass::get_query_args($settings);
        $args = HelperClass::get_dynamic_args($settings, $args);

        $settings ['expanison_indicator'] = $settings['excerpt_expanison_indicator'];

        $this->add_render_attribute(
            'eael_post_timeline_wrapper',
            [
                'id'    => "eael-post-timeline-{$this->get_id()}",
                'class' => ["eael-post-timeline", "timeline-layout-{$settings['eael_dynamic_template_Layout']}", "eael-post-timeline-arrow-{$settings['eael_timeline_arrow_alignment']}"],
            ]
        );

        $this->add_render_attribute(
            'eael_post_timeline',
            [
                'class' => ['eael-post-timeline', 'eael-post-appender', "eael-post-appender-{$this->get_id()}"],
            ]
        );

        echo '<div '; $this->print_render_attribute_string('eael_post_timeline_wrapper'); echo '>
            <div '; $this->print_render_attribute_string('eael_post_timeline'); echo '>';

                $template = $this->get_template($this->get_settings('eael_dynamic_template_Layout'));
                $settings['loadable_file_name'] = $this->get_filename_only($template);
	            $dir_name = $this->get_temp_dir_name($settings['loadable_file_name']);
	            $found_posts = 0;

                if(file_exists($template)){
                    $query = new \WP_Query($args);
                    if ($query->have_posts()) {
	                    $found_posts      = $query->found_posts;
	                    $ppp              = empty( $args['posts_per_page'] ) ? get_option( 'posts_per_page' ) : $args['posts_per_page'];
	                    $max_page         = ceil( $found_posts / absint( $ppp ) );
	                    $args['max_page'] = $max_page;
                        while ($query->have_posts()) {
                            $query->the_post();
                            include($template);
                        }
                    } else {
                        echo '<p class="no-posts-found">' . esc_html__( 'No posts found!', 'essential-addons-for-elementor-lite' ) . '</p>';
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="no-posts-found">' . esc_html__( 'No layout found!', 'essential-addons-for-elementor-lite' ) . '</p>';
                }
		    echo '</div>
		</div>';

	    if ( $found_posts > $args['posts_per_page'] ) {
		    $this->print_load_more_button( $settings, $args, $dir_name );
	    }
    }
}

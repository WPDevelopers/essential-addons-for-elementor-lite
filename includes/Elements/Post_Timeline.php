<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;

class Post_Timeline extends Widget_Base
{
	use \Essential_Addons_Elementor\Traits\Helper;
	use \Essential_Addons_Elementor\Template\Content\Post_Timeline;

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
        return ['essential-addons-elementor'];
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
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/post-timeline/';
    }

    protected function _register_controls()
    {

        /**
         * Query And Layout Controls!
         * @source includes/elementor-helper.php
         */
        $this->eael_query_controls();
        $this->eael_layout_controls();

        if (!apply_filters('eael/pro_enabled', false)) {
            $this->eael_go_premium();
        }

        $this->start_controls_section(
            'eael_section_post_timeline_style',
            [
                'label' => __('Timeline Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'eael_timeline_display_overlay',
			[
				'label' => __( 'Show Overlay', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-image' => 'opacity: .6',
                ],
			]
		);

        $this->add_control(
            'eael_timeline_overlay_color',
            [
                'label' => __('Overlay Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'description' => __('Leave blank or Clear to use default gradient overlay', 'essential-addons-for-elementor-lite'),
                'default' => 'linear-gradient(45deg, #3f3f46 0%, #05abe0 100%) repeat scroll 0 0 rgba(0, 0, 0, 0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-inner' => 'background: {{VALUE}}',
                ],
                'condition' => [
                    'eael_timeline_display_overlay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_timeline_bullet_color',
            [
                'label' => __('Timeline Bullet Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9fa9af',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-bullet' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_timeline_bullet_border_color',
            [
                'label' => __('Timeline Bullet Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-bullet' => 'border-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_timeline_vertical_line_color',
            [
                'label' => __('Timeline Vertical Line Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(83, 85, 86, .2)',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post:after' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_timeline_border_color',
            [
                'label' => __('Border & Arrow Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5eaed',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-inner' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post-inner::after' => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post:nth-child(2n) .eael-timeline-post-inner::after' => 'border-right-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_timeline_date_background_color',
            [
                'label' => __('Date Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.7)',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post time' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-timeline-post time::before' => 'border-bottom-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_timeline_date_color',
            [
                'label' => __('Date Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post time' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_timeline_title_style',
            [
                'label' => __('Title Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_timeline_title_color',
            [
                'label' => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-title h2' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_timeline_title_alignment',
            [
                'label' => __('Title Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-title h2' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_timeline_title_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-timeline-post-title h2',
            ]
        );

        $this->add_control(
            'eael_timeline_excerpt_style',
            [
                'label' => __('Excerpt Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_timeline_excerpt_color',
            [
                'label' => __('Excerpt Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-timeline-post-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_timeline_excerpt_alignment',
            [
                'label' => __('Excerpt Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-justify',
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
                'name' => 'eael_timeline_excerpt_typography',
                'label' => __('excerpt Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .eael-timeline-post-excerpt p',
            ]
        );

        $this->end_controls_section();

        $this->eael_load_more_button_style();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = $this->fix_old_query($settings);
        $args = $this->eael_get_query_args($settings);
        $settings = [
            'eael_show_image' => $settings['eael_show_image'],
            'image_size' => $settings['image_size'],
            'eael_show_title' => $settings['eael_show_title'],
            'eael_show_excerpt' => $settings['eael_show_excerpt'],
            'eael_excerpt_length' => $settings['eael_excerpt_length'],
            'show_load_more' => $settings['show_load_more'],
            'show_load_more_text' => $settings['show_load_more_text'],
            'expanison_indicator'   => $settings['excerpt_expanison_indicator']
        ];

        $this->add_render_attribute(
            'eael_post_timeline_wrapper',
            [
                'id' => "eael-post-timeline-{$this->get_id()}",
                'class' => 'eael-post-timeline',
            ]
        );

        $this->add_render_attribute(
            'eael_post_timeline',
            [
                'class' => ['eael-post-timeline', 'eael-post-appender', "eael-post-appender-{$this->get_id()}"],
            ]
        );

        echo '<div ' . $this->get_render_attribute_string('eael_post_timeline_wrapper') . '>
		    <div ' . $this->get_render_attribute_string('eael_post_timeline') . '>
				' . self::render_template_($args, $settings) . '
		    </div>
		</div>';

        if ('yes' == $settings['show_load_more']) {
            if ($args['posts_per_page'] != '-1') {
                echo '<div class="eael-load-more-button-wrap">
					<button class="eael-load-more-button" id="eael-load-more-btn-' . $this->get_id() . '" data-widget="' . $this->get_id() . '" data-class="' . get_class($this) . '" data-args="' . http_build_query($args) . '" data-settings="' . http_build_query($settings) . '" data-page="1">
						<div class="eael-btn-loader button__loader"></div>
						<span>' . esc_html__($settings['show_load_more_text'], 'essential-addons-for-elementor-lite') . '</span>
					</button>
				</div>';
            }
        }
    }
}

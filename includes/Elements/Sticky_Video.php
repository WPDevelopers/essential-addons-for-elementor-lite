<?php

namespace Essential_Addons_Elementor\Elements;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Widget_Base;

class Sticky_Video extends Widget_Base
{
    

    protected $eaelRElem = 1;

    public function get_name()
    {
        return 'eael-sticky-video';
    }

    public function get_title()
    {
        return esc_html__('Sticky Video', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-sticky-video';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'video',
            'sticky',
            'ea sticky video',
            'ea video player',
            'youtube',
            'vimeo',
            'mp4',
            'mpg',
            'ogg',
            'webm',
            'mov',
            'avi',
            'scrollable video',
            'sticky control',
            'video player',
            'youtube content',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/sticky-video/';
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

    protected function register_controls()
    {
        /**
         * General
         */
        $this->start_controls_section(
            'eaelsv_sticky_option_section',
            [
                'label' => __('Sticky Options', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eaelsv_is_sticky',
            [
                'label' => __('Sticky', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'label_on' => __('On', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Off', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} div.eaelsv-sticky-player' => 'display: block',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_sticky_position',
            [
                'label' => __('Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top-left' => __('Top Left', 'essential-addons-for-elementor-lite'),
                    'top-right' => __('Top Right', 'essential-addons-for-elementor-lite'),
                    'bottom-left' => __('Bottom Left', 'essential-addons-for-elementor-lite'),
                    'bottom-right' => __('Bottom Right', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'bottom-right',
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_video_settings',
            [
                'label' => esc_html__('Video', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_video_source',
            [
                'label' => __('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __('YouTube', 'essential-addons-for-elementor-lite'),
                    'vimeo' => __('Vimeo', 'essential-addons-for-elementor-lite'),
                    'self_hosted' => __('Self Hosted', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eaelsv_link_youtube',
            [
                'label' => __('Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'placeholder' => __('Enter your URL (YouTube)', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'default' => 'https://www.youtube.com/watch?v=uuyXfUDqRZM',
                'condition' => [
                    'eael_video_source' => 'youtube',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eaelsv_link_vimeo',
            [
                'label' => __('Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => __('Enter your URL (Vimeo)', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'default' => 'https://vimeo.com/235215203',
                'condition' => [
                    'eael_video_source' => 'vimeo',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eaelsv_link_dailymotion',
            [
                'label' => __('Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => __('Enter your URL (Dailymotion)', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'condition' => [
                    'eael_video_source' => 'dailymotion',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eaelsv_link_external',
            [
                'label' => __('External URL', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_hosted_url',
            [
                'label' => __('Choose File', 'elementor'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                    'eaelsv_link_external' => '',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'eaelsv_external_url',
            [
                'label' => __('Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => __('Enter your URL', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'show_label' => false,
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                    'eaelsv_link_external' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_video_self_hosted_link',
            [
                'label' => __('Choose File', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                    'eael_video_source_external' => '',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->add_control(
            'eaelsv_start_time',
            [
                'label' => __('Start Time', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'description' => 'Specify a start time (in seconds)',
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_end_time',
            [
                'label' => __('End Time', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'description' => 'Specify an end time (in seconds)',
                'condition' => [
                    'eael_video_source' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
            'eael_video_video_options',
            [
                'label' => __('Video Options', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eaelsv_autopaly',
            [
                'label' => __('Autoplay', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'eaelsv_autopaly_description',
            [
                'raw' => __('Autoplay requires mute volume.', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'eaelsv_autopaly' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_mute',
            [
                'label' => __('Mute', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'eaelsv_autopaly!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_loop',
            [
                'label' => __('Loop', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'eaelsv_sh_show_bar',
            [
                'label' => __('Show Bar', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .plyr__controls' => 'display: flex!important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_video_image_overlay_section',
            [
                'label' => __('Image Overlay', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eaelsv_overlay_options',
            [
                'label' => __('Image Overlay', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
	                '' => __('Default', 'essential-addons-for-elementor-lite'),
	                'yes' => __('Custom', 'essential-addons-for-elementor-lite'),
	                'transparent' => __('Transparent', 'essential-addons-for-elementor-lite'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'eaelsv_overlay_image',
            [
                'label' => __('Choose Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition' => [
                    'eaelsv_overlay_options' => 'yes',
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'default' => 'full',
                'name' => 'eaelsv_overlay_image_size',
                'condition' => [
                    'eaelsv_overlay_options' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_overlay_play_icon',
            [
                'label' => __('Play Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_block' => false,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eaelsv_overlay_options' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_icon_new',
            [
                'label' => esc_html__('Choose Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eaelsv_icon',
                'condition' => [
                    'eaelsv_overlay_options' => 'yes',
                    'eaelsv_overlay_play_icon' => 'yes',
                ],
            ]
        );

        $this->add_control( 'eaelsv_icon_new_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Play icon appears on top of overlay image.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            'condition' => [
                'eaelsv_overlay_options' => 'yes',
            ],
		] );

        $this->end_controls_section();

        /**
         * Style Tab Started
         */
        $this->start_controls_section(
            'eaelsv_sticky_video_interface',
            [
                'label' => __('Sticky Video Interface', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'eaelsv_sticky_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 500,
                'step' => 1,
                'default' => 300,
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-player2.out' => 'width: {{VALUE}}px!important;',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_sticky_height',
            [
                'label' => __('Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 55,
                'max' => 280,
                'step' => 1,
                'default' => 169,
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-player2.out' => 'height: {{VALUE}}px!important;',
                ],
            ]
        );



        $this->add_control(
            'eaelsv_scroll_height_display_sticky',
            [
                'label' => __('Scroll Height To Display Sticky (%)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_sticky_close_button_color',
            [
                'label' => __('Close Button Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'eaelsv_is_sticky' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eaelsv-sticky-player-close' => 'color: {{VALUE}}!important',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eaelsv_sh_player_section',
            [
                'label' => __('Player', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_video_width',
            [
                'label' => esc_html__('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_sh_video_border_type',
            [
                'label' => __('Border Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'essential-addons-for-elementor-lite'),
                    'solid' => __('Solid', 'essential-addons-for-elementor-lite'),
                    'double' => __('Double', 'essential-addons-for-elementor-lite'),
                    'dotted' => __('Dotted', 'essential-addons-for-elementor-lite'),
                    'dashed' => __('Dashed', 'essential-addons-for-elementor-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-wrapper' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_video_border_width',
            [
                'label' => esc_html__('Border Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eaelsv_sh_video_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_video_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-sticky-video-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eaelsv-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-sticky-video-player2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eaelsv_sh_player_interface_section',
            [
                'label' => __('Interface', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eaelsv_sh_video_interface_color',
            [
                'label' => esc_html__('Interface Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ADD8E6',
                'selectors' => [
                    '{{WRAPPER}} .plyr__control.plyr__tab-focus' => 'box-shadow: 0 0 0 5px {{VALUE}}!important',
                    '{{WRAPPER}} .plyr__control--overlaid' => 'background: {{VALUE}}!important',
                    '{{WRAPPER}} .plyr--video .plyr__control.plyr__tab-focus' => 'background: {{VALUE}}!important',
                    '{{WRAPPER}} .plyr__control--overlaid' => 'background: {{VALUE}}!important',
                    '{{WRAPPER}} .plyr--video .plyr__control:hover' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_play_button_size',
            [
                'label' => __('Play Button Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 15,
                        'max' => 55,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .plyr__control--overlaid' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eaelsv_sh_player_bar_section',
            [
                'label' => __('Bar', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_player_bar_padding',
            [
                'label' => __('Bar Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .plyr--video .plyr__controls' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eaelsv_sh_bar_margin',
            [
                'label' => esc_html__('Bar Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .plyr--video .plyr__controls' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $iconNew = $settings['eaelsv_icon_new'];
        $sticky = $settings['eaelsv_is_sticky'];
        $autoplay = ($settings['eaelsv_autopaly'] == 'yes') ? $settings['eaelsv_autopaly'] : 'no';
        $eaelsvPlayer = '';
	    $eaelsv_overlay_visibility = $settings['eaelsv_overlay_options'];

        if ('youtube' == $settings['eael_video_source']) {
            $eaelsvPlayer = $this->eaelsv_load_player_youtube();
        }
        if ('vimeo' == $settings['eael_video_source']) {
            $eaelsvPlayer = $this->eaelsv_load_player_vimeo();
        }
        if ('self_hosted' == $settings['eael_video_source']) {
            $eaelsvPlayer = $this->eaelsv_load_player_self_hosted();
        }

	    echo '<div class="eael-sticky-video-wrapper eaelsv-overlay-visibility-' . esc_attr( $eaelsv_overlay_visibility ) . '">';
        if ('yes' === $settings['eaelsv_overlay_options']) {
            // $autoplay = 'yes';
            $icon = '';
            if ('yes' === $settings['eaelsv_overlay_play_icon']) {
                if ($iconNew['value'] != '') {
                    if (is_array($iconNew['value'])) {
                        $icon = '<img src="' . esc_url( $iconNew['value']['url'] ) .  '" width="100">';
                    } else {
                        $icon = '<i class="' . esc_attr( $iconNew['value'] ) . '"></i>';
                    }
                } else {
                    $icon = '<i class="eicon-play"></i>';
                }
            }

            $overlay_class = 'eaelsv-overlay';
            if( 'yes' === $settings['eaelsv_overlay_options'] && empty( $settings['eaelsv_overlay_image']['url'] ) ){
                $icon = '';
                $overlay_class = 'eaelsv-overlay-ignore';
            }

            $this->add_render_attribute(
                'esvp_overlay_wrapper',
                [
                    'class' => esc_attr( $overlay_class ),
                    'style' => "background-image:url('" . $settings['eaelsv_overlay_image']['url'] . "');",
                ]
            );

            echo '<div ' . $this->get_render_attribute_string('esvp_overlay_wrapper') . '>
					<div class="eaelsv-overlay-icon">' . $icon . '</div>
				</div>';
        }

        $this->add_render_attribute(
            'esvp_overlay_wrapper2',
            [
                'class' => 'eael-sticky-video-player2',
                'data-sticky' => $sticky,
                'data-position' => $settings['eaelsv_sticky_position'],
                'data-sheight' => $settings['eaelsv_sticky_height'],
                'data-swidth' => $settings['eaelsv_sticky_width'],
                'data-scroll_height' => !empty($settings['eaelsv_scroll_height_display_sticky']['size']) ? $settings['eaelsv_scroll_height_display_sticky']['size'] : '',
                'data-autoplay' => $autoplay,
                'data-overlay' => ($settings['eaelsv_overlay_options'] == 'yes') ? $settings['eaelsv_overlay_options'] : 'no',
            ]
        );

        echo '<div ' . $this->get_render_attribute_string('esvp_overlay_wrapper2') . '>
				' . $eaelsvPlayer . '
				<span class="eaelsv-sticky-player-close"><i class="fas fa-times-circle"></i></span>
			</div>
		</div>';
    }

    protected function eaelsv_load_player_youtube()
    {
	    $settings = $this->get_settings_for_display();
	    $id       = $this->eaelsv_get_url_id();
	    $autoplay = $settings['eaelsv_autopaly'];
	    $mute     = $autoplay == 'yes' ? 'yes' : $settings['eaelsv_mute'];
	    $loop     = $settings['eaelsv_loop'];

	    $am = '"storage": {"enabled": false, "key": "plyr"}';
	    $am .= ( $autoplay == 'yes' ? ', "autoplay":1' : ', "autoplay":0' );
	    $am .= ( $mute == 'yes' ? ', "muted":1, "volume":0' : ', "muted":0' );

        if ('yes' == $loop) {
            $lp = '"loop": {"active": true}';
        } else {
            $lp = '"loop": {"active": false}';
        }

        return '<div
			id="eaelsv-player-' . $this->get_id() . '"
			data-plyr-provider="youtube"
			data-plyr-embed-id="' . esc_attr($id) . '"
			data-plyr-config="{' . esc_attr($am) . ', ' . esc_attr($lp) . '}"
		></div>';
    }

    protected function eaelsv_load_player_vimeo()
    {
	    $settings = $this->get_settings_for_display();
	    $id       = $this->eaelsv_get_url_id();
	    $autoplay = $settings['eaelsv_autopaly'];
	    $mute     = $autoplay == 'yes' ? 'yes' : $settings['eaelsv_mute'];
	    $loop     = $settings['eaelsv_loop'];

	    $am = '"storage": {"enabled": false, "key": "plyr"}';
	    $am .= ( $autoplay == 'yes' ? ', "autoplay":1' : ', "autoplay":0' );
	    $am .= ( $mute == 'yes' ? ', "muted":1, "volume":0' : ', "muted":0' );

        if ('yes' == $loop) {
            $lp = '"loop": {"active": true}';
        } else {
            $lp = '"loop": {"active": false}';
        }

        return '<div
			id="eaelsv-player-' . $this->get_id() . '"
			data-plyr-provider="vimeo"
			data-plyr-embed-id="' . esc_attr($id) . '"
			data-plyr-config="{' . esc_attr($am) . ', ' . esc_attr($lp) . '}"
		></div>';
    }

    protected function eaelsv_load_player_self_hosted()
    {
        $settings = $this->get_settings_for_display();
        $video = ($settings['eaelsv_external_url'] != '') ? $settings['eaelsv_external_url'] : $settings['eaelsv_hosted_url']['url'];
        $controlBars = $settings['eaelsv_sh_show_bar'];
        $autoplay = $settings['eaelsv_autopaly'];
        $mute = $settings['eaelsv_mute'];
        $loop = $settings['eaelsv_loop'];
        $interfaceColor = $settings['eaelsv_sh_video_interface_color'];
        $startTime = $settings['eaelsv_start_time'];
        $endTime = $settings['eaelsv_end_time'];

        $am = '';
        $am .= ($autoplay == 'yes' ? '"autoplay":1' : '"autoplay":0');
        $am .= ($mute == 'yes' ? ', "muted":1' : ', "muted":0');

        if ('yes' == $loop) {
            $lp = '"loop": {"active": true}';
        } else {
            $lp = '"loop": {"active": false}';
        }

        return '<video class="eaelsv-player" id="eaelsv-player-' . $this->get_id() . '" playsinline controls data-plyr-config="{' . esc_attr($am) . ', ' . esc_attr($lp) . '}">
			<source src="' . esc_attr($video) . '#t=' . esc_attr($startTime) . ',' . esc_attr($endTime) . '" type="video/mp4" />
		</video>';
    }

    protected function eaelsv_get_url_id()
    {
        $settings = $this->get_settings_for_display();

	    if ( 'youtube' === $settings['eael_video_source'] ) {
		    $url        = $settings['eaelsv_link_youtube'];
		    $link       = explode( '=', parse_url( $url, PHP_URL_QUERY ) );
		    $short_link = explode( '/', $url );
		    $id         = isset( $link[1] ) ? $link[1] : ( isset( $short_link[3] ) ? $short_link[3] : '' );
	    }
        if ('vimeo' === $settings['eael_video_source']) {
            $url = $settings['eaelsv_link_vimeo'];
            $link = explode('/', $url);
	        $id = isset( $link[3] ) ? $link[3] : '';
        }
        if ('self_hosted' === $settings['eael_video_source']) {
            $externalUrl = $settings['eaelsv_link_external'];
            if ('yes' == $externalUrl) {
                $id = $settings['eaelsv_external_url'];
            } else {
                $id = $settings['eaelsv_hosted_url']['url'];
            }
        }

        return $id;
    }
}

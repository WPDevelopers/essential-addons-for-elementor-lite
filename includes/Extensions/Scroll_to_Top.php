<?php

namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Essential_Addons_Elementor\Classes\Helper;

class Scroll_to_Top
{

    public function __construct()
    {
        add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
    }

    public function register_controls($element)
    {
        if (Helper::prevent_extension_loading(get_the_ID())) {
            return;
        }

        $global_settings = get_option('eael_global_settings');
        
        $element->start_controls_section(
            'eael_ext_scroll_to_top_section',
            [
                'label' => __('<i class="eaicon-logo"></i> Scroll to Top', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top',
            [
                'label' => __('Enable Scroll to Top', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_has_global',
            [
                'label' => __('Enabled Globally?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HIDDEN,
                'default' => (isset($global_settings['eael_ext_scroll_to_top']['enabled']) ? $global_settings['eael_ext_scroll_to_top']['enabled'] : false),
            ]
        );

        if (isset($global_settings['eael_ext_scroll_to_top']['enabled']) && ($global_settings['eael_ext_scroll_to_top']['enabled'] == true) && get_the_ID() != $global_settings['eael_ext_scroll_to_top']['post_id'] && get_post_status($global_settings['eael_ext_scroll_to_top']['post_id']) == 'publish') {
            $element->add_control(
                'eael_ext_scroll_to_top_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('You can modify the Global Scroll to Top by <strong><a href="' . get_bloginfo('url') . '/wp-admin/post.php?post=' . $global_settings['eael_ext_scroll_to_top']['post_id'] . '&action=elementor">Clicking Here</a></strong>', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                    'separator' => 'before',
                    'condition' => [
                        'eael_ext_scroll_to_top' => 'yes',
                    ],
                ]
            );
        } else {
            $element->add_control(
                'eael_ext_scroll_to_top_global',
                [
                    'label' => __('Enable Scroll to Top Globally', 'essential-addons-for-elementor-lite'),
                    'description' => __('Enabling this option will effect on entire site.', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'condition' => [
                        'eael_ext_scroll_to_top' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_scroll_to_top_global_display_condition',
                [
                    'label' => __('Display On', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'all',
                    'options' => [
                        'posts' => __('All Posts', 'essential-addons-for-elementor-lite'),
                        'pages' => __('All Pages', 'essential-addons-for-elementor-lite'),
                        'all' => __('All Posts & Pages', 'essential-addons-for-elementor-lite'),
                    ],
                    'condition' => [
                        'eael_ext_scroll_to_top' => 'yes',
                        'eael_ext_scroll_to_top_global' => 'yes',
                    ],
                    'separator' => 'before',
                ]
            );
        }

        $element->add_control(
            'eael_ext_scroll_to_top_position_text',
            [
                'label' => esc_html__('Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom-right',
                'label_block' => false,
                'options' => [
                    'bottom-left' => esc_html__('Bottom Left', 'essential-addons-for-elementor-lite'),
                    'bottom-right' => esc_html__('Bottom Right', 'essential-addons-for-elementor-lite'),
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_position_bottom',
            [
                'label' => __('Bottom', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_position_left',
            [
                'label' => __('Left', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                    'eael_ext_scroll_to_top_position_text' => 'bottom-left',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_position_right',
            [
                'label' => __('Right', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                    'eael_ext_scroll_to_top_position_text' => 'bottom-right',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_height',
            [
                'label' => __('Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_z_index',
            [
                'label' => __('Z Index', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 9999,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 9999,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'z-index: {{SIZE}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );


        $element->add_control(
            'eael_ext_scroll_to_top_button_opacity',
            [
                'label' => __('Opacity', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.7,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_icon_image',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-up',
                    'library' => 'fa-solid',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_icon_color',
            [
                'label' => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i' => 'color: {{VALUE}}',
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg' => 'fill: {{VALUE}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_to_top_button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'eael_ext_scroll_to_top' => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }
}

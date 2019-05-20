<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Background as Group_Control_Background;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class Progress_Bar extends Widget_Base
{

    public function get_name()
    {
        return 'eael-progress-bar';
    }

    public function get_title()
    {
        return esc_html__('EA Progress Bar', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'fa fa-tasks';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Content Tab: Layout
         */
        $this->start_controls_section(
            'progress_bar_section_layout',
            [
                'label' => __('Layout', 'essential-addons-elementor'),
            ]
        );

        // Progressbar Layout Options
        $options = apply_filters(
            'add_eael_progressbar_layout',
            [
                'layouts'   => [
                    'line'             => __('Line', 'essential-addons-elementor'),
                    'line_rainbow'     => __('Line Rainbow (Pro)', 'essential-addons-elementor'),
                    'circle'           => __('Circle', 'essential-addons-elementor'),
                    'circle_fill'      => __('Circle Fill (Pro)', 'essential-addons-elementor'),
                    'half_circle'      => __('Half Circle', 'essential-addons-elementor'),
                    'half_circle_fill' => __('Half Circle Fill (Pro)', 'essential-addons-elementor'),
                    'box'              => __('Box (Pro)', 'essential-addons-elementor'),
                ],
                'conditions'    => [
                    'line_rainbow', 'circle_fill', 'half_circle_fill', 'box'
                ]
            ]
        );

        $this->add_control(
            'progress_bar_layout',
            [
                'label'   => __('Layout', 'essential-addons-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => $options['layouts'],
                'default' => 'line',
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_pro_alert',
            [
                'label' => esc_html__('Only Available in Pro Version!', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'progress_bar_layout' => $options['conditions']
                ],
            ]
        );

        $this->add_control(
            'progress_bar_title',
            [
                'label' => __('Title', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Progress Bar', 'essential-addons-elementor'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_title_html_tag',
            [
                'label' => __('Title HTML Tag', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => __('H1', 'essential-addons-elementor'),
                    'h2'   => __('H2', 'essential-addons-elementor'),
                    'h3'   => __('H3', 'essential-addons-elementor'),
                    'h4'   => __('H4', 'essential-addons-elementor'),
                    'h5'   => __('H5', 'essential-addons-elementor'),
                    'h6'   => __('H6', 'essential-addons-elementor'),
                    'div'  => __('div', 'essential-addons-elementor'),
                    'span' => __('span', 'essential-addons-elementor'),
                    'p'    => __('p', 'essential-addons-elementor'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'progress_bar_value',
            [
                'label'      => __('Counter Value', 'essential-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range'      => [
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_show_count',
            [
                'label'        => esc_html__('Display Count', 'essential-addons-elementor'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'progress_bar_animation_duration',
            [
                'label'      => __('Animation Duration', 'essential-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1000,
                        'max' => 10000,
                        'step' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1500,
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_prefix_label',
            [
                'label'     => __('Prefix Label', 'essential-addons-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Prefix', 'essential-addons-elementor'),
                'condition' => [
                    'progress_bar_layout' => 'half_circle',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_postfix_label',
            [
                'label'     => __('Postfix Label', 'essential-addons-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Postfix', 'essential-addons-elementor'),
                'condition' => [
                    'progress_bar_layout' => 'half_circle',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        if(!apply_filters('eael/pro_enabled', false)) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __( 'Go Premium for More Features', 'essential-addons-elementor' )
                ]
            );
        
            $this->add_control(
                'eael_control_get_pro',
                [
                    'label'   => __( 'Unlock more possibilities', 'essential-addons-elementor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __( '', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
                ]
            );
            
            $this->end_controls_section();
        }

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: General(Line)
         */
        $style_condition = apply_filters('eael_progressbar_general_style_condition', ['line', 'line_rainbow']);

        $this->start_controls_section(
            'progress_bar_section_style_general_line',
            [
                'label'     => __('General', 'essential-addons-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => $style_condition,
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_alignment',
            [
                'label' => __('Alignment', 'essential-addons-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Background
         */
        $this->start_controls_section(
            'progress_bar_section_style_bg',
            [
                'label'     => __('Background', 'essential-addons-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => $style_condition, // ['line', 'line_rainbow'] ( Pro Only )
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_width',
            [
                'label' => __('Width', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-line-container' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_height',
            [
                'label' => __('Height', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-line' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_bg_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-line' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Fill
         */
        $this->start_controls_section(
            'progress_bar_section_style_fill',
            [
                'label' => __('Fill', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => $style_condition, // will here ['line', 'line_rainbow'] ( Pro Only )
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_fill_height',
            [
                'label' => __('Height', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-line-fill' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        if(apply_filters('eael/pro_enabled', false)){
            $line_fill_color_condition = [
                'progress_bar_layout' => 'line',
            ];
        }else {
            $line_fill_color_condition = [];
        }
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'    => 'progress_bar_line_fill_color',
                'label'   => __('Color', 'essential-addons-elementor'),
                'types'   => ['classic', 'gradient'],
                'exclude' => [
                    'image',
                ],
                'condition' => $line_fill_color_condition,
                'selector' => '{{WRAPPER}} .eael-progressbar-line-fill',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_line_fill_stripe',
            [
                'label'        => __('Show Stripe', 'essential-addons-elementor'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition'     => $line_fill_color_condition,
                'default'      => 'no',
                'separator'    => 'before',
            ]
        );

        $fill_stripe_animate_condition = apply_filters( 'eael_progressbar_line_fill_stripe_condition', ['progress_bar_line_fill_stripe' => 'yes']);

        $this->add_control(
            'progress_bar_line_fill_stripe_animate',
            [
                'label' => __('Stripe Animation', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal'  => __('Left To Right', 'essential-addons-elementor'),
                    'reverse' => __('Right To Left', 'essential-addons-elementor'),
                    'none'    => __('Disabled', 'essential-addons-elementor'),
                ],
                'default'   => 'none',
                'condition' => $fill_stripe_animate_condition
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: General(Circle)
         */
        $circle_general_condition = apply_filters('eael_circle_style_general_condition', ['circle', 'half_circle']);

        $this->start_controls_section(
            'progress_bar_section_style_general_circle',
            [
                'label'     => __('General', 'essential-addons-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => $circle_general_condition,
                ],
            ]
        );

        $this->add_control(
            'progress_bar_circle_alignment',
            [
                'label' => __('Alignment', 'essential-addons-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'progress_bar_circle_size',
            [
                'label'      => __('Size', 'essential-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle'            => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-progressbar-half-circle'       => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}} / 2 * 1{{UNIT}});',
                    '{{WRAPPER}} .eael-progressbar-half-circle-after' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-progressbar-circle-shadow'     => 'width: calc({{SIZE}}{{UNIT}} + 20px); height: calc({{SIZE}}{{UNIT}} + 20px);',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_circle_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-inner' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_circle_stroke_width',
            [
                'label'      => __('Stroke Width', 'essential-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-inner' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-progressbar-circle-half' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_circle_stroke_color',
            [
                'label'     => __('Stroke Color', 'essential-addons-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#eee',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-inner' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        if(apply_filters('eael/pro_enabled', false)) {
            $circle_fill_color_condition = [
                '{{WRAPPER}} .eael-progressbar-circle-half' => 'border-color: {{VALUE}}',
                '{{WRAPPER}} .eael-progressbar-circle-fill .eael-progressbar-circle-half' => 'background-color: {{VALUE}}',
                '{{WRAPPER}} .eael-progressbar-half-circle-fill .eael-progressbar-circle-half' => 'background-color: {{VALUE}}',
            ];
        }else {
            $circle_fill_color_condition = [
                '{{WRAPPER}} .eael-progressbar-circle-half' => 'border-color: {{VALUE}}',
            ];
        }

        $this->add_control(
            'progress_bar_circle_fill_color',
            [
                'label'     => __('Fill Color', 'essential-addons-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => $circle_fill_color_condition,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'progress_bar_circle_box_shadow',
                'label'     => __('Box Shadow', 'essential-addons-elementor'),
                'selector'  => '{{WRAPPER}} .eael-progressbar-circle-shadow',
                'condition' => [
                    'progress_bar_layout' => 'circle',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Import progress bar style controlls
        do_action( 'add_progress_bar_control', $this );

        /**
         * Style Tab: Typography
         */
        $this->start_controls_section(
            'progress_bar_section_style_typography',
            [
                'label' => __('Typography', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'progress_bar_title_typography',
                'label'    => __('Title', 'essential-addons-elementor'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-progressbar-title',
            ]
        );

        $this->add_control(
            'progress_bar_title_color',
            [
                'label' => __('Title Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-title' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_bar_count_typography',
                'label' => __('Counter', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-progressbar-count-wrap',
            ]
        );

        $this->add_control(
            'progress_bar_count_color',
            [
                'label' => __('Counter Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-count-wrap' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_bar_after_typography',
                'label' => __('Prefix/Postfix', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-progressbar-half-circle-after span',
                'condition' => [
                    'progress_bar_layout' => 'half_circle',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_after_color',
            [
                'label' => __('Prefix/Postfix Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-half-circle-after' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'progress_bar_layout' => 'half_circle',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings       = $this->get_settings_for_display();
        $wrap_classes   = ['eael-progressbar'];
        $circle_wrapper = [];

        if(!apply_filters('eael/pro_enabled', false)) {
            if(in_array($settings['progress_bar_layout'], ['line', 'line_rainbow', 'circle_fill', 'half_circle_fill', 'box'])) {
                $settings['progress_bar_layout'] = 'line';
            }
        }

        if ($settings['progress_bar_layout'] == 'line' || $settings['progress_bar_layout'] == 'line_rainbow') {

            $wrap_classes[] = 'eael-progressbar-line';
            $wrap_classes = apply_filters('eael_progressbar_rainbow_wrap_class', $wrap_classes, $settings);

            if ($settings['progress_bar_line_fill_stripe'] == 'yes') {
                $wrap_classes[] = 'eael-progressbar-line-stripe';
            }

            if ($settings['progress_bar_line_fill_stripe_animate'] == 'normal') {
                $wrap_classes[] = 'eael-progressbar-line-animate';
            } else if ($settings['progress_bar_line_fill_stripe_animate'] == 'reverse') {
                $wrap_classes[] = 'eael-progressbar-line-animate-rtl';
            }

            $this->add_render_attribute('eael-progressbar-line', [
                'class'         => $wrap_classes,
                'data-layout'   => 'line',
                'data-count'    => $settings['progress_bar_value']['size'],
                'data-duration' => $settings['progress_bar_animation_duration']['size'],
            ]);

            $this->add_render_attribute('eael-progressbar-line-fill', [
                'class' => 'eael-progressbar-line-fill',
                'style' => '-webkit-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;',
            ]);

            echo '<div class="eael-progressbar-line-container ' . $settings['progress_bar_line_alignment'] . '">
                ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '

                <div ' . $this->get_render_attribute_string('eael-progressbar-line') . '>
                    ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                    <span ' . $this->get_render_attribute_string('eael-progressbar-line-fill') . '></span>
                </div>
            </div>';
        }
        if($settings['progress_bar_layout'] == 'circle' || $settings['progress_bar_layout'] == 'circle_fill') {

            $wrap_classes[] = 'eael-progressbar-circle';
            $wrap_classes = apply_filters('eael_progressbar_circle_fill_wrap_class', $wrap_classes, $settings);
            
            $this->add_render_attribute(
                'eael-progressbar-circle',
                [
                    'class'         => $wrap_classes,
                    'data-layout'   => $settings['progress_bar_layout'],
                    'data-count'    => $settings['progress_bar_value']['size'],
                    'data-duration' => $settings['progress_bar_animation_duration']['size'],
                ]
            );

            echo '<div class="eael-progressbar-circle-container ' . $settings['progress_bar_circle_alignment'] . '">
                ' . ($settings['progress_bar_circle_box_shadow_box_shadow'] ? '<div class="eael-progressbar-circle-shadow">' : '') . '

                <div ' . $this->get_render_attribute_string('eael-progressbar-circle') . '>
                    <div class="eael-progressbar-circle-pie">
                        <div class="eael-progressbar-circle-half-left eael-progressbar-circle-half"></div>
                        <div class="eael-progressbar-circle-half-right eael-progressbar-circle-half"></div>
                    </div>
                    <div class="eael-progressbar-circle-inner"></div>
                    <div class="eael-progressbar-circle-inner-content">
                        ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '
                        ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                    </div>
                </div>

                ' . ($settings['progress_bar_circle_box_shadow_box_shadow'] ? '</div>' : '') . '
            </div>';
        }
        if(apply_filters('eael/pro_enabled', false)){
            $circle_condition = $settings['progress_bar_layout'] == 'half_circle' || $settings['progress_bar_layout'] == 'half_circle_fill';
        }else {
            $circle_condition = $settings['progress_bar_layout'] == 'half_circle';
        }
        if($circle_condition) {
            $wrap_classes[] = 'eael-progressbar-half-circle';
            $wrap_classes = apply_filters('eael_progressbar_half_circle_wrap_class', $wrap_classes, $settings);

            $this->add_render_attribute(
                'eael-progressbar-circle-half',
                [
                    'class' => 'eael-progressbar-circle-half',
                    'style' => '-webkit-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;',
                ]
            );

            $this->add_render_attribute(
                'eael-progressbar-half-circle',
                [
                    'class'         => $wrap_classes,
                    'data-layout'   => $settings['progress_bar_layout'],
                    'data-count'    => $settings['progress_bar_value']['size'],
                    'data-duration' => $settings['progress_bar_animation_duration']['size'],
                ]
            );

            echo '<div class="eael-progressbar-circle-container ' . $settings['progress_bar_circle_alignment'] . '">
                <div ' . $this->get_render_attribute_string('eael-progressbar-half-circle') . '>
                    <div class="eael-progressbar-circle">
                        <div class="eael-progressbar-circle-pie">
                            <div ' . $this->get_render_attribute_string('eael-progressbar-circle-half') . '></div>
                        </div>
                        <div class="eael-progressbar-circle-inner"></div>
                    </div>
                    <div class="eael-progressbar-circle-inner-content">
                        ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '
                        ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                    </div>
                </div>
                <div class="eael-progressbar-half-circle-after">
                    ' . ($settings['progress_bar_prefix_label'] ? sprintf('<span class="eael-progressbar-prefix-label">%1$s</span>', $settings['progress_bar_prefix_label']) : '') . '
                    ' . ($settings['progress_bar_postfix_label'] ? sprintf('<span class="eael-progressbar-postfix-label">%1$s</span>', $settings['progress_bar_postfix_label']) : '') . '
                </div>
            </div>';
        }
        do_action('add_eael_progressbar_block', $settings, $this, $wrap_classes);
    }
}

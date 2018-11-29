<?php
namespace Elementor;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Widget_Eael_Progress_Bar extends Widget_Base
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

        $this->add_control(
            'progress_bar_layout',
            [
                'label' => __('Layout', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'line' => __('Line', 'essential-addons-elementor'),
                    'circle' => __('Circle', 'essential-addons-elementor'),
                    'half_circle' => __('Half Circle', 'essential-addons-elementor'),
                ],
                'default' => 'line',
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
                    'h1' => __('H1', 'essential-addons-elementor'),
                    'h2' => __('H2', 'essential-addons-elementor'),
                    'h3' => __('H3', 'essential-addons-elementor'),
                    'h4' => __('H4', 'essential-addons-elementor'),
                    'h5' => __('H5', 'essential-addons-elementor'),
                    'h6' => __('H6', 'essential-addons-elementor'),
                    'div' => __('div', 'essential-addons-elementor'),
                    'span' => __('span', 'essential-addons-elementor'),
                    'p' => __('p', 'essential-addons-elementor'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'progress_bar_value',
            [
                'label' => __('Counter Value', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
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
                'label' => esc_html__('Display Count', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'progress_bar_prefix_label',
            [
                'label' => __('Prefix Label', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Prefix', 'essential-addons-elementor'),
                'condition' => [
                    'progress_bar_layout' => ['half_circle'],
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_postfix_label',
            [
                'label' => __('Postfix Label', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Postfix', 'essential-addons-elementor'),
                'condition' => [
                    'progress_bar_layout' => ['half_circle'],
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Background
         */
        $this->start_controls_section(
            'progress_bar_section_style_bg',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => 'line',
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
                    'progress_bar_layout' => 'line',
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

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'progress_bar_line_fill_color',
                'label' => __('Color', 'essential-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-progressbar-fill',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_line_fill_stripe',
            [
                'label' => __('Stripe', 'essential-addons-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Enable', 'essential-addons-elementor'),
                'label_off' => __('Disable', 'essential-addons-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_line_fill_stripe_animate',
            [
                'label' => __('Stripe Animation', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'normal' => __('Left To Right', 'plugin-domain'),
                    'reverse' => __('Right To Left', 'plugin-domain'),
                    'none' => __('Disabled', 'plugin-domain'),
                ],
                'default' => 'none',
                'condition' => [
                    'progress_bar_line_fill_stripe' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: General(Circle)
         */
        $this->start_controls_section(
            'progress_bar_section_style_general',
            [
                'label' => __('General', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'progress_bar_layout' => ['circle', 'half_circle'],
                ],
            ]
        );

        $this->add_control(
            'progress_bar_circle_size',
            [
                'label' => __('Size', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-progressbar-half-circle' => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}} / 2 * 1{{UNIT}});',
                    '{{WRAPPER}} .eael-progressbar-half-circle-after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_circle_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-shadow' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_circle_stroke_width',
            [
                'label' => __('Stroke Width', 'essential-addons-elementor'),
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
                    '{{WRAPPER}} .eael-progressbar-circle-shadow' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-progressbar-circle-half' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_circle_stroke_color',
            [
                'label' => __('Stroke Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-shadow' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_circle_fill_color',
            [
                'label' => __('Fill Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-circle-half' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

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
                'name' => 'progress_bar_title_typography',
                'label' => __('Title', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
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
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_bar_count_typography',
                'label' => __('Counter', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-progressbar-count-wrap',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'progress_bar_count_color',
            [
                'label' => __('Counter Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-count' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_bar_after_typography',
                'label' => __('Prefix/Postfix', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-progressbar-half-circle-after',
                'condition' => [
                    'progress_bar_layout' => ['half_circle'],
                ],
                'separator' => 'before',
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
                    'progress_bar_layout' => ['half_circle'],
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $wrap_classes = ['eael-progressbar'];

        if ($settings['progress_bar_layout'] == 'line') {
            $wrap_classes[] = 'eael-progressbar-line';

            if ($settings['progress_bar_line_fill_stripe'] == 'yes') {
                $wrap_classes[] = 'eael-progressbar-line-stripe';
            }

            if ($settings['progress_bar_line_fill_stripe_animate'] == 'normal') {
                $wrap_classes[] = 'eael-progressbar-line-animate';
            } else if ($settings['progress_bar_line_fill_stripe_animate'] == 'reverse') {
                $wrap_classes[] = 'eael-progressbar-line-animate-rtl';
            }

            $this->add_render_attribute('eael-progressbar-line', [
                'class' => $wrap_classes,
                'data-layout' => $settings['progress_bar_layout'],
                'data-count' => $settings['progress_bar_value']['size'],
            ]);

            if ($settings['progress_bar_title']) {
                echo sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']);
            }

            echo '<div ' . $this->get_render_attribute_string('eael-progressbar-line') . '>
                ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                <span class="eael-progressbar-line-fill"></span>
            </div>';
        } else if ($settings['progress_bar_layout'] == 'circle') {
            $wrap_classes[] = 'eael-progressbar-circle';

            $this->add_render_attribute('eael-progressbar-circle', [
                'class' => $wrap_classes,
                'data-layout' => $settings['progress_bar_layout'],
                'data-count' => $settings['progress_bar_value']['size'],
            ]);

            echo '<div ' . $this->get_render_attribute_string('eael-progressbar-circle') . '>
                <div class="eael-progressbar-circle-pie">
                    <div class="eael-progressbar-circle-half-left eael-progressbar-circle-half"></div>
                    <div class="eael-progressbar-circle-half-right eael-progressbar-circle-half"></div>
                </div>
                <div class="eael-progressbar-circle-shadow"></div>
                <div class="eael-progressbar-circle-inner">
                    ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '
                    ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                </div>
            </div>';
        } else if ($settings['progress_bar_layout'] == 'half_circle') {
            $wrap_classes[] = 'eael-progressbar-half-circle';

            $this->add_render_attribute('eael-progressbar-half-circle', [
                'class' => $wrap_classes,
                'data-layout' => $settings['progress_bar_layout'],
                'data-count' => $settings['progress_bar_value']['size'],
            ]);

            echo '<div ' . $this->get_render_attribute_string('eael-progressbar-half-circle') . '>
                <div class="eael-progressbar-circle">
                    <div class="eael-progressbar-circle-pie">
                        <div class="eael-progressbar-circle-half"></div>
                    </div>
                    <div class="eael-progressbar-circle-shadow"></div>
                </div>
                <div class="eael-progressbar-circle-inner">
                    ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '
                    ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . $settings['progress_bar_value']['unit'] . '</span></span>' : '') . '
                </div>
            </div>
            <div class="eael-progressbar-half-circle-after">
                ' . ($settings['progress_bar_prefix_label'] ? sprintf('<span class="eael-progressbar-prefix-label">%1$s</span>', $settings['progress_bar_prefix_label']) : '') . '
                ' . ($settings['progress_bar_postfix_label'] ? sprintf('<span class="eael-progressbar-postfix-label">%1$s</span>', $settings['progress_bar_postfix_label']) : '') . '
            </div>';
        }
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Eael_Progress_Bar());

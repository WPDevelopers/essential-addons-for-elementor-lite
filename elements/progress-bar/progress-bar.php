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
            'eael_progress_bar_section_layout',
            [
                'label' => __('Layout', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'progress_bar_layout',
            [
                'label' => __('Layout', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'line',
                'options' => [
                    'line' => __('Line', 'essential-addons-elementor'),
                    'circle' => __('Circle', 'essential-addons-elementor'),
                    'half_circle' => __('Half Circle', 'essential-addons-elementor'),
                    'fill' => __('Fill', 'essential-addons-elementor'),
                ],
            ]
        );

        $this->add_control(
            'progress_bar_value',
            [
                'label' => __('Value', 'essential-addons-elementor'),
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
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-fill' => 'width: {{SIZE}}{{UNIT}}',
                ],
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
            'eael_progress_bar_section_style_bg',
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_line_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Fill
         */
        $this->start_controls_section(
            'eael_progress_bar_section_style_fill',
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-progressbar-fill' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'progress_bar_line_fill_color',
                'label' => __('Background', 'essential-addons-elementor'),
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

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $wrap_classes = $fill_classes = [];

        if ($settings['progress_bar_layout'] == 'line') {
            $wrap_classes[] = 'eael-progressbar';

            if ($settings['progress_bar_line_fill_stripe'] == 'yes') {
                $wrap_classes[] = 'eael-progressbar-stripe';
            }
        }

        $this->add_render_attribute('eael-progressbar', [
            'class' => $wrap_classes,
            'data-value' => $settings['progress_bar_value']['size'],
        ]);

        ?>

            <div <?php echo $this->get_render_attribute_string('eael-progressbar'); ?>>
                <span class="eael-progressbar-fill init-zero"></span>
            </div>

        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Eael_Progress_Bar());
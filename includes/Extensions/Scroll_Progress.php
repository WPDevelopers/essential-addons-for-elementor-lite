<?php
namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Core\Settings\Manager as Settings_Manager;

class Scroll_Progress
{
    public function __construct()
    {
        add_action('elementor/element/post/document_settings/after_section_end', [$this, 'register_controls'], 10);
        add_action('wp_footer', [$this, 'render_html']);
    }

    public function register_controls($element)
    {
        $element->start_controls_section(
            'eael_ext_scroll_progress_section',
            [
                'label' => esc_html__('EA Scroll Progress', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress',
            [
                'label' => __('Enable Scroll Progress', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress_position',
            [
                'label' => esc_html__('Position', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'label_block' => false,
                'options' => [
                    'top' => esc_html__('Top', 'essential-addons-elementor'),
                    'bottom' => esc_html__('Bottom', 'essential-addons-elementor'),
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_progress' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress_height',
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
                    'size' => 5,
                ],
                'selectors' => [
                    '.eael-scroll-progress' => 'height: {{SIZE}}{{UNIT}}',
                    '.eael-scroll-progress .eael-scroll-progress-fill' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_progress' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fafafa',
                'selectors' => [
                    '.eael-scroll-progress' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_progress' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress_fill_color',
            [
                'label' => __('Fill Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cfdd10',
                'selectors' => [
                    '.eael-scroll-progress .eael-scroll-progress-fill' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_progress' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress_animation_speed',
            [
                'label' => __('Animation Speed', 'essential-addons-elementor'),
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
                    '.eael-scroll-progress .eael-scroll-progress-fill' => 'transition: width {{SIZE}}ms ease;',
                ],
                'separator' => 'before',
                'condition' => [
                    'eael_ext_scroll_progress' => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }

    public function render_html()
    {
        if (is_singular() || is_home() || is_archive()) {
            $page_settings_manager = Settings_Manager::get_settings_managers('page');
            $page_settings_model = $page_settings_manager->get_model(get_the_ID());

            if ($page_settings_model->get_settings('eael_ext_scroll_progress') == 'yes') {
                add_filter('eael/section/after_render', function ($extensions) {
                    $extensions[] = 'eael-scroll-progress';
                    return $extensions;
                });

                echo '<div class="eael-scroll-progress eael-scroll-progress-' . $page_settings_model->get_settings('eael_ext_scroll_progress_position') . '"><div class="eael-scroll-progress-fill"></div></div>';
            }
        }
    }

}

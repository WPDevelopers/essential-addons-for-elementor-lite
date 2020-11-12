<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;
use \Essential_Addons_Elementor\Classes\Controls;

class Content_Ticker extends Widget_Base
{
    
    use \Essential_Addons_Elementor\Traits\Template_Query;

    public function get_name()
    {
        return 'eael-content-ticker';
    }

    public function get_title()
    {
        return esc_html__('Content Ticker', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-content-ticker';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'ticker',
            'ea ticker',
            'ea content ticker',
            'news headline',
            'news ticker',
            'text rotate',
            'text animation',
            'text swing',
            'text slide',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/content-ticker/';
    }

    protected function _register_controls()
    {
        /**
         * Content Ticker Content Settings
         */
        $this->start_controls_section(
            'eael_section_content_ticker_settings',
            [
                'label' => esc_html__('Ticker Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_dynamic_template_Layout',
            [
                'label'   => esc_html__('Template Layout', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $this->get_template_list_for_dropdown(),
            ]
        );

        $ticker_options = apply_filters(
            'eael_ticker_options',
            [
                'options'    => [
                    'dynamic' => esc_html__('Dynamic', 'essential-addons-for-elementor-lite'),
                    'custom'  => esc_html__('Custom', 'essential-addons-for-elementor-lite'),
                ],
                'conditions' => [
                    'custom',
                ],
            ]
        );

        $this->add_control(
            'eael_ticker_type',
            [
                'label'       => esc_html__('Ticker Type', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'dynamic',
                'label_block' => false,
                'options'     => $ticker_options['options'],
            ]
        );

        $this->add_control(
            'eael_ticker_type_pro_alert',
            [
                'label'     => esc_html__('Custom Content available in pro version only!', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_ticker_type' => $ticker_options['conditions'],
                ],
            ]
        );

        $this->add_control(
            'eael_ticker_tag_text',
            [
                'label'       => esc_html__('Tag Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => false,
                'default'     => esc_html__('Trending Today', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->end_controls_section();

        /**
         * Query Controls
         * @source includes/helper.php
         */
        do_action('eael/controls/query', $this);

        do_action('eael_ticker_custom_content_controls', $this);

        /**
         * Content Tab: Carousel Settings
         */
        $this->start_controls_section(
            'section_additional_options',
            [
                'label' => __('Animation Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'carousel_effect',
            [
                'label'       => __('Effect', 'essential-addons-for-elementor-lite'),
                'description' => __('Sets transition effect', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'slide',
                'options'     => [
                    'slide' => __('Slide', 'essential-addons-for-elementor-lite'),
                    'fade'  => __('Fade', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_responsive_control(
            'items',
            [
                'label'          => __('Visible Items', 'essential-addons-for-elementor-lite'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => ['size' => 1],
                'tablet_default' => ['size' => 1],
                'mobile_default' => ['size' => 1],
                'range'          => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 10,
                        'step' => 1,
                    ],
                ],
                'size_units'     => '',
                'condition'      => [
                    'carousel_effect' => 'slide',
                ],
                'separator'      => 'before',
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => __('Items Gap', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 10],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'condition'  => [
                    'carousel_effect' => 'slide',
                ],
            ]
        );

        $this->add_control(
            'slider_speed',
            [
                'label'       => __('Slider Speed', 'essential-addons-for-elementor-lite'),
                'description' => __('Duration of transition between slides (in ms)', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SLIDER,
                'default'     => ['size' => 400],
                'range'       => [
                    'px' => [
                        'min'  => 100,
                        'max'  => 3000,
                        'step' => 1,
                    ],
                ],
                'size_units'  => '',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => __('Autoplay', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'      => __('Autoplay Speed', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 2000],
                'range'      => [
                    'px' => [
                        'min'  => 500,
                        'max'  => 5000,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'condition'  => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'        => __('Pause On Hover', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'condition'    => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'        => __('Infinite Loop', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'grab_cursor',
            [
                'label'        => __('Grab Cursor', 'essential-addons-for-elementor-lite'),
                'description'  => __('Shows grab cursor when you hover over the slider', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'navigation_heading',
            [
                'label'     => __('Navigation', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label'        => __('Arrows', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'     => __('Direction', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => __('Left', 'essential-addons-for-elementor-lite'),
                    'right' => __('Right', 'essential-addons-for-elementor-lite'),
                ],
                'separator' => 'before',
                'condition' => [
                    'carousel_effect' => 'slide',
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
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default'     => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style (Ticker Content Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_ticker_typography_settings',
            [
                'label' => esc_html__('Ticker Content', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_ticker_content_bg',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ticker-wrap .eael-ticker' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_ticker_content_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_ticker_hover_content_color',
            [
                'label'     => esc_html__('Text Hover Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_ticker_content_typography',
                'selector' => '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a',

            ]
        );

        $this->add_responsive_control(
            'eael_ticker_content_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_ticker_content_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-ticker-wrap .eael-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_ticker_tag_style_settings',
            [
                'label' => esc_html__('Tag Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_ticker_tag_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#222222',
                'selectors' => [
                    '{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_ticker_tag_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_ticker_tag_typography',
                'selector' => '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span',
            ]
        );
        $this->add_responsive_control(
            'eael_ticker_tag_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_ticker_tag_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_ticker_tag_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'     => __('Arrows', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow',
            [
                'label'       => __('Choose Prev Arrow', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::ICONS,
                'label_block' => true,
                'default'     => [
                    'value'   => 'fas fa-angle-left',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'arrow_new',
            [
                'label'            => __('Choose Next Arrow', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'arrow',
                'label_block'      => true,
                'default'          => [
                    'value'   => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label'      => __('Arrows Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => '22'],
                'range'      => [
                    'px' => [
                        'min'  => 5,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'         => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next img, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'      => __('Align Left Arrow', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'      => __('Align Right Arrow', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_arrows_style');

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'arrows_border_normal',
                'label'       => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
            ]
        );

        $this->add_control(
            'arrows_border_radius_normal',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrows_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = Helper::fix_old_query($settings);
        $args = Helper::get_query_args($settings);

        $this->add_render_attribute('content-ticker-wrap', 'class', 'swiper-container-wrap eael-ticker');

        $this->add_render_attribute('content-ticker', 'class', 'swiper-container eael-content-ticker');
        $this->add_render_attribute('content-ticker', 'class', 'swiper-container-' . esc_attr($this->get_id()));
        $this->add_render_attribute('content-ticker', 'data-pagination', '.swiper-pagination-' . esc_attr($this->get_id()));
        $this->add_render_attribute('content-ticker', 'data-arrow-next', '.swiper-button-next-' . esc_attr($this->get_id()));
        $this->add_render_attribute('content-ticker', 'data-arrow-prev', '.swiper-button-prev-' . esc_attr($this->get_id()));

        if ($settings['direction'] == 'right') {
            $this->add_render_attribute('content-ticker', 'dir', 'rtl');
        }

        if (!empty($settings['items']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-items', $settings['items']['size']);
        }
        if (!empty($settings['items_tablet']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-items-tablet', $settings['items_tablet']['size']);
        }
        if (!empty($settings['items_mobile']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-items-mobile', $settings['items_mobile']['size']);
        }
        if (!empty($settings['margin']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-margin', $settings['margin']['size']);
        }
        if (!empty($settings['margin_tablet']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-margin-tablet', $settings['margin_tablet']['size']);
        }
        if (!empty($settings['margin_mobile']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-margin-mobile', $settings['margin_mobile']['size']);
        }
        if ($settings['carousel_effect']) {
            $this->add_render_attribute('content-ticker', 'data-effect', $settings['carousel_effect']);
        }
        if (!empty($settings['slider_speed']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-speed', $settings['slider_speed']['size']);
        }
        if ($settings['autoplay'] == 'yes' && !empty($settings['autoplay_speed']['size'])) {
            $this->add_render_attribute('content-ticker', 'data-autoplay', $settings['autoplay_speed']['size']);
        } else {
            $this->add_render_attribute('content-ticker', 'data-autoplay', '999999');
        }
        if ($settings['pause_on_hover'] == 'yes') {
            $this->add_render_attribute('content-ticker', 'data-pause-on-hover', 'true');
        }
        if ($settings['infinite_loop'] == 'yes') {
            $this->add_render_attribute('content-ticker', 'data-loop', true);
        }
        if ($settings['grab_cursor'] == 'yes') {
            $this->add_render_attribute('content-ticker', 'data-grab-cursor', true);
        }
        if ($settings['arrows'] == 'yes') {
            $this->add_render_attribute('content-ticker', 'data-arrows', '1');
        }
        
        echo '<div class="eael-ticker-wrap" id="eael-ticker-wrap-' . $this->get_id() . '">';
        if (!empty($settings['eael_ticker_tag_text'])) {
            echo '<div class="ticker-badge">
                    <span>' . $settings['eael_ticker_tag_text'] . '</span>
                </div>';
        }

        echo '<div ' . $this->get_render_attribute_string('content-ticker-wrap') . '>
                <div ' . $this->get_render_attribute_string('content-ticker') . '>
                    <div class="swiper-wrapper">';

                        if ('dynamic' === $settings['eael_ticker_type']) {

                            if (\file_exists($this->get_template($settings['eael_dynamic_template_Layout']))) {
                                $query = new \WP_Query($args);
                                if ($query->have_posts()) {
                                    while ($query->have_posts()) {
                                        $query->the_post();
                                        include $this->get_template($settings['eael_dynamic_template_Layout']);
                                    }
                                    wp_reset_postdata();
                                }
                            } else {
                                echo '<div class="swiper-slide"><a href="#" class="ticker-content">' . __('No content found!', 'essential-addons-for-elementor-lite') . '</a></div>';
                            }
                        } elseif ('custom' === $settings['eael_ticker_type'] && apply_filters('eael/is_plugin_active', 'essential-addons-elementor/essential_adons_elementor.php')) {
                            if (\file_exists($this->get_template($settings['eael_dynamic_template_Layout']))) {
                                foreach ($settings['eael_ticker_custom_contents'] as $content) {
                                    echo Helper::include_with_variable($this->get_template($settings['eael_dynamic_template_Layout']), ['content' => $content['eael_ticker_custom_content'], 'link' => $content['eael_ticker_custom_content_link']]);
                                }
                            }
                        }
                        
                    echo '</div>
				</div>
				' . $this->render_arrows() . '
			</div>
		</div>';
    }

    /**
     * Render Content Ticker arrows output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render_arrows()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['arrows'] == 'yes') {
            if (isset($settings['__fa4_migrated']['arrow_new']) || empty($settings['arrow'])) {
                $arrow = $settings['arrow_new']['value'];
            } else {
                $arrow = $settings['arrow'];
            }

            $html = '<div class="content-ticker-pagination">';

            $html .= '<div class="swiper-button-next swiper-button-next-' . $this->get_id() . '">';
            if (isset($arrow['url'])) {
                $html .= '<img src="' . esc_url($arrow['url']) . '" alt="' . esc_attr(get_post_meta($arrow['id'], '_wp_attachment_image_alt', true)) . '" />';
            } else {
                $html .= '<i class="' . $arrow . '"></i>';
            }
            $html .= '</div>';

            $html .= '<div class="swiper-button-prev swiper-button-prev-' . $this->get_id() . '">';
            if (isset($settings['prev_arrow']['value']['url'])) {
                $html .= '<img src="' . esc_url($settings['prev_arrow']['value']['url']) . '" alt="' . esc_attr(get_post_meta($settings['prev_arrow']['value']['id'], '_wp_attachment_image_alt', true)) . '" />';
            } else {
                $html .= '<i class="' . esc_attr($settings['prev_arrow']['value']) . '"></i>';
            }
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }
    }
}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class SVG_Draw Extends Widget_Base
{
    public function get_name()
    {
        return 'eael-svg-draw';
    }

    public function get_title()
    {
        return esc_html__('SVG Draw', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'svg',
            'draq',
            'ea svg',
            'ea svg draw',
            'animation',
            'icon',
            'icon animation',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-tabs/';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'eael_section_svg_content_settings',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_svg_src',
            [
                'label' => esc_html__( 'Source', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                    'custom' => esc_html__( 'Custom HTML', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_svg_icon',
            [
                'label' => esc_html__( 'Icon', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_svg_src' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'svg_html',
            [
                'label' => esc_html__( 'SVG Code', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'eael_svg_src' => 'custom'
                ],
                'description' => esc_html__( 'SVG code needs path elements to draw.', 'essential-addons-for-elementor-lite' ),
            ]
        );



        $this->add_control(
            'eael_svg_exclude_color',
            [
                'label' => esc_html__( 'Exclude Color', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'default' => 'yes',
                'description' => esc_html__( 'Exclude color from SVG Source (If any).', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_responsive_control(
            'eael_svg_width',
            [
                'label' => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_svg_height',
            [
                'label' => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_alignment',
            [
                'label' => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_link',
            [
                'label' => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
                'options' => [ 'url' ],
                'label_block' => true,
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_svg_appearance',
            [
                'label' => esc_html__('Appearance', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_svg_fill',
            [
                'label' => esc_html__( 'SVG Fill Type', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                    'after' => esc_html__( 'Fill After Draw', 'essential-addons-for-elementor-lite' ),
                    'before' => esc_html__( 'Fill Before Draw', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );


        $this->add_control(
            'eael_svg_fill_transition',
            [
                'label' => esc_html__( 'Fill Transition', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}} .fill-svg svg path' => 'animation-duration: {{SIZE}}s;',
                ],
                'description' => esc_html__( 'Duration on SVG fills (in seconds)', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_animation_on',
            [
                'label' => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                    'page-load' => esc_html__( 'On Page Load', 'essential-addons-for-elementor-lite' ),
                    'page-scroll' => esc_html__( 'On Page Scroll', 'essential-addons-for-elementor-lite' ),
                    'hover'  => esc_html__( 'Mouse Hover', 'essential-addons-for-elementor-lite' ),
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_svg_draw_offset',
            [
                'label' => esc_html__( 'Drawing Start Point', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 50,
                'condition' => [
                    'eael_svg_animation_on' => [ 'page-scroll'],
                ],
                'description' => esc_html__( 'The point at which the drawing begins to animate as scrolls down (in pixels).', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover Off', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'default' => 'yes',
                'condition' => [
                    'eael_svg_animation_on' => 'hover',
                ],
                'description' => esc_html__( 'Pause SVG drawing on mouse leave', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_loop',
            [
                'label' => esc_html__( 'Repeat Drawing', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll', 'none'],
                ]
            ]
        );

        $this->add_control(
            'eael_svg_animation_direction',
            [
                'label' => esc_html__( 'Direction', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'reverse',
                'options' => [
                    'reverse' => esc_html__( 'Reverse', 'essential-addons-for-elementor-lite' ),
                    'restart' => esc_html__( 'Restart', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll', 'none'],
                    'eael_svg_loop' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_svg_draw_speed',
            [
                'label' => esc_html__( 'Speed', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 300,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll'],
                ],
                'description' => esc_html__( 'Duration on SVG draws (in ms)', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_svg_style_settings',
            [
                'label' => esc_html__('Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_svg_path_thickness',
            [
                'label' => esc_html__( 'Path Thickness', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} svg path' => 'stroke-width: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'selectors' => [
                    '{{WRAPPER}} svg path' => 'stroke:{{VALUE}};',
                    '{{WRAPPER}} .eael-svg-draw-container .fill-svg svg path' => 'fill:{{VALUE}};'
                ],
                'default' => '#c36'
            ]
        );

        $this->add_control(
            'eael_svg_fill_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Fill Color', 'essential-addons-for-elementor-lite' ),
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .fill-svg svg path' => 'fill:{{VALUE}};'
                ],
                'default' => '#c36',
                'condition' => [
                    'eael_svg_fill!' => 'none'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_svg_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_svg_border',
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_svg_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_padding',
            [
                'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_margin',
            [
                'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $svg_html = isset( $settings['svg_html'] ) ? preg_replace('#<script(.*?)>(.*?)</script>#is', '', $settings['svg_html'] ) : '';
        $this->add_render_attribute('eael-svg-drow-wrapper', [
            'class'           => [
                'eael-svg-draw-container',
                esc_attr( $settings['eael_svg_animation_on'] ),
                $settings['eael_svg_fill'] === 'before' ? 'fill-svg' : ''
            ],
        ]);

        $svg_options = [
            'fill' => $settings['eael_svg_fill'] === 'after' ? 'fill-svg' : '',
            'speed' => esc_attr( $settings['eael_svg_draw_speed'] ),
            'offset' => esc_attr( $settings['eael_svg_draw_offset'] ),
            'loop' => $settings['eael_svg_loop'] ? esc_attr( $settings['eael_svg_loop'] ) : 'no',
            'pause' => $settings['eael_svg_pause_on_hover'] ? esc_attr( $settings['eael_svg_pause_on_hover'] ) : 'no',
            'direction' => esc_attr( $settings['eael_svg_animation_direction'] ),
            'excludeFill' => esc_attr( $settings['eael_svg_exclude_color'] )
        ];

        $this->add_render_attribute('eael-svg-drow-wrapper', [
            'data-settings' => json_encode($svg_options)
        ]);

        if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
            $this->add_link_attributes( 'eael_svg_link', $settings['eael_svg_link'] );
            echo '<a ' . $this->get_render_attribute_string( 'eael_svg_link' ) . '>';
        }

        echo '<div ' . $this->get_render_attribute_string('eael-svg-drow-wrapper') . '>';

        if ( $settings['eael_svg_src'] === 'icon' ):

            if ( $settings['eael_svg_icon']['library'] === 'svg' ):
                Icons_Manager::render_icon($settings['eael_svg_icon'], ['aria-hidden' => 'true', 'class' => ['eael-svg-drow-wrapper']]);
            else:
               echo Helper::get_svg_by_icon( $settings['eael_svg_icon'] );
            endif;

        else:
            echo $svg_html;
        endif;

        echo ' </div>';

        if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
            echo "</a>";
        }

    }
}
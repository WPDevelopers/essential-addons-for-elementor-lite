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
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_svg_src',
            [
                'label' => esc_html__( 'SVG Source Type', 'essential-addons-for-elementor-lite' ),
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
                'label' => esc_html__( 'SVG html', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'eael_svg_src' => 'custom'
                ]
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
            'eael_svg_animation_on',
            [
                'label' => esc_html__( 'Animation Action', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'No Animation', 'essential-addons-for-elementor-lite' ),
                    'page-load' => esc_html__( 'On page load', 'essential-addons-for-elementor-lite' ),
                    'hover'  => esc_html__( 'Mouse Hover', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $svg_html = isset( $settings['svg_html'] ) ? $settings['svg_html'] : '';
        $this->add_render_attribute('eael-svg-drow-wrapper', [
            'class'           => [
                'eael-svg-draw-container',
                esc_attr( $settings['eael_svg_animation_on'] )
                ]
        ]);

        echo '<div '. $this->get_render_attribute_string('eael-svg-drow-wrapper') .'>';
        if ( $settings['eael_svg_src'] === 'icon' ):
            Icons_Manager::render_icon( $settings['eael_svg_icon'], [ 'aria-hidden' => 'true', 'class' => [ 'eael-svg-drow-wrapper' ] ] );
        else:
            echo $svg_html;
        endif;
        echo ' </div>';

    }
}
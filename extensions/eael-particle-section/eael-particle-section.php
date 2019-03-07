<?php

if( !defined( 'ABSPATH' ) ) exit;

use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Widget_Base;

add_action( 'elementor/frontend/section/before_render', 'before_render' );
add_action('elementor/element/section/section_layout/after_section_end', 'register_controls',10 );

function register_controls( $element ) {

    $element->start_controls_section(
        'eael_particles_section',
        [
            'label' => 'EA Particles',
            'tab'   => Controls_Manager::TAB_LAYOUT
        ]
    );

    $element->add_control(
        'eael_particle_switch',
        [
            'label' => __( 'Enable Particles', 'essential-addons-elementor' ),
            'type'  => Controls_Manager::SWITCHER,
        ]
    );

    $element->add_control(
        'eael_particle_theme_from',
        [
            'label'		=> __( 'Theme Source', 'essential-addons-elementor' ),
            'type'		=> Controls_Manager::CHOOSE,
            'options' => [
                'presets' => [
                    'title' => __( 'Defaults', 'essential-addons-elementor' ),
                    'icon' => 'fa fa-list',
                ],
                'custom' => [
                    'title' => __( 'Custom', 'essential-addons-elementor' ),
                    'icon' => 'fa fa-edit',
                ],
            ],
            'condition' => [
                'eael_particle_switch'  => 'yes'
            ],
            'default'   => 'presets'
        ]
    );

    $element->add_control(
        'eael_particle_preset_themes',
        [
            'label'       => esc_html__( 'Preset Themes', 'essential-addons-elementor' ),
            'type'        => Controls_Manager::SELECT,
            'label_block' => true,
            'options'     => [
                11         => __( 'Default', 'essential-addons-elementor' ),
                'nasa'     => __( 'Nasa', 'essential-addons-elementor' ),
                'bubble'   => __( 'Bubble', 'essential-addons-elementor' ),
                'snow'     => __( 'Snow', 'essential-addons-elementor' ),
                'nyan_cat' => __( 'Nyan Cat', 'essential-addons-elementor' )
            ],
            'default'       => 11,
            'condition' => [
                'eael_particle_theme_from' => 'presets',
                'eael_particle_switch'     => 'yes'
            ]
        ]
    );
    
    $element->add_control(
        'eael_particles_custom_style',
        [
            'label'       => __( 'Custom Style', 'essential-addons-elementor' ),
            'type'        => Controls_Manager::TEXTAREA,
            'description' => __( 'You can generate custom particles JSON code from <a href="http://vincentgarreau.com/particles.js/#default" target="_blank">Here!</a>. Simply just past the JSON code above. For more queries <a href="https://essential-addons.com/elementor/docs/" target="_blank">Click Here!</a>', 'essential-addons-elementor' ),
            'condition' => [
                'eael_particle_theme_from' => 'custom',
                'eael_particle_switch'     => 'yes'
            ]
        ]
    );

    $element->add_control(
        'eael_particle_section_notice',
        [
            'raw'       => __( 'You need to configure a <strong style="color:green">Background Type</strong> to see this in full effect. You can do this by switching to the <strong style="color:green">Style</strong> Tab.', 'essential-addons-elementor' ),
            'type'      => Controls_Manager::RAW_HTML,
            'condition' => [
                'eael_particle_theme_from' => 'custom',
                'eael_particle_switch'     => 'yes'
            ]
        ]
    );
    
    $element->end_controls_section();

}

function before_render( $element ) {
    $settings = $element->get_settings_for_display();

    if( $settings['eael_particle_switch'] == 'yes' ){

        $element->add_render_attribute(
            '_wrapper','id',
            'eael-section-particles-' . $element->get_id()
        );


        $preset_themes = include('particle-themes.php');
        if( isset($settings['eael_particle_theme_from']) && $settings['eael_particle_theme_from'] == 'presets') {
            $theme = $preset_themes[ $settings['eael_particle_preset_themes'] ];
            $element->add_render_attribute('_wrapper', 'data-theme', $theme);
        }

        if(
            isset($settings['eael_particle_theme_from'])
            && $settings['eael_particle_theme_from'] == 'custom'
            ) {
            $element->add_render_attribute('_wrapper', 'data-theme', $settings['eael_particles_custom_style']);
        }

    }
    
}

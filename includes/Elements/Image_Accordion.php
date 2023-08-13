<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Repeater;


use \Essential_Addons_Elementor\Classes\Helper;

class Image_Accordion extends Widget_Base {
    public function get_name() {
        return 'eael-image-accordion';
    }

    public function get_title() {
        return esc_html__( 'Image Accordion', 'essential-addons-for-elementor-lite' );
    }

    public function get_icon() {
        return 'eaicon-image-accrodion';
    }

    public function get_categories() {
        return [ 'essential-addons-elementor' ];
    }

    public function get_keywords() {
        return [
            'image',
            'ea image accordion',
            'image effect',
            'hover effect',
            'creative image',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/image-accordion/';
    }

    protected function register_controls() {
        /**
         * Image accordion Content Settings
         */
        $this->start_controls_section(
            'eael_section_img_accordion_settings',
            [
                'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_img_accordion_type',
            [
                'label'       => esc_html__( 'Accordion Style', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'on-hover',
                'label_block' => false,
                'options'     => [
                    'on-hover' => esc_html__( 'On Hover', 'essential-addons-for-elementor-lite' ),
                    'on-click' => esc_html__( 'On Click', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_img_accordion_direction',
            [
                'label'       => esc_html__( 'Direction', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'on-hover',
                'label_block' => false,
                'options'     => [
                    'accordion-direction-horizontal' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
                    'accordion-direction-vertical'   => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
                ],
                'default'     => 'accordion-direction-horizontal',
            ]
        );

        $this->add_control(
            'eael_img_accordion_content_heading',
            [
                'label' => __( 'Content', 'essential-addons-for-elementor-lite' ),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_img_accordion_content_horizontal_align',
            [
                'label'   => __( 'Horizontal Alignment', 'essential-addons-for-elementor-lite' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle'  => true,
            ]
        );
        $this->add_control(
            'eael_img_accordion_content_vertical_align',
            [
                'label'   => __( 'Vertical Alignment', 'essential-addons-for-elementor-lite' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top'    => [
                        'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'fa fa-arrow-circle-up',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'fa fa-arrow-circle-down',
                    ],
                ],
                'default' => 'center',
                'toggle'  => true,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => __( 'Select Tag', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1'   => __( 'H1', 'essential-addons-for-elementor-lite' ),
                    'h2'   => __( 'H2', 'essential-addons-for-elementor-lite' ),
                    'h3'   => __( 'H3', 'essential-addons-for-elementor-lite' ),
                    'h4'   => __( 'H4', 'essential-addons-for-elementor-lite' ),
                    'h5'   => __( 'H5', 'essential-addons-for-elementor-lite' ),
                    'h6'   => __( 'H6', 'essential-addons-for-elementor-lite' ),
                    'span' => __( 'Span', 'essential-addons-for-elementor-lite' ),
                    'p'    => __( 'P', 'essential-addons-for-elementor-lite' ),
                    'div'  => __( 'Div', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_accordion_is_active',
            [
                'label'        => __( 'Make it active?', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'eael_accordion_bg',
            [
                'label'       => esc_html__( 'Background Image', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'default'     => [
                    'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/accordion.png',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $repeater->add_control(
            'eael_accordion_tittle',
            [
                'label'       => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Accordion item title', 'essential-addons-for-elementor-lite' ),
                'dynamic'     => [ 'active' => true ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_accordion_content',
            [
                'label'       => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default'     => esc_html__( 'Accordion content goes here!', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $repeater->add_control(
            'eael_accordion_enable_title_link',
            [
                'label'        => esc_html__( 'Enable Title Link', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'your-plugin' ),
                'label_off'    => __( 'Hide', 'your-plugin' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'eael_accordion_title_link',
            [
                'name'          => 'eael_accordion_title_link',
                'label'         => esc_html__( 'Title Link', 'essential-addons-for-elementor-lite' ),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
                'default'       => [
                    'url'         => '#',
                    'is_external' => '',
                ],
                'show_external' => true,
                'condition'     => [
                    'eael_accordion_enable_title_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_img_accordions',
            [
                'type'        => Controls_Manager::REPEATER,
                'seperator'   => 'before',
                'default'     => [
                    [
                        'eael_accordion_tittle'  => esc_html__( 'Image Accordion #1', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_content' => esc_html__( 'Image Accordion Content Goes Here! Click edit button to change this text.', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_bg'      => [
                            'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/accordion.png',
                        ]
                    ],
                    [
                        'eael_accordion_tittle'  => esc_html__( 'Image Accordion #2', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_content' => esc_html__( 'Image Accordion Content Goes Here! Click edit button to change this text.', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_bg'      => [
                            'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/accordion.png',
                        ]
                    ],
                    [
                        'eael_accordion_tittle'  => esc_html__( 'Image Accordion #3', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_content' => esc_html__( 'Image Accordion Content Goes Here! Click edit button to change this text.', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_bg'      => [
                            'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/accordion.png',
                        ]
                    ],
                    [
                        'eael_accordion_tittle'  => esc_html__( 'Image Accordion #4', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_content' => esc_html__( 'Image Accordion Content Goes Here! Click edit button to change this text.', 'essential-addons-for-elementor-lite' ),
                        'eael_accordion_bg'      => [
                            'url' => EAEL_PLUGIN_URL . '/assets/front-end/img/accordion.png',
                        ]
                    ],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{eael_accordion_tittle}}',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Image accordion)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_img_accordion_style_settings',
            [
                'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_accordion_height',
            [
                'label'       => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '400',
                'description' => 'Unit in px',
                'selectors'   => [
                    '{{WRAPPER}} .eael-img-accordion ' => 'height: {{VALUE}}px;',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_accordion_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-img-accordion' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_accordion_container_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-img-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_accordion_container_margin',
            [
                'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-img-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_accordion_border',
                'label'    => esc_html__( 'Border', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .eael-img-accordion',
            ]
        );

        $this->add_control(
            'eael_accordion_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 4,
                ],
                'range'     => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-img-accordion'               => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-img-accordion a:first-child' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-img-accordion a:last-child'  => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_accordion_shadow',
                'selector' => '{{WRAPPER}} .eael-img-accordion',
            ]
        );

        $this->add_control(
            'eael_accordion_img_overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, .3)',
                'selectors' => [
	                '{{WRAPPER}} .eael-img-accordion .eael-image-accordion-hover:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_accordion_img_hover_color',
            [
                'label'     => esc_html__( 'Hover Overlay Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, .5)',
                'selectors' => [
                    '{{WRAPPER}} .eael-img-accordion .eael-image-accordion-hover:hover::before'         => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-img-accordion .eael-image-accordion-hover.overlay-active:hover::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-img-accordion .eael-image-accordion-hover.overlay-active:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style (Thumbnail Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_img_accordion_thumbnail_style_settings',
            [
                'label' => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_image_accordion_thumbnail_margin',
            [
                'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-img-accordion a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'eael_image_accordion_thumbnail_padding',
            [
                'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-img-accordion a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'eael_image_accordion_thumbnail_radius',
            [
                'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-img-accordion a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'eael_image_accordion_thumbnail_border',
                'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .eael-img-accordion a',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Image accordion Content Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_img_accordion_typography_settings',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_accordion_title_text',
            [
                'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_accordion_title_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-img-accordion .overlay .img-accordion-title' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_accordion_title_typography',
                'selector' => '{{WRAPPER}} .eael-img-accordion .overlay .img-accordion-title',
            ]
        );

        $this->add_control(
            'eael_accordion_content_text',
            [
                'label'     => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_accordion_content_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-img-accordion .overlay p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_accordion_content_typography',
                'selector' => '{{WRAPPER}} .eael-img-accordion .overlay p',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings             = $this->get_settings_for_display();
        $horizontal_alignment = 'eael-img-accordion-horizontal-align-' . $settings[ 'eael_img_accordion_content_horizontal_align' ];
        $vertical_alignment   = 'eael-img-accordion-vertical-align-' . $settings[ 'eael_img_accordion_content_vertical_align' ];

        $this->add_render_attribute(
            'eael-image-accordion',
            [
                'class' => [
                    'eael-img-accordion',
                    $settings[ 'eael_img_accordion_direction' ],
                    $horizontal_alignment,
                    $vertical_alignment,
                ],
                'id'    => 'eael-img-accordion-' . $this->get_id(),
            ]
        );


        $this->add_render_attribute( 'eael-image-accordion', 'data-img-accordion-id', esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'eael-image-accordion', 'data-img-accordion-type', $settings[ 'eael_img_accordion_type' ] );
        if ( empty( $settings[ 'eael_img_accordions' ] ) ) {
            return;
        }
        ?>
    <div <?php echo $this->get_render_attribute_string( 'eael-image-accordion' ); ?>>
        <?php foreach ( $settings[ 'eael_img_accordions' ] as $key => $img_accordion ): ?>
            <?php
            $eael_accordion_link = $target = $nofollow = $attributes = $active = '';
            $tag = 'div';
            if ( $img_accordion[ 'eael_accordion_enable_title_link' ] == 'yes' ) {
                $eael_accordion_link = ( '#' === $img_accordion[ 'eael_accordion_title_link' ][ 'url' ] ) ? '#/' : $img_accordion[ 'eael_accordion_title_link' ][ 'url' ];
            
                $target              = $img_accordion[ 'eael_accordion_title_link' ][ 'is_external' ] ? 'target="_blank"' : '';
                $nofollow            = $img_accordion[ 'eael_accordion_title_link' ][ 'nofollow' ] ? 'rel="nofollow"' : '';
                if($img_accordion['eael_accordion_title_link']['custom_attributes']){
                    $attributes = str_replace("|",'="',$img_accordion['eael_accordion_title_link']['custom_attributes']);
                    $attributes = str_replace(",",'" ',$attributes);
                    $attributes .='"';
                }
                $this->add_render_attribute( 'eael-image-accordion-link-' . $key, 'href', esc_url( $eael_accordion_link ));
                
                $tag = 'a';
            }

            $active              = $img_accordion[ 'eael_accordion_is_active' ];
            $activeCSS           = ( $active === 'yes' ? ' flex: 3 1 0%;' : '' );
            $this->add_render_attribute(
                'eael-image-accordion-link-' . $key,
                [
                    'class' => 'eael-image-accordion-hover',
                    'style' => "background-image: url(" . esc_url( $img_accordion[ 'eael_accordion_bg' ][ 'url' ] ) . ");" . $activeCSS,
                ]
            );
            if ( $active === 'yes' ) {
                $this->add_render_attribute( 'eael-image-accordion-link-' . $key, 'class', 'overlay-active' );
            }
            ?>

            <<?php echo $tag.' '; ?><?php echo $this->get_render_attribute_string( 'eael-image-accordion-link-' . $key ), $target,$nofollow,$attributes; ?>  tabindex="<?php echo $key; ?>">
            <div class="overlay">
                <div class="overlay-inner">
                    <div class="overlay-inner <?php echo( $active === 'yes' ? ' overlay-inner-show' : '' ); ?>">
                        <?php
                        if ( !empty( $img_accordion[ 'eael_accordion_tittle' ] ) ):
                            printf( '<%1$s class="img-accordion-title">%2$s</%1$s>', Helper::eael_validate_html_tag($settings[ 'title_tag' ]), Helper::eael_wp_kses($img_accordion[ 'eael_accordion_tittle' ]) );
                        endif;

                        if ( !empty( $img_accordion[ 'eael_accordion_content' ] ) ):
                        ?>
                            <p><?php echo sprintf( "%s", $this->parse_text_editor( $img_accordion[ 'eael_accordion_content' ] ) ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            </<?php echo $tag; ?>>
        <?php endforeach; ?>
        </div>
        <?php
        if ( !empty( $settings[ 'eael_img_accordions' ] ) ) {
            if ( 'on-hover' === $settings[ 'eael_img_accordion_type' ] ) {
                echo '<style typr="text/css">
                    #eael-img-accordion-' . $this->get_id() . ' .eael-image-accordion-hover:hover {
                        flex: 3 1 0% !important;
                    }
                    #eael-img-accordion-' . $this->get_id() . ' .eael-image-accordion-hover:hover:hover .overlay-inner * {
                        opacity: 1;
                        visibility: visible;
                        transform: none;
                        transition: all .3s .3s;
                    }
                </style>';
            }
        }
    }
}

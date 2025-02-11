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
use Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;


class Creative_Button extends Widget_Base
{

    public function get_name()
    {
        return 'eael-creative-button';
    }

    public function get_title()
    {
        return esc_html__('Creative Button', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-creative-button';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'button',
            'ea button',
            'creative button',
            'ea creative button',
            'cta',
            'call to action',
            'ea',
            'marketing button',
            'essential addons',
        ];
    }

    protected function is_dynamic_content():bool {
        return false;
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/creative-buttons/';
    }

    protected function register_controls()
    {

        if ( !apply_filters( 'eael/pro_enabled', false ) ) {
            // Content Controls
            $this->start_controls_section(
                'eael_section_creative_button_content',
                [
                    'label' => esc_html__('Button Content', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'creative_button_text',
                [
                    'label'       => __('Button Text', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'label_block' => true,
                    'default'     => 'Click Me!',
                    'placeholder' => __('Enter button text', 'essential-addons-for-elementor-lite'),
                    'title'       => __('Enter button text here', 'essential-addons-for-elementor-lite'),
                    'ai' => [
                        'active' => false,
                    ],
                ]
            );

            $this->add_control(
                'creative_button_secondary_text',
                [
                    'label'       => __('Button Secondary Text', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'label_block' => true,
                    'default'     => 'Go!',
                    'placeholder' => __('Enter button secondary text', 'essential-addons-for-elementor-lite'),
                    'title'       => __('Enter button secondary text here', 'essential-addons-for-elementor-lite'),
                    'ai' => [
                        'active' => false,
                    ],
                ]
            );

            $this->add_control(
                'creative_button_link_url',
                [
                    'label'         => esc_html__('Link URL', 'essential-addons-for-elementor-lite'),
                    'type'          => Controls_Manager::URL,
                    'dynamic'               => [
                        'active'       => true,
                        'categories'   => [
                            TagsModule::POST_META_CATEGORY,
                            TagsModule::URL_CATEGORY,
                        ],
                    ],
                    'label_block'   => true,
                    'default'       => [
                        'url'         => '#',
                        'is_external' => '',
                    ],
                    'show_external' => true,
                ]
            );

            $this->add_control(
                'eael_creative_button_icon_new',
                [
                    'label'            => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                    'type'             => Controls_Manager::ICONS,
                    'fa4compatibility' => 'eael_creative_button_icon',
                    'condition'        => [
                        'creative_button_effect!' => ['eael-creative-button--tamaya'],
                    ],
                ]
            );

            $this->add_control(
                'eael_creative_button_remove_svg_color_dummy',
                [
                    'label'        => esc_html__( 'Remove Default SVG Color', 'essential-addons-for-elementor-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'description'  => esc_html__( 'If you are using a custom SVG and want to apply colors from the controller, enable this option. Note that it will override the default color of your SVG.', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'eael_creative_button_icon_alignment',
                [
                    'label'     => esc_html__('Icon Position', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'left',
                    'options'   => [
                        'left'  => esc_html__('Before', 'essential-addons-for-elementor-lite'),
                        'right' => esc_html__('After', 'essential-addons-for-elementor-lite'),
                    ],
                    'condition' => [
                        'eael_creative_button_icon_new!' => '',
                        'creative_button_effect!'        => ['eael-creative-button--tamaya'],
                    ],
                ]
            );

            $this->add_responsive_control(
                'eael_creative_button_icon_indent',
                [
                    'label'     => esc_html__('Icon Spacing', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'max' => 60,
                        ],
                    ],
                    'condition' => [
                        'eael_creative_button_icon_new!' => '',
                        'creative_button_effect!'        => ['eael-creative-button--tamaya'],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button-icon-right' => 'margin-left: {{SIZE}}px;',
                        '{{WRAPPER}} .eael-creative-button-icon-left'  => 'margin-right: {{SIZE}}px;',
                        '{{WRAPPER}} .eael-creative-button--shikoba i' => 'left: {{SIZE}}%;',
                    ],
                ]
            );

            $this->end_controls_section();
        } else {
            do_action('eael_creative_button_pro_controls', $this);
        }

        if ( !apply_filters( 'eael/pro_enabled', false ) ) {
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
                            'icon'  => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default'     => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        // Style Controls
        $this->start_controls_section(
            'eael_section_creative_button_settings',
            [
                'label' => esc_html__('Button Effects &amp; Styles', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        if (!apply_filters('eael/pro_enabled', false)) {
            $this->add_control(
                'creative_button_effect',
                [
                    'label'       => esc_html__('Set Button Effect', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'eael-creative-button--default',
                    'options'     => [
                        'eael-creative-button--default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--winona'  => esc_html__('Winona', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--ujarak'  => esc_html__('Ujarak', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--wayra'   => esc_html__('Wayra', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--tamaya'  => esc_html__('Tamaya', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--rayen'   => esc_html__('Rayen', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--pipaluk' => esc_html__('Pipaluk (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--moema'   => esc_html__('Moema (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--wave'    => esc_html__('Wave (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--aylen'   => esc_html__('Aylen (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--saqui'   => esc_html__('Saqui (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--wapasha' => esc_html__('Wapasha (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--nuka'    => esc_html__('Nuka (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--antiman' => esc_html__('Antiman (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--quidel'  => esc_html__('Quidel (Pro)', 'essential-addons-for-elementor-lite'),
                        'eael-creative-button--shikoba' => esc_html__('Shikoba (Pro)', 'essential-addons-for-elementor-lite'),
                    ],
                    'description' => '10 more effects on <a href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor">Pro version</a>',
                ]
            );
            $this->add_control(
                'use_gradient_background',
                [
                    'label'        => __('Use Gradient Background', 'essential-addons-for-elementor-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default'      => '',
                ]
            );
            $this->start_controls_tabs('eael_creative_button_tabs');

            $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

	        $this->add_control('eael_creative_button_icon_color',
                [
                    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button .creative-button-inner svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );

	        $this->add_control(
		        'eael_creative_button_text_color',
		        [
			        'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
			        'type'      => Controls_Manager::COLOR,
			        'default'   => '#ffffff',
			        'selectors' => [
				        '{{WRAPPER}} .eael-creative-button'                                         => 'color: {{VALUE}};',
				        '{{WRAPPER}} .eael-creative-button svg'                                     => 'fill: {{VALUE}};',
				        '{{WRAPPER}} .eael-creative-button .eael-creative-button--tamaya-secondary' => 'color: {{VALUE}};',
			        ],
		        ]
	        );
            $this->add_control(
                'eael_creative_button_background_color',
                [
                    'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#f54',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button'                                      => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak:hover'   => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover'    => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after'  => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'use_gradient_background' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'eael_creative_button_gradient_background',
                    'types'     => ['gradient', 'classic'],
                    'selector'  => '
						{{WRAPPER}} .eael-creative-button,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak:hover,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after
					',
                    'condition' => [
                        'use_gradient_background' => 'yes',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'eael_creative_button_border',
                    'selector' => '{{WRAPPER}} .eael-creative-button',
                ]
            );

            $this->add_control(
                'eael_creative_button_border_radius',
                [
                    'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::SLIDER,
                    'range'     => [
                        'px' => [
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button'         => 'border-radius: {{SIZE}}px;',
                        '{{WRAPPER}} .eael-creative-button::before' => 'border-radius: {{SIZE}}px;',
                        '{{WRAPPER}} .eael-creative-button::after'  => 'border-radius: {{SIZE}}px;',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab('eael_creative_button_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

	        $this->add_control('eael_creative_button_hover_icon_color',
                [
                    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button:hover i' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button:hover .creative-button-inner svg' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'eael_creative_button_hover_text_color',
                [
                    'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button:hover .cretive-button-text' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover::before' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'eael_creative_button_hover_background_color',
                [
                    'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#f54',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button:hover'                                     => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak::before'      => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover::before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya:hover'        => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before'       => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover::before'       => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'use_gradient_background' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'      => 'eael_creative_button_hover_gradient_background',
                    'types'     => ['gradient', 'classic'],
                    'selector'  => '
						{{WRAPPER}} .eael-creative-button:hover,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak::before,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover::before,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya:hover,
						{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before,
                        {{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover::before
					',
                    'condition' => [
                        'use_gradient_background' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_creative_button_hover_border_color',
                [
                    'label'     => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-creative-button:hover'                                 => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--wapasha::before' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::before' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::before' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::before'  => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'eael_creative_button_alignment',
                [
                    'label'       => esc_html__('Button Alignment', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::CHOOSE,
                    'label_block' => true,
                    'options'     => [
                        'flex-start' => [
                            'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center'     => [
                            'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'flex-end'   => [
                            'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'default'     => '',
                    'selectors'   => [
                        '{{WRAPPER}} .eael-creative-button-wrapper' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'eael_creative_button_width',
                [
                    'label'      => esc_html__('Width', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-creative-button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'eael_creative_button_typography',
                    'global' => [
	                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .eael-creative-button .cretive-button-text, {{WRAPPER}} .eael-creative-button--winona::after, {{WRAPPER}} .eael-creative-button--rayen::before, {{WRAPPER}} .eael-creative-button--tamaya::after, {{WRAPPER}} .eael-creative-button--tamaya::before',
                ]
            );

            $this->add_responsive_control(
                'eael_creative_button_icon_size',
                [
                    'label'      => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default'    => [
                        'size' => 30,
                        'unit' => 'px',
                    ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-creative-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
	                    '{{WRAPPER}} .eael-creative-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
	            ]
            );

            $this->add_responsive_control(
                'eael_creative_button_padding',
                [
                    'label'      => esc_html__('Button Padding', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-creative-button'                                                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after'                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona > .creative-button-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before'                  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before'                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen > .creative-button-inner'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui::after'                    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        } else {
            do_action('eael_creative_button_style_pro_controls', $this);
        }

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .eael-creative-button',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $icon_migrated = isset($settings['__fa4_migrated']['eael_creative_button_icon_new']);
        $icon_is_new = empty($settings['eael_creative_button_icon']);

        $this->add_render_attribute('eael_creative_button', [
            'class' => ['eael-creative-button', esc_attr($settings['creative_button_effect'])],
        ]);

        if ( ! empty( $settings['creative_button_link_url']['url'] ) ) {
          $this->add_link_attributes( 'eael_creative_button', $settings['creative_button_link_url'] );
        }

        if ($settings['creative_button_link_url']['is_external']) {
            $this->add_render_attribute('eael_creative_button', 'target');
        }

        if ( $settings['creative_button_link_url']['nofollow'] ) {
            $this->add_render_attribute('eael_creative_button', 'rel', 'nofollow');
        }

        if ( isset( $settings['eael_creative_button_remove_svg_color'] ) && 'yes' === $settings['eael_creative_button_remove_svg_color'] ) {
            $this->add_render_attribute('eael_creative_button', 'class', 'csvg-use-color');
        }

        $this->add_render_attribute('eael_creative_button', 'data-text', esc_attr($settings['creative_button_secondary_text']));
        ?>
        <div class="eael-creative-button-wrapper">

            <a <?php $this->print_render_attribute_string('eael_creative_button'); ?>>

	    <?php if ($settings['creative_button_effect'] === 'eael-creative-button--tamaya' ) : ?>
            <div class="eael-creative-button--tamaya-secondary eael-creative-button--tamaya-before"><span><?php echo wp_kses( $settings['creative_button_secondary_text'], Helper::eael_allowed_tags() ); ?></span></div>
        <?php endif; ?>

                <div class="creative-button-inner">

                    <?php if ($settings['creative_button_effect'] !== 'eael-creative-button--tamaya' && $settings['eael_creative_button_icon_alignment'] == 'left') : ?>
                        <?php if ($icon_migrated || $icon_is_new) {
		                    echo '<span class="eael-creative-button-icon-left">';
		                    Icons_Manager::render_icon( $settings['eael_creative_button_icon_new'], [ 'aria-hidden' => 'true' ] );
		                    echo '</span>';
                         } else { ?>
                            <i class="<?php echo esc_attr($settings['eael_creative_button_icon']); ?> eael-creative-button-icon-left" aria-hidden="true"></i>
                        <?php } ?>
                    <?php endif; ?>

                    <span class="cretive-button-text"><?php echo wp_kses( $settings['creative_button_text'], Helper::eael_allowed_tags() ); ?></span>

                    <?php if ($settings['creative_button_effect'] !== 'eael-creative-button--tamaya' && $settings['eael_creative_button_icon_alignment'] == 'right') : ?>
                        <?php if ($icon_migrated || $icon_is_new) {
                            echo '<span class="eael-creative-button-icon-right">';
		                    Icons_Manager::render_icon( $settings['eael_creative_button_icon_new'], [ 'aria-hidden' => 'true' ] );
		                    echo '</span>';
                        } else { ?>
                            <i class="<?php echo esc_attr($settings['eael_creative_button_icon']); ?> eael-creative-button-icon-right" aria-hidden="true"></i>
                        <?php } ?>
                    <?php endif; ?>
                </div>
	            <?php if ($settings['creative_button_effect'] === 'eael-creative-button--tamaya' ) : ?>
                    <div class="eael-creative-button--tamaya-secondary eael-creative-button--tamaya-after"><span><?php echo wp_kses( $settings['creative_button_secondary_text'], Helper::eael_allowed_tags() ); ?></span></div>
	            <?php endif; ?>
            </a>
        </div>
        <?php

    }
}

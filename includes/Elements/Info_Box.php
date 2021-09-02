<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Info_Box extends Widget_Base
{
    public function get_name()
    {
        return 'eael-info-box';
    }

    public function get_title()
    {
        return esc_html__('Info Box', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-info-box';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'info',
            'ea infobox',
            'ea info box',
            'box',
            'ea box',
            'info box',
            'card',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/info-box/';
    }

    protected function _register_controls()
    {

        /**
         * Infobox Image Settings
         */
        $this->start_controls_section(
            'eael_section_infobox_content_settings',
            [
                'label' => esc_html__('Infobox Image', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_img_type',
            [
                'label' => esc_html__('Infobox Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'img-on-top',
                'label_block' => false,
                'options' => [
                    'img-on-top' => esc_html__('Image/Icon On Top', 'essential-addons-for-elementor-lite'),
                    'img-on-left' => esc_html__('Image/Icon On Left', 'essential-addons-for-elementor-lite'),
                    'img-on-right' => esc_html__('Image/Icon On Right', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_img_or_icon',
            [
                'label' => esc_html__('Image or Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-ban',
                    ],
                    'number' => [
                        'title' => esc_html__('Number', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-sort-numeric-desc',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-info-circle',
                    ],
                    'img' => [
                        'title' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-picture-o',
                    ],
                ],
                'default' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'icon_vertical_position',
            [
                'label' => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top',
                'condition' => [
                    'eael_infobox_img_type!' => 'img-on-top',
                ],
                'options' => [
                    'top' => [
                        'title' => __('Top', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __('Middle', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon' => 'align-self: {{VALUE}};',
                ],
                'selectors_dictionary' => [
                    'top' => 'baseline',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
            ]
        );

        /**
         * Condition: 'eael_infobox_img_or_icon' => 'img'
         */
        $this->add_control(
            'eael_infobox_image',
            [
                'label' => esc_html__('Infobox Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'eael_infobox_img_or_icon' => 'img',
                ],
            ]
        );

        /**
         * Condition: 'eael_infobox_img_or_icon' => 'icon'
         */
        $this->add_control(
            'eael_infobox_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_infobox_icon',
                'default' => [
                    'value' => 'fas fa-building',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_infobox_img_or_icon' => 'icon',
                ],
            ]
        );

        /**
         * Condition: 'eael_infobox_img_or_icon' => 'number'
         */
        $this->add_control(
            'eael_infobox_number',
            [
                'label' => esc_html__('Number', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'condition' => [
                    'eael_infobox_img_or_icon' => 'number',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Infobox Content
         */
        $this->start_controls_section(
            'eael_infobox_content',
            [
                'label' => esc_html__('Infobox Content', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_infobox_title',
            [
                'label' => esc_html__('Infobox Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('This is an icon box', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_infobox_title_tag',
            [
                'label' => __('Select Title Tag', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
                    'h1' => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2' => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3' => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4' => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5' => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6' => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p' => __('P', 'essential-addons-for-elementor-lite'),
                    'div' => __('Div', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_infobox_text_type',
            [
                'label' => __('Content Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => __('Content', 'essential-addons-for-elementor-lite'),
                    'template' => __('Saved Templates', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'content',
            ]
        );

        $this->add_control(
            'eael_primary_templates',
            [
                'label' => __('Choose Template', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => Helper::get_elementor_templates(),
                'condition' => [
                    'eael_infobox_text_type' => 'template',
                ],
            ]
        );
        $this->add_control(
            'eael_infobox_text',
            [
                'label' => esc_html__('Infobox Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__('Write a short description, that will describe the title or something informational and useful.', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_infobox_text_type' => 'content',
                ],
            ]
        );
        $this->add_control(
            'eael_show_infobox_content',
            [
                'label' => __('Show Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );
        $this->add_responsive_control(
            'eael_infobox_content_alignment',
            [
                'label' => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'eael-infobox-content-align-',
                'condition' => [
                    'eael_infobox_img_type' => 'img-on-top',
                ],
            ]
        );

        $this->add_control(
            'content_height',
            [
                'label' => esc_html__('Content Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['max' => 300],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .infobox-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * ----------------------------------------------
         * Infobox Button
         * ----------------------------------------------
         */
        $this->start_controls_section(
            'eael_infobox_button',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_show_infobox_button',
            [
                'label' => __('Show Infobox Button', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_show_infobox_clickable!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_show_infobox_clickable',
            [
                'label' => __('Infobox Clickable', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'condition' => [
                    'eael_show_infobox_button!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_show_infobox_clickable_link',
            [
                'label' => esc_html__('Infobox Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => 'http://',
                    'is_external' => '',
                ],
                'show_external' => true,
                'condition' => [
                    'eael_show_infobox_clickable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'infobox_button_text',
            [
                'label'       => __('Button Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
					'active' => true
				],
                'label_block' => true,
                'default' => 'Click Me!',
                'separator' => 'before',
                'placeholder' => __('Enter button text', 'essential-addons-for-elementor-lite'),
                'title' => __('Enter button text here', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_show_infobox_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'infobox_button_link_url',
            [
                'label'         => __('Link URL', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'label_block'   => true,
                'placeholder'   => __('Enter link URL for the button', 'essential-addons-for-elementor-lite'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                ],
                'title' => __('Enter heading for the button', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_show_infobox_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_infobox_button_icon',
                'condition' => [
                    'eael_show_infobox_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_icon_alignment',
            [
                'label' => esc_html__('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Before', 'essential-addons-for-elementor-lite'),
                    'right' => esc_html__('After', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_infobox_button_icon_new!' => '',
                    'eael_show_infobox_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_icon_indent',
            [
                'label' => esc_html__('Icon Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'condition' => [
                    'eael_infobox_button_icon_new!' => '',
                    'eael_show_infobox_button' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael_infobox_button_icon_right' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .eael_infobox_button_icon_left' => 'margin-right: {{SIZE}}px;',
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
                    'label' => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => '',
                            'icon'  => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style (Info Box Image)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_infobox_imgae_style_settings',
            [
                'label' => esc_html__('Image Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_infobox_img_or_icon' => 'img',
                ],
            ]
        );

        $this->start_controls_tabs('eael_infobox_image_style');

        $this->start_controls_tab(
            'eael_infobox_image_icon_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_image_icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon img' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_image_icon_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_image_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon img',
            ]
        );

        $this->add_control(
            'eael_infobox_img_shape',
            [
                'label' => esc_html__('Image Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'square',
                'label_block' => false,
                'options' => [
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-shape-',
                'condition' => [
                    'eael_infobox_img_or_icon' => 'img',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_infobox_image_icon_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_infobox_image_icon_hover_shadow',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon:hover img' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_image_icon_hover_animation',
            [
                'label' => esc_html__('Animation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_hover_image_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon img',
            ]
        );

        $this->add_control(
            'eael_infobox_hover_img_shape',
            [
                'label' => esc_html__('Image Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'square',
                'label_block' => false,
                'options' => [
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-hover-img-shape-',
                'condition' => [
                    'eael_infobox_img_or_icon' => 'img',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'eael_infobox_image_resizer',
            [
                'label' => esc_html__('Image Resizer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon img' => 'width: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-infobox.icon-on-left .infobox-icon' => 'width: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-infobox.icon-on-right .infobox-icon' => 'width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'condition' => [
                    'eael_infobox_image[url]!' => '',
                    'eael_infobox_img_or_icon' => 'img',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_infobox_img_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Info Box Number Icon Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_infobox_number_icon_style_settings',
            [
                'label' => esc_html__('Number Icon Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_infobox_img_or_icon' => 'number',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_infobox_number_icon_typography',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-number',
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_number_icon_bg_size',
            [
                'label' => __('Icon Background Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 90,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_number_icon_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_infobox_numbericon_style_controls');

        $this->start_controls_tab(
            'eael_infobox_number_icon_normal',
            [
                'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-number' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox.icon-beside-title .infobox-content .title figure .infobox-icon-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_bg_shape',
            [
                'label' => esc_html__('Background Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'label_block' => false,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-icon-bg-shape-',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_number_icon_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_infobox_number_icon_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_infobox_number_icon_hover',
            [
                'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_hover_animation',
            [
                'label' => esc_html__('Animation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_hover_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-number' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox.icon-beside-title:hover .infobox-content .title figure .infobox-icon-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_number_icon_hover_bg_shape',
            [
                'label' => esc_html__('Background Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'label_block' => false,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-icon-hover-bg-shape-',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_hover_number_icon_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_infobox_number_icon_hover_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Info Box Icon Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_infobox_icon_style_settings',
            [
                'label' => esc_html__('Icon Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_infobox_img_or_icon' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-infobox .infobox-icon svg' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap img' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_icon_bg_size',
            [
                'label' => __('Icon Background Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 90,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_icon_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_infobox_icon_style_controls');

        $this->start_controls_tab(
            'eael_infobox_icon_normal',
            [
                'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_icon_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox .infobox-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox.icon-beside-title .infobox-content .title figure i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_icon_bg_shape',
            [
                'label' => esc_html__('Background Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'label_block' => false,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-icon-bg-shape-',
            ]
        );

        $this->add_control(
            'eael_infobox_icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_icon_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_infobox_icon_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-icon-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_infobox_icon_hover',
            [
                'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_infobox_icon_hover_animation',
            [
                'label' => esc_html__('Animation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_control(
            'eael_infobox_icon_hover_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox:hover .infobox-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-infobox.icon-beside-title:hover .infobox-content .title figure i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_icon_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
                    'eael_infobox_icon_bg_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_icon_hover_bg_shape',
            [
                'label' => esc_html__('Background Shape', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'label_block' => false,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'circle' => esc_html__('Circle', 'essential-addons-for-elementor-lite'),
                    'radius' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                    'square' => esc_html__('Square', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-infobox-icon-hover-bg-shape-',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_hover_icon_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_infobox_icon_hover_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox:hover .infobox-icon-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style ( Info Box Button Style )
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_infobox_button_settings',
            [
                'label' => esc_html__('Button Styles', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_infobox_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_infobox_button_typography',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-button .eael-infobox-button .infobox-button-text',
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_button_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-button .eael-infobox-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-infobox .infobox-button .eael-infobox-button img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_creative_button_padding',
            [
                'label' => esc_html__('Button Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-button a.eael-infobox-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-button a.eael-infobox-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs('infobox_button_styles_controls_tabs');

        $this->start_controls_tab('infobox_button_normal', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_infobox_button_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .eael-infobox-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .eael-infobox-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_button_border',
                'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('infobox_button_hover', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_infobox_button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_infobox_button_hover_border',
                'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eael-infobox .eael-infobox-button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Info Box Title Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_infobox_title_style_settings',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('infobox_content_hover_style_tab');

        $this->start_controls_tab('infobox_content_normal_style', [
            'label' => esc_html__('Normal', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_infobox_title_heading',
            [
                'label' => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_infobox_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_infobox_title_typography',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-content .title',
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_title_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'eael_infobox_content_heading',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_content_margin',
            [
                'label' => esc_html__('Content Only Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_content_background',
            [
                'label' => esc_html__('Content Only Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_infobox_content_only_padding',
            [
                'label' => esc_html__('Content Only Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_content_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox .infobox-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_infobox_content_typography_hover',
                'selector' => '{{WRAPPER}} .eael-infobox .infobox-content p',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('infobox_content_hover_style', [
            'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_infobox_title_hover_color',
            [
                'label' => esc_html__('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-content .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_content_hover_color',
            [
                'label' => esc_html__('Content Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_infobox_content_transition',
            [
                'label' => esc_html__('Transition', 'essential-addons-for-elementor-lite'),
                'description' => esc_html__('Transition will applied to ms (ex: 300ms).', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'min' => 100,
                'max' => 1000,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .eael-infobox:hover .infobox-content h4' => 'transition: {{SIZE}}ms;',
                    '{{WRAPPER}} .eael-infobox:hover .infobox-content p' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

	/**
	 * This function is responsible for rendering divs and contents
	 * for infobox before partial.
	 */
    protected function eael_infobox_before()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('eael_infobox_inner', 'class', 'eael-infobox');

        if ('img-on-left' == $settings['eael_infobox_img_type']) {
            $this->add_render_attribute('eael_infobox_inner', 'class', 'icon-on-left');
        }

        if ('img-on-right' == $settings['eael_infobox_img_type']) {
            $this->add_render_attribute('eael_infobox_inner', 'class', 'icon-on-right');
        }

        $target = !empty($settings['eael_show_infobox_clickable_link']['is_external']) ? 'target="_blank"' : '';
        $nofollow = !empty($settings['eael_show_infobox_clickable_link']['nofollow']) ? 'rel="nofollow"' : '';

        ob_start();
        ?>
        <?php if ('yes' == $settings['eael_show_infobox_clickable']): ?><a href="<?php echo esc_url($settings['eael_show_infobox_clickable_link']['url']) ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php endif;?>
            <div <?php echo $this->get_render_attribute_string('eael_infobox_inner'); ?>>
            <?php
        echo ob_get_clean();
    }

	/**
	 * This function is rendering closing divs and tags
	 * of before partial for infobox.
	 */
    protected function eael_infobox_after()
    {
        $settings = $this->get_settings_for_display();
        ob_start(); ?></div><?php
if ('yes' == $settings['eael_show_infobox_clickable']): ?></a><?php endif;
        echo ob_get_clean();
    }

	/**
	 * This function is rendering appropriate icon for infobox.
	 */
    protected function render_infobox_icon()
    {
        $settings = $this->get_settings_for_display();

        if ('none' == $settings['eael_infobox_img_or_icon']) {
            return;
        }

        $infobox_image = $this->get_settings('eael_infobox_image');
        $infobox_image_url = Group_Control_Image_Size::get_attachment_image_src($infobox_image['id'], 'thumbnail', $settings);
        if (empty($infobox_image_url)){
	        $infobox_image_url = $infobox_image['url'];
        }

        $infobox_icon_migrated = isset($settings['__fa4_migrated']['eael_infobox_icon_new']);
        $infobox_icon_is_new = empty($settings['eael_infobox_icon']);

        $this->add_render_attribute(
            'infobox_icon',
            [
                'class' => ['infobox-icon'],
            ]
        );

        if ($settings['eael_infobox_icon_hover_animation']) {
            $this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_icon_hover_animation']);
        }

        if ($settings['eael_infobox_image_icon_hover_animation']) {
            $this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_image_icon_hover_animation']);
        }

        if ($settings['eael_infobox_number_icon_hover_animation']) {
            $this->add_render_attribute('infobox_icon', 'class', 'elementor-animation-' . $settings['eael_infobox_number_icon_hover_animation']);
        }

        if ('icon' == $settings['eael_infobox_img_or_icon']) {
            $this->add_render_attribute('infobox_icon', 'class', 'eael-icon-only');
        }

        if ($infobox_icon_is_new || $infobox_icon_migrated) {
            $icon = $this->get_settings('eael_infobox_icon_new')['value'];
            if (isset($icon['url'])) {
	            ob_start();
	            Icons_Manager::render_icon( $settings['eael_infobox_icon_new'], [ 'aria-hidden' => 'true' ] );
	            $icon_tag = ob_get_clean();
            } else {
                $this->add_render_attribute('icon_or_image', 'class', $icon);
                $icon_tag = '<i ' . $this->get_render_attribute_string('icon_or_image') . '></i>';
            }
        } else {
            $icon_tag = '<i class="' . esc_attr($settings['eael_infobox_icon']) . '"></i>';
        }

        ob_start();
        ?>
        <div <?php echo $this->get_render_attribute_string('infobox_icon'); ?>>

            <?php if ('img' == $settings['eael_infobox_img_or_icon']): ?>
                <img src="<?php echo esc_url($infobox_image_url); ?>" alt="<?php echo esc_attr(get_post_meta($infobox_image['id'], '_wp_attachment_image_alt', true)); ?>">
            <?php endif;?>

            <?php if ('icon' == $settings['eael_infobox_img_or_icon']): ?>
                <div class="infobox-icon-wrap">
                    <?php echo $icon_tag; ?>
                </div>
            <?php endif;?>

            <?php if ('number' == $settings['eael_infobox_img_or_icon']): ?>
                <div class="infobox-icon-wrap">
                    <span class="infobox-icon-number"><?php echo esc_attr($settings['eael_infobox_number']); ?></span>
                </div>
            <?php endif;?>

        </div>
    <?php
        echo ob_get_clean();
    }

    protected function render_infobox_content()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('infobox_content', 'class', 'infobox-content');
        if ('icon' == $settings['eael_infobox_img_or_icon']) {
            $this->add_render_attribute('infobox_content', 'class', 'eael-icon-only');
        }

        ob_start();
        ?>
        <div <?php echo $this->get_render_attribute_string('infobox_content'); ?>>
            <<?php echo $settings['eael_infobox_title_tag']; ?> class="title"><?php echo $settings['eael_infobox_title']; ?></<?php echo $settings['eael_infobox_title_tag']; ?>>
            <?php if ('yes' == $settings['eael_show_infobox_content']): ?>
                <?php if ('content' === $settings['eael_infobox_text_type']): ?>
                    <?php if (!empty($settings['eael_infobox_text'])): ?>
                        <p><?php echo $settings['eael_infobox_text']; ?></p>
                    <?php endif;?>
                    <?php $this->render_infobox_button();?>
                <?php elseif ('template' === $settings['eael_infobox_text_type']):
            if (!empty($settings['eael_primary_templates'])) {
                echo Plugin::$instance->frontend->get_builder_content($settings['eael_primary_templates'], true);
            }
        endif;?>
            <?php endif;?>
        </div>
    <?php

        echo ob_get_clean();
    }

	/**
	 * This function rendering infobox button
	 */
    protected function render_infobox_button()
    {
        $settings = $this->get_settings_for_display();
        if ('yes' == $settings['eael_show_infobox_clickable'] || 'yes' != $settings['eael_show_infobox_button']) {
            return;
        }

        $button_icon_migrated = isset($settings['__fa4_migrated']['eael_infobox_button_icon_new']);
        $button_icon_is_new = empty($settings['eael_infobox_button_icon']);

        $this->add_render_attribute('infobox_button', 'class', 'eael-infobox-button');

        if ($settings['infobox_button_link_url']['url']) {
            $this->add_render_attribute('infobox_button', 'href', esc_url($settings['infobox_button_link_url']['url']));
        }

        if ('on' == $settings['infobox_button_link_url']['is_external']) {
            $this->add_render_attribute('infobox_button', 'target', '_blank');
        }

        if ('on' == $settings['infobox_button_link_url']['nofollow']) {
            $this->add_render_attribute('infobox_button', 'rel', 'nofollow');
        }

        ob_start();
        ?>
        <div class="infobox-button">
            <a <?php echo $this->get_render_attribute_string('infobox_button'); ?>>
                <?php if ('left' == $settings['eael_infobox_button_icon_alignment']): ?>
                    <?php if ($button_icon_is_new || $button_icon_migrated) {?>
                        <?php if (isset($settings['eael_infobox_button_icon_new']['value']['url'])) {?>
                            <img class="eael_infobox_button_icon_left" src="<?php echo esc_attr($settings['eael_infobox_button_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_infobox_button_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                        <?php } else {?>
                            <i class="<?php echo esc_attr($settings['eael_infobox_button_icon_new']['value']); ?> eael_infobox_button_icon_left"></i>
                        <?php }?>
                    <?php } else {?>
                        <i class="<?php echo esc_attr($settings['eael_infobox_button_icon']); ?>"></i>
                    <?php }?>
                <?php endif;?>
                <span class="infobox-button-text"><?php echo esc_attr($settings['infobox_button_text']); ?></span>
                <?php if ('right' == $settings['eael_infobox_button_icon_alignment']): ?>
                    <?php if ($button_icon_is_new || $button_icon_migrated) {?>
                        <?php if (isset($settings['eael_infobox_button_icon_new']['value']['url'])) {?>
                            <img class="eael_infobox_button_icon_right" src="<?php echo esc_attr($settings['eael_infobox_button_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_infobox_button_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                        <?php } else {?>
                            <i class="<?php echo esc_attr($settings['eael_infobox_button_icon_new']['value']); ?> eael_infobox_button_icon_right"></i>
                        <?php }?>
                    <?php } else {

            if ('left' == $settings['eael_infobox_button_icon_alignment']) {
                $this->add_render_attribute('button_icon', 'class', 'eael_infobox_button_icon_left');
            }

            if ('right' == $settings['eael_infobox_button_icon_alignment']) {
                $this->add_render_attribute('button_icon', 'class', 'eael_infobox_button_icon_right');
            }

            $this->add_render_attribute(
                'button_icon',
                [
                    'class' => [
                        'eael_infobox_button_icon_right',
                        $settings['eael_infobox_button_icon'],
                    ],
                ]
            );
            ?>
                        <i <?php echo $this->get_render_attribute_string('button_icon'); ?>></i>
                    <?php }?>
                <?php endif;?>
            </a>
        </div>
<?php
echo ob_get_clean();
    }

    protected function render()
    {
        $this->eael_infobox_before();
        $this->render_infobox_icon();
        $this->render_infobox_content();
        $this->eael_infobox_after();
    }
}

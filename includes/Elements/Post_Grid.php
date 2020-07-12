<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography;
use \Elementor\Widget_Base;

class Post_Grid extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Template\Content\Post_Grid;

    public function get_name()
    {
        return 'eael-post-grid';
    }

    public function get_title()
    {
        return __('Post Grid', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-post-grid';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_keywords()
    {
        return [
            'post',
            'posts',
            'grid',
            'ea post grid',
            'ea posts grid',
            'blog post',
            'article',
            'custom posts',
            'masonry',
            'content views',
            'blog view',
            'content marketing',
            'blogger',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/post-grid/';
    }

    protected function _register_controls()
    {
        /**
         * Query And Layout Controls!
         * @source includes/elementor-helper.php
         */
        $this->eael_query_controls();
        $this->eael_layout_controls();

        /**
         * Grid Style Controls!
         */
        $this->start_controls_section(
            'section_post_grid_links',
            [
                'label' => __('Links', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'image_link',
            [
                'label'     => __('Image', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'image_link_nofollow',
            [
                'label'        => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'image_link_target_blank',
            [
                'label'        => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link',
            [
                'label'     => __('Title', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_link_nofollow',
            [
                'label'        => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link_target_blank',
            [
                'label'        => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link',
            [
                'label'     => __('Read More', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_read_more_button' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_link_nofollow',
            [
                'label'        => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link_target_blank',
            [
                'label'        => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition'    => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Grid Style Controls!
         */
        $this->start_controls_section(
            'eael_section_post_grid_style',
            [
                'label' => __('Post Grid Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_post_grid_preset_style',
            [
                'label'   => __('Select Style', 'essential-addons-for-elementor-lite'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''      => __('Default', 'essential-addons-for-elementor-lite'),
                    'two'   => __('Style Two', 'essential-addons-for-elementor-lite'),
                    'three' => __('Style Three', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_style_three_alert',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => __('Make sure to enable <strong>Show Date</strong> option from <strong>Layout Settings</strong>', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
                'condition'       => [
                    'eael_post_grid_preset_style' => ['two', 'three'],
                    'eael_show_date'              => '',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_bg_color',
            [
                'label'     => __('Post Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-holder' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_spacing',
            [
                'label'      => esc_html__('Spacing Between Items', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-grid-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_post_grid_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-grid-post-holder',
            ]
        );

        $this->add_control(
            'eael_post_grid_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-holder' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_post_grid_box_shadow',
                'selector' => '{{WRAPPER}} .eael-grid-post-holder',
            ]
        );

        $this->end_controls_section();

        /**
         * Thumbnail style
         */

        $this->start_controls_section(
            'eael_section_post_grid_thumbnail_style',
            [
                'label' => __('Thumbnail Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_thumbnail_radius',
            [
                'label'     => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-post-grid .eael-grid-post .eael-entry-media img, {{WRAPPER}} .eael-grid-post .eael-entry-overlay' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style tab: Meta Date style
         */
        $this->start_controls_section(
            'section_meta_date_style',
            [
                'label'     => __('Meta Date Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_meta'              => 'yes',
                    'eael_post_grid_preset_style' => ['three'],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'eael_post_grid_meta_date_background',
                'label'    => __('Background', 'essential-addons-for-elementor-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-meta-posted-on',
            ]
        );
        $this->add_control(
            'eael_post_grid_meta_date_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-meta-posted-on' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_post_grid_meta_date_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .eael-meta-posted-on',
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_date_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-meta-posted-on' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_post_grid_meta_date_shadow',
                'label'     => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-meta-posted-on',
                'condition' => [
                    'eael_post_grid_preset_style' => ['three'],
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab: Meta Date Position
         */
        $this->eael_controls_custom_positioning(
            'eael_meta_date_position_',
            __('Meta Date Position', 'essential-addons-for-elementor-lite'),
            '.eael-meta-posted-on',
            [
                'eael_show_meta'              => 'yes',
                'eael_post_grid_preset_style' => ['three'],
            ]
        );

        /**
         * Style tab: Meta Style
         */
        $this->start_controls_section(
            'section_meta_style_style',
            [
                'label'     => __('Meta Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_post_grid_preset_style!' => 'three',
                    'eael_show_meta'               => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_post_grid_meta_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-meta, .eael-entry-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_meta_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'     => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end'   => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-footer, {{WRAPPER}} .eael-grid-post .eael-entry-meta' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_post_grid_meta_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
                'selector'  => '{{WRAPPER}} .eael-entry-meta > span',
                'condition' => [
                    'meta_position' => 'meta-entry-footer',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_post_grid_meta_header_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
                'selector'  => '{{WRAPPER}} .eael-entry-meta > span',
                'condition' => [
                    'meta_position' => 'meta-entry-header',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-entry-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'meta_position' => 'meta-entry-header',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_footer_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-entry-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'meta_position' => 'meta-entry-footer',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab: Meta Position
         */
        $this->eael_controls_custom_positioning(
            'eael_meta_footer_',
            __('Meta Position', 'essential-addons-for-elementor-lite'),
            '.eael-grid-post .eael-entry-footer',
            [
                'eael_show_meta'               => 'yes',
                'meta_position'                => ['meta-entry-footer'],
                'eael_post_grid_preset_style!' => 'three',
            ]
        );

        $this->eael_controls_custom_positioning(
            'eael_meta_header_',
            __('Meta Position', 'essential-addons-for-elementor-lite'),
            '.eael-grid-post .eael-entry-meta',
            [
                'eael_show_meta'               => 'yes',
                'meta_position'                => ['meta-entry-header'],
                'eael_post_grid_preset_style!' => 'three',
            ]
        );

        /**
         * Color, Typography & Spacing
         */
        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __('Color, Typography, alignment & Spacing', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_title_style',
            [
                'label'     => __('Title Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_post_grid_title_color',
            [
                'label'     => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#303133',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title a' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_post_grid_title_hover_color',
            [
                'label'     => __('Title Hover Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#23527c',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title:hover, {{WRAPPER}} .eael-entry-title a:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_title_alignment',
            [
                'label'     => __('Title Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_post_grid_title_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-entry-title',
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_title_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_excerpt_style',
            [
                'label'     => __('Excerpt Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_post_grid_excerpt_color',
            [
                'label'     => __('Excerpt Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_excerpt_alignment',
            [
                'label'     => __('Excerpt Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_post_grid_excerpt_typography',
                'label'    => __('Excerpt Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .eael-grid-post-excerpt p',
            ]
        );

        $this->add_control(
            'content_height',
            [
                'label'      => esc_html__('Content Height', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => ['max' => 300],
                    '%'  => ['max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-grid-post-holder .eael-entry-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_excerpt_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style tab: terms style
         */
        $this->start_controls_section(
            'section_meta_terms_style',
            [
                'label'     => __('Terms Style', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_post_grid_preset_style' => 'two',
                    'eael_show_post_terms'        => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_post_grid_terms_color',
            [
                'label'     => __('Terms Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .post-meta-categories li, {{WRAPPER}} .post-meta-categories li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_terms_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'     => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end'   => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .post-meta-categories' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_post_grid_terms_typography',
                'label'    => __('Meta Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .post-meta-categories li, {{WRAPPER}} .post-meta-categories li a',
            ]
        );

        $this->add_control(
            'eael_post_carousel_terms_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .post-meta-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // terms style
        $this->start_controls_section(
            'section_terms_style',
            [
                'label'     => __('Terms', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_post_terms'        => 'yes',
                    'eael_post_grid_preset_style' => '',
                ],
            ]
        );

        $this->add_control(
            'terms_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .post-carousel-categories li a, {{WRAPPER}} .post-carousel-categories li:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'terms_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .post-carousel-categories li a',
            ]
        );

        $this->add_responsive_control(
            'terms_color_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-carousel-categories' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'terms_spacing',
            [
                'label'      => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .post-carousel-categories li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Card Hover
        $this->start_controls_section(
            'eael_section_hover_card_styles',
            [
                'label' => __('Hover Card Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_animation',
            [
                'label'   => esc_html__('Animation', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fade-in',
                'options' => [
                    'none'     => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'fade-in'  => esc_html__('FadeIn', 'essential-addons-for-elementor-lite'),
                    'zoom-in'  => esc_html__('ZoomIn', 'essential-addons-for-elementor-lite'),
                    'slide-up' => esc_html__('SlideUp', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_bg_hover_icon_new',
            [
                'label'            => __('Post Hover Icon', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_post_grid_bg_hover_icon',
                'default'          => [
                    'value'   => 'fas fa-long-arrow-alt-right',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'eael_post_grid_hover_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_bg_color',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0, .75)',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'eael_post_grid_hover_bg_radius',
            [
                'label'     => esc_html__('Cards Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-post-grid .eael-grid-post .eael-entry-media .eael-entry-overlay' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_icon_color',
            [
                'label'     => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_hover_icon_fontsize',
            [
                'label'      => __('Icon font size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Read More Button Style Controls
         */
        $this->eael_read_more_button_style();

        /**
         * Load More Button Style Controls!
         */
        $this->eael_load_more_button_style();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $settings = $this->fix_old_query($settings);
        $args = $this->eael_get_query_args($settings);

        $settings_arry = [
            'title_tag'                      => $settings['title_tag'],
            'eael_show_image'                => $settings['eael_show_image'],
            'image_size'                     => $settings['image_size'],
            'eael_show_title'                => $settings['eael_show_title'],
            'eael_show_excerpt'              => $settings['eael_show_excerpt'],
            'eael_show_meta'                 => $settings['eael_show_meta'],
            'meta_position'                  => $settings['meta_position'],
            'eael_excerpt_length'            => intval($settings['eael_excerpt_length'], 10),
            'eael_post_grid_hover_animation' => $settings['eael_post_grid_hover_animation'],
            'eael_post_grid_bg_hover_icon'   => (isset($settings['__fa4_migrated']['eael_post_grid_bg_hover_icon_new']) || empty($settings['eael_post_grid_bg_hover_icon'])) ? $settings['eael_post_grid_bg_hover_icon_new']['value'] : $settings['eael_post_grid_bg_hover_icon'],
            'eael_show_read_more_button'     => $settings['eael_show_read_more_button'],
            'read_more_button_text'          => $settings['read_more_button_text'],
            'read_more_button_text'          => $settings['read_more_button_text'],
            'show_load_more'                 => $settings['show_load_more'],
            'show_load_more_text'            => $settings['show_load_more_text'],
            'excerpt_expanison_indicator'    => $settings['excerpt_expanison_indicator'],
            'layout_mode'                    => $settings['layout_mode'],
            'orderby'                        => $settings['orderby'],
            'eael_show_post_terms'           => $settings['eael_show_post_terms'],
            'eael_post_terms'                => $settings['eael_post_terms'],
            'eael_post_terms_max_length'     => $settings['eael_post_terms_max_length'],
            'eael_show_avatar'               => $settings['eael_show_avatar'],
            'eael_show_author'               => $settings['eael_show_author'],
            'eael_show_date'                 => $settings['eael_show_date'],
            'title_link_nofollow'            => $settings['title_link_nofollow'],
            'title_link_target_blank'        => $settings['title_link_target_blank'],
            'read_more_link_nofollow'        => $settings['read_more_link_nofollow'],
            'read_more_link_target_blank'    => $settings['read_more_link_target_blank'],
            'image_link_nofollow'            => $settings['image_link_nofollow'],
            'image_link_target_blank'        => $settings['image_link_target_blank'],
            'eael_title_length'              => $settings['eael_title_length'],
            'eael_post_grid_preset_style'    => $settings['eael_post_grid_preset_style'],
        ];

        $this->add_render_attribute(
            'post_grid_wrapper',
            [
                'id'    => 'eael-post-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-post-grid-container',
                ],
            ]
        );

        $this->add_render_attribute(
            'post_grid_container',
            [
                'class' => [
                    'eael-post-grid',
                    'eael-post-appender',
                    'eael-post-appender-' . $this->get_id(),
                    'eael-post-grid-style-' . ($settings['eael_post_grid_preset_style'] !== "" ? $settings['eael_post_grid_preset_style'] : 'default'),
                ],
            ]
        );

        echo '<div ' . $this->get_render_attribute_string('post_grid_wrapper') . '>
            <div ' . $this->get_render_attribute_string('post_grid_container') . ' data-layout-mode="' . $settings["layout_mode"] . '">
                ' . self::render_template_($args, $settings_arry) . '
            </div>
            <div class="clearfix"></div>
        </div>';

        if ('yes' == $settings['show_load_more']) {
            if ($args['posts_per_page'] != '-1') {
                echo '<div class="eael-load-more-button-wrap">
					<button class="eael-load-more-button" id="eael-load-more-btn-' . $this->get_id() . '" data-widget="' . $this->get_id() . '" data-class="' . get_class($this) . '" data-args="' . http_build_query($args) . '" data-settings="' . http_build_query($settings_arry) . '" data-layout="' . $settings['layout_mode'] . '" data-page="1">
						<div class="eael-btn-loader button__loader"></div>
						<span>' . esc_html__($settings['show_load_more_text'], 'essential-addons-for-elementor-lite') . '</span>
					</button>
				</div>';
            }
        }

        if (\Elementor\Plugin::instance()->editor->is_edit_mode()) { ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    jQuery(".eael-post-grid").each(function() {
                        var $scope = jQuery(".elementor-element-<?php echo $this->get_id(); ?>"),
                            $gallery = $(this);
                        $layout_mode = $gallery.data('layout-mode');

                        if ($layout_mode === 'masonry') {
                            // init isotope
                            var $isotope_gallery = $gallery.isotope({
                                itemSelector: ".eael-grid-post",
                                layoutMode: $layout_mode,
                                percentPosition: true
                            });

                            // layout gal, while images are loading
                            $isotope_gallery.imagesLoaded().progress(function() {
                                $isotope_gallery.isotope("layout");
                            });

                            $('.eael-grid-post', $gallery).resize(function() {
                                $isotope_gallery.isotope('layout');
                            });
                        }

                    });
                });
            </script>
<?php
        }
    }
}

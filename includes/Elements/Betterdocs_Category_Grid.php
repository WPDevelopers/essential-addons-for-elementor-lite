<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Box_Shadow;
use \Essential_Addons_Elementor\Classes\Controls;
use \Essential_Addons_Elementor\Classes\Helper;

class Betterdocs_Category_Grid extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Template_Query;

    public function get_name()
    {
        return 'eael-betterdocs-category-grid';
    }

    public function get_title()
    {
        return __('BetterDocs Category Grid', 'essential-addons-for-elementor-lite');
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_icon()
    {
        return 'eaicon-betterdocs-category-grid';
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 3.5.2
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return [
            'knowledgebase',
            'knowledge base',
            'documentation',
            'Doc',
            'kb',
            'betterdocs',
            'ea betterdocs',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/betterdocs-category-grid/';
    }

    protected function _register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*    Content Tab
        /*-----------------------------------------------------------------------------------*/
        if (!defined('BETTERDOCS_URL')) {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>BetterDocs</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=BetterDocs&tab=search&type=term" target="_blank">BetterDocs</a> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else {

            /**
             * Query  Controls!
             * @source includes/elementor-helper.php
             */
            do_action('eael/controls/betterdocs/query', $this);

            /**
             * ----------------------------------------------------------
             * Section: Layout Options
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'select_layout',
                [
                    'label' => __('Layout Options', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'layout_template',
                [
                    'label' => __('Select Layout', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT2,
                    'options'   => $this->template_options(),
                    'default' => $this->get_default(),
                    'label_block' => true
                ]
            );

            $this->add_control(
                'layout_mode',
                [
                    'label' => __('Layout Mode', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => [
                        'grid'  => __('Grid', 'essential-addons-for-elementor-lite'),
                        'fit-to-screen'  => __( 'Fit to Screen', 'essential-addons-for-elementor-lite' ),
                        'masonry' => __('Masonry', 'essential-addons-for-elementor-lite'),
                    ],
                    'default' => 'grid',
                    'label_block' => true,
                ]
            );

            $this->add_responsive_control(
                'grid_column',
                [
                    'label' => __('Grid Column', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'tablet_default' => '2',
                    'mobile_default' => '1',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'prefix_class' => 'elementor-grid%s-',
                    'frontend_available' => true,
                    'label_block' => true
                ]
            );

            $this->add_control(
                'show_header',
                [
                    'label' => __('Show Header', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                ]
            );
    

            $this->add_control(
                'show_icon',
                [
                    'label' => __('Show Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'condition' => [
                        'show_header'   => 'true'
                    ]
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => __('Show Title', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'condition' => [
                        'show_header'   => 'true'
                    ]
                ]
            );

            $this->add_control(
                'title_tag',
                [
                    'label' => __('Select Tag', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'h2',
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
                    'condition' => [
                        'show_title' => 'true',
                        'show_header'   => 'true'
                    ],
                ]
            );

            $this->add_control(
                'show_count',
                [
                    'label' => __('Show Count', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'condition' => [
                        'show_header'   => 'true'
                    ]
                ]
            );


            $this->add_control(
                'show_list',
                [
                    'label' => __('Show List', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                ]
            );

            $this->add_control(
                'show_button',
                [
                    'label' => __('Show Button', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true',
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => __('Explore More', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'show_button' => 'true',
                    ],
                ]
            );

            $this->end_controls_section(); #end of section 'Layout Options'

            /**
             * ----------------------------------------------------------
             * Section: Column Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_column_settings',
                [
                    'label' => __('Grid', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );


            $this->start_controls_tabs('grid_style_tab');

                // Normal State Tab
                $this->start_controls_tab(
                    'grid_normal',
                    ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name'     => 'grid_bg', // Legacy control id 'content_area_bg'
                        'types'    => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-post .eael-bd-cg-inner',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'grid_box_shadow',
                        'label' => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner',
                    ]
                );


                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'grid_border',
                        'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner',
                    ]
                );

                $this->add_responsive_control(
                    'grid_border_radius',
                    [
                        'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );

                $this->end_controls_tab();

                // Hover State Tab
                $this->start_controls_tab(
                    'grid_hover',
                    ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name'     => 'grid_bg_hover',
                        'types'    => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-post .eael-bd-cg-inner:hover',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'grid_hover_box_shadow',
                        'label' => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner:hover',
                    ]
                );


                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'grid_hover_border',
                        'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                        'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner:hover',
                    ]
                );

                $this->add_responsive_control(
                    'grid_hover_border_radius',
                    [
                        'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs(); # end of $this->start_controls_tabs('grid_style_tab');

            $this->add_responsive_control(
                'grid_padding',
                [
                    'label' => __( 'Grid Padding', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'column_padding', // Legacy control id
                [
                    'label' => __( 'Grid Spacing', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-better-docs-category-grid-wrapper .eael-bd-cg-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->end_controls_section(); # end of 'Column Settings'


            $this->start_controls_section(
                'section_icon_settings',
                [
                    'label' => __('Icon', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_icon'    => 'true',
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );

            $this->start_controls_tabs('icon_settings_tabs');

            // Normal State Tab
            $this->start_controls_tab(
                'icon_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'header_icon_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon',
                    'exclude'   => [
                        'image'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'header_icon_border', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon',
                ]
            );

            $this->add_control(
                'header_icon_border_radius',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );

            $this->end_controls_tab();

             // Hover State Tab
             $this->start_controls_tab(
                'icon_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'header_icon_bg_hover',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon:hover',
                    'exclude'   => [
                        'image'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'header_icon_border_hover', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon:hover',
                ]
            );

            $this->add_control(
                'header_icon_border_radius_hover',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'header_icon_size',
                [
                    'label' => __('Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'header_icon_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );

            $this->add_responsive_control(
                'header_icon_margin',
                [
                    'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header .eael-docs-cat-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Title Settinggs
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_title_settings',
                [
                    'label' => __('Title', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_title'    => 'true'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cat_list_typography',
                    'selector' => '{{WRAPPER}} .eael-docs-cat-title',
                ]
            );

            $this->start_controls_tabs('title_settings_tabs');

            // Normal State Tab
            $this->start_controls_tab(
                'title_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'cat_title_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-cat-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cat_title_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-post:not(.layout-2) .eael-bd-cg-header, {{WRAPPER}} .eael-better-docs-category-grid-post.layout-2 .eael-docs-cat-title',
                    'exclude'   => [
                        'image'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header-inner, {{WRAPPER}} .layout-2 .eael-docs-cat-title',
                ]
            );

            $this->add_control(
                'title_border_radius',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .layout-2 .eael-docs-cat-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-bd-cg-header'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-bd-cg-header .eael-bd-cg-header-inner'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );

            $this->end_controls_tab();

            // Hover State Tab
            $this->start_controls_tab(
                'title_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'cat_title_color_hover',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-cat-title:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cat_title_bg_hover',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-grid-post:not(.layout-2) .eael-bd-cg-header:hover, {{WRAPPER}} .eael-better-docs-category-grid-post.layout-2 .eael-docs-cat-title:hover',
                    'exclude'   => [
                        'image'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border_hover', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header-inner:hover, {{WRAPPER}} .layout-2 .eael-docs-cat-title:hover',
                ]
            );

            $this->add_control(
                'title_border_radius_hover',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .layout-2 .eael-docs-cat-title:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_2'
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();


            $this->add_responsive_control(
                'cat_title_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-better-docs-category-grid-post.layout-2 .eael-docs-cat-title'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'cat_title_margin',
                [
                    'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-better-docs-category-grid-post.layout-2 .eael-docs-cat-title'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Title Settings'


            /**
             * ----------------------------------------------------------
             * Section: Count Settinggs
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_count_settings',
                [
                    'label' => __('Count', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_count'    => 'true'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'count_font_size',
                    'selector' => '{{WRAPPER}} .eael-docs-item-count',
                ]
            );

            $this->add_responsive_control(
                'count_size',
                [
                    'label' => __('Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template!' => 'Layout_2'
                    ]
                ]
            );

            $this->start_controls_tabs( 'count_settings_tabs' );

            // Normal State Tab
            $this->start_controls_tab(
                'count_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'count_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-docs-item-count, {{WRAPPER}} .layout-2 .eael-docs-item-count:before',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_control(
                'count_ticker_color',
                [
                    'label' => esc_html__('Ticker Background', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .layout-2 .eael-docs-item-count:after' => 'border-top-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'count_border', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-docs-item-count',
                    'condition' => [
                        'layout_template!' => 'Layout_2'
                    ]
                ]
            );

            $this->add_control(
                'count_border_radius',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'layout_template!' => 'Layout_2'
                    ]
                ]
            );

            $this->end_controls_tab();

            // Hover State Tab
            $this->start_controls_tab(
                'count_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'count_transition',
                [
                    'label'                 => __( 'Transition', 'essential-addons-elementor' ),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '300',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 10000,
                            'step'  => 100,
                        ],
                    ],
                    'size_units'            => [ 'px' ],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-docs-item-count' => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-docs-item-count:after' => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-docs-item-count:after' => 'transition: {{SIZE}}ms;',
                    ]
                ]
            );

            $this->add_control(
                'count_color_hover',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_bg_hover',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-docs-item-count:hover, {{WRAPPER}} .layout-2 .eael-docs-item-count:hover:before',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_control(
                'count_ticker_color_hover',
                [
                    'label' => esc_html__('Ticker Background', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .layout-2 .eael-docs-item-count:hover:after' => 'border-top-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'count_border_hover', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-docs-item-count:hover',
                    'condition' => [
                        'layout_template!' => 'Layout_2'
                    ]
                ]
            );

            $this->add_control(
                'count_border_radius_hover',
                [
                    'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'layout_template!' => 'Layout_2'
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();


            $this->end_controls_section(); # end of 'Count Settings'

            /**
             * ----------------------------------------------------------
             * Section: List Settinggs
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_article_settings',
                [
                    'label' => __('List', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_list' => 'true'
                    ]
                ]
            );

            $this->add_control(
                'list_settings_heading',
                [
                    'label' => esc_html__('List', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'list_item_typography',
                    'selector' => '{{WRAPPER}} .eael-bd-cg-body ul li a',
                ]
            );

            $this->add_control(
                'list_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body ul li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'list_hover_color',
                [
                    'label' => esc_html__('Hover Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body ul li a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'list_margin',
                [
                    'label' => esc_html__('List Item Spacing', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'list_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-cg-body',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_responsive_control(
                'list_area_padding',
                [
                    'label' => esc_html__('List Area Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'allowed_dimensions' => 'vertical',
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_settings_heading',
                [
                    'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'list_icon',
                [
                    'label' => __( 'Icon', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::ICONS,
                    'default'   => [
                        'value'     => 'far fa-file-alt',
                        'library'   => 'fa-regular'
                    ]
                ]
            );

            $this->add_control(
                'list_icon_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body .eael-bd-cg-post-list-icon' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'list_icon_size',
                [
                    'label' => __('Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body .eael-bd-cg-post-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eael-bd-cg-body img.eael-bd-cg-post-list-icon' => 'width: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'list_icon_spacing',
                [
                    'label' => esc_html__('Spacing', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-body .eael-bd-cg-post-list-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Nested List Settinggs
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_nested_list_settings',
                [
                    'label' => __('Nested List', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'nested_subcategory' => 'true'
                    ]
                ]
            );

            $this->add_control(
                'section_nested_list_title',
                [
                    'label' => esc_html__('Title', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'nested_list_title_typography',
                    'selector' => '{{WRAPPER}} .eael-bd-grid-sub-cat-title a',
                ]
            );

            $this->add_control(
                'nested_list_title_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'nested_list_title_background',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-grid-sub-cat-title',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'nested_list_title_border',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-bd-grid-sub-cat-title'
                ]
            );

            $this->add_responsive_control(
                'nested_list_title_spacing',
                [
                    'label' => esc_html__('Spacing', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'section_nested_list_icon',
                [
                    'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'nested_list_title_closed_icon',
                [
                    'label' => __( 'Collapse Icon', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::ICONS,
                    'default'   => [
                        'value'     => 'fas fa-angle-right',
                        'library'   => 'fa-regular'
                    ]
                ]
            );

            $this->add_control(
                'nested_list_title_open_icon',
                [
                    'label' => __( 'Open Icon', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::ICONS,
                    'default'   => [
                        'value'     => 'fas fa-angle-down',
                        'library'   => 'fa-regular'
                    ]
                ]
            );

            $this->add_control(
                'nested_list_icon_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title .toggle-arrow' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'nested_list_icon_size',
                [
                    'label' => __('Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title .toggle-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title img.toggle-arrow' => 'width: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'nested_list_icon_margin',
                [
                    'label' => esc_html__('Area Spacing', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-grid-sub-cat-title .toggle-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );



            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Button Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_button_settings',
                [
                    'label' => __('Button', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_button'   => 'true'
                    ]
                ]
            );

            $this->add_control(
                'show_button_icon',
                [
                    'label' => __('Show Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => 'true'
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label' => __( 'Icon', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::ICONS,
                    'default'   => [
                        'value' => 'fas fa-angle-right',
                        'library'   => 'fa-solid'
                    ],
                    'condition' => [
                        'show_button_icon'  => 'true'
                    ]
                ]
            );

            $this->add_control(
                'icon_position',
                [
                    'label' => __('Icon Position', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'after',
                    'options' => [
                        'before' => __( 'Before', 'essential-addons-for-elementor-lite' ),
                        'after' => __( 'After', 'essential-addons-for-elementor-lite' )
                    ],
                    'condition' => [
                        'show_button_icon'  => 'true'
                    ]
                ]
            );

            $this->start_controls_tabs(
                'button_settings_tabs'
            );

            // Normal State Tab
            $this->start_controls_tab(
                'button_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography_normal',
                    'selector' => '{{WRAPPER}} .eael-bd-cg-button',
                ]
            );

            $this->add_control(
                'button_color_normal',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'button_background_normal',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-cg-button',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'button_border_normal',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-button',
                ]
            );

            $this->add_responsive_control(
                'button_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_area_margin',
                [
                    'label' => esc_html__('Area Spacing', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // Normal State Tab
            $this->start_controls_tab(
                'button_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'button_transition',
                [
                    'label'                 => __( 'Transition', 'essential-addons-elementor' ),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '300',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 10000,
                            'step'  => 100,
                        ],
                    ],
                    'size_units'            => [ 'px' ],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-bd-cg-button' => 'transition: {{SIZE}}ms;',
                    ]
                ]
            );

            $this->add_control(
                'button_color_hover',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'button_background_hover',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-bd-cg-button:hover',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'button_border_hover',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-button:hover',
                ]
            );

            $this->add_responsive_control(
                'button_hover_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'button_text_alignment',
                [
                    'label' => __('Text Alignment', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-footer .eael-bd-cg-button' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_alignment',
                [
                    'label' => __('Button Alignment', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-footer' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Button Settings'

        }

    }

    protected function render()
    {

        if (!defined('BETTERDOCS_URL')) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            'bd_category_grid_wrapper',
            [
                'id' => 'eael-bd-cat-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-better-docs-category-grid-wrapper',
                ],
            ]
        );

        $this->add_render_attribute(
            'bd_category_grid_inner',
            [
                'class' => [
                    'eael-better-docs-category-grid',
                    $settings['layout_mode']
                ],
                'data-layout-mode'  => $settings['layout_mode']
            ]
        );


        $terms_object = array(
            'taxonomy' => 'doc_category',
            'order' => $settings['order'],
            'orderby' => $settings['orderby'],
            'offset'    => $settings['offset'],
            'number'    => $settings['grid_per_page']
        );

        if ( $settings['include'] ) {
            $terms_object['include'] = array_diff($settings['include'], (array) $settings['exclude']);
        }

        if($settings['exclude']) {
            $terms_object['exclude'] =  $settings['exclude'];
        }

        $default_multiple_kb = Helper::get_betterdocs_multiple_kb_status();

        if ($settings['layout_template'] == 'Layout_2') {
            $settings['layout_template'] = 'layout-2';
        }
        
        if($default_multiple_kb) {

            $taxonomy_objects = Helper::get_multiple_kb_terms(false, false);
            
            $meta_query = '';

            if(!empty($settings['selected_knowledge_base'])){
                $terms_object['meta_query'] =  array(
                    array(
                        'relation' => 'OR',
                        array(
                            'key'       => 'doc_category_knowledge_base',
                            'value'     => $settings['selected_knowledge_base'],
                            'compare'   => 'LIKE'
                        )
                    ),
                );
            }

        
            $taxonomy_objects = get_terms( $terms_object );

            $html = '<div ' . $this->get_render_attribute_string('bd_category_grid_wrapper') . '>';
                $html .= '<div '.$this->get_render_attribute_string('bd_category_grid_inner').'>';
                if(file_exists($this->get_template($settings['layout_template']))) {

                    if($taxonomy_objects && ! is_wp_error( $taxonomy_objects )) {
                        foreach($taxonomy_objects as $term) {
                            echo Helper::include_with_variable($this->get_template($settings['layout_template']), ['term' => $term, 'settings' => $settings, 'default_multiple_kb' => $default_multiple_kb]);
                        }
                    }else {
                        _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
                    }

                    wp_reset_postdata();

                }else {
                    $html .= '<h4>'.__( 'File Not Found', 'essential-addons-for-elementor-lite' ).'</h4>';
                }
                $html .= '</div>';
                $html .= '<div class="clearfix"></div>';

                if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
                    $this->render_editor_script();
                }
            $html .= '</div>';

            echo $html;


        }else {
            $taxonomy_objects = get_terms($terms_object);

            $html = '<div ' . $this->get_render_attribute_string('bd_category_grid_wrapper') . '>';
                $html .= '<div '.$this->get_render_attribute_string('bd_category_grid_inner').'>';
                if(file_exists($this->get_template($settings['layout_template']))) {

                    if($taxonomy_objects && ! is_wp_error( $taxonomy_objects )) {
                        foreach($taxonomy_objects as $term) {
                            ob_start();
                                include($this->get_template($settings['layout_template']));
                            $html .= ob_get_clean(); 
                        }
                    }else {
                        _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
                    }

                    wp_reset_postdata();

                }else {
                    $html .= '<h4>'.__( 'File Not Found', 'essential-addons-for-elementor-lite' ).'</h4>';
                }
                $html .= '</div>';
                $html .= '<div class="clearfix"></div>';

                if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
                    $this->render_editor_script();
                }
            $html .= '</div>';

            echo $html;
        }
    
    }

    protected function render_editor_script()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.eael-better-docs-category-grid').each(function() {
                    var $scope = jQuery(".elementor-element-<?php echo $this->get_id(); ?>"),
                        $gallery = $(this);
                        $layout_mode = $gallery.data('layout-mode');


                    if($layout_mode === 'masonry') {
                        // init isotope
                        var $isotope_gallery = $gallery.isotope({
                                itemSelector: ".eael-better-docs-category-grid-post",
                                layoutMode: $layout_mode,
                                percentPosition: true
                            });

                        // layout gal, while images are loading
                        $isotope_gallery.imagesLoaded().progress(function() {
                            $isotope_gallery.isotope("layout");
                        });

                        $('.eael-better-docs-category-grid-post', $gallery).resize(function() {
                            $isotope_gallery.isotope('layout');
                        });
                    }

                });
            });
        </script>
        <?php
}

}

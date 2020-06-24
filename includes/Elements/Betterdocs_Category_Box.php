<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH'))
{
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;

class Betterdocs_Category_Box extends Widget_Base {

    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Traits\Template_Query;

    public function get_name()
    {
        return 'eael-betterdocs-category-box';
    }

    public function get_title()
    {
        return __('BetterDocs Category Box', 'essential-addons-for-elementor-lite');
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_icon()
    {
        return 'eaicon-betterdocs-category-box';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @return array Widget keywords.
     * @since 3.5.2
     * @access public
     *
     */
    public function get_keywords()
    {
        return [
            'knowledgebase',
            'knowledge Base',
            'documentation',
            'doc',
            'kb',
            'betterdocs',
            'ea betterdocs',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/betterdocs-category-box/';
    }

    protected function _register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*    Content Tab
        /*-----------------------------------------------------------------------------------*/
        if (!defined('BETTERDOCS_URL'))
        {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __('<strong>BetterDocs</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=BetterDocs&tab=search&type=term" target="_blank">BetterDocs</a> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else
        {

            /**
             * Query  Controls!
             * @source includes/elementor-helper.php
             */
            $this->eael_betterdocs_query_controls();

            /**
             * ----------------------------------------------------------
             * Section: Layout Options
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_layout_options',
                [
                    'label' => __('Layout Options', 'essential-addons-for-elementor-lite')
                ]
            );

            $this->add_control(
                'layout_template',
                [
                    'label'       => __('Select Layout', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $this->template_list(),
                    'default'     => $this->get_default(),
                    'label_block' => true
                ]
            );
            
            $this->add_responsive_control(
                'box_column',
                [
                    'label'              => __('Box Column', 'essential-addons-for-elementor-lite'),
                    'type'               => Controls_Manager::SELECT,
                    'default'            => '3',
                    'tablet_default'     => '2',
                    'mobile_default'     => '1',
                    'options'            => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4'
                    ],
                    'prefix_class'       => 'elementor-grid%s-',
                    'frontend_available' => true,
                    'label_block'        => true
                ]
            );

            $this->add_control(
                'show_icon',
                [
                    'label'        => __('Show Icon', 'essential-addons-for-elementor-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default'      => 'true'
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label'        => __('Show Title', 'essential-addons-for-elementor-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default'      => 'true'
                ]
            );

            $this->add_control(
                'title_tag',
                [
                    'label'     => __('Select Tag', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'h2',
                    'options'   => [
                        'h1'   => __('H1', 'essential-addons-for-elementor-lite'),
                        'h2'   => __('H2', 'essential-addons-for-elementor-lite'),
                        'h3'   => __('H3', 'essential-addons-for-elementor-lite'),
                        'h4'   => __('H4', 'essential-addons-for-elementor-lite'),
                        'h5'   => __('H5', 'essential-addons-for-elementor-lite'),
                        'h6'   => __('H6', 'essential-addons-for-elementor-lite'),
                        'span' => __('Span', 'essential-addons-for-elementor-lite'),
                        'p'    => __('P', 'essential-addons-for-elementor-lite'),
                        'div'  => __('Div', 'essential-addons-for-elementor-lite'),
                    ],
                    'condition' => [
                        'show_title' => 'true'
                    ],
                ]
            );

            $this->add_control(
                'show_count',
                [
                    'label'        => __('Show Count', 'essential-addons-for-elementor-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default'      => 'true'
                ]
            );

            $this->add_control(
                'count_prefix',
                [
                    'label'     => __('Prefix', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'show_count' => 'true',
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );

            $this->add_control(
                'count_suffix',
                [
                    'label'     => __('Suffix', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __('articles', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'show_count' => 'true',
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );


            $this->end_controls_section();

            /**
             * ----------------------------------------------------------
             * Section: Column Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_column_settings',
                [
                    'label' => __('Column', 'essential-addons-for-elementor-lite'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                'column_space', // Legacy control id but new control
                [
                    'label'      => __('Box Spacing', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'column_padding',
                [
                    'label'      => __('Box Padding', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Box Styles
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_card_settings',
                [
                    'label' => __('Box', 'essential-addons-for-elementor-lite'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->start_controls_tabs('card_settings_tabs');

            // Normal State Tab
            $this->start_controls_tab(
                'card_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'card_bg_normal',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner'
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'card_border_normal',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner'
                ]
            );

            $this->add_responsive_control(
                'card_border_radius_normal',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'card_box_shadow_normal',
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner'
                ]
            );

            $this->end_controls_tab();

            // Hover State Tab
            $this->start_controls_tab(
                'card_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'card_transition',
                [
                    'label'      => __('Transition', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => [
                        'size' => 300,
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range'      => [
                        '%' => [
                            'max'  => 2500,
                            'step' => 1,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner' => 'transition: {{SIZE}}ms;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'card_bg_hover',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover'
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'card_border_hover',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover'
                ]
            );

            $this->add_responsive_control(
                'card_border_radius_hover',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'card_box_shadow_hover',
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover'
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();
            $this->end_controls_section(); # end of 'Card Settings'


            /**
             * ----------------------------------------------------------
             * Section: Icon Styles
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_box_icon_style',
                [
                    'label' => __('Icon', 'essential-addons-for-elementor-lite'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'category_settings_area',
                [
                    'label' => __( 'Area', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING
                ]
            );

            $this->add_responsive_control(
                'category_settings_icon_area_size_normal',
                [
                    'label'      => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range'      => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2'    => 'flex-basis: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'category_settings_icon',
                [
                    'label' => __( 'Icon', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs('box_icon_styles_tab');

            // Normal State Tab
            $this->start_controls_tab(
                'icon_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_responsive_control(
                'category_settings_icon_size_normal',
                [
                    'label'      => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range'      => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon img' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2 img'    => 'width: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'icon_background_normal',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon, {{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2',
                    'exclude'  => [
                        'image'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'icon_border_normal',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon, {{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2'
                ]
            );

            $this->add_responsive_control(
                'icon_border_radius_normal',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );

            $this->add_responsive_control(
                'icon_padding',
                [
                    'label'              => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type'               => Controls_Manager::DIMENSIONS,
                    'size_units'         => ['px', 'em', '%'],
                    'selectors'          => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );

            $this->add_responsive_control(
                'icon_spacing',
                [
                    'label'              => esc_html__('Spacing', 'essential-addons-for-elementor-lite'),
                    'type'               => Controls_Manager::DIMENSIONS,
                    'size_units'         => ['px', 'em', '%'],
                    'allowed_dimensions' => [
                        'top',
                        'bottom'
                    ],
                    'selectors'          => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon' => 'margin: {{TOP}}{{UNIT}} auto {{BOTTOM}}{{UNIT}} auto;'
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_Default'
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
                    'name'     => 'icon_background_hover',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-icon,
                    {{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-icon__layout-2'
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'icon_border_hover',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-icon,
                    {{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-icon__layout-2'
                ]
            );

            $this->add_responsive_control(
                'icon_border_radius_hover',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template' => 'Layout_Default'
                    ]
                ]
            );

            $this->add_control(
                'category_settings_icon_size_transition',
                [
                    'label'      => __('Transition', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => [
                        'size' => 300,
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range'      => [
                        '%' => [
                            'max'  => 2500,
                            'step' => 1,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .eael-bd-cb-cat-icon'     => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .eael-bd-cb-cat-icon img' => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2'    => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-icon__layout-2 img'    => 'transition: {{SIZE}}ms;'
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();


            $this->end_controls_section(); # end of 'Icon Styles'


            /**
             * ----------------------------------------------------------
             * Section: Title Styles
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_box_title_styles',
                [
                    'label' => __('Title', 'essential-addons-for-elementor-lite'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'title_styles_area_heading',
                [
                    'label' => __( 'Area', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING
                ]
            );

            $this->add_responsive_control(
                'title_area_size',
                [
                    'label'      => esc_html__('Area Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range'      => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .layout__2 .eael-bd-cb-cat-title__layout-2'    => 'flex-basis: {{SIZE}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_control(
                'title_styles_heading',
                [
                    'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs('box_title_styles_tab');

            // Normal State Tab
            $this->start_controls_tab(
                'title_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'cat_title_color_normal',
                [
                    'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cb-inner .eael-bd-cb-cat-title' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .layout__2 .eael-bd-cb-cat-title__layout-2' => 'color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'cat_title_typography_normal',
                    'selector' => '{{WRAPPER}} .eael-bd-cb-inner .eael-bd-cb-cat-title, {{WRAPPER}} .layout__2 .eael-bd-cb-cat-title__layout-2'
                ]
            );

            $this->add_responsive_control(
                'title_spacing',
                [
                    'label'      => __('Spacing', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-bd-cb-inner .eael-bd-cb-cat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .layout__2 .eael-bd-cb-cat-title__layout-2 span'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cb-inner:hover .eael-bd-cb-cat-title' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-bd-cb-inner:hover .eael-bd-cb-cat-title__layout-2' => 'color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_control(
                'category_title_transition',
                [
                    'label'      => __('Transition', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => [
                        'size' => 300,
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range'      => [
                        '%' => [
                            'max'  => 2500,
                            'step' => 1,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-bd-cb-inner .eael-bd-cb-cat-title' => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .eael-bd-cb-inner:hover .eael-bd-cb-cat-title__layout-2'   => 'transition: {{SIZE}}ms;',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->end_controls_section(); # end of 'Icon Styles'

            /**
             * ----------------------------------------------------------
             * Section: Count Styles
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_box_count_styles',
                [
                    'label' => __('Count', 'essential-addons-for-elementor-lite'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'count_styles_area_heading',
                [
                    'label' => __( 'Area', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING
                ]
            );

            $this->add_responsive_control(
                'count_area_size',
                [
                    'label'      => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range'      => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .layout__2 .eael-bd-cb-cat-count__layout-2'    => 'flex-basis: {{SIZE}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_control(
                'count_styles_heading',
                [
                    'label' => __( 'Count', 'essential-addons-for-elementor-lite' ),
                    'type' =>   Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->start_controls_tabs('box_count_styles_tab');

            // Normal State Tab
            $this->start_controls_tab(
                'count_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'count_typography_normal',
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .eael-bd-cb-cat-count, {{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2'
                ]
            );

            $this->add_control(
                'count_color_normal',
                [
                    'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .eael-bd-cb-cat-count' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2' => 'color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'count_box_bg',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'count_box_border',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_box_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'count_box_box_shadow',
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_box_size',
                [
                    'label'      => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range'      => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .count-inner__layout-2'    => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_spacing',
                [
                    'label'      => __('Spacing', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner .eael-bd-cb-cat-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'layout_template!'   => 'Layout_2'
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
                'count_color_hover',
                [
                    'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .eael-bd-cb-cat-count' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .count-inner__layout-2'    => 'color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'count_box_bg_hover',
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'count_box_border_hover',
                    'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_responsive_control(
                'count_box_border_radius_hover',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .count-inner__layout-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'count_box_box_shadow_hover',
                    'selector' => '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-inner:hover .count-inner__layout-2',
                    'condition' => [
                        'layout_template'   => 'Layout_2'
                    ]
                ]
            );

            $this->add_control(
                'category_count_transition',
                [
                    'label'      => __('Transition', 'essential-addons-for-elementor-lite'),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => [
                        'size' => 300,
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range'      => [
                        '%' => [
                            'max'  => 2500,
                            'step' => 1,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-better-docs-category-box-post .eael-bd-cb-cat-count' => 'transition: {{SIZE}}ms;',
                        '{{WRAPPER}} .layout__2 .eael-bd-cb-cat-count__layout-2 .count-inner__layout-2' => 'transition: {{SIZE}}ms;',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->end_controls_section(); # end of 'Count Styles'

        }

    }

    protected function render()
    {
        if (!defined('BETTERDOCS_URL')) return;
        $settings = $this->get_settings_for_display();


        $this->add_render_attribute(
            'bd_category_box_wrapper',
            [
                'id'    => 'eael-bd-cat-box-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-better-docs-category-box-wrapper',
                ],
            ]
        );

        $this->add_render_attribute(
            'bd_category_box_inner',
            [
                'class' => [
                    'eael-better-docs-category-box'
                ]
            ]
        );


        $terms_object = array(
            'parent'   => 0,
            'taxonomy' => 'doc_category',
            'order'    => $settings['order'],
            'orderby'  => $settings['orderby'],
            'offset'   => $settings['offset'],
            'number'   => $settings['box_per_page']
        );

        if ($settings['include'])
        {
            unset($terms_object['parent']);
            $terms_object['include'] = array_diff($settings['include'], (array) $settings['exclude']);
            $terms_object['orderby'] = 'include';
        }

        if ($settings['exclude'])
        {
            unset($terms_object['parent']);
            $terms_object['exclude'] = $settings['exclude'];
            $terms_object['orderby'] = 'exclude';
        }

        $taxonomy_objects = get_terms($terms_object);

        $html = '<div ' . $this->get_render_attribute_string('bd_category_box_wrapper') . '>';
        $html .= '<div ' . $this->get_render_attribute_string('bd_category_box_inner') . '>';


        if (file_exists($this->get_template($settings['layout_template'])))
        {

            if ($taxonomy_objects && !is_wp_error($taxonomy_objects))
            {
                foreach ($taxonomy_objects as $term)
                {
                    ob_start();
                    include($this->get_template($settings['layout_template']));
                    $html .= ob_get_clean();
                }
            } else
            {
                _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
            }

            wp_reset_postdata();

        } else
        {
            $html .= '<h4>' . __('File Not Found', 'essential-addons-for-elementor-lite') . '</h4>';
        }

        $html .= '</div>';
        $html .= '</div>';

        echo $html;

    }

}

<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Elementor\Group_Control_Box_Shadow;

class Betterdocs_Category_Grid extends Widget_Base
{

    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Template\BetterDocs\Category_Grid;

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
            $this->eael_betterdocs_query_controls();

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
                    'options' => [
                        'default' => __('Default', 'essential-addons-for-elementor-lite'),
                    ],
                    'default' => 'default',
                    'label_block' => true,
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
                'grid',
                [
                    'label' => __('Grid', 'essential-addons-for-elementor-lite'),
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
                    'label' => __('Show Counter', 'essential-addons-for-elementor-lite'),
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
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-bd-cg-header-inner',
                ]
            );

            $this->add_responsive_control(
                'cat_title_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'allowed_dimensions' => 'vertical',
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-bd-cg-header-inner' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
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
                    'selector' => '{{WRAPPER}} .eael-docs-item-count',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_responsive_control(
                'count_font_size',
                [
                    'label' => __('Font Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-docs-item-count' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
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
                        '{{WRAPPER}} .eael-docs-item-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'count_border', // Legacy control name change it with 'border_size' if anything happens.
                    'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
                    'selector' => '{{WRAPPER}} .eael-docs-item-count',
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
                ]
            );

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
                    'label' => __( 'Closed Icon', 'essential-addons-for-elementor-lite' ),
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

            $this->start_controls_tabs('button_settings_tabs');

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

            $this->end_controls_section(); # end of 'Button Settings'

        }

    }

    protected static function get_doc_post_count($term_count = 0, $term_id) {
        $tax_terms = get_terms( 'doc_category', ['child_of' => $term_id]);

        foreach ($tax_terms as $tax_term) {
            $term_count += $tax_term->count;
        }
        return $term_count;
    }

    protected function render()
    {

        if (!defined('BETTERDOCS_URL')) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $args = $this->eael_get_query_args($settings, 'docs');

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

        $settings = [
            'include' => $settings['include'],
            'exclude' => $settings['exclude'],
            'grid_per_page' => $settings['grid_per_page'],
            'offset' => $settings['offset'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_per_page' => $settings['post_per_page'],
            'post_orderby' => $settings['post_orderby'],
            'post_order' => $settings['post_order'],
            'nested_subcategory' => $settings['nested_subcategory'],
            'show_header'   => $settings['show_header'],
            'show_icon' => $settings['show_icon'],
            'show_count' => $settings['show_count'],
            'show_title' => $settings['show_title'],
            'title_tag' => $settings['title_tag'],
            'show_list' => $settings['show_list'],
            'show_button' => $settings['show_button'],
            'button_text' => $settings['button_text'],
            'list_icon' => $settings['list_icon'],
            'nested_list_title_closed_icon' => $settings['nested_list_title_closed_icon'],
            'nested_list_title_open_icon'   => $settings['nested_list_title_open_icon']
        ];

        $html = '<div ' . $this->get_render_attribute_string('bd_category_grid_wrapper') . '>';
            $html .= '<div '.$this->get_render_attribute_string('bd_category_grid_inner').'>';

            $html .= self::render_template_($settings);

            $html .= '</div>';
            $html .= '<div class="clearfix"></div>';

            if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
                $this->render_editor_script();
            }
        $html .= '</div>';

        echo $html;
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

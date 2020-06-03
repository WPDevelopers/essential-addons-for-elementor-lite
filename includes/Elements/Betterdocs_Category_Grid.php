<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;

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
	public function get_keywords() {
		return [
            'knowledgebase',
            'knowledge base',
            'documentation',
            'Doc',
            'kb',
            'betterdocs',
            'ea betterdocs',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url() {
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
             * Section 'Layout Options'
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
                        'default'   => __( 'Default', 'essential-addons-for-elementor-lite' )
                    ],
                    'default'   => 'default',
                    'label_block' => true
                ]
            );

            $this->add_control(
                'nested_subcategory',
                [
                    'label' => __( 'Enable Nested Subcategory', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                    'label_off' => __( 'No', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'true',
                    'default' => ''
                ]
            );

            $this->add_control(
                'show_icon',
                [
                    'label' => __( 'Show Icon', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
                    'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'true',
                    'default' => 'true'
                ]
            );

            $this->add_control(
                'show_count',
                [
                    'label' => __( 'Show Counter', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
                    'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'true',
                    'default' => 'true'
                ]
            );

            $this->end_controls_section(); #end of section 'Layout Options'


            /**
             * Optional Controls!
             * @source includes/elementor-helper.php
             */
            $this->eael_better_docs_content_area_controls();

            /**
             * ----------------------------------------------------------
             * Section: Column Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_column_settings',
                [
                    'label' => __('Column', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE
                ]
            );

            $this->add_control(
                'column_settings_heading',
                [
                    'label' => esc_html__( 'Column', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_responsive_control(
                'column_padding',
                [
                    'label' => __('Column Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0px {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-item-container' => 'padding: 0px {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'category_settings_heading',
                [
                    'label' => esc_html__( 'Category', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                 'name' => 'cat_list_typography',
                    'selector' => '{{WRAPPER}} .docs-cat-title-inner h3, {{WRAPPER}}.betterdocs-category-box .docs-single-cat-wrap .docs-cat-title'
                ]
            );

            $this->add_control(
                'cat_title_color',
                [
                    'label' => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .docs-cat-title-inner h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap .docs-cat-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'border_size',
                [
                    'label' => __('Border Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'max' => 30,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .docs-cat-title-inner' => 'border-bottom: {{SIZE}}{{UNIT}} solid;',
                    ],
                ]
            );

            $this->add_control(
                'cat_title_border_color',
                [
                    'label' => esc_html__( 'Title Border Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .docs-cat-title-inner' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'count_settings_heading',
                [
                    'label' => esc_html__( 'Count', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'count_color',
                [
                    'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .docs-cat-title-inner .docs-item-count span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'count_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .docs-item-count, {{WRAPPER}} .docs-cat-title-inner span',
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
                        '{{WRAPPER}} .docs-cat-title-inner .docs-item-count span' => 'font-size: {{SIZE}}{{UNIT}};',
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
                        '{{WRAPPER}} .docs-cat-title-inner .docs-item-count span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Article Settinggs
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_article_settings',
                [
                    'label' => __('Article', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE
                ]
            );

            $this->add_control(
                'list_settings_heading',
                [
                    'label' => esc_html__( 'List', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                 'name' => 'list_item_typography',
                    'selector' => '{{WRAPPER}} .betterdocs-categories-wrap li a',
                ]
            );

            $this->add_control(
                'list_color',
                [
                    'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'list_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-item-container',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_responsive_control(
                'list_margin',
                [
                    'label' => esc_html__('List Margin', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-item-container li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-item-container' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_settings_heading',
                [
                    'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'list_icon_color',
                [
                    'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-item-container li i' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-item-container li i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    'tab' => Controls_Manager::TAB_STYLE
                ]
            );

            $this->start_controls_tabs( 'button_settings_tabs' );

                // Normal State Tab
                $this->start_controls_tab(
                    'button_normal',
                    [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ]
                );

                $this->add_control(
                    'button_color_normal',
                    [
                        'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-link-btn, {{WRAPPER}} .docs-cat-link-btn' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'button_background_normal',
                        'types' => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .docs-cat-link-btn, {{WRAPPER}} .docs-cat-link-btn',
                        'exclude'   => [
                            'image'
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'button_border_normal',
                        'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
                        'selector' => '{{WRAPPER}} .docs-cat-link-btn, {{WRAPPER}} .docs-cat-link-btn',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                     'name' => 'button_typography_normal',
                        'selector' => '{{WRAPPER}} .docs-cat-link-btn, {{WRAPPER}} .docs-cat-link-btn',
                    ]
                );

                $this->add_responsive_control(
                    'button_padding',
                    [
                        'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-link-btn, {{WRAPPER}} .docs-cat-link-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                // Normal State Tab
                $this->start_controls_tab(
                    'button_hover',
                    [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ]
                );

                $this->add_control(
                    'button_color_hover',
                    [
                        'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-link-btn:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'button_background_hover',
                        'types' => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .docs-cat-link-btn:hover',
                        'exclude'   => [
                            'image'
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'button_border_hover',
                        'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
                        'selector' => '{{WRAPPER}} .docs-cat-link-btn:hover'
                    ]
                );

                $this->end_controls_tab();
                
            $this->end_controls_tabs();

            $this->end_controls_section(); # end of 'Button Settings'


        }

    }

    protected function render()
    {

        if( ! defined('BETTERDOCS_URL') ) return;
        $settings = $this->get_settings_for_display();
        $args = $this->eael_get_query_args($settings, 'docs');

        $this->add_render_attribute(
            'bd_category_grid_wrapper',
            [
                'id' => 'eael-bd-cat-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-better-docs-category-grid-wrapper'
                ]
            ]
        );

        $html = '<div '.$this->get_render_attribute_string('bd_category_grid_wrapper').'>';
            $html .= '<div class="eael-better-docs-category-grid eael-post-appender eael-post-appender-' . $this->get_id() . '">';
                $html .= self::render_template_($args, []);
            $html .= '</div>';
            $html .= '<div class="clearfix"></div>';
        $html .= '</div>';

        echo $html;
    }

}

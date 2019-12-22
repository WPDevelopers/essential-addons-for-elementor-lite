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

class Betterdocs_Category_Box extends Widget_Base
{

    use \Essential_Addons_Elementor\Traits\Helper;

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
        return 'eicon-document-file';
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
		return [ 'better', 'doc', 'ea', 'betterdocs category box' ];
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

            $this->eael_betterdocs_content_controls();

            /**
             * ----------------------------------------------------------
             * Section: Column Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_column_settings',
                [
                    'label' => __('Column', 'essential-addons-for-elementor-lite'),
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
                'column_space',
                [
                    'label' => __('Column Space', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap' => 'margin: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'column_padding',
                [
                    'label' => __('Column Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_section(); # end of 'Column Settings'

            /**
             * ----------------------------------------------------------
             * Section: Card Settings
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_card_settings',
                [
                    'label' => __('Card', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->start_controls_tabs( 'card_settings_tabs' );

                // Normal State Tab
                $this->start_controls_tab(
                    'card_normal',
                    [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ]
                );
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'card_bg_normal',
                        'types' => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'card_border_normal',
                        'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap'
                    ]
                );

                $this->add_responsive_control(
                    'card_border_radius_normal',
                    [
                        'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                        ],
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'card_box_shadow_normal',
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap'
                    ]
                );

                $this->add_control(
                    'category_settings_icon_normal',
                    [
                        'label' => esc_html__( 'Category Icon', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );

                $this->add_control(
                    'category_settings_icon_size_normal',
                    [
                        'label' => esc_html__( 'Size', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SLIDER,
                        'size_units'    => [ 'px', '%', 'em' ],
                        'range' => [
                            'px' => [
                                'max' => 500,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_control(
                    'category_settings_heading_normal',
                    [
                        'label' => esc_html__( 'Category Title', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                     'name' => 'cat_title_typography_normal',
                        'selector' => '{{WRAPPER}} .docs-cat-title-inner h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap .docs-cat-title'
                    ]
                );
    
                $this->add_control(
                    'cat_title_color_normal',
                    [
                        'label' => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-title-inner h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap .docs-cat-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );
    
                $this->add_control(
                    'count_settings_heading_normal',
                    [
                        'label' => esc_html__( 'Count', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                     'name' => 'count_typography_normal',
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap span'
                    ]
                );
    
                $this->add_control(
                    'count_color_normal',
                    [
                        'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap span' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_tab();

                // Hover State Tab
                $this->start_controls_tab(
                    'card_hover',
                    [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ]
                );

                $this->add_responsive_control(
                    'card_transition',
                    [
                        'label' => __('Transition', 'essential-addons-for-elementor-lite'),
                        'type'  => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 300,
                            'unit' => '%',
                        ],
                        'size_units' => ['%'],
                        'range' => [
                            '%' => [
                                'max' => 2500,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap' => 'transition: {{SIZE}}ms;',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'card_bg_hover',
                        'types' => ['classic', 'gradient'],
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'card_border_hover',
                        'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover'
                    ]
                );

                $this->add_responsive_control(
                    'card_border_radius_hover',
                    [
                        'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                        ],
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'card_box_shadow_hover',
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover'
                    ]
                );

                $this->add_control(
                    'category_settings_icon_hover',
                    [
                        'label' => esc_html__( 'Category Icon', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );

                $this->add_control(
                    'category_settings_icon_size_hover',
                    [
                        'label' => esc_html__( 'Size', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SLIDER,
                        'size_units'    => [ 'px', '%', 'em' ],
                        'range' => [
                            'px' => [
                                'max' => 500,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap:hover img' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'category_settings_icon_size_transition',
                    [
                        'label' => __('Transition', 'essential-addons-for-elementor-lite'),
                        'type'  => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 300,
                            'unit' => '%',
                        ],
                        'size_units' => ['%'],
                        'range' => [
                            '%' => [
                                'max' => 2500,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap img' => 'transition: {{SIZE}}ms;',
                        ],
                    ]
                );

                $this->add_control(
                    'category_settings_heading_hover',
                    [
                        'label' => esc_html__( 'Category Title', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                     'name' => 'cat_title_typography_hover',
                        'selector' => '{{WRAPPER}} .docs-cat-title-inner:hover h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap:hover .docs-cat-title'
                    ]
                );
    
                $this->add_control(
                    'cat_title_color_hover',
                    [
                        'label' => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-title-inner:hover h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap:hover .docs-cat-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'category_title_transition',
                    [
                        'label' => __('Transition', 'essential-addons-for-elementor-lite'),
                        'type'  => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 300,
                            'unit' => '%',
                        ],
                        'size_units' => ['%'],
                        'range' => [
                            '%' => [
                                'max' => 2500,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .docs-cat-title-inner h3, {{WRAPPER}} .betterdocs-category-box .docs-single-cat-wrap .docs-cat-title' => 'transition: {{SIZE}}ms;',
                        ],
                    ]
                );
    
                $this->add_control(
                    'count_settings_heading_hover',
                    [
                        'label' => esc_html__( 'Count', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );
    
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                     'name' => 'count_typography_hover',
                        'selector' => '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap:hover span'
                    ]
                );
    
                $this->add_control(
                    'count_color_hover',
                    [
                        'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap:hover span' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'category_count_transition',
                    [
                        'label' => __('Transition', 'essential-addons-for-elementor-lite'),
                        'type'  => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 300,
                            'unit' => '%',
                        ],
                        'size_units' => ['%'],
                        'range' => [
                            '%' => [
                                'max' => 2500,
                                'step' => 1,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .betterdocs-categories-wrap.betterdocs-category-box .docs-single-cat-wrap span' => 'transition: {{SIZE}}ms;',
                        ],
                    ]
                );

                $this->end_controls_tab();

            $this->end_controls_tabs();
            $this->end_controls_section(); # end of 'Card Settings'

        }

    }

    protected function render()
    {
        if( ! defined('BETTERDOCS_URL') ) return;
        $settings = $this->get_settings_for_display();
        $shortcode  = sprintf('[betterdocs_category_box]', apply_filters('eael_betterdocs_category_box_params', []));

        echo do_shortcode( shortcode_unautop( $shortcode ) );
    }

	public function render_plain_content() {
		// In plain mode, render without shortcode
		echo '[betterdocs_category_box]';
	}

}

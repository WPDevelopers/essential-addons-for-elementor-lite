<?php

namespace Essential_Addons_Elementor\Elements;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use Elementor\Plugin;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Controls;

if (!defined('ABSPATH')) {
    exit;
} // If this file is called directly, abort.

class Facebook_Feed extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Facebook_Feed;

    public function get_name()
    {
        return 'eael-facebook-feed';
    }

    public function get_title()
    {
        return esc_html__('Facebook Feed', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-facebook-feed';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'facebook',
            'social media',
            'facebook embed',
            'facebook feed',
            'ea facebook feed',
            'fb feed',
            'ea fb feed',
            'facebook marketing',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/facebook-feed/';
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends()
    {
        return [
            'font-awesome-4-shim',
        ];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'eael_section_facebook_feed_settings_account',
            [
                'label' => esc_html__('Facebook Account Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_facebook_feed_page_id',
            [
                'label' => esc_html__('Page ID', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => '',
                'description' => __('<a href="https://findmyfbid.com/" class="eael-btn" target="_blank">Find Your Page ID</a>', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_facebook_feed_access_token',
            [
                'label' => esc_html__('Access Token', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
                'description' => __('<a href="https://essential-addons.com/elementor/docs/facebook-feed/" class="eael-btn" target="_blank">Get Access Token</a>', 'essential-addons-for-elementor-lite'),
            ]
        );

	    $this->add_control(
		    'eael_facebook_feed_cache_limit',
		    [
			    'label' => __('Data Cache Time', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::NUMBER,
			    'min' => 1,
			    'default' => 60,
			    'description' => __('Cache expiration time (Minutes)', 'essential-addons-for-elementor-lite')
		    ]
	    );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_facebook_feed_settings_content',
            [
                'label' => esc_html__('Feed Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_facebook_feed_sort_by',
            [
                'label' => esc_html__('Sort By', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'most-recent' => esc_html__('Newest', 'essential-addons-for-elementor-lite'),
                    'least-recent' => esc_html__('Oldest', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'most-recent',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_image_count',
            [
                'label' => esc_html__('Max Visible Items', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 12,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_facebook_feed_settings_general',
            [
                'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_facebook_feed_layout_heading',
            [
                'label' => __('Layout Settings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_facebook_feed_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'card' => esc_html__('Card', 'essential-addons-for-elementor-lite'),
                    'overlay' => esc_html__('Overlay', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'card',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_columns',
            [
                'label' => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-col-3',
                'options' => [
                    'eael-col-1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    'eael-col-2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    'eael-col-3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    'eael-col-4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    'eael-col-5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    'eael-col-6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_content_heading',
            [
                'label' => __('Content Settings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_facebook_feed_message',
            [
                'label' => esc_html__('Display Message', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_message_max_length',
            [
                'label' => esc_html__('Max Message Length', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_message' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_likes',
            [
                'label' => esc_html__('Display Like', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_comments',
            [
                'label' => esc_html__('Display Comments', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_date',
            [
                'label' => esc_html__('Display Date', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_link_target',
            [
                'label' => esc_html__('Open link in new window', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_preview_heading',
            [
                'label' => __('Preview Content Settings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_facebook_feed_is_show_preview_content',
            [
                'label' => __('Show Preview Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_is_show_preview_thumbnail',
            [
                'label' => __('Show Preview Thumbnail', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_is_show_preview_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_is_show_preview_host',
            [
                'label' => __('Show Preview Host Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_is_show_preview_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_is_show_preview_title',
            [
                'label' => __('Show Preview Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_is_show_preview_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_is_show_preview_description',
            [
                'label' => __('Show Preview Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_facebook_feed_is_show_preview_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_load_more_heading',
            [
                'label' => __('Pagination', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'show_load_more',
            [
                'label' => __('Show Load More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'yes' => [
                        'title' => __('Yes', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-check',
                    ],
                    'no' => [
                        'title' => __('No', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => 'no',
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => __('Label', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => __('Load More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_load_more' => ['yes', '1', 'true'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_facebook_feed_styles_general',
            [
                'label' => esc_html__('Feed Item Styles', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'eael_facebook_feed_is_gradient_bg',
            [
                'label' => __('Use Gradient Background?', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'eael_facebook_feed_spacing',
            [
                'label' => esc_html__('Space Between Items', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_facebook_feed_box_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item-inner',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#eee',
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-inner, {{WRAPPER}} .eael-facebook-feed-item-inner .eael-facebook-feed-item-overlay' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_facebook_feed_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item-inner',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-inner' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_facebook_feed_shadow',
                'label' => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item-inner',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_thumbnail_style',
            [
                'label' => __('Thumbnail Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );
        $this->add_control(
            'eael_facebook_feed_thumbnail_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item .eael-facebook-feed-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_header_style',
            [
                'label' => __('Header Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_header_layout',
            [
                'label' => __('Header Layout', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default Layout', 'essential-addons-for-elementor-lite'),
                    'two' => __('Layout Two', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_facebook_feed_header_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item-header',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_header_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2f6fd',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-header' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_header_spacing',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_content_style',
            [
                'label' => __('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_content_spacing',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_content_preview_spacing',
            [
                'label' => esc_html__('Preview Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-preview-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview' => 'padding-left: 0; padding-right: 0;',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_footer_style',
            [
                'label' => __('Footer Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_facebook_feed_footer_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_footer_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2f6fd',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                    'eael_facebook_feed_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_footer_spacing',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->end_controls_section();

        // item hover style
        $this->start_controls_section(
            'eael_section_facebook_feed_hover_style',
            [
                'label' => esc_html__('Feed Item Hover Styles', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_section_facebook_feed_hover_border_color',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item-inner:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_section_facebook_feed_hover_shadow',
                'label' => __('Box Shadow', 'plugin-domain'),
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item-inner:hover',
            ]
        );
        $this->add_control(
            'eael_section_facebook_feed_hover_header_style',
            [
                'label' => __('Header Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_section_facebook_feed_hover_header_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-header',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );
        $this->add_control(
            'eael_section_facebook_feed_hover_header_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-item-user .eael-facebook-feed-username' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-post-time' => 'color: {{VALUE}} !important',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_section_facebook_feed_hover_content_style',
            [
                'label' => __('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_section_facebook_feed_hover_content_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );
        $this->add_control(
            'eael_section_facebook_feed_hover_content_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-description' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-title' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-host' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-item-content .eael-facebook-feed-message' => 'color: {{VALUE}} !important',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_section_facebook_feed_hover_footer_style',
            [
                'label' => __('Footer Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_section_facebook_feed_hover_footer_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );
        $this->add_control(
            'eael_section_facebook_feed_hover_footer_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-item:hover .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->end_controls_section();

        // color & Typography
        $this->start_controls_section(
            'eael_section_facebook_feed_styles_content',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_page_name_heading',
            [
                'label' => __('Page Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_page_name_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-item-user .eael-facebook-feed-username',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_page_name_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#365899',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-item-user .eael-facebook-feed-username' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_date_heading',
            [
                'label' => __('Date', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_date_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-post-time',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_date_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-header .eael-facebook-feed-post-time' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_message_heading',
            [
                'label' => __('Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_message_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-content .eael-facebook-feed-message',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_message_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-content .eael-facebook-feed-message' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_message_link_color',
            [
                'label' => esc_html__('Link Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#365899',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-content .eael-facebook-feed-message a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_preview_host_heading',
            [
                'label' => __('Preview Host', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_preview_host_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-host',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_preview_host_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-host' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_preview_title_heading',
            [
                'label' => __('Preview Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_preview_title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-title',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_preview_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_preview_desc_heading',
            [
                'label' => __('Preview Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_preview_desc_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-description',
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_preview_desc_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-preview-wrap .eael-facebook-feed-url-preview .eael-facebook-feed-url-description' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'card',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_styles_likes_conmments_heading',
            [
                'label' => __('Likes & Comments', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_facebook_feed_likes_conmments_typography',
                'scheme' => Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .eael-facebook-feed-overlay .eael-facebook-feed-item .eael-facebook-feed-item-overlay, {{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer',
            ]
        );

        $this->add_control(
            'eael_facebook_feed_likes_conmments_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-card .eael-facebook-feed-item .eael-facebook-feed-item-inner .eael-facebook-feed-item-footer' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-facebook-feed-overlay .eael-facebook-feed-item .eael-facebook-feed-item-overlay' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-facebook-feed-overlay .eael-facebook-feed-item .eael-facebook-feed-item-overlay i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_facebook_feed_overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(86,20,213,0.8)',
                'selectors' => [
                    '{{WRAPPER}} .eael-facebook-feed-overlay .eael-facebook-feed-item .eael-facebook-feed-item-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_facebook_feed_layout' => 'overlay',
                ],
            ]
        );

        $this->end_controls_section();

        do_action('eael/controls/load_more_button_style', $this);
    }

    protected function render(){
        $settings = $this->get_settings_for_display();
        $post_id = 0;
        if (Plugin::$instance->documents->get_current()) {
            $post_id = Plugin::$instance->documents->get_current()->get_main_id();
        }
        $this->add_render_attribute('fb-wrap', [
            'class' => [
                "eael-facebook-feed",
                $settings['eael_facebook_feed_columns'],
                "eael-facebook-feed-{$settings['eael_facebook_feed_layout']}",
            ],
            'id' => 'eael-facebook-feed-' . esc_attr($this->get_id()),
        ]);
        $this->add_render_attribute('load-more', [
            'class' => "eael-load-more-button",
            'id' => "eael-load-more-btn-" . $this->get_id(),
            'data-widget-id' => $this->get_id(),
            'data-post-id' => $post_id,
            'data-page' => 1,
        ]);

        ?>
        <div <?php $this->print_render_attribute_string('fb-wrap'); ?>>
            <?php echo $this->facebook_feed_render_items($settings); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        if (($settings['show_load_more'] == 'yes')) { ?>
            <div class="eael-load-more-button-wrap">
                <button <?php $this->print_render_attribute_string('load-more'); ?>>
                    <div class="eael-btn-loader button__loader"></div>
                    <span><?php echo esc_html($settings['loadmore_text']); ?></span>
                </button>
            </div>
            <?php
        }

        if (Plugin::instance()->editor->is_edit_mode()) {
            echo '<script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(".eael-facebook-feed").each(function() {
                        var $node_id = "' . $this->get_id() . '",
                        $scope = $(".elementor-element-"+$node_id+""),
                        $settings = {
                            itemSelector: ".eael-facebook-feed-item",
                            percentPosition: true,
                            masonry: {
                                columnWidth: ".eael-facebook-feed-item",
                            }
                        };

                        // init isotope
                        $instagram_gallery = $(".eael-facebook-feed", $scope).isotope($settings);

                        // layout gal, while images are loading
                        $instagram_gallery.imagesLoaded().progress(function() {
                            $instagram_gallery.isotope("layout");
                        });
                    });
                });
            </script>';
        }
    }
}

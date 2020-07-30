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
use \Elementor\Widget_Base as Widget_Base;

class Twitter_Feed extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-twitter-feed';
    }

    public function get_title()
    {
        return esc_html__('Twitter Feed', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-twitter-feed';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }
    
    public function get_keywords() {
        return [
            'twitter',
            'ea twitter feed',
            'ea twitter gallery',
            'social media',
            'twitter embed',
            'twitter feed',
            'twitter marketing',
            'tweet feed',
            'tweet embed',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/twitter-feed/';
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
            'font-awesome-4-shim'
        ];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'eael_section_twitter_feed_acc_settings',
            [
                'label' => esc_html__('Account Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_twitter_feed_ac_name',
            [
                'label' => esc_html__('Account Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => '@wpdevteam',
                'label_block' => false,
                'description' => esc_html__('Use @ sign with your account name.', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_twitter_feed_hashtag_name',
            [
                'label' => esc_html__('Hashtag Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'description' => esc_html__('Remove # sign from your hashtag name.', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_twitter_feed_consumer_key',
            [
                'label' => esc_html__('Consumer Key', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => 'wwC72W809xRKd9ySwUzXzjkmS',
                'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_consumer_secret',
            [
                'label' => esc_html__('Consumer Secret', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => 'rn54hBqxjve2CWOtZqwJigT3F5OEvrriK2XAcqoQVohzr2UA8h',
                'description' => '<a href="https://apps.twitter.com/app/" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_twitter_feed_settings',
            [
                'label' => esc_html__('Layout Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_twitter_feed_type',
            [
                'label' => esc_html__('Content Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'list' => esc_html__('List', 'essential-addons-for-elementor-lite'),
                    'masonry' => esc_html__('Masonry', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_type_col_type',
            [
                'label' => __('Column Grid', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'col-2' => '2 Columns',
                    'col-3' => '3 Columns',
                    'col-4' => '4 Columns',
                ],
                'default' => 'col-3',
                'condition' => [
                    'eael_twitter_feed_type' => 'masonry',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_content_length',
            [
                'label' => esc_html__('Content Length', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'min' => 1,
                'max' => 400,
                'default' => 400,
            ]
        );

        $this->add_responsive_control(
            'eael_twitter_feed_column_spacing',
            [
                'label' => esc_html__('Column spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_post_limit',
            [
                'label' => esc_html__('Post Limit', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 10,
            ]
        );

        $this->add_control(
            'eael_twitter_feed_media',
            [
                'label' => esc_html__('Show Media Elements', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_twitter_feed_card_settings',
            [
                'label' => esc_html__('Card Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_twitter_feed_show_avatar',
            [
                'label' => esc_html__('Show Avatar', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_avatar_style',
            [
                'label' => __('Avatar Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => 'Circle',
                    'square' => 'Square',
                ],
                'default' => 'circle',
                'prefix_class' => 'eael-social-feed-avatar-',
                'condition' => [
                    'eael_twitter_feed_show_avatar' => 'true',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_show_date',
            [
                'label' => esc_html__('Show Date', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_show_read_more',
            [
                'label' => esc_html__('Show Read More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_show_icon',
            [
                'label' => esc_html__('Show Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
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
                            'title' => __('', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style (Twitter Feed Card Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_twitter_feed_card_style_settings',
            [
                'label' => esc_html__('Card Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_twitter_feed_card_container_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-twitter-feed-item-content' => 'padding: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_twitter_feed_card_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-inner',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-inner' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_twitter_feed_card_shadow',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-inner',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Twitter Feed Typography Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_twitter_feed_card_typo_settings',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_twitter_feed_title_heading',
            [
                'label' => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_twitter_feed_title_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-author' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_twitter_feed_title_typography',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-author',
            ]
        );
        // Content Style
        $this->add_control(
            'eael_twitter_feed_content_heading',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_content_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_twitter_feed_content_typography',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-content p',
            ]
        );

        // Content Link Style
        $this->add_control(
            'eael_twitter_feed_content_link_heading',
            [
                'label' => esc_html__('Link Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_content_link_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_content_link_hover_color',
            [
                'label' => esc_html__('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_twitter_feed_content_link_typography',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-content a',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();

        echo '<div class="eael-twitter-feed eael-twitter-feed-' . $this->get_id() . ' eael-twitter-feed-' . $settings['eael_twitter_feed_type'] . ' eael-twitter-feed-' . $settings['eael_twitter_feed_type_col_type'] . ' clearfix" data-gutter="' . $settings['eael_twitter_feed_column_spacing']['size'] . '">
			' . $this->twitter_feed_render_items($this->get_id(), $settings) . '
        </div>';
        
        echo '<style>
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-masonry.eael-twitter-feed-col-2 .eael-twitter-feed-item {
                width: calc(50% - ' . ceil($settings['eael_twitter_feed_column_spacing']['size'] / 2) . 'px);
            }
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-masonry.eael-twitter-feed-col-3 .eael-twitter-feed-item {
                width: calc(33.33% - ' . ceil($settings['eael_twitter_feed_column_spacing']['size'] * 2 / 3) . 'px);
            }
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-masonry.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                width: calc(25% - ' . ceil($settings['eael_twitter_feed_column_spacing']['size'] * 3 / 4) . 'px);
            }
            
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-col-2 .eael-twitter-feed-item,
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-col-3 .eael-twitter-feed-item,
            .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                margin-bottom: ' . $settings['eael_twitter_feed_column_spacing']['size'] . 'px;
            }
            @media only screen and (min-width: 768px) and (max-width: 992px) {
                .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-masonry.eael-twitter-feed-col-3 .eael-twitter-feed-item,
                .eael-twitter-feed-' . $this->get_id() . '.eael-twitter-feed-masonry.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                    width: calc(50% - ' . ceil($settings['eael_twitter_feed_column_spacing']['size'] / 2) . 'px);
                }
            }
        </style>';
        if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
            echo '<script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(".eael-twitter-feed").each(function() {
                        var $node_id = "' . $this->get_id() . '",
                        $scope = $(".elementor-element-"+$node_id+""),
                        $gutter = $(".eael-twitter-feed", $scope).data("gutter"),
                        $settings = {
                            itemSelector: ".eael-twitter-feed-item",
                            percentPosition: true,
                            masonry: {
                                columnWidth: ".eael-twitter-feed-item",
                                gutter: $gutter
                            }
                        };
                        
                        // init isotope
                        $twitter_feed_gallery = $(".eael-twitter-feed", $scope).isotope($settings);
                    
                        // layout gal, while images are loading
                        $twitter_feed_gallery.imagesLoaded().progress(function() {
                            $twitter_feed_gallery.isotope("layout");
                        });
                    });
                });
            </script>';
        }
    }
}

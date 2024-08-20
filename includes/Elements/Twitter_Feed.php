<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

class Twitter_Feed extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Twitter_Feed;

    public function get_name()
    {
        return 'eael-twitter-feed';
    }

    public function get_title()
    {
        return esc_html__('X (Twitter) Feed', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-twitter-feed';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'twitter',
            'x',
            'ea twitter feed',
            'ea x feed',
            'ea twitter gallery',
            'ea x gallery',
            'social media',
            'twitter embed',
            'x embed',
            'twitter feed',
            'x feed',
            'twitter marketing',
            'x marketing',
            'tweet feed',
            'tweet embed',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
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
            'font-awesome-4-shim',
        ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'eael_section_twitter_feed_acc_settings',
            [
                'label' => esc_html__('Account Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
		    'eael_twitter_api_v2',
		    [
			    'label'        => esc_html__( 'Twitter API V2', 'essential-addons-for-elementor-lite' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			    'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			    'default'      => '',
			    'return_value' => 'yes',
		    ]
	    );

        $this->add_control(
            'eael_twitter_feed_ac_name',
            [
                'label' => esc_html__('Account Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'default' => '@wpdevteam',
                'label_block' => false,
                'description' => esc_html__('Use @ sign with your account name.', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_hashtag_name',
            [
                'label' => esc_html__('Hashtag Name', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
                'description' => esc_html__('Remove # sign from your hashtag name.', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_consumer_key',
            [
                'label' => esc_html__('Consumer Key', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '',
                'description' => '<a href="https://developer.twitter.com/en/portal/dashboard" target="_blank">Get Consumer Key.</a> Create a new app or select existing app and grab the <b>consumer key.</b>',
                'ai' => [
					'active' => false,
				],
                'condition'   => [
				    'eael_twitter_api_v2' => ''
			    ]
            ]
        );

        $this->add_control(
            'eael_twitter_feed_consumer_secret',
            [
                'label' => esc_html__('Consumer Secret', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '',
                'description' => '<a href="https://developer.twitter.com/en/portal/dashboard" target="_blank">Get Consumer Secret.</a> Create a new app or select existing app and grab the <b>consumer secret.</b>',
                'ai' => [
					'active' => false,
				],
                'condition'   => [
				    'eael_twitter_api_v2' => ''
			    ]
            ]
        );

        $this->add_control(
            'eael_twitter_feed_bearer_token',
            [
                'label' => esc_html__('Bearer Token', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '',
                'description' => '<a href="https://developer.twitter.com/en/portal/dashboard" target="_blank">Get Bearer Token.</a> Create a new app or select existing app within a project and grab the <b>bearer token.</b>',
                'ai' => [
					'active' => false,
				],
                'condition'   => [
				    'eael_twitter_api_v2' => 'yes'
			    ]
            ]
        );

	    $this->add_control(
		    'eael_auto_clear_cache',
		    [
			    'label'        => esc_html__( 'Auto Cache Clear', 'essential-addons-for-elementor-lite' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			    'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			    'default'      => 'yes',
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'eael_twitter_feed_cache_limit',
		    [
			    'label'       => __( 'Data Cache Time', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::NUMBER,
			    'min'         => 1,
			    'default'     => 60,
			    'description' => __( 'Cache expiration time (Minutes)', 'essential-addons-for-elementor-lite' ),
			    'condition'   => [
				    'eael_auto_clear_cache' => 'yes'
			    ]
		    ]
	    );

	    $this->add_control(
		    'eael_clear_cache_control',
		    [
			    'label'       => __( 'Clear Cache', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::BUTTON,
			    'text'        => __( 'Clear', 'essential-addons-for-elementor-lite' ),
			    'event'       => 'ea:cache:clear',
			    'description' => esc_html__( 'Note: This will refresh your feed and fetch the latest data from your Twitter account', 'essential-addons-for-elementor-lite' ),
			    'condition'   => [
				    'eael_auto_clear_cache' => ''
			    ]
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
            'eael_twitter_feed_show_replies',
            [
                'label' => esc_html__('Show Replies', 'essential-addons-for-elementor-lite'),
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
            'eael_twitter_feed_show_read_more_text',
            [
                'label' => esc_html__('Read More Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => __('Read More', 'essential-addons-for-elementor-lite'),
	            'condition' => [
	            	'eael_twitter_feed_show_read_more' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
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

        $this->add_control(
            'eael_twitter_feed_media',
            [
                'label' => esc_html__('Show Media', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'true',
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_hyperlinks_header',
            [
                'label' => esc_html__('Hyperlinks', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_twitter_feed_hash_linked',
            [
                'label' => esc_html__('Hashtag', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Enable', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Disable', 'essential-addons-for-elementor-lite'),
                'default' => '',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'eael_twitter_feed_mention_linked',
            [
                'label' => esc_html__('Mentions', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Enable', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Disable', 'essential-addons-for-elementor-lite'),
                'default' => '',
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Load More Button
         */
        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
            ]
        );
        
        $this->add_control(
            'pagination',
            [
                'label' => __('Show Load More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'false',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'eael_twitter_feed_posts_per_page',
            [
                'label' => __('Posts Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => 6,
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'load_more_text',
            [
                'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('Load More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'nomore_items_text',
            [
                'label' => __('No More Items Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'default' => __('No more items!', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        
        $this->add_control(
            'button_size',
            [
                'label' => __('Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __('Extra Small', 'essential-addons-for-elementor-lite'),
                    'sm' => __('Small', 'essential-addons-for-elementor-lite'),
                    'md' => __('Medium', 'essential-addons-for-elementor-lite'),
                    'lg' => __('Large', 'essential-addons-for-elementor-lite'),
                    'xl' => __('Extra Large', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_icon_new',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'load_more_icon',
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label' => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => __('After', 'essential-addons-for-elementor-lite'),
                    'before' => __('Before', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'load_more_align',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-loadmore-wrap' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
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
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
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
            'eael_twitter_feed_card_choose_style',
            [
                'label' => __('Choose Style', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default Style', 'essential-addons-for-elementor-lite'),
                    'two' => __('Style Two (right icon)', 'essential-addons-for-elementor-lite'),
                    'three' => __('Style Three', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_left_icon_alignment',
            [
                'label' => __('Left Icon Alignment', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Top', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Middle', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Bottom', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-entry-iconwrap' => 'align-self: {{VALUE}};',
                ],
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_is_gradient_bg',
            [
                'label' => __('Use gradient Background!', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_twitter_feed_card_gradient_bg',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-inner',
                'condition' => [
                    'eael_twitter_feed_card_is_gradient_bg' => 'yes',
                ],
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
                'condition' => [
                    'eael_twitter_feed_card_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_twitter_feed_card_inner_padding',
            [
                'label' => esc_html__('Main Card Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
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
                'condition' => [
                    'eael_twitter_feed_card_choose_style!' => 'three',
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
        $this->add_control(
            'eael_twitter_feed_card_left_icon_heading',
            [
                'label' => __('Left Icon Area', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_twitter_feed_card_item_left_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-style-three .eael-twitter-feed-item-inner .eael-twitter-feed-entry-iconwrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_right_content_heading',
            [
                'label' => __('Right Content Area', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_twitter_feed_card_item_right_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-style-three .eael-twitter-feed-item-inner .eael-twitter-feed-entry-contentwrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'eael_twitter_feed_card_item_right_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-style-three .eael-twitter-feed-item-inner .eael-twitter-feed-entry-contentwrap',
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ]
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_item_right_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-style-three .eael-twitter-feed-item-inner .eael-twitter-feed-entry-contentwrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_twitter_feed_card_choose_style' => 'three',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Card Hover Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_twitter_feed_card_hover_settings',
            [
                'label' => esc_html__('Card Hover Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_hover_title_color',
            [
                'label' => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-inner:hover .eael-twitter-feed-item-author' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_hover_content_color',
            [
                'label' => __('Content Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item-inner:hover .eael-twitter-feed-item-content p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_hover_link_color',
            [
                'label' => __('Link Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .eael-twitter-feed-item-inner:hover .eael-twitter-feed-item-content a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_hover_date_color',
            [
                'label' => __('Date Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .eael-twitter-feed-item-inner:hover .eael-twitter-feed-item-header .eael-twitter-feed-item-date' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'eael_twitter_feed_card_hover_icon_color',
            [
                'label' => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .eael-twitter-feed-item-inner:hover .eael-twitter-feed-item-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_card_border_hover_color',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .eael-twitter-feed-item-inner:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_twitter_feed_card_hover_bg',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-inner:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_twitter_feed_card_hover_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item-inner:hover',
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

        /**
         * -------------------------------------------
         * Tab Style (avatar style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_twitter_feed_avatar_style',
            [
                'label' => esc_html__('Avatar', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_twitter_feed_show_avatar' => 'true',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_avatar_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 38,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-avatar img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_twitter_feed_avatar_height',
            [
                'label' => __('Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-avatar img' => 'height: {{SIZE}}{{UNIT}};',
                ],
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
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'eael_twitter_feed_avatar_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-avatar img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_twitter_feed_avatar_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-avatar img',
            ]
        );

        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style (Icon style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_twitter_feed_icon_style',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_twitter_feed_show_icon' => 'true',
                ],
            ]
        );

        $this->add_control(
            'eael_section_twitter_feed_icon_size',
            [
                'label' => __('Font Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_section_twitter_feed_icon_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'global' => [
	                'default' => Global_Colors::COLOR_PRIMARY
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-item .eael-twitter-feed-item-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Load More Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_loadmore_button_style',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_margin_top',
            [
                'label' => __('Top Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs('tabs_eael_load_more_button_style');
        
        $this->start_controls_tab(
            'tab_load_more_button_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_bg_color_normal',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_text_color_normal',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_button_border_normal',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-load-more',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_button_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
                'selector' => '{{WRAPPER}} .eael-twitter-feed-load-more .eael-twitter-feed-load-more-text',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 500,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more .eael-twitter-feed-load-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-twitter-feed-load-more img.eael-twitter-feed-load-more-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_spacing',
            [
                'label' => __('Icon Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more .fg-load-more-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-twitter-feed-load-more .fg-load-more-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'load_more_button_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_button_box_shadow',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-load-more',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'load_more_button_icon_heading',
            [
                'label' => __('Button Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_icon!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'load_more_button_icon_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'placeholder' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more .eael-twitter-feed-load-more-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_icon!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more:hover' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-twitter-feed-load-more:hover' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .eael-twitter-feed-load-more:hover',
                'condition' => [
                    'pagination' => 'yes',
                    'load_more_text!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

    protected function render_loadmore_button()
    {
        $settings = $this->get_settings_for_display();
        $icon_migrated = isset($settings['__fa4_migrated']['load_more_icon_new']);
        $icon_is_new = empty($settings['load_more_icon']);

        $post_per_page = ! empty($settings['eael_twitter_feed_posts_per_page']) ? $settings['eael_twitter_feed_posts_per_page'] : 10;
        $post_limit = ! empty( $settings['eael_twitter_feed_post_limit'] ) ? $settings['eael_twitter_feed_post_limit'] : 10;
        $load_more_class = $post_per_page < $post_limit ? 'eael-d-block' : 'eael-d-none';
        
        $this->add_render_attribute('load-more-button', 'class', [
            'eael-twitter-feed-load-more',
            'elementor-button',
            'elementor-size-' . $settings['button_size'],
        ]);
        
        if ($settings['pagination'] == 'yes' && self::$twitter_feed_fetched_count > $post_per_page ) { ?>
            <div class="eael-twitter-feed-loadmore-wrap">
                <a href="#" <?php echo $this->get_render_attribute_string('load-more-button'); ?>>
                    <span class="eael-btn-loader"></span>
                    <?php if ($settings['button_icon_position'] == 'before') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-twitter-feed-load-more-icon fg-load-more-icon-left" src="<?php echo esc_url($settings['load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-twitter-feed-load-more-icon fg-load-more-icon-left <?php echo esc_attr($settings['load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-twitter-feed-load-more-icon fg-load-more-icon-left <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                    <span class="eael-twitter-feed-load-more-text">
                        <?php echo Helper::eael_wp_kses($settings['load_more_text']); ?>
                    </span>
                    <?php if ($settings['button_icon_position'] == 'after') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-twitter-feed-load-more-icon fg-load-more-icon-right" src="<?php echo esc_url($settings['load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-twitter-feed-load-more-icon fg-load-more-icon-right <?php echo esc_attr($settings['load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-twitter-feed-load-more-icon fg-load-more-icon-right <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                </a>
            </div>
        <?php }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $feedcolumnspacing = $this->get_settings('eael_twitter_feed_column_spacing')['size'];

        $no_more_items_text = Helper::eael_wp_kses($settings['nomore_items_text']);
        $post_limit = ! empty( $settings['eael_twitter_feed_post_limit'] ) ? intval( $settings['eael_twitter_feed_post_limit'] ) : 10;
        
        $this->add_render_attribute('twitter-feed-wrap', [
            'class' => [
                'eael-twitter-feed',
                'eael-twitter-feed-' . $this->get_id(),
                'eael-twitter-feed-' . $settings['eael_twitter_feed_type'],
                'eael-twitter-feed-' . $settings['eael_twitter_feed_type_col_type'],
                'clearfix',
            ],
            'data-gutter' => $settings['eael_twitter_feed_column_spacing']['size'],
            'data-posts-per-page' => $settings['eael_twitter_feed_posts_per_page'],
            'data-total-posts' => $post_limit,
            'data-nomore-item-text' => $no_more_items_text,
            'data-next-page' => 2,
        ]);

        ?> 
        <div>
            <div <?php echo $this->get_render_attribute_string('twitter-feed-wrap') ?> >
                <?php echo $this->twitter_feed_render_items($this->get_id(), $settings) ?>
            </div>
            <div class="clearfix">
                <?php echo $this->render_loadmore_button() ?>
            </div>
        </div>

        <?php 
        echo '<style>
            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-masonry.eael-twitter-feed-col-2 .eael-twitter-feed-item {
                width: calc(50% - ' . ceil($feedcolumnspacing / 2) . 'px);
            }
            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-masonry.eael-twitter-feed-col-3 .eael-twitter-feed-item {
                width: calc(33.33% - ' . ceil($settings['eael_twitter_feed_column_spacing']['size'] * 2 / 3) . 'px);
            }
            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-masonry.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                width: calc(25% - ' . ceil($feedcolumnspacing * 3 / 4) . 'px);
            }

            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-col-2 .eael-twitter-feed-item,
            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-col-3 .eael-twitter-feed-item,
            .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                margin-bottom: ' . $settings['eael_twitter_feed_column_spacing']['size'] . 'px;
            }
            @media only screen and (min-width: 768px) and (max-width: 992px) {
                .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-masonry.eael-twitter-feed-col-3 .eael-twitter-feed-item,
                .eael-twitter-feed-' . esc_html( $this->get_id() ) . '.eael-twitter-feed-masonry.eael-twitter-feed-col-4 .eael-twitter-feed-item {
                    width: calc(50% - ' . ceil($feedcolumnspacing / 2) . 'px);
                }
            }
        </style>';
        if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
            echo '<script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(".eael-twitter-feed").each(function() {
                        var $node_id = "' . esc_js( $this->get_id() ) . '",
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

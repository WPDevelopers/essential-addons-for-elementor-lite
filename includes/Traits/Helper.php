<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size as Group_Control_Image_Size;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Utils as Utils;

trait Helper
{
    /**
     * For All Settings Key Need To Display
     *
     */
    public $post_args = [
        // content ticker
        'eael_ticker_type',
        'eael_ticker_custom_contents',

        // post grid
        'eael_post_grid_columns',

        // common
        'meta_position',
        'eael_show_meta',
        'image_size',
        'eael_show_image',
        'eael_show_title',
        'eael_show_excerpt',
        'eael_excerpt_length',
        'eael_show_read_more',
        'eael_read_more_text',
        'show_load_more',
        'show_load_more_text',

        // query_args
        'post_type',
        'post__in',
        'posts_per_page',
        'post_style',
        'tax_query',
        'post__not_in',
        'eael_post_authors',
        'eaeposts_authors',
        'offset',
        'orderby',
        'order',
    ];

    /**
     * Get all types of post.
     * @return array
     */
    public function eael_get_all_types_post()
    {
        $posts_args = array(
            'post_type' => 'any',
            'post_style' => 'all_types',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
        );
        $posts = $this->eael_load_more_ajax($posts_args);

        $post_list = [];

        foreach ($posts as $post) {
            $post_list[$post->ID] = $post->post_title;
        }

        return $post_list;
    }

    /**
     * Query Controls
     *
     */
    protected function eael_query_controls()
    {
        if ('eael-content-ticker' === $this->get_name()) {
            $this->start_controls_section(
                'eael_section_content_ticker_filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_ticker_type' => 'dynamic',
                    ],
                ]
            );
        }

        if ('eael-content-timeline' === $this->get_name()) {
            $this->start_controls_section(
                'eael_section_timeline__filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        }

        if ('eael-content-timeline' !== $this->get_name() && 'eael-content-ticker' !== $this->get_name()) {
            $this->start_controls_section(
                'eael_section_post__filters',
                [
                    'label' => __('Query', 'essential-addons-elementor'),
                ]
            );
        }

        $this->add_group_control(
            'eaeposts',
            [
                'name' => 'eaeposts',
            ]
        );

        $this->add_control(
            'post__not_in',
            [
                'label' => __('Exclude', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->eael_get_all_types_post(),
                'label_block' => true,
                'post_type' => '',
                'multiple' => true,
                'condition' => [
                    'eaeposts_post_type!' => 'by_id',
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->eael_get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );

        $this->end_controls_section();
    }

    /**
     * Layout Controls For Post Block
     *
     */
    protected function eael_layout_controls()
    {
        $this->start_controls_section(
            'eael_section_post_timeline_layout',
            [
                'label' => __('Layout Settings', 'essential-addons-elementor'),
            ]
        );

        if ('eael-post-grid' === $this->get_name()) {
            $this->add_control(
                'eael_post_grid_columns',
                [
                    'label' => esc_html__('Number of Columns', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'eael-col-4',
                    'options' => [
                        'eael-col-1' => esc_html__('Single Column', 'essential-addons-elementor'),
                        'eael-col-2' => esc_html__('Two Columns', 'essential-addons-elementor'),
                        'eael-col-3' => esc_html__('Three Columns', 'essential-addons-elementor'),
                        'eael-col-4' => esc_html__('Four Columns', 'essential-addons-elementor'),
                        'eael-col-5' => esc_html__('Five Columns', 'essential-addons-elementor'),
                        'eael-col-6' => esc_html__('Six Columns', 'essential-addons-elementor'),
                    ],
                ]
            );
        }

        if ('eael-post-block' === $this->get_name()) {
            $this->add_control(
                'grid_style',
                [
                    'label' => esc_html__('Post Block Style Preset', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'post-block-style-default',
                    'options' => [
                        'post-block-style-default' => esc_html__('Default', 'essential-addons-elementor'),
                        'post-block-style-overlay' => esc_html__('Overlay', 'essential-addons-elementor'),
                    ],
                ]
            );
        }

        if ('eael-post-carousel' !== $this->get_name()) {

            /**
             * Show Read More
             * @uses ContentTimeLine Elements - EAE
             */
            if ('eael-content-timeline' === $this->get_name()) {

                $this->add_control(
                    'eael_show_read_more',
                    [
                        'label' => __('Show Read More', 'essential-addons-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            '1' => [
                                'title' => __('Yes', 'essential-addons-elementor'),
                                'icon' => 'fa fa-check',
                            ],
                            '0' => [
                                'title' => __('No', 'essential-addons-elementor'),
                                'icon' => 'fa fa-ban',
                            ],
                        ],
                        'default' => '1',
                        'condition' => [
                            'eael_content_timeline_choose' => 'dynamic',
                        ],
                    ]
                );

                $this->add_control(
                    'eael_read_more_text',
                    [
                        'label' => esc_html__('Label Text', 'essential-addons-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'default' => esc_html__('Read More', 'essential-addons-elementor'),
                        'condition' => [
                            'eael_content_timeline_choose' => 'dynamic',
                            'eael_show_read_more' => '1',
                        ],
                    ]
                );

            } else {

                $this->add_control(
                    'show_load_more',
                    [
                        'label' => __('Show Load More', 'essential-addons-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            '1' => [
                                'title' => __('Yes', 'essential-addons-elementor'),
                                'icon' => 'fa fa-check',
                            ],
                            '0' => [
                                'title' => __('No', 'essential-addons-elementor'),
                                'icon' => 'fa fa-ban',
                            ],
                        ],
                        'default' => '0',
                    ]
                );

                $this->add_control(
                    'show_load_more_text',
                    [
                        'label' => esc_html__('Label Text', 'essential-addons-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'default' => esc_html__('Load More', 'essential-addons-elementor'),
                        'condition' => [
                            'show_load_more' => '1',
                        ],
                    ]
                );
            }

        }

        if ('eael-content-timeline' !== $this->get_name()) {
            $this->add_control(
                'eael_show_image',
                [
                    'label' => __('Show Image', 'essential-addons-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __('Yes', 'essential-addons-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __('No', 'essential-addons-elementor'),
                            'icon' => 'fa fa-ban',
                        ],
                    ],
                    'default' => '1',
                ]
            );
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'image',
                    'exclude' => ['custom'],
                    'default' => 'medium',
                    'condition' => [
                        'eael_show_image' => '1',
                    ],
                ]
            );

        }

        if ('eael-content-timeline' === $this->get_name()) {

            $this->add_control(
                'eael_show_image_or_icon',
                [
                    'label' => __('Show Circle Image / Icon', 'essential-addons-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'img' => [
                            'title' => __('Image', 'essential-addons-elementor'),
                            'icon' => 'fa fa-picture-o',
                        ],
                        'icon' => [
                            'title' => __('Icon', 'essential-addons-elementor'),
                            'icon' => 'fa fa-info',
                        ],
                        'bullet' => [
                            'title' => __('Bullet', 'essential-addons-elementor'),
                            'icon' => 'fa fa-circle',
                        ],
                    ],
                    'default' => 'icon',
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );

            $this->add_control(
                'eael_icon_image',
                [
                    'label' => esc_html__('Icon Image', 'essential-addons-elementor'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' => [
                        'eael_show_image_or_icon' => 'img',
                    ],
                ]
            );
            $this->add_control(
                'eael_icon_image_size',
                [
                    'label' => esc_html__('Icon Image Size', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 24,
                    ],
                    'range' => [
                        'px' => [
                            'max' => 60,
                        ],
                    ],
                    'condition' => [
                        'eael_show_image_or_icon' => 'img',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-content-timeline-img img' => 'width: {{SIZE}}px;',
                    ],
                ]
            );

            $this->add_control(
                'eael_content_timeline_circle_icon',
                [
                    'label' => esc_html__('Icon', 'essential-addons-elementor'),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-pencil',
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                        'eael_show_image_or_icon' => 'icon',
                    ],
                ]
            );

        }

        $this->add_control(
            'eael_show_title',
            [
                'label' => __('Show Title', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'essential-addons-elementor'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'essential-addons-elementor'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'eael_show_excerpt',
            [
                'label' => __('Show excerpt', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'essential-addons-elementor'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'essential-addons-elementor'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'eael_excerpt_length',
            [
                'label' => __('Excerpt Words', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'eael_show_excerpt' => '1',
                ],
            ]
        );

        if ('eael-post-grid' === $this->get_name() || 'eael-post-block' === $this->get_name() || 'eael-post-carousel' === $this->get_name()) {

            $this->add_control(
                'eael_show_meta',
                [
                    'label' => __('Show Meta', 'essential-addons-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __('Yes', 'essential-addons-elementor'),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __('No', 'essential-addons-elementor'),
                            'icon' => 'fa fa-ban',
                        ],
                    ],
                    'default' => '1',
                ]
            );

            $this->add_control(
                'meta_position',
                [
                    'label' => esc_html__('Meta Position', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'meta-entry-footer',
                    'options' => [
                        'meta-entry-header' => esc_html__('Entry Header', 'essential-addons-elementor'),
                        'meta-entry-footer' => esc_html__('Entry Footer', 'essential-addons-elementor'),
                    ],
                    'condition' => [
                        'eael_show_meta' => '1',
                    ],
                ]
            );

        }

        $this->end_controls_section();
    }

    /**
     * Load More Button Style
     *
     */
    protected function eael_load_more_button_style()
    {
        $this->start_controls_section(
            'eael_section_load_more_btn',
            [
                'label' => __('Load More Button Style', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_load_more' => '1',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_load_more_btn_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_load_more_btn_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_typography',
                'selector' => '{{WRAPPER}} .eael-load-more-button',
            ]
        );

        $this->start_controls_tabs('eael_post_grid_load_more_btn_tabs');

        // Normal State Tab
        $this->start_controls_tab('eael_post_grid_load_more_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

        $this->add_control(
            'eael_post_grid_load_more_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_cta_btn_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#29d8d8',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_normal_border',
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-load-more-button',
            ]
        );

        $this->add_control(
            'eael_post_grid_load_more_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_shadow',
                'selector' => '{{WRAPPER}} .eael-load-more-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('eael_post_grid_load_more_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);

        $this->add_control(
            'eael_post_grid_load_more_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_load_more_btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#27bdbd',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_load_more_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]

        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_hover_shadow',
                'selector' => '{{WRAPPER}} .eael-load-more-button:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'eael_post_grid_loadmore_button_alignment',
            [
                'label' => __('Button Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-load-more-button-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Go Premium
     *
     */
    protected function eael_go_premium()
    {
        $this->start_controls_section(
            'eael_section_pro',
            [
                'label' => __('Go Premium for More Features', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'eael_control_get_pro',
            [
                'label' => __('Unlock more possibilities', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('', 'essential-addons-elementor'),
                        'icon' => 'fa fa-unlock-alt',
                    ],
                ],
                'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="http://essential-addons.com/elementor/#pricing" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
            ]
        );

        $this->end_controls_section();
    }

    public function eael_get_query_args($control_id, $settings)
    {
        $defaults = [
            $control_id . '_post_type' => 'post',
            $control_id . '_posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
        ];

        $settings = wp_parse_args($settings, $defaults);

        $post_type = $settings[$control_id . '_post_type'];

        $query_args = [
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish', // Hide drafts/private posts for admins
        ];

        if ('by_id' === $post_type) {
            $query_args['post_type'] = 'any';
            $query_args['post__in'] = $settings[$control_id . '_posts_ids'];

            if (empty($query_args['post__in'])) {
                // If no selection - return an empty query
                $query_args['post__in'] = [0];
            }
        } else {
            $query_args['post_type'] = $post_type;
            $query_args['posts_per_page'] = $settings['posts_per_page'];
            $query_args['tax_query'] = [];

            $query_args['offset'] = $settings['offset'];

            $taxonomies = get_object_taxonomies($post_type, 'objects');

            foreach ($taxonomies as $object) {
                $setting_key = $control_id . '_' . $object->name . '_ids';

                if (!empty($settings[$setting_key])) {
                    $query_args['tax_query'][] = [
                        'taxonomy' => $object->name,
                        'field' => 'term_id',
                        'terms' => $settings[$setting_key],
                    ];
                }
            }
        }

        if (!empty($settings[$control_id . '_authors'])) {
            $query_args['author__in'] = $settings[$control_id . '_authors'];
        }

        $post__not_in = [];
        if (!empty($settings['post__not_in'])) {
            $post__not_in = array_merge($post__not_in, $settings['post__not_in']);
            $query_args['post__not_in'] = $post__not_in;
        }

        if (isset($query_args['tax_query']) && count($query_args['tax_query']) > 1) {
            $query_args['tax_query']['relation'] = 'OR';
        }

        return $query_args;
    }

    /**
     * Get All POst Types
     * @return array
     */
    public function eael_get_post_types()
    {
        $eael_cpts = get_post_types(array('public' => true, 'show_in_nav_menus' => true), 'object');
        $eael_exclude_cpts = array('elementor_library', 'attachment');

        foreach ($eael_exclude_cpts as $exclude_cpt) {
            unset($eael_cpts[$exclude_cpt]);
        }
        $post_types = array_merge($eael_cpts);
        foreach ($post_types as $type) {
            $types[$type->name] = $type->label;
        }

        return $types;
    }

    /**
     * Post Settings Parameter
     * @param  array $settings
     * @return array
     */
    public function eael_get_post_settings($settings)
    {
        foreach ($settings as $key => $value) {
            if (in_array($key, $this->post_args)) {
                $post_args[$key] = $value;
            }
        }

        $post_args['post_style'] = isset($post_args['post_style']) ? $post_args['post_style'] : 'grid';
        $post_args['is_pro'] = isset($settings['is_pro']) ? $settings['is_pro'] : false;
        $post_args['post_status'] = 'publish';

        return $post_args;
    }

    /**
     * Getting Excerpts By Post Id
     * @param  int $post_id
     * @param  int $excerpt_length
     * @return string
     */
    public function eael_get_excerpt_by_id($post_id, $excerpt_length)
    {
        $the_post = get_post($post_id); //Gets post ID

        $the_excerpt = null;
        if ($the_post) {
            $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
        }

        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length):
            array_pop($words);
            array_push($words, '…');
            $the_excerpt = implode(' ', $words);
        endif;

        return $the_excerpt;
    }

    /**
     * Get Post Thumbnail Size
     *
     * @return array
     */
    public function eael_get_thumbnail_sizes()
    {
        $sizes = get_intermediate_image_sizes();
        foreach ($sizes as $s) {
            $ret[$s] = $s;
        }

        return $ret;
    }

    /**
     * POst Orderby Options
     *
     * @return array
     */
    public function eael_get_post_orderby_options()
    {
        $orderby = array(
            'ID' => 'Post ID',
            'author' => 'Post Author',
            'title' => 'Title',
            'date' => 'Date',
            'modified' => 'Last Modified Date',
            'parent' => 'Parent Id',
            'rand' => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order' => 'Menu Order',
        );

        return $orderby;
    }

    /**
     * Get Post Categories
     *
     * @return array
     */
    public function eael_post_type_categories()
    {
        $terms = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }

    /**
     * WooCommerce Product Query
     *
     * @return array
     */
    public function eael_woocommerce_product_categories()
    {
        $terms = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
            return $options;
        }
    }

    /**
     * WooCommerce Get Product By Id
     *
     * @return array
     */
    public function eael_woocommerce_product_get_product_by_id()
    {
        $postlist = get_posts(array(
            'post_type' => 'product',
            'showposts' => 9999,
        ));
        $options = array();

        if (!empty($postlist) && !is_wp_error($postlist)) {
            foreach ($postlist as $post) {
                $options[$post->ID] = $post->post_title;
            }
            return $options;

        }
    }

    /**
     * WooCommerce Get Product Category By Id
     *
     * @return array
     */
    public function eael_woocommerce_product_categories_by_id()
    {
        $terms = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
            return $options;
        }

    }

    /**
     * Get Contact Form 7 [ if exists ]
     */
    public function eael_select_contact_form()
    {
        $options = array();

        if (function_exists('wpcf7')) {
            $wpcf7_form_list = get_posts(array(
                'post_type' => 'wpcf7_contact_form',
                'showposts' => 999,
            ));
            $options[0] = esc_html__('Select a Contact Form', 'essential-addons-elementor');
            if (!empty($wpcf7_form_list) && !is_wp_error($wpcf7_form_list)) {
                foreach ($wpcf7_form_list as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
            }
        }
        return $options;
    }

    /**
     * Get Gravity Form [ if exists ]
     *
     * @return array
     */
    public function eael_select_gravity_form()
    {
        $options = array();

        if (class_exists('GFCommon')) {
            $gravity_forms = \RGFormsModel::get_forms(null, 'title');

            if (!empty($gravity_forms) && !is_wp_error($gravity_forms)) {

                $options[0] = esc_html__('Select Gravity Form', 'essential-addons-elementor');
                foreach ($gravity_forms as $form) {
                    $options[$form->id] = $form->title;
                }

            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
            }
        }

        return $options;
    }

    /**
     * Get WeForms Form List
     *
     * @return array
     */
    public function eael_select_weform()
    {
        $wpuf_form_list = get_posts(array(
            'post_type' => 'wpuf_contact_form',
            'showposts' => 999,
        ));

        $options = array();

        if (!empty($wpuf_form_list) && !is_wp_error($wpuf_form_list)) {
            $options[0] = esc_html__('Select weForm', 'essential-addons-elementor');
            foreach ($wpuf_form_list as $post) {
                $options[$post->ID] = $post->post_title;
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
        }

        return $options;
    }

    /**
     * Get Ninja Form List
     *
     * @return array
     */
    public function eael_select_ninja_form()
    {
        $options = array();

        if (class_exists('Ninja_Forms')) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {

                $options[0] = esc_html__('Select Ninja Form', 'essential-addons-elementor');

                foreach ($contact_forms as $form) {
                    $options[$form->get_id()] = $form->get_setting('title');
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
        }

        return $options;
    }

    /**
     * Get Caldera Form List
     *
     * @return array
     */
    public function eael_select_caldera_form()
    {
        $options = array();

        if (class_exists('Caldera_Forms')) {
            $contact_forms = \Caldera_Forms_Forms::get_forms(true, true);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select Caldera Form', 'essential-addons-elementor');
                foreach ($contact_forms as $form) {
                    $options[$form['ID']] = $form['name'];
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
        }

        return $options;
    }

    /**
     * Get WPForms List
     *
     * @return array
     */
    public function eael_select_wpforms_forms()
    {
        $options = array();

        if (class_exists('\WPForms\WPForms')) {
            $args = array(
                'post_type' => 'wpforms',
                'posts_per_page' => -1,
            );

            $contact_forms = get_posts($args);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select a WPForm', 'essential-addons-elementor');
                foreach ($contact_forms as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
        }

        return $options;
    }

    /**
     * Get all elementor page templates
     *
     * @return array
     */
    public function eael_get_page_templates($type = null)
    {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];

        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }

        $page_templates = get_posts($args);
        $options = array();

        if (!empty($page_templates) && !is_wp_error($page_templates)) {
            foreach ($page_templates as $post) {
                $options[$post->ID] = $post->post_title;
            }
        }
        return $options;
    }

    /**
     * Get all Authors
     *
     * @return array
     */
    public function eael_get_authors()
    {
        $options = array();
        $users = get_users();

        if ($users) {
            foreach ($users as $user) {
                $options[$user->ID] = $user->display_name;
            }
        }

        return $options;
    }

    /**
     * Get all Tags
     *
     * @return array
     */
    public function eael_get_tags()
    {
        $options = array();
        $tags = get_tags();

        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name;
        }

        return $options;
    }

    /**
     * Get all Posts
     *
     * @return array
     */
    public function eael_get_posts()
    {
        $post_list = get_posts(array(
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $posts = array();

        if (!empty($post_list) && !is_wp_error($post_list)) {
            foreach ($post_list as $post) {
                $posts[$post->ID] = $post->post_title;
            }
        }

        return $posts;
    }

    /**
     * Get all Pages
     *
     * @return array
     */
    public function eael_get_pages()
    {
        $page_list = get_posts(array(
            'post_type' => 'page',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $pages = array();

        if (!empty($page_list) && !is_wp_error($page_list)) {
            foreach ($page_list as $page) {
                $pages[$page->ID] = $page->post_title;
            }
        }

        return $pages;
    }

    /**
     * This function is responsible for get the post data.
     * It will return HTML markup with AJAX call and with normal call.
     *
     * @return string of an html markup with AJAX call.
     * @return array of content and found posts count without AJAX call.
     */
    public function eael_load_more_ajax()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'load_more') {
            $post_args = $this->eael_get_post_settings($_POST);
            $post_args = array_merge($this->eael_get_query_args('eaeposts', $_POST), $post_args, $_POST);

            if (isset($_POST['tax_query']) && count($_POST['tax_query']) > 1) {
                $post_args['tax_query']['relation'] = 'OR';
            }
        } else {
            $args = func_get_args();
            $post_args = $args[0];
        }

        $posts = new \WP_Query($post_args);

        /**
         * For returning all types of post as an array
         * @return array;
         */
        if (isset($post_args['post_style']) && $post_args['post_style'] == 'all_types') {
            return $posts->posts;
        }

        $return = array();
        $return['count'] = $posts->found_posts;

        if (isset($post_args['post_style'])) {
            if (
                $post_args['post_style'] == 'list'
                || $post_args['post_style'] == 'dynamic_gallery'
                || $post_args['post_style'] == 'content_timeline'
                || $post_args['post_style'] == 'list'
                || $post_args['post_style'] == 'block'
                || $post_args['post_style'] == 'post_carousel'
            ) {
                $post_args['is_pro'] = true;
            }
        }

        if (isset($post_args['post_style']) && $post_args['post_style'] == 'list') {
            $iterator = $feature_counter = 0;

            foreach ($posts->posts as $post) {
                if (isset($post_args['featured_posts']) && $post->ID != $post_args['featured_posts']) {
                    $normal_posts[] = $post;
                }
            }
            $posts->posts = array_merge(empty($post_args['featured_posts']) ? [] : [$post_args['featured_posts']], $normal_posts);
        }

        ob_start();
        while ($posts->have_posts()): $posts->the_post();
            $isPrinted = false;
            include ($post_args['is_pro'] ? EAEL_PRO_PLUGIN_PATH : EAEL_PLUGIN_PATH) . DIRECTORY_SEPARATOR . 'includes/templates/content/' . @$post_args['post_style'] . '.php';
        endwhile;

        $return['content'] = ob_get_clean();

        wp_reset_postdata();
        wp_reset_query();

        if (isset($_POST['action']) && $_POST['action'] == 'load_more') {
            if ($_POST['post_style'] == 'list') {
                echo json_encode($return);
                die();
            }

            echo $return['content'];
            die();
        } else {
            return $return;
        }
    }

    protected function render_feature_list($settings, $obj)
    {
        if (empty($settings['eael_pricing_table_items'])) {
            return;
        }

        $counter = 0;
        ?>
		<ul>
			<?php
            foreach ($settings['eael_pricing_table_items'] as $item):

            if ('yes' !== $item['eael_pricing_table_icon_mood']) {
                $obj->add_render_attribute('pricing_feature_item' . $counter, 'class', 'disable-item');
            }

            if ('yes' === $item['eael_pricing_item_tooltip']) {
                $obj->add_render_attribute('pricing_feature_item' . $counter,
                    [
                        'class' => 'tooltip',
                        'title' => $item['eael_pricing_item_tooltip_content'],
                        'id' => $obj->get_id() . $counter,
                    ]
                );
            }

            if ('yes' == $item['eael_pricing_item_tooltip']) {

                if ($item['eael_pricing_item_tooltip_side']) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-side', $item['eael_pricing_item_tooltip_side']);
                }

                if ($item['eael_pricing_item_tooltip_trigger']) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-trigger', $item['eael_pricing_item_tooltip_trigger']);
                }

                if ($item['eael_pricing_item_tooltip_animation']) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-animation', $item['eael_pricing_item_tooltip_animation']);
                }

                if (!empty($item['pricing_item_tooltip_animation_duration'])) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-animation_duration', $item['pricing_item_tooltip_animation_duration']);
                }

                if (!empty($item['eael_pricing_table_toolip_arrow'])) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-arrow', $item['eael_pricing_table_toolip_arrow']);
                }

                if (!empty($item['eael_pricing_item_tooltip_theme'])) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'data-theme', $item['eael_pricing_item_tooltip_theme']);
                }

            }
            ?>
            <li <?php echo $obj->get_render_attribute_string('pricing_feature_item' . $counter); ?>>
                <?php if ('show' === $settings['eael_pricing_table_icon_enabled']): ?>
                    <span class="li-icon" style="color:<?php echo esc_attr($item['eael_pricing_table_list_icon_color']); ?>"><i class="<?php echo esc_attr($item['eael_pricing_table_list_icon']); ?>"></i></span>
                <?php endif;?>
                <?php echo $item['eael_pricing_table_item']; ?>
            </li>
			<?php $counter++;endforeach;?>
		</ul>
		<?php
}

}

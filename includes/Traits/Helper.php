<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;

trait Helper
{
    /**
     * Get all types of post.
     * @return array
     */
    public function eael_get_all_types_post()
    {
        $posts = get_posts([
            'post_type' => 'any',
            'post_style' => 'all_types',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
        ]);

        if (!empty($posts)) {
            return wp_list_pluck($posts, 'post_title', 'ID');
        }

        return [];
    }

    /**
     * Query Controls
     *
     */
    protected function eael_query_controls()
    {
        $post_types = $this->eael_get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'essential-addons-elementor');
        $taxonomies = get_taxonomies([], 'objects');

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
        } else if ('eael-content-timeline' === $this->get_name()) {
            $this->start_controls_section(
                'eael_section_timeline__filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        } else {
            $this->start_controls_section(
                'eael_section_post__filters',
                [
                    'label' => __('Query', 'essential-addons-elementor'),
                ]
            );
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Source', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );

        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->eael_get_all_types_post(),
                'label_block' => true,
                'multiple' => true,
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );

        $this->add_control(
            'authors', [
                'label' => __('Author', 'essential-addons-elementor'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => $this->eael_get_authors(),
                'condition' => [
                    'post_type!' => 'by_id',
                ],
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            if (!in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $this->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'object_type' => $taxonomy,
                    'options' => wp_list_pluck(get_terms($taxonomy), 'name', 'term_id'),
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }

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
                    'post_type!' => 'by_id',
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
                    'content_timeline_layout',
                    [
                        'label' => esc_html__('Layout', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'center',
                        'options' => [
                            'left'   => esc_html__('Right', 'essential-addons-elementor'),
                            'center' => esc_html__('Center', 'essential-addons-elementor'),
                            'right'  => esc_html__('Left', 'essential-addons-elementor'),
                        ],
                        'default'   => 'center'
                    ]
                );

                $this->add_control(
                    'date_position',
                    [
                        'label' => esc_html__('Date Position', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'inside',
                        'options' => [
                            'inside'   => esc_html__('Inside', 'essential-addons-elementor'),
                            'outside' => esc_html__('Outside', 'essential-addons-elementor')
                        ],
                        'default'   => 'inside',
                        'condition' => [
                            'content_timeline_layout!'  => 'center'
                        ]
                    ]
                );

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
                'eael_content_timeline_circle_icon_new',
                [
                    'label' => esc_html__('Icon', 'essential-addons-elementor'),
                    'fa4compatibility' 		=> 'eael_content_timeline_circle_icon',
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-pencil-alt',
                        'library' => 'fa-solid',
                    ],
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

        $this->add_control(
            'excerpt_expanison_indicator',
            [
                'label' => esc_html__('Expanison Indicator', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => esc_html__('...', 'essential-addons-elementor'),
                'condition' => [
                    'eael_show_excerpt' => '1',
                ],
            ]
        );

        if (
            'eael-post-grid' === $this->get_name()
            || 'eael-post-block' === $this->get_name()
            || 'eael-post-carousel' === $this->get_name()
        ) {
            $this->add_control(
                'eael_show_read_more_button',
                [
                    'label' => __('Show Read More Button', 'essential-addons-elementor'),
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
                'read_more_button_text',
                [
                    'label' => __('Button Text', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Read More', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_show_read_more_button' => '1',
                    ],
                ]
            );
        }

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

    protected function eael_read_more_button_style()
    {
        if (
            'eael-post-grid' === $this->get_name()
            || 'eael-post-block' === $this->get_name()
            || 'eael-post-carousel' === $this->get_name()
            || 'eael-post-list' === $this->get_name()
            || 'eael-post-timeline' === $this->get_name()
        ) {
            $this->start_controls_section(
                'eael_section_read_more_btn',
                [
                    'label' => __('Read More Button Style', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_show_read_more_button' => '1',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'eael_post_read_more_btn_typography',
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                ]
            );

            $this->add_control(
                'eael_post_read_more_btn_color',
                [
                    'label' => esc_html__('Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#61ce70',
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'eael_post_read_more_btn_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }
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

    public function eael_get_query_args($settings = [])
    {
        // fix old settings
        foreach($settings as $key => $value) {
            if(strpos($key, 'eaeposts_') !== false) {
                $settings[str_replace('eaeposts_', '', $key)] = $value;
                unset($settings[$key]);
            }
        };

        $settings = wp_parse_args($settings, [
            'post_type' => 'post',
            'posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
            'post__not_in' => [],
        ]);

        $args = [
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'offset' => $settings['offset']
        ];

        if ('by_id' === $settings['post_type']) {
            $args['post_type'] = 'any';
            $args['post__in'] = empty($settings['posts_ids']) ? [0] : $settings['posts_ids'];
        } else {
            $args['post_type'] = $settings['post_type'];
            $args['tax_query'] = [];

            $taxonomies = get_object_taxonomies($settings['post_type'], 'objects');

            foreach ($taxonomies as $object) {
                $setting_key = $object->name . '_ids';

                if (!empty($settings[$setting_key])) {
                    $args['tax_query'][] = [
                        'taxonomy' => $object->name,
                        'field' => 'term_id',
                        'terms' => $settings[$setting_key],
                    ];
                }
            }

            if (!empty($args['tax_query'])) {
                $args['tax_query']['relation'] = 'OR';
            }
        }

        if (!empty($settings['authors'])) {
            $args['author__in'] = $settings['authors'];
        }

        if (!empty($settings['post__not_in'])) {
            $args['post__not_in'] = $settings['post__not_in'];
        }

        return $args;
    }

    /**
     * Get All POst Types
     * @return array
     */
    public function eael_get_post_types()
    {
        $post_types = get_post_types(['public' => true, 'show_in_nav_menus' => true], 'objects');
        $post_types = wp_list_pluck($post_types, 'label', 'name');

        return array_diff_key($post_types, ['elementor_library', 'attachment']);
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
    public function eael_post_type_categories($type = 'term_id')
    {
        $terms = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->{$type}] = $term->name;
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
     * Get FluentForms List
     * 
     * @return array
     */
    public static function eael_select_fluent_forms()
    {

        $options = array();

        if(defined('FLUENTFORM')) {
            global $wpdb;
            
            $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fluentform_forms" );
            if($result) {
                $options[0] = esc_html__('Select a Fluent Form', 'essential-addons-elementor');
                foreach($result as $form) {
                    $options[$form->id] = $form->title;
                }
            }else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-elementor');
            }
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
        $users = get_users([
            'who' => 'authors',
            'has_published_posts' => true,
            'fields' => [
                'ID',
                'display_name',
            ],
        ]);

        if (!empty($users)) {
            return wp_list_pluck($users, 'display_name', 'ID');
        }

        return [];
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
        parse_str($_REQUEST['args'], $args);
        parse_str($_REQUEST['settings'], $settings);

        $class = '\\' . str_replace('\\\\', '\\', $_REQUEST['class']);
        $args['offset'] = (int) $args['offset'] + (((int) $_REQUEST['page'] - 1) * (int) $args['posts_per_page']);

        if(isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy']['taxonomy'] != 'all') {
            $args['tax_query'] = [
                $_REQUEST['taxonomy']
            ];
        }

        $html = $class::__render_template($args, $settings);

        echo $html;
        wp_die();
    }

    /**
     * Twitter Feed
     *
     * @since 3.0.6
     */
    public function twitter_feed_render_items($id, $settings, $class = '')
    {
        $token = get_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token');
        $items = get_transient($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_cache');
        $html = '';

        if (empty($settings['eael_twitter_feed_consumer_key']) || empty($settings['eael_twitter_feed_consumer_secret'])) {
            return;
        }

        if ($items === false) {
            if (empty($token)) {
                $credentials = base64_encode($settings['eael_twitter_feed_consumer_key'] . ':' . $settings['eael_twitter_feed_consumer_secret']);

                add_filter('https_ssl_verify', '__return_false');

                $response = wp_remote_post('https://api.twitter.com/oauth2/token', [
                    'method' => 'POST',
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => [
                        'Authorization' => 'Basic ' . $credentials,
                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                    ],
                    'body' => ['grant_type' => 'client_credentials'],
                ]);

                $body = json_decode(wp_remote_retrieve_body($response));

                if ($body) {
                    update_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token', $body->access_token);
                    $token = $body->access_token;
                }
            }

            $args = array(
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => "Bearer $token",
                ),
            );

            add_filter('https_ssl_verify', '__return_false');

            $response = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $settings['eael_twitter_feed_ac_name'] . '&count=999&tweet_mode=extended', [
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ]);

            if (!is_wp_error($response)) {
                $items = json_decode(wp_remote_retrieve_body($response), true);
                set_transient($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_cache', $items, 1800);
            }
        }

        if (empty($items)) {
            return;
        }

        if ($settings['eael_twitter_feed_hashtag_name']) {
            foreach ($items as $key => $item) {
                $match = false;

                if ($item['entities']['hashtags']) {
                    foreach ($item['entities']['hashtags'] as $tag) {
                        if (strcasecmp($tag['text'], $settings['eael_twitter_feed_hashtag_name']) == 0) {
                            $match = true;
                        }
                    }
                }

                if ($match == false) {
                    unset($items[$key]);
                }
            }
        }

        $items = array_splice($items, 0, $settings['eael_twitter_feed_post_limit']);

        foreach ($items as $item) {
            $html .= '<div class="eael-twitter-feed-item ' . $class . '">
				<div class="eael-twitter-feed-item-inner">
				    <div class="eael-twitter-feed-item-header clearfix">';
            if ($settings['eael_twitter_feed_show_avatar'] == 'true') {
                $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings['eael_twitter_feed_avatar_style'] . '" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">
                                <img src="' . $item['user']['profile_image_url_https'] . '">
                            </a>';
            }
            $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">';
            if ($settings['eael_twitter_feed_show_icon'] == 'true') {
                $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
            }

            $html .= '<span class="eael-twitter-feed-item-author">' . $item['user']['name'] . '</span>
                        </a>';
            if ($settings['eael_twitter_feed_show_date'] == 'true') {
                $html .= '<span class="eael-twitter-feed-item-date">' . sprintf(__('%s ago', 'essential-addons-elementor'), human_time_diff(strtotime($item['created_at']))) . '</span>';
            }
            $html .= '</div>
                    <div class="eael-twitter-feed-item-content">
                        <p>' . substr(str_replace(@$item['entities']['urls'][0]['url'], '', $item['full_text']), 0, $settings['eael_twitter_feed_content_length']) . '...</p>';

            if ($settings['eael_twitter_feed_show_read_more'] == 'true') {
                $html .= '<a href="//twitter.com/' . @$item['user']['screen_name'] . '/status/' . $item['id'] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
            }
            $html .= '</div>
                    ' . (isset($item['extended_entities']['media'][0]) && $settings['eael_twitter_feed_media'] == 'true' ? ($item['extended_entities']['media'][0]['type'] == 'photo' ? '<img src="' . $item['extended_entities']['media'][0]['media_url_https'] . '">' : '') : '') . '
                </div>
			</div>';
        }

        return $html;
    }

}

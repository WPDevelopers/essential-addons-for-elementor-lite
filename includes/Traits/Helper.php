<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Helper
{
    /**
     * Get all types of post.
     * @return array
     */
    public function eael_get_all_types_post($post_type = 'any')
    {
        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
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
        $post_types['by_id'] = __('Manual Selection', 'essential-addons-for-elementor-lite');
        $taxonomies = get_taxonomies([], 'objects');

        if ('eael-content-ticker' === $this->get_name()) {
            $this->start_controls_section(
                'eael_section_content_ticker_filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_ticker_type' => 'dynamic',
                    ],
                ]
            );
        } elseif ('eael-content-timeline' === $this->get_name()) {
            $this->start_controls_section(
                'eael_section_timeline__filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        } else {
            $this->start_controls_section(
                'eael_section_post__filters',
                [
                    'label' => __('Query', 'essential-addons-for-elementor-lite'),
                ]
            );
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );

        $this->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'essential-addons-for-elementor-lite'),
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
            'authors',
            [
                'label' => __('Author', 'essential-addons-for-elementor-lite'),
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
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
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
                'label' => __('Exclude', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Posts Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->eael_get_post_orderby_options(),
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'essential-addons-for-elementor-lite'),
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
     * Query Controls
     *
     */
    protected function eael_betterdocs_query_controls()
    {
        $this->start_controls_section(
            'eael_section_post__filters',
            [
                'label' => __('Query', 'essential-addons-for-elementor-lite'),
            ]
        );

        if ($this->get_name() === 'eael-betterdocs-category-grid') {
            $this->add_control(
                'grid_query_heading',
                [
                    'label' => __('Category Grid', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );
        }

        $this->add_control(
            'include',
            [
                'label' => __('Include', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'options' => $this->eael_post_type_categories('term_id', 'doc_category'),
                'multiple' => true,
                'default' => [],
            ]
        );

        $this->add_control(
            'exclude',
            [
                'label' => __('Exclude', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->eael_post_type_categories('term_id', 'doc_category'),
                'label_block' => true,
                'post_type' => '',
                'multiple' => true,
            ]
        );

        if ($this->get_name() === 'eael-betterdocs-category-grid') {
            $this->add_control(
                'grid_per_page',
                [
                    'label' => __('Grid Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '8',
                ]
            );
        } else {
            $this->add_control(
                'box_per_page',
                [
                    'label' => __('Box Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '8',
                ]
            );
        }

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'name' => __('Name', 'essential-addons-for-elementor-lite'),
                    'slug' => __('Slug', 'essential-addons-for-elementor-lite'),
                    'term_group' => __('Term Group', 'essential-addons-for-elementor-lite'),
                    'term_id' => __('Term ID', 'essential-addons-for-elementor-lite'),
                    'id' => __('ID', 'essential-addons-for-elementor-lite'),
                    'description' => __('Description', 'essential-addons-for-elementor-lite'),
                    'parent' => __('Parent', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'name',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'asc',
            ]
        );

        if ($this->get_name() === 'eael-betterdocs-category-grid') {
            $this->add_control(
                'grid_posts_query_heading',
                [
                    'label' => __('Grid List Posts', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'post_per_page',
                [
                    'label' => __('Post Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '6',
                ]
            );

            $this->add_control(
                'post_orderby',
                [
                    'label' => __('Order By', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $this->eael_get_post_orderby_options(),
                    'default' => 'date',
                ]
            );

            $this->add_control(
                'post_order',
                [
                    'label' => __('Order', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'asc' => 'Ascending',
                        'desc' => 'Descending',
                    ],
                    'default' => 'desc',
                ]
            );

            $this->add_control(
                'nested_subcategory',
                [
                    'label' => __('Enable Nested Subcategory', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'true',
                    'default' => '',
                ]
            );
        }

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
                'label' => __('Layout Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        if ('eael-post-grid' === $this->get_name()) {
            $this->add_responsive_control(
                'eael_post_grid_columns',
                [
                    'label' => esc_html__('Column', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'eael-col-4',
                    'tablet_default' => 'eael-col-2',
                    'mobile_default' => 'eael-col-1',
                    'options' => [
                        'eael-col-1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                        'eael-col-2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                        'eael-col-3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                        'eael-col-4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                        'eael-col-5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                        'eael-col-6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                    ],
                    'prefix_class' => 'elementor-grid%s-',
                    'frontend_available' => true,
                ]
            );

            $this->add_control(
                'layout_mode',
                [
                    'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'masonry',
                    'options' => [
                        'grid' => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                        'masonry' => esc_html__('Masonry', 'essential-addons-for-elementor-lite'),
                    ],
                ]
            );
        }

        if ('eael-post-block' === $this->get_name()) {
            $this->add_control(
                'grid_style',
                [
                    'label' => esc_html__('Post Block Style Preset', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'post-block-style-default',
                    'options' => [
                        'post-block-style-default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                        'post-block-style-overlay' => esc_html__('Overlay', 'essential-addons-for-elementor-lite'),
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
                        'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'center',
                        'options' => [
                            'left' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                            'center' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                            'right' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        ],
                        'default' => 'center',
                    ]
                );

                $this->add_control(
                    'date_position',
                    [
                        'label' => esc_html__('Date Position', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'inside',
                        'options' => [
                            'inside' => esc_html__('Inside', 'essential-addons-for-elementor-lite'),
                            'outside' => esc_html__('Outside', 'essential-addons-for-elementor-lite'),
                        ],
                        'default' => 'inside',
                        'condition' => [
                            'content_timeline_layout!' => 'center',
                        ],
                    ]
                );
            } else {
                $this->add_control(
                    'show_load_more',
                    [
                        'label' => __('Show Load More', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                        'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                        'return_value' => 'yes',
                        'default' => '',
                    ]
                );

                $this->add_control(
                    'show_load_more_text',
                    [
                        'label' => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'default' => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
                        'condition' => [
                            'show_load_more' => 'yes',
                        ],
                    ]
                );
            }
        }

        if ('eael-content-timeline' !== $this->get_name()) {
            $this->add_control(
                'eael_show_image',
                [
                    'label' => __('Show Image', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'image',
                    'exclude' => ['custom'],
                    'default' => 'medium',
                    'condition' => [
                        'eael_show_image' => 'yes',
                    ],
                ]
            );
        }

        if ('eael-content-timeline' === $this->get_name()) {
            $this->add_control(
                'eael_show_image_or_icon',
                [
                    'label' => __('Show Circle Image / Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'img' => [
                            'title' => __('Image', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-picture-o',
                        ],
                        'icon' => [
                            'title' => __('Icon', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-info',
                        ],
                        'bullet' => [
                            'title' => __('Bullet', 'essential-addons-for-elementor-lite'),
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
                    'label' => esc_html__('Icon Image', 'essential-addons-for-elementor-lite'),
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
                    'label' => esc_html__('Icon Image Size', 'essential-addons-for-elementor-lite'),
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
                    'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                    'fa4compatibility' => 'eael_content_timeline_circle_icon',
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
                'label' => __('Show Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
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
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        if ('eael-post-grid' === $this->get_name() || 'eael-post-carousel' === $this->get_name()) {
            $this->add_control(
                'eael_title_length',
                [
                    'label' => __('Title Length', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => [
                        'eael_show_title' => 'yes',
                    ],
                ]
            );
        }

        $this->add_control(
            'eael_show_excerpt',
            [
                'label' => __('Show excerpt', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        if ('eael-content-timeline' === $this->get_name()) {
            $this->add_control(
                'eael_excerpt_length',
                [
                    'label' => __('Excerpt Words', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 10,
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );

            $this->add_control(
                'excerpt_expanison_indicator',
                [
                    'label' => esc_html__('Expanison Indicator', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'default' => esc_html__('...', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        } else {
            $this->add_control(
                'eael_excerpt_length',
                [
                    'label' => __('Excerpt Words', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 10,
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'excerpt_expanison_indicator',
                [
                    'label' => esc_html__('Expanison Indicator', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'default' => esc_html__('...', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                    ],
                ]
            );
        }

        $this->add_control(
            'eael_show_read_more',
            [
                'label' => __('Show Read More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_content_timeline_choose' => 'dynamic',
                ],
            ]
        );

        $this->add_control(
            'eael_read_more_text',
            [
                'label' => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => esc_html__('Read More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_content_timeline_choose' => 'dynamic',
                    'eael_show_read_more' => 'yes',
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
                    'label' => __('Show Read More Button', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'post_type!' => 'product',
                    ],
                ]
            );

            $this->add_control(
                'read_more_button_text',
                [
                    'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Read More', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_read_more_button' => 'yes',
                        'post_type!' => 'product',
                    ],
                ]
            );
        }

        if ('eael-post-carousel' === $this->get_name() || 'eael-post-grid' === $this->get_name() || 'eael-post-block' === $this->get_name()) {
            $this->add_control(
                'eael_show_post_terms',
                [
                    'label' => __('Show Post Terms', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'condition' => [
                        'eael_show_image' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_post_terms',
                [
                    'label' => __('Show Terms From', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'category' => __('Category', 'essential-addons-for-elementor-lite'),
                        'tags' => __('Tags', 'essential-addons-for-elementor-lite'),
                    ],
                    'default' => 'category',
                    'condition' => [
                        'eael_show_post_terms' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_post_terms_max_length',
                [
                    'label' => __('Max Terms to Show', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        1 => __('1', 'essential-addons-for-elementor-lite'),
                        2 => __('2', 'essential-addons-for-elementor-lite'),
                        3 => __('3', 'essential-addons-for-elementor-lite'),
                    ],
                    'default' => 1,
                    'condition' => [
                        'eael_show_post_terms' => 'yes',
                    ],
                ]
            );
        }

        if ('eael-post-grid' === $this->get_name() || 'eael-post-block' === $this->get_name() || 'eael-post-carousel' === $this->get_name()) {
            $this->add_control(
                'eael_show_meta',
                [
                    'label' => __('Show Meta', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'meta_position',
                [
                    'label' => esc_html__('Meta Position', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'meta-entry-footer',
                    'options' => [
                        'meta-entry-header' => esc_html__('Entry Header', 'essential-addons-for-elementor-lite'),
                        'meta-entry-footer' => esc_html__('Entry Footer', 'essential-addons-for-elementor-lite'),
                    ],
                    'condition' => [
                        'eael_show_meta' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_show_avatar',
                [
                    'label' => __('Show Avatar', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'meta_position' => 'meta-entry-footer',
                        'eael_show_meta' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_show_author',
                [
                    'label' => __('Show Author Name', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'eael_show_meta' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'eael_show_date',
                [
                    'label' => __('Show Date', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'eael_show_meta' => 'yes',
                    ],
                ]
            );
        }

        $this->end_controls_section();
    }

    protected function terms_style()
    {
        $this->start_controls_section(
            'section_terms_style',
            [
                'label' => __('Terms', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'terms_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .post-carousel-categories li a, {{WRAPPER}} .post-carousel-categories li:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'terms_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .post-carousel-categories li a',
            ]
        );

        $this->add_responsive_control(
            'terms_color_alignment',
            [
                'label' => __('Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-carousel-categories' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'terms_spacing',
            [
                'label' => __('Spacing', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .post-carousel-categories li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

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
                    'label' => __('Read More Button Style', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_show_read_more_button' => 'yes',
                        'post_type!' => 'product',
                    ],
                ]
            );

            if ('eael-post-grid' === $this->get_name() || 'eael-post-carousel' === $this->get_name()) {
                $this->add_control(
                    'eael_post_read_more_btn_alignment',
                    [
                        'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
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
                        'default' => ('eael-post-grid' === $this->get_name() ? 'left' : 'center'),
                        'selectors' => [
                            '{{WRAPPER}} .eael-post-elements-readmore-wrap' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
            }

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'eael_post_read_more_btn_typography',
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                ]
            );

            $this->start_controls_tabs('read_more_button_tabs');

            $this->start_controls_tab(
                'read_more_button_style_normal',
                [
                    'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_post_read_more_btn_color',
                [
                    'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#61ce70',
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'read_more_btn_background',
                    'label' => __('Background', 'essential-addons-for-elementor-lite'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'read_more_btn_border',
                    'label' => __('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                ]
            );

            $this->add_responsive_control(
                'read_more_btn_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'read_more_button_style_hover',
                [
                    'label' => __('Hover', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_post_read_more_btn_hover_color',
                [
                    'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'read_more_btn_hover_background',
                    'label' => __('Background', 'essential-addons-for-elementor-lite'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn:hover',
                    'exclude' => [
                        'image',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'read_more_btn_hover_border',
                    'label' => __('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn:hover',
                ]
            );

            $this->add_responsive_control(
                'read_more_btn_border_hover_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'eael_post_read_more_btn_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'read_more_btn_margin',
                [
                    'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => __('Load More Button Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_load_more' => ['yes', '1', 'true'],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_load_more_btn_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
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
        $this->start_controls_tab('eael_post_grid_load_more_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_post_grid_load_more_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-load-more-button',
            ]
        );

        $this->add_control(
            'eael_post_grid_load_more_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
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
        $this->start_controls_tab('eael_post_grid_load_more_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_post_grid_load_more_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Button Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
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
                'description' => '<span class="pro-feature"> Get the  <a href="http://essential-addons.com/elementor/#pricing" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
            ]
        );

        $this->end_controls_section();
    }

    public function fix_old_query($settings)
    {
        $update_query = false;

        foreach ($settings as $key => $value) {
            if (strpos($key, 'eaeposts_') !== false) {
                $settings[str_replace('eaeposts_', '', $key)] = $value;
                $update_query = true;
            }
        }

        if ($update_query) {
            global $wpdb;

            $post_id = get_the_ID();
            $data = get_post_meta($post_id, '_elementor_data', true);
            $data = str_replace('eaeposts_', '', $data);
            $wpdb->update(
                $wpdb->postmeta,
                [
                    'meta_value' => $data,
                ],
                [
                    'post_id' => $post_id,
                    'meta_key' => '_elementor_data',
                ]
            );
        }

        return $settings;
    }

    public function eael_get_query_args($settings = [], $requested_post_type = 'post')
    {
        $settings = wp_parse_args($settings, [
            'post_type' => $requested_post_type,
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
            'offset' => $settings['offset'],
        ];

        if ('by_id' === $settings['post_type']) {
            $args['post_type'] = 'any';
            $args['post__in'] = empty($settings['posts_ids']) ? [0] : $settings['posts_ids'];
        } else {
            $args['post_type'] = $settings['post_type'];

            if ($args['post_type'] !== 'page') {
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
                    $args['tax_query']['relation'] = 'AND';
                }
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
        $orderby = [
            'ID' => 'Post ID',
            'author' => 'Post Author',
            'title' => 'Title',
            'date' => 'Date',
            'modified' => 'Last Modified Date',
            'parent' => 'Parent Id',
            'rand' => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order' => 'Menu Order',
        ];

        return $orderby;
    }

    /**
     * POst Orderby Options
     *
     * @return array
     */
    public function eael_get_product_orderby_options()
    {
        $orderby = [
            'ID' => __('Product ID', 'essential-addons-for-elementor-lite'),
            'title' => __('Product Title', 'essential-addons-for-elementor-lite'),
            '_price' => __('Price', 'essential-addons-for-elementor-lite'),
            '_sku' => __('SKU', 'essential-addons-for-elementor-lite'),
            'date' => __('Date', 'essential-addons-for-elementor-lite'),
            'modified' => __('Last Modified Date', 'essential-addons-for-elementor-lite'),
            'parent' => __('Parent Id', 'essential-addons-for-elementor-lite'),
            'rand' => __('Random', 'essential-addons-for-elementor-lite'),
            'menu_order' => __('Menu Order', 'essential-addons-for-elementor-lite'),
        ];

        return $orderby;
    }

    /**
     * This function is responsible for counting doc post under a category.
     *
     * @param int $term_count
     * @param int $term_id
     * @return int $term_count;
     */
    protected function eael_get_doc_post_count($term_count = 0, $term_id)
    {
        $tax_terms = get_terms('doc_category', ['child_of' => $term_id]);

        foreach ($tax_terms as $tax_term) {
            $term_count += $tax_term->count;
        }
        return $term_count;
    }

    /**
     * Get Post Categories
     *
     * @return array
     */
    public function eael_post_type_categories($type = 'term_id', $term_key = 'category')
    {
        $terms = get_terms([
            'taxonomy' => $term_key,
            'hide_empty' => true,
        ]);

        $options = [];

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
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ]);

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
        $postlist = get_posts([
            'post_type' => 'product',
            'showposts' => 9999,
        ]);
        $options = [];

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
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ]);

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
        $options = [];

        if (function_exists('wpcf7')) {
            $wpcf7_form_list = get_posts([
                'post_type' => 'wpcf7_contact_form',
                'showposts' => 999,
            ]);
            $options[0] = esc_html__('Select a Contact Form', 'essential-addons-for-elementor-lite');
            if (!empty($wpcf7_form_list) && !is_wp_error($wpcf7_form_list)) {
                foreach ($wpcf7_form_list as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

        if (class_exists('GFCommon')) {
            $gravity_forms = \RGFormsModel::get_forms(null, 'title');

            if (!empty($gravity_forms) && !is_wp_error($gravity_forms)) {
                $options[0] = esc_html__('Select Gravity Form', 'essential-addons-for-elementor-lite');
                foreach ($gravity_forms as $form) {
                    $options[$form->id] = $form->title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $wpuf_form_list = get_posts([
            'post_type' => 'wpuf_contact_form',
            'showposts' => 999,
        ]);

        $options = [];

        if (!empty($wpuf_form_list) && !is_wp_error($wpuf_form_list)) {
            $options[0] = esc_html__('Select weForm', 'essential-addons-for-elementor-lite');
            foreach ($wpuf_form_list as $post) {
                $options[$post->ID] = $post->post_title;
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

        if (class_exists('Ninja_Forms')) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select Ninja Form', 'essential-addons-for-elementor-lite');

                foreach ($contact_forms as $form) {
                    $options[$form->get_id()] = $form->get_setting('title');
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

        if (class_exists('Caldera_Forms')) {
            $contact_forms = \Caldera_Forms_Forms::get_forms(true, true);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select Caldera Form', 'essential-addons-for-elementor-lite');
                foreach ($contact_forms as $form) {
                    $options[$form['ID']] = $form['name'];
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

        if (class_exists('\WPForms\WPForms')) {
            $args = [
                'post_type' => 'wpforms',
                'posts_per_page' => -1,
            ];

            $contact_forms = get_posts($args);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select a WPForm', 'essential-addons-for-elementor-lite');
                foreach ($contact_forms as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

        if (defined('FLUENTFORM')) {
            global $wpdb;

            $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}fluentform_forms");
            if ($result) {
                $options[0] = esc_html__('Select a Fluent Form', 'essential-addons-for-elementor-lite');
                foreach ($result as $form) {
                    $options[$form->id] = $form->title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
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
        $options = [];

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
     * @param  array  $args
     *
     * @return array
     */
    public function eael_get_tags($args = [])
    {
        $options = [];
        $tags = get_tags($args);

        if (is_wp_error($tags)) {
            return [];
        }

        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name;
        }

        return $options;
    }

    /**
     * Get all taxonomies by post
     *
     * @param  array   $args
     *
     * @param  string  $output
     * @param  string  $operator
     *
     * @return array
     */
    public function eael_get_taxonomies_by_post($args = [], $output = 'names', $operator = 'and')
    {
        global $wp_taxonomies;

        $field = ('names' === $output) ? 'name' : false;

        // Handle 'object_type' separately.
        if (isset($args['object_type'])) {
            $object_type = (array) $args['object_type'];
            unset($args['object_type']);
        }

        $taxonomies = wp_filter_object_list($wp_taxonomies, $args, $operator);

        if (isset($object_type)) {
            foreach ($taxonomies as $tax => $tax_data) {
                if (!array_intersect($object_type, $tax_data->object_type)) {
                    unset($taxonomies[$tax]);
                }
            }
        }

        if ($field) {
            $taxonomies = wp_list_pluck($taxonomies, $field);
        }

        return $taxonomies;
    }

    /**
     * Get all Posts
     *
     * @return array
     */
    public function eael_get_posts()
    {
        $post_list = get_posts([
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ]);

        $posts = [];

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
        $page_list = get_posts([
            'post_type' => 'page',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ]);

        $pages = [];

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

        if (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy']['taxonomy'] != 'all') {
            $args['tax_query'] = [
                $_REQUEST['taxonomy'],
            ];
        }

        if ($class == '\Essential_Addons_Elementor\Elements\Post_Grid' && $settings['orderby'] === 'rand') {
            $args['post__not_in'] = array_unique($_REQUEST['post__not_in']);
        }

        $html = $class::render_template_($args, $settings);

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

            $args = [
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ];

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
                        if (
                            (isset($tag['text']) && isset($settings['eael_twitter_feed_hashtag_name'])) &&
                            strcasecmp($tag['text'], $settings['eael_twitter_feed_hashtag_name']) == 0
                        ) {
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
            $delimeter = strlen($item['full_text']) > $settings['eael_twitter_feed_content_length'] ? '...' : '';
            $has_media = (isset($item['extended_entities']['media'][0]) && $settings['eael_twitter_feed_media'] == 'true' ? true : false);
            if ($settings['eael_twitter_feed_card_choose_style'] == 'three') {
                $html .= '<div class="eael-twitter-feed-item eael-twitter-feed-item-style-three ' . $class . '">
                <div class="eael-twitter-feed-item-inner' . ($has_media === true ? ' has-media' : '') . '">';
                // left icon bar
                $html .= '<div class="eael-twitter-feed-entry-iconwrap">';
                if ($settings['eael_twitter_feed_show_avatar'] == 'true') {
                    $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings['eael_twitter_feed_avatar_style'] . '" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">
                                 <img src="' . $item['user']['profile_image_url_https'] . '">
                             </a>';
                }
                if ($settings['eael_twitter_feed_show_icon'] == 'true') {
                    $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
                }
                $html .= '</div>';
                // content
                $html .= '<div class="eael-twitter-feed-entry-contentwrap">
                    <div class="eael-twitter-feed-item-header clearfix">';

                $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">';
                $html .= '<span class="eael-twitter-feed-item-author">' . $item['user']['name'] . '</span>
                            </a>';
                if ($settings['eael_twitter_feed_show_date'] == 'true') {
                    $html .= '<span class="eael-twitter-feed-item-date">' . sprintf(__('%s ago', 'essential-addons-for-elementor-lite'), human_time_diff(strtotime($item['created_at']))) . '</span>';
                }
                $html .= '</div>
                        <div class="eael-twitter-feed-item-content">
                            <p>' . substr((isset($item['entities']['urls'][0]['url']) ? str_replace(@$item['entities']['urls'][0]['url'], '', $item['full_text']) : $item['full_text']), 0, $settings['eael_twitter_feed_content_length']) . $delimeter  . '</p>';

                if ($settings['eael_twitter_feed_show_read_more'] == 'true') {
                    $html .= '<a href="//twitter.com/' . @$item['user']['screen_name'] . '/status/' . $item['id_str'] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
                }
                $html .= '</div>
                        ' . ($has_media ? ($item['extended_entities']['media'][0]['type'] == 'photo' ? '<img src="' . $item['extended_entities']['media'][0]['media_url_https'] . '">' : '') : '') . '
                </div>
                </div>
			</div>';
            } elseif ($settings['eael_twitter_feed_card_choose_style'] == 'two') {
                $html .= '<div class="eael-twitter-feed-item eael-twitter-feed-item-style-two ' . $class . '">
				<div class="eael-twitter-feed-item-inner' . ($has_media === true ? ' has-media' : '') . '">
				    <div class="eael-twitter-feed-item-header clearfix">';
                if ($settings['eael_twitter_feed_show_avatar'] == 'true') {
                    $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings['eael_twitter_feed_avatar_style'] . '" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">
                                <img src="' . $item['user']['profile_image_url_https'] . '">
                            </a>';
                }
                $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">';
                $html .= '<span class="eael-twitter-feed-item-author">' . $item['user']['name'] . '</span>
                        </a>';
                if ($settings['eael_twitter_feed_show_icon'] == 'true') {
                    $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
                }
                $html .= '</div>
                    <div class="eael-twitter-feed-item-content">
                        <p>' . substr((isset($item['entities']['urls'][0]['url']) ? str_replace(@$item['entities']['urls'][0]['url'], '', $item['full_text']) : $item['full_text']), 0, $settings['eael_twitter_feed_content_length']) . $delimeter . '</p>';

                if ($settings['eael_twitter_feed_show_read_more'] == 'true') {
                    $html .= '<a href="//twitter.com/' . @$item['user']['screen_name'] . '/status/' . $item['id_str'] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
                }
                $html .= '</div>
                    ' . ($has_media ? ($item['extended_entities']['media'][0]['type'] == 'photo' ? '<img src="' . $item['extended_entities']['media'][0]['media_url_https'] . '">' : '') : '') . '
                </div>
			</div>';
            } else {
                $html .= '<div class="eael-twitter-feed-item ' . $class . '">
				<div class="eael-twitter-feed-item-inner' . ($has_media === true ? ' has-media' : '') . '">
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
                    $html .= '<span class="eael-twitter-feed-item-date">' . sprintf(__('%s ago', 'essential-addons-for-elementor-lite'), human_time_diff(strtotime($item['created_at']))) . '</span>';
                }
                $html .= '</div>
                    <div class="eael-twitter-feed-item-content">
                        <p>' . substr((isset($item['entities']['urls'][0]['url']) ? str_replace(@$item['entities']['urls'][0]['url'], '', $item['full_text']) : $item['full_text']), 0, $settings['eael_twitter_feed_content_length']) . $delimeter . '</p>';

                if ($settings['eael_twitter_feed_show_read_more'] == 'true') {
                    $html .= '<a href="//twitter.com/' . @$item['user']['screen_name'] . '/status/' . $item['id_str'] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
                }
                $html .= '</div>
                    ' . ($has_media ? ($item['extended_entities']['media'][0]['type'] == 'photo' ? '<img src="' . $item['extended_entities']['media'][0]['media_url_https'] . '">' : '') : '') . '
                </div>
			</div>';
            }
        }

        return $html;
    }

    /**
     * Facebook Feed
     *
     * @since 3.4.0
     */
    public function facebook_feed_render_items()
    {
        // check if ajax request
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'facebook_feed_load_more') {
            // check ajax referer
            check_ajax_referer('essential-addons-elementor', 'security');

            // init vars
            $page = $_REQUEST['page'];
            parse_str($_REQUEST['settings'], $settings);
        } else {
            // init vars
            $page = 0;
            $settings = $this->get_settings_for_display();
        }

        $html = '';
        $page_id = $settings['eael_facebook_feed_page_id'];
        $token = $settings['eael_facebook_feed_access_token'];

        if (empty($page_id) || empty($token)) {
            return;
        }

        $key = 'eael_facebook_feed_' . substr(str_rot13(str_replace('.', '', $page_id . $token)), 32);

        if (get_transient($key) === false) {
            $facebook_data = wp_remote_retrieve_body(wp_remote_get("https://graph.facebook.com/v7.0/{$page_id}/posts?fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url},comments.summary(total_count),reactions.summary(total_count)&access_token={$token}"));
            set_transient($key, $facebook_data, 1800);
        } else {
            $facebook_data = get_transient($key);
        }

        $facebook_data = json_decode($facebook_data, true);

        if (isset($facebook_data['data'])) {
            $facebook_data = $facebook_data['data'];
        } else {
            return;
        }

        switch ($settings['eael_facebook_feed_sort_by']) {
            case 'least-recent':
                $facebook_data = array_reverse($facebook_data);
                break;
        }

        $items = array_splice($facebook_data, ($page * $settings['eael_facebook_feed_image_count']['size']), $settings['eael_facebook_feed_image_count']['size']);
        foreach ($items as $item) {
            $message = wp_trim_words((isset($item['message']) ? $item['message'] : (isset($item['story']) ? $item['story'] : '')), $settings['eael_facebook_feed_message_max_length']['size'], '...');
            $photo = (isset($item['full_picture']) ? $item['full_picture'] : '');
            $likes = (isset($item['reactions']) ? $item['reactions']['summary']['total_count'] : 0);
            $comments = (isset($item['comments']) ? $item['comments']['summary']['total_count'] : 0);

            if ($settings['eael_facebook_feed_layout'] == 'card') {
                $html .= '<div class="eael-facebook-feed-item eael-facebook-feed-item-style-' . $settings['eael_facebook_feed_header_layout'] . '">
                    <div class="eael-facebook-feed-item-inner">';
                // card header
                if ($settings['eael_facebook_feed_header_layout'] == 'two') {
                    $html .= '<header class="eael-facebook-feed-item-header clearfix">
                    <div class="eael-facebook-feed-item-user clearfix">
                        <a href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '"><img src="https://graph.facebook.com/v4.0/' . $page_id . '/picture" alt="' . $item['from']['name'] . '" class="eael-facebook-feed-avatar"></a>';
                    $html .= '<div class="eael-facebook-feed-header-content">';
                    $html .= '<a class="eael-facebook-feed-username" href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '">' . $item['from']['name'] . '</a>';
                    if ($settings['eael_facebook_feed_date']) {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] ? '_blank' : '_self') . '" class="eael-facebook-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . date('d M Y', strtotime($item['created_time'])) . '</a>';
                    }
                    $html .= '</div>';

                    $html .= '</div></header>';
                } else {
                    $html .= '<header class="eael-facebook-feed-item-header clearfix">
                    <div class="eael-facebook-feed-item-user clearfix">
                        <a href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '"><img src="https://graph.facebook.com/v4.0/' . $page_id . '/picture" alt="' . $item['from']['name'] . '" class="eael-facebook-feed-avatar"></a>
                        <a href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '"><p class="eael-facebook-feed-username">' . $item['from']['name'] . '</p></a>
                    </div>';

                    if ($settings['eael_facebook_feed_date']) {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] ? '_blank' : '_self') . '" class="eael-facebook-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . date('d M Y', strtotime($item['created_time'])) . '</a>';
                    }
                    $html .= '</header>';
                }

                // normal message
                if ($settings['eael_facebook_feed_message'] && !empty($message)) {
                    $html .= '<div class="eael-facebook-feed-item-content">
                                        <p class="eael-facebook-feed-message">' . esc_html($message) . '</p>
                                    </div>';
                }

                // preview content
                if (!empty($photo) || isset($item['attachments']['data'])) {
                    $html .= '<div class="eael-facebook-feed-preview-wrap">';
                    if ($item['status_type'] == 'shared_story') {
                        if ($settings['eael_facebook_feed_is_show_preview_content'] == 'yes' && $settings['eael_facebook_feed_is_show_preview_thumbnail'] == 'yes') {
                            $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">';
                            if ($item['attachments']['data'][0]['media_type'] == 'video') {
                                $html .= '<img class="eael-facebook-feed-img" src="' . $photo . '">
                                                    <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>';
                            } else {
                                $html .= '<img class="eael-facebook-feed-img" src="' . $photo . '">';
                            }
                            $html .= '</a>';
                        }
                        if ($settings['eael_facebook_feed_is_show_preview_content'] == 'yes') {
                            $html .= '<div class="eael-facebook-feed-url-preview">';
                            if ($settings['eael_facebook_feed_is_show_preview_host'] == 'yes') {
                                $html .= '<p class="eael-facebook-feed-url-host">' . parse_url($item['attachments']['data'][0]['unshimmed_url'])['host'] . '</p>';
                            }
                            if ($settings['eael_facebook_feed_is_show_preview_title'] == 'yes') {
                                $html .= '<h2 class="eael-facebook-feed-url-title">' . $item['attachments']['data'][0]['title'] . '</h2>';
                            }
                            if ($settings['eael_facebook_feed_is_show_preview_description'] == 'yes') {
                                $html .= '<p class="eael-facebook-feed-url-description">' . @$item['attachments']['data'][0]['description'] . '</p>';
                            }
                            $html .= '</div>';
                        }
                    } elseif ($item['status_type'] == 'added_video') {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">
                                                <img class="eael-facebook-feed-img" src="' . $photo . '">
                                                <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>
                                            </a>';
                    } else {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">
                                                <img class="eael-facebook-feed-img" src="' . $photo . '">
                                            </a>';
                    }
                    $html .= '</div>';
                }

                // card footer
                if ($settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments']) {
                    $html .= '<footer class="eael-facebook-feed-item-footer">
                                <div class="clearfix">';
                    if ($settings['eael_facebook_feed_likes']) {
                        $html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
                    }
                    if ($settings['eael_facebook_feed_comments']) {
                        $html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $comments . '</span>';
                    }
                    $html .= '</div>
                            </footer>';
                }

                $html .= '</div>
                </div>';
            } else {
                $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] ? '_blank' : '_self') . '" class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                        <img class="eael-facebook-feed-img" src="' . (empty($photo) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : $photo) . '">';

                if ($settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments']) {
                    $html .= '<div class="eael-facebook-feed-item-overlay">
                                        <div class="eael-facebook-feed-item-overlay-inner">
                                            <div class="eael-facebook-feed-meta">';
                    if ($settings['eael_facebook_feed_likes']) {
                        $html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
                    }
                    if ($settings['eael_facebook_feed_comments']) {
                        $html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $comments . '</span>';
                    }
                    $html .= '</div>
                                        </div>
                                    </div>';
                }
                $html .= '</div>
                </a>';
            }
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'facebook_feed_load_more') {
            wp_send_json([
                'num_pages' => ceil(count($facebook_data) / $settings['eael_facebook_feed_image_count']['size']),
                'html' => $html,
            ]);
        }

        return $html;
    }

    /**
     * @param $page_obj
     * @param $key
     * @return string
     */
    public function eael_get_extension_settings($page_settings_model = [], $global_settings = [], $extension, $key)
    {
        if (isset($page_settings_model) && $page_settings_model->get_settings($extension) == 'yes') {
            return $page_settings_model->get_settings($key);
        } elseif (isset($global_settings[$extension]['enabled'])) {
            return isset($global_settings[$extension][$key]) ? $global_settings[$extension][$key] : '';
        }

        return '';
    }

    /**
     * @param $post_css
     * @param $elements
     * @return string|void
     */
    public function eael_toc_global_css($page_settings_model, $global_settings)
    {
        $eael_toc = $global_settings['eael_ext_table_of_content'];
        $eael_toc_width = isset($eael_toc['eael_ext_toc_width']['size']) ? $eael_toc['eael_ext_toc_width']['size'] : 300;
        $toc_list_color_active = $eael_toc['eael_ext_table_of_content_list_text_color_active'];
        $toc_list_separator_style = $eael_toc['eael_ext_table_of_content_list_separator_style'];
        $header_padding = $eael_toc['eael_ext_toc_header_padding'];
        $body_padding = $eael_toc['eael_ext_toc_body_padding'];
        $header_typography = $this->eael_get_typography_data('eael_ext_table_of_content_header_typography', $eael_toc);
        $list_typography = $this->eael_get_typography_data('eael_ext_table_of_content_list_typography_normal', $eael_toc);
        $box_shadow = $eael_toc['eael_ext_toc_table_box_shadow_box_shadow'];
        $border_radius = $eael_toc['eael_ext_toc_box_border_radius']['size'];
        $bullet_size = $eael_toc['eael_ext_toc_box_list_bullet_size']['size'];
        $top_position = $eael_toc['eael_ext_toc_box_list_top_position']['size'];
        $indicator_size = $eael_toc['eael_ext_toc_indicator_size']['size'];
        $indicator_position = $eael_toc['eael_ext_toc_indicator_position']['size'];
        $close_bt_box_shadow = $eael_toc['eael_ext_table_of_content_close_button_box_shadow'];
        $toc_global_css = "
            .eael-toc-global .eael-toc-header,
            .eael-toc-global.collapsed .eael-toc-button
            {
                background-color:{$eael_toc['eael_ext_table_of_content_header_bg']};
            }

            .eael-toc-global {
                width:{$eael_toc_width}px;
                z-index:{$eael_toc['eael_ext_toc_sticky_z_index']['size']};
            }

            .eael-toc-global.eael-sticky {
                top:{$eael_toc['eael_ext_toc_sticky_offset']['size']};
            }
            .eael-toc-global .eael-toc-header .eael-toc-title,
            .eael-toc-global.collapsed .eael-toc-button
            {
                color:{$eael_toc['eael_ext_table_of_content_header_text_color']};
                $header_typography
            }
            .eael-toc-global .eael-toc-header {
                padding:{$header_padding['top']}px {$header_padding['right']}px {$header_padding['bottom']}px {$header_padding['left']}px;
            }

            .eael-toc-global .eael-toc-body {
                padding:{$body_padding['top']}px {$body_padding['right']}px {$body_padding['bottom']}px {$body_padding['left']}px;
            }

            .eael-toc-global .eael-toc-close
            {
                font-size: {$eael_toc['eael_ext_table_of_content_close_button_icon_size']['size']}px !important;
                height: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                width: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                line-height: {$eael_toc['eael_ext_table_of_content_close_button_line_height']['size']}px !important;
                color:{$eael_toc['eael_ext_table_of_content_close_button_text_color']} !important;
                background-color:{$eael_toc['eael_ext_table_of_content_close_button_bg']} !important;
                border-radius: {$eael_toc['eael_ext_table_of_content_close_button_border_radius']['size']}px !important;
                box-shadow:{$close_bt_box_shadow['horizontal']}px {$close_bt_box_shadow['vertical']}px {$close_bt_box_shadow['blur']}px {$close_bt_box_shadow['spread']}px {$close_bt_box_shadow['color']} !important;
            }

            .eael-toc-global.eael-toc:not(.collapsed)
            {
                box-shadow:{$box_shadow['horizontal']}px {$box_shadow['vertical']}px {$box_shadow['blur']}px {$box_shadow['spread']}px {$box_shadow['color']};
            }

            .eael-toc-global .eael-toc-body
            {
                background-color:{$eael_toc['eael_ext_table_of_content_body_bg']};
            }

            .eael-toc-global .eael-toc-body ul.eael-toc-list.eael-toc-bullet li:before
            {
                width:{$bullet_size}px;
                height:{$bullet_size}px;
                top:{$top_position}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li a
            {
                color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:before {
                background-color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a
            {
                color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:before {
                border-bottom-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:after {
                background-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-parent:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent > a
            {
                color:$toc_list_color_active !important;
            }


            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:before
            {
                border-bottom-color:$toc_list_color_active !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:after,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-parent:before
            {
                background-color:$toc_list_color_active !important;
            }

            .eael-toc-global ul.eael-toc-list > li
            {
                color:{$eael_toc['eael_ext_table_of_content_list_separator_color']} !important;
                $list_typography
            }
            .eael-toc.eael-toc-global .eael-toc-body ul.eael-toc-list li:before {
                $list_typography
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                height:{$indicator_size}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-arrow li.eael-highlight-active > a:before,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                margin-top:{$indicator_position}px;
            }


            .eael-toc:not(.eael-toc-right)
            {
                border-top-right-radius:{$border_radius}px;
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-header
            {
                border-top-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-body {
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right {
                border-top-left-radius:{$border_radius}px;
                border-bottom-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-header {
                border-top-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-body {
                border-bottom-left-radius:{$border_radius}px;
            }


            #eael-toc.eael-toc-global ul.eael-toc-list > li
            {
                padding-top:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
            }

            #eael-toc.eael-toc-global ul.eael-toc-list>li ul li
            {
                padding-top:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
            }
        ";
        if ($toc_list_separator_style != 'none') {
            $toc_global_css .= "
            .eael-toc-global ul.eael-toc-list > li
            {border-top: 0.5px $toc_list_separator_style !important;}
            .eael-toc ul.eael-toc-list>li:first-child
            {border: none !important;}";
        }

        if (isset($eael_toc['eael_ext_toc_border_border'])) {
            $border_width = $eael_toc['eael_ext_toc_border_width'];
            $toc_global_css .= "
            .eael-toc.eael-toc-global,.eael-toc-global button.eael-toc-button
            {
                border-style: {$eael_toc['eael_ext_toc_border_border']};
                border-width: {$border_width['top']}px {$border_width['right']}px {$border_width['bottom']}px {$border_width['left']}px;
                border-color: {$eael_toc['eael_ext_toc_border_color']};
            }";
        }

        wp_register_style('eael-toc-global', false);
        wp_enqueue_style('eael-toc-global');
        wp_add_inline_style('eael-toc-global', $toc_global_css);
    }

    /**
     * @param $id
     * @param $global_data
     * @return string
     */
    public function eael_get_typography_data($id, $global_data)
    {
        $typo_data = '';
        $fields_keys = [
            'font_family',
            'font_weight',
            'text_transform',
            'font_style',
            'text_decoration',
            'font_size',
            'letter_spacing',
            'line_height',
        ];
        foreach ($fields_keys as $key => $field) {
            $typo_attr = $global_data[$id . '_' . $field];
            $attr = str_replace('_', '-', $field);
            if (in_array($field, ['font_size', 'letter_spacing', 'line_height'])) {
                if (!empty($typo_attr['size'])) {
                    $typo_data .= "{$attr}:{$typo_attr['size']}{$typo_attr['unit']} !important;";
                }
            } elseif (!empty($typo_attr)) {
                $typo_data .= ($attr == 'font-family') ? "{$attr}:{$typo_attr}, sans-serif;" : "{$attr}:{$typo_attr};";
            }
        }
        return $typo_data;
    }

    public function eael_language_code_list()
    {
        return [
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'ar' => 'Arabic',
            'eu' => 'Basque',
            'bn' => 'Bengali',
            'bs' => 'Bosnian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'zh-cn' => 'Chinese',
            'zh-tw' => 'Chinese-tw',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'et' => 'Estonian',
            'fi' => 'Finnish',
            'fr' => 'French',
            'gl' => 'Galician',
            'ka' => 'Georgian',
            'de' => 'German',
            'el' => 'Greek (Modern)',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'io' => 'Ido',
            'id' => 'Indonesian',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'kk' => 'Kazakh',
            'ko' => 'Korean',
            'lv' => 'Latvian',
            'lb' => 'Letzeburgesch',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ro' => 'Moldovan, Moldavian, Romanian',
            'nb' => 'Norwegian Bokmål',
            'nn' => 'Norwegian Nynorsk',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'sr' => 'Serbian',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'es' => 'Spanish',
            'sv' => 'Swedish',
            'tr' => 'Turkish',
            'uk' => 'Ukrainian',
            'vi' => 'Vietnamese',
        ];
    }

    /**
     * @since  3.8.2
     * @param $source
     *
     * @return array
     */
    public function eael_event_calendar_source($source)
    {
        if (apply_filters('eael/pro_enabled', false)) {
            $source['eventon'] = __('EventON', 'essential-addons-for-elementor-lite');
        } else {
            $source['eventon'] = __('EventON (Pro) ', 'essential-addons-for-elementor-lite');
        }

        return $source;
    }

    public function eael_list_ninja_tables()
    {
        $tables = get_posts([
            'post_type' => 'ninja-table',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
        ]);

        if (!empty($tables)) {
            return wp_list_pluck($tables, 'post_title', 'ID');
        }

        return [];
    }

    public function advanced_data_table_source_control($wb)
    {
        if (apply_filters('eael/active_plugins', 'ninja-tables/ninja-tables.php')) {
            $wb->add_control(
                'ea_adv_data_table_source_ninja_table_id',
                [
                    'label' => esc_html__('Table ID', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $this->eael_list_ninja_tables(),
                    'condition' => [
                        'ea_adv_data_table_source' => 'ninja',
                    ],
                ]
            );
        } else {
            $wb->add_control(
                'ea_adv_data_table_ninja_required',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>Ninja Tables</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=Ninja+Tables&tab=search&type=term" target="_blank">Ninja Tables</a> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                    'condition' => [
                        'ea_adv_data_table_source' => 'ninja',
                    ],
                ]
            );
        }
    }

    public function advanced_data_table_ninja_integration($settings)
    {
        if (empty($settings['ea_adv_data_table_source_ninja_table_id'])) {
            return;
        }

        $html = '';
        $table_settings = ninja_table_get_table_settings($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_headers = ninja_table_get_table_columns($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_rows = ninjaTablesGetTablesDataByID($settings['ea_adv_data_table_source_ninja_table_id']);

        if (!empty($table_rows)) {
            if (!isset($table_settings['hide_header_row']) || $table_settings['hide_header_row'] != true) {
                $html .= '<thead><tr>';
                foreach ($table_headers as $key => $th) {
                    $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                    $html .= '<th' . $style . '>' . $th['name'] . '</th>';
                }
                $html .= '</tr></thead>';
            }

            $html .= '<tbody>';
            foreach ($table_rows as $key => $tr) {
                $html .= '<tr>';
                foreach ($table_headers as $th) {
                    if (!isset($th['data_type'])) {
                        $th['data_type'] = '';
                    }

                    if ($th['data_type'] == 'image') {
                        $html .= '<td>' . (isset($tr[$th['key']]['image_thumb']) ? '<a href="' . $tr[$th['key']]['image_full'] . '"><img src="' . $tr[$th['key']]['image_thumb'] . '"></a>' : '') . '</td>';
                    } elseif ($th['data_type'] == 'selection') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? implode((array) $tr[$th['key']], ', ') : '') . '</td>';
                    } elseif ($th['data_type'] == 'button') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? '<a href="' . $tr[$th['key']] . '" class="button" target="' . $th['link_target'] . '">' . $th['button_text'] . '</a>' : '') . '</td>';
                    } else {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? $tr[$th['key']] : '') . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        return $html;
    }

    protected static function get_terms_as_list($term_type = 'category', $length = 1)
    {
        if ($term_type === 'category') {
            $terms = get_the_category();
        }

        if ($term_type === 'tags') {
            $terms = get_the_tags();
        }

        if (empty($terms)) {
            return;
        }

        $html = '<ul class="post-carousel-categories">';
        $count = 0;
        foreach ($terms as $term) {
            if ($count === $length) {
                break;
            }
            $link = ($term_type === 'category') ? get_category_link($term->term_id) : get_tag_link($term->term_id);
            $html .= '<li>';
            $html .= '<a href="' . esc_url($link) . '">';
            $html .= $term->name;
            $html .= '</a>';
            $html .= '</li>';
            $count++;
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Woo Checkout
     */
    public function woo_checkout_update_order_review()
    {
        $setting = $_POST['orderReviewData'];
        ob_start();
        Woo_Checkout::checkout_order_review_default($setting);
        $woo_checkout_update_order_review = ob_get_clean();

        wp_send_json(
            [
                'order_review' => $woo_checkout_update_order_review,
            ]
        );
    }

    public function eael_controls_custom_positioning($prefix, $section_name, $css_selector, $condition = [])
    {
        $selectors = '{{WRAPPER}} ' . $css_selector;
        $this->start_controls_section(
            $prefix . '_section_position',
            [
                'label' => $section_name,
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => $condition,
            ]
        );

        $this->add_control(
            $prefix . '_position',
            [
                'label' => __('Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default', 'essential-addons-for-elementor-lite'),
                    'absolute' => __('Absolute', 'essential-addons-for-elementor-lite'),
                ],
                'selectors' => [
                    $selectors => 'position: {{VALUE}}',
                ],
            ]
        );

        $start = is_rtl() ? __('Right', 'essential-addons-for-elementor-lite') : __('Left', 'essential-addons-for-elementor-lite');
        $end = !is_rtl() ? __('Right', 'essential-addons-for-elementor-lite') : __('Left', 'essential-addons-for-elementor-lite');

        $this->add_control(
            $prefix . '_offset_orientation_h',
            [
                'label' => __('Horizontal Orientation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
                'condition' => [
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_offset_x',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.rtl) ' . $selectors => 'left: {{SIZE}}{{UNIT}}',
                    'body.rtl ' . $selectors => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    $prefix . '_offset_orientation_h!' => 'end',
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_offset_x_end',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.rtl) ' . $selectors => 'right: {{SIZE}}{{UNIT}}',
                    'body.rtl ' . $selectors => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    $prefix . '_offset_orientation_h' => 'end',
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_offset_orientation_v',
            [
                'label' => __('Vertical Orientation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => __('Top', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => __('Bottom', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_offset_y',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    $selectors => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    $prefix . '_offset_orientation_v!' => 'end',
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_offset_y_end',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    $selectors => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    $prefix . '_offset_orientation_v' => 'end',
                    $prefix . '_position!' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /** Filter to add plugins to the TOC list.
     *
     * @since  3.9.3
     * @param array TOC plugins.
     *
     * @return mixed
     */
    public function eael_toc_rank_math_support($toc_plugins)
    {
        $toc_plugins['essential-addons-for-elementor-lite/essential_adons_elementor.php'] = __('Essential Addons for Elementor', 'essential-addons-for-elementor-lite');
        return $toc_plugins;
    }

    /**
     * Save typeform access token
     *
     * @since  4.0.2
     */
    public function eael_typeform_auth_handle()
    {
        $post = $_POST;
        if (isset($post['typeform_tk']) && isset($post['pr_code'])) {
            if (wp_hash('eael_typeform') === $post['pr_code']) {
                update_option('eael_save_typeform_personal_token', sanitize_text_field($post['typeform_tk']));
            }
        }
        wp_send_json_success(['status' => 'success']);
    }
}

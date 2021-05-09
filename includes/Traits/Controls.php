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
use \Essential_Addons_Elementor\Classes\Helper as ControlsHelper;

trait Controls
{
    /**
     * Query Controls
     *
     */
    public static function query($wb)
    {
        $post_types = ControlsHelper::get_post_types();
        $post_types['by_id'] = __('Manual Selection', 'essential-addons-for-elementor-lite');

        if ($wb->get_name() !== 'eael-dynamic-filterable-gallery' && $wb->get_name() !== 'eael-post-list') {
            $post_types['source_dynamic'] = __('Dynamic', 'essential-addons-for-elementor-lite');
        }

        $taxonomies = get_taxonomies([], 'objects');

        if ('eael-content-ticker' === $wb->get_name()) {
            $wb->start_controls_section(
                'eael_section_content_ticker_filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_ticker_type' => 'dynamic',
                    ],
                ]
            );
        } else if ('eael-content-timeline' === $wb->get_name()) {
            $wb->start_controls_section(
                'eael_section_timeline__filters',
                [
                    'label' => __('Dynamic Content Settings', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        } else {
            $wb->start_controls_section(
                'eael_section_post__filters',
                [
                    'label' => __('Query', 'essential-addons-for-elementor-lite'),
                ]
            );
        }

        $wb->add_control(
            'post_type',
            [
                'label' => __('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default' => key($post_types),
            ]
        );

        $wb->add_control(
            'eael_global_dynamic_source_warning_text',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'post_type' => 'source_dynamic',
                ],
            ]
        );

        $wb->add_control(
            'posts_ids',
            [
                'label' => __('Search & Select', 'essential-addons-for-elementor-lite'),
                'type' => 'eael-select2',
                'options' => ControlsHelper::get_post_list(),
                'label_block' => true,
                'multiple'    => true,
                'source_name' => 'post_type',
                'source_type' => 'any',
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ]
        );

        $wb->add_control(
            'authors', [
                'label' => __('Author', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => ControlsHelper::get_authors_list(),
                'condition' => [
                    'post_type!' => ['by_id', 'source_dynamic'],
                ],
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            if (!isset($object->object_type[0]) || !in_array($object->object_type[0], array_keys($post_types))) {
                continue;
            }

            $wb->add_control(
                $taxonomy . '_ids',
                [
                    'label' => $object->label,
                    'type' => 'eael-select2',
                    'label_block' => true,
                    'multiple' => true,
                    'source_name' => 'taxonomy',
                    'source_type' => $taxonomy,
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ]
            );
        }

	    $wb->add_control(
		    'post__not_in',
		    [
			    'label'       => __( 'Exclude', 'essential-addons-for-elementor-lite' ),
			    'type'        => 'eael-select2',
			    'label_block' => true,
			    'multiple'    => true,
			    'source_name' => 'post_type',
			    'source_type' => 'any',
			    'condition'   => [
				    'post_type!' => [ 'by_id', 'source_dynamic' ],
			    ],
		    ]
	    );

        $wb->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $wb->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $wb->add_control(
            'orderby',
            [
                'label' => __('Order By', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => ControlsHelper::get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $wb->add_control(
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

        $wb->end_controls_section();
    }

    /**
     * Query Controls
     *
     */
    public static function betterdocs_query($wb)
    {
        $wb->start_controls_section(
            'eael_section_post__filters',
            [
                'label' => __('Query', 'essential-addons-for-elementor-lite'),
            ]
        );

        $default_multiple_kb = ControlsHelper::get_betterdocs_multiple_kb_status();

        if ($default_multiple_kb) {
            $multiple_kb_terms = ControlsHelper::get_multiple_kb_terms(true, false);
            $default_slug = count($multiple_kb_terms) > 0 ? array_keys($multiple_kb_terms)[0] : '';

            $wb->add_control(
                'selected_knowledge_base',
                [
                    'label' => __('Knowledge Bases', 'essential-addons-for-elementor-lite'),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT2,
                    'options' => $multiple_kb_terms,
                    'multiple' => false,
                    'default' => '',
                    'select2options' => [
                        'placeholder' => __('All Knowledge Base', 'essential-addons-for-elementor-lite'),
                        'allowClear' => true,
                    ],
                ]
            );
        }

        if ($wb->get_name() === 'eael-betterdocs-category-grid') {
            $wb->add_control(
                'grid_query_heading',
                [
                    'label' => __('Category Grid', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                ]
            );
        }

        $wb->add_control(
            'include',
            [
                'label' => __('Include', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'options' => ControlsHelper::get_terms_list('doc_category', 'term_id'),
                'multiple' => true,
                'default' => [],
            ]
        );

        $wb->add_control(
            'exclude',
            [
                'label' => __('Exclude', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT2,
                'options' => ControlsHelper::get_terms_list('doc_category', 'term_id'),
                'label_block' => true,
                'post_type' => '',
                'multiple' => true,
            ]
        );

        if ($wb->get_name() === 'eael-betterdocs-category-grid') {
            $wb->add_control(
                'grid_per_page',
                [
                    'label' => __('Grid Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '8',
                ]
            );
        } else {
            $wb->add_control(
                'box_per_page',
                [
                    'label' => __('Box Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '8',
                ]
            );
        }

        $wb->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $wb->add_control(
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

        $wb->add_control(
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

        if ($wb->get_name() === 'eael-betterdocs-category-grid') {
            $wb->add_control(
                'grid_posts_query_heading',
                [
                    'label' => __('Grid List Posts', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $wb->add_control(
                'post_per_page',
                [
                    'label' => __('Post Per Page', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '6',
                ]
            );

            $wb->add_control(
                'post_orderby',
                [
                    'label' => __('Order By', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => ControlsHelper::get_post_orderby_options(),
                    'default' => 'date',
                ]
            );

            $wb->add_control(
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

            $wb->add_control(
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

        $wb->end_controls_section();
    }

    /**
     * Layout Controls For Post Block
     *
     */
    public static function layout($wb)
    {
        $wb->start_controls_section(
            'eael_section_post_timeline_layout',
            [
                'label' => __('Layout Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

	    if ('eael-post-block' === $wb->get_name()) {
		    $wb->add_control(
			    'eael_post_block_layout',
			    [
				    'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'post-block-layout-block',
				    'options' => [
					    'post-block-layout-block' => esc_html__('Block', 'essential-addons-for-elementor-lite'),
					    'post-block-layout-tiled' => esc_html__('Tiled', 'essential-addons-for-elementor-lite'),
				    ],
			    ]
		    );

		    $wb->add_control(
			    'eael_post_tiled_preset',
			    [
				    'label' => esc_html__('Preset', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'eael-post-tiled-preset-1',
				    'options' => [
					    'eael-post-tiled-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-preset-4' => esc_html__('Preset 4', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-preset-5' => esc_html__('Preset 5', 'essential-addons-for-elementor-lite'),
				    ],
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled'
				    ],
			    ]
		    );

		    $wb->add_control(
			    'eael_post_block_tiled_preset_1_note',
			    [
				    'label' => esc_html__('Note: Use 5 posts per page from Content » Query » Posts Per Page, to view this layout perfectly.', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::HEADING,
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled',
					    'eael_post_tiled_preset' => ['eael-post-tiled-preset-1', 'eael-post-tiled-preset-3'],
				    ],
			    ]
		    );

		    $wb->add_control(
			    'eael_post_block_tiled_preset_5_note',
			    [
				    'label' => esc_html__('Note: Use 3 posts per page from Content » Query » Posts Per Page, to view this layout perfectly.', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::HEADING,
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled',
					    'eael_post_tiled_preset' => ['eael-post-tiled-preset-5'],
				    ],
			    ]
		    );
		    $wb->add_control(
			    'eael_post_block_tiled_preset_2_note',
			    [
				    'label' => esc_html__('Note: Use 4 posts per page from Content » Query » Posts Per Page, to view this layout perfectly.', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::HEADING,
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled',
					    'eael_post_tiled_preset' => ['eael-post-tiled-preset-2'],
				    ],
			    ]
		    );
		    $wb->add_control(
			    'eael_post_block_tiled_preset_4_note',
			    [
				    'label' => esc_html__('Note: Use 2 posts per page from Content » Query » Posts Per Page, to view this layout perfectly.', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::HEADING,
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled',
					    'eael_post_tiled_preset' => ['eael-post-tiled-preset-4'],
				    ],
			    ]
		    );

		    $wb->add_control(
			    'eael_post_tiled_column',
			    [
				    'label' => esc_html__('Column', 'essential-addons-for-elementor-lite'),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'eael-post-tiled-col-4',
				    'options' => [
					    'eael-post-tiled-col-2' => esc_html__('Column 2', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-col-3' => esc_html__('Column 3', 'essential-addons-for-elementor-lite'),
					    'eael-post-tiled-col-4' => esc_html__('Column 4', 'essential-addons-for-elementor-lite'),
				    ],
				    'description' => esc_html__('Note: Column layout will be applied from second row.', 'essential-addons-for-elementor-lite'),
				    'condition' => [
					    'eael_post_block_layout' => 'post-block-layout-tiled',
				    ],
			    ]
		    );
	    }

        $wb->add_control(
            'eael_dynamic_template_Layout',
            [
                'label'   => esc_html__('Template Layout', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $wb->get_template_list_for_dropdown(),
            ]
        );

        if ('eael-post-grid' === $wb->get_name()) {
            $wb->add_responsive_control(
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

            $wb->add_control(
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

        if ('eael-post-block' === $wb->get_name()) {
            $wb->add_control(
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

	        $wb->add_control(
		        'eael_show_fallback_img',
		        [
			        'label' => __('Fallback Image', 'essential-addons-for-elementor-lite'),
			        'type' => Controls_Manager::SWITCHER,
			        'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
			        'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
			        'return_value' => 'yes',
			        'default' => 'yes',
		        ]
	        );
	        $wb->add_control(
		        'eael_post_block_fallback_img',
		        [
			        'label'             => __( 'Image', 'essential-addons-for-elementor-lite' ),
			        'type'              => Controls_Manager::MEDIA,
			        'condition'         => [
				        'eael_show_fallback_img'    => 'yes'
			        ]
		        ]
	        );
        }

        if ('eael-post-carousel' !== $wb->get_name()) {

            /**
             * Show Read More
             * @uses ContentTimeLine Elements - EAE
             */
            if ('eael-content-timeline' === $wb->get_name()) {

                $wb->add_control(
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
                    ]
                );

                $wb->add_control(
                    'date_position',
                    [
                        'label' => esc_html__('Date Position', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'inside',
                        'options' => [
                            'inside' => esc_html__('Inside', 'essential-addons-for-elementor-lite'),
                            'outside' => esc_html__('Outside', 'essential-addons-for-elementor-lite'),
                        ],
                        'condition' => [
                            'content_timeline_layout!' => 'center',
                        ],
                    ]
                );

            } else {
                $wb->add_control(
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

                $wb->add_control(
                    'show_load_more_text',
                    [
                        'label' => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic'     => [ 'active' => true ],
                        'label_block' => false,
                        'default' => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
                        'condition' => [
                            'show_load_more' => ['yes', '1', 'true'],
                        ],
                    ]
                );
            }

        }

        if ('eael-content-timeline' !== $wb->get_name()) {
            $wb->add_control(
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

            $wb->add_group_control(
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

            if( 'eael-post-block' === $wb->get_name() ) {
                $wb->add_control(
                    'post_block_image_height',
                    [
                        'label'      => __('Image Height', 'essential-addons-for-elementor-lite'),
                        'type'       => Controls_Manager::SLIDER,
                        'range'      => [
                            'px' => [
                                'min'  => 0,
                                'max'  => 600,
                                'step' => 1,
                            ],
                        ],
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                            '{{WRAPPER}} .eael-entry-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'eael_show_image' => 'yes',
                        ],
                    ]
                );
            }

            if( 'eael-post-grid' === $wb->get_name() ) {
                $wb->add_responsive_control(
                    'postgrid_image_height',
                    [
                        'label'      => __('Image Height', 'essential-addons-for-elementor-lite'),
                        'type'       => Controls_Manager::SLIDER,
                        'range'      => [
                            'px' => [
                                'min'  => 0,
                                'max'  => 600,
                                'step' => 1,
                            ],
                        ],
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                            '{{WRAPPER}} .eael-entry-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                        'condition' => [
                            'eael_show_image' => 'yes',
                        ],
                    ]
                );
            }

        }

        if ('eael-content-timeline' === $wb->get_name()) {

            $wb->add_control(
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

            $wb->add_control(
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
            $wb->add_control(
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

            $wb->add_control(
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

        $wb->add_control(
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

        $wb->add_control(
            'title_tag',
            [
                'label' => __('Title Tag', 'essential-addons-for-elementor-lite'),
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

        if ('eael-post-grid' === $wb->get_name() || 'eael-post-carousel' === $wb->get_name()) {
            $wb->add_control(
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

        $wb->add_control(
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

        if ('eael-content-timeline' === $wb->get_name()) {
            $wb->add_control(
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

            $wb->add_control(
                'excerpt_expanison_indicator',
                [
                    'label' => esc_html__('Expansion Indicator', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'label_block' => false,
                    'default' => esc_html__('...', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                        'eael_content_timeline_choose' => 'dynamic',
                    ],
                ]
            );
        } else {
            $wb->add_control(
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

            $wb->add_control(
                'excerpt_expanison_indicator',
                [
                    'label' => esc_html__('Expansion Indicator', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'label_block' => false,
                    'default' => esc_html__('...', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_excerpt' => 'yes',
                    ],
                ]
            );
        }

        $wb->add_control(
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

        $wb->add_control(
            'eael_read_more_text',
            [
                'label' => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
                'default' => esc_html__('Read More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_content_timeline_choose' => 'dynamic',
                    'eael_show_read_more' => 'yes',
                ],
            ]
        );

        if (
            'eael-post-grid' === $wb->get_name()
            || 'eael-post-block' === $wb->get_name()
            || 'eael-post-carousel' === $wb->get_name()
        ) {
            $wb->add_control(
                'eael_show_read_more_button',
                [
                    'label' => __('Show Read More Button', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $wb->add_control(
                'read_more_button_text',
                [
                    'label' => __('Button Text', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'default' => __('Read More', 'essential-addons-for-elementor-lite'),
                    'condition' => [
                        'eael_show_read_more_button' => 'yes',
                    ],
                ]
            );
        }

        if ('eael-post-carousel' === $wb->get_name() || 'eael-post-grid' === $wb->get_name()) {
            $wb->add_control(
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

            $wb->add_control(
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

            $wb->add_control(
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

        if ('eael-post-grid' === $wb->get_name() || 'eael-post-block' === $wb->get_name() || 'eael-post-carousel' === $wb->get_name()) {

            $wb->add_control(
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

            $wb->add_control(
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

            $wb->add_control(
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

            $wb->add_control(
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

            $wb->add_control(
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

        $wb->end_controls_section();
    }

    public static function terms_style($wb)
    {
        $wb->start_controls_section(
            'section_terms_style',
            [
                'label' => __('Terms', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );

        $wb->add_control(
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

        $wb->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'terms_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .post-carousel-categories li a',
            ]
        );

        $wb->add_responsive_control(
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

        $wb->add_control(
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

        $wb->end_controls_section();
    }

    public static function read_more_button_style($wb)
    {
        if (
            'eael-post-grid' === $wb->get_name()
            || 'eael-post-block' === $wb->get_name()
            || 'eael-post-carousel' === $wb->get_name()
            || 'eael-post-list' === $wb->get_name()
            || 'eael-post-timeline' === $wb->get_name()
        ) {
            $wb->start_controls_section(
                'eael_section_read_more_btn',
                [
                    'label' => __('Read More Button Style', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_show_read_more_button' => 'yes',
                    ],
                ]
            );

            $wb->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'eael_post_read_more_btn_typography',
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                ]
            );

            $wb->start_controls_tabs('read_more_button_tabs');

            $wb->start_controls_tab(
                'read_more_button_style_normal',
                [
                    'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                ]
            );

            $wb->add_control(
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

            $wb->add_group_control(
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

            $wb->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'read_more_btn_border',
                    'label' => __('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn',
                ]
            );

            $wb->add_responsive_control(
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

            $wb->end_controls_tab();

            $wb->start_controls_tab(
                'read_more_button_style_hover',
                [
                    'label' => __('Hover', 'essential-addons-for-elementor-lite'),
                ]
            );

            $wb->add_control(
                'eael_post_read_more_btn_hover_color',
                [
                    'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-post-elements-readmore-btn:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $wb->add_group_control(
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

            $wb->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'read_more_btn_hover_border',
                    'label' => __('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn:hover',
                ]
            );

            $wb->add_responsive_control(
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

            $wb->end_controls_tab();

            $wb->end_controls_tabs();

            $wb->add_responsive_control(
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

            $wb->add_responsive_control(
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

            $wb->end_controls_section();
        }
    }

    /**
     * Load More Button Style
     *
     */
    public static function load_more_button_style($wb)
    {
        $wb->start_controls_section(
            'eael_section_load_more_btn',
            [
                'label' => __('Load More Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_load_more' => ['yes', '1', 'true'],
                ],
            ]
        );

        $wb->add_responsive_control(
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

        $wb->add_responsive_control(
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
        $wb->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_typography',
                'selector' => '{{WRAPPER}} .eael-load-more-button',
            ]
        );

        $wb->start_controls_tabs('eael_post_grid_load_more_btn_tabs');

        // Normal State Tab
        $wb->start_controls_tab('eael_post_grid_load_more_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $wb->add_control(
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

        $wb->add_control(
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

        $wb->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_normal_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-load-more-button',
            ]
        );

        $wb->add_control(
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
        $wb->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_shadow',
                'selector' => '{{WRAPPER}} .eael-load-more-button',
                'separator' => 'before',
            ]
        );

        $wb->end_controls_tab();

        // Hover State Tab
        $wb->start_controls_tab('eael_post_grid_load_more_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $wb->add_control(
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

        $wb->add_control(
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

        $wb->add_control(
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
        $wb->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_load_more_btn_hover_shadow',
                'selector' => '{{WRAPPER}} .eael-load-more-button:hover',
                'separator' => 'before',
            ]
        );
        $wb->end_controls_tab();

        $wb->end_controls_tabs();

        $wb->add_responsive_control(
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

        $wb->end_controls_section();
    }

    public static function custom_positioning($wb, $prefix, $section_name, $css_selector, $condition = [])
    {
        $selectors = '{{WRAPPER}} ' . $css_selector;

        $wb->start_controls_section(
            $prefix . '_section_position',
            [
                'label' => $section_name,
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => $condition,
            ]
        );

        $wb->add_control(
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

        $wb->add_control(
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

        $wb->add_responsive_control(
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

        $wb->add_responsive_control(
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

        $wb->add_control(
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

        $wb->add_responsive_control(
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

        $wb->add_responsive_control(
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

        $wb->end_controls_section();
    }

    public function advanced_data_table_source($wb)
    {
        if (apply_filters('eael/is_plugin_active', 'ninja-tables/ninja-tables.php')) {
            $wb->add_control(
                'ea_adv_data_table_source_ninja_table_id',
                [
                    'label' => esc_html__('Table ID', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => ControlsHelper::get_ninja_tables_list(),
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

    /**
     * @since  3.8.2
     * @param $source
     *
     * @return array
     */
    public function event_calendar_source($source)
    {
        if (apply_filters('eael/pro_enabled', false)) {
            $source['eventon'] = __('EventON', 'essential-addons-for-elementor-lite');
        } else {
            $source['eventon'] = __('EventON (Pro) ', 'essential-addons-for-elementor-lite');
        }

        return $source;
    }

	public static function nothing_found_style($wb){
		$wb->start_controls_section(
			'eael_section_nothing_found_style',
			[
				'label' => __('Not Found Message', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$wb->add_control( 'eael_section_nothing_found_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Style the message when no posts are found.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$wb->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_nothing_found_typography',
				'selector' => '{{WRAPPER}} .eael-no-posts-found',
			]
		);
		$wb->add_control(
			'eael_post_nothing_found_color',
			[
				'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-no-posts-found' => 'color: {{VALUE}};',
				],
			]
		);
		$wb->add_control(
			'eael_post_nothing_found_bg_color',
			[
				'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-no-posts-found' => 'background-color: {{VALUE}};',
				],
			]
		);
		$wb->add_responsive_control(
			'eael_post_nothing_found_padding',
			[
				'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default'    => [
					'top'      => "25",
					'right'    => "25",
					'bottom'   => "25",
					'left'     => "25",
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-no-posts-found' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$wb->add_control(
			'eael_post_nothing_found_alignment',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .eael-no-posts-found' => 'text-align: {{VALUE}};',
				],
			]
		);

		$wb->end_controls_section();
	}
}

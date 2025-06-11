<?php

namespace Essential_Addons_Elementor\Traits;
use Essential_Addons_Elementor\Classes\Helper;

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

            $show_child_cat_control = ('category' === $taxonomy || 'product_cat' === $taxonomy) ? 1 : 0;
            $is_element_dynamic_gallery = 'eael-dynamic-filterable-gallery' === $wb->get_name() ? 1 : 0;
            
            if($show_child_cat_control && $is_element_dynamic_gallery){
                $wb->add_control(
                    $taxonomy . '_show_child_items',
                    [
                        'label' => __('Show Child Category Items', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                        'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                        'return_value' => 'yes',
                        'default' => 'no',
                        'condition' => [
                            $taxonomy . '_ids!' => '',
                            'post_type' => $object->object_type,
                        ],
                    ]
                );
            }

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

        if( 'eael-post-grid' === $wb->get_name() ){
            $wb->add_control(
                'ignore_sticky_posts',
                [
                    'label'        => __('Ignore Sticky Posts', 'essential-addons-for-elementor-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                    'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );
        }

        $wb->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'min' => '1',
            ]
        );

        if ( 'eael-dynamic-filterable-gallery' === $wb->get_name() ) {
            $wb->add_control(
                'eael_acf_important_note',
                [
                    'label' => '',
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => esc_html__( 'Given number of posts will be fetched along with their ACF gallery items', 'essential-addons-for-elementor-lite' ),
                    'content_classes' => 'elementor-descriptor',
                    'condition'       => [
                        'fetch_acf_image_gallery' => 'yes'
                    ]
                ]
            );
        }

        $wb->add_control(
            'offset',
            [
                'label' => __('Offset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
	            'condition' => [
	            	'orderby!' => 'rand'
	            ]
            ]
        );

        if( $wb->get_name() === 'eael-post-list' ) {
            $wb->add_control(
                'eael_fecth_all_posts',
                [
                    'label' => esc_html__( 'Fetch All Posts', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                    'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'yes',
                    'description' => esc_html__( 'By Enabling this option all posts will be fetch for "All" tab except exclude.', 'essential-addons-for-elementor-lite' ),
                ]
            );
        }

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
				'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'asc' => [
						'title' => esc_html__( 'Ascending', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fas fa-sort-amount-up-alt',
					],
					'desc' => [
						'title' => esc_html__( 'Descending', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fas fa-sort-amount-down',
					],
				],
				'default' => 'desc',
				'toggle'  => false,
			]
		);


        if ( 'eael-dynamic-filterable-gallery' === $wb->get_name() ) {
            $wb->add_control(
                'fetch_acf_image_gallery',
                [
                    'label'        => esc_html__( 'Fetch Images from ACF Gallery', 'essential-addons-for-elementor-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                    'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                    'separator'    => 'before',
                ]
            );

            if( class_exists( 'ACF' ) ){
                $fields = Helper::get_all_acf_fields();
                $key_list = [ '' => esc_html__( 'No Gallery Field Found', 'essential-addons-for-elementor-lite' ) ];
                if( ! empty( $fields ) ){
                    $key_list = [];
                    foreach( $fields as $field_name => $field ){
                        if( 'gallery' === $field['type'] ){
                            $key_list[ $field_name ] = $field['label'];
                        }
                    }
                }
                    
                $wb->add_control(
                    'eael_acf_gallery_keys',
                    [
                        'label'       => esc_html__( 'Select ACF Gallery Items Field', 'essential-addons-for-elementor-lite' ),
                        'type'        => Controls_Manager::SELECT2,
                        'label_block' => true,
                        'multiple'    => true,
                        'options'     => $key_list,
                        'condition'   => [
                            'fetch_acf_image_gallery' => 'yes'
                        ]
                    ]
                );

                $wb->add_control(
                    'eael_gf_hide_parent_items',
                    [
                        'label'        => esc_html__( 'Hide Featured Image', 'essential-addons-for-elementor-lite' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                        'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                        'condition'    => [
                            'fetch_acf_image_gallery' => 'yes'
                        ]
                    ]
                );
                
                $wb->add_control(
                    'eael_gf_afc_use_parent_data',
                    [
                        'label'        => esc_html__( 'Use Parent Data to Populate ACF Gallery Items', 'essential-addons-for-elementor-lite' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                        'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                        'return_value' => 'yes',
                        'default'      => 'no',
                        'condition'    => [
                            'fetch_acf_image_gallery' => 'yes'
                        ]
                    ]
                );

            } else {
                $wb->add_control(
                    'eael_scf_gallery_warnig_text',
                    [
                        'type'            => Controls_Manager::RAW_HTML,
                        'raw'             => __('<strong>Advanced Custom Fields (ACF)</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=advanced-custom-fields&tab=search&type=term" target="_blank">Advanced Custom Fields (ACF)</a> first.', 'essential-addons-for-elementor-lite'),
                        'content_classes' => 'eael-warning',
                        'condition'       => [
                            'fetch_acf_image_gallery' => 'yes'
                        ]
                    ]
                );
            }
        }

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
                    'betterdocs_order' => __('BetterDocs Order', 'essential-addons-for-elementor-lite'),
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

        if( 'eael-post-timeline' === $wb->get_name() ){
            $template_list = $wb->get_template_list_for_dropdown();
            $layout_options = [];
            if( ! empty( $template_list ) ){
                $image_dir_url = EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/';
                $image_dir_path = EAEL_PLUGIN_PATH . 'assets/admin/images/layout-previews/';
                foreach( $template_list as $key => $label ){
                    $image_url = $image_dir_url . 'post-timeline-' . $key . '.png';
                    $image_url =  file_exists( $image_dir_path . 'post-timeline-' . $key . '.png' ) ? $image_url : $image_dir_url . 'custom-layout.png';
                    $layout_options[ $key ] = [
                        'title' => $label,
                        'image' => $image_url
                    ];
                }
            }

            $wb->add_control(
                'eael_dynamic_template_Layout',
                [
                    'label'       => esc_html__( 'Template Layout', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::CHOOSE,
                    'options'     => $layout_options,
                    'default'     => 'default',
                    'label_block' => true,
                    'toggle'      => false,
                    'image_choose'=> true,
                ]
            );
        }else{
            $wb->add_control(
                'eael_dynamic_template_Layout',
                [
                    'label'   => esc_html__('Template Layout', 'essential-addons-for-elementor-lite'),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => $wb->get_template_list_for_dropdown(),
                ]
            );
        }

        if ('eael-post-carousel' === $wb->get_name()) {
            $wb->add_control(
                'eael_post_carousel_item_style',
                [
                    'label' => esc_html__('Item Style', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'eael-cards',
                    'options' => [
                        'eael-overlay' => esc_html__('Overlay', 'essential-addons-for-elementor-lite'),
                        'eael-cards' => esc_html__('Cards', 'essential-addons-for-elementor-lite'),
                    ],
                ]
            );

            $wb->add_control(
                'eael_post_carousel_title',
                [
                    'label'       => esc_html__( 'Carousel Title', 'essential-addons-for-elementor-lite' ),
                    'label_block' => true,
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Essential Addons Post Carousel', 'essential-addons-for-elementor-lite' ),
                ]
            );

            $wb->add_control(
                'eael_post_carousel_title_tag',
                [
                    'label'       => __('Carousel Title Tag', 'essential-addons-for-elementor-lite'),
                    'label_block' => true,
                    'type'        => Controls_Manager::CHOOSE,
                    'options'     => [
                        'h1' => [
                            'title' => esc_html__( 'H1', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h1',
                        ],
                        'h2' => [
                            'title' => esc_html__( 'H2', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h2',
                        ],
                        'h3' => [
                            'title' => esc_html__( 'H3', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h3',
                        ],
                        'h4' => [
                            'title' => esc_html__( 'H4', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h4',
                        ],
                        'h5' => [
                            'title' => esc_html__( 'H5', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h5',
                        ],
                        'h6' => [
                            'title' => esc_html__( 'H6', 'essential-addons-for-elementor-lite' ),
                            'icon'  => 'eicon-editor-h6',
                        ],
                        'div' => [
                            'title' => esc_html__( 'Div', 'essential-addons-for-elementor-lite' ),
                            'text'  => 'div',
                        ],
                        'span' => [
                            'title' => esc_html__( 'Span', 'essential-addons-for-elementor-lite' ),
                            'text'  => 'span',
                        ],
                        'p' => [
                            'title' => esc_html__( 'P', 'essential-addons-for-elementor-lite' ),
                            'text'  => 'P',
                        ],
                    ],
                    'default'   => 'h2',
                    'toggle'    => false,
                    'condition' => [
                        'eael_post_carousel_title!' => '',
                    ],
                ]
            );

        }

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
                    ],
                    'ai' => [
                        'active' => false,
                    ],
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
                        'label'   => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                            'left' => [
                                'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-h-align-right',
                            ],
                            'center' => [
                                'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-h-align-center',
                            ],
                            'right' => [
                                'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-h-align-left',
                            ],
                        ],
                        'default'   => 'center',
                        'toggle'    => false,
                        'condition' => [
                            'eael_dynamic_template_Layout' => 'default',
                        ],
                    ]
                );

                $wb->add_control(
                    'content_timeline_layout_horizontal',
                    [
                        'label'   => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                            'top' => [
                                'title' => esc_html__( 'Top', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-v-align-bottom',
                            ],
                            'middle' => [
                                'title' => esc_html__( 'Middle', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-v-align-middle',
                            ],
                            'bottom' => [
                                'title' => esc_html__( 'Bottom', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-v-align-top',
                            ],
                        ],
                        'default'   => 'middle',
                        'toggle'    => false,
                        'condition' => [
                            'eael_dynamic_template_Layout' => 'horizontal',
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
                            'eael_dynamic_template_Layout' => 'default',
                            'content_timeline_layout!' => 'center',
                        ],
                    ]
                );
                
                $wb->add_control(
                    'date_position_horizontal',
                    [
                        'label' => esc_html__('Date Position', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'outside',
                        'options' => [
                            'inside' => esc_html__('Inside', 'essential-addons-for-elementor-lite'),
                            'outside' => esc_html__('Outside', 'essential-addons-for-elementor-lite'),
                        ],
                        'condition' => [
                            'eael_dynamic_template_Layout' => 'horizontal',
                            'content_timeline_layout_horizontal!' => 'middle',
                        ],
                    ]
                );

            } else {
                $wb->add_control(
                    'show_load_more',
                    [
                        'label'   => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                            'no' => [
                                'title' => esc_html__( 'Disable', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-ban',
                            ],
                            'yes' => [
                                'title' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-button',
                            ],
                            'infinity' => [
                                'title' => esc_html__( 'Infinity Scroll', 'essential-addons-for-elementor-lite' ),
                                'icon'  => 'eicon-image-box',
                            ],
                        ],
                        'default'   => 'no',
                        'separator' => 'before',
                        'toggle'    => false,
                    ]
                );

                $wb->add_control(
                    'load_more_infinityscroll_offset',
                    [
                        'label'       => esc_html__('Scroll Offset (px)', 'essential-addons-for-elementor-lite'),
                        'type'        => Controls_Manager::NUMBER,
                        'dynamic'     => [ 'active' => false ],
                        'label_block' => false,
                        'separator'   => 'after',
                        'default'     => '-200',
                        'description' => esc_html__('Set the position of loading to the viewport before it ends from view', 'essential-addons-for-elementor-lite'),
                        'condition'   => [
                            'show_load_more' => 'infinity',
                        ],
                    ]
                );

                $wb->add_control(
                    'show_load_more_text',
                    [
                        'label'       => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                        'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [ 'active' => true ],
                        'label_block' => false,
                        'separator'   => 'after',
                        'default'     => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
                        'condition'   => [
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
                            'icon' => 'eicon-image-bold',
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
                        'eael_content_timeline_choose!' => 'custom',
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
                    'ai' => [
                        'active' => false,
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
                        '{{WRAPPER}} .eael-horizontal-timeline-item__point-content .eael-elements-icon img' => 'width: {{SIZE}}px;',
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
                        'eael_content_timeline_choose!' => 'custom',
                        'eael_show_image_or_icon' => 'icon',
                    ],
                ]
            );

        }

        $wb->add_control(
            'eael_show_fallback_img_all',
            [
                'label' => __('Fallback Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $wb->add_control(
            'eael_post_carousel_fallback_img_all',
            [
                'label'             => __( 'Image', 'essential-addons-for-elementor-lite' ),
                'type'              => Controls_Manager::MEDIA,
                'condition'         => [
                    'eael_show_fallback_img_all'    => 'yes',
                    'eael_show_image' => 'yes',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

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
                'label'       => __('Title Tag', 'essential-addons-for-elementor-lite'),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'h1' => [
						'title' => esc_html__( 'H1', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h1',
					],
					'h2' => [
						'title' => esc_html__( 'H2', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h2',
					],
					'h3' => [
						'title' => esc_html__( 'H3', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h3',
					],
					'h4' => [
						'title' => esc_html__( 'H4', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h4',
					],
					'h5' => [
						'title' => esc_html__( 'H5', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h5',
					],
					'h6' => [
						'title' => esc_html__( 'H6', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-editor-h6',
					],
					'div' => [
						'title' => esc_html__( 'Div', 'essential-addons-for-elementor-lite' ),
						'text'  => 'div',
					],
					'span' => [
						'title' => esc_html__( 'Span', 'essential-addons-for-elementor-lite' ),
						'text'  => 'span',
					],
					'p' => [
						'title' => esc_html__( 'P', 'essential-addons-for-elementor-lite' ),
						'text'  => 'P',
					],
				],
                'default'   => 'h2',
				'toggle'    => false,
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
			]
		);

	    if ( 'eael-content-timeline' === $wb->get_name() ) {
		    $wb->add_control(
			    'eael_show_image',
			    [
				    'label'        => __( 'Show Image', 'essential-addons-for-elementor-lite' ),
				    'type'         => Controls_Manager::SWITCHER,
				    'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				    'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				    'return_value' => 'yes',
				    'default'      => '',
				    'condition'    => [
					    'eael_content_timeline_choose!' => 'custom',
				    ],
			    ]
		    );

		    $wb->add_group_control(
			    Group_Control_Image_Size::get_type(),
			    [
				    'name'      => 'image',
				    'exclude'   => [ 'custom' ],
				    'default'   => 'medium',
				    'condition' => [
					    'eael_show_image'              => 'yes',
					    'eael_content_timeline_choose!' => 'custom',
				    ],
			    ]
		    );

		    $wb->add_control(
			    'eael_image_linkable',
			    [
				    'label'        => __( 'Image Clickable', 'essential-addons-for-elementor-lite' ),
				    'type'         => Controls_Manager::SWITCHER,
				    'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				    'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				    'return_value' => 'yes',
				    'default'      => '',
				    'condition'    => [
					    'eael_show_image'              => 'yes',
					    'eael_content_timeline_choose!' => 'custom',
				    ],
			    ]
		    );
	    }

        $wb->add_control(
            'eael_show_excerpt',
            [
                'label' => __('Show Excerpt', 'essential-addons-for-elementor-lite'),
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
                    'ai' => [ 'active' => false ],
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
                    'label'       => esc_html__('Expansion Indicator', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active'      =>true ],
                    'ai'          => [ 'active'      =>false ],
                    'label_block' => false,
                    'default'     => esc_html__('...', 'essential-addons-for-elementor-lite'),
                    'condition'   => [
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
                    'eael_content_timeline_choose!' => 'custom',
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
                    'eael_content_timeline_choose!' => 'custom',
                    'eael_show_read_more' => 'yes',
                ],
            ]
        );

        if (
            'eael-content-timeline' === $wb->get_name()
        ) {
            $wb->add_control(
                'eael_content_timeline_navigation_type',
                array(
                    'label'   => esc_html__( 'Navigation Type', 'essential-addons-for-elementor-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'scrollbar',
                    'options' => array(
                        'scrollbar' => esc_html__( 'Scrollbar', 'essential-addons-for-elementor-lite' ),
                        'arrows' => esc_html__( 'Arrows', 'essential-addons-for-elementor-lite' ),
                    ),
                    'condition' => [
                        'eael_dynamic_template_Layout' => 'horizontal',
                    ],
                )
            );

            $wb->add_control(
                'eael_content_timeline_arrow_type',
                array(
                    'label'   => esc_html__( 'Arrow Type', 'essential-addons-for-elementor-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'fa fa-angle-left',
                    'options' => array(
                                    'fa fa-angle-left'          => __( 'Angle', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-chevron-left'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-angle-double-left'   => __( 'Angle Double', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-arrow-left'          => __( 'Arrow', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-caret-left'          => __( 'Caret', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-long-arrow-alt-left' => __( 'Long Arrow', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'essential-addons-for-elementor-lite' ),
                                    'fa fa-caret-square-left'   => __( 'Caret Square', 'essential-addons-for-elementor-lite' ),
                                ),
                    'condition' => [
                        'eael_dynamic_template_Layout' => 'horizontal',
                        'eael_content_timeline_navigation_type' => 'arrows',
                    ],
                )
            );

            $content_timeline_range = range( 1, 3 );
            $wb->add_responsive_control(
                'eael_content_timeline_slides_to_scroll',
                array(
                    'label'     => esc_html__( 'Slides to Scroll', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '1',
                    'options'   => array_combine( $content_timeline_range, $content_timeline_range ),
                    'condition' => [
                        'eael_dynamic_template_Layout' => 'horizontal',
                        'eael_content_timeline_navigation_type' => 'arrows',
                    ],
                )
            );
        }

        if ('eael-post-block' === $wb->get_name() || 'eael-post-carousel' === $wb->get_name()) {

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

            if ( 'eael-post-grid' !== $wb->get_name() ){
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
            }

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
                'label' => __('Terms', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );

        $wb->add_control(
            'terms_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .post-carousel-categories li a',
            ]
        );

        $wb->add_responsive_control(
            'terms_color_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
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
                'label' => __('Spacing', 'essential-addons-for-elementor-lite'),
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
                    'label' => __('Read More', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_show_read_more_button' => 'yes',
                    ],
                ]
            );

	        if ('eael-post-grid' === $wb->get_name()) {
	            $wb->add_responsive_control(
		        'eael_post_grid_read_more_alignment',
		        [
			        'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
			        'type' => Controls_Manager::CHOOSE,
			        'options' => [
				        'left' => [
					        'title' => __('Left', 'essential-addons-for-elementor-lite'),
					        'icon' => 'eicon-text-align-left',
				        ],
				        'center' => [
					        'title' => __('Center', 'essential-addons-for-elementor-lite'),
					        'icon' => 'eicon-text-align-center',
				        ],
				        'right' => [
					        'title' => __('Right', 'essential-addons-for-elementor-lite'),
					        'icon' => 'eicon-text-align-right',
				        ],
			        ],
			        'selectors' => [
				        '{{WRAPPER}} .eael-post-elements-readmore-btn' => 'text-align: {{VALUE}};',
			        ],
		        ]
	        );
	        }

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
                    'default' => '#000BEC',
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

            if ( 'eael-post-carousel' === $wb->get_name() ) {
                $wb->add_control(
                    'eael_post_read_more_btn_hover_color',
                    [
                        'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .eael-post-elements-readmore-btn:hover' => 'color: {{VALUE}};',
                        ],
                        'condition' => [
                            'eael_post_carousel_item_style!' => 'eael-overlay',
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
                        'condition' => [
                            'eael_post_carousel_item_style!' => 'eael-overlay',
                        ],
                    ]
                );

                $wb->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'read_more_btn_hover_border',
                        'label' => __('Border', 'essential-addons-for-elementor-lite'),
                        'selector' => '{{WRAPPER}} .eael-post-elements-readmore-btn:hover',
                        'condition' => [
                            'eael_post_carousel_item_style!' => 'eael-overlay',
                        ],
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
                        'condition' => [
                            'eael_post_carousel_item_style!' => 'eael-overlay',
                        ],
                    ]
                );
            } else {
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
            }

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
                'label' => __('Load More', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_load_more' => ['yes', '1', 'true', 'infinity'],
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
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
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
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
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

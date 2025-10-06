<?php

namespace Essential_Addons_Elementor\Elements;
use Elementor\Group_Control_Image_Size;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Traits\Helper;

class Post_Grid extends Widget_Base
{
    use Helper;

	protected $page_id;

    public function get_name()
    {
        return 'eael-post-grid';
    }

    public function get_title()
    {
        return __('Post Grid', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-post-grid';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_keywords()
    {
        return [
            'post',
            'posts',
            'grid',
            'ea post grid',
            'ea posts grid',
            'blog post',
            'article',
            'custom posts',
            'masonry',
            'content views',
            'blog view',
            'content marketing',
            'blogger',
            'ea',
            'essential addons',
        ];
    }

    public function has_widget_inner_wrapper(): bool {
        return ! HelperClass::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/post-grid/';
    }

    protected function register_controls()
    {
        /**
         * Grid Layout Controls!
         */
        $this->start_controls_section(
            'eael_section_post_grid_layout',
            [
                'label' => __('Layout', 'essential-addons-for-elementor-lite'),
            ]
        );

        // $this->add_control(
        //     'eael_dynamic_template_Layout',
        //     [
        //         'label'   => esc_html__('Template Layout', 'essential-addons-for-elementor-lite'),
        //         'type'    => Controls_Manager::SELECT,
        //         'default' => 'default',
        //         'options' => $this->get_template_list_for_dropdown(),
        //     ]
        // );

        $template_list = $this->get_template_list_for_dropdown();
        $layout_options = [];
        if( ! empty( $template_list ) ){
            $image_dir_url = EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/';
            $image_dir_path = EAEL_PLUGIN_PATH . 'assets/admin/images/layout-previews/';
            foreach( $template_list as $key => $label ){
                $image_url = $image_dir_url . 'post-grid-' . $key . '.png';
                $image_url =  file_exists( $image_dir_path . 'post-grid-' . $key . '.png' ) ? $image_url : $image_dir_url . 'custom-layout.png';
                $layout_options[ $key ] = [
                    'title' => $label,
                    'image' => $image_url
                ];
            }
        }

        $this->add_control(
            'eael_post_grid_preset_style',
            [
                'label'       => esc_html__( 'Skin', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => $layout_options,
                'default'     => 'one',
                'label_block' => true,
                'toggle'      => false,
                'image_choose'=> true,
            ]
        );

        $this->add_control(
            'layout_mode',
            [
                'label'   => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'masonry',
                'options' => [
                    'grid'       => [
                        'title'  => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                        'icon'   => 'eicon-gallery-grid'
                    ],
                    'masonry' => [
                        'title'  => esc_html__('Masonry', 'essential-addons-for-elementor-lite'),
                        'icon'   => 'eicon-gallery-masonry'
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_columns',
            [
                'label'       => esc_html__('Column', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle'      => false,
                'options'     => [
                    'eael-col-1' => [
                        'title'  => esc_html__('One', 'essential-addons-for-elementor-lite'),
                        'text'   => '1'
                    ],
                    'eael-col-2' => [
                        'title'  => esc_html__('Two', 'essential-addons-for-elementor-lite'),
                        'text'   => '2'
                    ],
                    'eael-col-3' => [
                        'title'  => esc_html__('Three', 'essential-addons-for-elementor-lite'),
                        'text'   => '3'
                    ],
                    'eael-col-4' => [
                        'title'  => esc_html__('Four', 'essential-addons-for-elementor-lite'),
                        'text'   => '4'
                    ],
                    'eael-col-5' => [
                        'title'  => esc_html__('Five', 'essential-addons-for-elementor-lite'),
                        'text'   => '5'
                    ],
                    'eael-col-6' => [
                        'title'  => esc_html__('Six', 'essential-addons-for-elementor-lite'),
                        'text'   => '6'
                    ],
                ],
                'default'            => 'eael-col-4',
                'tablet_default'     => 'eael-col-2',
                'mobile_default'     => 'eael-col-1',
                'prefix_class'       => 'elementor-grid%s-',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
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

        $this->add_control(
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

        $this->add_control(
            'show_load_more_text',
            [
                'label'       => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
                'label_block' => false,
                'separator'   => 'after',
                'default'     => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'show_load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'eael_title_heading',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'eael_show_title',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'       => __('HTML Tag', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            'eael_title_length',
            [
                'label'     => __('Length', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::NUMBER,
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'eael_excerpt_heading',
			[
				'label'     => esc_html__( 'Excerpt', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'eael_show_excerpt',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_excerpt_length',
            [
                'label'     => __('Length', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 10,
                'condition' => [
                    'eael_show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
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

        $this->add_control(
			'eael_read_more_text_heading',
			[
				'label'     => esc_html__( 'Read More Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'eael_show_read_more_button',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'read_more_button_text',
            [
                'label'       => esc_html__('Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'ai'          => [ 'active' => false ],
                'label_block' => false,
                'default'     => esc_html__('Read More', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'eael_post_terms_heading',
			[
				'label'     => esc_html__( 'Post Terms', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition'   => [
                    'eael_post_grid_preset_style' =>  ['two', 'three'],
                ],
			]
		);

        $this->add_control(
            'eael_show_post_terms',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'condition'    => [
                    'eael_show_image'             => 'yes',
                    'eael_post_grid_preset_style' => ['two', 'three']
                ]
            ]
        );

        $post_types = HelperClass::get_post_types();
        unset(
            $post_types['post'],
            $post_types['page'],
            $post_types['product']
        );
        $taxonomies     = get_taxonomies( [], 'objects' );
        $post_types_tax = [];

        foreach ( $taxonomies as $taxonomy => $object ) {
            if( isset( $object->object_type ) && is_array( $object->object_type ) && count( $object->object_type ) ){
                foreach( $object->object_type as $object_type ){
                    if ( ! in_array( $object_type, array_keys( $post_types ) ) ) { 
                        continue;
                    }
                    $post_types_tax[ $object_type ][ $taxonomy ] = $object->label;
                }
            }
        }

        foreach ( $post_types as $post_type => $post_taxonomies ) {
            $this->add_control(
                'eael_' . $post_type . '_terms',
                [
                    'label'     => __( 'Terms From', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => isset( $post_types_tax[ $post_type ] ) ? $post_types_tax[ $post_type ] : [],
                    'default'   => isset( $post_types_tax[ $post_type ] ) ? key( $post_types_tax[ $post_type ] ) : '',
                    'condition' => [
                        'eael_show_image'             => 'yes',
                        'eael_show_post_terms'        => 'yes',
                        'post_type'                   => $post_type,
                        'eael_post_grid_preset_style' =>  ['two', 'three'],
                    ],
                ]
            );
        }

        $this->add_control(
            'eael_post_terms',
            [
                'label'   => __('Terms From', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'category' => __('Category', 'essential-addons-for-elementor-lite'),
                    'tags'     => __('Tags', 'essential-addons-for-elementor-lite'),
                ],
                'default'   => 'category',
                'condition' => [
                    'eael_show_image'      => 'yes',
                    'eael_show_post_terms' => 'yes',
                    'post_type'            => [ 'post', 'page', 'product', 'by_id', 'source_dynamic' ],
                    'eael_post_grid_preset_style' =>  ['two', 'three'],
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
                    'eael_show_image'             => 'yes', 
                    'eael_show_post_terms'        => 'yes',
                    'eael_post_grid_preset_style' => ['two', 'three'],
                ]
            ]
        );

        $this->add_control(
            'eael_post_terms_separator',
            [
                'label'       => esc_html__( 'Terms Separator', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'ai'          => [ 'active' => false ],
                'default'     => esc_html__( '', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_show_post_terms'        => 'yes',
                    'eael_post_grid_preset_style' =>  ['two', 'three'],
                ],
            ]
        );
        
        $this->add_control(
            'eael_post_terms_on_image_hover',
            [
                'label'        => esc_html__('Terms on Image Hover', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_show_post_terms' => 'yes',
                    'eael_post_grid_preset_style' =>  ['two', 'three'],
                ],
            ]
        );

        $this->add_control(
			'eael_meta_data_heading',
			[
				'label'     => esc_html__( 'Meta Data', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'eael_show_meta',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'meta_position',
            [
                'label'   => esc_html__('Meta Position', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
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
                'label'        => __('Avatar', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                    'eael_post_grid_preset_style!' => ['two', 'three'],
                ],
            ]
        );

        $this->add_control(
            'eael_show_author',
            [
                'label'        => __('Author Name', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                    'eael_post_grid_preset_style' => '',
                ],
            ]
        );

        // Style two and three has different default values and keeping controls consistant (Avatar and Author).
        $this->add_control(
            'eael_show_avatar_two',
            [
                'label'        => __('Avatar', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                    'eael_post_grid_preset_style' => 'two',
                ],
            ]
        );
        
        $this->add_control(
            'eael_show_author_two',
            [
                'label'        => __('Author Name', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                    'eael_post_grid_preset_style' => 'two',
                ],
            ]
        );

        $this->add_control(
            'eael_show_author_three',
            [
                'label'        => __('Author Name', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                    'meta_position'  => 'meta-entry-footer',
                    'eael_post_grid_preset_style' => 'three',
                ],
            ]
        );

        $this->add_control(
            'eael_show_date',
            [
                'label'        => __('Date', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_show_meta' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Query And Layout Controls!
         * @source includes/elementor-helper.php
         */
        do_action('eael/controls/query', $this);
        // do_action('eael/controls/layout', $this);

        /**
         * Grid Image Controls!
         */
        $this->start_controls_section(
            'eael_section_post_image_section',
            [
                'label' => __('Image', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_show_image',
            [
                'label'        => __('Show', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image',
                'exclude'   => ['custom'],
                'default'   => 'medium',
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_postgrid_image_ratio',
            [
                'label'        => __('Manage Ratio', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'postgrid_image_ratio',
            [
                'label' => __('Ratio', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 2,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.66,
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-entry-thumbnail' => 'padding-bottom: calc({{SIZE}} * 100%);',
                ],
                'condition' => [
                    'enable_postgrid_image_ratio' => 'yes',
                    'eael_show_image'             => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'postgrid_image_height',
            [
                'label'      => __('Height', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            'eael_show_fallback_img_all',
            [
                'label'        => __('Fallback', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_post_carousel_fallback_img_all',
            [
                'label'     => '',
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'eael_show_fallback_img_all' => 'yes',
                    'eael_show_image'            => 'yes',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Grid Style Controls!
         */
        $this->start_controls_section(
            'section_post_grid_links',
            [
                'label' => __('Link Options', 'essential-addons-for-elementor-lite'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                       [
                          'name' => 'eael_show_image',
                          'operator' => '==',
                          'value' => 'yes',
                       ],
                       [
                          'name' => 'eael_show_title',
                          'operator' => '==',
                          'value' => 'yes',
                       ],
                       [
                          'name' => 'eael_show_read_more_button',
                          'operator' => '==',
                          'value' => 'yes',
                       ],
                       
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_link',
            [
                'label' => __('Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'image_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'image_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_image' => 'yes',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'title_link',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'read_more_link',
            [
                'label' => __('Read More', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_read_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Grid Style Controls!
         */
        $this->start_controls_section(
            'eael_section_post_grid_style',
            [
                'label' => __('Item', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_style_three_alert',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('Make sure to enable <strong>Show Date</strong> option from <strong>Layout Settings</strong>', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'eael_post_grid_preset_style' => ['two', 'three'],
                    'eael_show_date' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-holder' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_spacing',
            [
                'label' => esc_html__('Space Between Items', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_post_grid_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-grid-post-holder',
            ]
        );

        $this->add_control(
            'eael_post_grid_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-holder' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_box_shadow',
                'selector' => '{{WRAPPER}} .eael-grid-post-holder',
            ]
        );

        $this->end_controls_section();

        /**
         * Thumbnail style
         */

        $this->start_controls_section(
            'eael_section_post_grid_thumbnail_style',
            [
                'label' => __('Image', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_thumbnail_radius',
            [
                'label' => esc_html__('Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-post-grid .eael-grid-post .eael-entry-media img, {{WRAPPER}} .eael-grid-post .eael-entry-overlay' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style tab: Meta Date style
         */
        $this->start_controls_section(
            'section_meta_date_style',
            [
                'label' => __('Meta Date', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_meta' => 'yes',
                    'eael_post_grid_preset_style' => ['three'],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_post_grid_meta_date_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-meta-posted-on',
            ]
        );
        $this->add_control(
            'eael_post_grid_meta_date_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-meta-posted-on' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_meta_date_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'selector' => '{{WRAPPER}} .eael-meta-posted-on',
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_date_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-meta-posted-on' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_post_grid_meta_date_shadow',
                'label' => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-meta-posted-on',
                'condition' => [
                    'eael_post_grid_preset_style' => ['three'],
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab: Meta Date Position
         */
        do_action('eael/controls/custom_positioning',
            $this,
            'eael_meta_date_position_',
            __('Meta Date Position', 'essential-addons-for-elementor-lite'),
            '.eael-meta-posted-on',
            [
                'eael_show_meta' => 'yes',
                'eael_post_grid_preset_style' => ['three'],
            ]
        );

        /**
         * Style tab: Meta Style
         */
        $this->start_controls_section(
            'section_meta_style_style',
            [
                'label' => __('Meta Data', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_post_grid_preset_style!' => 'three',
                    'eael_show_meta' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_post_grid_meta_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-meta a' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_show_author_name' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_color_date',
            [
                'label' => __('Date Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-meta .eael-posted-on' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-post-grid-style-two .eael-entry-meta .eael-meta-posted-on' => 'color: {{VALUE}};',
                ],
                'condition' => [
	                'eael_show_date' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_meta_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
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
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-footer' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .eael-grid-post .eael-entry-header-after' => 'justify-content: {{VALUE}}; align-items: center;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_meta_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'selector' => '{{WRAPPER}} .eael-entry-meta > span',
                'condition' => [
                    'meta_position' => 'meta-entry-footer',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_meta_header_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'selector' => '{{WRAPPER}} .eael-entry-meta > span',
                'condition' => [
                    'meta_position' => 'meta-entry-header',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-header-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'meta_position' => 'meta-entry-header',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_meta_footer_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-entry-header-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'meta_position' => 'meta-entry-footer',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab: Meta Position
         */
        do_action('eael/controls/custom_positioning',
            $this,
            'eael_meta_footer_',
            __('Meta Position', 'essential-addons-for-elementor-lite'),
            '.eael-grid-post .eael-entry-footer',
            [
                'eael_show_meta' => 'yes',
                'meta_position' => ['meta-entry-footer'],
                'eael_post_grid_preset_style!' => 'three',
            ]
        );

        do_action('eael/controls/custom_positioning',
            $this,
            'eael_meta_header_',
            __('Meta Position', 'essential-addons-for-elementor-lite'),
            '.eael-grid-post .eael-entry-header-after',
            [
                'eael_show_meta' => 'yes',
                'meta_position' => ['meta-entry-header'],
            ]
        );

        /**
         * Color, Typography & Spacing
         */
        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __('Content', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_title_style',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_post_grid_title_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#303133',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title a' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'eael_post_grid_title_hover_color',
            [
                'label' => __('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#23527c',
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title:hover, {{WRAPPER}} .eael-entry-title a:hover' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_title_alignment',
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
                    '{{WRAPPER}} .eael-entry-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_title_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
                'selector' => '{{WRAPPER}} .eael-entry-title, {{WRAPPER}} .eael-entry-title a',
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_title_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_excerpt_style',
            [
                'label' => __('Excerpt Style', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_post_grid_excerpt_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_excerpt_alignment',
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
                    'justify' => [
                        'title' => __('Justified', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_excerpt_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'selector' => '{{WRAPPER}} .eael-grid-post-excerpt p',
            ]
        );

        $this->add_control(
            'content_height',
            [
                'label' => esc_html__('Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['max' => 300],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-holder .eael-entry-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_excerpt_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post-excerpt p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style tab: terms style
         */
        $this->start_controls_section(
            'section_meta_terms_style',
            [
                'label' => __('Terms', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_post_grid_preset_style' => 'two',
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_post_grid_terms_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .post-meta-categories li, {{WRAPPER}} .post-meta-categories li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_terms_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
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
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .post-meta-categories' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_post_grid_terms_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'selector' => '{{WRAPPER}} .post-meta-categories li, {{WRAPPER}} .post-meta-categories li a',
            ]
        );

        $this->add_control(
            'eael_post_carousel_terms_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .post-meta-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        // terms style
        $this->start_controls_section(
            'section_terms_style',
            [
                'label' => __('Terms', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                    'eael_post_grid_preset_style' => '',
                ],
            ]
        );

        $this->add_control(
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'terms_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .post-carousel-categories li a',
            ]
        );

        $this->add_responsive_control(
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

        $this->add_control(
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

        $this->end_controls_section();

        // Card Hover
        $this->start_controls_section(
            'eael_section_hover_card_styles',
            [
                'label' => __('Hover Card', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_animation',
            [
                'label' => esc_html__('Animation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade-in',
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'fade-in' => esc_html__('FadeIn', 'essential-addons-for-elementor-lite'),
                    'zoom-in' => esc_html__('ZoomIn', 'essential-addons-for-elementor-lite'),
                    'slide-up' => esc_html__('SlideUp', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_bg_hover_icon_new',
            [
                'label' => __('Hover Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_post_grid_hover_animation!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0, .75)',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay' => 'background-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'eael_post_grid_hover_bg_radius',
            [
                'label' => esc_html__('Cards Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-post-grid .eael-grid-post .eael-entry-media .eael-entry-overlay' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_post_grid_hover_icon_color',
            [
                'label' => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_post_grid_hover_icon_fontsize',
            [
                'label' => __('Icon font size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-grid-post .eael-entry-overlay > img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Read More Button Style Controls
         */
        do_action('eael/controls/read_more_button_style', $this);

        /**
         * Load More Button Style Controls!
         */
        do_action('eael/controls/load_more_button_style', $this);
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $settings = HelperClass::fix_old_query($settings);
        $args = HelperClass::get_query_args($settings);
        $args = HelperClass::get_dynamic_args($settings, $args);

	    if ( ! in_array( $settings['post_type'], [ 'post', 'page', 'product', 'by_id', 'source_dynamic' ] ) ) {
		    $settings['eael_post_terms'] = empty( $settings["eael_{$settings['post_type']}_terms"] ) ? '' : $settings["eael_{$settings['post_type']}_terms"];
	    } elseif ( $settings['post_type'] === 'product' ) {
		    $settings['eael_post_terms'] = $settings['eael_post_terms'] === 'category' ? 'product_cat' : ( $settings['eael_post_terms'] === 'tags' ? 'product_tag' : $settings['eael_post_terms'] );
	    }

        $link_settings = [
            'image_link_nofollow' => $settings['image_link_nofollow'] ? 'rel="nofollow"' : '',
            'image_link_target_blank' => $settings['image_link_target_blank'] ? 'target="_blank"' : '',
            'title_link_nofollow' => $settings['title_link_nofollow'] ? 'rel="nofollow"' : '',
            'title_link_target_blank' => $settings['title_link_target_blank'] ? 'target="_blank"' : '',
            'read_more_link_nofollow' => $settings['read_more_link_nofollow'] ? 'rel="nofollow"' : '',
            'read_more_link_target_blank' => $settings['read_more_link_target_blank'] ? 'target="_blank"' : '',
        ];

        $this->add_render_attribute(
            'post_grid_wrapper',
            [
                'id' => 'eael-post-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-post-grid-container',
                ],
            ]
        );

        $this->add_render_attribute(
            'post_grid_container',
            [
                'class' => [
                    'eael-post-grid',
                    'eael-post-appender',
                    'eael-post-appender-' . $this->get_id(),
                    'eael-post-grid-style-' . ($settings['eael_post_grid_preset_style'] !== "" ? $settings['eael_post_grid_preset_style'] : 'default'),
                ],
            ]
        );

        echo '<div '; $this->print_render_attribute_string( 'post_grid_wrapper' ); echo '>
            <div '; $this->print_render_attribute_string( 'post_grid_container' ); echo ' data-layout-mode="' . esc_attr( $settings["layout_mode"] ) . '">';

        $template = $this->get_template($settings['eael_post_grid_preset_style'] );
        $settings['loadable_file_name'] = $this->get_filename_only($template);
	    $dir_name = $this->get_temp_dir_name($settings['loadable_file_name']);
	    $found_posts = 0;
        $offset = $settings['offset'] ? absint( $settings['offset'] ) : 0;
        $posts_per_page = isset($args['posts_per_page']) && $args['posts_per_page'] > 0 ? $args['posts_per_page'] : -1 ;

        set_transient( 'eael_post_grid_read_more_button_text_'. $this->get_id(), $this->get_settings_for_display('read_more_button_text'), DAY_IN_SECONDS );
        set_transient( 'eael_post_grid_excerpt_expanison_indicator_'. $this->get_id(), $this->get_settings_for_display('excerpt_expanison_indicator'), DAY_IN_SECONDS );
        $settings['read_more_button_text'] = $this->get_settings_for_display('read_more_button_text');
        $settings['excerpt_expanison_indicator'] = $this->get_settings_for_display('excerpt_expanison_indicator');

        $args['ignore_sticky_posts'] = isset( $settings['ignore_sticky_posts'] ) && 'yes' === $settings['ignore_sticky_posts'];

        if(file_exists($template)){
            $query = new \WP_Query( $args );

            if ( $query->have_posts() ) {
	            $found_posts      = $query->found_posts - $offset;
	            $max_page         = ceil( $found_posts / absint( $posts_per_page ) );
	            $args['max_page'] = $max_page;

                while ( $query->have_posts() ) {
                    $query->the_post();
                    include($template);
                }
            }else {
                echo '<p class="no-posts-found">' . esc_html__( 'No posts found!', 'essential-addons-for-elementor-lite' ) . '</p>';
            }
            wp_reset_postdata();
        } else {
            echo '<p class="no-posts-found">' . esc_html__( 'No Layout Found', 'essential-addons-for-elementor-lite' ) . '</p>';
        }


        echo '</div>
            <div class="clearfix"></div>
        </div>';

	    if ( $found_posts > $posts_per_page ) {
		    $this->print_load_more_button( $settings, $args, $dir_name );
	    }

        if (Plugin::instance()->editor->is_edit_mode()) {?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    jQuery(".eael-post-grid").each(function() {
                        var $scope = jQuery(".elementor-element-<?php echo esc_js( $this->get_id() ); ?>"),
                            $gallery = $(this),
                            $layout_mode = $gallery.data('layout-mode');

                        if ( $layout_mode === 'masonry' ) {
                            // init isotope
                            var $isotope_gallery = $gallery.isotope({
                                itemSelector: ".eael-grid-post",
                                layoutMode: $layout_mode,
                                percentPosition: true
                            });

                            // layout gal, while images are loading
                            $isotope_gallery.imagesLoaded().progress(function() {
                                $isotope_gallery.isotope("layout");
                            });

                            $('.eael-grid-post', $gallery).resize(function() {
                                $isotope_gallery.isotope("layout");
                            });
                        }
                    });
                });
            </script>
            <?php
        }
    }
}

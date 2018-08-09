<?php
namespace Elementor;
use Elementor\Group_Control_Base;

function eael_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'essential-addons-elementor',
        [
            'title'  => 'Essential Addons',
            'icon' => 'font'
        ],
        1
    );

    /**
     * Initialize EAE_Helper
     */
    new EAE_Helper;

}
add_action('elementor/init','Elementor\eael_elementor_init');


trait ElementsCommonFunctions {

    /**
     * For Exclude Option
     */
    public function add_exclude_controls( ) {
        $this->add_control(
            'post__not_in',
            [
                'label' => __( 'Exclude', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'options' => eael_get_all_types_post(),
                'label_block' => true,
                'post_type' => '',
                'multiple' => true,
                'condition' => [
                    'eaeposts_post_type!' => 'by_id'
                ],
            ]
        );
    }

    protected function query_controls(){

        if( 'eael-content-ticker' === $this->get_name() ) {
            $this->start_controls_section(
                'eael_section_content_ticker_filters',
                [
                    'label' => __( 'Dynamic Content Settings', 'essential-addons-elementor' ),
                    'condition' => [
                        'eael_ticker_type' => 'dynamic'
                    ]
                ]
            );
        }

        if( 'eael-content-timeline' === $this->get_name() ) {
            $this->start_controls_section(
                'eael_section_post_timeline_filters',
                [
                    'label' => __( 'Dynamic Content Settings', 'essential-addons-elementor' ),
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic'
                    ]
                ]
            );
        }

        if( 'eael-content-timeline' !== $this->get_name() && 'eael-content-ticker' !== $this->get_name() ) {
            $this->start_controls_section(
                'eael_section_post_timeline_filters',
                [
                    'label' => __( 'Query', 'essential-addons-elementor' ),
                ]
            );
        }

        $this->add_group_control(
            EAE_Posts_Group_Control::get_type(),
            [
                'name' => 'eaeposts'
            ]
        );

        $this->add_exclude_controls();

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Posts Per Page', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '4'
            ]
        );
		
        $this->add_control(
            'offset',
            [
                'label' => __( 'Offset', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order By', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => 'desc',

            ]
        );

        $this->end_controls_section();

    }

    /**
     * Go Premium
     */
    protected function eae_go_premium(){
        $this->start_controls_section(
			'eael_section_pro',
			[
				'label' => __( 'Go Premium for More Features', 'essential-addons-elementor' )
			]
		);

        $this->add_control(
            'eael_control_get_pro',
            [
                'label' => __( 'Unlock more possibilities', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( '', 'essential-addons-elementor' ),
						'icon' => 'fa fa-unlock-alt',
					],
				],
				'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="https://essential-addons.com/elementor/buy.php" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
            ]
        );

        $this->end_controls_section();
    }
    

    /**
     * Layout Controls For All Post Block!
     * if needed!
     */
    protected function layout_controls(){

        $this->start_controls_section(
			'eael_section_post_timeline_layout',
			[
				'label' => __( 'Layout Settings', 'essential-addons-elementor' )
			]
        );
        
        if( 'eael-post-grid' === $this->get_name() ) {
            $this->add_control(
                'eael_post_grid_columns',
                [
                    'label' => esc_html__( 'Number of Columns', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'eael-col-4',
                    'options' => [
                        'eael-col-1' => esc_html__( 'Single Column', 'essential-addons-elementor' ),
                        'eael-col-2' => esc_html__( 'Two Columns',   'essential-addons-elementor' ),
                        'eael-col-3' => esc_html__( 'Three Columns', 'essential-addons-elementor' ),
                        'eael-col-4' => esc_html__( 'Four Columns',  'essential-addons-elementor' ),
                        'eael-col-5' => esc_html__( 'Five Columns',  'essential-addons-elementor' ),
                        'eael-col-6' => esc_html__( 'Six Columns',   'essential-addons-elementor' ),
                    ],
                ]
            );
        }

        if( 'eael-post-block' === $this->get_name() ) {
            $this->add_control(
                'grid_style',
                [
                    'label' => esc_html__( 'Post Block Style Preset', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'post-block-style-default',
                    'options' => [
                        'post-block-style-default' => esc_html__( 'Default', 'essential-addons-elementor' ),
                        'post-block-style-overlay' => esc_html__( 'Overlay',   'essential-addons-elementor' ),
                    ],
                ]
            );
        }

        if( 'eael-post-carousel' !== $this->get_name() ) {

            /**
             * Show Read More 
             * @uses ContentTimeLine Elements - EAE
             */
            if( 'eael-content-timeline' === $this->get_name() ) {

                $this->add_control(
                    'eael_show_read_more',
                    [
                        'label' => __( 'Show Read More', 'essential-addons-elementor' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            '1' => [
                                'title' => __( 'Yes', 'essential-addons-elementor' ),
                                'icon' => 'fa fa-check',
                            ],
                            '0' => [
                                'title' => __( 'No', 'essential-addons-elementor' ),
                                'icon' => 'fa fa-ban',
                            ]
                        ],
                        'default' => '1',
                        'condition' => [
                            'eael_content_timeline_choose' => 'dynamic'
                        ]
                    ]
                );
        
                $this->add_control(
                    'eael_read_more_text',
                    [
                        'label' => esc_html__( 'Label Text', 'essential-addons-elementor' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'default' => esc_html__( 'Read More', 'essential-addons-elementor' ),
                        'condition' => [
                            'eael_content_timeline_choose' => 'dynamic',
                            'eael_show_read_more' => '1',
                        ]
                    ]
                );
                
            } else {
             
                $this->add_control(
                    'show_load_more',
                    [
                        'label' => __( 'Show Load More', 'essential-addons-elementor' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            '1' => [
                                'title' => __( 'Yes', 'essential-addons-elementor' ),
                                'icon' => 'fa fa-check',
                            ],
                            '0' => [
                                'title' => __( 'No', 'essential-addons-elementor' ),
                                'icon' => 'fa fa-ban',
                            ]
                        ],
                        'default' => '0'
                    ]
                );
        
                $this->add_control(
                    'show_load_more_text',
                    [
                        'label' => esc_html__( 'Label Text', 'essential-addons-elementor' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => false,
                        'default' => esc_html__( 'Load More', 'essential-addons-elementor' ),
                        'condition' => [
                            'show_load_more' => '1',
                        ]
                    ]
                );
            }

        }

        if( 'eael-content-timeline' !== $this->get_name() ) {
            $this->add_control(
                'eael_show_image',
                [
                    'label' => __( 'Show Image', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __( 'Yes', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __( 'No', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-ban',
                        ]
                    ],
                    'default' => '1'
                ]
            );
            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'image',
                    'exclude' => [ 'custom' ],
                    'default' => 'medium',
                    'condition' => [
                        'eael_show_image' => '1',
                    ]
                ]
            );

        }
        
        if( 'eael-content-timeline' === $this->get_name() ) {

            $this->add_control(
                'eael_show_image_or_icon',
                [
                    'label' => __( 'Show Circle Image / Icon', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'img' => [
                            'title' => __( 'Image', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-picture-o',
                        ],
                        'icon' => [
                            'title' => __( 'Icon', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-info',
                        ],
                        'bullet' => [
                            'title' => __( 'Bullet', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-circle',
                        ]
                    ],
                    'default' => 'icon',
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic'
                    ]
                ]
            );

            $this->add_control(
                'eael_icon_image',
                [
                    'label' => esc_html__( 'Icon Image', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' => [
                        'eael_show_image_or_icon' => 'img',
                    ]
                ]
            );
            $this->add_control(
                'eael_icon_image_size',
                [
                    'label' => esc_html__( 'Icon Image Size', 'essential-addons-elementor' ),
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
                    'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-pencil',
                    'condition' => [
                        'eael_content_timeline_choose' => 'dynamic',
                        'eael_show_image_or_icon'      => 'icon',
                    ]
                ]
            );

        }

		$this->add_control(
            'eael_show_title',
            [
                'label' => __( 'Show Title', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );

		$this->add_control(
            'eael_show_excerpt',
            [
                'label' => __( 'Show excerpt', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );

        $this->add_control(
            'eael_excerpt_length',
            [
                'label' => __( 'Excerpt Words', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'eael_show_excerpt' => '1',
                ],
                'description' => '<span class="pro-feature"> Pro Feature. Get <a href="https://essential-addons.com/elementor/buy.php" target="_blank">Pro version</a> </span>'
            ]
        );

        if( 'eael-post-grid' === $this->get_name() || 'eael-post-block' === $this->get_name() || 'eael-post-carousel' === $this->get_name() ) {

            $this->add_control(
                'eael_show_meta',
                [
                    'label' => __( 'Show Meta', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __( 'Yes', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-check',
                        ],
                        '0' => [
                            'title' => __( 'No', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-ban',
                        ]
                    ],
                    'default' => '1'
                ]
            );

            $this->add_control(
                'meta_position',
                [
                    'label' => esc_html__( 'Meta Position', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'meta-entry-footer',
                    'options' => [
                        'meta-entry-header' => esc_html__( 'Entry Header', 'essential-addons-elementor' ),
                        'meta-entry-footer' => esc_html__( 'Entry Footer',   'essential-addons-elementor' ),
                    ],
                    'condition' => [
                        'eael_show_meta' => '1',
                    ]
                ]
            );

        }

		$this->end_controls_section();

    }

    /**
     * Load More Button Style
     * with Hover!
     */
    protected function load_more_button_style(){

        $this->start_controls_section(
            'eael_section_load_more_btn',
            [
                'label' => __( 'Load More Button Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                	'show_load_more' => '1'
                ]
            ]
        );

		$this->add_responsive_control(
			'eael_post_grid_load_more_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_post_grid_load_more_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
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

		$this->start_controls_tabs( 'eael_post_grid_load_more_btn_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_post_grid_load_more_btn_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_post_grid_load_more_btn_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
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
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
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
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-load-more-button',
				]
			);

			$this->add_control(
				'eael_post_grid_load_more_btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
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
					'separator' => 'before'
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_post_grid_load_more_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_post_grid_load_more_btn_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
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
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
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
					'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
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
					'separator' => 'before'
				]
			);
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

    }

}

class EAE_Helper {

    public function __construct() {
        $this->add_actions();
    }

    public function register_controls() {
        $controls_manager = Plugin::instance()->controls_manager;
        $controls_manager->add_group_control( EAE_Posts_Group_Control::get_type(), new EAE_Posts_Group_Control() );
    }

    protected function add_actions() {
        add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
    }

    public static function get_query_args( $control_id, $settings ) {
        $defaults = [
            $control_id . '_post_type' => 'post',
            $control_id . '_posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
        ];

        $settings = wp_parse_args( $settings, $defaults );

        $post_type = $settings[ $control_id . '_post_type' ];

        $query_args = [
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish', // Hide drafts/private posts for admins
        ];

        if ( 'by_id' === $post_type ) {
            $query_args['post_type'] = 'any';
            $query_args['post__in']  = $settings[ $control_id . '_posts_ids' ];

            if ( empty( $query_args['post__in'] ) ) {
                // If no selection - return an empty query
                $query_args['post__in'] = [ 0 ];
            }
        } else {
            $query_args['post_type'] = $post_type;
            $query_args['posts_per_page'] = $settings['posts_per_page'];
            $query_args['tax_query'] = [];

            $query_args['offset'] = $settings['offset'];

            $taxonomies = get_object_taxonomies( $post_type, 'objects' );

            foreach ( $taxonomies as $object ) {
                $setting_key = $control_id . '_' . $object->name . '_ids';

                if ( ! empty( $settings[ $setting_key ] ) ) {
                    $query_args['tax_query'][] = [
                        'taxonomy' => $object->name,
                        'field' => 'term_id',
                        'terms' => $settings[ $setting_key ],
                    ];
                }
            }
        }

        if ( ! empty( $settings[ $control_id . '_authors' ] ) ) {
            $query_args['author__in'] = $settings[ $control_id . '_authors' ];
        }

        $post__not_in = [];
        if ( ! empty( $settings['post__not_in'] ) ) {
            $post__not_in = array_merge( $post__not_in, $settings['post__not_in'] );
            $query_args['post__not_in'] = $post__not_in;
        }

        if( isset( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 ) {
            $query_args['tax_query']['relation'] = 'OR';
        }

        return $query_args;
    }

}

/**
 * Group Control For EAE - Elements ( Posts ).
 * @since 2.10.0
 */

if( class_exists( 'Elementor\Plugin' ) ) : 
    class EAE_Posts_Group_Control extends Group_Control_Base {

        protected static $fields;

        public static function get_type() {
            return 'eaeposts';
        }

        public static function on_export_remove_setting_from_element( $element, $control_id ) {
            unset( $element['settings'][ $control_id . '_posts_ids' ] );
            unset( $element['settings'][ $control_id . '_authors' ] );

            foreach ( Utils::get_post_types() as $post_type => $label ) {
                $taxonomy_filter_args = [
                    'show_in_nav_menus' => true,
                    'object_type' => [ $post_type ],
                ];

                $taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

                foreach ( $taxonomies as $taxonomy => $object ) {
                    unset( $element['settings'][ $control_id . '_' . $taxonomy . '_ids' ] );
                }
            }

            return $element;
        }

        protected function init_fields() {
            $fields = [];

            $fields['post_type'] = [
                'label' => __( 'Source', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
            ];

            $fields['posts_ids'] = [
                'label' => __( 'Search & Select', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'post_type' => '',
                'options' => \eael_get_all_types_post(),
                'label_block' => true,
                'multiple' => true,
                'condition' => [
                    'post_type' => 'by_id',
                ],
            ];

            $fields['authors'] = [
                'label' => __( 'Author', 'essential-addons-elementor' ),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => $this->get_authors(),
                'condition' => [
                    'post_type!' => [
                        'by_id',
                    ],
                ],
            ];

            return $fields;
        }

        protected function prepare_fields( $fields ) {

            $post_types = eael_get_post_types();

            $post_types_options = $post_types;

            $post_types_options['by_id'] = __( 'Manual Selection', 'essential-addons-elementor' );

            $fields['post_type']['options'] = $post_types_options;

            $fields['post_type']['default'] = key( $post_types );

            $fields['posts_ids']['object_type'] = array_keys( $post_types );

            $taxonomy_filter_args = [
                'show_in_nav_menus' => true,
            ];

            if ( ! empty( $args['post_type'] ) ) {
                $taxonomy_filter_args['object_type'] = [ $args['post_type'] ];
            }

            $taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

            foreach ( $taxonomies as $taxonomy => $object ) {
                $taxonomy_args = [
                    'label' => $object->label,
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'object_type' => $taxonomy,
                    'options' => [],
                    'condition' => [
                        'post_type' => $object->object_type,
                    ],
                ];

                $options = [];

                $taxonomy_args['type'] = Controls_Manager::SELECT2;

                $terms = get_terms( $taxonomy );

                foreach ( $terms as $term ) {
                    $options[ $term->term_id ] = $term->name;
                }

                $taxonomy_args['options'] = $options;

                $fields[ $taxonomy . '_ids' ] = $taxonomy_args;
            }

            unset( $fields['post_format_ids'] );

            return parent::prepare_fields( $fields );
        }

        /**
         * All authors name and ID, who published at least 1 post.
         * @return array
         */
        public function get_authors() {
            $user_query = new \WP_User_Query(
                [
                    'who' => 'authors',
                    'has_published_posts' => true,
                    'fields' => [
                        'ID',
                        'display_name',
                    ],
                ]
            );

            $authors = [];

            foreach ( $user_query->get_results() as $result ) {
                $authors[ $result->ID ] = $result->display_name;
            }

            return $authors;
        }

        protected function get_default_options() {
            return [
                'popover' => false,
            ];
        }
    }
endif;
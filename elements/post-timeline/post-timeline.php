<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_PostTimeline extends Widget_Base {

	public function get_name() {
		return 'eael-post-timeline';
	}

	public function get_title() {
		return __( 'EA Post Timeline', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'eael_section_post_timeline_filters',
			[
				'label' => __( 'Post Settings', 'essential-addons-elementor' )
			]
		);


		$this->add_control(
            'eael_post_type',
            [
                'label' => __( 'Post Type', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_types(),
                'default' => 'post',

            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Categories', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => eael_post_type_categories(),
                'condition' => [
                       'eael_post_type' => 'post'
                ]
            ]
        );


        $this->add_control(
            'eael_posts_count',
            [
                'label' => __( 'Number of Posts', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '4'
            ]
        );

        $this->add_control(
            'eael_post_offset',
            [
                'label' => __( 'Post Offset', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );

        $this->add_control(
            'eael_post_orderby',
            [
                'label' => __( 'Order By', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'eael_post_order',
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

        $this->start_controls_section(
			'eael_section_post_timeline_layout',
			[
				'label' => __( 'Layout Settings', 'essential-addons-elementor' )
			]
		);


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
                ]

            ]
        );


		$this->end_controls_section();

        $this->start_controls_section(
            'eael_section_post_timeline_style',
            [
                'label' => __( 'Timeline Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
			'eael_timeline_overlay_color',
			[
				'label' => __( 'Overlay Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'description' => __('Leave blank or Clear to use default gradient overlay', 'essential-addons-elementor'),
				'default' => 'linear-gradient(45deg, #3f3f46 0%, #05abe0 100%) repeat scroll 0 0 rgba(0, 0, 0, 0)',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-inner' => 'background: {{VALUE}}',
				]

			]
		);

        $this->add_control(
			'eael_timeline_bullet_color',
			[
				'label' => __( 'Timeline Bullet Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#9fa9af',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-bullet' => 'background-color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_timeline_bullet_border_color',
			[
				'label' => __( 'Timeline Bullet Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-bullet' => 'border-color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_timeline_vertical_line_color',
			[
				'label' => __( 'Timeline Vertical Line Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> 'rgba(83, 85, 86, .2)',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post:after' => 'background-color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_timeline_border_color',
			[
				'label' => __( 'Border & Arrow Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-inner' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-timeline-post-inner::after' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .eael-timeline-post:nth-child(2n) .eael-timeline-post-inner::after' => 'border-right-color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_timeline_date_background_color',
			[
				'label' => __( 'Date Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> 'rgba(0, 0, 0, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post time' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-timeline-post time::before' => 'border-bottom-color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_timeline_date_color',
			[
				'label' => __( 'Date Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post time' => 'color: {{VALUE}};',
				]

			]
		);


		$this->end_controls_section();

        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __( 'Typography', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

		$this->add_control(
			'eael_timeline_title_style',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_timeline_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-title h2' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'eael_timeline_title_alignment',
			[
				'label' => __( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-title h2' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_timeline_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-timeline-post-title h2',
			]
		);

		$this->add_control(
			'eael_timeline_excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_timeline_excerpt_color',
			[
				'label' => __( 'Excerpt Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-excerpt p' => 'color: {{VALUE}};',
				]
			]
		);

        $this->add_responsive_control(
			'eael_timeline_excerpt_alignment',
			[
				'label' => __( 'Excerpt Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-timeline-post-excerpt p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_timeline_excerpt_typography',
				'label' => __( 'excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-timeline-post-excerpt p',
			]
		);


		$this->end_controls_section();

	}


	protected function render( ) {
        $settings = $this->get_settings();

        $post_args = eael_get_post_settings($settings);

        $posts = eael_get_post_data($post_args);

        ?>

		<div id="eael-post-timeline-<?php echo esc_attr($this->get_id()); ?>" class="eael-post-timeline">
		    <div class="eael-post-timeline">
		    <?php
		        if(count($posts)){
		            global $post;
		            ?>
		                <?php
		                    foreach($posts as $post){
		                        setup_postdata($post);
		                    ?>
		                    <article class="eael-timeline-post eael-timeline-column">
		                        <div class="eael-timeline-bullet"></div>
		                        <div class="eael-timeline-post-inner">
		                            <a class="eael-timeline-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>">
			                            <time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time>
			                            <div class="eael-timeline-post-image" <?php if($settings['eael_show_image'] == 1){ ?> style="background-image: url('<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])?>');" <?php } ?>></div>
			                            <?php if($settings['eael_show_excerpt']){ ?>
			                            <div class="eael-timeline-post-excerpt">
			                                <p><?php echo  eael_get_excerpt_by_id(get_the_ID(),$settings['eael_excerpt_length']);?></p>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if($settings['eael_show_title']){ ?>
			                            <div class="eael-timeline-post-title">
			                                <h2><?php the_title(); ?></h2>
			                            </div>
			                            <?php } ?>
		                            </a>
		                        </div>
		                    </article>
		                    <?php
		                    }
		                    wp_reset_postdata();
		                ?>
		            <?php
		        }
		    ?>
		    </div>
		</div>


        <?php
	}

	protected function content_template() {
		?>

		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_PostTimeline() );
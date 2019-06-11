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
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;

class Post_Grid extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'eael-post-grid';
	}

	public function get_title() {
		return __( 'EA Post Grid', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

		/**
		 * Query And Layout Controls!
		 * @source includes/elementor-helper.php
		 */
		$this->eael_query_controls();
		$this->eael_layout_controls();

		/**
		 * Grid Style Controls!
		 */
        $this->start_controls_section(
            'eael_section_post_grid_style',
            [
                'label' => __( 'Post Grid Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
			'eael_post_grid_bg_color',
			[
				'label' => __( 'Post Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-holder' => 'background-color: {{VALUE}}',
				]

			]
		);

		$this->add_responsive_control(
			'eael_post_grid_spacing',
			[
				'label' => esc_html__( 'Spacing Between Items', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_post_grid_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-grid-post-holder',
			]
		);

		$this->add_control(
			'eael_post_grid_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
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

        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __( 'Color & Typography', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

		$this->add_control(
			'eael_post_grid_title_style',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#303133',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-title a' => 'color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_post_grid_title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#23527c',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-title:hover, {{WRAPPER}} .eael-entry-title a:hover' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'eael_post_grid_title_alignment',
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
					'{{WRAPPER}} .eael-entry-title' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_grid_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-entry-title',
			]
		);

		$this->add_control(
			'eael_post_grid_excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_excerpt_color',
			[
				'label' => __( 'Excerpt Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-excerpt p' => 'color: {{VALUE}};',
				]
			]
		);

        $this->add_responsive_control(
			'eael_post_grid_excerpt_alignment',
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
					'{{WRAPPER}} .eael-grid-post-excerpt p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_grid_excerpt_typography',
				'label' => __( 'Excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-grid-post-excerpt p',
			]
		);


		$this->add_control(
			'eael_post_grid_meta_style',
			[
				'label' => __( 'Meta Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_meta_color',
			[
				'label' => __( 'Meta Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-meta, .eael-entry-meta a' => 'color: {{VALUE}};',
				]
			]
		);

        $this->add_responsive_control(
			'eael_post_grid_meta_alignment',
			[
				'label' => __( 'Meta Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-entry-footer' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .eael-entry-meta'	=> 'justify-content: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'		=> 'eael_post_grid_meta_typography',
				'label'		=> __( 'Meta Typography', 'essential-addons-elementor' ),
				'scheme'	=> Scheme_Typography::TYPOGRAPHY_3,
				'selector'	=> '{{WRAPPER}} .eael-entry-meta > div, {{WRAPPER}} .eael-entry-meta > span',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
            'eael_section_hover_card_styles',
            [
				'label' => __( 'Hover Card Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
            ]
		);
		
		$this->add_control(
			'eael_post_grid_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade-in',
				'options' => [
					'none'		=> esc_html__( 'None', 'essential-addons-elementor' ),
					'fade-in'	=> esc_html__( 'FadeIn', 'essential-addons-elementor' ),
					'zoom-in'	=> esc_html__( 'ZoomIn', 'essential-addons-elementor' ),
					'slide-up'	=> esc_html__( 'SlideUp', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_post_grid_bg_hover_icon',
			[
				'label'		=> __( 'Post Hover Icon', 'essential-addons-elementor' ),
				'type'		=> Controls_Manager::ICON,
				'default'	=> 'fa fa-long-arrow-right',
				'condition'	=> [
					'eael_post_grid_hover_animation!'	=> 'none'
				]
			]
		);

		$this->add_control(
			'eael_post_grid_hover_bg_color',
			[
				'label' => __( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0, .75)',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post .eael-entry-overlay' => 'background-color: {{VALUE}}',
				]

			]
		);

        $this->add_control(
			'eael_post_grid_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i' => 'color: {{VALUE}}',
				]

			]
		);

		$this->add_responsive_control(
			'eael_post_grid_hover_icon_fontsize',
			[
				'label' => __( 'Icon font size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'size_units' => [ 'px', 'em' ],
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
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post .eael-entry-overlay > i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Load More Button Style Controls!
		 */
		$this->eael_load_more_button_style();

	}


	protected function render() {
        $settings = $this->get_settings();
		/**
		 * Setup the post arguments.
		 */
		$settings['post_style'] = 'grid';
		$post_args = $this->eael_get_post_settings( $settings );
		$query_args = $this->eael_get_query_args( 'eaeposts', $settings );
		$query_args = array_merge($query_args, $post_args, $settings);

		if( isset( $query_args['tax_query'] ) ) {
			$tax_query = $query_args['tax_query'];
		}
		/**
		 * Get posts from database.
		 */
		$posts = $this->eael_load_more_ajax($query_args);
		/**
		 * Set total posts.
		 */
		$total_post = $posts['count'];
		?>
		<div id="eael-post-grid-<?php echo esc_attr($this->get_id()); ?>" class="eael-post-grid-container <?php echo esc_attr($settings['eael_post_grid_columns'] ); ?>">
		    <div class="eael-post-grid eael-post-appender-<?php echo esc_attr( $this->get_id() ); ?>">
			<?php
		        if( ! empty( $posts['content'] ) ){
					echo $posts['content'];
		        } else {
					echo '<p class="text-danger">Something went wrong.</p>';
				}
		    ?>
		    </div>
		    <div class="clearfix"></div>
		</div>
		<?php 
			if( 1 == $settings['show_load_more'] ) : 
				if( 
					$settings['posts_per_page'] != '-1'
					&& $total_post != $settings['posts_per_page'] 
					&& $total_post > intval( $settings['offset'] ) + intval( ! empty( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : 4 ) 
				) : 
		?>
		<!-- Load More Button -->
		<div class="eael-load-more-button-wrap">
			<button class="eael-load-more-button" id="eael-load-more-btn-<?php echo $this->get_id(); ?>">
				<div class="eael-btn-loader button__loader"></div>
				<span><?php echo esc_html__( $settings['show_load_more_text'], 'essential-addons-elementor' ); ?></span>
			</button>
		</div>
		<!-- Loading Lode More Js -->
		<script>
			jQuery(document).ready(function($) {
				'use strict';
				var options = {
					totalPosts: <?php echo $total_post; ?>,
					loadMoreBtn: $( '#eael-load-more-btn-<?php echo $this->get_id(); ?>' ),
					postContainer: $( '.eael-post-appender-<?php echo esc_attr( $this->get_id() ); ?>' ),
					postStyle: 'grid',
				}

				var settings = {
					postType: '<?php echo isset($settings['eaeposts_post_type']) ? $settings['eaeposts_post_type'] : ''; ?>',
					perPage: <?php echo $settings['posts_per_page'] != '' ? $settings['posts_per_page'] : '4'; ?>,
					postOrder: '<?php echo $settings['order']; ?>',
					orderBy: '<?php echo $settings['orderby']; ?>',
					showImage: <?php echo $settings['eael_show_image']; ?>,
					imageSize: '<?php echo $settings['image_size']; ?>',
					showTitle: <?php echo $settings['eael_show_title']; ?>,
					showExcerpt: <?php echo $settings['eael_show_excerpt']; ?>,
					showMeta: <?php echo $settings['eael_show_meta']; ?>,
					offset: <?php echo intval( $settings['offset'] ); ?>,
					metaPosition: '<?php echo $settings['meta_position']; ?>',
					excerptLength: parseInt( <?php echo $settings['eael_excerpt_length']; ?>, 10 ),
					btnText: '<?php echo $settings['show_load_more_text']; ?>',
					tax_query: <?php echo json_encode( ! empty( $tax_query ) ? $tax_query : [] ); ?>,
					exclude_posts: <?php echo json_encode( ! empty( $settings['post__not_in'] ) ? $settings['post__not_in'] : [] ); ?>,
					post__in: <?php echo json_encode( ! empty( $settings['post__in'] ) ? $settings['post__in'] : [] ); ?>,
					hover_animation: '<?php echo $settings['eael_post_grid_hover_animation']; ?>',
					hover_icon: '<?php echo $settings['eael_post_grid_bg_hover_icon']; ?>'
				}
				eaelLoadMore( options, settings );
			});
		</script>
		<?php endif; endif; 
	}

	protected function content_template() {}
}

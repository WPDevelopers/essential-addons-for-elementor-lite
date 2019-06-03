<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class Post_Timeline extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

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
		
		/**
		 * Query And Layout Controls!
		 * @source includes/elementor-helper.php
		 */
		$this->eael_query_controls();
		$this->eael_layout_controls();

		if(!apply_filters('eael/pro_enabled', false)) {
			$this->eael_go_premium();
		}


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
				'default'=> '#e5eaed',
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

		$this->eael_load_more_button_style();

	}


	protected function render( ) {
        $settings = $this->get_settings();
		/**
		 * Setup the post arguments.
		 */
		$settings['post_style'] = 'timeline';
		$post_args = $this->eael_get_post_settings( $settings );
		$query_args = $this->eael_get_query_args( 'eaeposts', $this->get_settings() );
		$settings = $query_args = array_merge( $query_args, $post_args );

		if( isset( $query_args['tax_query'] ) ) {
			$tax_query = $query_args['tax_query'];
		}
		/**
		 * Get posts from database.
		 */
		$posts = $this->eael_load_more_ajax( $query_args );
		/**
		 * Set total posts.
		 */
		$total_post = $posts['count'];

		$this->add_render_attribute(
			'eael_post_timeline_wrapper',
			[
				'id'		=> "eael-post-timeline-{$this->get_id()}",
				'class'		=> 'eael-post-timeline',
				'data-total_posts'	=> $total_post,
				'data-timeline_id'	=> $this->get_id(),
				
				'data-post_type'	=> $settings['post_type'],
				'data-posts_per_page'	=> $settings['posts_per_page'] ? $settings['posts_per_page'] : 4,
				'data-post_order'		=> $settings['order'],
				'data-post_orderby'		=> $settings['orderby'],
				'data-post_offset'		=> $settings['offset'],

				'data-show_images'	=> $settings['eael_show_image'],
				'data-image_size'	=> $settings['image_size'],
				'data-show_title'	=> $settings['eael_show_title'],

				'data-show_excerpt'	=> $settings['eael_show_excerpt'],
				'data-excerpt_length'	=> $settings['eael_excerpt_length'],

				'data-btn_text'			=> $settings['show_load_more_text'],

				'data-tax_query'		=> json_encode( ! empty( $tax_query ) ? $tax_query : [] ),
				'data-post__in'		=> json_encode( ! empty( $settings['post__in'] ) ? $settings['post__in'] : [] ),

				'data-exclude_posts'	=> json_encode( ! empty( $exclude_posts ) ? $exclude_posts : [] ),
			]
		);

		$this->add_render_attribute(
			'eael_post_timeline',
			[
				'class'	=> [ 'eael-post-timeline', "eael-post-appender-{$this->get_id()}" ]
			]
		);

        ?>
		<div <?php echo $this->get_render_attribute_string('eael_post_timeline_wrapper'); ?>>
		    <div <?php echo $this->get_render_attribute_string('eael_post_timeline'); ?>>
				<?php
					if( ! empty( $posts['content'] ) ){
						echo $posts['content'];
					} else {
						echo '<p>Something went wrong.</p>';
					}
				?>
		    </div>
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
		<?php endif; endif;
	}

	protected function content_template() { }
}
<?php 
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
   exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Breadcrumbs extends Widget_Base {
   public function get_name() {
		return 'eael-breadcrumbs';
	}

   public function get_title() {
		return esc_html__( 'Breadcrumbs', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-breadcrumbs';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

   public function get_keywords() {
		return [ 'breadcrumbs', 'shop', 'page', 'post', 'product', 'ea breadcrumbs' ];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/docs/ea-breadcrumbs/';
	}

   protected function register_controls() {
      //General Section
      $this->eael_breadcrumb_general();
      //Style Section
      $this->eael_breadcrumb_style();

      //
      $this->eael_breadcrumb_separator_style();

      //
      $this->eael_breadcrumb_prefix_style();
   }

   protected function eael_breadcrumb_general() {
      $this->start_controls_section(
         'breadcrumb_general',
         [
            'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_control(
			'breadcrumb_home_text',
			[
				'label'       => esc_html__( 'Label For Home', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Home', 'essential-addons-for-elementor-lite' ),
            'ai'  => [
					'active' => false,
				],
			]
		);

      //
      $this->add_control(
			'breadcrumb_prefix_switch',
			[
				'label'        => esc_html__( 'Show Prefix', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
            'separator' => 'before',
			]
		);

      $this->add_control(
			'eael_breadcrumb_prefix_type',
			[
				'label'   => esc_html__( 'Prefix Type', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-nerd',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-area',
					],
				],
				'default'   => 'icon',
            'condition' => [
					'breadcrumb_prefix_switch' => 'yes',
				],
			]
		);

      $this->add_control(
			'eael_breadcrumb_prefix_icon',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_breadcrumb_prefix_type' => 'icon',
					'breadcrumb_prefix_switch'    => 'yes',
				],
			]
		);

      $this->add_control(
			'eael_breadcrumb_prefix_text',
			[
				'label'       => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Browse: ', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_breadcrumb_prefix_type' => 'text',
					'breadcrumb_prefix_switch'    => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

      $this->end_controls_tabs();

      //
      $this->add_control(
			'eael_separator_type',
			[
				'label'   => esc_html__( 'Separator Type', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-nerd',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-area',
					],
				],
				'default' => 'text',
            'separator' => 'before',
			]
		);

      $this->add_control(
			'eael_separator_icon',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-angle-double-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_separator_type' => 'icon',
				],
			]
		);

      $this->add_control(
			'eael_separator_type_text',
			[
				'label'       => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( '/', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'eael_separator_type' => 'text',
				],
				'ai' => [
					'active' => false,
				],
			]
		);
      
      $this->end_controls_section();
   }

   protected function eael_breadcrumb_style() {
      $this->start_controls_section(
         'breadcrumb_style',
         [
            'label' => esc_html__( 'Style', 'essential-addons-for-elementor-lite' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

		$this->add_responsive_control(
			'breadcrumb_align',
			[
				'label' => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_breadcrumb_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f8fa',
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'eael_breadcrumb_border',
				'selector' => '{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content',
			]
		);

		$this->add_control(
			'eael_breadcrumb_border_radious',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_breadcrumb_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'breadcrumb_link_color',
			[
				'label'     => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'breadcrumb_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'breadcrumb_typography',
				'selector' => '{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__content',
			]
		);
      
      $this->end_controls_section();
   }

   protected function eael_breadcrumb_prefix_style() {
      $this->start_controls_section(
         'prefix_style',
         [
            'label' => esc_html__( 'Prefix', 'essential-addons-for-elementor-lite' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'breadcrumb_prefix_switch' => 'yes',
				],
         ]
      );

      $this->add_control(
			'prefix_style_icon_heading',
			[
				'label'     => esc_html__( 'Icon Style', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

      $this->add_control(
			'prefix_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix svg path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix i' => 'color: {{VALUE}}',
				],
			]
		);

      $this->add_control(
			'prefix_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

      $this->add_responsive_control(
			'prefix_icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 0,
					'right'  => 8,
					'bottom' => 0,
					'left'   => 0,
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

      $this->add_control(
			'prefix_style_text_heading',
			[
				'label'     => esc_html__( 'Text Style', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

      $this->add_control(
			'prefix_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix span' => 'color: {{VALUE}}',
				],
			]
		);

      $this->add_responsive_control(
			'prefix_text_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => -2,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 0,
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

      $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'prefix_text_typography',
				'selector' => '{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumbs__prefix span',
			]
		);
      
      $this->end_controls_section();
   }

   protected function eael_breadcrumb_separator_style() {
      $this->start_controls_section(
         'separator_style',
         [
            'label' => esc_html__( 'Separator', 'essential-addons-for-elementor-lite' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_control(
			'separator_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#A5A4B0',
            'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator svg path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_separator_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

      $this->add_control(
			'separator_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'eael_separator_type' => 'icon',
				],
			]
		);

      $this->add_responsive_control(
			'separator_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 4,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator svg, 
					{{WRAPPER}} .eael-breadcrumbs .eael-breadcrumb-separator i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_separator_type' => 'icon',
				],
			]
		);
      
      $this->end_controls_section();
   }

   protected function eael_breadcrumb_home_label() {
      $settings = $this->get_settings_for_display();
      if ( ! empty( $settings['breadcrumb_home_text'] ) ) {
         return Helper::eael_wp_kses( $settings['breadcrumb_home_text'] );
      }
      return;
   }

	protected function eael_breadcrumb_prefix() {
		$settings = $this->get_settings_for_display();
		$prefix_type = $settings['eael_breadcrumb_prefix_type'];
		if ( 'yes' == $settings['breadcrumb_prefix_switch'] ) {
			?>
			<div class="eael-breadcrumbs__prefix">
				<?php 
					switch ( $prefix_type ) {
						case 'icon':
							\Elementor\Icons_Manager::render_icon( $settings['eael_breadcrumb_prefix_icon'], [ 'aria-hidden' => 'true' ] );
							break;
						case 'text':
							echo "<span>" . Helper::eael_wp_kses( $settings['eael_breadcrumb_prefix_text'] ) . "</span>";
							break;
					}
				?>
			</div>
			<?php
		}
	}

   protected function eael_breadcrumb_separator() {
      $settings = $this->get_settings_for_display();
      if ( ! empty( $settings['eael_separator_icon'] ) ) {
         ob_start();
         \Elementor\Icons_Manager::render_icon($settings['eael_separator_icon'], ['aria-hidden' => 'true']);
         $separator_icon = ob_get_clean();
         return sprintf( '<span class="eael-breadcrumb-separator">%s</span>', $separator_icon );
      } else {
			return sprintf( '<span class="eael-breadcrumb-separator">%s</span>', Helper::eael_wp_kses( $settings['eael_separator_type_text'] ) );
      }
   }

	protected function eael_wc_breadcrumb() {
      $args = array(
         'delimiter'   => $this->eael_breadcrumb_separator(),
         'wrap_before' => '<nav class="eael-breadcrumbs__content woocommerce-breadcrumb" aria-label="Breadcrumb">',
         'wrap_after'  => '</nav>',
         'before'      => '',
         'after'       => '',
         'home'        => $this->eael_breadcrumb_home_label(),
      ); 

		$this->eael_breadcrumb_prefix();
		woocommerce_breadcrumb( $args ); 
	}

	protected function eael_breadcrumbs() {
		global $post;
		$show_on_home = 1;
		$delimiter    = $this->eael_breadcrumb_separator();
		$home         = $this->eael_breadcrumb_home_label();
		$show_current = 1;
		$before       = '<span class = "eael-current">';
		$after        = '</span>';
		$home_link    = get_bloginfo( 'url' );

		//
		$output = '';
		if ( is_home() || is_front_page() ) {
			if ( $show_on_home == 1 ) {
				$this->eael_breadcrumb_prefix();
				$output .= '<div class="eael-breadcrumbs__content"><a href="' . $home_link . '">' . $home . '</a></div>';
			}
		} else {
			$this->eael_breadcrumb_prefix();
			$output .= '<div class="eael-breadcrumbs__content"><a href="' . $home_link . '">' . $home . '</a> ' . $delimiter . ' ';
			if ( is_category() ) {
				$get_category = get_category( get_query_var( 'cat' ), false );
				if ( $get_category->parent != 0 ) {
					$output .= get_category_parents( $get_category->parent, true, ' ' . $delimiter . ' ' );
				}
				$output .= $before . esc_html__( 'Archive by category', 'essential-addons-for-elementor-lite' ) .' "' . single_cat_title( '', false ) . '"' . $after;
			} elseif ( is_attachment() ) {
				$parent   = get_post( $post->post_parent );
				$cat      = get_the_category( $parent->ID ); 
				$cat      = $cat[0];
				$output   .= get_category_parents( $cat, TRUE, ' ' . $delimiter . ' ' );
				$output   .= '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>';
				if ( $show_current == 1 ) {
					$output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
				} 
			} elseif ( is_page() && ! $post->post_parent ) {
				if ( $show_current == 1 ) {
					$output .= $before . get_the_title() . $after;
				}
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page = get_page( $parent_id );
					$breadcrumbs[] = '<a href="' . get_permalink( $page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					$output .= $breadcrumbs[$i];
					if ($i != count($breadcrumbs)-1) {
						$output .= ' ' . $delimiter . ' ';
					}
				}
				if ($show_current == 1){
					$output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
				} 
			} elseif ( is_single() && ! is_attachment() ) {
				if ( 'post' !== get_post_type() ) {
					$post_type = get_post_type_object( get_post_type() );
					$get_slug = $post_type->rewrite ?? false;
					
					if ($get_slug && is_array($get_slug) && isset($get_slug['slug'])) {
						$slug = $get_slug['slug'];
					} else {
						$slug = '';
					}
					$output .= '<a href="' . $home_link . '/' . $slug . '/">' . $post_type->labels->singular_name . '</a>';
					
					if ( $show_current == 1 ) {
						$output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
					}
				} else {
					$cat  = get_the_category();
					$cat  = $cat[0];
					$cats = get_category_parents( $cat, TRUE, ' ' . $delimiter . ' ' );
					if ( $show_current == 0 ) {
						$cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats ) ;
					}
					$output .= $cats;
					if ( $show_current == 1 ) {
						$output .= $before . get_the_title() . $after;
					}
				}
			} elseif ( ! is_single() && ! is_page() && get_post_type() !== 'post' && ! is_404() ) {
				$post_type = get_post_type_object( get_post_type() );
				$output .= $before . $post_type->labels->singular_name . $after;
			} elseif ( is_search() ) {
				$output .= $before . esc_html__( 'Search results for', 'essential-addons-for-elementor-lite' ) . ' "' . get_search_query() . '"' . $after;
			} elseif ( is_day() ) {
				$output .= '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				$output .= '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_time( 'd' ) . $after;
			} elseif ( is_month() ) {
				$output .= '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_time( 'F' ) . $after;
			} elseif ( is_year() ) {
				$output .= $before . get_the_time( 'Y' ) . $after;
			} elseif ( is_tag() ) {
				$output .= $before . esc_html__( 'Posts tagged', 'essential-addons-for-elementor-lite' ) . ' "' . single_tag_title( '', false ) . '"' . $after;
			} elseif( is_author() ) {
				global $author;
				$user_data = get_userdata( $author );
				$output .= $before . esc_html__( 'Articles posted by', 'essential-addons-for-elementor-lite' ) . $user_data->display_name . $after;
			} elseif ( is_404() ) {
				$output .= $before . esc_html__( 'Error 404', 'essential-addons-for-elementor-lite' ) . $after;
			}
		}
		echo $output;
	}

   protected function render() {
		$product = false;

		if ( class_exists( 'WooCommerce' ) ) {
			$product = wc_get_product( get_the_ID() );
		}

		?>
		<div class="eael-breadcrumbs">
			<?php
			if ( ! $product ) {
				$this->eael_breadcrumbs();
			} else {
				$this->eael_wc_breadcrumb();
			}
			?>
		</div>
		<?php
   }
}
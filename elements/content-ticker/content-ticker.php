<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Content_Ticker extends Widget_Base {

  public function get_name() {
    return 'eael-content-ticker';
  }

  public function get_title() {
    return esc_html__( 'EA Content Ticker', 'essential-addons-elementor' );
  }

  public function get_icon() {
    return 'eicon-call-to-action';
  }

   public function get_categories() {
    return [ 'essential-addons-elementor' ];
  }

  protected function _register_controls() {
    /**
       * Content Ticker Content Settings
       */
      $this->start_controls_section(
        'eael_section_content_ticker_settings',
        [
          'label' => esc_html__( 'Ticker Settings', 'essential-addons-elementor' )
        ]
      );
      $this->add_control(
      'eael_ticker_type',
        [
        'label'         => esc_html__( 'Ticker Type', 'essential-addons-elementor' ),
          'type'      => Controls_Manager::SELECT,
          'default'     => 'dynamic',
          'label_block'   => false,
          'options'     => [
            'dynamic'     => esc_html__( 'Dynamic', 'essential-addons-elementor' ),
            'custom'      => esc_html__( 'Custom', 'essential-addons-elementor' ),
          ],
        ]
    );

      $this->add_control(
        'eael_ticker_type_pro_alert',
        [
          'label' => esc_html__( 'Only available in pro version!', 'essential-addons-elementor' ),
          'type' => Controls_Manager::HEADING,
          'condition' => [
            'eael_ticker_type' => 'custom',
          ]
        ]
      );

    $this->add_control(
      'eael_ticker_tag_text',
      [
        'label' => esc_html__( 'Tag Text', 'essential-addons-elementor' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => false,
        'default' => esc_html__( 'Trending Today', 'essential-addons-elementor' ),
      ]
    );
    $this->add_control(
      'eael_ticker_autoplay',
      [
        'label' => esc_html__( 'Autoplay', 'essential-addons-elementor' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'true',
        'label_on' => __( 'Yes', 'essential-addons-elementor' ),
        'label_off' => __( 'No', 'essential-addons-elementor' ),
        'return_value' => 'true',
      ]
    );
    $this->add_control(
      'eael_ticker_autoplay_speed',
      [
        'label' => esc_html__( 'Autoplay Speed(ms)', 'essential-addons-elementor' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => false,
        'default' => esc_html__( '3000', 'essential-addons-elementor' ),
      ]
    );
    $this->add_control(
      'eael_ticker_slide_speed',
      [
        'label' => esc_html__( 'Slide Speed(ms)', 'essential-addons-elementor' ),
        'type' => Controls_Manager::TEXT,
        'label_block' => false,
        'default' => esc_html__( '300', 'essential-addons-elementor' ),
      ]
    );
    $this->add_control(
      'eael_ticker_arrow',
      [
        'label' => esc_html__( 'Show Nav Arrow', 'essential-addons-elementor' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'true',
        'label_on' => __( 'Yes', 'essential-addons-elementor' ),
        'label_off' => __( 'No', 'essential-addons-elementor' ),
        'return_value' => 'true',
      ]
    );
    $this->add_control(
      'eael_ticker_pause_on_hover',
      [
        'label' => esc_html__( 'Pause On Hover', 'essential-addons-elementor' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'true',
        'label_on' => __( 'Yes', 'essential-addons-elementor' ),
        'label_off' => __( 'No', 'essential-addons-elementor' ),
        'return_value' => 'true',
      ]
    );
    $this->add_control(
      'eael_ticker_fade',
      [
        'label' => esc_html__( 'Fade Effect', 'essential-addons-elementor' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'true',
        'label_on' => __( 'Yes', 'essential-addons-elementor' ),
        'label_off' => __( 'No', 'essential-addons-elementor' ),
        'return_value' => 'true',
      ]
    );
    $this->add_control(
      'eael_ticker_easing',
        [
        'label'         => esc_html__( 'Easing', 'essential-addons-elementor' ),
          'type'      => Controls_Manager::SELECT,
          'default'     => 'ease',
          'label_block'   => false,
          'options'     => [
            'ease'          => esc_html__( 'Ease', 'essential-addons-elementor' ),
            'ease-in'       => esc_html__( 'Ease In', 'essential-addons-elementor' ),
            'ease-in-out'   => esc_html__( 'Ease In Out', 'essential-addons-elementor' ),
          ],
        ]
    );
    $this->add_control(
      'eael_ticker_prev_icon',
      [
        'label' => esc_html__( 'Prev Icon', 'essential-addons-elementor' ),
        'type' => Controls_Manager::ICON,
        'default' => 'fa fa-angle-left',
      ]
    );
    $this->add_control(
      'eael_ticker_next_icon',
      [
        'label' => esc_html__( 'Next Icon', 'essential-addons-elementor' ),
        'type' => Controls_Manager::ICON,
        'default' => 'fa fa-angle-right',
      ]
    );
    $this->end_controls_section();

    /**
       * Content Ticker Dynamic Content Settings
       */
    $this->start_controls_section(
      'eael_section_ticker_dynamic_content-settings',
      [
        'label' => __( 'Dynamic Content Settings', 'essential-addons-elementor' ),
        'condition' => [
          'eael_ticker_type' => ['dynamic', 'custom']
        ]
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


      /**
       * -------------------------------------------
       * Tab Style (Content Ticker)
       * -------------------------------------------
       */
      $this->start_controls_section(
        'eael_section_ticker_style_settings',
        [
          'label' => esc_html__( 'Content Ticker Style', 'essential-addons-elementor' ),
          'tab' => Controls_Manager::TAB_STYLE
        ]
      );

      $this->add_control(
        'eael_ticker_bg_color',
        [
          'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#f9f9f9',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap' => 'background-color: {{VALUE}};',
          ],
        ]
      );

      $this->add_responsive_control(
        'eael_ticker_container_padding',
        [
          'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', 'em', '%' ],
          'selectors' => [
              '{{WRAPPER}} .eael-ticker-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $this->add_responsive_control(
        'eael_ticker_container_margin',
        [
          'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', 'em', '%' ],
          'selectors' => [
              '{{WRAPPER}} .eael-ticker-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $this->add_group_control(
        Group_Control_Border::get_type(),
        [
          'name' => 'eael_ticker_border',
          'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
          'selector' => '{{WRAPPER}} .eael-ticker-wrap',
        ]
      );

      $this->add_control(
        'eael_ticker_border_radius',
        [
          'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
          'type' => Controls_Manager::SLIDER,
          'default' => [
            'size' => 0,
          ],
          'range' => [
            'px' => [
              'max' => 500,
            ],
          ],
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap' => 'border-radius: {{SIZE}}px;',
          ],
        ]
      );

      $this->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        [
          'name' => 'eael_ticker_shadow',
          'selector' => '{{WRAPPER}} .eael-ticker-wrap',
        ]
      );

      $this->end_controls_section();

      /**
       * -------------------------------------------
       * Tab Style (Ticker Content Style)
       * -------------------------------------------
       */
      $this->start_controls_section(
        'eael_section_ticker_typography_settings',
        [
          'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
          'tab' => Controls_Manager::TAB_STYLE
        ]
      );
      $this->add_control(
        'eael_ticker_content_text',
        [
          'label' => esc_html__( 'Ticker Content', 'essential-addons-elementor' ),
          'type' => Controls_Manager::HEADING,
          'separator' => 'before'
        ]
      );

      $this->add_control(
        'eael_ticker_content_color',
        [
          'label' => esc_html__( 'Title Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#222222',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content' => 'color: {{VALUE}};',
          ],
        ]
      );
      $this->add_control(
        'eael_ticker_hover_content_color',
        [
          'label' => esc_html__( 'Title Hover Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#f44336',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content:hover' => 'color: {{VALUE}};',
          ],
        ]
      );

      $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
          'name' => 'eael_ticker_content_typography',
          'selector' =>'{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content',

        ]
      );

      $this->add_control(
        'eael_ticker_nav_icon_style',
        [
          'label' => esc_html__( 'Navigation', 'essential-addons-elementor' ),
          'type' => Controls_Manager::HEADING,
          'separator' => 'before'
        ]
      );

      $this->add_control(
        'eael_ticker_nav_icon_color',
        [
          'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#222',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-prev' => 'color: {{VALUE}};',
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-next' => 'color: {{VALUE}};',
          ],
        ]
      );

      $this->add_control(
        'eael_ticker_nav_icon_hover_color',
        [
          'label' => esc_html__( 'Icon Hover Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#fff',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-prev:hover' => 'color: {{VALUE}};',
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-next:hover' => 'color: {{VALUE}};',
          ],
        ]
      );

      $this->add_control(
        'eael_ticker_nav_icon_bg_color',
        [
          'label' => esc_html__( 'Icon Background Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#fff',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-prev' => 'background-color: {{VALUE}};',
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-next' => 'background-color: {{VALUE}};',
          ],
        ]
      );
      $this->add_control(
        'eael_ticker_nav_icon_bg_color_hover',
        [
          'label' => esc_html__( 'Icon Background Hover Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#222',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-prev:hover' => 'background-color: {{VALUE}};',
            '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-next:hover' => 'background-color: {{VALUE}};',
          ],
        ]
      );

      $this->add_group_control(
        Group_Control_Border::get_type(),
        [
          'name' => 'eael_ticker_nav_icon_border',
          'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
          'selector' => '{{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-prev, {{WRAPPER}} .eael-ticker-wrap .slick-slider .eael-slick-next',
        ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
        'eael_section_ticker_tag_style_settings',
        [
          'label' => esc_html__( 'Tag Style', 'essential-addons-elementor' ),
          'tab' => Controls_Manager::TAB_STYLE
        ]
      );
      $this->add_control(
        'eael_ticker_tag_bg_color',
        [
          'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#222222',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'background-color: {{VALUE}};',
          ],
        ]
      );
      $this->add_control(
        'eael_ticker_tag_color',
        [
          'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
          'type' => Controls_Manager::COLOR,
          'default' => '#fff',
          'selectors' => [
            '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'color: {{VALUE}};',
          ],
        ]
      );

      $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
          'name' => 'eael_ticker_tag_typography',
          'selector' => '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span',
        ]
      );
      $this->add_responsive_control(
        'eael_ticker_tag_padding',
        [
          'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', 'em', '%' ],
          'selectors' => [
              '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );
      $this->add_responsive_control(
        'eael_ticker_tag_radius',
        [
          'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
          'type' => Controls_Manager::DIMENSIONS,
          'size_units' => [ 'px', 'em', '%' ],
          'selectors' => [
              '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );
      $this->end_controls_section();
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


  protected function render( ) {

      $settings = $this->get_settings();
      $post_args = eael_get_post_settings($settings);
      $posts = eael_get_post_data($post_args);

      ?>
        <?php if(count($posts)) : global $post; ?>
        <div class="eael-ticker-wrap" id="eael-ticker-wrap-<?php echo $this->get_id(); ?>">
          <?php if( !empty($settings['eael_ticker_tag_text']) ) : ?>
          <div class="ticker-badge">
            <span><?php echo $settings['eael_ticker_tag_text']; ?></span>
          </div>
          <?php endif; ?>
          <div class="eael-ticker">
            <?php foreach( $posts as $post ) : setup_postdata( $post );
              echo '<div><a href="'.get_the_permalink().'" class="ticker-content">'.get_the_title().'</a></div>';
            endforeach; ?>
          </div>
        </div>
          <?php endif; ?>

    <script>
      jQuery(document).ready(function($) {

        $('#eael-ticker-wrap-<?php echo $this->get_id(); ?> .eael-ticker').slick({
          autoplay: <?php if( !empty($settings['eael_ticker_autoplay']) ) : echo $settings['eael_ticker_autoplay']; else: echo 'false'; endif; ?>,
          autoplaySpeed: <?php echo $settings['eael_ticker_autoplay_speed']; ?>,
          arrows: <?php if( !empty($settings['eael_ticker_arrow']) ) : echo $settings['eael_ticker_arrow']; else: echo 'false'; endif; ?>,
          cssEase: 'ease',
          fade: <?php if( !empty($settings['eael_ticker_fade']) ) : echo $settings['eael_ticker_fade']; else: echo 'false'; endif; ?>,
          easing: '<?php echo $settings['eael_ticker_easing']; ?>',
          pauseOnHover: <?php if( !empty($settings['eael_ticker_pause_on_hover']) ) : echo $settings['eael_ticker_pause_on_hover']; else: echo 'false'; endif; ?>,
          prevArrow: '<button type="button" class="eael-slick-prev"><i class="<?php echo $settings['eael_ticker_prev_icon']; ?>"></i></button>',
          nextArrow: '<button type="button" class="eael-slick-next"><i class="<?php echo $settings['eael_ticker_next_icon']; ?>"></i></button>',
          speed: <?php echo $settings['eael_ticker_slide_speed']; ?>,
          useCSS: true
        });

      });
    </script>
      <?php

    }

  protected function content_template() {

    ?>

    <?php
  }
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Content_Ticker() );
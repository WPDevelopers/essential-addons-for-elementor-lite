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
		return 'eicon-elementor-circle';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

   public function get_keywords() {
		return [ 'breadcrumb', 'shop', 'page', 'post' ];
	}

   protected function register_controls() {
      //General Section
      $this->eael_breadcrumb_general();
      //Style Section
      $this->eael_breadcrumb_style();
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
			'breadcrumb_style_preset',
			[
				'label'   => esc_html__( 'Border Style', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => esc_html__( 'Style 1', 'essential-addons-for-elementor-lite' ),
					'style2' => esc_html__( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'style3' => esc_html__( 'Style 3', 'essential-addons-for-elementor-lite' ),
					'style4' => esc_html__( 'Style 4', 'essential-addons-for-elementor-lite' ),
					'style5' => esc_html__( 'Style 5', 'essential-addons-for-elementor-lite' ),
				],
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
				'default' => 'icon',
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

      $this->add_control(
			'breadcrumb_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
			]
		);
      
      $this->end_controls_section();
   }

   protected function breadcrumb_home_label() {
      $settings = $this->get_settings_for_display();
      if ( ! empty( $settings['breadcrumb_home_text'] ) ) {
         return Helper::eael_wp_kses( $settings['breadcrumb_home_text'] );
      }
      return esc_html__( 'Home', 'essential-addons-for-elementor-lite' );
   }

   protected function breadcrumb_separator() {
      $settings = $this->get_settings_for_display();
      if ( ! empty( $settings['eael_separator_icon'] ) ) {
         ob_start();
         \Elementor\Icons_Manager::render_icon($settings['eael_separator_icon'], ['aria-hidden' => 'true']);
         $separator_icon = ob_get_clean();
         return sprintf( '<span class="eael-breadcrumb-separator">%s</span>', $separator_icon );
      } else {
         return Helper::eael_wp_kses( $settings['eael_separator_type_text'] );
      }
   }

   protected function render() {
      $settings = $this->get_settings_for_display();
      $prefix_type = $settings['eael_breadcrumb_prefix_type'];

      $args = array(
         'delimiter'   => $this->breadcrumb_separator(),
         'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
         'wrap_after'  => '</nav>',
         'before'      => '',
         'after'       => '',
         'home'        => $this->breadcrumb_home_label(),
      ); 

      ?>
      <div class="eael-breadcrumbs">
         <?php if ( 'yes' == $settings['breadcrumb_prefix_switch'] ) {
            ?>
            <div class="eael-breadcrumbs__prefix">
               <?php 
                  switch ( $prefix_type ) {
                     case 'icon':
                        \Elementor\Icons_Manager::render_icon( $settings['eael_breadcrumb_prefix_icon'], [ 'aria-hidden' => 'true' ] );
                        break;
                     case 'text':
                        echo Helper::eael_wp_kses( $settings['eael_breadcrumb_prefix_text'] );
                        break;
                  }
               ?>
            </div>
            <?php
         }
         ?>
         <?php woocommerce_breadcrumb( $args ); ?>
      </div>
      <?php
   }

}
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

   protected function render() {
      ?>
      <div class="breadcrumb-wrap">
      <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#">Library</a></li>
            <li class="active">Data</li>
      </ol>
      </div>
      <?php
   }

}
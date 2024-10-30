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
			'breadcrumb_home',
			[
				'label'       => esc_html__( 'Label For Home', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Type your title here', 'essential-addons-for-elementor-lite' ),
            'ai'  => [
					'active' => false,
				],
			]
		);

      $this->add_control(
			'breadcrumb_prefix_switch',
			[
				'label'        => esc_html__( 'Show Prefix', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

      $this->start_controls_tabs(
			'breadcrumb_prefix_tabs',
         [
            'condition' => [
               'breadcrumb_prefix_switch' => 'yes',
            ],
         ]
		);

		$this->start_controls_tab(
			'breadcrumb_prefix_iocn_tab',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
			]
		);

      $this->add_control(
			'breadcrumb_prefix_iocn',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);

      $this->end_controls_tab();

      $this->start_controls_tab(
			'breadcrumb_prefix_text_tab',
			[
				'label' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
			]
		);

      $this->add_control(
			'breadcrumb_prefix_text',
			[
				'label'       => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Type your title here', 'essential-addons-for-elementor-lite' ),
            'ai'  => [
					'active' => false,
				],
			]
		);
      $this->end_controls_tab();
      $this->end_controls_tabs();

      //
      $this->add_control(
			'more_options',
			[
				'label'     => esc_html__( 'Separator Type', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

      $this->start_controls_tabs(
			'breadcrumb_separator_tab',
		);

		$this->start_controls_tab(
			'breadcrumb_separator_iocn_tab',
			[
				'label' => esc_html__( 'Separator Icon', 'essential-addons-for-elementor-lite' ),
			]
		);

      $this->add_control(
			'breadcrumb_separator_icon',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);

      $this->end_controls_tab();

      $this->start_controls_tab(
			'breadcrumb_separator_text_tab',
			[
				'label' => esc_html__( 'Separator Text', 'essential-addons-for-elementor-lite' ),
			]
		);

      $this->add_control(
			'breadcrumb_separator_text',
			[
				'label'       => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Type your title here', 'essential-addons-for-elementor-lite' ),
            'ai'  => [
					'active' => false,
				],
			]
		);
      $this->end_controls_tab();
      $this->end_controls_tabs();
      
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
      $args = array(
         'delimiter'   => '&nbsp;&#47;&nbsp;',
         'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
         'wrap_after'  => '</nav>',
         'before'      => '',
         'after'       => '',
         'home'        => _x( 'Home', 'breadcrumb', 'essential-addons-for-elementor-lite' ),
      ); 
      
      woocommerce_breadcrumb( $args );
   }

}
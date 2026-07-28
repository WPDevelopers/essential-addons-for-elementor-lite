<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Mega_Menu extends Widget_Base {

	public function get_name() {
		return 'eael-mega-menu';
	}

	public function get_title() {
		return esc_html__( 'Mega Menu', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-simple-menu';
	}

	/**
	 * Forcefully enqueue elementor icon library
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return [ 'elementor-icons' ];
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'mega menu',
			'ea mega menu',
			'megamenu',
			'nav menu',
			'ea nav menu',
			'navigation',
			'ea navigation',
			'header menu',
			'dropdown menu',
			'ea',
			'essential addons',
		];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! HelperClass::eael_e_optimized_markup();
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/mega-menu/';
	}

	protected function register_controls() {

		/**
		 * Content: Layout
		 *
		 * Placeholder section — controls land here in Step 2.
		 */
		$this->start_controls_section(
			'eael_mega_menu_section_layout',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$this->add_render_attribute(
			'eael-mega-menu-container',
			[
				'class' => 'eael-mega-menu-container',
			]
		);

		echo '<div ' . $this->get_render_attribute_string( 'eael-mega-menu-container' ) . '></div>';
	}
}

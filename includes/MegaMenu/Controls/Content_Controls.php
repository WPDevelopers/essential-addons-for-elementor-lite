<?php

namespace Essential_Addons_Elementor\MegaMenu\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Modules\NestedElements\Controls\Control_Nested_Repeater;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\MegaMenu\Manager;

/**
 * Content tab controls for the Mega Menu widget.
 *
 * @since 6.3.0
 */
class Content_Controls {

	/**
	 * Register every content tab section on the widget.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	public static function register( Widget_Base $widget ) {
		self::register_menu_items_section( $widget );
		self::register_settings_section( $widget );
		self::register_mobile_section( $widget );
	}

	/**
	 * Menu items repeater — each row owns one nested container.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_menu_items_section( Widget_Base $widget ) {
		$manager = Manager::instance();

		$widget->start_controls_section( 'eael_mega_menu_items_section', [
			'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'eael_mega_menu_item_label', [
			'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Menu Item', 'essential-addons-for-elementor-lite' ),
			'placeholder' => esc_html__( 'Menu Item', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_link', [
			'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::URL,
			'label_block' => true,
			'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_icon', [
			'label'       => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::ICONS,
			'skin'        => 'inline',
			'label_block' => false,
		] );

		$repeater->add_control( 'eael_mega_menu_item_has_submenu', [
			'label'        => esc_html__( 'Submenu', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
			'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
			'default'      => 'yes',
			'return_value' => 'yes',
			'description'  => esc_html__( 'Turn off to render this item as a plain link without a dropdown panel.', 'essential-addons-for-elementor-lite' ),
			'separator'    => 'before',
		] );

		$repeater->add_control( 'eael_mega_menu_item_submenu_width', [
			'label'     => esc_html__( 'Submenu Width', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'full',
			'options'   => $manager->get_submenu_width_options(),
			'condition' => [ 'eael_mega_menu_item_has_submenu' => 'yes' ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_submenu_custom_width', [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vw', 'em', 'rem' ],
			'range'      => [
				'px' => [ 'min' => 100, 'max' => 1600 ],
				'%'  => [ 'min' => 10, 'max' => 100 ],
				'vw' => [ 'min' => 10, 'max' => 100 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 480 ],
			'condition'  => [
				'eael_mega_menu_item_has_submenu'   => 'yes',
				'eael_mega_menu_item_submenu_width' => 'custom',
			],
		] );

		$repeater->add_control( 'eael_mega_menu_item_css_id', [
			'label'         => esc_html__( 'CSS ID', 'essential-addons-for-elementor-lite' ),
			'type'          => Controls_Manager::TEXT,
			'default'       => '',
			'ai'            => [ 'active' => false ],
			'dynamic'       => [ 'active' => true ],
			'title'         => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'essential-addons-for-elementor-lite' ),
			'style_transfer' => false,
			'classes'       => 'elementor-control-direction-ltr',
			'separator'     => 'before',
		] );

		$repeater->add_control( 'eael_mega_menu_item_css_classes', [
			'label'         => esc_html__( 'CSS Classes', 'essential-addons-for-elementor-lite' ),
			'type'          => Controls_Manager::TEXT,
			'default'       => '',
			'ai'            => [ 'active' => false ],
			'dynamic'       => [ 'active' => true ],
			'title'         => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'essential-addons-for-elementor-lite' ),
			'style_transfer' => false,
			'classes'       => 'elementor-control-direction-ltr',
		] );

		$widget->add_control( 'eael_mega_menu_items', [
			'label'       => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
			'type'        => Control_Nested_Repeater::CONTROL_TYPE,
			'fields'      => $repeater->get_controls(),
			'default'     => $manager->get_default_menu_items(),
			'title_field' => '{{{ eael_mega_menu_item_label }}}',
			'button_text' => esc_html__( 'Add Menu Item', 'essential-addons-for-elementor-lite' ),
			// The frontend handler resolves per item submenu width / state from these rows.
			'frontend_available' => true,
		] );

		$widget->end_controls_section();
	}

	/**
	 * Behaviour controls — trigger, animation, alignment, indicator.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_settings_section( Widget_Base $widget ) {
		$manager = Manager::instance();

		$widget->start_controls_section( 'eael_mega_menu_settings_section', [
			'label' => esc_html__( 'Settings', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_trigger', [
			'label'              => esc_html__( 'Open Submenu On', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SELECT,
			'default'            => 'hover',
			'options'            => $manager->get_trigger_options(),
			'description'        => esc_html__( 'Touch devices always use tap, regardless of this setting.', 'essential-addons-for-elementor-lite' ),
			'frontend_available' => true,
		] );

		$widget->add_control( 'eael_mega_menu_hover_delay', [
			'label'              => esc_html__( 'Close Delay', 'essential-addons-for-elementor-lite' ) . ' (ms)',
			'type'               => Controls_Manager::SLIDER,
			'range'              => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 10 ] ],
			'default'            => [ 'size' => 150 ],
			'frontend_available' => true,
			'condition'          => [ 'eael_mega_menu_trigger' => 'hover' ],
		] );

		$widget->add_control( 'eael_mega_menu_close_on_outside_click', [
			'label'              => esc_html__( 'Close On Outside Click', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'default'            => 'yes',
			'return_value'       => 'yes',
			'frontend_available' => true,
		] );

		$widget->add_control( 'eael_mega_menu_animation', [
			'label'     => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'fade',
			'options'   => $manager->get_animation_options(),
			'separator' => 'before',
		] );

		$widget->add_control( 'eael_mega_menu_animation_duration', [
			'label'      => esc_html__( 'Animation Duration', 'essential-addons-for-elementor-lite' ) . ' (ms)',
			'type'       => Controls_Manager::SLIDER,
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 2000, 'step' => 50 ] ],
			'default'    => [ 'size' => 300 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-animation-duration: {{SIZE}}ms;',
			],
			'condition'  => [ 'eael_mega_menu_animation!' => 'none' ],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_align', [
			'label'                => esc_html__( 'Align Menu', 'essential-addons-for-elementor-lite' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => [
				'flex-start'    => [
					'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-start-h',
				],
				'center'        => [
					'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-center-h',
				],
				'flex-end'      => [
					'title' => esc_html__( 'End', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-end-h',
				],
				'space-between' => [
					'title' => esc_html__( 'Stretch', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-stretch-h',
				],
			],
			'default'              => 'flex-start',
			'selectors'            => [
				'{{WRAPPER}}' => '--eael-mm-bar-justify: {{VALUE}};',
			],
			'separator'            => 'before',
		] );

		$widget->add_control( 'eael_mega_menu_indicator_icon', [
			'label'     => esc_html__( 'Submenu Indicator', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::ICONS,
			'skin'      => 'inline',
			'default'   => [
				'value'   => 'fas fa-chevron-down',
				'library' => 'fa-solid',
			],
			'separator' => 'before',
		] );

		$widget->end_controls_section();
	}

	/**
	 * Responsive / mobile controls.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_mobile_section( Widget_Base $widget ) {
		$manager = Manager::instance();

		$widget->start_controls_section( 'eael_mega_menu_mobile_section', [
			'label' => esc_html__( 'Responsive', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_breakpoint', [
			'label'              => esc_html__( 'Breakpoint', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SELECT,
			'default'            => 'tablet',
			'options'            => $manager->get_breakpoint_options(),
			'description'        => esc_html__( 'Below this breakpoint the menu collapses behind a toggle button and submenus stack as an accordion.', 'essential-addons-for-elementor-lite' ),
			'frontend_available' => true,
		] );

		$widget->add_control( 'eael_mega_menu_toggle_text', [
			'label'       => esc_html__( 'Toggle Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Menu', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->add_control( 'eael_mega_menu_toggle_icon', [
			'label'     => esc_html__( 'Toggle Icon', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::ICONS,
			'skin'      => 'inline',
			'default'   => [
				'value'   => 'fas fa-bars',
				'library' => 'fa-solid',
			],
			'condition' => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->add_control( 'eael_mega_menu_toggle_close_icon', [
			'label'     => esc_html__( 'Close Icon', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::ICONS,
			'skin'      => 'inline',
			'default'   => [
				'value'   => 'fas fa-times',
				'library' => 'fa-solid',
			],
			'condition' => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->add_control( 'eael_mega_menu_toggle_full_width', [
			'label'        => esc_html__( 'Overlay Dropdown', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'description'  => esc_html__( 'Float the collapsed menu over the page instead of pushing the content below it down.', 'essential-addons-for-elementor-lite' ),
			'condition'    => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->end_controls_section();
	}
}

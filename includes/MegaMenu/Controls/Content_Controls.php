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
use Essential_Addons_Elementor\MegaMenu\Presets\Preset_Library;

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
		self::register_preset_section( $widget );
		self::register_menu_items_section( $widget );
		self::register_settings_section( $widget );
		self::register_mobile_section( $widget );
	}

	/**
	 * Ready-made designs, applied from the panel.
	 *
	 * First in the tab because it is the first decision: a preset rewrites the
	 * repeater below it, the whole Style tab, the contents of every panel and the
	 * header block around them, so offering it after the user has filled those in
	 * is offering to throw their work away.
	 *
	 * The control does not *do* anything by itself. Its value is a record of the
	 * last preset applied — which keeps the right tile lit when the panel is
	 * reopened — and the editor script watches it for changes and performs the
	 * apply. Hence `render_type: none`: the widget is about to be rebuilt by that
	 * script, and re-rendering it for the control's own sake would be a second,
	 * pointless teardown of every nested container.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_preset_section( Widget_Base $widget ) {
		$manager = Manager::instance();
		$options = $manager->get_preset_options();

		// One option left means every preset was filtered out — a widget it needs
		// is switched off, or a filter emptied the list. A "Preset" section whose
		// only choice is "Custom" is a control that cannot do anything.
		if ( count( $options ) < 2 ) {
			return;
		}

		$widget->start_controls_section( 'eael_mega_menu_preset_section', [
			'label' => esc_html__( 'Mega Menu Preset', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_preset', [
			'label'        => esc_html__( 'Preset', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::CHOOSE,
			'image_choose' => true,
			'label_block'  => true,
			// Never toggle off: an empty value is not a state this control has —
			// "no preset" is what the Custom tile means.
			'toggle'       => false,
			'default'      => Preset_Library::CUSTOM,
			'options'      => $options,
			'css_class'    => 'eael-mega-menu-preset-choices',
			'description'  => esc_html__( 'A preset is a whole header — logo, menu and buttons. Choosing one replaces the block this menu sits in. Undo restores what was there.', 'essential-addons-for-elementor-lite' ),
			'render_type'  => 'none',
		] );

		$widget->end_controls_section();
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

		$repeater->add_control( 'eael_mega_menu_item_type', [
			'label'     => esc_html__( 'Menu Item Type', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'mega',
			'options'   => $manager->get_item_type_options(),
			'separator' => 'before',
		] );

		$repeater->add_control( 'eael_mega_menu_item_template', [
			'label'       => esc_html__( 'Choose Template', 'essential-addons-for-elementor-lite' ),
			'type'        => 'eael-select2',
			'source_name' => 'post_type',
			'source_type' => 'elementor_library',
			'label_block' => true,
			'description' => esc_html__( 'The selected template is rendered on the front end. This item\'s nested container is ignored.', 'essential-addons-for-elementor-lite' ),
			'condition'   => [ 'eael_mega_menu_item_type' => 'template' ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_section_id', [
			'label'          => esc_html__( 'Section ID', 'essential-addons-for-elementor-lite' ),
			'type'           => Controls_Manager::TEXT,
			'default'        => '',
			'ai'             => [ 'active' => false ],
			'label_block'    => true,
			'placeholder'    => esc_html__( 'e.g: one', 'essential-addons-for-elementor-lite' ),
			'title'          => esc_html__( 'Add the CSS ID of the section WITHOUT the Pound key. e.g: one', 'essential-addons-for-elementor-lite' ),
			'description'    => esc_html__( 'Give a container or section on this page the same CSS ID. It is moved into this menu item on the front end. Preview it on the front end — the editor leaves it in place.', 'essential-addons-for-elementor-lite' ),
			'style_transfer' => false,
			'classes'        => 'elementor-control-direction-ltr',
			'condition'      => [ 'eael_mega_menu_item_type' => 'section' ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_submenu_width', [
			'label'     => esc_html__( 'Submenu Width', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'full',
			'options'   => $manager->get_submenu_width_options(),
			'condition' => [ 'eael_mega_menu_item_type' => [ 'mega', 'template', 'section' ] ],
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
				'eael_mega_menu_item_type'          => [ 'mega', 'template', 'section' ],
				'eael_mega_menu_item_submenu_width' => 'custom',
			],
		] );

		$repeater->add_control( 'eael_mega_menu_item_panel_align', [
			'label'       => esc_html__( 'Panel Alignment', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'start'  => [
					'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-start-h',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-center-h',
				],
				'end'    => [
					'title' => esc_html__( 'End', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-end-h',
				],
			],
			'default'     => 'start',
			'description' => esc_html__( 'Where the panel sits relative to this menu item. Full width panels ignore this.', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'eael_mega_menu_item_type'          => [ 'mega', 'template', 'section' ],
				'eael_mega_menu_item_submenu_width' => [ 'item', 'custom' ],
			],
		] );

		$repeater->add_control( 'eael_mega_menu_item_panel_offset_x', [
			'label'      => esc_html__( 'Horizontal Offset', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => -500, 'max' => 500 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 0 ],
			'condition'  => [ 'eael_mega_menu_item_type' => [ 'mega', 'template', 'section' ] ],
		] );

		$repeater->add_control( 'eael_mega_menu_item_panel_offset_y', [
			'label'      => esc_html__( 'Vertical Offset', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => -200, 'max' => 200 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 0 ],
			'condition'  => [ 'eael_mega_menu_item_type' => [ 'mega', 'template', 'section' ] ],
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
			// Escaped: the row title is compiled with the same Underscore settings
			// as the preview template and written with .html(), so the triple form
			// would run a label's markup inside the panel.
			'title_field' => '{{ eael_mega_menu_item_label }}',
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

		/*
		 * `render_type` on the behaviour controls below is a deliberate
		 * performance choice, not decoration.
		 *
		 * Elementor's default for a control with no `selectors` is a full
		 * `renderHTML()` of the widget (see `renderChanges()` in editor.js). For a
		 * nested widget that means tearing down and re-mounting every child
		 * container — every panel, and everything the user has built inside it —
		 * to change one class or a number the handler reads live. That is what
		 * made the Animation control feel slow.
		 *
		 * `ui` regenerates the element's CSS only (`renderStyles()` and friends),
		 * which keeps conditional selectors such as Animation Duration honest;
		 * `none` skips even that, for the two settings that touch neither the
		 * markup nor the stylesheet. The handler applies whatever visual
		 * difference is left — see `syncModifierClasses()`.
		 */
		$widget->add_control( 'eael_mega_menu_trigger', [
			'label'              => esc_html__( 'Open Submenu On', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SELECT,
			'default'            => 'hover',
			'options'            => $manager->get_trigger_options(),
			'description'        => esc_html__( 'Touch devices always use tap, regardless of this setting.', 'essential-addons-for-elementor-lite' ),
			'frontend_available' => true,
			'render_type'        => 'ui',
		] );

		$widget->add_control( 'eael_mega_menu_hover_delay', [
			'label'              => esc_html__( 'Close Delay', 'essential-addons-for-elementor-lite' ) . ' (ms)',
			'type'               => Controls_Manager::SLIDER,
			'range'              => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 10 ] ],
			'default'            => [ 'size' => 150 ],
			'frontend_available' => true,
			'condition'          => [ 'eael_mega_menu_trigger' => 'hover' ],
			// Read live by the handler on each close; nothing in the DOM or CSS.
			'render_type'        => 'none',
		] );

		$widget->add_control( 'eael_mega_menu_close_on_outside_click', [
			'label'              => esc_html__( 'Close On Outside Click', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'default'            => 'yes',
			'return_value'       => 'yes',
			'frontend_available' => true,
			// Read live by the document click handler; nothing in the DOM or CSS.
			'render_type'        => 'none',
		] );

		$widget->add_control( 'eael_mega_menu_animation', [
			'label'       => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'fade',
			'options'     => $manager->get_animation_options(),
			'separator'   => 'before',
			'render_type' => 'ui',
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

		$widget->add_responsive_control( 'eael_mega_menu_stretch', [
			'label'        => esc_html__( 'Stretch To Full Width', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'description'  => esc_html__( 'Fill the parent container. Turn off to let the menu bar shrink to the width of its items.', 'essential-addons-for-elementor-lite' ),
			'separator'    => 'before',
			// Widgets placed in a row-direction container are flex items and would
			// otherwise size to their content, leaving Align nothing to distribute.
			// Width (not flex-grow) is used so this never stretches the widget
			// vertically inside a column-direction container. Turning this off in a
			// "logo left, hamburger right" header is safe: the collapsed dropdown
			// is positioned against the viewport, not against the widget box.
			'selectors'    => [
				'{{WRAPPER}}' => 'width: 100%;',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_align', [
			'label'                => esc_html__( 'Align Menu', 'essential-addons-for-elementor-lite' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => [
				'flex-start' => [
					'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-start-h',
				],
				'center'     => [
					'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-center-h',
				],
				'flex-end'   => [
					'title' => esc_html__( 'End', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-end-h',
				],
				'stretch'    => [
					'title' => esc_html__( 'Stretch', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-stretch-h',
				],
			],
			'default'              => 'flex-start',
			'selectors_dictionary' => [
				'flex-start' => '--eael-mm-bar-justify: flex-start; --eael-mm-item-grow: 0;',
				'center'     => '--eael-mm-bar-justify: center; --eael-mm-item-grow: 0;',
				'flex-end'   => '--eael-mm-bar-justify: flex-end; --eael-mm-item-grow: 0;',
				// "Stretch" has to grow the items themselves — justify-content
				// alone only moves them around, it never fills the bar.
				'stretch'    => '--eael-mm-bar-justify: flex-start; --eael-mm-item-grow: 1;',
				// Alias for the value this option used before it grew the items.
				'space-between' => '--eael-mm-bar-justify: flex-start; --eael-mm-item-grow: 1;',
			],
			'selectors'            => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
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

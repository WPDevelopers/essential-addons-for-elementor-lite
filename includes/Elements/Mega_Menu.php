<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Mega_Menu extends Widget_Base {

	/**
	 * Saved-template ids currently mid-render, keyed by template id.
	 *
	 * Guards against a template that itself contains a Mega Menu pointing back at
	 * the same template — without this the render would recurse until it fataled.
	 *
	 * @var array<int, bool>
	 */
	protected static $rendering_templates = [];

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

	/**
	 * Devices a menu item can be hidden on.
	 *
	 * @return array<string, string> Device key => translated label.
	 */
	protected function get_device_options() {

		return [
			'desktop' => esc_html__( 'Desktop', 'essential-addons-for-elementor-lite' ),
			'tablet'  => esc_html__( 'Tablet', 'essential-addons-for-elementor-lite' ),
			'mobile'  => esc_html__( 'Mobile', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Build the device-visibility classes for one menu item.
	 *
	 * Two classes per device, on purpose:
	 *
	 *  - `elementor-hidden-{device}` does the actual hiding. Elementor generates
	 *    that rule from the site's own breakpoints, so a kit with custom
	 *    breakpoints keeps working — media queries hard-coded in our stylesheet
	 *    would not.
	 *  - `eael-mega-menu__item--hide-{device}` carries no CSS of its own. It is
	 *    the documented hook for styling and makes the markup readable.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return array List of class names, empty when the item is visible everywhere.
	 */
	protected function get_item_device_classes( $item ) {

		if ( empty( $item['hide_on'] ) || ! is_array( $item['hide_on'] ) ) {
			return [];
		}

		$devices = array_keys( $this->get_device_options() );
		$classes = [];

		foreach ( $item['hide_on'] as $device ) {
			// Ignore anything that is not one of our own device keys — the value
			// reaches a class attribute.
			if ( ! is_string( $device ) || ! in_array( $device, $devices, true ) ) {
				continue;
			}

			$classes[] = 'eael-mega-menu__item--hide-' . $device;
			$classes[] = 'elementor-hidden-' . $device;
		}

		return $classes;
	}

	protected function register_controls() {

		/**
		 * Content: Layout
		 */
		$this->start_controls_section(
			'eael_mega_menu_section_layout',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_mega_menu_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
					'vertical'   => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_preset',
			[
				'label'   => esc_html__( 'Preset', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'preset-1',
				'options' => [
					'preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
					'preset-3' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_align',
			[
				'label'                => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'left',
				'options'              => [
					'left'    => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'selectors_dictionary' => [
					'left'    => '--eael-mm-justify: flex-start; --eael-mm-item-grow: 0;',
					'center'  => '--eael-mm-justify: center; --eael-mm-item-grow: 0;',
					'right'   => '--eael-mm-justify: flex-end; --eael-mm-item-grow: 0;',
					'stretch' => '--eael-mm-justify: flex-start; --eael-mm-item-grow: 1;',
				],
				'selectors'            => [
					'{{WRAPPER}} .eael-mega-menu-container' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_trigger',
			[
				'label'       => esc_html__( 'Open Panel On', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'hover',
				'options'     => [
					'hover' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
					'click' => esc_html__( 'Click', 'essential-addons-for-elementor-lite' ),
				],
				'description' => esc_html__( 'Applies to desktop only. On mobile the menu always opens on tap.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_indicator_icon',
			[
				'label'       => esc_html__( 'Dropdown Indicator', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'description' => esc_html__( 'Shown only on menu items that have a dropdown panel.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_animation',
			[
				'label'   => esc_html__( 'Panel Animation', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none'       => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
					'fade'       => esc_html__( 'Fade', 'essential-addons-for-elementor-lite' ),
					'slide-down' => esc_html__( 'Slide Down', 'essential-addons-for-elementor-lite' ),
					'slide-up'   => esc_html__( 'Slide Up', 'essential-addons-for-elementor-lite' ),
					'zoom'       => esc_html__( 'Zoom', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_animation_duration',
			[
				'label'      => esc_html__( 'Animation Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 300,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 50,
					],
				],
				'condition'  => [
					'eael_mega_menu_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_enable_overlay',
			[
				'label'        => esc_html__( 'Page Overlay', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Dim the page behind the menu while a dropdown panel is open.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Content: Menu Items
		 */
		$this->start_controls_section(
			'eael_mega_menu_section_items',
			[
				'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			[
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Menu Item', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'default'     => [
					'url' => '#',
				],
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_icon',
			[
				'label'       => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'description' => esc_html__( 'Shown before the label. Leave it empty and no icon markup is output at all.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'item_badge',
			[
				'label'       => esc_html__( 'Badge', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__( 'A short tag such as New or Sale, shown after the label. Leave it empty and no badge markup is output at all.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'item_badge_color',
			[
				'label'     => esc_html__( 'Badge Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__item-badge' => 'color: {{VALUE}};',
				],
				'condition' => [
					'item_badge!' => '',
				],
			]
		);

		$repeater->add_control(
			'item_badge_bg',
			[
				'label'     => esc_html__( 'Badge Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__item-badge' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'item_badge!' => '',
				],
			]
		);

		$repeater->add_control(
			'content_source',
			[
				'label'       => esc_html__( 'Dropdown Content', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Saved Template', 'essential-addons-for-elementor-lite' ),
					'section'  => esc_html__( 'Section ID', 'essential-addons-for-elementor-lite' ),
					'none'     => esc_html__( 'Link only', 'essential-addons-for-elementor-lite' ),
				],
				'description' => esc_html__( 'Saved Template — build the dropdown once in Templates → Saved Templates. Works on every page. Recommended. Section ID — design a Container in THIS page or template and enter its CSS ID here. Link only — no dropdown, plain menu link.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'content_template',
			[
				'label'       => esc_html__( 'Choose Template', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'post_type',
				'source_type' => 'elementor_library',
				'label_block' => true,
				'condition'   => [
					'content_source' => 'template',
				],
			]
		);

		$repeater->add_control(
			'section_css_id',
			[
				'label'       => esc_html__( 'Section CSS ID', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'description' => esc_html__( 'Enter the container\'s Advanced → CSS ID, without the #. It must exist in the same page or template as this menu. Editing a header template? Put the container inside that header template.', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'content_source' => 'section',
				],
			]
		);

		$repeater->add_control(
			'editor_preview_open',
			[
				'label'        => esc_html__( 'Preview Dropdown Here', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				// Off by default on purpose. The panel is an absolute overlay, so
				// an open preview sits on top of the page — including the very
				// section a Section ID dropdown points at. Opening it is the
				// user's call, one dropdown at a time.
				'default'      => '',
				'description'  => esc_html__( 'Switch on to open this dropdown inside the editor so you can style it. It overlays the page while open, so turn it off again when you need to reach what is underneath. Editor only — it never affects the live site.', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'content_source!' => 'none',
				],
			]
		);

		$repeater->add_control(
			'panel_width',
			[
				'label'     => esc_html__( 'Panel Width', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'container',
				'options'   => [
					'full'      => esc_html__( 'Full Width (viewport)', 'essential-addons-for-elementor-lite' ),
					'container' => esc_html__( 'Container Width', 'essential-addons-for-elementor-lite' ),
					'custom'    => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'content_source!' => 'none',
				],
			]
		);

		$repeater->add_responsive_control(
			'panel_custom_width',
			[
				'label'      => esc_html__( 'Custom Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1600,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 600,
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__panel' => 'width: {{SIZE}}{{UNIT}}; max-width: none;',
				],
				'condition'  => [
					'content_source!' => 'none',
					'panel_width'     => 'custom',
				],
			]
		);

		$repeater->add_control(
			'panel_position',
			[
				'label'     => esc_html__( 'Panel Position', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'   => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
					'center' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'right'  => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'content_source!' => 'none',
					'panel_width!'    => 'full',
				],
			]
		);

		$repeater->add_control(
			'panel_offset_y',
			[
				'label'      => esc_html__( 'Vertical Offset', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__panel' => '--eael-mm-offset-y: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'content_source!' => 'none',
				],
			]
		);

		$repeater->add_control(
			'hide_on',
			[
				'label'       => esc_html__( 'Hide On', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_device_options(),
				'description' => esc_html__( 'Devices this item is hidden on. It follows the same breakpoints as Elementor\'s own responsive settings.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_label }}}',
				'default'     => [
					[
						'item_label' => esc_html__( 'Home', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
					[
						'item_label' => esc_html__( 'About', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
					[
						'item_label' => esc_html__( 'Contact', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->register_mobile_controls();

		$this->register_container_style_controls();
		$this->register_item_style_controls();
		$this->register_panel_style_controls();
		$this->register_hamburger_style_controls();
		$this->register_mobile_menu_style_controls();
	}

	/**
	 * Widths the menu can collapse into a hamburger at.
	 *
	 * Mirrors Simple Menu's own dropdown options so the two widgets read the
	 * same in the panel. Laptop and widescreen are left out — collapsing a menu
	 * on a wide screen is not what this control is for.
	 *
	 * @return array<string, string> Breakpoint key => translated label.
	 */
	protected function get_breakpoint_options() {

		$options = [];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $breakpoint ) {
			if ( in_array( $key, [ 'laptop', 'widescreen' ], true ) ) {
				continue;
			}

			$options[ $key ] = sprintf(
				/* translators: 1: breakpoint label, 2: breakpoint width in pixels. */
				esc_html__( '%1$s (up to %2$dpx)', 'essential-addons-for-elementor-lite' ),
				$breakpoint->get_label(),
				$breakpoint->get_value()
			);
		}

		$options['desktop'] = esc_html__( 'Desktop and below', 'essential-addons-for-elementor-lite' );
		$options['none']    = esc_html__( 'Never collapse', 'essential-addons-for-elementor-lite' );

		return $options;
	}

	/**
	 * The width, in pixels, below which the hamburger takes over.
	 *
	 * @param string $breakpoint Breakpoint key from get_breakpoint_options().
	 *
	 * @return int Pixel width, or 0 when the menu should never collapse.
	 */
	protected function get_breakpoint_width( $breakpoint ) {

		if ( '' === $breakpoint || 'none' === $breakpoint ) {
			return 0;
		}

		// "Desktop" means everything below the widescreen breakpoint, since there
		// is no desktop breakpoint of its own to read.
		if ( 'desktop' === $breakpoint ) {
			$config = method_exists( Plugin::$instance->breakpoints, 'get_breakpoints_config' )
				? Plugin::$instance->breakpoints->get_breakpoints_config()
				: [];

			return isset( $config['widescreen']['value'] ) ? absint( $config['widescreen']['value'] ) - 1 : 2400;
		}

		$active = Plugin::$instance->breakpoints->get_active_breakpoints();

		if ( ! isset( $active[ $breakpoint ] ) ) {
			return 0;
		}

		return absint( $active[ $breakpoint ]->get_value() );
	}

	/**
	 * Content: Mobile & Hamburger
	 */
	protected function register_mobile_controls() {

		$this->start_controls_section(
			'eael_mega_menu_section_mobile',
			[
				'label' => esc_html__( 'Mobile & Hamburger', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_mega_menu_breakpoint',
			[
				'label'       => esc_html__( 'Collapse Into Hamburger', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'tablet',
				'options'     => $this->get_breakpoint_options(),
				'description' => esc_html__( 'Below this width the menu is replaced by a hamburger button.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_type',
			[
				'label'     => esc_html__( 'Mobile Menu Style', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dropdown',
				'options'   => [
					'dropdown' => esc_html__( 'Dropdown (pushes the page down)', 'essential-addons-for-elementor-lite' ),
					'offcanvas' => esc_html__( 'Off-Canvas (slides in from the side)', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_offcanvas_side',
			[
				'label'     => esc_html__( 'Slide In From', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'  => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
					'right' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_offcanvas_width',
			[
				'label'      => esc_html__( 'Drawer Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 600,
					],
					'%'  => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 320,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-drawer-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_mega_menu_breakpoint!' => 'none',
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_hamburger_icon',
			[
				'label'     => esc_html__( 'Hamburger Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_hamburger_close_icon',
			[
				'label'     => esc_html__( 'Close Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_hamburger_align',
			[
				'label'                => esc_html__( 'Hamburger Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'right',
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-toggle-justify: {{VALUE}};',
				],
				'condition'            => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_panel_behavior',
			[
				'label'       => esc_html__( 'Dropdowns On Mobile', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'accordion',
				'options'     => [
					'accordion' => esc_html__( 'Accordion (open inside the menu)', 'essential-addons-for-elementor-lite' ),
					'hide'      => esc_html__( 'Hide (menu links only)', 'essential-addons-for-elementor-lite' ),
				],
				'description' => esc_html__( 'Hide keeps linked-section dropdowns out of the page on small screens, which is lighter. Those items become plain links. Saved Template dropdowns are rendered by the server and are unaffected.', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_body_scroll_lock',
			[
				'label'        => esc_html__( 'Lock Page Scroll', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Stops the page behind the drawer from scrolling while it is open. The reading position is restored on close.', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'eael_mega_menu_breakpoint!' => 'none',
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_close_on_click',
			[
				'label'        => esc_html__( 'Close After Tapping a Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Only applies to links that navigate. Tapping an item that has a dropdown always opens that dropdown instead.', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Dropdown Panel
	 */
	protected function register_panel_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_panel',
			[
				'label' => esc_html__( 'Dropdown Panel', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_border',
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_shadow',
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_max_height',
			[
				'label'       => esc_html__( 'Max Height', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'vh' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 1200,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'description' => esc_html__( 'Taller content scrolls inside the panel.', 'essential-addons-for-elementor-lite' ),
				'selectors'   => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_panel_z_index',
			[
				'label'     => esc_html__( 'Z-Index', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => 999,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Hamburger Button
	 */
	protected function register_hamburger_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_hamburger',
			[
				'label'     => esc_html__( 'Hamburger Button', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_toggle_size',
			[
				'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 80,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__toggle'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-mega-menu__toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_toggle_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_mega_menu_toggle_tabs' );

		$this->start_controls_tab(
			'eael_mega_menu_toggle_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_toggle_state_controls( 'normal', '{{WRAPPER}} .eael-mega-menu__toggle' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_mega_menu_toggle_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_toggle_state_controls(
			'hover',
			'{{WRAPPER}} .eael-mega-menu__toggle:hover, {{WRAPPER}} .eael-mega-menu__toggle:focus'
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_mega_menu_toggle_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_toggle_border',
				'selector' => '{{WRAPPER}} .eael-mega-menu__toggle',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Colour and background for one hamburger button state.
	 *
	 * @param string $state    Control id suffix — normal or hover.
	 * @param string $selector CSS selector the state applies to.
	 */
	protected function add_toggle_state_controls( $state, $selector ) {

		$this->add_control(
			'eael_mega_menu_toggle_color_' . $state,
			[
				'label'     => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector          => 'color: {{VALUE}};',
					$selector . ' svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_toggle_bg_' . $state,
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'background-color: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Style: Mobile Menu — how the menu looks once it has collapsed.
	 */
	protected function register_mobile_menu_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_mobile_panel',
			[
				'label'     => esc_html__( 'Mobile Menu', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_mega_menu_breakpoint!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_notice',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'These only take effect below the width set in Content → Mobile & Hamburger. Switch the editor to Tablet or Mobile to see them.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_mega_menu_mobile_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-mega-menu__nav',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_mega_menu_mobile_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .eael-mega-menu--is-mobile .eael-mega-menu__item-link',
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_item_color',
			[
				'label'     => esc_html__( 'Item Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu--is-mobile .eael-mega-menu__item-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_item_bg',
			[
				'label'     => esc_html__( 'Item Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu--is-mobile .eael-mega-menu__item-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_mobile_item_padding',
			[
				'label'      => esc_html__( 'Item Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu--is-mobile .eael-mega-menu__item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_drawer_heading',
			[
				'label'     => esc_html__( 'Drawer Close Button', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_drawer_header_padding',
			[
				'label'      => esc_html__( 'Header Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__drawer-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_drawer_close_size',
			[
				'label'      => esc_html__( 'Close Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 60,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__close'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-mega-menu__close svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_drawer_close_color',
			[
				'label'     => esc_html__( 'Close Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu__close'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-mega-menu__close svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_overlay_heading',
			[
				'label'     => esc_html__( 'Drawer Overlay', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.5)',
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-overlay-color: {{VALUE}};',
				],
				'condition' => [
					'eael_mega_menu_mobile_type' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_divider_heading',
			[
				'label'     => esc_html__( 'Divider', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_divider_width',
			[
				'label'      => esc_html__( 'Thickness', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-mobile-divider-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_mobile_divider_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e8eb',
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-mobile-divider-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve a repeater row's saved template to a renderable template id.
	 *
	 * Mirrors the validation chain used by Adv_Tabs — self-recursion, nested
	 * recursion, unpublished templates and the WPML translated id are all
	 * rejected before anything is echoed.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return int Template id safe to render, or 0 when there is nothing to render.
	 */
	protected function get_renderable_template_id( $item ) {

		if ( empty( $item['content_template'] ) || is_array( $item['content_template'] ) ) {
			return 0;
		}

		$template_id = absint( $item['content_template'] );

		if ( ! $template_id ) {
			return 0;
		}

		// Self-recursion — the template is the page being rendered, or one of its revisions.
		$current_page_id = get_the_ID();

		if ( $template_id === absint( $current_page_id ) ) {
			return 0;
		}

		$revision_ids = wp_list_pluck( wp_get_post_revisions( $current_page_id ), 'ID' );

		if ( in_array( $template_id, array_map( 'absint', $revision_ids ), true ) ) {
			return 0;
		}

		// Nested recursion — this template is already being rendered further up the stack.
		if ( isset( self::$rendering_templates[ $template_id ] ) ) {
			return 0;
		}

		if ( ! HelperClass::is_elementor_publish_template( $template_id ) ) {
			return 0;
		}

		// WPML compatibility — swap in the translated template for the active language.
		$template_id = absint( apply_filters( 'wpml_object_id', $template_id, 'elementor_library', true ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		// Re-validate: the translated post may not be a published elementor_library entry.
		if ( ! HelperClass::is_elementor_publish_template( $template_id ) ) {
			return 0;
		}

		return $template_id;
	}

	/**
	 * Echo a saved template's builder output inside a dropdown panel.
	 *
	 * @param int $template_id Already validated by get_renderable_template_id().
	 */
	protected function render_template_panel_content( $template_id ) {

		self::$rendering_templates[ $template_id ] = true;

		HelperClass::eael_onpage_edit_template_markup( get_the_ID(), $template_id );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo Plugin::$instance->frontend->get_builder_content( $template_id, true );

		unset( self::$rendering_templates[ $template_id ] );
	}

	/**
	 * Read a repeater row's linked-section CSS ID, sanitized for safe use.
	 *
	 * The value reaches both a CSS selector and getElementById(), and it can
	 * come from a dynamic tag, so it is reduced to [A-Za-z0-9_-] before use.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return string Safe CSS ID, or an empty string when there is nothing usable.
	 */
	protected function get_linked_section_id( $item ) {

		if ( empty( $item['section_css_id'] ) || ! is_string( $item['section_css_id'] ) ) {
			return '';
		}

		// Users routinely paste the selector rather than the bare ID.
		$section_id = ltrim( trim( $item['section_css_id'] ), '#' );

		return sanitize_html_class( $section_id );
	}

	/**
	 * Collect every linked-section CSS ID used by this menu.
	 *
	 * @param array $items Repeater rows.
	 *
	 * @return array Unique list of sanitized CSS IDs.
	 */
	protected function collect_linked_section_ids( $items ) {

		$section_ids = [];

		foreach ( $items as $item ) {
			if ( empty( $item['content_source'] ) || 'section' !== $item['content_source'] ) {
				continue;
			}

			$section_id = $this->get_linked_section_id( $item );

			if ( '' !== $section_id ) {
				$section_ids[] = $section_id;
			}
		}

		return array_unique( $section_ids );
	}

	/**
	 * Print the stylesheet that hides linked sections until the JS has moved
	 * them into their panels. Without it the sections would flash in their
	 * original position on first paint.
	 *
	 * mega-menu.js removes this tag as soon as the move is done — otherwise the
	 * section would stay hidden inside the panel too.
	 *
	 * @param array $section_ids Sanitized CSS IDs.
	 */
	protected function print_linked_section_hide_style( $section_ids ) {

		if ( empty( $section_ids ) ) {
			return;
		}

		$selectors = [];

		foreach ( $section_ids as $section_id ) {
			// An attribute selector rather than `#id`, because a CSS ID that
			// starts with a digit is valid HTML but an invalid CSS selector —
			// and one bad selector would void the whole rule.
			$selectors[] = '[id="' . $section_id . '"]';
		}

		printf(
			'<style id="eael-mm-hide-%s">%s{display:none!important}</style>',
			esc_attr( $this->get_id() ),
			// Safe: every ID passed through sanitize_html_class(), so only
			// [A-Za-z0-9_-] survives. esc_html() is wrong here — <style> is a
			// raw-text element, so entities would be taken literally by the
			// CSS parser and break the rule.
			implode( ',', $selectors ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Style: Container
	 */
	protected function register_container_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_container',
			[
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_mega_menu_container_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_container_border',
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_container_shadow',
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_min_height',
			[
				'label'      => esc_html__( 'Min Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_gap',
			[
				'label'      => esc_html__( 'Item Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_container_z_index',
			[
				'label'     => esc_html__( 'Z-Index', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Menu Items
	 */
	protected function register_item_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_items',
			[
				'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_mega_menu_item_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .eael-mega-menu__item-link',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => 10,
					'right'    => 15,
					'bottom'   => 10,
					'left'     => 15,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_mega_menu_item_tabs' );

		/**
		 * Normal
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls( 'normal', '{{WRAPPER}} .eael-mega-menu__item-link' );

		$this->end_controls_tab();

		/**
		 * Hover
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls(
			'hover',
			'{{WRAPPER}} .eael-mega-menu__item-link:hover, {{WRAPPER}} .eael-mega-menu__item-link:focus'
		);

		$this->end_controls_tab();

		/**
		 * Active — current page item, and (from Step 5) the item whose panel is open.
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_active',
			[
				'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls(
			'active',
			'{{WRAPPER}} .eael-mega-menu__item--active > .eael-mega-menu__item-link, {{WRAPPER}} .eael-mega-menu__item.is-active > .eael-mega-menu__item-link'
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->register_item_icon_style_controls();
		$this->register_item_indicator_style_controls();
		$this->register_item_badge_style_controls();

		$this->end_controls_section();
	}

	/**
	 * Style: the per-item icon that sits before the label.
	 *
	 * Lives inside the Menu Items section — see register_item_style_controls().
	 */
	protected function register_item_icon_style_controls() {

		$this->add_control(
			'eael_mega_menu_icon_heading',
			[
				'label'     => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_icon_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 80,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__item-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					// An inline SVG ignores font-size, so it is sized directly.
					'{{WRAPPER}} .eael-mega-menu__item-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_icon_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu__item-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-mega-menu__item-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_icon_gap',
			[
				'label'      => esc_html__( 'Gap After Icon', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-icon-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Style: the dropdown indicator, including how far it turns when a panel opens.
	 *
	 * Lives inside the Menu Items section — see register_item_style_controls().
	 */
	protected function register_item_indicator_style_controls() {

		$this->add_control(
			'eael_mega_menu_indicator_heading',
			[
				'label'     => esc_html__( 'Dropdown Indicator', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_indicator_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__indicator'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-mega-menu__indicator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_indicator_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu__indicator'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-mega-menu__indicator svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_indicator_gap',
			[
				'label'      => esc_html__( 'Gap Before Indicator', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-indicator-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_indicator_rotation',
			[
				'label'       => esc_html__( 'Rotation When Open', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'deg' ],
				'default'     => [
					'unit' => 'deg',
					'size' => 180,
				],
				'range'       => [
					'deg' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 15,
					],
				],
				'description' => esc_html__( 'How far the indicator turns while its dropdown is open. Set it to 0 to keep it still.', 'essential-addons-for-elementor-lite' ),
				// A variable rather than a rule, because the open state is matched
				// in two places: `.is-active` on the front end, and the editor
				// preview switch. Both read this one value.
				'selectors'   => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-indicator-rotate: {{SIZE}}deg;',
				],
			]
		);
	}

	/**
	 * Style: the per-item badge. Colours are per row (see the Menu Items
	 * repeater) — shape and type are shared by every badge.
	 *
	 * Lives inside the Menu Items section — see register_item_style_controls().
	 */
	protected function register_item_badge_style_controls() {

		$this->add_control(
			'eael_mega_menu_badge_heading',
			[
				'label'     => esc_html__( 'Badge', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_mega_menu_badge_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .eael-mega-menu__item-badge',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_badge_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 2,
					'right'    => 8,
					'bottom'   => 2,
					'left'     => 8,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__item-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_badge_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__item-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_badge_offset_x',
			[
				'label'      => esc_html__( 'Horizontal Offset', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-badge-offset-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_badge_offset_y',
			[
				'label'      => esc_html__( 'Vertical Offset', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => '--eael-mm-badge-offset-y: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Colour / background / border / radius / shadow for one menu item state.
	 *
	 * @param string $state    Control id suffix — normal, hover or active.
	 * @param string $selector CSS selector the state applies to.
	 */
	protected function add_item_state_controls( $state, $selector ) {

		$this->add_control(
			'eael_mega_menu_item_color_' . $state,
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => 'normal' === $state ? Global_Colors::COLOR_TEXT : '',
				],
				'selectors' => [
					$selector => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_item_bg_' . $state,
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_item_border_' . $state,
				'selector' => $selector,
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_radius_' . $state,
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_item_shadow_' . $state,
				'selector' => $selector,
			]
		);
	}

	/**
	 * Print the media query that swaps the menu for a hamburger.
	 *
	 * It has to be generated here rather than written into the stylesheet
	 * because the width comes from the site's own Elementor breakpoints, and
	 * every menu can collapse at a different one. The rules are scoped by the
	 * `--hamburger-{device}` class, so two menus set to different widths on the
	 * same page do not interfere.
	 *
	 * @param string $breakpoint Breakpoint key.
	 * @param int    $width      Pixel width below which the hamburger takes over.
	 */
	protected function print_breakpoint_style( $breakpoint, $width ) {

		if ( ! $width ) {
			return;
		}

		$scope = '.eael-mega-menu--hamburger-' . sanitize_html_class( $breakpoint );

		// Deliberately minimal. This only has to hold the collapsed state on the
		// very first paint, before mega-menu.js adds `--is-mobile`; all of the
		// mobile layout lives in the stylesheet under that class.
		$css = sprintf(
			'@media screen and (max-width:%1$dpx){' .
				'%2$s .eael-mega-menu__toggle{display:inline-flex}' .
				'%2$s .eael-mega-menu__nav{max-height:0;overflow:hidden}' .
			'}',
			absint( $width ),
			$scope
		);

		printf(
			'<style id="eael-mm-bp-%s">%s</style>',
			esc_attr( $this->get_id() ),
			// Safe: the width is absint()'d and the scope class is built from a
			// sanitize_html_class()'d breakpoint key, so nothing user-authored
			// reaches the CSS. esc_html() is wrong inside <style> — it is a
			// raw-text element, so entities would break the rules.
			$css // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Whether a menu item link points at the document currently being viewed.
	 *
	 * @param string $url Raw link url from the repeater row.
	 *
	 * @return bool
	 */
	protected function is_current_menu_item( $url ) {

		if ( empty( $url ) || '#' === $url ) {
			return false;
		}

		// Compare paths only — ignore query strings and fragments.
		$item_url = untrailingslashit( strtok( $url, '?#' ) );

		if ( '' === $item_url ) {
			return false;
		}

		global $wp;

		$current_url = untrailingslashit( home_url( isset( $wp->request ) ? $wp->request : '' ) );

		return $item_url === $current_url;
	}

	protected function render() {

		$settings      = $this->get_settings_for_display();
		$is_edit_mode  = Plugin::$instance->editor->is_edit_mode();
		$items         = ! empty( $settings['eael_mega_menu_items'] ) && is_array( $settings['eael_mega_menu_items'] )
			? $settings['eael_mega_menu_items']
			: [];

		if ( empty( $items ) ) {
			if ( $is_edit_mode ) {
				printf(
					'<div class="eael-mega-menu-notice">%s</div>',
					esc_html__( 'Add at least one menu item from the Menu Items section.', 'essential-addons-for-elementor-lite' )
				);
			}

			return;
		}

		$layout    = ! empty( $settings['eael_mega_menu_layout'] ) ? $settings['eael_mega_menu_layout'] : 'horizontal';
		$preset    = ! empty( $settings['eael_mega_menu_preset'] ) ? $settings['eael_mega_menu_preset'] : 'preset-1';
		$trigger   = ! empty( $settings['eael_mega_menu_trigger'] ) ? $settings['eael_mega_menu_trigger'] : 'hover';
		$animation = ! empty( $settings['eael_mega_menu_animation'] ) ? $settings['eael_mega_menu_animation'] : 'fade';
		$duration  = isset( $settings['eael_mega_menu_animation_duration']['size'] )
			? absint( $settings['eael_mega_menu_animation_duration']['size'] )
			: 300;
		$nav_id    = 'eael-mega-menu-' . esc_attr( $this->get_id() );

		$breakpoint       = ! empty( $settings['eael_mega_menu_breakpoint'] ) ? $settings['eael_mega_menu_breakpoint'] : 'tablet';
		$breakpoint_width = $this->get_breakpoint_width( $breakpoint );
		$mobile_type      = ! empty( $settings['eael_mega_menu_mobile_type'] ) ? $settings['eael_mega_menu_mobile_type'] : 'dropdown';
		$close_on_click   = ! empty( $settings['eael_mega_menu_close_on_click'] ) ? 'yes' : 'no';
		$panel_behavior   = ! empty( $settings['eael_mega_menu_mobile_panel_behavior'] ) ? $settings['eael_mega_menu_mobile_panel_behavior'] : 'accordion';
		$scroll_lock      = ! empty( $settings['eael_mega_menu_body_scroll_lock'] ) ? 'yes' : 'no';
		$offcanvas_side   = ! empty( $settings['eael_mega_menu_offcanvas_side'] ) ? $settings['eael_mega_menu_offcanvas_side'] : 'left';
		$is_offcanvas     = $breakpoint_width && 'offcanvas' === $mobile_type;

		$container_classes = [
			'eael-mega-menu-container',
			'eael-mega-menu--' . sanitize_html_class( $layout ),
			'eael-mega-menu--' . sanitize_html_class( $preset ),
		];

		if ( $breakpoint_width ) {
			$container_classes[] = 'eael-mega-menu--hamburger-' . sanitize_html_class( $breakpoint );
			$container_classes[] = 'eael-mega-menu--mobile-' . sanitize_html_class( $mobile_type );
		}

		if ( $is_offcanvas ) {
			$container_classes[] = 'eael-mega-menu--drawer-' . sanitize_html_class( $offcanvas_side );
		}

		$this->add_render_attribute(
			'eael-mega-menu-container',
			[
				'class'          => $container_classes,
				'data-trigger'   => sanitize_key( $trigger ),
				'data-animation' => sanitize_key( $animation ),
				'data-duration'  => $duration,
				// The pixel width, not the breakpoint key — the JS feeds it
				// straight into matchMedia(). The key is already on the element
				// as the --hamburger-* class.
				'data-breakpoint'     => $breakpoint_width,
				'data-mobile-type'    => sanitize_key( $mobile_type ),
				'data-close-on-click' => $close_on_click,
				'data-mobile-panel'   => sanitize_key( $panel_behavior ),
				'data-scroll-lock'    => $scroll_lock,
			]
		);

		$this->add_render_attribute(
			'eael-mega-menu-nav',
			[
				'class'      => 'eael-mega-menu__nav',
				'id'         => $nav_id,
				'aria-label' => esc_attr__( 'Main menu', 'essential-addons-for-elementor-lite' ),
			]
		);
		// Printed before the menu markup so the browser applies it as early as
		// possible. Editor keeps the sections visible and editable in place.
		if ( ! $is_edit_mode ) {
			$this->print_linked_section_hide_style( $this->collect_linked_section_ids( $items ) );
		}

		$this->print_breakpoint_style( $breakpoint, $breakpoint_width );
		?>
		<div <?php $this->print_render_attribute_string( 'eael-mega-menu-container' ); ?>>
			<?php if ( $breakpoint_width ) : ?>
				<button class="eael-mega-menu__toggle" type="button" aria-expanded="false"
					aria-controls="<?php echo esc_attr( $nav_id ); ?>"
					aria-label="<?php esc_attr_e( 'Toggle menu', 'essential-addons-for-elementor-lite' ); ?>">
					<span class="eael-mega-menu__toggle-open">
						<?php Icons_Manager::render_icon( $settings['eael_mega_menu_hamburger_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
					<span class="eael-mega-menu__toggle-close">
						<?php Icons_Manager::render_icon( $settings['eael_mega_menu_hamburger_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
				</button>
			<?php endif; ?>
			<?php if ( $is_offcanvas ) : ?>
				<div class="eael-mega-menu__overlay" aria-hidden="true"></div>
			<?php endif; ?>
			<nav <?php $this->print_render_attribute_string( 'eael-mega-menu-nav' ); ?>>
				<?php if ( $is_offcanvas ) : ?>
					<div class="eael-mega-menu__drawer-header">
						<button class="eael-mega-menu__close" type="button"
							aria-label="<?php esc_attr_e( 'Close menu', 'essential-addons-for-elementor-lite' ); ?>">
							<?php Icons_Manager::render_icon( $settings['eael_mega_menu_hamburger_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</button>
					</div>
				<?php endif; ?>
				<ul class="eael-mega-menu">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$item_key   = 'menu-item-' . $index;
						$link_key   = 'menu-item-link-' . $index;
						$panel_key  = 'menu-item-panel-' . $index;
						$item_url   = isset( $item['item_link']['url'] ) ? $item['item_link']['url'] : '';
						$is_current = $this->is_current_menu_item( $item_url );

						// A panel is only rendered when the chosen source actually resolves
						// to something. An unresolved template or a blank section ID
						// leaves the item as a plain link.
						$content_source = ! empty( $item['content_source'] ) ? $item['content_source'] : 'template';
						$template_id    = 'template' === $content_source ? $this->get_renderable_template_id( $item ) : 0;
						$section_id     = 'section' === $content_source ? $this->get_linked_section_id( $item ) : '';
						$has_panel      = $template_id || '' !== $section_id;
						$panel_id       = 'eael-mega-panel-' . $this->get_id() . '-' . $index;

						$item_classes = [
							'eael-mega-menu__item',
							// Elementor resolves {{CURRENT_ITEM}} in repeater selectors to this class.
							'elementor-repeater-item-' . ( isset( $item['_id'] ) ? $item['_id'] : $index ),
						];

						if ( $is_current ) {
							$item_classes[] = 'eael-mega-menu__item--active';
						}

						if ( $has_panel ) {
							$item_classes[] = 'eael-mega-menu__item--has-panel';
						}

						$item_classes = array_merge( $item_classes, $this->get_item_device_classes( $item ) );

						// An empty badge must leave no element behind at all, so the
						// value is trimmed once here and checked before printing.
						$item_badge = isset( $item['item_badge'] ) ? trim( $item['item_badge'] ) : '';

						$this->add_render_attribute(
							$item_key,
							[
								'class'     => $item_classes,
								'data-item' => $index,
							]
						);

						$this->add_render_attribute( $link_key, 'class', 'eael-mega-menu__item-link' );

						if ( $is_current ) {
							$this->add_render_attribute( $link_key, 'aria-current', 'page' );
						}

						if ( $has_panel ) {
							$this->add_render_attribute(
								$link_key,
								[
									'aria-haspopup' => 'true',
									'aria-expanded' => 'false',
									'aria-controls' => $panel_id,
								]
							);

							$panel_width    = ! empty( $item['panel_width'] ) ? $item['panel_width'] : 'container';
							$panel_position = ! empty( $item['panel_position'] ) ? $item['panel_position'] : 'left';

							$panel_classes = [
								'eael-mega-menu__panel',
								'eael-mega-menu__panel--' . sanitize_html_class( $panel_width ),
							];

							if ( 'full' !== $panel_width ) {
								$panel_classes[] = 'eael-mega-menu__panel--pos-' . sanitize_html_class( $panel_position );
							}

							// Editor-only: hold the panel open so it can be styled. This class
							// is never emitted on the front end.
							if ( $is_edit_mode && ! empty( $item['editor_preview_open'] ) && 'yes' === $item['editor_preview_open'] ) {
								$panel_classes[] = 'eael-mega-menu__panel--editor-open';
							}

							$this->add_render_attribute(
								$panel_key,
								[
									'class'      => $panel_classes,
									'id'         => $panel_id,
									'role'       => 'region',
									/* translators: %s: menu item label. */
									'aria-label' => sprintf( esc_attr__( '%s submenu', 'essential-addons-for-elementor-lite' ), $item['item_label'] ),
								]
							);

							// mega-menu.js reads this and moves the matching element in.
							if ( '' !== $section_id ) {
								$this->add_render_attribute( $panel_key, 'data-mega-section', $section_id );
							}
						}

						if ( ! empty( $item_url ) ) {
							$this->add_link_attributes( $link_key, $item['item_link'] );
						}
						?>
						<li <?php $this->print_render_attribute_string( $item_key ); ?>>
							<a <?php $this->print_render_attribute_string( $link_key ); ?>>
								<?php if ( ! empty( $item['item_icon']['value'] ) ) : ?>
									<span class="eael-mega-menu__item-icon">
										<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
								<span class="eael-mega-menu__item-text"><?php echo esc_html( $item['item_label'] ); ?></span>
								<?php if ( '' !== $item_badge ) : ?>
									<span class="eael-mega-menu__item-badge"><?php echo esc_html( $item_badge ); ?></span>
								<?php endif; ?>
								<?php if ( $has_panel && ! empty( $settings['eael_mega_menu_indicator_icon']['value'] ) ) : ?>
									<span class="eael-mega-menu__indicator">
										<?php Icons_Manager::render_icon( $settings['eael_mega_menu_indicator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
							</a>
							<?php if ( $has_panel ) : ?>
								<div <?php $this->print_render_attribute_string( $panel_key ); ?>>
									<?php
									// Section panels start empty — the JS moves the linked
									// element in once the page has parsed.
									if ( $template_id ) {
										$this->render_template_panel_content( $template_id );
									}
									?>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
		<?php
	}
}

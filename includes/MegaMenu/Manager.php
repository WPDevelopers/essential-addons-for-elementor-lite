<?php

namespace Essential_Addons_Elementor\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;

/**
 * Service provider for the Mega Menu feature.
 *
 * Holds every piece of shared configuration the controls, renderers and
 * templates need, so none of those layers has to reach into Elementor globals
 * on its own.
 *
 * It also owns the feature's single global hook: the editor-window script that
 * registers the nested element type with Elementor. Everything else rides on the
 * existing `config.php` element registry and `Asset_Builder`, so booting this
 * class adds nothing else to global plugin behaviour.
 *
 * @since 6.3.0
 */
class Manager {

	/**
	 * Elementor widget name.
	 */
	const WIDGET_NAME = 'eael-mega-menu';

	/**
	 * Element key inside config.php / `eael_save_settings`.
	 */
	const ELEMENT_KEY = 'mega-menu';

	/**
	 * Root CSS class of the rendered markup.
	 */
	const CSS_ROOT = 'eael-mega-menu';

	/**
	 * @var Manager|null
	 */
	private static $instance = null;

	/**
	 * Singleton accessor.
	 *
	 * @return Manager
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register the feature's editor hooks.
	 *
	 * Called once from Bootstrap. Everything else about the widget rides on the
	 * existing config.php registry and Asset_Builder, so this is the only global
	 * hook the feature adds.
	 */
	public function init() {
		// Priority 20: Elementor's nested-elements module enqueues its own script
		// on this hook at the default priority, and EA boots on `plugins_loaded`,
		// which is earlier than the `init` pass that registers Elementor's modules.
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 20 );
	}

	/**
	 * Enqueue the editor-window script that registers the nested element type.
	 *
	 * Elementor will not treat a widget as nestable until an element type for it
	 * is registered with `elementor.elementsManager`; `support_nesting` in the
	 * widget config only describes it. Without this the default child containers
	 * are never created.
	 */
	public function enqueue_editor_scripts() {
		if ( ! $this->is_supported() ) {
			return;
		}

		// The script extends a class provided by Elementor's nested-elements
		// module. When the experiment is off that module never loads and the
		// widget is hidden from the panel anyway. The `nested-elements`
		// dependency below is resolved by WP at print time, not here, so this
		// does not depend on which callback ran first.
		if ( ! $this->is_available_in_panel() ) {
			return;
		}

		wp_enqueue_script(
			'eael-mega-menu-editor',
			EAEL_PLUGIN_URL . 'assets/front-end/js/edit/mega-menu.min.js',
			[ Conditions::NESTED_EXPERIMENT ],
			EAEL_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Is the feature usable on this install.
	 *
	 * @return bool
	 */
	public function is_supported() {
		return Conditions::is_supported();
	}

	/**
	 * Should the widget be listed in the Elementor panel.
	 *
	 * @return bool
	 */
	public function is_available_in_panel() {
		return Conditions::is_nested_elements_active();
	}

	/**
	 * Submenu open triggers.
	 *
	 * @return array
	 */
	public function get_trigger_options() {
		return [
			'hover' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			'click' => esc_html__( 'Click', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * What a menu item is: a plain link, an inline nested container, or a saved
	 * Elementor template.
	 *
	 * @return array
	 */
	public function get_item_type_options() {
		return [
			'link'     => esc_html__( 'Normal (Link Only)', 'essential-addons-for-elementor-lite' ),
			'mega'     => esc_html__( 'Mega Menu (Build Inline)', 'essential-addons-for-elementor-lite' ),
			'template' => esc_html__( 'Mega Menu (Saved Template)', 'essential-addons-for-elementor-lite' ),
			'section'  => esc_html__( 'Mega Menu (Section CSS ID)', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Submenu reveal animations. Keys map to `eael-mega-menu--anim-{key}` classes.
	 *
	 * @return array
	 */
	public function get_animation_options() {
		return [
			'none'       => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
			'fade'       => esc_html__( 'Fade', 'essential-addons-for-elementor-lite' ),
			'slide-down' => esc_html__( 'Slide Down', 'essential-addons-for-elementor-lite' ),
			'slide-up'   => esc_html__( 'Slide Up', 'essential-addons-for-elementor-lite' ),
			'zoom'       => esc_html__( 'Zoom In', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Submenu width behaviours.
	 *
	 * @return array
	 */
	public function get_submenu_width_options() {
		return [
			'full'     => esc_html__( 'Full Width (Menu)', 'essential-addons-for-elementor-lite' ),
			'viewport' => esc_html__( 'Full Width (Viewport)', 'essential-addons-for-elementor-lite' ),
			'item'     => esc_html__( 'Fit to Content', 'essential-addons-for-elementor-lite' ),
			'custom'   => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Breakpoints the menu can collapse into its mobile (accordion) layout at.
	 *
	 * Mirrors Elementor's own nested tabs dropdown so custom breakpoint values
	 * configured in the Site Settings are honoured.
	 *
	 * @return array
	 */
	public function get_breakpoint_options() {
		$options = [
			'none' => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
		];

		$excluded = [ 'laptop', 'tablet_extra', 'widescreen' ];

		if ( ! isset( Plugin::$instance->breakpoints ) || ! is_object( Plugin::$instance->breakpoints ) ) {
			$options['mobile'] = esc_html__( 'Mobile', 'essential-addons-for-elementor-lite' );

			return $options;
		}

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $key => $breakpoint ) {
			if ( in_array( $key, $excluded, true ) ) {
				continue;
			}

			$options[ $key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value in pixels. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'essential-addons-for-elementor-lite' ),
				$breakpoint->get_label(),
				'>',
				$breakpoint->get_value()
			);
		}

		return $options;
	}

	/**
	 * Label and type of every default repeater row.
	 *
	 * A realistic starting menu: mostly plain links with a couple of mega panels,
	 * so the widget demonstrates both item types the moment it is dropped instead
	 * of opening an empty submenu under every label.
	 *
	 * @return array List of [ label, type ] pairs.
	 */
	protected function get_default_item_map() {
		return [
			[ 'label' => esc_html__( 'Home', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => esc_html__( 'Products', 'essential-addons-for-elementor-lite' ), 'type' => 'mega' ],
			[ 'label' => esc_html__( 'Blog', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => esc_html__( 'Docs', 'essential-addons-for-elementor-lite' ), 'type' => 'mega' ],
			[ 'label' => esc_html__( 'Support', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => esc_html__( 'Contact', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
		];
	}

	/**
	 * Default repeater rows. Kept in sync with get_default_children_elements().
	 *
	 * @return array
	 */
	public function get_default_menu_items() {
		$items = [];

		foreach ( $this->get_default_item_map() as $default ) {
			$item = [
				'eael_mega_menu_item_label' => $default['label'],
				'eael_mega_menu_item_type'  => $default['type'],
			];

			if ( 'link' === $default['type'] ) {
				// Without an href the renderer falls back to a <span>, which is
				// neither clickable nor focusable — a placeholder link keeps the
				// default menu behaving like a menu. Submenu items are left
				// deliberately unlinked so the item itself opens its panel
				// instead of growing a separate disclosure button.
				$item['eael_mega_menu_item_link'] = [ 'url' => '#' ];
			} else {
				$item['eael_mega_menu_item_submenu_width'] = 'full';
			}

			$items[] = $item;
		}

		return $items;
	}

	/**
	 * Nested container each menu item owns. Elementor stores these as regular
	 * child elements of the widget — no extra post meta, no extra tables.
	 *
	 * @param int $index 1 based menu item position.
	 *
	 * @return array
	 */
	public function get_child_container( $index ) {
		return [
			'elType'   => 'container',
			'settings' => [
				'_title' => sprintf(
				/* translators: %d: Menu item index. */
					esc_html__( 'Menu Item #%d', 'essential-addons-for-elementor-lite' ),
					$index
				),
				'content_width' => 'full',
			],
		];
	}

	/**
	 * Default children structure, one container per default repeater row.
	 *
	 * Every row gets a container, including the plain link ones: the widget
	 * addresses children positionally (`print_child( $index )` in Mega_Menu), so
	 * skipping the link rows would shift every later panel onto the wrong menu
	 * item. An unused container costs nothing until its row is switched to a
	 * submenu type, at which point it is already there.
	 *
	 * @return array
	 */
	public function get_default_children_elements() {
		$children = [];

		for ( $index = 1; $index <= count( $this->get_default_menu_items() ); $index++ ) {
			$children[] = $this->get_child_container( $index );
		}

		return $children;
	}
}

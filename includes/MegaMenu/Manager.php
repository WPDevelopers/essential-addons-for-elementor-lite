<?php

namespace Essential_Addons_Elementor\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Helper;
use Essential_Addons_Elementor\Elements\Mega_Menu_Products;
use Essential_Addons_Elementor\MegaMenu\Presets\Preset_Library;

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
	 * admin-ajax action that renders a saved template for the editor preview.
	 */
	const TEMPLATE_PREVIEW_ACTION = 'eael_mega_menu_template_preview';

	/**
	 * admin-ajax action that returns the elements of one preset.
	 */
	const PRESET_ACTION = 'eael_mega_menu_preset';

	/**
	 * Nonce action shared by both editor endpoints.
	 *
	 * The plugin-wide one: EA already prints it for every editor request, so the
	 * two handlers below verify what the editor is already carrying instead of
	 * minting a second token for the same session.
	 */
	const NONCE_ACTION = 'essential-addons-elementor';

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

		// Logged-in only by design — there is no `nopriv` twin. The preview exists
		// for the editor and returns rendered post content, so it stays behind the
		// capability check in the handler.
		add_action( 'wp_ajax_' . self::TEMPLATE_PREVIEW_ACTION, [ $this, 'ajax_template_preview' ] );

		// Same reasoning as the preview above: the presets are only ever applied
		// from inside the editor, so there is no `nopriv` twin.
		add_action( 'wp_ajax_' . self::PRESET_ACTION, [ $this, 'ajax_preset' ] );

		// Priority 20: EA registers its own elements on this hook at the default
		// priority, and the guard below asks whether the Mega Menu was one of
		// them — a question that has no answer until that pass has run.
		add_action( 'elementor/widgets/register', [ $this, 'register_companion_widgets' ], 20 );
	}

	/**
	 * Widgets that belong to the Mega Menu rather than to `config.php`.
	 *
	 * Registered here, off the menu's own boot, and deliberately not given an
	 * element key of their own. A key would put them in EA -> Elements as a
	 * switch a user could turn off — and a switch somewhere else that empties a
	 * column of a menu preset is exactly the fragility this avoids. If the Mega
	 * Menu is available, its companions are.
	 *
	 * They stay out of the widget panel; see
	 * {@see \Essential_Addons_Elementor\Elements\Mega_Menu_Products::show_in_panel()}.
	 *
	 * @since 6.8.4
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_companion_widgets( $widgets_manager ) {
		// One question, asked of the thing that actually knows: did the Mega
		// Menu get registered? That covers every reason it might not have been —
		// Elementor older than the 3.8 nested API, the Nested Elements
		// experiment off, the element unticked in EA -> Elements, or Elementor's
		// own element manager hiding it — without this method having to know,
		// or stay in sync with, any of them.
		if ( ! $widgets_manager->get_widget_types( self::WIDGET_NAME ) ) {
			return;
		}

		$widgets_manager->register( new Mega_Menu_Products() );
	}

	/**
	 * The element of one preset, ready for the editor to apply.
	 *
	 * Built per request rather than shipped with the editor page: every element
	 * needs a fresh ID, so applying the same preset twice cannot collide, and a
	 * header's worth of containers and widgets is a payload no editor load should
	 * carry for a control most sessions never touch. The mode comes from the
	 * editor because only it can see where the menu sits — see the `header` /
	 * `widget` split in {@see Presets\Preset_Library}.
	 *
	 * @since 6.7.5
	 */
	public function ajax_preset() {
		check_ajax_referer( self::NONCE_ACTION, 'security' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to apply presets.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		// The action is registered on every request — Bootstrap boots this feature
		// without checking Elementor, and it cannot: `plugins_loaded` runs before
		// `elementor/loaded`. The preset library asks the widgets manager whether
		// each widget it emits is registered, which is not a question that can be
		// answered without Elementor.
		if ( ! Conditions::has_elementor() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Elementor is not available.', 'essential-addons-for-elementor-lite' ) ], 400 );
		}

		$slug    = isset( $_POST['preset'] ) ? sanitize_key( wp_unslash( $_POST['preset'] ) ) : '';
		$mode    = isset( $_POST['mode'] ) ? sanitize_key( wp_unslash( $_POST['mode'] ) ) : Preset_Library::MODE_HEADER;
		$content = Preset_Library::get_content( $slug, $mode );

		if ( null === $content ) {
			wp_send_json_error( [ 'message' => esc_html__( 'This preset is no longer available.', 'essential-addons-for-elementor-lite' ) ], 404 );
		}

		wp_send_json_success( $content );
	}

	/**
	 * Options for the widget's Preset control.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	public function get_preset_options() {
		return Preset_Library::get_control_options();
	}

	/**
	 * Render a saved template so the editor can preview it inside its panel.
	 *
	 * The editor builds the widget from its JS template, which cannot run the PHP
	 * that pulls a saved template in, so a Saved Template item would otherwise show
	 * nothing there — only the unused nested container behind it. This returns the
	 * same markup the front end renders; `get_builder_content()` forces the
	 * document's CSS inline during an AJAX request, so the response is self
	 * contained and needs no extra stylesheet wiring in the preview.
	 */
	public function ajax_template_preview() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to preview templates.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		// The action is registered on every request — Bootstrap boots this feature
		// without checking Elementor, and it cannot: `plugins_loaded` runs before
		// `elementor/loaded`. So the dependency is asserted here instead, where it
		// is knowable. Without it, deactivating Elementor while library templates
		// remain turns a stale editor tab into a fatal on `Plugin::$instance`.
		if ( ! Conditions::has_elementor() ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Elementor is not available.', 'essential-addons-for-elementor-lite' ) ], 400 );
		}

		$template_id = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
		$page_id     = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;

		// The same guards the front end renderer applies: a published library
		// template, and never the document being edited — that would recurse.
		if ( ! $template_id || $template_id === $page_id ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid template.', 'essential-addons-for-elementor-lite' ) ], 400 );
		}

		// WPML compatibility, mirroring Frontend_Renderer::render_template_panel().
		$template_id = apply_filters( 'wpml_object_id', $template_id, 'elementor_library', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		if ( ! Helper::is_elementor_publish_template( $template_id ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Template not found.', 'essential-addons-for-elementor-lite' ) ], 404 );
		}

		wp_send_json_success( [
			'html' => Plugin::$instance->frontend->get_builder_content( $template_id, true ),
		] );
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

		wp_localize_script( 'eael-mega-menu-editor', 'eaelMegaMenuEditor', [
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( self::NONCE_ACTION ),
			'action'    => self::PRESET_ACTION,
			'widget'    => self::WIDGET_NAME,
			// The value that means "no preset". Sent rather than hard coded in
			// the script so the two cannot drift apart.
			'custom'    => Preset_Library::CUSTOM,
			// Unescaped on purpose. `wp_localize_script()` only entity-decodes
			// scalar members, so an escaped string nested one level down would
			// reach the dialog still carrying its entities.
			'i18n'      => [
				'title'   => __( 'Apply Preset', 'essential-addons-for-elementor-lite' ),
				'confirm' => __( 'Applying a preset replaces this menu\'s items, styles and everything inside its panels. Continue?', 'essential-addons-for-elementor-lite' ),
				'confirmHeader' => __( 'Applying a preset rebuilds the header block this menu sits in — the logo, the menu and its panels, and the buttons beside them. Anything else in that block is replaced. Continue?', 'essential-addons-for-elementor-lite' ),
				'confirmCustom' => __( 'Switching to Custom clears the design and leaves a plain menu to build from. Everything in this header block is replaced. Continue?', 'essential-addons-for-elementor-lite' ),
				'apply'   => __( 'Apply', 'essential-addons-for-elementor-lite' ),
				'cancel'  => __( 'Cancel', 'essential-addons-for-elementor-lite' ),
				'failed'  => __( 'The preset could not be applied.', 'essential-addons-for-elementor-lite' ),
			],
		] );
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

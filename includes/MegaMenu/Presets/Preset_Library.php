<?php
/**
 * Ready-made Mega Menu designs.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.5
 */

namespace Essential_Addons_Elementor\MegaMenu\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\Theme_Builder\Presets\Elements;

/**
 * The starting points offered by the widget's Preset control.
 *
 * A preset is a finished **header**, not a styled menu. The widget's own design
 * already lives in three places at once — the repeater rows that make the bar,
 * the settings that style it, and the nested containers that fill each panel —
 * and around it sits the third of the header nobody navigates without: the
 * logo, and whatever the site asks visitors to do. A preset that stopped at the
 * menu would land a beautiful panel under an empty bar.
 *
 * So `build()` returns one whole element with the menu inside it, and the editor
 * swaps the container the menu was sitting in for it. Same shape the Theme
 * Builder presets use, same builders in {@see Elements}, and for the same
 * reason: the elements are assembled at apply time rather than stored as a
 * frozen blob, because every element ID has to be unique per insert.
 *
 * `MODE_WIDGET` is the way out for a menu the preset has no business rebuilding
 * around — one nested inside something other than a plain container. It returns
 * the menu alone, and the editor replaces just the widget.
 *
 * Nothing a preset uses may be Pro. A preset that names its `widgets` is hidden
 * whenever one of them is missing — switched off in EA's settings, disabled in
 * Elementor's element manager — rather than applying half way and leaving the
 * wreckage on the canvas.
 *
 * @since 6.7.5
 */
class Preset_Library {

	/**
	 * The "I will build this myself" option.
	 *
	 * Not a preset — there is no design behind it — but a real choice all the
	 * same: picking it puts the widget back to the plain menu it ships as, which
	 * is the blank page a user asks for when they want to start over. A tile that
	 * quietly did nothing was a tile that read as broken.
	 */
	const CUSTOM = 'custom';

	/**
	 * Build the whole header — brand, menu, actions.
	 */
	const MODE_HEADER = 'header';

	/**
	 * Build the Mega Menu widget on its own.
	 */
	const MODE_WIDGET = 'widget';

	/**
	 * Every preset, keyed by slug.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	public static function get_presets() {
		$presets = [
			'saas' => [
				'slug'      => 'saas',
				'title'     => __( 'SaaS Menu', 'essential-addons-for-elementor-lite' ),
				'thumbnail' => self::thumbnail_url( 'mega-menu-saas.png' ),
				'widgets'   => [ 'eael-adv-tabs', 'eael-info-box', 'eael-creative-button', 'heading', 'image', 'button', 'icon' ],
				'builder'   => [ Saas_Menu::class, 'build' ],
			],
		];

		/**
		 * Filters the Mega Menu presets offered in the widget panel.
		 *
		 * A preset needs a `title`, a `thumbnail` URL and a `builder` callable
		 * taking a mode (`header` or `widget`) and returning one Elementor
		 * element: the finished header bar with a Mega Menu widget somewhere
		 * inside it, or that widget on its own. The optional `widgets` list names
		 * every widget type the builder can emit, in either mode.
		 *
		 * @since 6.7.5
		 *
		 * @param array $presets Preset definitions keyed by slug.
		 */
		$presets = (array) apply_filters( 'eael/mega-menu/presets', $presets );

		$valid = [];

		foreach ( $presets as $slug => $preset ) {
			$slug = sanitize_key( $slug );

			// `custom` is the control's own value, never a preset — a filtered
			// entry claiming it would shadow the option and make the tile apply
			// something the user asked it not to.
			if ( '' === $slug || self::CUSTOM === $slug || ! is_array( $preset ) ) {
				continue;
			}

			if ( empty( $preset['title'] ) || empty( $preset['builder'] ) || ! is_callable( $preset['builder'] ) ) {
				continue;
			}

			if ( ! empty( $preset['widgets'] ) && ! Elements::has_widgets( $preset['widgets'] ) ) {
				continue;
			}

			$preset['slug'] = $slug;

			$valid[ $slug ] = $preset;
		}

		return $valid;
	}

	/**
	 * Options for the Preset control, in panel order.
	 *
	 * The shape EA's image-backed `CHOOSE` control expects: a `title` for the
	 * tooltip and an `image` for the tile. "Custom" comes last and reuses the
	 * same "your own layout" tile the other EA widgets show.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	public static function get_control_options() {
		$options = [];

		foreach ( self::get_presets() as $slug => $preset ) {
			$options[ $slug ] = [
				'title' => $preset['title'],
				'image' => isset( $preset['thumbnail'] ) ? $preset['thumbnail'] : '',
			];
		}

		$options[ self::CUSTOM ] = [
			'title' => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'image' => self::thumbnail_url( 'custom-layout.png' ),
		];

		return $options;
	}

	/**
	 * The element one preset applies.
	 *
	 * @since 6.7.5
	 *
	 * @param string $slug Preset slug.
	 * @param string $mode `header` for the whole bar, `widget` for the menu alone.
	 *
	 * @return array|null One Elementor element, or null when the slug is unknown.
	 */
	public static function get_content( $slug, $mode = self::MODE_HEADER ) {
		$presets = self::get_presets();
		$slug    = sanitize_key( $slug );
		$mode    = self::MODE_WIDGET === $mode ? self::MODE_WIDGET : self::MODE_HEADER;

		$content = self::CUSTOM === $slug
			? self::default_content( $mode )
			: ( isset( $presets[ $slug ] ) ? call_user_func( $presets[ $slug ]['builder'], $mode ) : null );

		if ( empty( $content ) || ! is_array( $content ) ) {
			return null;
		}

		// The control has to come back holding the preset that built the menu, and
		// the builder is the one place that cannot know its own slug — it is the
		// registry key, not something the class carries.
		self::stamp_preset( $content, $slug );

		/**
		 * Filters the element a Mega Menu preset applies.
		 *
		 * @since 6.7.5
		 *
		 * @param array  $content One Elementor element.
		 * @param string $slug    Preset slug.
		 * @param string $mode    `header` or `widget`.
		 */
		return (array) apply_filters( 'eael/mega-menu/preset_content', $content, $slug, $mode );
	}

	/**
	 * The widget as it ships, for the Custom tile.
	 *
	 * The same rows and the same one-container-per-row that dropping the widget
	 * on the canvas produces, so switching to Custom lands exactly where a user
	 * who had never touched the preset control would have started.
	 *
	 * In header mode it comes wrapped in a bare container, because that is what
	 * it replaces — the preset's header bar goes, and what is left is the plain
	 * container the widget arrived in.
	 *
	 * @since 6.7.5
	 *
	 * @param string $mode `header` or `widget`.
	 *
	 * @return array One Elementor element.
	 */
	protected static function default_content( $mode ) {
		$manager = Manager::instance();

		$rows = [];

		foreach ( $manager->get_default_menu_items() as $item ) {
			$rows[] = Elements::row( $item );
		}

		$panels = [];

		foreach ( $manager->get_default_children_elements() as $child ) {
			$panels[] = Elements::nested_child( isset( $child['settings'] ) ? $child['settings'] : [] );
		}

		$widget = Elements::widget(
			Manager::WIDGET_NAME,
			[ 'eael_mega_menu_items' => $rows ],
			$panels
		);

		if ( self::MODE_WIDGET === $mode ) {
			return $widget;
		}

		return Elements::container(
			[
				'content_width' => 'boxed',
				'padding'       => Elements::spacing( 20, 24, 20, 24 ),
				'_title'        => __( 'Header', 'essential-addons-for-elementor-lite' ),
			],
			[ $widget ]
		);
	}

	/**
	 * Record the preset on every Mega Menu inside an element.
	 *
	 * By reference and recursive: in header mode the widget is two containers
	 * down, and a preset is free to build more than one menu.
	 *
	 * @since 6.7.5
	 *
	 * @param array  $element Element to walk.
	 * @param string $slug    Preset slug.
	 */
	protected static function stamp_preset( array &$element, $slug ) {
		if ( isset( $element['widgetType'] ) && Manager::WIDGET_NAME === $element['widgetType'] ) {
			$element['settings']['eael_mega_menu_preset'] = $slug;
		}

		if ( empty( $element['elements'] ) || ! is_array( $element['elements'] ) ) {
			return;
		}

		foreach ( $element['elements'] as &$child ) {
			if ( is_array( $child ) ) {
				self::stamp_preset( $child, $slug );
			}
		}
	}

	/**
	 * URL of a preset thumbnail.
	 *
	 * @since 6.7.5
	 *
	 * @param string $file File name inside the layout preview image folder.
	 *
	 * @return string
	 */
	protected static function thumbnail_url( $file ) {
		return EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/' . $file;
	}
}

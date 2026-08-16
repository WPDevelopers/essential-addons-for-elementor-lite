<?php
/**
 * Element builders shared by every preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Utils;

/**
 * The Elementor shapes a preset is assembled from.
 *
 * A preset is an array of plain element arrays handed to `document/elements/create`
 * in the editor, so every one of these returns data, never markup. They exist so a
 * preset reads as a layout rather than as a wall of Elementor's storage format.
 *
 * @since 6.7.3
 */
class Elements {

	/**
	 * A container element.
	 *
	 * @since 6.7.3
	 *
	 * @param array $settings Container settings.
	 * @param array $children Child elements.
	 *
	 * @return array
	 */
	public static function container( $settings = [], $children = [] ) {
		return [
			'id'       => self::uid(),
			'elType'   => 'container',
			'settings' => $settings,
			'elements' => $children,
			'isInner'  => false,
		];
	}

	/**
	 * A container that is one nested widget's child.
	 *
	 * Elementor's `NestedModelBase::isValidChild()` accepts a child only when it
	 * carries `isLocked` — the flag its own `getDefaultChildren()` stamps on the
	 * containers it builds. A preset supplies its children ready-made, which stops
	 * that method from running at all (it only fires for a widget created with no
	 * elements), so the flag has to be set here or the panels are rejected.
	 *
	 * @since 6.7.3
	 *
	 * @param array $settings Container settings.
	 * @param array $children Child elements.
	 *
	 * @return array
	 */
	public static function nested_child( $settings = [], $children = [] ) {
		$container = self::container( $settings, $children );

		$container['isLocked'] = true;

		return $container;
	}

	/**
	 * A widget element.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type     Widget type.
	 * @param array  $settings Widget settings.
	 * @param array  $children Child elements — nested widgets only.
	 *
	 * @return array
	 */
	public static function widget( $type, $settings = [], $children = [] ) {
		return [
			'id'         => self::uid(),
			'elType'     => 'widget',
			'widgetType' => $type,
			'settings'   => $settings,
			'elements'   => $children,
		];
	}

	/**
	 * A repeater row.
	 *
	 * Elementor keys repeater rows by `_id` and generates one for every row added
	 * in the panel. A row that arrives without it cannot be told apart from its
	 * siblings — reordering and deleting both work off that key — so preset rows
	 * are stamped here rather than left for the editor to backfill.
	 *
	 * @since 6.7.3
	 *
	 * @param array $fields Row fields.
	 *
	 * @return array
	 */
	public static function row( $fields = [] ) {
		return array_merge( [ '_id' => self::uid() ], $fields );
	}

	/**
	 * A padding/margin/radius value in Elementor's shape.
	 *
	 * @since 6.7.3
	 *
	 * @param int    $top    Top value.
	 * @param int    $right  Right value.
	 * @param int    $bottom Bottom value.
	 * @param int    $left   Left value.
	 * @param string $unit   CSS unit.
	 *
	 * @return array
	 */
	public static function spacing( $top, $right, $bottom, $left, $unit = 'px' ) {
		return [
			'unit'     => $unit,
			'top'      => (string) $top,
			'right'    => (string) $right,
			'bottom'   => (string) $bottom,
			'left'     => (string) $left,
			'isLinked' => false,
		];
	}

	/**
	 * A slider value.
	 *
	 * @since 6.7.3
	 *
	 * @param int|float $size Value.
	 * @param string    $unit CSS unit.
	 *
	 * @return array
	 */
	public static function size( $size, $unit = 'px' ) {
		return [
			'unit' => $unit,
			'size' => $size,
		];
	}

	/**
	 * A container gap value.
	 *
	 * @since 6.7.3
	 *
	 * @param int $size Gap in pixels, used for both axes.
	 *
	 * @return array
	 */
	public static function gap( $size ) {
		return [
			'unit'     => 'px',
			'size'     => $size,
			'column'   => (string) $size,
			'row'      => (string) $size,
			'isLinked' => true,
		];
	}

	/**
	 * A box shadow value.
	 *
	 * @since 6.7.3
	 *
	 * @param int    $vertical Vertical offset.
	 * @param int    $blur     Blur radius.
	 * @param string $color    Shadow colour.
	 *
	 * @return array
	 */
	public static function shadow( $vertical, $blur, $color ) {
		return [
			'horizontal' => 0,
			'vertical'   => $vertical,
			'blur'       => $blur,
			'spread'     => 0,
			'color'      => $color,
		];
	}

	/**
	 * A URL control value.
	 *
	 * @since 6.7.3
	 *
	 * @param string $url Destination.
	 *
	 * @return array
	 */
	public static function link( $url = '#' ) {
		return [
			'url'         => $url,
			'is_external' => '',
			'nofollow'    => '',
		];
	}

	/**
	 * An icon control value.
	 *
	 * @since 6.7.3
	 *
	 * @param string $value   Icon class, empty for no icon.
	 * @param string $library Icon library.
	 *
	 * @return array
	 */
	public static function icon( $value, $library = 'fa-solid' ) {
		return [
			'value'   => $value,
			'library' => '' === $value ? '' : $library,
		];
	}

	/**
	 * Elementor's own placeholder image.
	 *
	 * Presets ship no artwork of their own: an image the user has to hunt down and
	 * replace is worse than the placeholder they already recognise as "swap me".
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function placeholder_image() {
		return class_exists( '\Elementor\Utils' ) ? Utils::get_placeholder_image_src() : '';
	}

	/**
	 * A fresh element ID.
	 *
	 * Elementor keys every element by a 7 character hex ID and expects them to
	 * be unique inside a document — so they are generated per insert rather than
	 * baked into the preset, which would collide the second time it is used.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function uid() {
		return substr( str_pad( dechex( wp_rand( 0, 0xfffffff ) ), 7, '0', STR_PAD_LEFT ), 0, 7 );
	}
}

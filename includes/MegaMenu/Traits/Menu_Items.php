<?php

namespace Essential_Addons_Elementor\MegaMenu\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Shared menu item helpers used by the widget and its renderers.
 *
 * Keeps the "what does this repeater row mean" logic in one place so the
 * frontend renderer and the editor template can never drift apart.
 *
 * @since 6.3.0
 */
trait Menu_Items {

	/**
	 * Normalise a repeater row into a predictable shape.
	 *
	 * @param array $item          Raw repeater row.
	 * @param int   $index         Zero based row index.
	 * @param int   $widget_number Unique widget number.
	 *
	 * @return array
	 */
	public function eael_mega_menu_prepare_item( $item, $index, $widget_number ) {
		$position     = $index + 1;
		$default_id   = 'eael-mega-menu-item-' . $widget_number . '-' . $position;
		$custom_id    = isset( $item['eael_mega_menu_item_css_id'] ) ? trim( (string) $item['eael_mega_menu_item_css_id'] ) : '';
		$custom_class = isset( $item['eael_mega_menu_item_css_classes'] ) ? trim( (string) $item['eael_mega_menu_item_css_classes'] ) : '';
		$url          = isset( $item['eael_mega_menu_item_link']['url'] ) ? trim( (string) $item['eael_mega_menu_item_link']['url'] ) : '';

		$type = $this->eael_mega_menu_item_type( $item );

		return [
			'index'        => $index,
			'position'     => $position,
			'item'         => $item,
			'label'        => isset( $item['eael_mega_menu_item_label'] ) ? $item['eael_mega_menu_item_label'] : '',
			'icon'         => isset( $item['eael_mega_menu_item_icon'] ) ? $item['eael_mega_menu_item_icon'] : [],
			'link'         => isset( $item['eael_mega_menu_item_link'] ) ? $item['eael_mega_menu_item_link'] : [],
			'has_url'      => '' !== $url,
			'type'         => $type,
			'has_submenu'  => 'link' !== $type,
			'template_id'  => isset( $item['eael_mega_menu_item_template'] ) ? absint( $item['eael_mega_menu_item_template'] ) : 0,
			'section_id'   => $this->eael_mega_menu_sanitize_element_id( isset( $item['eael_mega_menu_item_section_id'] ) ? $item['eael_mega_menu_item_section_id'] : '' ),
			'width_mode'   => isset( $item['eael_mega_menu_item_submenu_width'] ) && '' !== $item['eael_mega_menu_item_submenu_width']
				? $item['eael_mega_menu_item_submenu_width']
				: 'full',
			'item_id'      => '' !== $custom_id ? $custom_id : $default_id,
			'default_id'   => $default_id,
			'panel_id'     => 'eael-mega-menu-panel-' . $widget_number . '-' . $position,
			'custom_class' => $custom_class,
		];
	}

	/**
	 * Normalised menu item type.
	 *
	 * Falls back to the switcher this control replaced so rows saved before the
	 * type existed keep behaving the same way.
	 *
	 * @param array $item Raw repeater row.
	 *
	 * @return string One of `link`, `mega`, `template`.
	 */
	public function eael_mega_menu_item_type( $item ) {
		$type = isset( $item['eael_mega_menu_item_type'] ) ? $item['eael_mega_menu_item_type'] : '';

		if ( '' === $type ) {
			$had_submenu = ! isset( $item['eael_mega_menu_item_has_submenu'] ) || 'yes' === $item['eael_mega_menu_item_has_submenu'];
			$type        = $had_submenu ? 'mega' : 'link';
		}

		return in_array( $type, [ 'link', 'mega', 'template', 'section' ], true ) ? $type : 'mega';
	}

	/**
	 * Does a repeater row own a submenu panel.
	 *
	 * @param array $item Raw repeater row.
	 *
	 * @return bool
	 */
	public function eael_mega_menu_item_has_submenu( $item ) {
		return 'link' !== $this->eael_mega_menu_item_type( $item );
	}

	/**
	 * Sanitise a user supplied CSS ID.
	 *
	 * Tolerates a leading `#` and strips anything that could break out of the
	 * attribute or be abused as a selector.
	 *
	 * @param string $id Raw CSS ID.
	 *
	 * @return string
	 */
	public function eael_mega_menu_sanitize_element_id( $id ) {
		if ( ! is_string( $id ) || '' === trim( $id ) ) {
			return '';
		}

		return preg_replace( '/[^A-Za-z0-9_-]/', '', ltrim( trim( $id ), '#' ) );
	}

	/**
	 * Split a user supplied CSS class string into sanitised class names.
	 *
	 * @param string $classes Space separated class list.
	 *
	 * @return array
	 */
	public function eael_mega_menu_sanitize_classes( $classes ) {
		if ( empty( $classes ) || ! is_string( $classes ) ) {
			return [];
		}

		$classes = preg_split( '/\s+/', trim( $classes ) );

		return array_values( array_filter( array_map( 'sanitize_html_class', (array) $classes ) ) );
	}
}

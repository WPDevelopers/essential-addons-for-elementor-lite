<?php

// 'fields' => [
// 	[
// 		'field'       => 'eael_flipbox_front_title',
// 		'type'        => __('Flip Box: Front Title', 'essential-addons-elementor'),
// 		'editor_type' => 'LINE',
// 	],
// 	[
// 		'field'       => 'eael_flipbox_front_text',
// 		'type'        => __('Flip Box: Front Text', 'essential-addons-elementor'),
// 		'editor_type' => 'AREA',
// 	],
// 	[
// 		'field'       => 'eael_flipbox_back_title',
// 		'type'        => __('Flip Box: Back Title', 'essential-addons-elementor'),
// 		'editor_type' => 'LINE',
// 	],
// 	[
// 		'field'       => 'eael_flipbox_back_text',
// 		'type'        => __('Flip Box: Back Text', 'essential-addons-elementor'),
// 		'editor_type' => 'AREA',
// 	]
// ],

namespace Essential_Addons_Elementor\Classes\WPML\Widgets;

use WPML_Elementor_Module_With_Items;
use Essential_Addons_Elementor\Classes\WPML\Eael_WPML;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Carousel
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Flip_Box extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_flipbox_content_tabs';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'eael_flipbox_front_title',
			'eael_flipbox_front_text',
			'eael_flipbox_back_title',
			'eael_flipbox_back_text'
		);
	}

  	/**
     * @param string $field
	 * 
	 * Get the field title string
     *
     * @return string
     */
	protected function get_title( $field ) {
		switch($field) {
			case 'eael_flipbox_front_title':
				return __( 'Flip Box Front: Title', 'essential-addons-elementor' );

			case 'eael_flipbox_front_text':
				return __( 'Flip Box Front: Content', 'essential-addons-elementor' );

			case 'eael_flipbox_back_title':
				return __( 'Flip Box Back: Title', 'essential-addons-elementor' );

			case 'eael_flipbox_back_text':
				return __( 'Flip Box Back: Content', 'essential-addons-elementor' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 * 
	 * Get perspective field types.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch($field) {
			case 'eael_flipbox_front_title':
				return 'LINE';

			case 'eael_flipbox_front_text':
				return 'AREA';

			case 'eael_flipbox_back_title':
				return 'LINE';

			case 'eael_flipbox_back_text':
				return 'AREA';

			default:
				return '';
		}
	}

}

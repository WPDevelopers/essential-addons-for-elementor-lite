<?php

namespace Essential_Addons_Elementor\Classes\WPML\Widgets;

use WPML_Elementor_Module_With_Items;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Carousel
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Image_Accordion extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_img_accordions';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'eael_accordion_tittle',
			'eael_accordion_content'
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
			case 'eael_accordion_tittle':
				return __( 'Image Accordion: Title', 'essential-addons-for-elementor-lite');

			case 'eael_accordion_content':
				return __( 'Image Accordion: Content', 'essential-addons-for-elementor-lite');

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
			case 'eael_accordion_tittle':
				return 'LINE';

			case 'eael_accordion_content':
				return 'VISUAL';

			default:
				return '';
		}
	}

}

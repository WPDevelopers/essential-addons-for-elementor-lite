<?php

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
class Pricing_Table extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_pricing_table_items';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'eael_pricing_table_item',
			'eael_pricing_item_tooltip_content'
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
			case 'eael_pricing_table_item':
				return __( 'Pricing Table: Feature', 'essential-addons-elementor' );

			case 'eael_pricing_item_tooltip_content':
				return __( 'Pricing Table: Feature Tooltip', 'essential-addons-elementor' );

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
			case 'eael_pricing_table_item':
				return 'LINE';

			case 'eael_pricing_item_tooltip_content':
				return 'LINE';

			default:
				return '';
		}
	}

}

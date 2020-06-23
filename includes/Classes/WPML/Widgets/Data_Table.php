<?php

namespace Essential_Addons_Elementor\Classes\WPML\Widgets;

use WPML_Elementor_Module_With_Items;

if (!defined('ABSPATH')) {
    exit;
}
// No access of directly access

/**
 * Creative Button
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Data_Table extends WPML_Elementor_Module_With_Items
{

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_data_table_header_cols_data';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
            'eael_data_table_header_col'
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
			case 'eael_data_table_header_col':
                return __( 'Data Table: Header', 'essential-addons-for-elementor-lite' );

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
			case 'eael_data_table_header_col':
                return 'LINE';

			default:
				return '';
		}
	}

}

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
class Data_Table extends WPML_Elementor_Module_With_Items {

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
			'eael_data_table_header_col',
			'eael_data_table_content_row_title',
			'eael_data_table_content_row_content'
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
				return __( 'Data Table: Column Name', 'essential-addons-elementor' );

			case 'eael_data_table_content_row_title':
				return __( 'Data Table: Cell Text', 'essential-addons-elementor' );

			case 'eael_data_table_content_row_content':
				return __( 'Data Table: Cell Text', 'essential-addons-elementor' );

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

			case 'eael_data_table_content_row_title':
				return 'AREA';

			case 'eael_data_table_content_row_content':
				return 'AREA';

			default:
				return '';
		}
	}

}

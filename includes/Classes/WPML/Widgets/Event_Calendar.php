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
class Event_Calendar extends WPML_Elementor_Module_With_Items
{

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_event_items';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
            'eael_event_title',
            'eael_event_link',
            'eael_event_description'
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
			case 'eael_event_title':
                return __( 'Event: Title', 'essential-addons-for-elementor-lite' );
                
            case 'eael_event_link':
                return __( 'Event: Content', 'essential-addons-for-elementor-lite' );
                
            case 'eael_event_description':
                return __( 'Event : Description', 'essential-addons-for-elementor-lite' );

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
			case 'eael_event_title':
                return 'LINE';
                
            case 'eael_event_link':
                return 'LINE';
                 
            case 'eael_event_description':
				return 'VISUAL';

			default:
				return '';
		}
	}

}

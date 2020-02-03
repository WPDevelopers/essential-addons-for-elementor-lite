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
class Creative_Button extends WPML_Elementor_Module_With_Items
{

    /**
     * Get widget field name.
     *
     * @return string
     */
    public function get_items_field()
    {
        return 'creative_button_link_url';
    }

    /**
     * Get the fields inside the repeater.
     *
     * @return array
     */
    public function get_fields()
    {
        return [
            'url',
        ];
    }

    /**
     * @param string $field
     *
     * Get the field title string
     *
     * @return string
     */
    protected function get_title($field)
    {
        switch ($field) {
            case 'url':
                return __('Creative Button: Link URL', 'essential-addons-for-elementor-lite');

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
    protected function get_editor_type($field)
    {
        switch ($field) {
            case 'url':
                return 'LINE';

            default:
                return '';
        }
    }

}

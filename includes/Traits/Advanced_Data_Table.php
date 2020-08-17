<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Advanced_Data_Table
{
    public function advanced_data_table_ninja_integration($settings)
    {
        if (empty($settings['ea_adv_data_table_source_ninja_table_id'])) {
            return;
        }

        $html = '';
        $table_settings = ninja_table_get_table_settings($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_headers = ninja_table_get_table_columns($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_rows = ninjaTablesGetTablesDataByID($settings['ea_adv_data_table_source_ninja_table_id']);

        if (!empty($table_rows)) {
            if (!isset($table_settings['hide_header_row']) || $table_settings['hide_header_row'] != true) {
                $html .= '<thead><tr>';
                foreach ($table_headers as $key => $th) {
                    $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                    $html .= '<th' . $style . '>' . $th['name'] . '</th>';
                }
                $html .= '</tr></thead>';
            }

            $html .= '<tbody>';
            foreach ($table_rows as $key => $tr) {
                $html .= '<tr>';
                foreach ($table_headers as $th) {
                    if (!isset($th['data_type'])) {
                        $th['data_type'] = '';
                    }

                    if ($th['data_type'] == 'image') {
                        $html .= '<td>' . (isset($tr[$th['key']]['image_thumb']) ? '<a href="' . $tr[$th['key']]['image_full'] . '"><img src="' . $tr[$th['key']]['image_thumb'] . '"></a>' : '') . '</td>';
                    } elseif ($th['data_type'] == 'selection') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? implode((array) $tr[$th['key']], ', ') : '') . '</td>';
                    } elseif ($th['data_type'] == 'button') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? '<a href="' . $tr[$th['key']] . '" class="button" target="' . $th['link_target'] . '">' . $th['button_text'] . '</a>' : '') . '</td>';
                    } else {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? $tr[$th['key']] : '') . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        return $html;
    }
}

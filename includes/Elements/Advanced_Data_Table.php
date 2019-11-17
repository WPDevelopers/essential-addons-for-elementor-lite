<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Advanced_Data_Table extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-advanced-data-table';
    }

    public function get_title()
    {
        return esc_html__('EA Advanced Data Table', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'eicon-table';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'ea_section_adv_data_table_source',
            [
                'label' => esc_html__('Data Source', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_source',
            [
                'label' => esc_html__('Source', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => esc_html__('Static Data', 'essential-addons-elementor'),
                ],
                'default' => 'static',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_static_num_row',
            [
                'label' => __('Row', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 4,
                'condition' => [
                    'ea_adv_data_table_source' => 'static',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_static_num_col',
            [
                'label' => __('Column', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 4,
                'condition' => [
                    'ea_adv_data_table_source' => 'static',
                ],
            ]
        );

        // $this->add_control(
        //     'ea_adv_data_table_static_generate',
        //     [
        //         'type' => Controls_Manager::BUTTON,
        //         'text' => __('Generate Table', 'essential-addons-elementor'),
        //         'event' => 'ea:atab:gen',
        //         'condition' => [
        //             'ea_adv_data_table_source' => 'static',
        //         ],
        //     ]
        // );

        // $this->add_control(
        //     'ea_adv_data_table_static_generate',
        //     [
        //         'type' => Controls_Manager::RAW_HTML,
        //         'label' => '<div style="display:block;"><button class="elementor-button ea-adv-data-table-gen" data-id="' . $this->get_id() . '" style="display:block;background:#d30c5c;color:#fff;margin:0 auto;padding:5px 10px;">Generate</button></div>',
        //         'condition' => [
        //             'ea_adv_data_table_source' => 'static',
        //         ],
        //     ]
        // );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['ea_adv_data_table_source'] == 'static') {
            $this->add_render_attribute('ea-adv-data-table-wrap', [
                'class' => "ea-advanced-data-table-wrap ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
                'data-id' => $this->get_id(),
                'data-num-row' => $settings['ea_adv_data_table_static_num_row'],
                'data-num-col' => $settings['ea_adv_data_table_static_num_col'],
            ]);
        }

        echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-wrap') . '>
            ' . $this->html_static_table($settings) . '
        </div>';
    }

    protected function html_static_table($settings)
    {
        $row = $settings['ea_adv_data_table_static_num_row'];
        $col = $settings['ea_adv_data_table_static_num_col'];
        $table = '<table>';

        foreach (range(0, $row) as $r) {
            $table .= '<tr>';
            foreach (range(0, $col) as $c) {
                $this->add_inline_editing_attributes("ea_adv_data_table_{$r}_{$c}", 'none');

                $table .= '<td>' . $this->get_settings("ea_adv_data_table_{$r}_{$c}") . '</td>';
            }
            $table .= '</tr>';
        }

        $table .= '</table>';

        return $table;
    }

}

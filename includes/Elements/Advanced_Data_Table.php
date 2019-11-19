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

    protected $html_table = '<table><tbody><tr><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td></tr><tr><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td></tr><tr><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td></tr><tr><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td><td contenteditable=""></td></tr></tbody></table>';

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
            'ea_adv_data_table_static_html',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => $this->html_table,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['ea_adv_data_table_source'] == 'static') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
                'data-id' => $this->get_id(),
            ]);
        }

        echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table') . '>
            ' . $this->html_static_table($settings) . '
        </div>';
    }

    protected function html_static_table($settings)
    {
        return $settings['ea_adv_data_table_static_html'];
    }

}

<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography;
use \Elementor\Widget_Base;

class Advanced_Data_Table extends Widget_Base
{
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
        // general
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
                'default' => '<thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody>',
            ]
        );

        $this->end_controls_section();

        // style
        $this->start_controls_section(
            'ea_section_adv_data_table_style_general',
            [
                'label' => __('General', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_border',
                'label' => __('Table Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .ea-advanced-data-table',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_head',
            [
                'label' => __('Head', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_head_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} th textarea, {{WRAPPER}} th',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'ea_adv_data_table_head_horizontal_alignment',
            [
                'label' => esc_html__('Text Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} th textarea' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} th' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ea_adv_data_table_head_background',
                'label' => __('Background', 'essential-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => [
                    'image',
                ],
                'selector' => '{{WRAPPER}} thead',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_head_border',
                'label' => __('Head Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} thead',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_head_cell_border',
                'label' => __('Cell Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} th',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_cell_padding',
            [
                'label' => __('Padding', 'plugin-domain'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-editable) th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table.ea-advanced-data-table-editable th textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['ea_adv_data_table_source'] == 'static') {
            $this->add_render_attribute('ea-adv-data-table-wrap', [
                'class' => "ea-advanced-data-table-wrap",
                'data-id' => $this->get_id(),
            ]);

            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
                'data-id' => $this->get_id(),
            ]);
        }

        echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-wrap') . '>
            <table ' . $this->get_render_attribute_string('ea-adv-data-table') . '>' . $this->html_static_table($settings) . '</table>
        </div>';
    }

    protected function html_static_table($settings)
    {
        return $settings['ea_adv_data_table_static_html'];
    }

}

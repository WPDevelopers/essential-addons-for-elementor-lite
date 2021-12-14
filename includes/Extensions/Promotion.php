<?php
namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;

class Promotion
{
    public function __construct()
    {
        if (!apply_filters('eael/pro_enabled', false)) {
            add_action('elementor/element/section/section_layout/after_section_end', [$this, 'section_parallax'], 10);
            add_action('elementor/element/section/section_layout/after_section_end', [$this, 'section_particles'], 10);
            add_action('elementor/element/common/_section_style/after_section_end', [$this, 'content_protection'], 10);
            add_action('elementor/element/common/_section_style/after_section_end', [$this, 'section_tooltip'], 10);
        }
    }

    public function teaser_template($texts)
    {
        $html = '<div class="ea-nerd-box">
            <div class="ea-nerd-box-icon">
                <img src="' . EAEL_PLUGIN_URL . 'assets/admin/images/icon-ea-logo.svg' . '">
            </div>
            <div class="ea-nerd-box-title">' . $texts['title'] . '</div>
            <div class="ea-nerd-box-message">' . $texts['messages'] . '</div>
            <a class="ea-nerd-box-link elementor-button elementor-button-default" href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">
            ' . __('Upgrade Essential Addons', 'essential-addons-for-elementor-lite') . '
            </a>
        </div>';

        return $html;
    }

    public function section_parallax($element)
    {
        $element->start_controls_section(
            'eael_ext_section_parallax_section',
            [
                'label' => __('<i class="eaicon-logo"></i> Parallax', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'eael_ext_section_parallax_pro_required',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => $this->teaser_template([
                    'title' => __('Meet EA Parallax', 'essential-addons-for-elementor-lite'),
                    'messages' => __('Create stunning Parallax effects on your site and blow everyone away.', 'essential-addons-for-elementor-lite'),
                ]),
            ]
        );

        $element->end_controls_section();
    }

    public function section_particles($element)
    {
        $element->start_controls_section(
            'eael_ext_section_particles_section',
            [
                'label' => __('<i class="eaicon-logo"></i> Particles', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'eael_ext_section_particles_pro_required',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => $this->teaser_template([
                    'title' => __('Meet EA Particles', 'essential-addons-for-elementor-lite'),
                    'messages' => __('Create stunning Particles effects on your site and blow everyone away.', 'essential-addons-for-elementor-lite'),
                ]),
            ]
        );

        $element->end_controls_section();
    }

    public function content_protection($element)
    {
        $element->start_controls_section(
            'eael_ext_content_protection_section',
            [
                'label' => __('<i class="eaicon-logo"></i> Content Protection', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'eael_ext_content_protection_pro_required',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => $this->teaser_template([
                    'title' => __('Meet EA Content Protection', 'essential-addons-for-elementor-lite'),
                    'messages' => __('Put a restriction on any of your content and protect your privacy.', 'essential-addons-for-elementor-lite'),
                ]),
            ]
        );

        $element->end_controls_section();
    }

    public function section_tooltip($element)
    {
        $element->start_controls_section(
            'eael_ext_section_tooltip_section',
            [
                'label' => __('<i class="eaicon-logo"></i> Advanced Tooltip', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'eael_ext_section_tooltip_pro_required',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => $this->teaser_template([
                    'title' => __('Meet EA Advanced Tooltip', 'essential-addons-for-elementor-lite'),
                    'messages' => __('Highlight any Elementor widgets with a key message when they are hovered.', 'essential-addons-for-elementor-lite'),
                ]),
            ]
        );

        $element->end_controls_section();
    }

}

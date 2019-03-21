<?php
namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use EssentialAddonsElementor\Classes\Group_Control_EA_Posts as Group_Control_EA_Posts;

trait ElementorHelper
{
    public function before_enqueue_scripts()
    {
        wp_enqueue_style('essential_addons_elementor_editor-css', $this->plugin_url . 'assets/css/essential-addons-editor.css');
        wp_enqueue_style('essential_addons_elementor', content_url('uploads/essential-addons/eael.min.css'));
        wp_enqueue_script('essential_addons_elementor', content_url('uploads/essential-addons/eael.min.js'), array('jquery'), $this->plugin_version, true);

        if ( class_exists( 'GFCommon' ) ) {
            foreach( eael_select_gravity_form() as $form_id => $form_name ){
                if ( $form_id != '0' ) {
                    gravity_form_enqueue_scripts( $form_id );
                }
            };
        }

        if ( function_exists( 'wpforms' ) ) {
            wpforms()->frontend->assets_css();
        }

        // localize script
    }

    public function add_elementor_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'essential-addons-elementor',
            [
                'title' => __('Essential Addons', 'essential-addons-elementor'),
                'icon' => 'font',
            ],
            1
        );
    }

    public function controls_registered($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }
}

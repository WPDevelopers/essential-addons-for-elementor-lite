<?php
namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

use EssentialAddonsElementor\Classes\EAE_Posts_Group_Control as EAE_Posts_Group_Control;

trait ElementorHelper
{


    public function eae_before_enqueue_scripts()
    {
        wp_register_style('essential_addons_elementor_editor-css', ESSENTIAL_ADDONS_EL_URL . 'assets/css/essential-addons-editor.css');
        wp_enqueue_style('essential_addons_elementor_editor-css');
    }

    public function eae_posts_register_control($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new EAE_Posts_Group_Control);
    }
}
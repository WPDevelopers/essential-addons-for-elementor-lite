<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Enqueue
{
    public function enqueue_scripts()
    {
        // Gravity forms Compatibility
        if (class_exists('GFCommon')) {
            foreach ($this->eael_select_gravity_form() as $form_id => $form_name) {
                if ($form_id != '0') {
                    gravity_form_enqueue_scripts($form_id);
                }
            };
        }

        // WPforms compatibility
        if (function_exists('wpforms')) {
            wpforms()->frontend->assets_css();
        }

        // Caldera forms compatibility
        if (class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // My Assets
        if (Plugin::$instance->preview->is_preview_mode()) {
            if ($this->has_cache_files()) {
                $css_file = EAEL_ASSET_URL . '/eael.min.css';
                $js_file = EAEL_ASSET_URL . '/eael.min.js';
            } else {
                $css_file = EAEL_PLUGIN_URL . '/assets/front-end/css/eael.min.css';
                $js_file = EAEL_PLUGIN_URL . '/assets/front-end/js/eael.min.js';

                $this->generate_scripts($this->get_settings());
            }

            wp_enqueue_script(
                'eael-backend',
                $js_file,
                ['jquery'],
                EAEL_PLUGIN_VERSION,
                true
            );

            wp_enqueue_style(
                'eael-editor-css',
                EAEL_PLUGIN_URL . '/assets/front-end/css/eael-editor.css',
                false,
                EAEL_PLUGIN_VERSION
            );

            wp_enqueue_style(
                'eael-backend',
                $css_file,
                false,
                EAEL_PLUGIN_VERSION
            );

            // localize script
            wp_localize_script('eael-backend', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        } else if (is_singular()) {
            $post_id = get_the_ID();

            if ($this->has_cache_files($post_id)) {
                $css_file = EAEL_ASSET_URL . '/eael-' . $post_id . '.min.css';
                $js_file = EAEL_ASSET_URL . '/eael-' . $post_id . '.min.js';
            } else {
                $css_file = EAEL_PLUGIN_URL . '/assets/front-end/css/eael.min.css';
                $js_file = EAEL_PLUGIN_URL . '/assets/front-end/js/eael.min.js';
            }

            wp_enqueue_script(
                'eael-front-end',
                $js_file,
                ['jquery'],
                EAEL_PLUGIN_VERSION,
                true
            );

            wp_enqueue_style(
                'eael-front-end',
                $css_file,
                false,
                EAEL_PLUGIN_VERSION
            );

            // localize script
            wp_localize_script('eael-front-end', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        }
    }
}

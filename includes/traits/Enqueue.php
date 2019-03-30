<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Enqueue
{
    public $js_file, $css_file, $post_id;

    public function enqueue_scripts()
    {
        // Gravity Form Compatibility
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

        // My Assets
        if (Plugin::$instance->preview->is_preview_mode()) {
            if (file_exists(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael.min.js')) {
                $js_file = EAEL_ASSET_URL . '/eael.min.js';
            } else {
                $js_file = EAEL_PLUGIN_URL . '/assets/front-end/js/eael.min.js';
                $this->generate_scripts($this->get_settings());
            }

            if (file_exists(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael.min.css')) {
                $css_file = EAEL_ASSET_URL . '/eael.min.css';
            } else {
                $css_file = EAEL_PLUGIN_URL . '/assets/front-end/css/eael.min.css';
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
            $elements = $this->widgets_in_post($post_id);

            if (empty($elements)) {
                return;
            }

            if (file_exists(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael-' . $post_id . '.min.js')) {
                $js_file = EAEL_ASSET_URL . '/eael-' . $post_id . '.min.js';
            } else {
                $js_file = EAEL_PLUGIN_URL . '/assets/front-end/js/eael.min.js';
                $this->generate_post_scripts($post_id, $elements);
            }

            if (file_exists(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael-' . $post_id . '.min.css')) {
                $css_file = EAEL_ASSET_URL . '/eael-' . $post_id . '.min.css';
            } else {
                $css_file = EAEL_PLUGIN_URL . '/assets/front-end/css/eael.min.css';
                $this->generate_post_scripts($post_id, $elements);
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

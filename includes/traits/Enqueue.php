<?php

namespace EssentialAddonsElementor\Traits;

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
            if (file_exists($this->asset_path . DIRECTORY_SEPARATOR . 'eael.min.js')) {
                $js_file = $this->asset_url . DIRECTORY_SEPARATOR . 'eael.min.js';
            } else {
                $js_file = $this->plugin_url . DIRECTORY_SEPARATOR . 'assets/front-end/js/eael.min.js';
            }

            if (file_exists($this->asset_path . DIRECTORY_SEPARATOR . 'eael.min.css')) {
                $css_file = $this->asset_url . DIRECTORY_SEPARATOR . 'eael.min.css';
            } else {
                $css_file = $this->plugin_url . DIRECTORY_SEPARATOR . 'assets/front-end/css/eael.min.css';
            }

            wp_enqueue_script(
                'eael-backend',
                $js_file,
                ['jquery'],
                $this->plugin_version,
                true
            );

            wp_enqueue_style(
                'eael-editor-css',
                $this->plugin_url . DIRECTORY_SEPARATOR . 'assets/front-end/css/eael-editor.css',
                false,
                $this->plugin_version
            );

            wp_enqueue_style(
                'eael-backend',
                $css_file,
                false,
                $this->plugin_version
            );

            // localize script
            wp_localize_script('eael-backend', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        } else if (!is_admin() && is_singular()) {
            $post_id = get_the_ID();

            if (file_exists($this->asset_path . DIRECTORY_SEPARATOR . $post_id . '.min.js')) {
                $js_file = $this->asset_url . DIRECTORY_SEPARATOR . $post_id . '.min.js';
                error_log('found');
            } else {
                $js_file = $this->plugin_url . DIRECTORY_SEPARATOR . 'assets/front-end/js/eael.min.js';
                $this->generate_post_scripts($post_id);
                error_log('nfound');
            }

            if (file_exists($this->asset_path . DIRECTORY_SEPARATOR . $post_id . '.min.css')) {
                $css_file = $this->asset_url . DIRECTORY_SEPARATOR . $post_id . '.min.css';
            } else {
                $css_file = $this->plugin_url . DIRECTORY_SEPARATOR . 'assets/front-end/css/eael.min.css';
                $this->generate_post_scripts($post_id);
            }

            wp_enqueue_script(
                'eael-front-end',
                $js_file,
                ['jquery'],
                $this->plugin_version,
                true
            );

            wp_enqueue_style(
                'eael-front-end',
                $css_file,
                false,
                $this->plugin_version
            );

            // localize script
            wp_localize_script('eael-front-end', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        }
    }
}

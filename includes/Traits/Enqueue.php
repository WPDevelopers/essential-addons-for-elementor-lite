<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Essential_Addons_Elementor\Classes\Helper as EnqueueHelper;

trait Enqueue
{
    public function before_enqueue_styles($widgets)
    {
        // Compatibility: Gravity forms
        if (in_array('gravity-form', $widgets) && class_exists('GFCommon')) {
            foreach (EnqueueHelper::get_gravity_form_list() as $form_id => $form_name) {
                if ($form_id != '0') {
                    gravity_form_enqueue_scripts($form_id);
                }
            }
        }

        // Compatibility: WPforms
        if (in_array('wpforms', $widgets) && function_exists('wpforms')) {
            wpforms()->frontend->assets_css();
        }

        // Compatibility: Caldera forms
        if (in_array('caldera-form', $widgets) && class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Compatibility: Fluent forms
        if (in_array('fluentform', $widgets) && defined('FLUENTFORM')) {
            wp_register_style(
                'fluent-form-styles',
                WP_PLUGIN_URL . '/fluentform/public/css/fluent-forms-public.css',
                false,
                FLUENTFORM_VERSION
            );

            wp_register_style(
                'fluentform-public-default',
                WP_PLUGIN_URL . '/fluentform/public/css/fluentform-public-default.css',
                false,
                FLUENTFORM_VERSION
            );
        }

        // Compatibility: reCaptcha with login/register
        if (in_array('login-register', $widgets) && $site_key = get_option('eael_recaptcha_sitekey')) {
            wp_register_script('eael-recaptcha', "https://www.google.com/recaptcha/api.js?render=explicit", false, EAEL_PLUGIN_VERSION, false);
        }
    }

    public function enqueue_scripts()
    {
        if (!apply_filters('eael/active_plugins', 'elementor/elementor.php')) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        if ($this->uid === null) {
            return;
        }

        // register fontawesome as fallback
        wp_register_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_style(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
            false,
            EAEL_PLUGIN_VERSION
        );

        // register reading progress assets
        wp_register_style(
            'eael-reading-progress',
            EAEL_PLUGIN_URL . 'assets/front-end/css/view/reading-progress.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'eael-reading-progress',
            EAEL_PLUGIN_URL . 'assets/front-end/js/view/reading-progress.min.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

        // register Table of contents assets
        wp_register_style(
            'eael-table-of-content',
            EAEL_PLUGIN_URL . 'assets/front-end/css/view/table-of-content.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'eael-table-of-content',
            EAEL_PLUGIN_URL . 'assets/front-end/js/view/table-of-content.min.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

        // localize object
        $this->localize_objects = apply_filters('eael/localize_objects', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('essential-addons-elementor'),
        ]);

        // edit mode
        if ($this->is_edit_mode()) {
            $elements = $this->get_settings();

            // if no widget in page, return
            if (empty($elements)) {
                return;
            }

            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $elements);

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings = $this->generate_strings($elements, 'edit', 'css');
            } else {
                // generate editor style
                $this->generate_script($elements, 'edit', 'css');

                // enqueue
                wp_enqueue_style(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.css'),
                    false,
                    time()
                );
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $elements);

            // js
            if (get_option('eael_js_print_method') == 'internal') {
                $this->js_strings = $this->generate_strings($elements, 'edit', 'js');
            } else {
                // generate editor script
                $this->generate_script($elements, 'edit', 'js');

                // enqueue
                wp_enqueue_script(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.js'),
                    ['jquery'],
                    time(),
                    true
                );

                // localize
                wp_localize_script($this->uid, 'localize', $this->localize_objects);
            }
        }

        // view mode
        if ($this->is_preview_mode()) {
            if ($this->request_requires_update) {
                $elements = $this->get_settings();
            } else {
                $elements = get_transient($this->uid . '_elements');
            }

            // if no widget in page, return
            if (empty($elements)) {
                return;
            }

            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $elements);

            // css
            if ($this->request_requires_update || get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings = $this->generate_strings($elements, 'view', 'css');
            } else {
                // generate script if not exists
                if (!$this->has_assets_files($this->uid, 'css')) {
                    $this->generate_script($elements, 'view', 'css');
                }

                // enqueue
                wp_enqueue_style(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.css'),
                    false,
                    time()
                );
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $elements);

            // js
            if ($this->request_requires_update || get_option('eael_js_print_method') == 'internal') {
                $this->js_strings = $this->generate_strings($elements, 'view', 'js');
            } else {
                // generate script if not exists
                if (!$this->has_assets_files($this->uid, 'js')) {
                    $this->generate_script($elements, 'view', 'js');
                }

                // enqueue
                wp_enqueue_script(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.js'),
                    ['jquery'],
                    time(),
                    true
                );

                // localize script
                wp_localize_script($this->uid, 'localize', $this->localize_objects);
            }
        }
    }

    // editor styles
    public function editor_enqueue_scripts()
    {
        // ea icon font
        wp_enqueue_style(
            'ea-icon',
            $this->safe_url(EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css'),
            false
        );

        // editor style
        wp_enqueue_style(
            'eael-editor',
            $this->safe_url(EAEL_PLUGIN_URL . 'assets/admin/css/editor.css'),
            false
        );
    }

    // inline enqueue styles
    public function enqueue_inline_styles()
    {
        if ($this->is_edit_mode() || $this->is_preview_mode()) {
            if ($this->css_strings) {
                echo '<style id="' . $this->uid . '">' . $this->css_strings . '</style>';
            }
        }
    }

    // inline enqueue scripts
    public function enqueue_inline_scripts()
    {
        // view/edit mode mode
        if ($this->is_edit_mode() || $this->is_preview_mode()) {
            if ($this->js_strings) {
                echo '<script>var localize =' . json_encode($this->localize_objects) . '</script>';
                echo '<script>' . $this->js_strings . '</script>';
            }
        }
    }
}

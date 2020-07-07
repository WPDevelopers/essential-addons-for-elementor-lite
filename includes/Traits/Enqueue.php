<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Enqueue
{

    public function enqueue_template_scripts($css_file)
    {
        if (!Plugin::$instance->db->is_built_with_elementor($css_file->get_post_id())) {
            return;
        }

        if (Plugin::$instance->preview->is_preview_mode()) {
            return;
        }

        if (Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        $post_id = (int) $css_file->get_post_id();

        // loaded template stack
        $this->loaded_templates[] = $post_id;

        // generate post script
        $widgets = $this->parse_widgets($post_id);

        // if no widget in page, return
        if (empty($widgets)) {
            return;
        }

        // Compatibility: Gravity forms
        if (isset($widgets['gravity-form']) && class_exists('GFCommon')) {
            foreach ($this->eael_select_gravity_form() as $form_id => $form_name) {
                if ($form_id != '0') {
                    gravity_form_enqueue_scripts($form_id);
                }
            }
        }

        // Compatibility: WPforms
        if (isset($widgets['wpforms']) && function_exists('wpforms')) {
            wpforms()->frontend->assets_css();
        }

        // Compatibility: Caldera forms
        if (isset($widgets['caldera-form']) && class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Compatibility: Fluent forms
        if (isset($widgets['fluentform']) && defined('FLUENTFORM')) {
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

        // Compatibility: Ninja forms
        if (isset($widgets['ninja-form']) && class_exists('\Ninja_Forms') && class_exists('\NF_Display_Render')) {
            add_action('elementor/preview/enqueue_styles', function () {
                ob_start();
                \NF_Display_Render::localize(0);
                ob_clean();

                wp_add_inline_script('nf-front-end', 'var nfForms = nfForms || [];');
            });
        }

        // run hook before enqueue script
        do_action('eael/before_single_enqueue_scripts', $widgets);

        // css
        if (get_option('elementor_css_print_method') == 'internal') {
            $css_strings = $this->generate_strings($post_id, $widgets, 'view', 'css');

            echo '<style id="eael-post-' . $post_id . '">' . $css_strings . '</style>';
        } else {
            // generate post style
            $this->generate_post_script($post_id, $widgets, 'css');

            // enqueue
            wp_enqueue_style(
                'eael-post-' . $post_id,
                $this->safe_protocol(EAEL_ASSET_URL . '/post-' . $post_id . '.min.css'),
                false,
                time()
            );
        }

        // js
        if (get_option('eael_js_print_method', 'external') == 'internal') {
            $this->js_strings[$post_id] = $this->generate_strings($post_id, $widgets, 'view', 'js');
        } else {
            // generate post script
            $this->generate_post_script($post_id, $widgets, 'js');

            wp_enqueue_script(
                'eael-post-' . $post_id,
                $this->safe_protocol(EAEL_ASSET_URL . '/post-' . $post_id . '.min.js'),
                ['jquery'],
                time(),
                true
            );

            // localize script
            wp_localize_script('eael-post-' . $post_id, 'localize', $this->localize_objects);
        }
    }

    public function enqueue_scripts()
    {
        if ($this->is_running_background()) {
            return;
        }

        // register fontawesome as fallback
        wp_register_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            false,
            time()
        );

        wp_register_style(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
            false,
            time()
        );

        wp_register_script(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
            false,
            time()
        );

        // localize object
        $this->localize_objects = apply_filters('eael/localize_objects', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('essential-addons-elementor'),
        ]);

        // enqueue
        if (Plugin::$instance->preview->is_preview_mode()) {
            $widgets = $this->get_settings();

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings['all'] = $this->generate_strings(null, $widgets, 'edit', 'css');
            } else {
                // generate editor style
                $this->generate_editor_script($widgets, 'css');

                // enqueue
                wp_enqueue_style(
                    'eael-edit',
                    $this->safe_protocol(EAEL_ASSET_URL . '/eael.min.css'),
                    false,
                    time()
                );
            }

            // js
            if (get_option('eael_js_print_method', 'external') == 'internal') {
                $this->js_strings['all'] = $this->generate_strings(null, $widgets, 'edit', 'js');
            } else {
                // generate editor script
                $this->generate_editor_script($widgets, 'js');

                // enqueue
                wp_enqueue_script(
                    'eael-edit',
                    $this->safe_protocol(EAEL_ASSET_URL . '/eael.min.js'),
                    ['jquery'],
                    time(),
                    true
                );

                // localize
                wp_localize_script('eael-edit', 'localize', $this->localize_objects);
            }
        }
    }

    // editor styles
    public function editor_enqueue_scripts()
    {
        wp_enqueue_style(
            'eael-editor',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/admin/css/editor.css'),
            false
        );

        // ea icon font
        wp_enqueue_style(
            'ea-icon',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css'),
            false
        );
    }

    // inline enqueue styles
    public function enqueue_inline_styles()
    {
        if ($this->css_strings) {
            foreach ($this->css_strings as $css_string) {
                echo '<style>' . $css_string . '</style>';
            }
        }
    }
    
    // inline enqueue styles
    public function enqueue_inline_scripts()
    {
        if ($this->js_strings) {
            // localize scripts for once
            echo '<script>var localize =' . json_encode($this->localize_objects) . '</script>';

            foreach ($this->js_strings as $js_string) {
                echo '<script>' . $js_string . '</script>';
            }
        }
    }
}

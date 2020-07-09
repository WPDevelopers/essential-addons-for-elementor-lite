<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Enqueue
{
    public function before_enqueue_styles($widgets)
    {
        // Compatibility: Gravity forms
        if (in_array('gravity-form', $widgets) && class_exists('GFCommon')) {
            foreach ($this->eael_select_gravity_form() as $form_id => $form_name) {
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

        // Compatibility: Ninja forms
        if (in_array('ninja-form', $widgets) && class_exists('\Ninja_Forms') && class_exists('\NF_Display_Render')) {
            add_action('elementor/preview/enqueue_styles', function () {
                ob_start();
                \NF_Display_Render::localize(0);
                ob_clean();

                wp_add_inline_script('nf-front-end', 'var nfForms = nfForms || [];');
            });
        }
    }

    public function enqueue_template_scripts($css_file)
    {
        $post_id = (int) $css_file->get_post_id();

        if (!Plugin::$instance->db->is_built_with_elementor($post_id)) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        if ($this->is_edit_mode()) {
            return;
        }

        if ($this->is_preview_mode()) {
            // loaded template stack
            $this->loaded_templates[] = $post_id;

            // parse widgets from post
            $widgets = $this->parse_widgets($post_id);
            $widgets_to_load = array_diff($widgets, $this->loaded_widgets);

            // if no widget in page, return
            if (empty($widgets) || empty($widgets_to_load)) {
                return;
            }

            // loaded widgets stack
            $this->loaded_widgets = array_filter(array_unique(array_merge($this->loaded_widgets, $widgets)));

            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $widgets_to_load);

            $file_name = implode('-', $this->loaded_templates);

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                echo '<style id="eael-post-' . $file_name . '">' . $this->generate_strings($this->loaded_widgets, 'view', 'css') . '</style>';
            } else {
                $this->generate_script($this->loaded_widgets, 'view', 'css');

                // enqueue
                wp_enqueue_style(
                    'eael-post-' . $file_name,
                    $this->safe_protocol(EAEL_ASSET_URL . '/post-' . $file_name . '.min.css'),
                    false,
                    time()
                );
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $widgets_to_load);
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
        if ($this->is_edit_mode()) {
            $widgets = $this->get_settings();

            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $widgets);

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings = $this->generate_strings($widgets, 'edit', 'css');
            } else {
                // generate editor style
                $this->generate_script($widgets, 'edit', 'css');

                // enqueue
                wp_enqueue_style(
                    'eael-edit',
                    $this->safe_protocol(EAEL_ASSET_URL . '/eael.min.css'),
                    false,
                    time()
                );
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $widgets);

            // js
            if (get_option('eael_js_print_method') == 'internal') {
                $this->js_strings = $this->generate_strings($widgets, 'edit', 'js');
            } else {
                // generate editor script
                $this->generate_script($widgets, 'edit', 'js');

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
        if ($this->is_edit_mode()) {
            if ($this->css_strings) {
                echo '<style id="eael-edit">' . $this->css_strings . '</style>';
            }
        }
    }

    // inline enqueue styles
    public function enqueue_inline_scripts()
    {

        // edit mode
        if ($this->is_edit_mode()) {
            if ($this->js_strings) {
                echo '<script>var localize =' . json_encode($this->localize_objects) . '</script>';
                echo '<script>' . $this->js_strings . '</script>';
            }
        }

        // view mode
        if ($this->is_preview_mode()) {
            if ($this->loaded_templates && $this->loaded_widgets) {
                // js
                if (get_option('eael_js_print_method') == 'internal') {
                    // localize scripts for once
                    echo '<script>var localize =' . json_encode($this->localize_objects) . '</script>';
                    echo '<script>' . $this->generate_strings($this->loaded_widgets, 'view', 'js') . '</script>';
                } else {
                    // generate post script
                    $this->generate_script($this->loaded_widgets, 'view', 'js');

                    $file_name = implode('-', $this->loaded_templates);

                    wp_enqueue_script(
                        'eael-post-' . $file_name,
                        $this->safe_protocol(EAEL_ASSET_URL . '/post-' . $file_name . '.min.js'),
                        ['jquery'],
                        time(),
                        true
                    );

                    // localize script
                    wp_localize_script('eael-post-' . $file_name, 'localize', $this->localize_objects);
                }
            }
        }
    }
}

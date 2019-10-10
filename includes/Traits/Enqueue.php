<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

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
            }
        }

        // WPforms compatibility
        if (function_exists('wpforms')) {
            wpforms()->frontend->assets_css();
        }

        // Caldera forms compatibility
        if (class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        if( defined('FLUENTFORM') ) {
            wp_enqueue_style(
                'fluent-form-styles',
                WP_PLUGIN_URL . '/fluentform/public/css/fluent-forms-public.css',
                array(),
                FLUENTFORM_VERSION
            );

            wp_enqueue_style(
                'fluentform-public-default',
                WP_PLUGIN_URL . '/fluentform/public/css/fluentform-public-default.css',
                array(),
                FLUENTFORM_VERSION
            );
        }

        // Load fontawesome as fallback
        wp_enqueue_style(
			'font-awesome-5-all',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);
        
        wp_enqueue_style(
			'font-awesome-4-shim',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
			false,
			EAEL_PLUGIN_VERSION
        );
        
        //Admin bar css
        wp_enqueue_style(
            'ea-admin-bar',
            EAEL_PLUGIN_URL . 'assets/admin/css/admin-bar.css',
            false,
            EAEL_PLUGIN_VERSION
        );
        
        wp_enqueue_script(
			'font-awesome-4-shim',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
			false,
			EAEL_PLUGIN_VERSION
        );
        
        // Admin bar js
        wp_enqueue_script(
			'ea-admin-bar',
			EAEL_PLUGIN_URL . 'assets/admin/js/admin-bar.js',
			['jquery'],
			EAEL_PLUGIN_VERSION
		);

        // My Assets
        if ($this->is_preview_mode()) {
            // generate fallback scripts
            if (!$this->has_cache_files()) {
                $this->generate_scripts($this->get_settings());
            }

            // enqueue scripts
            if ($this->has_cache_files()) {
                $css_file = EAEL_ASSET_URL . '/eael.min.css';
                $js_file = EAEL_ASSET_URL . '/eael.min.js';
            } else {
                $css_file = EAEL_PLUGIN_URL . '/assets/front-end/css/eael.min.css';
                $js_file = EAEL_PLUGIN_URL . '/assets/front-end/js/eael.min.js';
            }

            wp_enqueue_style(
                'eael-backend',
                $this->safe_protocol($css_file),
                false,
                EAEL_PLUGIN_VERSION
            );

            wp_enqueue_script(
                'eael-backend',
                $this->safe_protocol($js_file),
                ['jquery'],
                EAEL_PLUGIN_VERSION,
                true
            );

            // hook extended assets
            do_action('eael/after_enqueue_scripts', $this->has_cache_files());

            // localize script
            $this->localize_objects = apply_filters('eael/localize_objects', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ]);

            wp_localize_script('eael-backend', 'localize', $this->localize_objects);
        } else {
            if (is_singular() || is_home() || is_archive()) {
                $queried_object = get_queried_object_id();
                $post_type = (is_singular() || is_home() ? 'post' : 'term');
                $elements = (array) get_metadata($post_type, $queried_object, 'eael_transient_elements', true);

                if (empty($elements)) {
                    return;
                }
    
                $this->enqueue_protocols($post_type, $queried_object);
            }
        }
    }

    // editor styles
    public function editor_enqueue_scripts() {
        wp_enqueue_style(
            'eael-editor-css',
            $this->safe_protocol(EAEL_PLUGIN_URL . '/assets/admin/css/editor.css'),
            false,
            EAEL_PLUGIN_VERSION
        );
    }

    // rules how css will be enqueued on front-end
    protected function enqueue_protocols($post_type, $queried_object)
    {
        if ($this->has_cache_files($post_type, $queried_object)) {
            $css_file = EAEL_ASSET_URL . '/eael-' . $post_type . '-' . $queried_object . '.min.css';
            $js_file = EAEL_ASSET_URL . '/eael-' . $post_type . '-' . $queried_object . '.min.js';
        } else {
            $css_file = EAEL_PLUGIN_URL . 'assets/front-end/css/eael.min.css';
            $js_file = EAEL_PLUGIN_URL . 'assets/front-end/js/eael.min.js';
        }

        wp_enqueue_style(
            'eael-front-end',
            $this->safe_protocol($css_file),
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_enqueue_script(
            'eael-front-end',
            $this->safe_protocol($js_file),
            ['jquery'],
            EAEL_PLUGIN_VERSION,
            true
        );

        // hook extended assets
        do_action('eael/after_enqueue_scripts', $this->has_cache_files($post_type, $queried_object));

        // localize script
        $this->localize_objects = apply_filters('eael/localize_objects', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('essential-addons-elementor'),
        ]);

        wp_localize_script('eael-front-end', 'localize', $this->localize_objects);
    }
}

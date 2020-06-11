<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Enqueue
{

    public function enqueue_scripts_new($css_file)
    {
        $document = Plugin::$instance->documents->get($css_file->get_post_id());
        $document_elements = $document->get_elements_data();
        $document_settings = $document->get_settings('eael_custom_js');

        $widgets = $this->collect_recursive_elements_new($document_elements);

        $replace = [
            'eicon-woocommerce' => 'eael-product-grid',
            'eael-countdown' => 'eael-count-down',
            'eael-creative-button' => 'eael-creative-btn',
            'eael-team-member' => 'eael-team-members',
            'eael-testimonial' => 'eael-testimonials',
            'eael-weform' => 'eael-weforms',
            'eael-cta-box' => 'eael-call-to-action',
            'eael-dual-color-header' => 'eael-dual-header',
            'eael-pricing-table' => 'eael-price-table',
            'eael-filterable-gallery' => 'eael-filter-gallery',
            'eael-one-page-nav' => 'eael-one-page-navigation',
            'eael-interactive-card' => 'eael-interactive-cards',
            'eael-image-comparison' => 'eael-img-comparison',
            'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
            'eael-google-map' => 'eael-adv-google-map',
            'eael-instafeed' => 'eael-instagram-gallery',
        ];
        $widgets = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? str_replace(['eael-'], [''], $val) : null);
        }, $widgets);

        $widgets = array_filter(array_unique($widgets));

        $old_widgets = get_post_meta($css_file->get_post_id(), 'eael_transient_elements', true);

        if ($old_widgets === '') {
            $old_widgets = [];
        }

        // print_r($old_widgets);

        // sort two arr for compare
        sort($widgets);
        sort($old_widgets);

        if ($old_widgets != $widgets) {
            update_post_meta($css_file->get_post_id(), 'eael_transient_elements', $widgets);

            if (!empty($widgets)) {
                // generate cache files
                $this->generate_scripts($widgets, $css_file->get_post_id(), 'view');
            }
        }

        // if no elements, remove cache files
        if (empty($widgets)) {
            $this->remove_files($css_file->get_post_id());

            return;
        }

        // if no cache files, generate new
        if (!$this->has_cache_files($css_file->get_post_id())) {
            $this->generate_scripts($widgets, $css_file->get_post_id(), 'view');
        }

        // if (!EAEL_DEV_MODE) {
            // enqueue
            wp_enqueue_style(
                'eael-cache-view',
                $this->safe_protocol(EAEL_ASSET_URL . '/' . $css_file->get_post_id() . '.min.css'),
                false,
                time()
            );

            wp_enqueue_script(
                'eael-cache-view',
                $this->safe_protocol(EAEL_ASSET_URL . '/' . $css_file->get_post_id() . '.min.js'),
                ['jquery'],
                time(),
                true
            );
        // }

        // localize script
        // $this->localize_objects = apply_filters('eael/localize_objects', [
        //     'ajaxurl' => admin_url('admin-ajax.php'),
        //     'nonce' => wp_create_nonce('essential-addons-elementor'),
        // ]);

        // wp_localize_script('eael-cache-view', 'localize', $this->localize_objects);

    }

    public function enqueue_scripts()
    {
        // Register our styles
        wp_register_style(
            'eael-lib-view',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/css/lib-view/lib-view.min.css'),
            false,
            time()
        );

        wp_register_style(
            'eael-lib-edit',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/css/lib-edit/lib-edit.min.css'),
            false,
            time()
        );

        wp_register_style(
            'eael-view',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/css/view/view.min.css'),
            false,
            time()
        );

        // Register our scripts
        wp_register_script(
            'eael-lib-view',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/js/lib-view/lib-view.min.js'),
            ['jquery'],
            time(),
            true
        );

        wp_register_script(
            'eael-lib-edit',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/js/lib-edit/lib-edit.min.js'),
            ['jquery'],
            time(),
            true
        );

        wp_register_script(
            'eael-view',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/js/view/view.min.js'),
            ['jquery'],
            time(),
            true
        );

        wp_register_script(
            'eael-edit',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/front-end/js/edit/edit.min.js'),
            ['jquery'],
            time(),
            true
        );

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

        // Fluent forms compatibility
        if (defined('FLUENTFORM')) {
            wp_register_style(
                'fluent-form-styles',
                WP_PLUGIN_URL . '/fluentform/public/css/fluent-forms-public.css',
                array(),
                FLUENTFORM_VERSION
            );

            wp_register_style(
                'fluentform-public-default',
                WP_PLUGIN_URL . '/fluentform/public/css/fluentform-public-default.css',
                array(),
                FLUENTFORM_VERSION
            );
        }

        if (class_exists('\Ninja_Forms') && class_exists('\NF_Display_Render')) {
            add_action('elementor/preview/enqueue_styles', function () {
                ob_start();
                \NF_Display_Render::localize(0);
                ob_clean();

                wp_add_inline_script('nf-front-end', 'var nfForms = nfForms || [];');
            });
        }

        // Load fontawesome as fallback
        if (apply_filters('eael/pro_enabled', false)) {
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
        }

        // admin bar
        if (is_admin_bar_showing()) {
            wp_enqueue_style(
                'ea-admin-bar',
                EAEL_PLUGIN_URL . 'assets/admin/css/admin-bar.css',
                false,
                EAEL_PLUGIN_VERSION
            );

            wp_enqueue_script(
                'ea-admin-bar',
                EAEL_PLUGIN_URL . 'assets/admin/js/admin-bar.js',
                ['jquery'],
                EAEL_PLUGIN_VERSION
            );
        }

        // My Assets
        if ($this->is_preview_mode()) {
            // enqueue scripts
            if ($this->has_cache_files() && !EAEL_DEV_MODE) {
                // enqueue
                wp_enqueue_style(
                    'eael-cache-edit',
                    $this->safe_protocol(EAEL_ASSET_URL . '/eael.min.css'),
                    false,
                    EAEL_PLUGIN_VERSION
                );

                wp_enqueue_script(
                    'eael-cache-edit',
                    $this->safe_protocol(EAEL_ASSET_URL . '/eael.min.js'),
                    ['jquery'],
                    EAEL_PLUGIN_VERSION,
                    true
                );
            } else {
                // generate fallback scripts
                if (!EAEL_DEV_MODE) {
                    $this->generate_scripts($this->get_settings(), null, 'edit');
                }

                // enqueue
                wp_enqueue_style('eael-lib-view');
                wp_enqueue_style('eael-lib-edit');
                wp_enqueue_style('eael-view');

                wp_enqueue_script('eael-lib-view');
                wp_enqueue_script('eael-lib-edit');
                wp_enqueue_script('eael-view');
                wp_enqueue_script('eael-edit');
            }

            // hook extended assets
            do_action('eael/after_enqueue_scripts', $this->has_cache_files());

            // localize script
            $this->localize_objects = apply_filters('eael/localize_objects', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ]);

            wp_localize_script('eael-cache-edit', 'localize', $this->localize_objects);
            wp_localize_script('eael-view', 'localize', $this->localize_objects);
        } else {
            if ($this->request_uid) {
                $elements = get_transient('eael_transient_elements_' . $this->request_uid);

                if ($elements === false) {
                    return;
                }

                $this->enqueue_protocols();
            }
        }
    }

    // editor styles
    public function editor_enqueue_scripts()
    {
        wp_enqueue_style(
            'eael-editor-css',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/admin/css/editor.css'),
            false,
            time()
        );

        // ea icon font
        wp_enqueue_style(
            'ea-icon',
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css'),
            false,
            time()
        );
    }

    // rules how css will be enqueued on front-end
    protected function enqueue_protocols()
    {
        if (!EAEL_DEV_MODE && $this->has_cache_files($this->request_uid)) {
            // enqueue
            wp_enqueue_style(
                'eael-cache-view',
                $this->safe_protocol(EAEL_ASSET_URL . '/' . $this->request_uid . '.min.css'),
                false,
                time()
            );

            wp_enqueue_script(
                'eael-cache-view',
                $this->safe_protocol(EAEL_ASSET_URL . '/' . $this->request_uid . '.min.js'),
                ['jquery'],
                time(),
                true
            );
        } else {
            // enqueue
            wp_enqueue_style('eael-lib-view');
            wp_enqueue_style('eael-view');

            wp_enqueue_script('eael-lib-view');
            wp_enqueue_script('eael-view');
        }

        // hook extended assets
        do_action('eael/after_enqueue_scripts', $this->has_cache_files($this->request_uid));

        // localize script
        $this->localize_objects = apply_filters('eael/localize_objects', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('essential-addons-elementor'),
        ]);

        wp_localize_script('eael-cache-view', 'localize', $this->localize_objects);
        wp_localize_script('eael-view', 'localize', $this->localize_objects);
    }
}

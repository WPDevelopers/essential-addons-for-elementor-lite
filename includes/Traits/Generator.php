<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Core\Settings\Manager as Settings_Manager;
use \Elementor\Plugin;

trait Generator
{
    /**
     * Parse widgets from page data
     *
     * @since 3.0.0
     */
    public function parse_widgets($post_id)
    {
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
        $global_settings = get_option('eael_global_settings');
        $document = Plugin::$instance->documents->get($post_id);
        $widgets = $this->collect_recursive_elements_new($document->get_elements_data());
        $widgets = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? str_replace(['eael-'], [''], $val) : null);
        }, $widgets);

        // collect extension
        if ($document->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
            $widgets[] = 'eael-reading-progress';
        }

        if ($document->get_settings('eael_ext_table_of_content') == 'yes' || isset($global_settings['eael_ext_table_of_content']['enabled'])) {
            $widgets[] = 'eael-table-of-content';
        }

        return array_filter(array_unique($widgets));
    }

    public function collect_recursive_elements_new($elements)
    {
        $collections = [];

        foreach ($elements as $element) {
            if (isset($element['elType']) && $element['elType'] == 'widget') {
                if ($element['widgetType'] === 'global') {
                    $document = Plugin::$instance->documents->get($element['templateID']);
                    $collections = array_merge($collections, $this->collect_recursive_elements_new($document->get_elements_data()));
                } else {
                    $collections[] = $element['widgetType'];
                }
            }

            if (!empty($element['elements'])) {
                $collections = array_merge($collections, $this->collect_recursive_elements_new($element['elements']));
            }
        }

        return $collections;
    }

    /**
     * Combine files into one
     *
     * @since 3.0.1
     */
    public function combine_files($paths = array(), $file = 'eael.min.css')
    {
        $output = '';
        $page_settings_manager = Settings_Manager::get_settings_managers('page');
        $page_settings_model = $page_settings_manager->get_model(get_the_ID());

        // $custom_js = $document->get_settings('eael_custom_js');

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
        }

        if (pathinfo($file, PATHINFO_EXTENSION) === 'js' && $page_settings_model->get_settings('eael_custom_js_print_method') == 'external') {
            $output .= $page_settings_model->get_settings('eael_custom_js');
        }

        return file_put_contents($this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file), $output);
    }

    /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function generate_dependency(array $elements, $type, $context)
    {
        $lib = ['view' => [], 'edit' => []];
        $self = ['general' => [], 'view' => [], 'edit' => []];

        if ($type == 'js') {
            $self['general'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/view/general.min.js';
            $self['edit'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/promotion.min.js';
        } else if ($type == 'css') {
            $self['view'][] = EAEL_PLUGIN_PATH . "assets/front-end/css/view/general.min.css";
        }

        foreach ($elements as $element) {
            if (isset($this->registered_elements[$element])) {
                if (!empty($this->registered_elements[$element]['dependency'][$type])) {
                    foreach ($this->registered_elements[$element]['dependency'][$type] as $file) {
                        ${$file['type']}[$file['context']][] = $file['file'];
                    }
                }
            } elseif (isset($this->registered_extensions[$element])) {
                if (!empty($this->registered_extensions[$element]['dependency'][$type])) {
                    foreach ($this->registered_extensions[$element]['dependency'][$type] as $file) {
                        ${$file['type']}[$file['context']][] = $file['file'];
                    }
                }
            }
        }

        if ($context == 'view') {
            return array_unique(array_merge($lib['view'], $self['general'], $self['view']));
        }

        return array_unique(array_merge($lib['view'], $lib['edit'], $self['general'], $self['edit'], $self['view']));
    }

    /**
     * Generate single post script.
     *
     * @since 3.0.0
     */
    public function generate_post_scripts($post_id)
    {
        $widgets = $this->parse_widgets($post_id);
        $old_widgets = get_post_meta($post_id, 'eael_transient_elements', true);

        if ($old_widgets === '') {
            $old_widgets = [];
        }

        // sort two arr for compare
        sort($widgets);
        sort($old_widgets);

        // if page updated, generate assets
        if ($old_widgets != $widgets) {
            update_post_meta($post_id, 'eael_transient_elements', $widgets);

            // generate cache files
            if (!empty($widgets)) {
                $this->generate_scripts($widgets, 'eael-post-' . $post_id, 'view');
            }
        }

        // if no elements, remove cache files
        if (empty($widgets)) {
            $this->remove_files($post_id);
        } else {
            // if no cache files, generate new
            if (!$this->has_cache_files($post_id)) {
                $this->generate_scripts($widgets, 'eael-post-' . $post_id, 'view');
            }
        }

        return $widgets;
    }

    /**
     * Generate scripts and minify.
     *
     * @since 3.0.0
     */
    public function generate_scripts($widgets, $file_name, $context)
    {
        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // collect library scripts & styles
        $js_paths = $this->generate_dependency($widgets, 'js', $context);
        $css_paths = $this->generate_dependency($widgets, 'css', $context);

        // combine files
        $this->combine_files($css_paths, ($file_name ? $file_name : 'eael') . '.min.css');
        $this->combine_files($js_paths, ($file_name ? $file_name : 'eael') . '.min.js');
    }

    /**
     * Check if cache files exists
     *
     * @since 3.0.0
     */
    public function has_cache_files($uid = null)
    {
        $css_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.css';
        $js_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.js';

        if (is_readable($this->safe_path($css_path)) && is_readable($this->safe_path($js_path))) {
            return true;
        }

        return false;
    }

    /**
     * Generate single post scripts
     *
     * @since 3.0.0
     */
    // public function generate_frontend_scripts()
    // {
    //     if ($this->is_preview_mode()) {
    //         return;
    //     }

    //     if (!Plugin::$instance->frontend->has_elementor_in_page()) {
    //         return;
    //     }

    //     $replace = [
    //         'eicon-woocommerce' => 'eael-product-grid',
    //         'eael-countdown' => 'eael-count-down',
    //         'eael-creative-button' => 'eael-creative-btn',
    //         'eael-team-member' => 'eael-team-members',
    //         'eael-testimonial' => 'eael-testimonials',
    //         'eael-weform' => 'eael-weforms',
    //         'eael-cta-box' => 'eael-call-to-action',
    //         'eael-dual-color-header' => 'eael-dual-header',
    //         'eael-pricing-table' => 'eael-price-table',
    //         'eael-filterable-gallery' => 'eael-filter-gallery',
    //         'eael-one-page-nav' => 'eael-one-page-navigation',
    //         'eael-interactive-card' => 'eael-interactive-cards',
    //         'eael-image-comparison' => 'eael-img-comparison',
    //         'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
    //         'eael-google-map' => 'eael-adv-google-map',
    //         'eael-instafeed' => 'eael-instagram-gallery',
    //     ];
    //     $elements = array_map(function ($val) use ($replace) {
    //         if (array_key_exists($val, $replace)) {
    //             $val = $replace[$val];
    //         }
    //         return (strpos($val, 'eael-') !== false ? str_replace(['eael-'], [''], $val) : null);
    //     }, $this->transient_elements);
    //     $extensions = apply_filters('eael/section/after_render', $this->transient_extensions);
    //     $elements = array_filter(array_unique(array_merge($elements, $extensions)));
    //     $old_elements = get_transient('eael_transient_elements_' . $this->request_uid);

    //     if ($old_elements === false) {
    //         $old_elements = [];
    //     }

    //     // sort two arr for compare
    //     sort($elements);
    //     sort($old_elements);

    //     if ($old_elements != $elements) {
    //         set_transient('eael_transient_elements_' . $this->request_uid, $elements, MONTH_IN_SECONDS);

    //         if (!empty($elements)) {
    //             // load fallback assets
    //             $this->enqueue_protocols($this->request_uid);

    //             // generate cache files
    //             $this->generate_scripts($elements, $this->request_uid, 'view');
    //         }
    //     }

    //     // if no cache files, generate new
    //     if (!$this->has_cache_files($this->request_uid)) {
    //         $this->generate_scripts($elements, $this->request_uid, 'view');
    //     }

    //     // if no elements, remove cache files
    //     if (empty($elements)) {
    //         $this->remove_files($this->request_uid);
    //     }
    // }
}

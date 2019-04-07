<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \MatthiasMullie\Minify;
use \Elementor\Plugin;

trait Generator
{

    /**
     * Collect elements in a page or post
     *
     * @since 3.0.0
     */
    public function collect_transient_elements($widget) {
        $this->transient_elements[] = $widget->get_name();
    }

    /**
     * Mark post was updated
     *
     * @since 3.0.0
     */
    public function set_transient_status($post_id) {
        update_post_meta($post_id, 'eael_has_transient_elements', true);
    }
    
     /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function generate_dependency(array $elements, $type)
    {
        $paths = [];

        foreach ($elements as $element) {
            if (!empty($this->registered_elements[$element]['dependency'][$type])) {
                foreach ($this->registered_elements[$element]['dependency'][$type] as $path) {
                    $paths[] = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . $path;
                }
            }
        }

        return array_unique($paths);
    }

    /**
     * Generate scripts and minify.
     *
     * @since 3.0.0
     */
    public function generate_scripts($elements, $file_name = null)
    {
        if (empty($elements)) {
            $this->remove_files();

            return;
        }

        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // collect eael js
        $js_paths = array(
            EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/general.js',
        );
        $css_paths = array(
            EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . "assets/front-end/css/general.css",
        );

        // collect library scripts & styles
        $js_paths = array_merge($js_paths, $this->generate_dependency($elements, 'js'));
        $css_paths = array_merge($css_paths, $this->generate_dependency($elements, 'css'));

        foreach ((array) $elements as $element) {
            if (is_readable($path = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/' . $element . '/index.js')) {
                $js_paths[] = $path;
            }

            if (is_readable($path = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . "assets/front-end/css/$element.css")) {
                $css_paths[] = $path;
            }
        }

        $minifier = new Minify\JS($js_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($file_name ? $file_name : 'eael') . '.min.js', $minifier->minify());

        $minifier = new Minify\CSS($css_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($file_name ? $file_name : 'eael') . '.min.css', $minifier->minify());
    }

    /**
     * Check if cache files exists
     *
     * @since 3.0.0
     */
    public function has_cache_files($post_id = null)
    {
        $css_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_id ? 'eael-' . $post_id : 'eael') . '.min.css';
        $js_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_id ? 'eael-' . $post_id : 'eael') . '.min.js';

        if (is_readable($css_path) && is_readable($js_path)) {
            return true;
        }

        return false;
    }

    /**
     * Generate single post scripts
     *
     * @since 3.0.0
     */
    public function generate_post_scripts($query)
    {
        if (Plugin::$instance->preview->is_preview_mode()) {
            return;
        }

        if (get_post_meta($query->queried_object_id, 'eael_has_transient_elements', true) || !$this->has_cache_files($query->queried_object_id)) {
            $elements = array_map(function ($val) {
                $val = str_replace(['eael-'], [''], $val);

                return str_replace([
                    'eicon-woocommerce',
                    'countdown',
                    'creative-button',
                    'team-member',
                    'testimonial',
                    'weform',
                    'cta-box',
                    'dual-color-header',
                    'pricing-table',
                    'filterable-gallery',
                ], [
                    'product-grid',
                    'count-down',
                    'creative-btn',
                    'team-members',
                    'testimonials',
                    'weforms',
                    'call-to-action',
                    'dual-header',
                    'price-table',
                    'filter-gallery',
                ], $val);
            }, $this->transient_elements);

            $elements = array_intersect(array_keys($this->registered_elements), $elements);

            if (empty($elements)) {
                $this->remove_files($query->queried_object_id);
            } else {
                $this->generate_scripts($elements, 'eael-' . $query->queried_object_id);
                update_post_meta($query->queried_object_id, 'eael_has_transient_elements', false);
            }
        }
    }
}

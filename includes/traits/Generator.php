<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \MatthiasMullie\Minify;

trait Generator
{

    /**
     * Collect elements in a page or post
     *
     * @since 3.0.0
     */
    public function collect_transient_elements($widget)
    {
        $this->transient_elements[] = $widget->get_name();
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
    public function has_cache_files($post_type = null, $post_id = null)
    {
        $css_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_type ? 'eael-' . $post_type : 'eael') . ($post_id ? '-' . $post_id : '') . '.min.css';
        $js_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_type ? 'eael-' . $post_type : 'eael') . ($post_id ? '-' . $post_id : '') . '.min.js';

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
    public function generate_frontend_scripts($wp_query)
    {
        if ($this->is_preview_mode()) {
            return;
        }

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

        if ($wp_query->is_singular || $wp_query->is_archive) {
            $queried_object = get_queried_object_id();
            $post_type = ($wp_query->is_singular ? 'post' : 'term');
            $old_elements = (array) get_metadata($post_type, $queried_object, 'eael_transient_elements', true);

            // sort two arr for compare
            sort($elements);
            sort($old_elements);

            if ($old_elements != $elements) {
                update_metadata($post_type, $queried_object, 'eael_transient_elements', $elements);

                // if not empty elements, regenerate cache files
                if (!empty($elements)) {
                    $this->generate_scripts($elements, 'eael-' . $post_type . '-' . $queried_object);

                    // load generated files - fallback
                    $this->enqueue_protocols($queried_object, $post_type);
                }
            }

            // if no cache files, generate new
            if (!$this->has_cache_files($post_type, $queried_object)) {
                $this->generate_scripts($elements, 'eael-' . $post_type . '-' . $queried_object);
            }

            // if no elements, remove cache files
            if (empty($elements)) {
                $this->remove_files($queried_object);
            }
        }
    }
}

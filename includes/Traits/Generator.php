<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;
use \ReflectionClass;

trait Generator
{
    /**
     * Collect elements in a page or post
     *
     * @since 3.0.0
     */
    public function generate_request_uid()
    {
        global $wp_query;

        $uid = null;

        if ($wp_query->is_home) {
            $uid = 'home';
        } else if ($wp_query->is_search) {
            $uid = 'search';
        } elseif ($wp_query->is_404) {
            $uid = '404';
        } elseif ($wp_query->is_singular) {
            $uid = 'post-' . get_queried_object_id();
        } elseif ($wp_query->is_archive) {
            if ($wp_query->is_post_type_archive) {
                if (isset($wp_query->query['post_type'])) {
                    $uid = 'post-type-archive-' . $wp_query->query['post_type'];
                } else if (isset($wp_query->query_vars['post_type'])) {
                    $uid = 'post-type-archive-' . $wp_query->query_vars['post_type'];
                }
            } else {
                $uid = 'archive-' . $wp_query->queried_object_id;
            }
        }

        if ($uid) {
            $this->request_uid = substr(md5($uid), 0, 16);
        }
    }

    /**
     * Collect elements in a page or post
     *
     * @since 3.0.0
     */
    public function collect_transient_elements($widget)
    {
        if ($widget->get_name() === 'global') {
            $reflection = new ReflectionClass(get_class($widget));
            $protected  = $reflection->getProperty('template_data');
            $protected->setAccessible(true);

            if ($global_data = $protected->getValue($widget)) {
                $this->transient_elements = array_merge($this->transient_elements, $this->collect_recursive_elements($global_data['content']));
            }
        } else {
            $this->transient_elements[] = $widget->get_name();
        }
    }

    /**
     * Collect recursive elements
     *
     * @since 3.0.5
     */
    public function collect_recursive_elements($elements)
    {
        $collections = [];

        array_walk_recursive($elements, function ($val, $key) use (&$collections) {
            if ($key == 'widgetType') {
                $collections[] = $val;
            }
        });

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

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
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
        $lib  = ['view' => [], 'edit' => []];
        $self = ['general' => [], 'view' => [], 'edit' => []];

        if ($type == 'js') {
            $self['general'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/view/general.min.js';
            $self['edit'][]    = EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/promotion.min.js';
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
     * Generate scripts and minify.
     *
     * @since 3.0.0
     */
    public function generate_scripts($elements, $file_name = null, $context)
    {
        if (empty($elements) || EAEL_DEV_MODE) {
            return;
        }

        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // collect library scripts & styles
        $js_paths  = $this->generate_dependency($elements, 'js', $context);
        $css_paths = $this->generate_dependency($elements, 'css', $context);

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
        $js_path  = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.js';

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
    public function generate_frontend_scripts()
    {
        if ($this->is_preview_mode()) {
            return;
        }

        if (!Plugin::$instance->frontend->has_elementor_in_page()) {
            return;
        }

        $replace = [
            'eicon-woocommerce'               => 'eael-product-grid',
            'eael-countdown'                  => 'eael-count-down',
            'eael-creative-button'            => 'eael-creative-btn',
            'eael-team-member'                => 'eael-team-members',
            'eael-testimonial'                => 'eael-testimonials',
            'eael-weform'                     => 'eael-weforms',
            'eael-cta-box'                    => 'eael-call-to-action',
            'eael-dual-color-header'          => 'eael-dual-header',
            'eael-pricing-table'              => 'eael-price-table',
            'eael-filterable-gallery'         => 'eael-filter-gallery',
            'eael-one-page-nav'               => 'eael-one-page-navigation',
            'eael-interactive-card'           => 'eael-interactive-cards',
            'eael-image-comparison'           => 'eael-img-comparison',
            'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
            'eael-google-map'                 => 'eael-adv-google-map',
            'eael-instafeed'                  => 'eael-instagram-gallery',
        ];
        $elements = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? str_replace(['eael-'], [''], $val) : null);
        }, $this->transient_elements);
        $extensions   = apply_filters('eael/section/after_render', $this->transient_extensions);
        $elements     = array_filter(array_unique(array_merge($elements, $extensions)));
        $old_elements = get_transient('eael_transient_elements_' . $this->request_uid);

        if ($old_elements === false) {
            $old_elements = [];
        }

        // sort two arr for compare
        sort($elements);
        sort($old_elements);

        if ($old_elements != $elements) {
            set_transient('eael_transient_elements_' . $this->request_uid, $elements, MONTH_IN_SECONDS);

            if (!empty($elements)) {
                // load fallback assets
                $this->enqueue_protocols($this->request_uid);

                // generate cache files
                $this->generate_scripts($elements, $this->request_uid, 'view');
            }
        }

        // if no cache files, generate new
        if (!$this->has_cache_files($this->request_uid)) {
            $this->generate_scripts($elements, $this->request_uid, 'view');
        }

        // if no elements, remove cache files
        if (empty($elements)) {
            $this->remove_files($this->request_uid);
        }
    }
}

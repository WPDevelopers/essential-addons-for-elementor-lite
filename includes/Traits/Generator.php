<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;
use \Essential_Addons_Elementor\Classes\Helper;
use \ReflectionClass;

trait Generator
{

    public function uid($uid = null)
    {
        if ($uid == null) {
            if (is_front_page()) {
                $uid = 'front-page';
            } else if (is_home()) {
                $uid = 'home';
            } else if (is_post_type_archive()) {
                $post_type = get_query_var('post_type');

                if (is_array($post_type)) {
                    $post_type = reset($post_type);
                }

                $uid = 'post-type-archive-' . $post_type;
            } else if (is_category()) {
                $uid = 'category-' . get_queried_object_id();
            } else if (is_tag()) {
                $uid = 'tag-' . get_queried_object_id();
            } else if (is_tax()) {
                $uid = 'tax-' . get_queried_object_id();
            } else if (is_author()) {
                $uid = 'author-' . get_queried_object_id();
            } else if (is_date()) {
                $uid = 'date';
            } else if (is_archive()) {
                $uid = 'archive';
            } else if (is_search()) {
                $uid = 'search';
            } else if (is_404()) {
                $uid = 'error-404';
            } else if (is_single() || is_page() || is_singular()) {
                $uid = 'singular-' . get_queried_object_id();
            }
        }

        if ($uid) {
            return substr(md5($uid), 0, 9);
        }

        return $uid;
    }

    public function collect_loaded_templates($css_file)
    {
        $post_id = (int) $css_file->get_post_id();

        if ($this->is_running_background()) {
            return;
        }

        // loaded template stack
        if ($this->is_edit_mode() || $this->is_preview_mode()) {
            $this->loaded_templates[] = $post_id;
        }
    }

    public function collect_loaded_widgets($widget)
    {
        if ($widget->get_name() === 'global') {
            $reflection = new ReflectionClass(get_class($widget));
            $protected = $reflection->getProperty('template_data');
            $protected->setAccessible(true);

            if ($global_data = $protected->getValue($widget)) {
                $this->loaded_widgets = array_merge($this->loaded_widgets, $this->collect_recursive_elements($global_data['content']));
            }
        } else {
            $this->loaded_widgets[] = $widget->get_name();
        }
    }

    public function update_request_data()
    {
        if ($this->is_preview_mode()) {
            $widgets = get_transient($this->uid() . '_loaded_widgets');
            $templates = get_transient($this->uid() . '_loaded_templates');

            if ($this->loaded_templates) {
                foreach ($this->loaded_templates as $post_id) {
                    $extensons = (array) $this->parse_extensions($post_id);

                    // loaded widgets stack
                    $this->loaded_widgets = array_filter(array_unique(array_merge($this->loaded_widgets, $extensons)));
                }
            }

            $this->loaded_widgets = $this->parse_widgets($this->loaded_widgets);

            // update transient
            if ($widgets != $this->loaded_widgets) {
                set_transient($this->uid() . '_loaded_widgets', $this->loaded_widgets);
            }

            if ($templates != $this->loaded_templates) {
                set_transient($this->uid() . '_loaded_templates', $this->loaded_templates);
            }
        }
    }

    /**
     * Parse widgets from page data
     *
     * @since 3.0.0
     */
    public function parse_extensions($post_id)
    {
        if (!Plugin::$instance->db->is_built_with_elementor($post_id)) {
            return;
        }

        $global_settings = get_option('eael_global_settings');
        $document = Plugin::$instance->documents->get($post_id);
        $extensions = [];

        // collect page extensions
        if (!Helper::prevent_extension_loading($post_id)) {
            if ($document->get_settings('eael_custom_js')) {
                $extensions[] = 'eael-custom-js';
            }

            if ($document->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
                $extensions[] = 'eael-reading-progress';
            }

            if ($document->get_settings('eael_ext_table_of_content') == 'yes' || isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                $extensions[] = 'eael-table-of-content';
            }
        }

        return $extensions;
    }

    /**
     * Parse widgets from page data
     *
     * @since 3.0.0
     */
    public function parse_widgets($widgets)
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

        $widgets = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? preg_replace('/^eael-/', '', $val) : null);
        }, $widgets);

        return array_filter(array_unique($widgets));
    }

    /**
     * Traverse in element data recursively
     *
     * @since 3.0.0
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
     * Generate scripts file.
     *
     * @since 3.0.0
     */
    public function generate_script($widgets, $context, $ext)
    {
        $loaded_assets = get_transient($this->uid() . '_loaded_assets');
        $editor_updated_at = get_transient('eael_editor_updated_at');

        // check if script exists
        if ($context == 'view') {
            if ($loaded_assets == $widgets && $this->has_assets_files($this->uid(), $ext)) {
                return;
            }

            // update loaded widgets data & sync update time with editor time
            if ($ext == 'js') {
                set_transient($this->uid() . '_loaded_assets', $widgets);
                set_transient($this->uid() . '_updated_at', $editor_updated_at);
            }
        } else if ($context == 'edit') {
            if ($this->has_assets_files($this->uid('eael'), $ext)) {
                return;
            }
        }

        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // naming asset file
        $file_name = ($context == 'view' ? $this->uid() : $this->uid('eael')) . '.min.' . $ext;

        // output asset string
        $output = $this->generate_strings($widgets, $context, $ext);

        // write to file
        file_put_contents($this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name), $output);
    }

    /**
     * Generate scripts strings.
     *
     * @since 3.0.0
     */
    public function generate_strings($widgets, $context, $ext)
    {
        $output = '';
        $paths = $this->generate_dependency($widgets, $context, $ext);

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
        }

        if ($context == 'view' && $ext == 'js') {
            $templates = get_transient($this->uid() . '_loaded_templates');

            if ($templates) {
                foreach ($templates as $post_id) {
                    if (get_post_status($post_id) === false) {
                        continue;
                    }

                    $document = Plugin::$instance->documents->get($post_id);

                    if ($custom_js = $document->get_settings('eael_custom_js')) {
                        $output .= $custom_js;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function generate_dependency(array $elements, $context, $type)
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
     * Check if assets files exists
     *
     * @since 3.0.0
     */
    public function has_assets_files($uid = null, $ext = ['css', 'js'])
    {
        if (!is_array($ext)) {
            $ext = (array) $ext;
        }

        foreach ($ext as $e) {
            $path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.' . $e;

            if (!is_readable($this->safe_path($path))) {
                return false;
            }
        }

        return true;
    }
}

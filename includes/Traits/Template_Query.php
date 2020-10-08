<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Template_Query
{

    /**
     * Retrives Template name from file header.
     *
     * @array
     */
    private $template_headers = [
        'Template Name' => 'Template Name',
    ];

    /**
     * Prepare the directory name from the following widget name.
     *
     * @access private
     *
     *
     * @return  string  $widget_name
     */
    private function process_directory_name()
    {
        $widget_name = str_replace('eael-', '', $this->get_name());
        $widget_name = str_replace('-', ' ', $widget_name);
        $widget_name = ucwords($widget_name);
        $widget_name = str_replace(' ', '-', $widget_name);

        return $widget_name;
    }

    /**
     * Retrive `Theme Template Directory`
     *
     * @return string templates directory from the active theme.
     */
    private function theme_templates_dir()
    {
        $current_theme = wp_get_theme();

        $dir = sprintf(
            '%s/%s/Template/%s',
            $current_theme->theme_root,
            $current_theme->stylesheet,
            $this->process_directory_name()
        );

        if (is_dir($dir)) {
            $file = scandir($dir);
            $file = array_pop($file);

            return pathinfo($file, PATHINFO_EXTENSION) === 'php' ? $dir : false;
        }

        return false;
    }

    /**
     * Retrives the lite plugin template directory path.
     *
     * @return  string  templates directory path of lite version.
     */
    private function get_template_dir()
    {
        return \sprintf(
            '%sincludes/Template/%s',
            EAEL_PLUGIN_PATH, $this->process_directory_name()
        );
    }

    /**
     * Retrives the pro plugin template directory path.
     *
     * @return  string  templates directory path of pro version.
     */
    private function get_pro_template_dir()
    {
        // ensure is_plugin_active() exists (not on frontend)
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (!apply_filters('eael/active_plugins', 'essential-addons-elementor/essential_adons_elementor.php')) {
            return false;
        }

        return \sprintf(
            '%sincludes/Template/%s',
            EAEL_PRO_PLUGIN_PATH, $this->process_directory_name()
        );
    }

    /**
     * Collecting templates from different sources.
     *
     * @return array free templates+pro templates.
     */
    private function get_template_files()
    {
        if ($this->theme_templates_dir()) {
            return ['theme' => scandir($this->theme_templates_dir(), 1)];
        }

        $templates = $pro_templates = [];

        if (is_dir($this->get_template_dir())) {
            $templates['free'] = scandir($this->get_template_dir(), 1);
        }

        if (is_dir($this->get_pro_template_dir())) {
            $pro_templates['pro'] = scandir($this->get_pro_template_dir(), 1);
        }

        return array_merge($templates, $pro_templates);
    }

    /**
     * Retrives template list from template directory.
     *
     * @return array template list.
     */
    protected function get_template_list()
    {
        $files = [];

        if ($this->get_template_files()) {
            foreach ($this->get_template_files() as $key => $handler) {
                foreach ($handler as $handle) {
                    if (strpos($handle, '.php') !== false) {

                        if ($key === 'free') {
                            $path = sprintf('%s/%s', $this->get_template_dir(), $handle);
                        } else if ($key === 'pro') {
                            $path = sprintf('%s/%s', $this->get_pro_template_dir(), $handle);
                        } else if ($key === 'theme') {
                            $path = sprintf('%s/%s', $this->theme_templates_dir(), $handle);
                        }

                        $template_info = get_file_data($path, $this->template_headers);
                        $template_name = $template_info['Template Name'];

                        if ($template_name) {
                            $files[$template_name] = $path;
                        }
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Preparing template options for frontend select
     *
     * @return array teplate select options.
     */
    private function get_template_options()
    {
        $files = [];

        if ($this->get_template_list()) {
            foreach ($this->get_template_list() as $filename => $path) {
                $filename = \str_replace(' ', '-', $filename);

                $files[strtolower($filename)] = $path;
            }
        }

        return $files;
    }

    /**
     * Adding key value pairs in template options.
     *
     * @return array
     */
    private function template_options()
    {
        $keys = array_keys($this->get_template_options());
        $values = array_keys($this->get_template_list());

        return array_combine($keys, $values);
    }

    /**
     * Retrive template
     *
     * @param string $filename
     *
     * @return string include-able full template path.
     */
    public function get_template($filename)
    {
        if (in_array($filename, array_keys($this->get_template_options()))) {
            return $this->get_template_options()[$filename];
        }

        return false;
    }

    /**
     * Set default option in frontend select control.
     *
     * @return string first option.
     */
    public function get_default()
    {
        $dt = array_reverse($this->template_options());

        return strtolower(array_pop($dt));
    }

}

<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Template_Query
{

    private $template_headers = [
        'Template Name',
    ];


    /**
     * Retrive metadata from a file.
     *
     * @param string $file path to the file
     * @param array  $template_headers default template header list.
     */
    private function get_meta_data($file, $template_headers)
    {
        $fopen = fopen($file, 'r');
        $file_data = fread($fopen, filesize($file));
        fclose($fopen);

        $file_data = str_replace("\r", "\n", $file_data);
        $headers = $template_headers;

        foreach ($headers as $regex) {
            if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, $match) && $match[1]) {
                $headers = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
            } else {
                $headers = '';
            }
        }

        return $headers;
    }

    private function process_directory_name()
    {
        $dir = str_replace('eael-', '', $this->get_name());
        $dir = str_replace('-', ' ', $dir);
        $dir = ucwords($dir);
        $dir = str_replace(' ', '-', $dir);

        return $dir;
    }

    private function get_template_dir()
    {
        return \sprintf(
            '%sincludes/Template/%s',
            EAEL_PLUGIN_PATH, $this->process_directory_name()
        );
    }

    private function get_pro_template_dir()
    {
        if( ! is_plugin_active( 'essential-addons-elementor/essential_adons_elementor.php' ) ) return false;

        return \sprintf(
            '%sincludes/Template/%s',
            EAEL_PRO_PLUGIN_PATH, $this->process_directory_name()
        );
    }

    private function get_template_files()
    {
        if($this->theme_templates()) {
            return $this->theme_templates();
        }

        $templates = $pro_templates = [];

        if (is_dir($this->get_template_dir())) {
            $templates['free'] = scandir($this->get_template_dir(), 1);
        }

        if(is_dir($this->get_pro_template_dir())) {
            $pro_templates['pro'] = scandir($this->get_pro_template_dir(), 1);
        }

        return array_merge($templates, $pro_templates);
    }

    protected function get_template_list()
    {
        $files = [];

        if ($this->get_template_files()) {

            foreach ($this->get_template_files() as $key => $handler) {

                foreach($handler as $handle) {
                    if (strpos($handle, '.php') !== false) {

                        if($key === 'free') {
                            $path = sprintf('%s/%s', $this->get_template_dir(), $handle);
                        }

                        else if($key === 'pro') {
                            $path = sprintf('%s/%s', $this->get_pro_template_dir(), $handle);
                        }

                        $template_name = $this->get_meta_data($path, $this->template_headers);

                        if($template_name) {
                            $files[$template_name] = $path;
                        }
                    }
                }
            }

        }

        return $files;
    }

    private function get_template_options()
    {
        $files = [];

        if($this->get_template_list()) {

            foreach($this->get_template_list() as $filename => $path) {

                $filename = \str_replace(' ', '-', $filename);
                $files[strtolower($filename)] = $path;

            }

        }

        return $files;
    }

    private function template_options()
    {
        $keys = array_keys($this->get_template_options());
        $values = array_keys($this->get_template_list());

        return array_combine($keys, $values);
    }

    /**
     * Retrive `Theme Template Directory`
     * 
     * @return 
     */
    private function theme_templates()
    {
        $current_theme = wp_get_theme();
        
        $dir = sprintf(
            '%s/%s/Template/%s',
            $current_theme->theme_root,
            $current_theme->stylesheet,
            $this->process_directory_name()
        );

        if(is_dir($dir)) {
            $file = scandir($dir);
            $file = array_pop($file);

            return pathinfo($file, PATHINFO_EXTENSION) === 'php' ? $dir : false;
        }

        return false;
    }

    public function get_template($filename)
    {
        if(in_array($filename, array_keys($this->get_template_options()))) {

            return $this->get_template_options()[$filename];

        }

        return false;
    }

    public function get_default()
    {
        $dt = array_reverse($this->template_options());
        
        return strtolower(array_pop($dt));
    }

}

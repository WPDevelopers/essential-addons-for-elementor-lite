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

        if($this->theme_templates()) {
            return $this->theme_templates();
        }

        return \sprintf('%sincludes/Template/%s', EAEL_PLUGIN_PATH, $this->process_directory_name());
    }

    private function get_template_files()
    {

        if (is_dir($this->get_template_dir())) {
            return scandir($this->get_template_dir(), 1);
        }

        return false;
    }

    protected function template_list()
    {
        $files = [];

        if ($this->get_template_files()) {

            foreach ($this->get_template_files() as $handler) {
                if (strpos($handler, '.php') !== false) {

                    $path = sprintf('%s/%s', $this->get_template_dir(), $handler);

                    $template_name = $this->get_meta_data($path, $this->template_headers);

                    if($template_name) {
                        $files[str_replace('.php', '', $handler)] = $template_name;
                    }
                }
            }

        }

        return $files;
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
        return sprintf('%s/%s.php', $this->get_template_dir(), $filename);
    }

    public function get_default()
    {
        $dt = array_keys($this->template_list());
        $dt = array_reverse($dt);
        return array_pop($dt);
    }

}

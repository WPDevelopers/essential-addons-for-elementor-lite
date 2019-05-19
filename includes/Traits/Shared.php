<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Shared
{
    /**
     * Generate safe url
     *
     * @since v3.0.0
     */
    public function safe_protocol($url)
    {
        return preg_replace(['/^http:/', '/^https:/', '/(?!^)\/\//'], ['', '', '/'], $url);
    }

    /**
     * Generate safe path
     *
     * @since v3.0.0
     */
    public function safe_path($path)
    {
        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);

        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

}

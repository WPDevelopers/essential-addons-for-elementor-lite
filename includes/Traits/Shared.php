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

}

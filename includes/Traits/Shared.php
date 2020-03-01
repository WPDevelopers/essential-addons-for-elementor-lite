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
        if( is_ssl() ) {
            $url = wp_parse_url( $url );
            $url['scheme'] = 'https';
            return $this->unparse_url( $url );
        }
        return $url;
    }

    private function unparse_url($parsed_url) {
        $scheme   = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
        $port     = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
        $user     = isset( $parsed_url['user'] ) ? $parsed_url['user'] : '';
        $pass     = isset( $parsed_url['pass'] ) ? ':' . $parsed_url['pass']  : '';
        $pass     = ( $user || $pass ) ? "$pass@" : '';
        $path     = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
        $query    = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
        $fragment = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

}

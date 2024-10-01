<?php
use Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Default
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (isset($content) && isset($link)) {
    echo '<div class="swiper-slide">
        <div class="ticker-content">';
        if (!empty($link['url'])) {
            echo '<a href="' . esc_url( $link['url'] ) . '" ';

            if ($link['is_external'] == 'on') {
                echo 'target="_blank" ';
            }

            if ($link['nofollow'] == 'on') {
                echo 'rel="nofollow"';
            }

            echo '>';
        }

        echo wp_kses( $content, Helper::eael_allowed_tags() );

        if (!empty($link['url'])) {
            echo '</a>';
        }
        echo '</div>
    </div>';
} else {
    echo '<div class="swiper-slide">
        <div class="ticker-content">
            <a href="' . esc_url( get_the_permalink() ) . '" class="ticker-content-link">' . wp_kses( get_the_title(), Helper::eael_allowed_tags() ) . '</a>
        </div>
    </div>';
}

<?php

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
            echo '<a href="' . $link['url'] . '" ';

            if ($link['is_external'] == 'on') {
                echo 'target="_blank" ';
            }

            if ($link['nofollow'] == 'on') {
                echo 'rel="nofollow"';
            }

            echo '>';
        }

        echo $content;

        if (!empty($link['url'])) {
            echo '</a>';
        }
        echo '</div>
    </div>';
} else {
    echo '<div class="swiper-slide">
        <div class="ticker-content">
            <a href="' . get_the_permalink() . '" class="ticker-content-link">' . get_the_title() . '</a>
        </div>
    </div>';
}

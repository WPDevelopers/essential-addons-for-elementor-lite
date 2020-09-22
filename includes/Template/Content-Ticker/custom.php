<?php

/**
 * Template Name: Custom
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

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

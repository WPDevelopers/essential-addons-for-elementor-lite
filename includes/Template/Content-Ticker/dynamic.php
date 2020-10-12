<?php

/**
 * Template Name: Dynamic
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

echo '<div class="swiper-slide"><div class="ticker-content">
    <a href="' . get_the_permalink() . '" class="ticker-content-link">' . get_the_title() . '</a>
</div></div>';

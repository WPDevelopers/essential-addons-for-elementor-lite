<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Content_Ticker
{
    public static function render_template_($args, $settings)
    {
        $html = '';
        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $html .= '<div class="swiper-slide"><div class="ticker-content">
                    <a href="' . get_the_permalink() . '" class="ticker-content-link">' . get_the_title() . '</a>
                </div></div>';
            }
        } else {
            $html .= '<div class="swiper-slide"><a href="#" class="ticker-content">' . __('No content found!', 'essential-addons-for-elementor-lite') . '</a></div>';
        }

        wp_reset_postdata();

        return $html;
    }
}

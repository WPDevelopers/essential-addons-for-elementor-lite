<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Content_Ticker
{
    public function __render_template($posts)
    {
        $html = '';

        if (empty($posts)) {
            $html .= '<div class="swiper-slide"><a href="#" class="ticker-content">' . __('No content found!', 'essential-addons-elementor') . '</a></div>';
        } else {
            foreach ($posts as $post) {
                $html .= '<div class="swiper-slide"><div class="ticker-content">
                    <a href="' . $post->guid . '" class="ticker-content-link">' . $post->post_title . '</a>
                </div></div>';
            }
        }

        return $html;
    }
}

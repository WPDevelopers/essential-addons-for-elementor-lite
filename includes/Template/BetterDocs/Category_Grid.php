<?php

namespace Essential_Addons_Elementor\Template\BetterDocs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Category_Grid
{
    public static function render_template_($args, $settings)
    {

        $query = new \WP_Query($args);
         
        ob_start();

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                echo '<article class="eael-grid-post eael-post-grid-column" data-id="'.get_the_ID().'">
                    '.get_the_title().'
                </article>';
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
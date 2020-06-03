<?php

namespace Essential_Addons_Elementor\Template\BetterDocs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

//TODO: Add Icon Control
//TODO: Add control for changing title tag

trait Category_Grid
{
    protected static function get_doc_post_count($term_count = 0, $term_id) {
        $tax_terms = get_terms( 'doc_category', ['child_of' => $term_id]);

        foreach ($tax_terms as $tax_term) {
            $term_count += $tax_term->count;
        }
        return $term_count;
    }

    public static function render_template_($args, $settings)
    {

        $query = new \WP_Query($args);
         
        ob_start();

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $term = get_the_terms(get_the_ID(), 'doc_category');

                echo '<article class="eael-better-docs-category-grid-post" data-id="'.get_the_ID().'">
                    <div class="eael-bd-cg-header">
                        <div class="eael-docs-cat-icon"><img src="http://eael-dev.local/wp-content/plugins/betterdocs/admin/assets/img/betterdocs-cat-icon.svg" /></div>
                        <h3 class="eael-docs-cat-title">'.get_the_title().'</h3>';
                        if(!empty($term)) {
                            echo '<div class="eael-docs-item-coutn"><span>'.self::get_doc_post_count($term[0]->count, $term[0]->term_id).'</span></div>';
                        }
                echo '</div>
                </article>';
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
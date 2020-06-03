<?php

namespace Essential_Addons_Elementor\Template\BetterDocs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

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

    public static function render_template_($settings)
    {

        $query = new \WP_Query($args);

        $terms_object = array(
            'parent' => 0,
            'taxonomy' => 'doc_category',
            'order' => $settings['order'],
            'orderby' => $settings['orderby'],
            'number'    => $settings['grid_per_page'],
            'exclude'   => $settings['exclude']
        );

        if ( $settings['include'] ) {
            unset($terms_object['parent']);
            $terms_object['include'] = array_diff($settings['include'], $settings['exclude']);
            $terms_object['orderby'] = 'include';
        }
        
        $taxonomy_objects = get_terms($terms_object);

         
        ob_start();

        if($taxonomy_objects && ! is_wp_error( $taxonomy_objects )) {
            foreach($taxonomy_objects as $term) {

                $cat_icon_id = get_term_meta( $term->term_id, 'doc_category_image-id', true);

                if($cat_icon_id){
                    $cat_icon = wp_get_attachment_image( $cat_icon_id, 'thumbnail', [ 'alt' => esc_attr(get_post_meta($cat_icon_id, '_wp_attachment_image_alt', true)) ] );
                } else {
                    $cat_icon = '<img class="docs-cat-icon" src="'.BETTERDOCS_ADMIN_URL.'assets/img/betterdocs-cat-icon.svg" alt="">';
                }

                echo '<article class="eael-better-docs-category-grid-post" data-id="'.get_the_ID().'">
                    <div class="eael-bd-cg-inner">
                        <div class="eael-bd-cg-header">';
                            if($settings['show_icon']) {
                                echo '<div class="eael-docs-cat-icon">'.$cat_icon.'</div>';
                            }
                            if($settings['show_title']) {
                                echo '<'.$settings['title_tag'].' class="eael-docs-cat-title">'.$term->name.'</'.$settings['title_tag'].'>';
                            }
                            if($settings['show_count']) {
                                echo '<div class="eael-docs-item-count"><span>'.self::get_doc_post_count($term->count, $term->term_id).'</span></div>';
                            }
                        echo '</div>
                        
                        <div class="eael-bd-cg-body">';
                            $args = array(
                                'post_type'   => 'docs',
                                'post_status' => 'publish',
                                'posts_per_page'    => $settings['post_per_page'],
                                'orderby'   => $settings['post_orderby'],
                                'order' => $settings['post_order'],
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'doc_category',
                                        'field'    => 'slug',
                                        'terms'    => $term->slug,
                                        'operator'          => 'AND',
                                        'include_children'  => false
                                    ),
                                ),
                            );

                            $query = new \WP_Query( $args );
                                if ( $query->have_posts() ) :
                                    
                                    echo '<ul>';
                                    while ( $query->have_posts() ) {
                                        $query->the_post();
                                        $attr = ['href="'.get_the_permalink().'"'];
                                        echo '<li><svg class="eael-bd-cg-post-list-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="0.86em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1536 1792"><path d="M1468 380q28 28 48 76t20 88v1152q0 40-28 68t-68 28H96q-40 0-68-28t-28-68V96q0-40 28-68T96 0h896q40 0 88 20t76 48zm-444-244v376h376q-10-29-22-41l-313-313q-12-12-41-22zm384 1528V640H992q-40 0-68-28t-28-68V128H128v1536h1280zM384 800q0-14 9-23t23-9h704q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64zm736 224q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h704zm0 256q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h704z"/></svg><a '.implode(' ',$attr).'>'.get_the_title().'</a></li>';
                                    }
                                    
                                    echo '</ul>';
                                
                                endif;
                                wp_reset_query();
                                
                                // Nested category query
                                if($settings['nested_subcategory'] === 'true') {

                                    $args = array(
                                        'child_of' => $term->term_id,
                                        'order' => $settings['order'],
                                        'orderby' => $settings['orderby'],
                                    );

                                    $sub_categories = get_terms( 'doc_category', $args);

                                    if($sub_categories){
                                        
                                        foreach($sub_categories as $sub_category) {
                                            echo '<span class="docs-sub-cat-title">
                                            <svg class="toggle-arrow arrow-right" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="0.48em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 608 1280"><g transform="translate(608 0) scale(-1 1)"><path d="M595 288q0 13-10 23L192 704l393 393q10 10 10 23t-10 23l-50 50q-10 10-23 10t-23-10L23 727q-10-10-10-23t10-23l466-466q10-10 23-10t23 10l50 50q10 10 10 23z"/></g></svg>
                                            <svg class="toggle-arrow arrow-down" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="0.8em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1280"><path d="M1011 480q0 13-10 23L535 969q-10 10-23 10t-23-10L23 503q-10-10-10-23t10-23l50-50q10-10 23-10t23 10l393 393l393-393q10-10 23-10t23 10l50 50q10 10 10 23z"/></svg>
                                            <a href="#">'.$sub_category->name.'</a></span>';
                                            echo '<ul class="docs-sub-cat">';
                                            $sub_args = array(
                                                'post_type'   => 'docs',
                                                'post_status' => 'publish',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'doc_category',
                                                        'field'    => 'slug',
                                                        'terms'    => $sub_category->slug,
                                                        'operator'          => 'AND',
                                                        'include_children'  => false
                                                    ),
                                                )
                                            );
                                            
                                            $sub_args['posts_per_page'] = -1;
                                            $sub_post_query = new \WP_Query( $sub_args );
                                            if ( $sub_post_query->have_posts() ) :
                                                while ( $sub_post_query->have_posts() ) : $sub_post_query->the_post();
                                                    $sub_attr = ['href="'.get_the_permalink().'"'];
                                                    echo '<li class="sub-list"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="0.86em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1536 1792"><path d="M1468 380q28 28 48 76t20 88v1152q0 40-28 68t-68 28H96q-40 0-68-28t-28-68V96q0-40 28-68T96 0h896q40 0 88 20t76 48zm-444-244v376h376q-10-29-22-41l-313-313q-12-12-41-22zm384 1528V640H992q-40 0-68-28t-28-68V128H128v1536h1280zM384 800q0-14 9-23t23-9h704q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64zm736 224q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h704zm0 256q14 0 23 9t9 23v64q0 14-9 23t-23 9H416q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h704z"/></svg><a '.implode(' ',$sub_attr).'>'.get_the_title().'</a></li>';
                                                endwhile;
                                            endif;
                                            wp_reset_query();
                                            echo '</ul>';
                                        }
                                    }

                                }

                        echo '</div>';

                        if($settings['show_button']) {
                            echo '<a class="eael-bd-cg-button" href="'.get_term_link( $term->slug, 'doc_category' ).'">'.$settings['button_text'].'</a>';
                        }
                    echo '</div>';
                echo '</article>';
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
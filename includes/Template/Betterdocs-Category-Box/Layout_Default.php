<?php

/**
 * Template Name: Default
 * 
 */

echo '<a href="'.get_term_link( $term->slug, 'doc_category' ).'" class="eael-better-docs-category-box-post">
    <div class="eael-bd-cb-inner">';

    if($settings['show_icon']) {
        $cat_icon_id = get_term_meta( $term->term_id, 'doc_category_image-id', true);

        if($cat_icon_id){
            $cat_icon = wp_get_attachment_image( $cat_icon_id, 'thumbnail', [ 'alt' => esc_attr(get_post_meta($cat_icon_id, '_wp_attachment_image_alt', true)) ] );
        } else {
            $cat_icon = '<img src="'.BETTERDOCS_ADMIN_URL.'assets/img/betterdocs-cat-icon.svg" alt="betterdocs-category-box-icon">';
        }

        echo '<div class="eael-bd-cb-cat-icon">'.$cat_icon.'</div>';
    }

    if($settings['show_title']) {
        echo '<'.$settings['title_tag'].' class="eael-bd-cb-cat-title">'.$term->name.'</'.$settings['title_tag'].'>';
    }

    if($settings['show_count']) {
        printf('<div class="eael-bd-cb-cat-count"><span class="count-prefix">%s</span>%s<span class="count-suffix">%s</span></div>', $settings['count_prefix'], $this->eael_get_doc_post_count($term->count, $term->term_id), $settings['count_suffix']);
    }

    echo '</div>';
echo '</a>';

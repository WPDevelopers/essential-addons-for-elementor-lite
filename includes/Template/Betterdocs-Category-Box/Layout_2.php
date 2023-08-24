<?php

use \Essential_Addons_Elementor\Classes\Helper;
/**
 * Template Name: Layout 2
 *
 */

if ($default_multiple_kb) {
    if(!empty($settings['selected_knowledge_base'])){
        $button_link = str_replace('%knowledge_base%', $settings['selected_knowledge_base'], get_term_link($term->slug, 'doc_category'));
    }else{
        $button_link = str_replace('%knowledge_base%', 'non-knowledgebase', get_term_link($term->slug, 'doc_category'));
    }
} else {
    $button_link = get_term_link($term->slug, 'doc_category');
}

echo '<a href="' . $button_link . '" class="eael-better-docs-category-box-post layout__2">';
echo '<div class="eael-bd-cb-inner">';

if ($settings['show_icon']) {
    $cat_icon_id = get_term_meta($term->term_id, 'doc_category_image-id', true);

    if ($cat_icon_id) {
        $cat_icon = wp_get_attachment_image($cat_icon_id, 'thumbnail', ['alt' => esc_attr(get_post_meta($cat_icon_id, '_wp_attachment_image_alt', true))]);
    } else {
        $cat_icon = '<img src="' . EAEL_PLUGIN_URL . 'assets/front-end/img/betterdocs-cat-icon.svg" alt="betterdocs-category-box-icon">';
    }

    echo '<div class="eael-bd-cb-cat-icon__layout-2">' . $cat_icon . '</div>';
}

if ($settings['show_title']) {
    echo '<' . Helper::eael_validate_html_tag($settings['title_tag']) . ' class="eael-bd-cb-cat-title__layout-2"><span>' . $term->name . '</span></' . Helper::eael_validate_html_tag($settings['title_tag']) . '>';
}

if ($settings['show_count']) {
    printf('<div class="eael-bd-cb-cat-count__layout-2"><span class="count-inner__layout-2">%s</span></div>', Helper::get_doc_post_count($term->count, $term->term_id));
}

echo '</div>';
echo '</a>';

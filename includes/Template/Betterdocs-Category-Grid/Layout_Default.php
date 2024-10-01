<?php

use \Essential_Addons_Elementor\Classes\Helper;
/**
 * Template Name: Default
 *
 */

echo '<article class="eael-better-docs-category-grid-post" data-id="' . esc_attr( get_the_ID() ) . '">
    <div class="eael-bd-cg-inner">';
        if ($settings['show_header'] === 'true') {
            echo '<div class="eael-bd-cg-header">
                <div class="eael-bd-cg-header-inner">';
                    if ($settings['show_icon']) {

                        $cat_icon_id = get_term_meta($term->term_id, 'doc_category_image-id', true);
                        if ($cat_icon_id) {
                            $cat_icon = wp_get_attachment_image($cat_icon_id, 'thumbnail', true, ['alt' => esc_attr( get_post_meta($cat_icon_id, '_wp_attachment_image_alt', true))]);
                        } else {
                            $cat_icon = '<img src="' . EAEL_PLUGIN_URL . 'assets/front-end/img/betterdocs-cat-icon.svg" alt="betterdocs-category-grid-icon">';
                        }

                        echo '<div class="eael-docs-cat-icon">' . wp_kses( $cat_icon, Helper::eael_allowed_icon_tags() ) . '</div>';
                    }
                    $html = '';
                    if ( $settings['show_title'] ) {
                        $title_tag = Helper::eael_validate_html_tag( $settings['title_tag'] );
                        $html .= '<' . $title_tag . ' class="eael-docs-cat-title">' . $term->name . '</' . $title_tag . '>';
                    }
                    if ( $settings['show_count'] ) {
                        $html .= '<div class="eael-docs-item-count">' . Helper::get_doc_post_count( $term->count, $term->term_id ) . '</div>';
                    }

                    if( $html ) {
                        echo wp_kses( $html, Helper::eael_allowed_tags() );
                    }
                echo '</div>
            </div>';
        }

        if ($settings['show_list'] === 'true') {
            echo '<div class="eael-bd-cg-body">';

            $multiple_kb = Helper::get_betterdocs_multiple_kb_status();

            if ($multiple_kb == true) {
                $taxes = array('knowledge_base', 'doc_category');

                foreach ($taxes as $tax) {
                    $kterms = get_terms($tax);

                    if (!is_wp_error($kterms)) {
                        foreach ($kterms as $kterm) {
                            $tax_map[$tax][$kterm->slug] = $kterm->term_taxonomy_id;
                        }
                    }    
                }

                $args = array(
                    'post_type' => 'docs',
                    'post_status' => 'publish',
                    'posts_per_page' => $settings['post_per_page'],
                    'orderby' => $settings['post_orderby'],
                    'order' => $settings['post_order'],
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'doc_category',
                            'field' => 'term_taxonomy_id',
                            'operator' => 'IN',
                            'terms' => array($tax_map['doc_category'][$term->slug]),
                            'include_children' => false,
                        ),
                    ),
                );
                if(!empty($settings['selected_knowledge_base'])){
                    $args['tax_query'][] = array(
                        'taxonomy' => 'knowledge_base',
                        'field' => 'term_taxonomy_id',
                        'terms' => array($tax_map['knowledge_base'][$settings['selected_knowledge_base']]),
                        'operator' => 'IN',
                        'include_children' => false,
                    );
                }
            } else {
                $args = array(
                    'post_type' => 'docs',
                    'post_status' => 'publish',
                    'posts_per_page' => $settings['post_per_page'],
                    'orderby' => $settings['post_orderby'],
                    'order' => $settings['post_order'],
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'doc_category',
                            'field' => 'slug',
                            'terms' => $term->slug,
                            'operator' => 'AND',
                            'include_children' => false,
                        ),
                    ),
                );
            }

            $query = new \WP_Query($args);

            if ($query->have_posts()) {
                echo '<ul>';
                while ($query->have_posts()) {
                    $query->the_post();

                    echo '<li>';
                    if (isset($settings['list_icon']['value']['url']) && !empty($settings['list_icon']['value']['url'])) {
                        echo '<img class="eael-bd-cg-post-list-icon" src="' . esc_url( $settings['list_icon']['value']['url'] ) . '" />';
                    } else {
                        echo '<i class="' . esc_attr( $settings['list_icon']['value'] ) . ' eael-bd-cg-post-list-icon"></i>';
                    }
                    echo '<a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>
                                </li>';
                }

                echo '</ul>';
            }
            wp_reset_query();

            // Nested category query
            if ($settings['nested_subcategory'] === 'true') {

                $args = array(
                    'child_of' => $term->term_id,
                    'order' => $settings['order'],
                    'orderby' => $settings['orderby'],
                );

                $sub_categories = get_terms('doc_category', $args);

                if ($sub_categories) {

                    foreach ($sub_categories as $sub_category) {
                        echo '<span class="eael-bd-grid-sub-cat-title">';

                        if (isset($settings['nested_list_title_closed_icon']['value']['url']) && !empty($settings['nested_list_title_closed_icon']['value']['url'])) {
                            echo '<img class="toggle-arrow arrow-right" src="' . esc_url( $settings['nested_list_title_closed_icon']['value']['url'] ) . '" />';
                        } else {
                            echo '<i class="' . esc_attr( $settings['nested_list_title_closed_icon']['value'] ) . ' toggle-arrow arrow-right"></i>';
                        }

                        if (isset($settings['nested_list_title_open_icon']['value']['url']) && !empty($settings['nested_list_title_open_icon']['value']['url'])) {
                            echo '<img class="toggle-arrow arrow-down" src="' . esc_url( $settings['nested_list_title_open_icon']['value']['url'] ) . '" />';
                        } else {
                            echo '<i class="' . esc_attr( $settings['nested_list_title_open_icon']['value'] ) . ' toggle-arrow arrow-down"></i>';
                        }

                        echo '<a href="#">' . esc_html( $sub_category->name ) . '</a></span>';
                        echo '<ul class="docs-sub-cat-list">';
                        $sub_args = array(
                            'post_type' => 'docs',
                            'post_status' => 'publish',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'doc_category',
                                    'field' => 'slug',
                                    'terms' => $sub_category->slug,
                                    'operator' => 'AND',
                                    'include_children' => false,
                                ),
                            ),
                        );

                        $sub_args['posts_per_page'] = -1;
                        $sub_post_query = new \WP_Query($sub_args);
                        if ($sub_post_query->have_posts()):
                            while ($sub_post_query->have_posts()): $sub_post_query->the_post();
                                $sub_attr = [''];
                                echo '<li class="sub-list">';
                                if (isset($settings['list_icon']['value']['url']) && !empty($settings['list_icon']['value']['url'])) {
                                    echo '<img class="eael-bd-cg-post-list-icon" src="' . esc_url( $settings['list_icon']['value']['url'] ) . '" />';
                                } else {
                                    echo '<i class="' . esc_attr( $settings['list_icon']['value'] ) . ' eael-bd-cg-post-list-icon"></i>';
                                }
                                echo '<a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
                            endwhile;
                        endif;
                        wp_reset_query();
                        echo '</ul>';
                    }
                }

            }
            echo '</div>';
        }

        echo '<div class="eael-bd-cg-footer">';
            if ($settings['show_button']) {
                if ($default_multiple_kb) {
                    if(!empty($settings['selected_knowledge_base'])){
                        $button_link = str_replace('%knowledge_base%', $settings['selected_knowledge_base'], get_term_link($term->slug, 'doc_category'));
                    }else{
                        $button_link = str_replace('%knowledge_base%', 'non-knowledgebase', get_term_link($term->slug, 'doc_category'));
                    }
                } else {
                    $button_link = get_term_link($term->slug, 'doc_category');
                }

                echo '<a class="eael-bd-cg-button" href="' . esc_url( $button_link ) . '">';

                if ($settings['icon_position'] === 'before') {
                    if (isset($settings['button_icon']['value']['url']) && !empty($settings['button_icon']['value']['url'])) {
                        echo '<img class="eael-bd-cg-button-icon eael-bd-cg-button-icon-left" src="' . esc_url( $settings['button_icon']['value']['url'] ) . '" />';
                    } else {
                        echo '<i class="' . esc_attr( $settings['button_icon']['value'] ) . ' eael-bd-cg-button-icon eael-bd-cg-button-icon-left"></i>';
                    }
                }

                echo wp_kses( $settings['button_text'], Helper::eael_allowed_tags() );

                if ($settings['icon_position'] === 'after') {
                    if (isset($settings['button_icon']['value']['url']) && !empty($settings['button_icon']['value']['url'])) {
                        echo '<img class="eael-bd-cg-button-icon eael-bd-cg-button-icon-right" src="' . esc_url( $settings['button_icon']['value']['url'] ) . '" />';
                    } else {
                        echo '<i class="' . esc_attr( $settings['button_icon']['value'] ) . ' eael-bd-cg-button-icon eael-bd-cg-button-icon-right"></i>';
                    }
                }

                echo '</a>';
            }
        echo '</div>';
    echo '</div>';
echo '</article>';

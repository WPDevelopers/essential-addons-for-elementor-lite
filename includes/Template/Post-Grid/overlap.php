<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Overlap
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$title_tag = isset($settings['title_tag']) ? $settings['title_tag'] : 'h2';

    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
        <div class="eael-grid-post-holder">
            <div class="eael-grid-post-holder-inner">';

                if (has_post_thumbnail() && $settings['eael_show_image'] == 'yes') {

			        echo '<div class="eael-entry-media" style="background-image: url(' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']) . ')">';

			        echo '</div>';
			    }

                if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
        echo '<div class="eael-entry-wrapper">';
        if ($settings['eael_show_title']) {
            echo '<header class="eael-entry-header"><' . $title_tag . ' class="eael-entry-title">';
            echo '<a
                        class="eael-grid-post-link"
                        href="' . get_the_permalink() . '"
                        title="' . get_the_title() . '"
                        ' . ($settings['title_link_nofollow'] ? 'rel="nofollow"' : '') . '
                        ' . ($settings['title_link_target_blank'] ? 'target="_blank"' : '') . '
                        >';

            if (empty($settings['eael_title_length'])) {
                echo get_the_title();
            } else {
                echo implode(" ", array_slice(explode(" ", get_the_title()), 0, $settings['eael_title_length']));
            }
            echo '</a>';
            echo '</' . $title_tag . '></header>';
        }
        if ($settings['meta_position'] == 'meta-entry-header') {
            if ($settings['eael_show_meta']) {
                echo '<div class="eael-entry-meta">';
                if ($settings['eael_show_author'] === 'yes') {
                    echo '<span class="eael-posted-by">' . get_the_author_posts_link() . '</span>';
                }
                if ($settings['eael_show_date'] === 'yes') {
                    echo '<span class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
                }
                echo '</div>';
            }
        }

        if ($settings['eael_show_excerpt'] || $settings['eael_show_read_more_button']) {
            echo '<div class="eael-entry-content">
                        <div class="eael-grid-post-excerpt">';
            if ($settings['eael_show_excerpt']) {
                if (empty($settings['eael_excerpt_length'])) {
                    echo '<p>' . strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()) . '</p>';
                } else {
                    echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['excerpt_expanison_indicator']) . '</p>';
                }
            }

            if ($settings['eael_show_read_more_button']) {
                echo '<a
                            href="' . get_the_permalink() . '"
                            class="eael-post-elements-readmore-btn"
                            ' . ($settings['read_more_link_nofollow'] ? 'rel="nofollow"' : '') . '
                            ' . ($settings['read_more_link_target_blank'] ? 'target="_blank"' : '') . '
                            >' . esc_attr($settings['read_more_button_text']) . '</a>';
            }
            echo '</div>
                    </div>';
        }

        if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer') {
            echo '<div class="eael-entry-footer">';
            if ($settings['eael_show_avatar'] === 'yes') {
                echo '<div class="eael-author-avatar"><a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_avatar(get_the_author_meta('ID'), 96) . '</a></div>';
            }

            if ($settings['eael_show_meta']) {
                echo '<div class="eael-entry-meta">';
                if ($settings['eael_show_author'] === 'yes') {
                    echo '<span class="eael-posted-by">' . get_the_author_posts_link() . '</span>';
                }
                if ($settings['eael_show_date'] === 'yes') {
                    echo '<span class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
                }
                echo '</div>';
            }

            echo '</div>';
        }

        echo '</div>';
    }
    echo '</div>
    </div>
</article>';

<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Post_Grid
{
    public static function __render_template($args, $settings)
    {
        $query = new \WP_Query($args);
      
        ob_start();

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                echo '<article class="eael-grid-post eael-post-grid-column">
                    <div class="eael-grid-post-holder">
                        <div class="eael-grid-post-holder-inner">';
                            if (has_post_thumbnail() && $settings['eael_show_image'] == 1) {
                                echo '<div class="eael-entry-media">';
                                    if ('none' !== $settings['eael_post_grid_hover_animation']) {
                                        echo '<div class="eael-entry-overlay ' . $settings['eael_post_grid_hover_animation'] . '">';
                                            if( isset($settings['eael_post_grid_bg_hover_icon']['url']) ) {
                                                echo '<img src="'.esc_url($settings['eael_post_grid_bg_hover_icon']['url']).'" alt="'.esc_attr(get_post_meta($settings['eael_post_grid_bg_hover_icon']['id'], '_wp_attachment_image_alt', true)).'" />';
                                            }else {
                                                echo '<i class="' . $settings['eael_post_grid_bg_hover_icon'] . '" aria-hidden="true"></i>';
                                            }
                                            echo '<a href="' . get_the_permalink() . '"></a>';
                                        echo '</div>';
                                    }

                                    echo '<div class="eael-entry-thumbnail">
                                        <img src="' . esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">
                                    </div>';
                                echo '</div>';
                            }

                            if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                                echo '<div class="eael-entry-wrapper">
                                    <header class="eael-entry-header">';
                                        if ($settings['eael_show_title']) {
                                            echo '<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="' . get_the_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h2>';
                                        }

                                        if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-header') {
                                            echo '<div class="eael-entry-meta">
                                                <span class="eael-posted-by">' . get_the_author_meta( 'display_name' ) . '</span>
                                                <span class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>
                                            </div>';
                                        }
                                    echo '</header>';

                                    if ($settings['eael_show_excerpt']) {
                                        echo '<div class="eael-entry-content">
                                            <div class="eael-grid-post-excerpt">
                                                <p>' . implode(" ", array_slice(explode(" ", strip_tags(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()))), 0, $settings['eael_excerpt_length'])) . $settings['expanison_indicator'] . '</p>';
                                                if ($settings['eael_show_read_more_button']) {
                                                    echo '<a href="' . get_the_permalink() . '" class="eael-post-elements-readmore-btn">' . esc_attr($settings['read_more_button_text']) . '</a>';
                                                }
                                            echo '</div>
                                        </div>';
                                    }
                                echo '</div>';

                                if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer') {
                                    echo '<div class="eael-entry-footer">
                                        <div class="eael-author-avatar">
                                            <a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_avatar(get_the_author_meta('ID'), 96) . '</a>
                                        </div>
                                        <div class="eael-entry-meta">
                                            <div class="eael-posted-by">' . get_the_author_posts_link() . '</div>
                                            <div class="eael-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></div>
                                        </div>
                                    </div>';
                                }
                            }
                        echo '</div>
                    </div>
                </article>';
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-elementor');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
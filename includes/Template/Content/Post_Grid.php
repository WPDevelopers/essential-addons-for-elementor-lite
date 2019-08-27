<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Post_Grid
{
    public function __render_template($posts, $settings)
    {
        $html = '';

        if (empty($posts)) {
            $html .= __('No content found!', 'essential-addons-elementor');
        } else {
            foreach ($posts as $post) {
                $hover_icon = (isset($settings['__fa4_migrated']['eael_post_grid_bg_hover_icon_new']) || empty($settings['eael_post_grid_bg_hover_icon'])) ? $settings['eael_post_grid_bg_hover_icon_new']['value'] : $settings['eael_post_grid_bg_hover_icon'];
                $excerpt = implode(" ", array_slice(explode(" ", strip_tags(strip_shortcodes($post->post_excerpt ? $post->post_excerpt : $post->post_content))), 0, $settings['eael_excerpt_length']));

                $html .= '<article class="eael-grid-post eael-post-grid-column">
                    <div class="eael-grid-post-holder">
                        <div class="eael-grid-post-holder-inner">';
                            if (has_post_thumbnail($post->ID) && $settings['eael_show_image'] == 1) {
                                $html .= '<div class="eael-entry-media">';
                                    if ('none' !== $settings['eael_post_grid_hover_animation']) {
                                        $html .= '<div class="eael-entry-overlay ' . $settings['eael_post_grid_hover_animation'] . '">
                                            <i class="' . $hover_icon . '" aria-hidden="true"></i>
                                            <a href="' . esc_url($post->guid) . '"></a>
                                        </div>';
                                    }

                                    $html .= '<div class="eael-entry-thumbnail">
                                        <img src="' . esc_url(wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), $settings['image_size'])) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true)) . '">
                                    </div>';
                                $html .= '</div>';
                            }

                            if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                                $html .= '<div class="eael-entry-wrapper">
                                    <header class="eael-entry-header">';
                                        if ($settings['eael_show_title']) {
                                            $html .= '<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="' . $post->guid . '" title="' . $post->post_title . '">' . $post->post_title . '</a></h2>';
                                        }

                                        if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-header') {
                                            $html .= '<div class="eael-entry-meta">
                                                <span class="eael-posted-by">' . get_author_posts_url($post->post_author) . '</span>
                                                <span class="eael-posted-on"><time datetime="' . mysql2date(get_option('date_format'), $post->post_date) . '">' . mysql2date(get_option('date_format'), $post->post_date) . '</time></span>
                                            </div>';
                                        }
                                    $html .= '</header>';

                                    if ($settings['eael_show_excerpt']) {
                                        $html .= '<div class="eael-entry-content">
                                            <div class="eael-grid-post-excerpt">
                                                <p>' . $excerpt . '...</p>';
                                                if ($settings['eael_show_read_more_button']) {
                                                    $html .= '<a href="' . $post->guid . '" class="eael-post-elements-readmore-btn">' . esc_attr($settings['read_more_button_text']) . '</a>';
                                                }
                                            $html .= '</div>
                                        </div>';
                                    }
                                $html .= '</div>';

                                if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer') {
                                    $html .= '<div class="eael-entry-footer">
                                        <div class="eael-author-avatar">
                                            <a href="' . get_author_posts_url($post->post_author) . '">' . get_avatar($post->post_author, 96) . '</a>
                                        </div>
                                        <div class="eael-entry-meta">
                                            <div class="eael-posted-by"><a href="' . get_author_posts_url($post->post_author) . '">' . get_the_author_meta('user_nicename', $post->post_author) . '</a></div>
                                            <div class="eael-posted-on"><time datetime="' . mysql2date(get_option('date_format'), $post->post_date) . '">' . mysql2date(get_option('date_format'), $post->post_date) . '</time></div>
                                        </div>
                                    </div>';
                                }
                            }
                        $html .= '</div>
                    </div>
                </article>';
            }
        }

        return $html;
    }
}
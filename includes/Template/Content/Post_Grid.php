<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Post_Grid
{
    private static function render_template__header($settings, $style = null)
    {
        if ($style === 'two' && $settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-header') {
            self::render_template__meta_style_two($settings);
        }
        $title_tag = (isset($settings['title_tag']) ? $settings['title_tag'] : 'h2');

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
        if ($style == '' && $settings['meta_position'] == 'meta-entry-header') {
            self::render_template__meta($settings);
        }
    }
    private static function render_template__meta($settings)
    {
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

    private static function render_template__meta_style_two($settings)
    {
        echo '<div class="eael-entry-meta">';
        if ($settings['eael_show_date'] === 'yes') {
            echo '<span class="eael-meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
        }
        if ($settings['eael_show_post_terms'] === 'yes') {
            if ($settings['eael_post_terms'] === 'category') {
                $terms = get_the_category();
            }
            if ($settings['eael_post_terms'] === 'tags') {
                $terms = get_the_tags();
            }
            if (!empty($terms)) {
                $html = '<ul class="post-meta-categories">';
                $count = 0;
                foreach ($terms as $term) {
                    if ($count === intval($settings['eael_post_terms_max_length'])) {
                        break;
                    }
                    if ($count === 0) {
                        $html .= '<li class="meta-cat-icon"><i class="far fa-folder-open"></i></li>';
                    }
                    $link = ($settings['eael_post_terms'] === 'category') ? get_category_link($term->term_id) : get_tag_link($term->term_id);
                    $html .= '<li>';
                    $html .= '<a href="' . esc_url($link) . '">';
                    $html .= $term->name;
                    $html .= '</a>';
                    $html .= '</li>';
                    $count++;
                }
                $html .= '</ul>';
                echo $html;
            }
        }
        echo '</div>';
    }

    private static function render_template__footer_meta($settings)
    {
        if ($settings['eael_show_meta'] && $settings['meta_position'] == 'meta-entry-footer') {
            echo '<div class="eael-entry-footer">';
            if ($settings['eael_show_avatar'] === 'yes') {
                echo '<div class="eael-author-avatar"><a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_avatar(get_the_author_meta('ID'), 96) . '</a></div>';
            }
            self::render_template__meta($settings);
            echo '</div>';
        }
    }
    private static function render_template__excerpt($settings)
    {
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
                echo '<div class="eael-post-elements-readmore-wrap"><a
                    href="' . get_the_permalink() . '"
                    class="eael-post-elements-readmore-btn"
                    ' . ($settings['read_more_link_nofollow'] ? 'rel="nofollow"' : '') . '
                    ' . ($settings['read_more_link_target_blank'] ? 'target="_blank"' : '') . '
                    >' . esc_attr($settings['read_more_button_text']) . '</a></div>';
            }
            echo '</div>
            </div>';
        }
    }
    private static function render_template__thumbnail($settings, $style = null)
    {
        if (has_post_thumbnail() && $settings['eael_show_image'] == 'yes') {

            echo '<div class="eael-entry-media">';
            if ($style == null && $settings['eael_show_post_terms'] === 'yes') {
                echo self::get_terms_as_list($settings['eael_post_terms'], $settings['eael_post_terms_max_length']);
            }

            echo '<div class="eael-entry-overlay ' . $settings['eael_post_grid_hover_animation'] . '">';
            if (isset($settings['eael_post_grid_bg_hover_icon']['url'])) {
                echo '<img src="' . esc_url($settings['eael_post_grid_bg_hover_icon']['url']) . '" alt="' . esc_attr(get_post_meta($settings['eael_post_grid_bg_hover_icon']['id'], '_wp_attachment_image_alt', true)) . '" />';
            } else {
                echo '<i class="' . $settings['eael_post_grid_bg_hover_icon'] . '" aria-hidden="true"></i>';
            }
            echo '<a
                                        href="' . get_the_permalink() . '"
                                        ' . ($settings['image_link_nofollow'] ? 'rel="nofollow"' : '') . '
                                        ' . ($settings['image_link_target_blank'] ? 'target="_blank"' : '') . '
                                    ></a>';
            echo '</div>';

            echo '<div class="eael-entry-thumbnail">
                                <img src="' . esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">
                            </div>';
            echo '</div>';
            if ($style === 'three' && $settings['eael_show_date'] === 'yes') {
                echo '<span class="eael-meta-posted-on"><time datetime="' . get_the_date() . '"><span>' . get_the_date('d') . '</span>' . get_the_date('F') . '</time></span>';
            }
        }
    }
    public static function render_template_($args, $settings)
    {

        $query = new \WP_Query($args);

        ob_start();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                if ($settings['eael_post_grid_preset_style'] === 'two') {
                    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
                        <div class="eael-grid-post-holder">
                            <div class="eael-grid-post-holder-inner">';

                    self::render_template__thumbnail($settings, 'two');

                    if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                        echo '<div class="eael-entry-wrapper">';
                        self::render_template__header($settings, 'two');
                        self::render_template__excerpt($settings);
                        if ($settings['meta_position'] == 'meta-entry-footer') {
                            self::render_template__meta_style_two($settings);
                        }
                        echo '</div>';
                    }
                    echo '</div>
                        </div>
                    </article>';
                } else if ($settings['eael_post_grid_preset_style'] === 'three') {
                    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
                        <div class="eael-grid-post-holder">
                            <div class="eael-grid-post-holder-inner">';

                    self::render_template__thumbnail($settings, 'three');

                    if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                        echo '<div class="eael-entry-wrapper">';
                        self::render_template__header($settings, 'three');
                        self::render_template__excerpt($settings);
                        echo '</div>';
                    }
                    echo '</div>
                        </div>
                    </article>';
                }
                else {
                    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
                        <div class="eael-grid-post-holder">
                            <div class="eael-grid-post-holder-inner">';

                    self::render_template__thumbnail($settings);

                    if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                        echo '<div class="eael-entry-wrapper">';
                        self::render_template__header($settings);
                        self::render_template__excerpt($settings);
                        self::render_template__footer_meta($settings);
                        echo '</div>';
                    }
                    echo '</div>
                        </div>
                    </article>';
                }
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}

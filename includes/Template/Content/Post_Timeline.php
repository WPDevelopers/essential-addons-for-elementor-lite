<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Post_Timeline
{
    public static function render_template_($args, $settings)
    {
        $html = '';
        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $html .= '<article class="eael-timeline-post eael-timeline-column">
                    <div class="eael-timeline-bullet"></div>
                    <div class="eael-timeline-post-inner">
                        <a class="eael-timeline-post-link" href="' . get_the_permalink() . '" title="' . get_the_title() . '">
                            <time datetime="' . get_the_date() . '">' . get_the_date() . '</time>
                            <div class="eael-timeline-post-image" ' . ($settings['eael_show_image'] == 'yes' ? 'style="background-image: url(' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']) . ');"' : null) . '></div>';
                            if ($settings['eael_show_excerpt']) {
                                $html .= '<div class="eael-timeline-post-excerpt">';
                                    if(empty($settings['eael_excerpt_length'])) {
                                        $html .= '<p>'.strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()).'</p>';
                                    }else {
                                        $html .= '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['expanison_indicator']) . '</p>';
                                    }
                                $html .= '</div>';
                            }

                            if ($settings['eael_show_title']) {
                                $html .= '<div class="eael-timeline-post-title">
                                    <h2>' . get_the_title() . '</h2>
                                </div>';
                            }
                        $html .= '</a>
                    </div>
                </article>';
            }
        } else {
            $html .= __('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return $html;
    }
}

<?php
/**
 * Template Name: Default
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

echo '<article class="eael-timeline-post eael-timeline-column">
    <div class="eael-timeline-bullet"></div>
    <div class="eael-timeline-post-inner">
        <a class="eael-timeline-post-link" href="' . get_the_permalink() . '" title="' . get_the_title() . '">
            <time datetime="' . get_the_date() . '">' . get_the_date() . '</time>
            <div class="eael-timeline-post-image" ' . ($settings['eael_show_image'] == 'yes' ? 'style="background-image: url(' . wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size']) . ');"' : null) . '></div>';
            if ($settings['eael_show_excerpt']) {
                echo '<div class="eael-timeline-post-excerpt">';
                    if(empty($settings['eael_excerpt_length'])) {
                        echo '<p>'.strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()).'</p>';
                    }else {
                        echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['expanison_indicator']) . '</p>';
                    }
                echo '</div>';
            }

            if ($settings['eael_show_title']) {
                echo '<div class="eael-timeline-post-title">
                    <h2>' . get_the_title() . '</h2>
                </div>';
            }
        echo '</a>
    </div>
</article>';


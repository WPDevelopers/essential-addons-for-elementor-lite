<?php
/**
 * Template Name: Card
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$image = '';
if ($settings['eael_show_image'] == 'yes') {
	$image = '<div class="eael-timeline-post-image"><img src="'. wp_get_attachment_image_url(get_post_thumbnail_id(),
			$settings['image_size']) .'"></div>';
}


echo '<article class="eael-timeline-post">
    <div class="eael-timeline-bullet"></div>
    <div class="eael-timeline-post-inner">
        <a class="eael-timeline-post-link" href="' . get_the_permalink() . '" title="' . esc_html(get_the_title()) . '">
            <time datetime="' . get_the_date() . '">' . get_the_date() . '</time>
            '. $image ;

			if ($settings['eael_show_title']) {
				echo '<div class="eael-timeline-post-title">
			                    <h2>' . get_the_title() . '</h2>
			                </div>';
			}
            if ($settings['eael_show_excerpt']) {
                echo '<div class="eael-timeline-post-excerpt">';
                    if(empty($settings['eael_excerpt_length'])) {
                        echo '<p>'.strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()).'</p>';
                    }else {
                        echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['expanison_indicator']) . '</p>';
                    }
                echo '</div>';
            }
        echo '</a>
    </div>
    <div class="eael-timeline-clear"></div>
</article>';


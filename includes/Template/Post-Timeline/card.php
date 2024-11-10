<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Card
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$image = '';
if ($settings['eael_show_image'] == 'yes' && has_post_thumbnail()) {
	$image = '<div class="eael-timeline-post-image" style="background-image: url('. wp_get_attachment_image_url
		(get_post_thumbnail_id(),
			$settings['image_size']) .')"></div>';
}

$title_tag = isset($settings['title_tag']) ? Helper::eael_validate_html_tag($settings['title_tag']) : 'h2';

echo '<article class="eael-timeline-post">
    <div class="eael-timeline-bullet"></div>
    <div class="eael-timeline-post-inner">
        <a class="eael-timeline-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_html( get_the_title() ) . '"' . ($settings['timeline_link_nofollow'] ? 'rel="nofollow"' : '') .'' . ($settings['timeline_link_target_blank'] ? 'target="_blank"' : '') . '>
            <time datetime="' . get_the_date() . '">' . get_the_date() . '</time>
            '. wp_kses( $image, Helper::eael_allowed_tags() );

			if( $settings['eael_show_title'] || $settings['eael_show_excerpt'] ) {
				echo '<div class="eael-timeline-content">';

				if ( $settings['eael_show_title'] ) {
					$title_html = '<div class="eael-timeline-post-title">
		                    <' . $title_tag . ' class="eael-timeline-post-title-text-card" >' . get_the_title() . '</'.$title_tag.'>
		                </div>';
					echo wp_kses( $title_html, Helper::eael_allowed_tags() );
				}
				if ( $settings['eael_show_excerpt'] ) {
					echo '<div class="eael-timeline-post-excerpt">';
					if ( empty( $settings['eael_excerpt_length'] ) ) {
						$content = strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() );
						echo '<p>' . wp_kses( $content, Helper::eael_allowed_tags() ) . '</p>';
					} else {
						$content = wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['eael_excerpt_length'], $settings['expanison_indicator'] );
						echo '<p>' . esc_html( $content ) . '</p>';
					}
					echo '</div>';
				}
				echo '</div>';
			}
        echo '</a>
    </div>
    <div class="eael-timeline-clear"></div>
</article>';


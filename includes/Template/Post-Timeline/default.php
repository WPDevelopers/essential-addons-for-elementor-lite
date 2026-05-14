<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Default
 */

use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$post_timeline_image_url = Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(),
	'image', $settings );

$image_size = sanitize_html_class( $settings['image_size'] ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$image_class = " attachment-$image_size size-$image_size"; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$title_tag = isset($settings['title_tag']) ? Helper::eael_validate_html_tag($settings['title_tag']) : 'h2'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

echo '<article class="eael-timeline-post eael-timeline-column">
    <div class="eael-timeline-bullet"></div>
    <div class="eael-timeline-post-inner">
        <a class="eael-timeline-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr(get_the_title()) . '"' . ($settings['timeline_link_nofollow'] ? 'rel="nofollow"' : '') .'' . ($settings['timeline_link_target_blank'] ? 'target="_blank"' : '') . '>
            <time datetime="' . get_the_date() . '">' . get_the_date() . '</time>
            <div class="eael-timeline-post-image ' . esc_attr( $image_class ) . '" ' . ($settings['eael_show_image'] == 'yes' ? 'style="background-image: url('.esc_url( $post_timeline_image_url ).');"' : null) . '></div>';
            if ($settings['eael_show_excerpt']) {
                echo '<div class="eael-timeline-post-excerpt">';
                    if(empty($settings['eael_excerpt_length'])) {
                        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                        $content = strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content());
                        echo '<p>'. wp_kses( $content, Helper::eael_allowed_tags() ) .'</p>';
                    }else {
                        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                        $content = wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), intval( $settings['eael_excerpt_length'] ), sanitize_text_field( $settings['expanison_indicator'] ));
                        echo '<p>' . esc_html( $content ) . '</p>';
                    }
                echo '</div>';
            }

            if ($settings['eael_show_title']) {
                // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                $title_html = '<div class="eael-timeline-post-title">
                    <' .  $settings['title_tag'] . ' class="eael-timeline-post-title-text">' . get_the_title() . '</' . $settings['title_tag'] . '>
                </div>';
                echo wp_kses( $title_html, Helper::eael_allowed_tags() );
            }
        echo '</a>
    </div>
</article>';


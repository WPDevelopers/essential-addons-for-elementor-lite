<?php

use \Essential_Addons_Elementor\Classes\Helper;
use \Elementor\Group_Control_Image_Size;
/**
 * Template Name: Two
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$thumbnail_html = '';
if ( $settings['eael_show_image'] == 'yes' ) {
	$settings[ 'eael_image_size_customize' ] = [
		'id' => get_post_thumbnail_id(),
	];
	$settings['eael_image_size_customize_size'] = $settings['image_size'];
	$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'eael_image_size_customize' );
    
    if ( "" === $thumbnail_html && 'yes' === $settings['eael_show_fallback_img_all'] && !empty( $settings['eael_post_carousel_fallback_img_all']['url'] ) ) {
        $fallback_image_id = $settings['eael_post_carousel_fallback_img_all']['id'];
        $settings[ 'eael_image_size_customize' ] = [
            'id' => $settings['eael_post_carousel_fallback_img_all']['id'],
        ];
        $settings['eael_image_size_customize_size'] = $settings['image_size'];
        $thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'eael_image_size_customize' );
    }
}


global $authordata;
$author_link = $author_name = $author_url = '';
if ( is_object( $authordata ) ) {
    $author_name = $authordata->display_name;

    if ( ! $author_name && isset( $authordata->first_name ) ) {
        $author_name = $authordata->first_name;
		if ( isset( $authordata->last_name ) ) {
			$author_name .= ' ' . $authordata->last_name;
		}
	}

    $author_url = get_author_posts_url( $authordata->ID, $authordata->user_nicename );
    $author_link = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		esc_url( $author_url ),
		/* translators: %s: Author's display name. */
		esc_attr( sprintf( __( 'Posts by %s' ), $author_name ) ),
		$author_name
	);
}
$enable_ratio = $settings['enable_postgrid_image_ratio'] == 'yes' ? 'eael-image-ratio':'';
$is_show_meta = 'yes' === $settings['eael_show_meta'];
$title_tag    = isset($settings['title_tag']) ? Helper::eael_validate_html_tag($settings['title_tag']) : 'h2';

echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . esc_attr( get_the_ID() ) . '">
    <div class="eael-grid-post-holder">
        <div class="eael-grid-post-holder-inner">';
            if ( $thumbnail_html && 'yes' === $settings['eael_show_image'] ) {
                echo '<div class="eael-entry-media">';
                    if ( 'yes' === $settings['eael_show_post_terms'] && 'yes' === $settings['eael_post_terms_on_image_hover'] ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo Helper::get_terms_as_list($settings['eael_post_terms'], $settings['eael_post_terms_max_length']);
                    }

                    echo '<div class="eael-entry-overlay ' . esc_attr( $settings['eael_post_grid_hover_animation'] ) . '">';
                        if (isset($settings['eael_post_grid_bg_hover_icon_new']['url'])) {
                            echo '<img src="' . esc_url($settings['eael_post_grid_bg_hover_icon_new']['url']) . '" alt="' . esc_attr(get_post_meta($settings['eael_post_grid_bg_hover_icon_new']['id'], '_wp_attachment_image_alt', true)) . '" />';
                        } else {
                            echo '<i class="' . esc_attr( $settings['eael_post_grid_bg_hover_icon_new']['value'] ) . '" aria-hidden="true"></i>';
                        }
                        
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<a href="' . esc_url( get_the_permalink() ) . '"' . $link_settings['image_link_nofollow'] . '' . $link_settings['image_link_target_blank'] . '></a>';
                    echo '</div>';

                    echo '<div class="eael-entry-thumbnail '. esc_attr( $enable_ratio ) .'">
                            '. wp_kses( $thumbnail_html, Helper::eael_allowed_icon_tags() ) .'
                            </div>
                    </div>';
            }

            if ($settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt']) {
                echo '<div class="eael-entry-wrapper">';
                if ($settings['eael_show_title']) {
                    echo '<header class="eael-entry-header"><' . esc_attr( $title_tag ) . ' class="eael-entry-title">';
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<a class="eael-grid-post-link" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( strip_tags( get_the_title() ) ) . '"' . $link_settings['title_link_nofollow'] . '' . $link_settings['title_link_target_blank'] . '>';

                    if (empty($settings['eael_title_length'])) {
                        echo wp_kses( get_the_title(), Helper::eael_allowed_tags() );
                    } else {
                        echo wp_kses( implode(" ", array_slice(explode(" ", get_the_title()), 0, $settings['eael_title_length'])), Helper::eael_allowed_tags() );
                    }
                    echo '</a>';
                    echo '</' . esc_attr( $title_tag ) . '></header>';
                }

                if ( $is_show_meta && 'meta-entry-header' === $settings['meta_position'] ) {
                    echo '<div class="eael-entry-header-after style-two">';
                    if ( isset( $settings['eael_show_avatar_two'] ) && 'yes' === $settings['eael_show_avatar_two'] ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<div class="eael-author-avatar"><a href="' . esc_url( $author_url ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96, '', $author_name ) . '</a></div>';
                    }

                    if ( $settings['eael_show_meta'] ) {
                        echo '<div class="eael-entry-meta">';
                        if ( isset( $settings['eael_show_author_two'] ) && 'yes' === $settings['eael_show_author_two'] ) {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<span class="eael-posted-by">' . $author_link . '</span>';
                        }
                        if ($settings['eael_show_date'] === 'yes') {
                            echo '<span class="eael-posted-on eael-meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
                        }
                        echo '</div>';
                    }

                    echo '</div>';
                }

                if ($settings['eael_show_excerpt'] || $settings['eael_show_read_more_button']) {
                    echo '<div class="eael-entry-content">
                                <div class="eael-grid-post-excerpt">';
                    if ($settings['eael_show_excerpt']) {
                        if (empty($settings['eael_excerpt_length'])) {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<p>' . strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()) . '</p>';
                        } else {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<p>' . wp_trim_words( strip_shortcodes(get_the_excerpt() ? get_the_excerpt() : get_the_content()), $settings['eael_excerpt_length'], $settings['excerpt_expanison_indicator'] ) . '</p>';
                        }
                    }

                    if ($settings['eael_show_read_more_button']) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<a href="' . esc_url( get_the_permalink() ) . '" class="eael-post-elements-readmore-btn"' . $link_settings['read_more_link_nofollow'] . '' . $link_settings['read_more_link_target_blank'] . '>' . Helper::eael_wp_kses($settings['read_more_button_text']) . '</a>';
                    }
                    echo '</div>
                            </div>';
                }

                if ( $is_show_meta && 'meta-entry-footer' === $settings['meta_position'] ) {
                    echo '<div class="eael-entry-header-after style-two">';
                        if ( isset( $settings['eael_show_avatar_two'] ) && 'yes' === $settings['eael_show_avatar_two'] ) {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<div class="eael-author-avatar"><a href="' . esc_url( $author_url ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96, '', $author_name ) . '</a></div>';
                        }

                        echo '<div class="eael-entry-meta">';
                        if ( isset( $settings['eael_show_author_two'] ) && 'yes' === $settings['eael_show_author_two'] ) {
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo '<span class="eael-posted-by style-two-footer">' . $author_link . '</span>';
                        }
                        if ( 'yes' === $settings['eael_show_date'] ) {
                            echo '<span class="eael-meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
                        }
                        if ( 'yes' === $settings['eael_show_post_terms'] ) {
                            if ($settings['eael_post_terms'] === 'category') {
                                $terms = get_the_category();
                            }
                            if ($settings['eael_post_terms'] === 'tags') {
                                $terms = get_the_tags();
                            }
                            
                            //For custom post type
                            $get_custom_post_type = get_post_type( get_the_ID() ); //post
                            if ( 'product' === $get_custom_post_type ) {
                                $get_custom_taxonomy  = $settings['eael_post_terms'] === 'category' ? 'product_cat' : ( $settings['eael_post_terms'] === 'tags' ? 'product_tag' : $settings['eael_post_terms'] );
                            } else {
                                $get_custom_taxonomy  = $settings["eael_{$get_custom_post_type}_terms"]; //tags
                            }

                            if( 'post' !== $get_custom_post_type && $settings[ 'eael_post_terms' ] === $get_custom_taxonomy ) {
                                $terms = wp_get_post_terms( get_the_ID(), $get_custom_taxonomy );
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
                                    
                                    $is_last_item = $count + 1 === intval($settings['eael_post_terms_max_length']) || $count + 1 === count( (array) $terms);
                                    
                                    if (  ! empty( $term->name ) ) {
                                        $eael_post_terms_separator = ! empty( $settings['eael_post_terms_separator'] ) ? wp_strip_all_tags( $settings['eael_post_terms_separator'] ) : '';
                                        $eael_post_terms_separator = $is_last_item  ? '' : $eael_post_terms_separator; 
                                    }
                                
                                    $link = ($settings['eael_post_terms'] === 'category') ? get_category_link($term->term_id) : get_tag_link($term->term_id);
                                    $html .= '<li>';
                                    $html .= '<a href="' . esc_url($link) . '">';
                                    $html .= $term->name . " " . esc_html( $eael_post_terms_separator );
                                    $html .= '</a>';
                                    $html .= '</li>';
                                    $count++;
                                }
                                $html .= '</ul>';
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo $html;
                            }
                        }
                        echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
echo '</div>
    </div>
</article>';
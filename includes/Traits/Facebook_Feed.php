<?php

namespace Essential_Addons_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use \Essential_Addons_Elementor\Classes\Helper as HelperClass;

trait Facebook_Feed {
	/**
	 * Facebook Feed
	 *
	 * @param array $settings optional widget's settings
	 *
	 * @return false|string|void
	 * @since 3.4.0
	 */
	public function facebook_feed_render_items( $settings = [] ) {
		// check if ajax request
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'facebook_feed_load_more' ) {
			$ajax = wp_doing_ajax();
			// check ajax referer
			check_ajax_referer( 'essential-addons-elementor', 'security' );

			// init vars
			$page = isset( $_POST['page'] ) ? intval( $_REQUEST['page'], 10 ) : 0;
			if ( ! empty( $_POST['post_id'] ) ) {
				$post_id = intval( $_POST['post_id'], 10 );
			} else {
				$err_msg = __( 'Post ID is missing', 'essential-addons-for-elementor-lite' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}

				return false;
			}
			if ( ! empty( $_POST['widget_id'] ) ) {
				$widget_id = sanitize_text_field( $_POST['widget_id'] );
			} else {
				$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}

				return false;
			}
			$settings = HelperClass::eael_get_widget_settings( $post_id, $widget_id );

		} else {
			// init vars
			$page     = 0;
			$settings = ! empty( $settings ) ? $settings : $this->get_settings_for_display();
		}

		$html    = '';
		$page_id = $settings['eael_facebook_feed_page_id'];
		$token   = $settings['eael_facebook_feed_access_token'];
		$source    = $settings['eael_facebook_feed_data_source'];
        $display_comment = isset( $settings['eael_facebook_feed_comments'] ) ? $settings['eael_facebook_feed_comments'] : '';

		if ( empty( $page_id ) || empty( $token ) ) {
			return;
		}

		$key           = 'eael_facebook_feed_' . md5( str_rot13( str_replace( '.', '', $source . $page_id . $token ) ) . $settings['eael_facebook_feed_cache_limit'] );
		$facebook_data = get_transient( $key );

		if ( $facebook_data == false ) {
			$facebook_data = wp_remote_retrieve_body( wp_remote_get( $this->get_url($page_id, $token, $source, $display_comment), [
				'timeout' => 70,
			] ) );
			$facebook_data = json_decode( $facebook_data, true );
			if ( isset( $facebook_data['data'] ) ) {
				set_transient( $key, $facebook_data, ( $settings['eael_facebook_feed_cache_limit'] * MINUTE_IN_SECONDS ) );
			}
		}

		if ( ! isset( $facebook_data['data'] ) ) {
			return;
		}
		$facebook_data = $facebook_data['data'];

		switch ( $settings['eael_facebook_feed_sort_by'] ) {
			case 'least-recent':
				$facebook_data = array_reverse( $facebook_data );
				break;
		}
		$items = array_splice( $facebook_data, ( $page * $settings['eael_facebook_feed_image_count']['size'] ), $settings['eael_facebook_feed_image_count']['size'] );
		$bg_style = isset( $settings['eael_facebook_feed_image_render_type'] ) && $settings['eael_facebook_feed_image_render_type'] == 'cover' ? "background-size: cover;background-position: center;background-repeat: no-repeat;" : "background-size: 100% 100%;background-repeat: no-repeat;";
		foreach ( $items as $item ) {
			$t        = 'eael_facebook_feed_message_max_length'; // short it
			$limit    = isset( $settings[ $t ] ) && isset( $settings[ $t ]['size'] ) ? $settings[ $t ]['size'] : null;
			$message  = wp_trim_words( ( isset( $item['message'] ) ? $item['message'] : ( isset( $item['story'] ) ? $item['story'] : '' ) ), $limit, '...' );
			$photo    = ( isset( $item['full_picture'] ) ? esc_url( $item['full_picture'] ) : '' );
			$likes    = ( isset( $item['reactions'] ) ? $item['reactions']['summary']['total_count'] : 0 );
			$comments = ( isset( $item['comments'] ) ? $item['comments']['summary']['total_count'] : 0 );

			if ( empty( $photo ) ) {
				$photo = isset( $item['attachments']['data'][0]['media']['image']['src'] ) ? esc_url( $item['attachments']['data'][0]['media']['image']['src'] ) : $photo;
			}

			if ( $settings['eael_facebook_feed_layout'] == 'card' ) {
				$item_form_name  = ! empty( $item['from']['name'] ) ? $item['from']['name'] : '';
				$current_page_id = ! empty( $item['from']['id'] ) ? $item['from']['id'] : $page_id;

				$html .= '<div class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                        <header class="eael-facebook-feed-item-header clearfix">
                            <div class="eael-facebook-feed-item-user clearfix">
                                <a href="https://www.facebook.com/' . $current_page_id . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '"><img src="https://graph.facebook.com/v4.0/' . $current_page_id . '/picture" alt="' . esc_attr( $item_form_name ) . '" class="eael-facebook-feed-avatar"></a>
                                <a href="https://www.facebook.com/' . $current_page_id . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '"><p class="eael-facebook-feed-username">' . esc_html( $item_form_name ) . '</p></a>
                            </div>';

				if ( $settings['eael_facebook_feed_date'] ) {
					$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="eael-facebook-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . date_i18n( get_option('date_format'), strtotime( $item['created_time'] ) ) . '</a>';
				}
				$html .= '</header>';

				if ( $settings['eael_facebook_feed_message'] && ! empty( $message ) ) {
					$html .= '<div class="eael-facebook-feed-item-content">
                                        <p class="eael-facebook-feed-message">' . $this->eael_str_check( $message ) . '</p>
                                    </div>';
				}

				if ( ! empty( $photo ) || isset( $item['attachments']['data'] ) ) {
					$html .= '<div class="eael-facebook-feed-preview-wrap">';
					if ( $item['status_type'] == 'shared_story' ) {

						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">';
							if ( !empty($item['attachments']['data'][0]['media_type']) && $item['attachments']['data'][0]['media_type'] == 'video' ) {
								$html .= '<div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . ');' . esc_attr( $bg_style ) . '">
								<img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '"></div>
	                                                    <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>';
							} else {
								$html .= '<div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . ');' . esc_attr( $bg_style ) . '">
								<img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '"></div>';
							}
							$html .= '</a>';
						}

						$html .= '<div class="eael-facebook-feed-url-preview">';
						if ( isset( $settings['eael_facebook_feed_is_show_preview_host'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_host'] && !empty($item['attachments']['data'][0]['unshimmed_url']) ) {
							$html .= '<p class="eael-facebook-feed-url-host">' . parse_url( $item['attachments']['data'][0]['unshimmed_url'] )['host'] . '</p>';
						}
						if ( isset( $settings['eael_facebook_feed_is_show_preview_title'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_title'] ) {
							$html .= '<h2 class="eael-facebook-feed-url-title">' . esc_html( $item['attachments']['data'][0]['title'] ) . '</h2>';
						}

						if ( isset( $settings['eael_facebook_feed_is_show_preview_description'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_description'] ) {
							$description = isset( $item['attachments']['data'][0]['description'] ) ? $item['attachments']['data'][0]['description'] : '';
							$html        .= '<p class="eael-facebook-feed-url-description">' . wp_kses( $description, HelperClass::eael_allowed_tags() ) . '</p>';
						}
						$html .= '</div>';

					} else if ( $item['status_type'] == 'added_video' ) {
						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">
	                                                <div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . '); ' . esc_attr( $bg_style ) . '">
	                                                    <img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '">
	                                                </div>
	                                                <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>
	                                            </a>';
						}
					} else {
						if ( isset( $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' == $settings['eael_facebook_feed_is_show_preview_thumbnail'] ) {

							$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self' ) . '" class="eael-facebook-feed-preview-img">
	                                                <div class="eael-facebook-feed-img-container" style="background:url(' . esc_url( $photo ) . '); ' . esc_attr( $bg_style ) . '">
	                                                    <img class="eael-facebook-feed-img" src="' . esc_url( $photo ) . '">
	                                                </div>
	                                            </a>';

						}
					}
					$html .= '</div>';
				}


				if ( $settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments'] ) {
					$html .= '<footer class="eael-facebook-feed-item-footer">
                                <div class="clearfix">';
					if ( $settings['eael_facebook_feed_likes'] ) {
						$html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . esc_html( $likes ) . '</span>';
					}
					if ( $settings['eael_facebook_feed_comments'] ) {
						$html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . esc_html( $comments ) . '</span>';
					}
					$html .= '</div>
                            </footer>';
				}
				$html .= '</div>
                </div>';
			} else {
				$html .= '<a href="' . esc_url( $item['permalink_url'] ) . '" target="' . ( $settings['eael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                    	<div class="eael-facebook-feed-img-container" style="background:url(' . ( empty( $photo ) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : esc_url( $photo ) ) . '); ' . esc_attr( $bg_style ) . '">
                            <img class="eael-facebook-feed-img" src="' . ( empty( $photo ) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : esc_url( $photo ) ) . '">
                        </div>';

				if ( $settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments'] ) {
					$html .= '<div class="eael-facebook-feed-item-overlay">
                                        <div class="eael-facebook-feed-item-overlay-inner">
                                            <div class="eael-facebook-feed-meta">';
					if ( $settings['eael_facebook_feed_likes'] ) {
						$html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . esc_html( $likes ) . '</span>';
					}
					if ( $settings['eael_facebook_feed_comments'] ) {
						$html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . esc_html( $comments ) . '</span>';
					}
					$html .= '</div>
                                        </div>
                                    </div>';
				}
				$html .= '</div>
                </a>';
			}
		}

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'facebook_feed_load_more' ) {
			$data = [
				'num_pages' => ceil( count( $facebook_data ) / $settings['eael_facebook_feed_image_count']['size'] ),
				'html'      => $html,
			];
			while ( ob_get_status() ) {
				ob_end_clean();
			}
			wp_send_json( $data );
			wp_die();

		}

		return $html;
	}

	public function eael_str_check( $textData = '' ) {
		$stringText = '';
		if ( strlen( $textData ) > 5 ) {
			$explodeText = explode( ' ', trim( $textData ) );
			for ( $st = 0; $st < count( $explodeText ); $st ++ ) {
				$pos      = stripos( trim( $explodeText[ $st ] ), '#' );
				$pos1     = stripos( trim( $explodeText[ $st ] ), '@' );
				$poshttp  = stripos( trim( $explodeText[ $st ] ), 'http' );
				$poshttps = stripos( trim( $explodeText[ $st ] ), 'https' );

				if ( $pos !== false ) {
					$stringText .= '<a href="https://facebook.com/hashtag/' . str_replace( '#', '', $explodeText[ $st ] ) . '?source=feed_text" target="_blank"> ' . esc_html( $explodeText[ $st ] ) . ' </a>';
				} elseif ( $pos1 !== false ) {
					$stringText .= '<a href="https://facebook.com/' . $explodeText[ $st ] . '/" target="_blank"> ' . esc_html( $explodeText[ $st ] ) . ' </a>';
				} elseif ( $poshttp !== false || $poshttps !== false ) {
					$stringText .= '<a href="' . esc_url( $explodeText[ $st ] ) . '" target="_blank"> ' . esc_html( $explodeText[ $st ] ) . ' </a>';
				} else {
					$stringText .= ' ' . $explodeText[ $st ];
				}
			}
		}

		return $stringText;
	}

	/**
	 * get_url
	 * Build and return api endpoint based on source type
	 *
	 * @param string $page_id string
	 * @param string $token string
	 * @param string $source string
	 *
	 * @return string
	 */
	public function get_url( $page_id = '', $token = '', $source = 'posts', $display_comment = '' ) {
        $comment_count = $display_comment == 'yes' ? ',comments.summary(total_count)' : '';
        $post_limit =  apply_filters( 'eael_facebook_feed_post_limit', 99 );
		$post_url = "https://graph.facebook.com/v18.0/{$page_id}/posts?fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url,media}{$comment_count},reactions.summary(total_count)&limit={$post_limit}&access_token={$token}";
		$feed_url = "https://graph.facebook.com/v18.0/{$page_id}/feed?fields=id,message,full_picture,status_type,created_time,attachments{title,description,type,url,media},from,permalink_url,shares,call_to_action{$comment_count},reactions.summary(total_count),privacy&access_token={$token}&limit={$post_limit}&locale=en_US";

		if ( 'posts' === $source ) {
			return $post_url;
		}
		return $feed_url;
	}
}

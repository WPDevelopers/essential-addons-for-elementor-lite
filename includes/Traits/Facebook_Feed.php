<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
use \Essential_Addons_Elementor\Classes\Helper as HelperClass;

trait Facebook_Feed
{
    /**
     * Facebook Feed
     *
     * @param array $settings optional widget's settings
     * @return false|string|void
     * @since 3.4.0
     */
    public function facebook_feed_render_items($settings = [])
    {
        // check if ajax request
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'facebook_feed_load_more') {
            $ajax = wp_doing_ajax();
            // check ajax referer
            check_ajax_referer('essential-addons-elementor', 'security');

            // init vars
            $page = isset($_POST['page']) ? intval($_REQUEST['page'], 10) : 0;
            if (!empty($_POST['post_id'])) {
                $post_id = intval($_POST['post_id'], 10);
            } else {
                $err_msg = __('Post ID is missing', 'essential-addons-for-elementor-lite');
                if ($ajax) {
                    wp_send_json_error($err_msg);
                }
                return false;
            }
            if (!empty($_POST['widget_id'])) {
                $widget_id = sanitize_text_field($_POST['widget_id']);
            } else {
                $err_msg = __('Widget ID is missing', 'essential-addons-for-elementor-lite');
                if ($ajax) {
                    wp_send_json_error($err_msg);
                }
                return false;
            }
            $settings = HelperClass::eael_get_widget_settings($post_id, $widget_id);

        } else {
            // init vars
            $page = 0;
            $settings = !empty($settings) ? $settings : $this->get_settings_for_display();
        }

        $html = '';
        $page_id = $settings['eael_facebook_feed_page_id'];
        $token = $settings['eael_facebook_feed_access_token'];

        if (empty($page_id) || empty($token)) {
            return;
        }

        $key = 'eael_facebook_feed_' . substr(str_rot13(str_replace('.', '', $page_id . $token)), 32);

        if (get_transient($key) === false) {
            $facebook_data = wp_remote_retrieve_body(wp_remote_get("https://graph.facebook.com/v8.0/{$page_id}/posts?fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url},comments.summary(total_count),reactions.summary(total_count)&limit=99&access_token={$token}", [
                'timeout' => 30,
            ]));
            set_transient($key, $facebook_data, 1800);
        } else {
            $facebook_data = get_transient($key);
        }

        $facebook_data = json_decode($facebook_data, true);

        if (isset($facebook_data['data'])) {
            $facebook_data = $facebook_data['data'];
        } else {
            return;
        }

        switch ($settings['eael_facebook_feed_sort_by']) {
            case 'least-recent':
                $facebook_data = array_reverse($facebook_data);
                break;
        }
        $items = array_splice($facebook_data, ($page * $settings['eael_facebook_feed_image_count']['size']), $settings['eael_facebook_feed_image_count']['size']);
        foreach ($items as $item) {
            $t = 'eael_facebook_feed_message_max_length'; // short it
            $limit = isset($settings[$t]) && isset($settings[$t]['size']) ? $settings[$t]['size']: null;
            $message = wp_trim_words((isset($item['message']) ? $item['message'] : (isset($item['story']) ? $item['story'] : '')), $limit, '...');
            $photo = (isset($item['full_picture']) ? esc_url($item['full_picture']) : '');
            $likes = (isset($item['reactions']) ? $item['reactions']['summary']['total_count'] : 0);
            $comments = (isset($item['comments']) ? $item['comments']['summary']['total_count'] : 0);

            if ($settings['eael_facebook_feed_layout'] == 'card') {
                $html .= '<div class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                        <header class="eael-facebook-feed-item-header clearfix">
                            <div class="eael-facebook-feed-item-user clearfix">
                                <a href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '"><img src="https://graph.facebook.com/v4.0/' . $page_id . '/picture" alt="' . $item['from']['name'] . '" class="eael-facebook-feed-avatar"></a>
                                <a href="https://www.facebook.com/' . $page_id . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '"><p class="eael-facebook-feed-username">' . $item['from']['name'] . '</p></a>
                            </div>';

                if ($settings['eael_facebook_feed_date']) {
                    $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] ? '_blank' : '_self') . '" class="eael-facebook-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . date("d M Y", strtotime($item['created_time'])) . '</a>';
                }
                $html .= '</header>';

                if ($settings['eael_facebook_feed_message'] && !empty($message)) {
                    $html .= '<div class="eael-facebook-feed-item-content">
                                        <p class="eael-facebook-feed-message">' . esc_html($message) . '</p>
                                    </div>';
                }

                if (!empty($photo) || isset($item['attachments']['data'])) {
                    $html .= '<div class="eael-facebook-feed-preview-wrap">';
                    if ($item['status_type'] == 'shared_story') {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">';
                        if ($item['attachments']['data'][0]['media_type'] == 'video') {
                            $html .= '<img class="eael-facebook-feed-img" src="' . $photo . '">
                                                    <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>';
                        } else {
                            $html .= '<img class="eael-facebook-feed-img" src="' . $photo . '">';
                        }
                        $html .= '</a>';

                        $html .= '<div class="eael-facebook-feed-url-preview">
                                                <p class="eael-facebook-feed-url-host">' . parse_url($item['attachments']['data'][0]['unshimmed_url'])['host'] . '</p>
                                                <h2 class="eael-facebook-feed-url-title">' . $item['attachments']['data'][0]['title'] . '</h2>
                                                <p class="eael-facebook-feed-url-description">' . @$item['attachments']['data'][0]['description'] . '</p>
                                            </div>';
                    } else if ($item['status_type'] == 'added_video') {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">
                                                <img class="eael-facebook-feed-img" src="' . $photo . '">
                                                <div class="eael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>
                                            </a>';
                    } else {
                        $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] == 'yes' ? '_blank' : '_self') . '" class="eael-facebook-feed-preview-img">
                                                <img class="eael-facebook-feed-img" src="' . $photo . '">
                                            </a>';
                    }
                    $html .= '</div>';
                }

                if ($settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments']) {
                    $html .= '<footer class="eael-facebook-feed-item-footer">
                                <div class="clearfix">';
                    if ($settings['eael_facebook_feed_likes']) {
                        $html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
                    }
                    if ($settings['eael_facebook_feed_comments']) {
                        $html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $comments . '</span>';
                    }
                    $html .= '</div>
                            </footer>';
                }
                $html .= '</div>
                </div>';
            } else {
                $html .= '<a href="' . $item['permalink_url'] . '" target="' . ($settings['eael_facebook_feed_link_target'] ? '_blank' : '_self') . '" class="eael-facebook-feed-item">
                    <div class="eael-facebook-feed-item-inner">
                        <img class="eael-facebook-feed-img" src="' . (empty($photo) ? EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg' : $photo) . '">';

                if ($settings['eael_facebook_feed_likes'] || $settings['eael_facebook_feed_comments']) {
                    $html .= '<div class="eael-facebook-feed-item-overlay">
                                        <div class="eael-facebook-feed-item-overlay-inner">
                                            <div class="eael-facebook-feed-meta">';
                    if ($settings['eael_facebook_feed_likes']) {
                        $html .= '<span class="eael-facebook-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
                    }
                    if ($settings['eael_facebook_feed_comments']) {
                        $html .= '<span class="eael-facebook-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $comments . '</span>';
                    }
                    $html .= '</div>
                                        </div>
                                    </div>';
                }
                $html .= '</div>
                </a>';
            }
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'facebook_feed_load_more') {
            $data = [
                'num_pages' => ceil(count($facebook_data) / $settings['eael_facebook_feed_image_count']['size']),
                'html' => $html,
            ];
            while (ob_get_status()) {
                ob_end_clean();
            }
            if (function_exists('gzencode')) {
                $response = gzencode(wp_json_encode($data));
                header('Content-Type: application/json; charset=utf-8');
                header('Content-Encoding: gzip');
                header('Content-Length: ' . strlen($response));

                echo $response;
            } else {
                wp_send_json($data);
            }
            wp_die();

        }

        return $html;
    }
}

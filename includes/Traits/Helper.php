<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Helper
{
    /**
     * Twitter Feed
     *
     * @since 3.0.6
     */
    public function twitter_feed_render_items($id, $settings, $class = '')
    {
        $token = get_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token');
        $items = get_transient($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_cache');
        $html = '';

        if (empty($settings['eael_twitter_feed_consumer_key']) || empty($settings['eael_twitter_feed_consumer_secret'])) {
            return;
        }

        if ($items === false) {
            if (empty($token)) {
                $credentials = base64_encode($settings['eael_twitter_feed_consumer_key'] . ':' . $settings['eael_twitter_feed_consumer_secret']);

                add_filter('https_ssl_verify', '__return_false');

                $response = wp_remote_post('https://api.twitter.com/oauth2/token', [
                    'method' => 'POST',
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => [
                        'Authorization' => 'Basic ' . $credentials,
                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                    ],
                    'body' => ['grant_type' => 'client_credentials'],
                ]);

                $body = json_decode(wp_remote_retrieve_body($response));

                if ($body) {
                    update_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token', $body->access_token);
                    $token = $body->access_token;
                }
            }

            $args = array(
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => "Bearer $token",
                ),
            );

            add_filter('https_ssl_verify', '__return_false');

            $response = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $settings['eael_twitter_feed_ac_name'] . '&count=999&tweet_mode=extended', [
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ]);

            if (!is_wp_error($response)) {
                $items = json_decode(wp_remote_retrieve_body($response), true);
                set_transient($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_cache', $items, 1800);
            }
        }

        if (empty($items)) {
            return;
        }

        if ($settings['eael_twitter_feed_hashtag_name']) {
            foreach ($items as $key => $item) {
                $match = false;

                if ($item['entities']['hashtags']) {
                    foreach ($item['entities']['hashtags'] as $tag) {
                        if (strcasecmp($tag['text'], $settings['eael_twitter_feed_hashtag_name']) == 0) {
                            $match = true;
                        }
                    }
                }

                if ($match == false) {
                    unset($items[$key]);
                }
            }
        }

        $items = array_splice($items, 0, $settings['eael_twitter_feed_post_limit']);

        foreach ($items as $item) {
            $delimeter = strlen($item['full_text']) > $settings['eael_twitter_feed_content_length'] ? '...' : '';

            $html .= '<div class="eael-twitter-feed-item ' . $class . '">
				<div class="eael-twitter-feed-item-inner">
				    <div class="eael-twitter-feed-item-header clearfix">';
            if ($settings['eael_twitter_feed_show_avatar'] == 'true') {
                $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings['eael_twitter_feed_avatar_style'] . '" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">
                                <img src="' . $item['user']['profile_image_url_https'] . '">
                            </a>';
            }
            $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">';
            if ($settings['eael_twitter_feed_show_icon'] == 'true') {
                $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
            }

            $html .= '<span class="eael-twitter-feed-item-author">' . $item['user']['name'] . '</span>
                        </a>';
            if ($settings['eael_twitter_feed_show_date'] == 'true') {
                $html .= '<span class="eael-twitter-feed-item-date">' . sprintf(__('%s ago', 'essential-addons-for-elementor-lite'), human_time_diff(strtotime($item['created_at']))) . '</span>';
            }
            $html .= '</div>

                    <div class="eael-twitter-feed-item-content">
                        <p>' . substr(str_replace(@$item['entities']['urls'][0]['url'], '', $item['full_text']), 0, $settings['eael_twitter_feed_content_length']) . $delimeter . '</p>';

            if ($settings['eael_twitter_feed_show_read_more'] == 'true') {
                $html .= '<a href="//twitter.com/' . @$item['user']['screen_name'] . '/status/' . $item['id_str'] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
            }
            $html .= '</div>
                    ' . (isset($item['extended_entities']['media'][0]) && $settings['eael_twitter_feed_media'] == 'true' ? ($item['extended_entities']['media'][0]['type'] == 'photo' ? '<img src="' . $item['extended_entities']['media'][0]['media_url_https'] . '">' : '') : '') . '
                </div>
			</div>';
        }

        return $html;
    }

    /**
     * Facebook Feed
     *
     * @since 3.4.0
     */
    public function facebook_feed_render_items()
    {
        // check if ajax request
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'facebook_feed_load_more') {
            // check ajax referer
            check_ajax_referer('essential-addons-elementor', 'security');

            // init vars
            $page = $_REQUEST['page'];
            parse_str($_REQUEST['settings'], $settings);
        } else {
            // init vars
            $page = 0;
            $settings = $this->get_settings_for_display();
        }

        $html = '';
        $page_id = $settings['eael_facebook_feed_page_id'];
        $token = $settings['eael_facebook_feed_access_token'];

        if (empty($page_id) || empty($token)) {
            return;
        }

        $key = 'eael_facebook_feed_' . substr(str_rot13(str_replace('.', '', $page_id . $token)), 32);

        if (get_transient($key) === false) {
            $facebook_data = wp_remote_retrieve_body(wp_remote_get("https://graph.facebook.com/v7.0/{$page_id}/posts?fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url},comments.summary(total_count),reactions.summary(total_count)&access_token={$token}"));
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
            $message = wp_trim_words((isset($item['message']) ? $item['message'] : (isset($item['story']) ? $item['story'] : '')), $settings['eael_facebook_feed_message_max_length']['size'], '...');
            $photo = (isset($item['full_picture']) ? $item['full_picture'] : '');
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
            wp_send_json([
                'num_pages' => ceil(count($facebook_data) / $settings['eael_facebook_feed_image_count']['size']),
                'html' => $html,
            ]);
        }

        return $html;
    }

    /**
     * @param $id
     * @param $global_data
     * @return string
     */
    public function eael_get_typography_data($id, $global_data)
    {
        $typo_data = '';
        $fields_keys = [
            'font_family',
            'font_weight',
            'text_transform',
            'font_style',
            'text_decoration',
            'font_size',
            'letter_spacing',
            'line_height',
        ];
        foreach ($fields_keys as $key => $field) {
            $typo_attr = $global_data[$id . '_' . $field];
            $attr = str_replace('_', '-', $field);
            if (in_array($field, ['font_size', 'letter_spacing', 'line_height'])) {
                if (!empty($typo_attr['size'])) {
                    $typo_data .= "{$attr}:{$typo_attr['size']}{$typo_attr['unit']} !important;";
                }
            } elseif (!empty($typo_attr)) {
                $typo_data .= ($attr == 'font-family') ? "{$attr}:{$typo_attr}, sans-serif;" : "{$attr}:{$typo_attr};";
            }
        }
        return $typo_data;
    }

    /**
     * @since  3.8.2
     * @param $source
     *
     * @return array
     */
    public function eael_event_calendar_source($source)
    {
        if (apply_filters('eael/pro_enabled', false)) {
            $source['eventon'] = __('EventON', 'essential-addons-for-elementor-lite');
        } else {
            $source['eventon'] = __('EventON (Pro) ', 'essential-addons-for-elementor-lite');
        }

        return $source;
    }

    public function eael_list_ninja_tables()
    {
        $tables = get_posts([
            'post_type' => 'ninja-table',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
        ]);

        if (!empty($tables)) {
            return wp_list_pluck($tables, 'post_title', 'ID');
        }

        return [];
    }

    public function advanced_data_table_source_control($wb)
    {
        if (apply_filters('eael/active_plugins', 'ninja-tables/ninja-tables.php')) {
            $wb->add_control(
                'ea_adv_data_table_source_ninja_table_id',
                [
                    'label' => esc_html__('Table ID', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $this->eael_list_ninja_tables(),
                    'condition' => [
                        'ea_adv_data_table_source' => 'ninja',
                    ],
                ]
            );
        } else {
            $wb->add_control(
                'ea_adv_data_table_ninja_required',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>Ninja Tables</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=Ninja+Tables&tab=search&type=term" target="_blank">Ninja Tables</a> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                    'condition' => [
                        'ea_adv_data_table_source' => 'ninja',
                    ],
                ]
            );
        }
    }

    public function advanced_data_table_ninja_integration($settings)
    {
        if (empty($settings['ea_adv_data_table_source_ninja_table_id'])) {
            return;
        }

        $html = '';
        $table_settings = ninja_table_get_table_settings($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_headers = ninja_table_get_table_columns($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_rows = ninjaTablesGetTablesDataByID($settings['ea_adv_data_table_source_ninja_table_id']);

        if (!empty($table_rows)) {
            if (!isset($table_settings['hide_header_row']) || $table_settings['hide_header_row'] != true) {
                $html .= '<thead><tr>';
                foreach ($table_headers as $key => $th) {
                    $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                    $html .= '<th' . $style . '>' . $th['name'] . '</th>';
                }
                $html .= '</tr></thead>';
            }

            $html .= '<tbody>';
            foreach ($table_rows as $key => $tr) {
                $html .= '<tr>';
                foreach ($table_headers as $th) {
                    if (!isset($th['data_type'])) {
                        $th['data_type'] = '';
                    }

                    if ($th['data_type'] == 'image') {
                        $html .= '<td>' . (isset($tr[$th['key']]['image_thumb']) ? '<a href="' . $tr[$th['key']]['image_full'] . '"><img src="' . $tr[$th['key']]['image_thumb'] . '"></a>' : '') . '</td>';
                    } elseif ($th['data_type'] == 'selection') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? implode((array) $tr[$th['key']], ', ') : '') . '</td>';
                    } elseif ($th['data_type'] == 'button') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? '<a href="' . $tr[$th['key']] . '" class="button" target="' . $th['link_target'] . '">' . $th['button_text'] . '</a>' : '') . '</td>';
                    } else {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? $tr[$th['key']] : '') . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        return $html;
    }

    /**
     * Woo Checkout
     */
    public function woo_checkout_update_order_review()
    {
        $setting = $_POST['orderReviewData'];
        ob_start();
        Woo_Checkout::checkout_order_review_default($setting);
        $woo_checkout_update_order_review = ob_get_clean();

        wp_send_json(
            array(
                'order_review' => $woo_checkout_update_order_review,
            )
        );
    }

    /** Filter to add plugins to the TOC list.
     *
     * @since  3.9.3
     * @param array TOC plugins.
     *
     * @return mixed
     */
    public function eael_toc_rank_math_support($toc_plugins)
    {
        $toc_plugins['essential-addons-for-elementor-lite/essential_adons_elementor.php'] = __('Essential Addons for Elementor', 'essential-addons-for-elementor-lite');
        return $toc_plugins;
    }

    /**
     * Save typeform access token
     *
     * @since  4.0.2
     */
    public function eael_typeform_auth_handle()
    {
        $post = $_POST;
        if (isset($post['typeform_tk']) && isset($post['pr_code'])) {
            if (wp_hash('eael_typeform') === $post['pr_code']) {
                update_option('eael_save_typeform_personal_token', sanitize_text_field($post['typeform_tk']));
            }
        }
        wp_send_json_success(['status' => 'success']);
    }
}

<?php

namespace Essential_Addons_Elementor\Traits;

use function Better_Payment\Lite\Classes\better_payment_dd;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Twitter_Feed
{
    public static $twitter_feed_fetched_count = 0;
    /**
     * Twitter Feed
     *
     * @since 3.0.6
     */
    public function twitter_feed_render_items($id, $settings, $class = '')
    {
        $token = get_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_token');
        $user_object = get_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_user_object');
        $user_object = ! empty( $user_object->data ) ? $user_object->data : '';
        $expiration = ! empty( $settings['eael_auto_clear_cache'] ) && ! empty( $settings['eael_twitter_feed_cache_limit'] ) ? absint( $settings['eael_twitter_feed_cache_limit'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
	    $cache_key = $settings['eael_twitter_feed_ac_name'] . '_' . $expiration . '_' . md5( $settings['eael_twitter_feed_hashtag_name'] . $settings['eael_twitter_feed_consumer_key'] . $settings['eael_twitter_feed_consumer_secret'] . ( empty( $settings['eael_twitter_feed_bearer_token'] ) ? '' : $settings['eael_twitter_feed_bearer_token'] ) ) . '_tf_cache';
        $items = get_transient( $cache_key );
        $html = '';

        $twitter_v2 = ! empty( $settings['eael_twitter_api_v2'] ) && 'yes' === $settings['eael_twitter_api_v2'] ? true : false;
        $account_name = sanitize_text_field( str_replace('@', '', $settings['eael_twitter_feed_ac_name']) );
                
        if ( ! $twitter_v2 && ( empty($settings['eael_twitter_feed_consumer_key']) || empty($settings['eael_twitter_feed_consumer_secret']) ) ) {
            return;
        }

        if ( $twitter_v2 && empty( $settings['eael_twitter_feed_bearer_token'] ) ) {
            return;
        }

        if( $user_object ){
            $user_id                = ! empty( $user_object->id ) ? $user_object->id : '';
            $user_profile_image_url = ! empty( $user_object->profile_image_url ) ? $user_object->profile_image_url : '';
            $user_username          = ! empty( $user_object->username ) ? $user_object->username : '';
            $user_name              = ! empty( $user_object->name ) ? $user_object->name : ''; 
        }

        if ($items === false) {
            if ( ! $twitter_v2 && empty( $token ) ) {
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

            add_filter('https_ssl_verify', '__return_false');

            $api_endpoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $settings['eael_twitter_feed_ac_name'] . '&count=999&tweet_mode=extended';
            
            if ( $twitter_v2 ){
                $token = ! empty( $settings['eael_twitter_feed_bearer_token'] ) ? $settings['eael_twitter_feed_bearer_token'] : '';
                $tweet_fields = [ 'entities', 'public_metrics', 'in_reply_to_user_id', 'attachments', 'created_at' ];
                $tweet_fields_params = implode(',', $tweet_fields);

                if ( empty( $user_object ) ){
                    $api_endpoint_user = "https://api.twitter.com/2/users/by/username/$account_name?user.fields=profile_image_url";

                    $response_user = wp_remote_get($api_endpoint_user, [
                        'blocking' => true,
                        'headers' => [
                            'Authorization' => "Bearer $token",
                        ],
                    ]);
    
                    $body_user = json_decode(wp_remote_retrieve_body($response_user));
    
                    if ($body_user) {
                        $user_object = $body_user;
                        update_option($id . '_' . $settings['eael_twitter_feed_ac_name'] . '_tf_user_object', $user_object);
                        
                        $user_id                = ! empty( $user_object->id ) ? $user_object->id : '';
                        $user_profile_image_url = ! empty( $user_object->profile_image_url ) ? $user_object->profile_image_url : '';
                        $user_username          = ! empty( $user_object->username ) ? $user_object->username : '';
                        $user_name              = ! empty( $user_object->name ) ? $user_object->name : '';
                    }
                }

                if ( empty( $user_id ) ){
                    return $html;
                }

                $api_endpoint = "https://api.twitter.com/2/users/$user_id/tweets?max_results=100&tweet.fields=$tweet_fields_params";
            }
            
            $response = wp_remote_get($api_endpoint, [
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ]);
            
	        if ( is_wp_error( $response ) ) {
		        return $html;
	        }

	        if ( ! empty( $response['response'] ) && $response['response']['code'] == 200 ) {
		        $items  = json_decode( wp_remote_retrieve_body( $response ), true );
                $items  = $twitter_v2 && ! empty( $items['data'] ) ? $items['data'] : $items; 
		        
                set_transient( $cache_key, $items, $expiration );
	        }
        }
        
	    if ( empty( $items ) ) {
		    return $html;
	    }

        if ( $settings['eael_twitter_feed_hashtag_name'] ) {
            foreach ($items as $key => $item) {
                $match = false;

                if ( ! empty( $item['entities']['hashtags'] ) ) {
                    foreach ($item['entities']['hashtags'] as $tag) {
                        $tag['text'] = $twitter_v2 ? $tag['tag'] : $tag['text'];
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
        $post_per_page = ! empty($settings['eael_twitter_feed_posts_per_page']) ? $settings['eael_twitter_feed_posts_per_page'] : 10;
        $counter = 0;
        $current_page = 1;
        self::$twitter_feed_fetched_count = count($items);
        
        foreach ($items as $item) {
            $counter++;
            if ($post_per_page > 0) {
                $current_page = ceil($counter / $post_per_page);
            }

            $is_reply = ! empty($item['in_reply_to_status_id']) ? true : false;
            
            if( $twitter_v2 ){
                $is_reply = !empty($item['in_reply_to_user_id']) ? true : false;
            }

            $show_reply = ( !empty($settings['eael_twitter_feed_show_replies']) && 'true' === $settings['eael_twitter_feed_show_replies'] ) ? true : false;

            if($is_reply && !$show_reply){
                continue;
            }

            $item['full_text'] = $twitter_v2 ? $item['text'] : $item['full_text'];
            $delimeter = strlen($item['full_text']) > $settings['eael_twitter_feed_content_length'] ? '...' : '';

	        $media = isset( $item['extended_entities']['media'] ) ? $item['extended_entities']['media'] :
		        ( isset( $item['retweeted_status']['entities']['media'] ) ? $item['retweeted_status']['entities']['media'] :
			        ( isset( $item['quoted_status']['entities']['media'] ) ? $item['quoted_status']['entities']['media'] :
				        [] ) );

            $show_pagination = ! empty($settings['pagination']) && 'yes' === $settings['pagination'] ? true : false;
            
            if($show_pagination){
                $pagination_class = ' page-' . $current_page;
                $pagination_class .= 1 === intval( $current_page ) ? ' eael-d-block' : ' eael-d-none';
            } else {
                $pagination_class = 'page-1 eael-d-block';
            }

            if ($counter == count($items)) {
                $pagination_class .= ' eael-last-twitter-feed-item';
            }
            
            $user_name_full = '';
            
            $html .= '<div class="eael-twitter-feed-item ' . esc_attr( $class ) . ' ' . esc_attr( $pagination_class ) . ' ">
				<div class="eael-twitter-feed-item-inner">
				    <div class="eael-twitter-feed-item-header clearfix">';
                        $user_profile_image_url_https = ! empty( $item['user']['profile_image_url_https'] ) ? $item['user']['profile_image_url_https'] : '';
                        $$user_name_full = ! empty( $item['user']['name'] ) ? $item['user']['name'] : '';

                        $user_profile_image_url_https = $twitter_v2 && ! empty( $user_profile_image_url ) ? $user_profile_image_url : '';
                        $user_name_full = $twitter_v2 && ! empty( $user_name ) ? $user_name : '';
                        
                        if ($settings['eael_twitter_feed_show_avatar'] == 'true' && ! empty( $user_profile_image_url_https ) ) {
                            $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings['eael_twitter_feed_avatar_style'] . '" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">
                                <img src="' . esc_url( $user_profile_image_url_https ) . '">
                            </a>';
                        }

                        $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings['eael_twitter_feed_ac_name'] . '" target="_blank">';
                            if ( $settings['eael_twitter_feed_show_icon'] == 'true' && ! empty( $user_name_full ) ) {
                                $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
                            }
                            $html .= '<span class="eael-twitter-feed-item-author">' . $user_name_full . '</span>
                        </a>';

                        if ($settings['eael_twitter_feed_show_date'] == 'true') {
                            $html .= '<span class="eael-twitter-feed-item-date">' . sprintf(__('%s ago', 'essential-addons-for-elementor-lite'), human_time_diff(strtotime($item['created_at']))) . '</span>';
                        }
                    $html .= '</div>

                    <div class="eael-twitter-feed-item-content">';
                            $content = isset($item['entities']['urls'][0]['url'])?str_replace($item['entities']['urls'][0]['url'], '', $item['full_text']):$item['full_text'];
                            $content = substr( $content, 0, $settings['eael_twitter_feed_content_length']) . $delimeter;
                            if ( ! empty( $settings['eael_twitter_feed_hash_linked'] ) && $settings['eael_twitter_feed_hash_linked'] === 'yes' && ! empty( $item['entities']['hashtags'] ) ) {
                                $hashtags = [];
                                foreach ( $item['entities']['hashtags'] as $hashtag ){
                                    $hashtag['text'] = $twitter_v2 ? $hashtag['tag'] : $hashtag['text'];
                                    
                                    if ( $hashtag['text'] ){
                                        $hashtags['#'.$hashtag['text']] = "<a href='https://twitter.com/hashtag/{$hashtag['text']}?src=hashtag_click' target='_blank'>#{$hashtag['text']}</a>";
                                    }
                                }
                                $content = str_replace( array_keys($hashtags), $hashtags, $content );
                            }
                            
                            if( $twitter_v2 ) {
                                $item['id_str'] = $item['id'];
                                $item['entities']['user_mentions'] = ! empty( $item['entities']['mentions'] ) ? $item['entities']['mentions'] : [];
                            }
                            
                            if ( ! empty( $settings['eael_twitter_feed_mention_linked'] ) && $settings['eael_twitter_feed_mention_linked'] === 'yes' && $item['entities']['user_mentions'] ) {
                                $mentions = [];
                                foreach ( $item['entities']['user_mentions'] as $mention ){
                                    $mention['screen_name'] = $twitter_v2 ? ( ! empty( $mention['tag'] ) ? $mention['tag'] : '' ) : $mention['screen_name'];

                                    if ( $mention['screen_name'] ){
                                        $mentions['@'.$mention['screen_name']] = "<a href='https://twitter.com/{$mention['screen_name']}' target='_blank'>@{$mention['screen_name']}</a>";
                                    }
                                }
                                $content = str_replace( array_keys($mentions), $mentions, $content );
                            }
                            $html .= '<p>' . $content . '</p>';

                            $item_user_screen_name = ! empty( $item['user']['screen_name'] ) ? $item['user']['screen_name'] : '';
                            $item_user_screen_name = $twitter_v2 && ! empty( $user_username ) ? $user_username : '';

                            if ($settings['eael_twitter_feed_show_read_more'] == 'true' && ! empty( $item_user_screen_name ) ) {
	                        $read_more = !empty( $settings[ 'eael_twitter_feed_show_read_more_text' ] ) ? $settings[ 'eael_twitter_feed_show_read_more_text' ] : __( 'Read More', 'essential-addons-for-elementor-lite' );
                            $html .= '<a href="//twitter.com/' . esc_attr( $item_user_screen_name ) . '/status/' . esc_attr( $item['id_str'] ) . '" target="_blank" class="read-more-link">'. esc_html( $read_more ).' <i class="fas fa-angle-double-right"></i></a>';
                        }
                    $html .= '</div>
                    ' . ( isset( $media[0] ) && $settings['eael_twitter_feed_media'] == 'true' ? ( $media[0]['type'] == 'photo' ? '<img src="' . esc_url( $media[0]['media_url_https'] ) . '">' : '' ) : '' ) . '
                </div>
			</div>';
        }

        return $html;
    }
}

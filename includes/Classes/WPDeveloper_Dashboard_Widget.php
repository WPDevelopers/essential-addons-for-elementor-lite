<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

class WPDeveloper_Dashboard_Widget
{
    // instance container
    private static $instance = null;

    /**
     * Constructor of plugin class
     *
     * @return void
     */
    private function __construct()
    {
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widgets']);
    }

    /**
     * Singleton instance
     *
     * @return $instance
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Add dashboard widgets
     *
     * @return void
     */
    public function add_dashboard_widgets()
    {
        wp_add_dashboard_widget('wpdev_feed_widget', __('WPDeveloper Feed', 'essential-addons-for-elementor-lite'), [$this, 'feed']);
    }

    /**
     * Dashboard feed widget
     *
     * @return void
     */
    public function feed()
    {
        if (!function_exists('fetch_feed')) {
            include_once ABSPATH . WPINC . '/feed.php';
        }

        $url    = 'https://wpdeveloper.net/feed';
        $count  = 5;
        $output = '<ul>';

        // Get a SimplePie feed object from the specified feed source.
        $rss = fetch_feed($url);

        if (is_wp_error($rss)) {
            $output .= '<li>' . __('No recent news.', 'essential-addons-for-elementor-lite') . '</li>';
        } else {
            // Figure out how many total items there are, but limit it to 5.
            $max = $rss->get_item_quantity($count);

            // Build an array of all the items, starting with element 0 (first element).
            $rss_items = $rss->get_items(0, $max);

            foreach ($rss_items as $item) {
                $output .= '<li>
					<a href="' . esc_url($item->get_permalink()) . '?utm_source=dashboard&utm_medium=feed&utm_campaign=wpdev_feed_ea" title="' . sprintf(__('Posted on %s', 'essential-addons-for-elementor-lite'), $item->get_date('j F Y | g:i a')) . '">' . esc_html($item->get_title()) . '</a>
                </li>';
            }
        }

        $output .= '</ul>';
        $output .= '<p>' . __('Keep Your Eyes on', 'essential-addons-for-elementor-lite') . ' <a href="https://wpdeveloper.net" target="_blank">WPDeveloper</a></p>';

        echo $output;
    }
}

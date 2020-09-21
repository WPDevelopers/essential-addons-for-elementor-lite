<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Essential_Addons_Elementor\Classes\Helper as HelperClass;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Helper
{
    /**
     * This function is responsible for get the post data.
     * It will return HTML markup with AJAX call and with normal call.
     *
     * @return string of an html markup with AJAX call.
     * @return array of content and found posts count without AJAX call.
     */
    public function ajax_load_more()
    {
        parse_str($_REQUEST['args'], $args);
        parse_str($_REQUEST['settings'], $settings);

        $html = '';
        $class = '\\' . str_replace('\\\\', '\\', $_REQUEST['class']);
        $args['offset'] = (int) $args['offset'] + (((int) $_REQUEST['page'] - 1) * (int) $args['posts_per_page']);

        if (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy']['taxonomy'] != 'all') {
            $args['tax_query'] = [
                $_REQUEST['taxonomy'],
            ];
        }

        if ($class == '\Essential_Addons_Elementor\Elements\Post_Grid' && $settings['orderby'] === 'rand') {
            $args['post__not_in'] = array_unique($_REQUEST['post__not_in']);
        }

        $template_info = $_REQUEST['template_info'];

        if ($template_info) {
            if ($template_info['dir'] === 'free') {
                $file_path = EAEL_PLUGIN_PATH;
            }

            if ($template_info['dir'] === 'pro') {
                $file_path = EAEL_PRO_PLUGIN_PATH;
            }

            $file_path = sprintf(
                '%sincludes/Template/%s/%s.php',
                $file_path,
                $template_info['name'],
                $template_info['file_name']
            );

            if ($file_path) {
                $query = new \WP_Query($args);

                $iterator = 0;

                if ($query->have_posts()) {

                    if($class === '\Essential_Addons_Elementor\Pro\Elements\Post_List') {
                        $html .= '<div class="eael-post-list-posts-wrap">';
                    }

                    while ($query->have_posts()) {
                        $query->the_post();

                        $html .= HelperClass::includes_with_variable($file_path, ['settings' => $settings, 'iterator' => $iterator]);
                        $iterator++;
                    }
                    if($class === '\Essential_Addons_Elementor\Pro\Elements\Post_List') {
                        $html .= '</div>';
                    }
                }
            }
        }

        echo $html;
        wp_die();
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
    public function toc_rank_math_support($toc_plugins)
    {
        $toc_plugins['essential-addons-for-elementor-lite/essential_adons_elementor.php'] = __('Essential Addons for Elementor', 'essential-addons-for-elementor-lite');
        return $toc_plugins;
    }

    /**
     * Save typeform access token
     *
     * @since  4.0.2
     */
    public function typeform_auth_handle()
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

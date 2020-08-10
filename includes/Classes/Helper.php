<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;

trait Helper
{
    /**
     * Get all types of post.
     * @return array
     */
    public static function eael_get_all_types_post($post_type = 'any')
    {
        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        if (!empty($posts)) {
            return wp_list_pluck($posts, 'post_title', 'ID');
        }

        return [];
    }

    /**
     * Go Premium
     *
     */
    public static function eael_go_premium($wb)
    {
        $wb->start_controls_section(
            'eael_section_pro',
            [
                'label' => __('Go Premium for More Features', 'essential-addons-for-elementor-lite'),
            ]
        );

        $wb->add_control(
            'eael_control_get_pro',
            [
                'label' => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-unlock-alt',
                    ],
                ],
                'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="http://essential-addons.com/elementor/#pricing" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
            ]
        );

        $wb->end_controls_section();
    }

    public static function fix_old_query($settings)
    {
        $update_query = false;

        foreach ($settings as $key => $value) {
            if (strpos($key, 'eaeposts_') !== false) {
                $settings[str_replace('eaeposts_', '', $key)] = $value;
                $update_query = true;
            }
        }

        if ($update_query) {
            global $wpdb;

            $post_id = get_the_ID();
            $data = get_post_meta($post_id, '_elementor_data', true);
            $data = str_replace('eaeposts_', '', $data);
            $wpdb->update(
                $wpdb->postmeta,
                [
                    'meta_value' => $data,
                ],
                [
                    'post_id' => $post_id,
                    'meta_key' => '_elementor_data',
                ]
            );
        }

        return $settings;
    }

    public static function eael_get_query_args($settings = [], $requested_post_type = 'post')
    {
        $settings = wp_parse_args($settings, [
            'post_type' => $requested_post_type,
            'posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 3,
            'offset' => 0,
            'post__not_in' => [],
        ]);

        $args = [
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'offset' => $settings['offset'],
        ];

        if ('by_id' === $settings['post_type']) {
            $args['post_type'] = 'any';
            $args['post__in'] = empty($settings['posts_ids']) ? [0] : $settings['posts_ids'];
        } else {
            $args['post_type'] = $settings['post_type'];

            if ($args['post_type'] !== 'page') {
                $args['tax_query'] = [];

                $taxonomies = get_object_taxonomies($settings['post_type'], 'objects');

                foreach ($taxonomies as $object) {
                    $setting_key = $object->name . '_ids';

                    if (!empty($settings[$setting_key])) {
                        $args['tax_query'][] = [
                            'taxonomy' => $object->name,
                            'field' => 'term_id',
                            'terms' => $settings[$setting_key],
                        ];
                    }
                }

                if (!empty($args['tax_query'])) {
                    $args['tax_query']['relation'] = 'AND';
                }
            }
        }

        if (!empty($settings['authors'])) {
            $args['author__in'] = $settings['authors'];
        }

        if (!empty($settings['post__not_in'])) {
            $args['post__not_in'] = $settings['post__not_in'];
        }

        return $args;
    }

    /**
     * Get All POst Types
     * @return array
     */
    public static function eael_get_post_types()
    {
        $post_types = get_post_types(['public' => true, 'show_in_nav_menus' => true], 'objects');
        $post_types = wp_list_pluck($post_types, 'label', 'name');

        return array_diff_key($post_types, ['elementor_library', 'attachment']);
    }

    /**
     * POst Orderby Options
     *
     * @return array
     */
    public static function eael_get_post_orderby_options()
    {
        $orderby = array(
            'ID' => 'Post ID',
            'author' => 'Post Author',
            'title' => 'Title',
            'date' => 'Date',
            'modified' => 'Last Modified Date',
            'parent' => 'Parent Id',
            'rand' => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order' => 'Menu Order',
        );

        return $orderby;
    }

    /**
     * This function is responsible for counting doc post under a category.
     *
     * @param int $term_count
     * @param int $term_id
     * @return int $term_count;
     */
    protected static function eael_get_doc_post_count($term_count = 0, $term_id)
    {
        $tax_terms = get_terms('doc_category', ['child_of' => $term_id]);

        foreach ($tax_terms as $tax_term) {
            $term_count += $tax_term->count;
        }
        return $term_count;
    }

    /**
     * Get Post Categories
     *
     * @return array
     */
    public static function eael_post_type_categories($type = 'term_id', $term_key = 'category')
    {
        $terms = get_terms(array(
            'taxonomy' => $term_key,
            'hide_empty' => true,
        ));

        $options = [];

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->{$type}] = $term->name;
            }
        }

        return $options;
    }

    /**
     * WooCommerce Product Query
     *
     * @return array
     */
    public static function eael_woocommerce_product_categories()
    {
        $terms = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
            return $options;
        }
    }

    /**
     * WooCommerce Get Product By Id
     *
     * @return array
     */
    public static function eael_woocommerce_product_get_product_by_id()
    {
        $postlist = get_posts(array(
            'post_type' => 'product',
            'showposts' => 9999,
        ));
        $options = array();

        if (!empty($postlist) && !is_wp_error($postlist)) {
            foreach ($postlist as $post) {
                $options[$post->ID] = $post->post_title;
            }
            return $options;

        }
    }

    /**
     * WooCommerce Get Product Category By Id
     *
     * @return array
     */
    public static function eael_woocommerce_product_categories_by_id()
    {
        $terms = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
            return $options;
        }

    }

    /**
     * Get Contact Form 7 [ if exists ]
     */
    public static function eael_select_contact_form()
    {
        $options = array();

        if (function_exists('wpcf7')) {
            $wpcf7_form_list = get_posts(array(
                'post_type' => 'wpcf7_contact_form',
                'showposts' => 999,
            ));
            $options[0] = esc_html__('Select a Contact Form', 'essential-addons-for-elementor-lite');
            if (!empty($wpcf7_form_list) && !is_wp_error($wpcf7_form_list)) {
                foreach ($wpcf7_form_list as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
            }
        }
        return $options;
    }

    /**
     * Get Gravity Form [ if exists ]
     *
     * @return array
     */
    public static function eael_select_gravity_form()
    {
        $options = array();

        if (class_exists('GFCommon')) {
            $gravity_forms = \RGFormsModel::get_forms(null, 'title');

            if (!empty($gravity_forms) && !is_wp_error($gravity_forms)) {

                $options[0] = esc_html__('Select Gravity Form', 'essential-addons-for-elementor-lite');
                foreach ($gravity_forms as $form) {
                    $options[$form->id] = $form->title;
                }

            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
            }
        }

        return $options;
    }

    /**
     * Get WeForms Form List
     *
     * @return array
     */
    public static function eael_select_weform()
    {
        $wpuf_form_list = get_posts(array(
            'post_type' => 'wpuf_contact_form',
            'showposts' => 999,
        ));

        $options = array();

        if (!empty($wpuf_form_list) && !is_wp_error($wpuf_form_list)) {
            $options[0] = esc_html__('Select weForm', 'essential-addons-for-elementor-lite');
            foreach ($wpuf_form_list as $post) {
                $options[$post->ID] = $post->post_title;
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
        }

        return $options;
    }

    /**
     * Get Ninja Form List
     *
     * @return array
     */
    public static function eael_select_ninja_form()
    {
        $options = array();

        if (class_exists('Ninja_Forms')) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {

                $options[0] = esc_html__('Select Ninja Form', 'essential-addons-for-elementor-lite');

                foreach ($contact_forms as $form) {
                    $options[$form->get_id()] = $form->get_setting('title');
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
        }

        return $options;
    }

    /**
     * Get Caldera Form List
     *
     * @return array
     */
    public static function eael_select_caldera_form()
    {
        $options = array();

        if (class_exists('Caldera_Forms')) {
            $contact_forms = \Caldera_Forms_Forms::get_forms(true, true);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select Caldera Form', 'essential-addons-for-elementor-lite');
                foreach ($contact_forms as $form) {
                    $options[$form['ID']] = $form['name'];
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
        }

        return $options;
    }

    /**
     * Get WPForms List
     *
     * @return array
     */
    public static function eael_select_wpforms_forms()
    {
        $options = array();

        if (class_exists('\WPForms\WPForms')) {
            $args = array(
                'post_type' => 'wpforms',
                'posts_per_page' => -1,
            );

            $contact_forms = get_posts($args);

            if (!empty($contact_forms) && !is_wp_error($contact_forms)) {
                $options[0] = esc_html__('Select a WPForm', 'essential-addons-for-elementor-lite');
                foreach ($contact_forms as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            }
        } else {
            $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
        }

        return $options;
    }

    /**
     * Get FluentForms List
     *
     * @return array
     */
    public static function eael_select_fluent_forms()
    {

        $options = array();

        if (defined('FLUENTFORM')) {
            global $wpdb;

            $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}fluentform_forms");
            if ($result) {
                $options[0] = esc_html__('Select a Fluent Form', 'essential-addons-for-elementor-lite');
                foreach ($result as $form) {
                    $options[$form->id] = $form->title;
                }
            } else {
                $options[0] = esc_html__('Create a Form First', 'essential-addons-for-elementor-lite');
            }
        }

        return $options;

    }

    /**
     * Get all elementor page templates
     *
     * @return array
     */
    public static function eael_get_page_templates($type = null)
    {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];

        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }

        $page_templates = get_posts($args);
        $options = array();

        if (!empty($page_templates) && !is_wp_error($page_templates)) {
            foreach ($page_templates as $post) {
                $options[$post->ID] = $post->post_title;
            }
        }
        return $options;
    }

    /**
     * Get all Authors
     *
     * @return array
     */
    public static function eael_get_authors()
    {
        $users = get_users([
            'who' => 'authors',
            'has_published_posts' => true,
            'fields' => [
                'ID',
                'display_name',
            ],
        ]);

        if (!empty($users)) {
            return wp_list_pluck($users, 'display_name', 'ID');
        }

        return [];
    }

    /**
     * Get all Tags
     *
     * @param  array  $args
     *
     * @return array
     */
    public static function eael_get_tags($args = array())
    {
        $options = [];
        $tags = get_tags($args);

        if (is_wp_error($tags)) {
            return [];
        }

        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name;
        }

        return $options;
    }

    /**
     * Get all taxonomies by post
     *
     * @param  array   $args
     *
     * @param  string  $output
     * @param  string  $operator
     *
     * @return array
     */
    public static function eael_get_taxonomies_by_post($args = [], $output = 'names', $operator = 'and')
    {
        global $wp_taxonomies;

        $field = ('names' === $output) ? 'name' : false;

        // Handle 'object_type' separately.
        if (isset($args['object_type'])) {
            $object_type = (array) $args['object_type'];
            unset($args['object_type']);
        }

        $taxonomies = wp_filter_object_list($wp_taxonomies, $args, $operator);

        if (isset($object_type)) {
            foreach ($taxonomies as $tax => $tax_data) {
                if (!array_intersect($object_type, $tax_data->object_type)) {
                    unset($taxonomies[$tax]);
                }
            }
        }

        if ($field) {
            $taxonomies = wp_list_pluck($taxonomies, $field);
        }

        return $taxonomies;
    }

    /**
     * Get all Posts
     *
     * @return array
     */
    public static function eael_get_posts()
    {
        $post_list = get_posts(array(
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $posts = array();

        if (!empty($post_list) && !is_wp_error($post_list)) {
            foreach ($post_list as $post) {
                $posts[$post->ID] = $post->post_title;
            }
        }

        return $posts;
    }

    /**
     * Get all Pages
     *
     * @return array
     */
    public static function eael_get_pages()
    {
        $page_list = get_posts(array(
            'post_type' => 'page',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $pages = array();

        if (!empty($page_list) && !is_wp_error($page_list)) {
            foreach ($page_list as $page) {
                $pages[$page->ID] = $page->post_title;
            }
        }

        return $pages;
    }

    /**
     * This function is responsible for get the post data.
     * It will return HTML markup with AJAX call and with normal call.
     *
     * @return string of an html markup with AJAX call.
     * @return array of content and found posts count without AJAX call.
     */
    public static function eael_load_more_ajax()
    {
        parse_str($_REQUEST['args'], $args);
        parse_str($_REQUEST['settings'], $settings);

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

        $html = $class::render_template_($args, $settings);

        echo $html;
        wp_die();
    }

    public static function eael_list_ninja_tables()
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

    protected static function get_terms_as_list($term_type = 'category', $length = 1)
    {

        if ($term_type === 'category') {
            $terms = get_the_category();
        }

        if ($term_type === 'tags') {
            $terms = get_the_tags();
        }

        if (empty($terms)) {
            return;
        }

        $html = '<ul class="post-carousel-categories">';
        $count = 0;
        foreach ($terms as $term) {
            if ($count === $length) {break;}
            $link = ($term_type === 'category') ? get_category_link($term->term_id) : get_tag_link($term->term_id);
            $html .= '<li>';
            $html .= '<a href="' . esc_url($link) . '">';
            $html .= $term->name;
            $html .= '</a>';
            $html .= '</li>';
            $count++;
        }
        $html .= '</ul>';

        return $html;

    }
}

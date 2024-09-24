<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use \Elementor\Utils;
use Elementor\Plugin;

class Helper
{


	const EAEL_ALLOWED_HTML_TAGS = [
		'article',
		'aside',
		'div',
		'footer',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'header',
		'main',
		'nav',
		'p',
		'section',
		'span',
	];

    /**
     * It stores all faqs data for all ea elements
     * @since 5.1.9
     */
    public static $eael_advanced_accordion_faq = [];
    
    /**
     * Returns all the faqs in one instance
     *
     * @since 5.1.9
     * @return array
     */
    public static function get_eael_advanced_accordion_faq(){
        $json = [];
        if( count( self::$eael_advanced_accordion_faq ) ) {
            $json = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => self::$eael_advanced_accordion_faq,
            ];
        }
        
        return $json;
    }

    /**
     * Adds faq to the faq list
     * @since 5.1.9
     * @param array $faq single faq data - question and answer
     */
    public static function set_eael_advanced_accordion_faq( $faq ){
        return self::$eael_advanced_accordion_faq[] = $faq;
    }

    /**
     * Include a file with variables
     *
     * @param $file_path
     * @param $variables
     *
     * @return string
     * @since  4.2.2
     */
    public static function include_with_variable( $file_path, $variables = [])
    {
        if (file_exists($file_path)) {
            extract($variables);

            ob_start();

            include $file_path;

            return ob_get_clean();
        }

        return '';
    }

    /**
     * check EAEL extension can load this page or post
     *
     * @param $id  page or post id
     *
     * @return bool
     * @since  4.0.4
     */
    public static function prevent_extension_loading($post_id)
    {
        $template_name = get_post_meta($post_id, '_elementor_template_type', true);
        $template_list = [
            'header',
            'footer',
            'single',
            'post',
            'page',
            // 'archive',
            'search-results',
            'error-404',
            // 'product',
            // 'product-archive',
            'section',
        ];

        return in_array($template_name, $template_list);
    }

	public static function str_to_css_id( $str ) {
		$str = strtolower( $str );

		//Make alphanumeric (removes all other characters)
		$str = preg_replace( "/[^a-z0-9_\s-]/", "", $str );

		//Clean up multiple dashes or whitespaces
		$str = preg_replace( "/[\s-]+/", " ", $str );

		//Convert whitespaces and underscore to dash
		$str = preg_replace( "/[\s_]/", "-", $str );

		return $str;
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

    public static function get_query_args($settings = [], $post_type = 'post')
    {
	    $settings = wp_parse_args( $settings, [
		    'post_type'      => $post_type,
		    'posts_ids'      => [],
		    'orderby'        => 'date',
		    'order'          => 'desc',
		    'posts_per_page' => 3,
		    'offset'         => 0,
		    'post__not_in'   => [],
	    ] );

	    $args = [
		    'orderby'             => $settings['orderby'],
		    'order'               => $settings['order'],
		    'ignore_sticky_posts' => 1,
		    'post_status'         => 'publish',
		    'posts_per_page'      => $settings['posts_per_page'],
		    'offset'              => $settings['offset'],
	    ];

	    if ( 'by_id' === $settings['post_type'] ) {
		    $args['post_type'] = 'any';
		    $args['post__in']  = empty( $settings['posts_ids'] ) ? [ 0 ] : $settings['posts_ids'];
	    } else {
		    $args['post_type'] = $settings['post_type'];
		    $args['tax_query'] = [];

		    $taxonomies = get_object_taxonomies( $settings['post_type'], 'objects' );

		    foreach ( $taxonomies as $object ) {
			    $setting_key = $object->name . '_ids';

			    if ( ! empty( $settings[ $setting_key ] ) ) {
				    $args['tax_query'][] = [
					    'taxonomy' => $object->name,
					    'field'    => 'term_id',
					    'terms'    => $settings[ $setting_key ],
				    ];
			    }
		    }

		    if ( ! empty( $args['tax_query'] ) ) {
			    $args['tax_query']['relation'] = 'AND';
		    }
	    }

	    if ( $args['orderby'] === 'most_viewed' ) {
		    $args['orderby']  = 'meta_value_num';
		    $args['meta_key'] = '_eael_post_view_count';
	    }

	    if ( ! empty( $settings['authors'] ) ) {
		    $args['author__in'] = $settings['authors'];
	    }

	    if ( ! empty( $settings['post__not_in'] ) ) {
		    $args['post__not_in'] = $settings['post__not_in'];
	    }

        if( 'product' === $post_type && function_exists('whols_lite') ){
            $args['meta_query'] = array_filter( apply_filters( 'woocommerce_product_query_meta_query', $args['meta_query'], new \WC_Query() ) );
        }

        return $args;
    }

    /**
     * Go Premium
     *
     */
    public static function go_premium($wb)
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
                        'title' => '',
                        'icon' => 'fa fa-unlock-alt',
                    ],
                ],
                'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
            ]
        );

        $wb->end_controls_section();
    }

    /**
     * Get All POst Types
     * @return array
     */
    public static function get_post_types()
    {
        $post_types = get_post_types(['public' => true, 'show_in_nav_menus' => true], 'objects');
        $post_types = wp_list_pluck($post_types, 'label', 'name');

        return array_diff_key($post_types, ['elementor_library', 'attachment']);
    }

    /**
     * Get all types of post.
     *
     * @param  string  $post_type
     *
     * @return array
     */
    public static function get_post_list($post_type = 'any')
    {
        return self::get_query_post_list($post_type);
    }

    /**
     * POst Orderby Options
     *
     * @return array
     */
    public static function get_post_orderby_options()
    {
	    $orderby = array(
		    'ID'            => __( 'Post ID', 'essential-addons-for-elementor-lite' ),
		    'author'        => __( 'Post Author', 'essential-addons-for-elementor-lite' ),
		    'title'         => __( 'Title', 'essential-addons-for-elementor-lite' ),
		    'date'          => __( 'Date', 'essential-addons-for-elementor-lite' ),
		    'modified'      => __( 'Last Modified Date', 'essential-addons-for-elementor-lite' ),
		    'parent'        => __( 'Parent Id', 'essential-addons-for-elementor-lite' ),
		    'rand'          => __( 'Random', 'essential-addons-for-elementor-lite' ),
		    'comment_count' => __( 'Comment Count', 'essential-addons-for-elementor-lite' ),
		    'most_viewed'   => __( 'Most Viewed', 'essential-addons-for-elementor-lite' ),
		    'menu_order'    => __( 'Menu Order', 'essential-addons-for-elementor-lite' )
	    );

        return $orderby;
    }

    /**
     * Get Post Categories
     *
     * @return array
     */
    public static function get_terms_list($taxonomy = 'category', $key = 'term_id')
    {
        $options = [];
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->{$key}] = $term->name;
            }
        }

        return $options;
    }

    /**
     * Get all elementor page templates
     *
     * @param  null  $type
     *
     * @return array
     */
    public static function get_elementor_templates($type = null)
    {
        $options = [];

        if ($type) {
            $args = [
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
            ];
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];

            $page_templates = get_posts($args);

            if (!empty($page_templates) && !is_wp_error($page_templates)) {
                foreach ($page_templates as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            }
        } else {
            $options = self::get_query_post_list('elementor_library');
        }

        return $options;
    }

    /**
     * Get all Authors
     *
     * @return array
     */
	public static function get_authors_list() {
		$args = [
			'capability'          => [ 'edit_posts' ],
			'has_published_posts' => true,
			'fields'              => [
				'ID',
				'display_name',
			],
		];

		// Capability queries were only introduced in WP 5.9.
		if ( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ) {
			$args['who'] = 'authors';
			unset( $args['capability'] );
		}

		$users = get_users( $args );

		if ( ! empty( $users ) ) {
			return wp_list_pluck( $users, 'display_name', 'ID' );
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
    public static function get_tags_list($args = array())
    {
        $options = [];
        $tags = get_tags($args);

        if (!is_wp_error($tags) && !empty($tags)) {
            foreach ($tags as $tag) {
                $options[$tag->term_id] = $tag->name;
            }
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
    public static function get_taxonomies_by_post($args = [], $output = 'names', $operator = 'and')
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
     * Get Contact Form 7 [ if exists ]
     */
    public static function get_wpcf7_list()
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
    public static function get_gravity_form_list()
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
    public static function get_weform_list()
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
    public static function get_ninja_form_list()
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
    public static function get_caldera_form_list()
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
    public static function get_wpforms_list()
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



    public static function get_ninja_tables_list()
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

    public static function get_terms_as_list($term_type = 'category', $length = 1)
    {
	    $terms = get_the_terms( get_the_ID(), $term_type );

        if ($term_type === 'category') {
            $terms = get_the_category();
        }

        if ($term_type === 'tags') {
            $terms = get_the_tags();
        }

        if (empty($terms)) {
            return;
        }

        $count = 0;

        $html = '<ul class="post-carousel-categories">';
        foreach ($terms as $term) {
            if ( $count === absint( $length ) ) {
                break;
            }
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

	/**
	 * Returns product categories list
	 *
	 * @return string
	 */
	public static function get_product_categories_list($terms_name) {
		global $product;

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return ''; 
		}

		$separator = '';
		$before    = '<ul class="eael-product-cats"><li>';
		$after     = '</li></ul>';

		return get_the_term_list( $product->get_id(), $terms_name, $before, $separator, $after );
	}

    /**
     * This function is responsible for counting doc post under a category.
     *
     * @param int $term_count
     * @param int $term_id
     * @return int $term_count;
     */
    public static function get_doc_post_count($term_count = 0, $term_id = 0)
    {
        $tax_terms = get_terms('doc_category', ['child_of' => $term_id]);

        foreach ($tax_terms as $tax_term) {
            $term_count += $tax_term->count;
        }

        return $term_count;
    }

    public static function get_dynamic_args(array $settings, array $args)
    {
	    if ( $settings['post_type'] === 'source_dynamic' && ( is_archive() || is_search() ) ) {
            $data = get_queried_object();

            if (isset($data->post_type)) {
                $args['post_type'] = $data->post_type;
                $args['tax_query'] = [];
            } else {
                global $wp_query;
                $args['post_type'] = $wp_query->query_vars['post_type'];
                if(!empty($wp_query->query_vars['s'])){
                    $args['s'] = $wp_query->query_vars['s'];
                    $args['offset'] = 0;
                }
            }

            if ( isset( $data->taxonomy ) ) {
                $args[ 'tax_query' ][] = [
                    'taxonomy' => $data->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $data->term_id,
                ];
            }

            if ( isset($data->taxonomy) ) {
                $args[ 'tax_query' ][] = [
                    'taxonomy' => $data->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $data->term_id,
                ];
            }

            if (get_query_var('author') > 0) {
                $args['author__in'] = get_query_var('author');
            }

            if (get_query_var('s')!='') {
                $args['s'] = get_query_var('s');
            }

            if (get_query_var('year') || get_query_var('monthnum') || get_query_var('day')) {
                $args['date_query'] = [
                    'year' => get_query_var('year'),
                    'month' => get_query_var('monthnum'),
                    'day' => get_query_var('day'),
                ];
            }

            if (!empty($args['tax_query'])) {
                $args['tax_query']['relation'] = 'AND';
            }

            $args[ 'meta_query' ] = [ 'relation' => 'AND' ];
            $show_stock_out_products = isset( $settings['eael_product_out_of_stock_show'] ) ? $settings['eael_product_out_of_stock_show'] : 'yes';

            if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' || 'yes' !== $show_stock_out_products  ) {
                $args[ 'meta_query' ][] = [
                    'key'   => '_stock_status',
                    'value' => 'instock'
                ];
            }
            if( 'product' === $args['post_type'] && function_exists('whols_lite') ){
                $args['meta_query'] = array_filter( apply_filters( 'woocommerce_product_query_meta_query', $args['meta_query'], new \WC_Query() ) );
            }
        }

        return $args;
    }

    public static function get_multiple_kb_terms($prettify = false, $term_id = true)
    {
        $args = [
            'taxonomy' => 'knowledge_base',
            'hide_empty' => true,
            'parent' => 0,
        ];

        $terms = get_terms($args);

        if (is_wp_error($terms)) {
            return [];
        }

        if ($prettify) {
            $pretty_taxonomies = [];

            foreach ($terms as $term) {
                $pretty_taxonomies[$term_id ? $term->term_id : $term->slug] = $term->name;
            }

            return $pretty_taxonomies;
        }

        return $terms;
    }

    public static function get_betterdocs_multiple_kb_status()
    {
        if (\BetterDocs_DB::get_settings('multiple_kb') == 1) {
            return 'true';
        }

        return '';
    }

    public static function get_query_post_list($post_type = 'any', $limit = -1, $search = '')
    {
        global $wpdb;
        $where = '';
        $data = [];

        if (-1 == $limit) {
            $limit = '';
        } elseif (0 == $limit) {
            $limit = "limit 0,1";
        } else {
            $limit = $wpdb->prepare(" limit 0,%d", esc_sql($limit));
        }

        if ('any' === $post_type) {
            $in_search_post_types = get_post_types(['exclude_from_search' => false]);
            if (empty($in_search_post_types)) {
                $where .= ' AND 1=0 ';
            } else {
                $where .= " AND {$wpdb->posts}.post_type IN ('" . join("', '",
                    array_map('esc_sql', $in_search_post_types)) . "')";
            }
        } elseif (!empty($post_type)) {
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_type = %s", esc_sql($post_type));
        }

        if (!empty($search)) {
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql($search) . '%');
        }

        $query = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            foreach ($results as $row) {
                $data[$row->ID] = $row->post_title;
            }
        }
        return $data;
    }

    public static function eael_get_widget_settings( $page_id, $widget_id ) {
        $document = Plugin::$instance->documents->get( $page_id );
        $settings = [];
        if ( $document ) {
            $elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
            $widget_data = self::find_element_recursive( $elements, $widget_id );
            if (!empty($widget_data) && is_array($widget_data)) {
                $widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
            }
            if ( !empty($widget) ) {
                $settings    = $widget->get_settings_for_display();
            }
        }
        return $settings;
    }

    /**
     * Get Widget data.
     *
     * @param array  $elements Element array.
     * @param string $form_id  Element ID.
     *
     * @return bool|array
     */
    public static function find_element_recursive( $elements, $form_id ) {

        foreach ( $elements as $element ) {
            if ( $form_id === $element['id'] ) {
                return $element;
            }

            if ( ! empty( $element['elements'] ) ) {
                $element = self::find_element_recursive( $element['elements'], $form_id );

                if ( $element ) {
                    return $element;
                }
            }
        }

        return false;
    }

	/**
	 * eael_pagination
     * Generate post pagination
     *
	 * @param $args array wp_query param
	 * @param $settings array Elementor widget setting data
	 *
     * @access public
	 * @return string|void
     * @since 3.3.0
	 */
	public static function eael_pagination ($args, $settings) {

		$pagination_Count          = intval( $args['total_post'] ?? 0 );
		$paginationLimit           = intval( $settings['eael_product_grid_products_count'] ) ?: 4;
		$pagination_Paginationlist = ceil( $pagination_Count / $paginationLimit );
		$widget_id                 = sanitize_key( $settings['eael_widget_id'] );
		$page_id                   = intval( $settings['eael_page_id'] );
		$next_label                = $settings['pagination_next_label'];
		$adjacents                 = "2";
		$setPagination             = "";
		$template_info             = [
			'dir'       => 'free',
			'file_name'  => $settings['eael_dynamic_template_Layout'],
			'name'      => $settings['eael_widget_name']
		];

		if( $pagination_Paginationlist > 0 ){

			$setPagination .="<nav id='{$widget_id}-eael-pagination' class='eael-woo-pagination' data-plimit='$paginationLimit' data-totalpage ='{$args['total_post']}' data-widgetid='{$widget_id}' data-pageid='$page_id' data-args='".http_build_query( $args )."'  data-template='".json_encode( $template_info, 1 )."'>";
			    $setPagination .="<ul class='page-numbers'>";

                    if ( $pagination_Paginationlist < 7 + ($adjacents * 2) ){
                        for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
                            $active        = ( $pagination == 0 || $pagination == 1 ) ? 'current' : '';
	                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" ,$active ,$pagination);
                        }

                    } else if ( $pagination_Paginationlist >= 5 + ($adjacents * 2) ){
                        for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {
                            $active        = ( $pagination == 0 || $pagination == 1 ) ? 'current' : '';
	                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" ,$active ,$pagination);
                        }

                        $setPagination .="<li class='pagitext dots'>...</li>";
                        $setPagination .= sprintf("<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>" ,$active ,$pagination);
                    }

                    if ($pagination_Paginationlist > 1) {
                        $setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='2'>".esc_html( $next_label )."</a></li>";
                    }

                $setPagination .="</ul>";
			$setPagination .="</nav>";

			return $setPagination;
		}
	}

	public static function eael_product_quick_view ($product, $settings, $widget_id) {

		$sale_badge_align  = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
		$sale_badge_preset = isset( $settings['eael_product_sale_badge_preset'] ) ? $settings['eael_product_sale_badge_preset'] : '';
		$sale_text         = ! empty( $settings['eael_product_carousel_sale_text'] ) ? $settings['eael_product_carousel_sale_text'] : (! empty( $settings['eael_product_sale_text'] ) ? $settings['eael_product_sale_text'] :( !empty( $settings['eael_product_gallery_sale_text'] ) ? $settings['eael_product_gallery_sale_text'] : 'Sale!' ));
		$stockout_text     = ! empty( $settings['eael_product_carousel_stockout_text'] ) ? $settings['eael_product_carousel_stockout_text'] : (! empty( $settings['eael_product_stockout_text'] ) ? $settings['eael_product_stockout_text'] : ( !empty($settings['eael_product_gallery_stockout_text']) ? $settings['eael_product_gallery_stockout_text'] : 'Stock Out' ));
        $tag               = ! empty( $settings['eael_product_quick_view_title_tag'] ) ? self::eael_validate_html_tag( $settings['eael_product_quick_view_title_tag'] ) : 'h1';
        
        remove_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
        add_action( 'eael_woo_single_product_summary', function () use ( $tag ) {
            printf('<%1$s class="eael-product-quick-view-title product_title entry-title">%2$s</%1$s>',$tag,Helper::eael_wp_kses( get_the_title() ));
        }, 5 );

	    ?>

		<div id="eaproduct<?php echo esc_attr( $widget_id . $product->get_id() ); ?>" class="eael-product-popup
		eael-product-zoom-in woocommerce">
			<div class="eael-product-modal-bg"></div>
			<div class="eael-product-popup-details">
				<div id="product-<?php esc_attr( get_the_ID() ); ?>" <?php post_class( 'product' ); ?>>
					<div class="eael-product-image-wrap">
						<?php
						echo ( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.esc_attr( $sale_badge_preset ).' '.esc_attr( $sale_badge_align ).'">'. Helper::eael_wp_kses( $stockout_text ) .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.esc_attr( $sale_badge_preset ).' '.esc_attr( $sale_badge_align ).'">' . Helper::eael_wp_kses( $sale_text ) . '</span>' : '') );
						do_action( 'eael_woo_single_product_image' );
						?>
					</div>
					<div class="eael-product-details-wrap">
						<?php do_action( 'eael_woo_single_product_summary' ); ?>
					</div>
				</div>
				<button class="eael-product-popup-close"><i class="fas fa-times"></i></button>
			</div>

		</div>
	<?php
	}

	public static function eael_avoid_redirect_to_single_page() {
		return '';
	}

	public static function eael_woo_product_grid_actions() {

		add_filter( 'woocommerce_add_to_cart_form_action', self::eael_avoid_redirect_to_single_page(), 10 );
		add_action( 'eael_woo_before_product_loop', 'woocommerce_output_all_notices', 30 );

	}

    public static function get_local_plugin_data( $basename = '' ) {
        if ( empty( $basename ) ) {
            return false;
        }

        if ( !function_exists( 'get_plugins' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        if ( !isset( $plugins[ $basename ] ) ) {
            return false;
        }

        return $plugins[ $basename ];
    }

	/**
	 * eael_validate_html_tag
	 * @param $tag
	 * @return mixed|string
	 */
    public static function eael_validate_html_tag( $tag ){
	    return in_array( strtolower( $tag ), self::EAEL_ALLOWED_HTML_TAGS ) ? $tag : 'div';
    }

	/**
     *
     * Strip tag based on allowed html tag
	 * eael_wp_kses
	 * @param $text
	 * @return string
	 */
	public static function eael_wp_kses( $text ) {
        if ( empty( $text ) ) {
            return '';
        }
		return wp_kses( $text, self::eael_allowed_tags(), array_merge( wp_allowed_protocols(), [ 'data' ] ) );
	}

	/**
     * List of allowed html tag for wp_kses
     *
	 * eael_allowed_tags
	 * @return array
	 */
	public static function eael_allowed_tags( $extra = [] ) {
		$allowed_tags = [
			'a'       => [
				'href'   => [],
				'title'  => [],
				'class'  => [],
				'rel'    => [],
				'id'     => [],
				'style'  => [],
				'target' => [],
				'data-elementor-open-lightbox' => [],
			],
			'q'       => [
				'cite'  => [],
				'class' => [],
				'id'    => [],
			],
			'img'     => [
				'src'    => [],
				'alt'    => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'style'  => []
			],
			'span'    => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'dfn'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'time'    => [
				'datetime' => [],
				'class'    => [],
				'id'       => [],
				'style'    => [],
			],
			'cite'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'hr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'b'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'p'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'i'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'u'       => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			's'       => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'br'      => [],
			'em'      => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'code'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'mark'    => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'small'   => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'abbr'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'strong'  => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'del'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'ins'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'sub'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'sup'     => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'div'     => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
			'strike'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'acronym' => [],
			'h1'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h2'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h3'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h4'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h5'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'h6'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'button'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'center'  => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ul'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'ol'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'li'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'table'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'dir'   => [],
				'align' => [],
			],
			'thead'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tbody'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'tfoot'   => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'th'      => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
			'tr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
				'align' => [],
			],
			'td'     => [
				'class'   => [],
				'id'      => [],
				'style'   => [],
				'align'   => [],
				'colspan' => [],
				'rowspan' => [],
			],
			'header' => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'iframe' => [
				'class'  => [],
				'id'     => [],
				'style'  => [],
				'title'  => [],
				'width'  => [],
				'height' => [],
				'src'    => []
			]
		];

		if ( count( $extra ) > 0 ) {
			$allowed_tags = array_merge_recursive( $allowed_tags, $extra );
		}

		return apply_filters( 'eael_allowed_tags', $allowed_tags );
	}

    /**
     * List of allowed icon/svg tags for wp_kses
     *
	 * eael_allowed_icon_tags
	 * @return array
	 */
    public static function eael_allowed_icon_tags(){
        return [
            'svg'   => [
                'class'           => [],
                'aria-hidden'     => [],
                'aria-labelledby' => [],
                'role'            => [],
                'xmlns'           => [],
                'width'           => [],
                'height'          => [],
                'viewbox'         => []
            ],
            'g'     => [ 'fill'  => [] ],
            'title' => [ 'title' => [] ],
            'path'     => [
                'd'    => [], 
                'fill' => [] 
            ],
			'i'      => [
				'class' => [],
				'id'    => [],
				'style' => []
			],
            'img'     => [
				'src'    => [],
				'alt'    => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'style'  => []
			],
        ];
    }

    public static function eael_fetch_color_or_global_color($settings, $control_name=''){
        if( !isset($settings[$control_name])) {
            return '';
        }

        $color = $settings[$control_name];

        if(!empty($settings['__globals__']) && !empty($settings['__globals__'][$control_name])){
            $color = $settings['__globals__'][$control_name];
            $color_arr = explode('?id=', $color); //E.x. 'globals/colors/?id=primary'

            $color_name = count($color_arr) > 1 ? $color_arr[1] : '';
            if( !empty($color_name) ) {
                $color = "var( --e-global-color-$color_name )";
            }
        }

        return $color;
    }

	/**
	 * Get Render Icon
	 *
	 * Used to get render Icon for \Elementor\Controls_Manager::ICONS
	 * @param array $icon             Icon Type, Icon value
	 * @param array $attributes       Icon HTML Attributes
	 * @param string $tag             Icon HTML tag, defaults to <i>
	 *
	 * @return mixed|string
	 */
	public static function get_render_icon( $icon, $attributes = [], $tag = 'i' ) {
		if ( empty( $icon['library'] ) ) {
			return false;
		}

		$output = '';

		/**
		 * When the library value is svg it means that it's a SVG media attachment uploaded by the user.
		 * Otherwise, it's the name of the font family that the icon belongs to.
		 */
		if ( 'svg' === $icon['library'] ) {
			$output = method_exists( 'Elementor\Icons_Manager', 'render_uploaded_svg_icon' ) ? Icons_Manager::render_uploaded_svg_icon( $icon['value'] ) : '';
		} else {
			$output = method_exists( 'Elementor\Icons_Manager', 'render_font_icon' ) ? Icons_Manager::render_font_icon( $icon, $attributes, $tag ) : '';
		}

		return $output;
	}

    /**
     * Get SVG html by Icon
     *
     * Used to get svg attributes from Icon class for SVG Drawing widget
     * @param string $icon             Icon
     *
     * @return string
     */
    public static function get_svg_by_icon( $icon ) {
        if ( empty( $icon ) || empty( $icon['value'] ) || empty( $icon['library'] ) ) return '';

        $svg_html = "";

        $icon_name  = str_replace( [ 'fas fa-', 'fab fa-', 'far fa-' ], '', $icon['value'] );
        $library    = str_replace( 'fa-', '', $icon['library'] );
        $svg_object = file_get_contents( EAEL_PLUGIN_PATH . "assets/front-end/js/lib-view/icons/{$library}.json" );
        $svg_object = json_decode( $svg_object, true );
        $i_class    = str_replace(' ', '-', $icon['value']);

        if ( empty( $svg_object['icons'][$icon_name] ) ) return $svg_html;

        $icon       = $svg_object['icons'][$icon_name];
        $view_box   = "0 0 {$icon[0]} {$icon[1]}";
        $svg_html  .= "<svg class='svg-inline--". $i_class ."  eael-svg-icon' aria-hidden='true' data-icon='store' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='{$view_box}' >";
        $svg_html  .= "<path d='{$icon[4]}'></path>";
        $svg_html  .= "</svg>";

        return $svg_html;
    }
    
    /**
     * Get product image src and Product gallery's first image src
     * 
     * @since 5.1.9
     * @return array 
     */
    public static function eael_get_woo_product_gallery_image_srcs( $product, $image_size ){
        $image_id = $product->get_image_id();
        $image_gallery_ids = $product->get_gallery_image_ids();

        $src = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : '';

        if ( $image_id ) {
            $src = wp_get_attachment_image_src( $image_id, $image_size );
            $src = is_array($src) ? $src[0] : $src;
        }

        $src_hover = count( $image_gallery_ids ) ? wp_get_attachment_image_src( $image_gallery_ids[0], $image_size ) : '';
        $src_hover = is_array($src_hover) ? $src_hover[0] : $src_hover;
        
        return [
            'src' => $src,
            'src_hover' => $src_hover,
        ];
    }

	/**
	 * Sanitize a 'relation' operator.
	 *
	 * @param string $relation Raw relation key from the query argument.
	 *
	 * @return string Sanitized relation ('AND' or 'OR').
	 * @since 5.3.2
	 *
	 */
	public static function eael_sanitize_relation( $relation ) {
		if ( 'OR' === strtoupper( $relation ) ) {
			return 'OR';
		} else {
			return 'AND';
		}
	}

    /**
     * Get all ordered products by the user
     * @return boolean|array order ids
     * @since 5.8.9
     */
    public static function eael_get_all_user_ordered_products() {
        $user_id = get_current_user_id();

        if( ! $user_id ) {
            return false;
        }

        $args = array(
            'customer_id' => $user_id,
            'limit' => -1,
        );

        $orders = wc_get_orders($args);
        $product_ids = [];

        foreach( $orders as $order ){
            $items = $order->get_items();
            
            foreach($items as $item){
                $product_ids[] = $item->get_product_id();
            }
        }

        return $product_ids;
    }

	/**
	 * Get current device by screen size
	 *
	 *
	 * @return string device name.
	 * @since 5.9.1
	 *
	 */
	public static function eael_get_current_device_by_screen() {
		if ( ! session_id() ) {
			session_start( [
				'read_and_close' => true,
			] );
		}

		if ( isset( $_SESSION['eael_screen'] ) && ! empty( $breakpoints = Plugin::$instance->breakpoints->get_breakpoints_config() ) ) {
			$breakpoints = array_filter( $breakpoints, function ( $breakpoint ) {
				return $breakpoint['is_enabled'];
			} );

			if ( isset( $breakpoints['widescreen'] ) ) {
				$widescreen = $breakpoints['widescreen'];
				unset( $breakpoints['widescreen'] );
				$breakpoints['desktop'] = $widescreen;
			}else{
                $breakpoints['desktop'] = [
                    'value' => 2400
                ];
            }
            
			$current_screen = intval( $_SESSION['eael_screen'] );
			foreach ( $breakpoints as $device => $screen ) {
				if ( $current_screen <= $screen['value'] ) {
					return $device;
				}
			}

			return "widescreen";
		}

		// If no match is found, you can return a default value or handle it as needed.
		return "unknown";
	}

    public static function get_all_acf_fields() {

        if( ! class_exists( 'ACF' ) ){
            return [];
        }

        // Get all registered post types
        $post_types = get_post_types( [ 'public' => true ], 'names' );
        $acf_fields = [];
    
        // Loop through each post type
        foreach( $post_types as $post_type ) {
            // Query the first post of this post type
            $args = [
                'post_type'      => $post_type,
                'posts_per_page' => 1,
                'post_status'    => 'publish'
            ];
    
            $query = new \WP_Query($args);
    
            if( $query->have_posts() ) {
                while( $query->have_posts() ): $query->the_post();
    
                    // Get all field objects for this post
                    $fields = get_field_objects();
    
                    if( $fields ) {
                        foreach( $fields as $field_name => $field ) {
                            // Add field details to the array
                            $acf_fields[ $field_name ] = [
                                'label'     => $field['label'],
                                'name'      => $field_name,
                                'type'      => $field['type'],
                                'post_type' => $post_type,
                            ];
                        }
                    }
    
                endwhile;
                wp_reset_postdata();
            }
        }
    
        return $acf_fields;
    }  
      
    public static function eael_rating_markup( $rating, $count ) {
        $html = '';
		if ( 0 == $rating ) {
			$html  = '<div class="eael-star-rating star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}
}

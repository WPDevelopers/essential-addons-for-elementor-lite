<?php

/**
 * Get Post Data
 * @param  array $args
 * @return array
 */
function eael_get_post_data( $args ) {
    $defaults = array(
        'posts_per_page'   => 5,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'       => '',
        'author_name'      => '',
        'post_status'      => 'publish',
        'suppress_filters' => true
    );

    $atts = wp_parse_args( $args, $defaults );

    $posts = get_posts( $atts );

    return $posts;
}

/**
 * Get All POst Types
 * @return array
 */
function eael_get_post_types(){

    $eael_cpts = get_post_types( array( 'public'   => true, 'show_in_nav_menus' => true ) );
    $eael_exclude_cpts = array( 'elementor_library', 'attachment', 'product' );

    foreach ( $eael_exclude_cpts as $exclude_cpt ) {
        unset($eael_cpts[$exclude_cpt]);
    }

    $post_types = array_merge($eael_cpts);
    return $post_types;
}

/**
 * Add REST API support to an already registered post type.
 */
add_action( 'init', 'eael_custom_post_type_rest_support', 25 );
function eael_custom_post_type_rest_support() {
    global $wp_post_types;

    $post_types = eael_get_post_types();
    foreach( $post_types as $post_type ) {
        $post_type_name = $post_type;
        if( isset( $wp_post_types[ $post_type_name ] ) ) {
            $wp_post_types[$post_type_name]->show_in_rest = true;
            $wp_post_types[$post_type_name]->rest_base = $post_type_name;
            $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
        }
    }

}

/**
 * Post Settings Parameter
 * @param  array $settings
 * @return array
 */
function eael_get_post_settings($settings){
    $post_args['post_type'] = $settings['eael_post_type'];

    if($settings['eael_post_type'] == 'post'){
        $post_args['category'] = $settings['category'];
    }

    $post_args['posts_per_page'] = $settings['eael_posts_count'];
    $post_args['offset'] = $settings['eael_post_offset'];
    $post_args['orderby'] = $settings['eael_post_orderby'];
    $post_args['order'] = $settings['eael_post_order'];

    return $post_args;
}

/**
 * Getting Excerpts By Post Id
 * @param  int $post_id
 * @param  int $excerpt_length
 * @return string
 */
function eael_get_excerpt_by_id($post_id,$excerpt_length){
    $the_post = get_post($post_id); //Gets post ID

    $the_excerpt = null;
    if ($the_post)
    {
        $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
    }

    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

     if(count($words) > $excerpt_length) :
         array_pop($words);
         array_push($words, '…');
         $the_excerpt = implode(' ', $words);
     endif;

     return $the_excerpt;
}

/**
 * Get Post Thumbnail Size
 * @return array
 */
function eael_get_thumbnail_sizes(){
    $sizes = get_intermediate_image_sizes();
    foreach($sizes as $s){
        $ret[$s] = $s;
    }

    return $ret;
}

/**
 * POst Orderby Options
 * @return array
 */
function eael_get_post_orderby_options(){
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
 * Get Post Categories
 * @return array
 */
function eael_post_type_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'category',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->term_id ] = $term->name;
    }
    }

    return $options;
}

/**
 * WooCommerce Product Query
 * @return array
 */
function eael_woocommerce_product_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->slug ] = $term->name;
    }
    return $options;
    }
}

/**
 * WooCommerce Get Product By Id
 * @return array
 */
function eael_woocommerce_product_get_product_by_id(){
    $postlist = get_posts(array(
        'post_type' => 'product',
        'showposts' => 9999,
    ));
    $posts = array();

    if ( ! empty( $postlist ) && ! is_wp_error( $postlist ) ){
    foreach ( $postlist as $post ) {
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;

    }
}

/**
 * WooCommerce Get Product Category By Id
 * @return array
 */
function eael_woocommerce_product_categories_by_id(){
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->term_id ] = $term->name;
    }
    return $options;
    }

}

/**
 * Get Contact Form 7 [ if exists ]
 */
if ( function_exists( 'wpcf7' ) ) {
function eael_select_contact_form(){
    $wpcf7_form_list = get_posts(array(
        'post_type' => 'wpcf7_contact_form',
        'showposts' => 999,
    ));
    $posts = array();

    if ( ! empty( $wpcf7_form_list ) && ! is_wp_error( $wpcf7_form_list ) ){
    foreach ( $wpcf7_form_list as $post ) {
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;
    }
}
}

/**
 * Get Gravity Form [ if exists ]
 */

function eael_select_gravity_form() {

    $forms = RGFormsModel::get_forms( null, 'title' );
    foreach( $forms as $form ) {
      $options[ $form->id ] = $form->title;
    }
    return $options;

}

/**
 * Get WeForms Form List
 * @return array
 */
function eael_select_weform() {

    $wpuf_form_list = get_posts( array(
        'post_type' => 'wpuf_contact_form',
        'showposts' => 999,
    ));
    $posts = array();

    if ( ! empty( $wpuf_form_list ) && ! is_wp_error( $wpuf_form_list ) ) {
        foreach ( $wpuf_form_list as $post ) {
            $options[ $post->ID ] = $post->post_title;
        }
        return $options;
    }

}

/**
 * Get Ninja Form List
 * @return array
 */
function eael_select_ninja_form() {
    global $wpdb;
    $eael_nf_table_name = $wpdb->prefix.'nf3_forms';
    $forms = $wpdb->get_results( "SELECT id, title FROM $eael_nf_table_name" );
    foreach( $forms as $form ) {
        $options[$form->id] = $form->title;
    }
    return $options;
}

/**
 * Get Caldera Form List
 * @return array
 */
function eael_select_caldera_form() {
    global $wpdb;
    $eael_cf_table_name = $wpdb->prefix.'cf_forms';
    $forms = $wpdb->get_results( "SELECT * FROM $eael_cf_table_name" );
    foreach( $forms as $form ) {
        $unserialize = unserialize( $form->config );
        $form_title = $unserialize['name'];
        $options[$form->form_id] = $form_title;
    }
    return $options;
}


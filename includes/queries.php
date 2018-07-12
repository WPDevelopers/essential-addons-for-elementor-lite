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
        'suppress_filters' => true,
        'tag__in'          => '',
        'post__not_in'     => '',
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
 * Post Settings Parameter
 * @param  array $settings
 * @return array
 */
function eael_get_post_settings($settings){
    $post_args['post_type'] = $settings['eael_post_type'];
    $post_args['posts_per_page'] = $settings['eael_posts_count'];

    if($settings['eael_post_type'] == 'post' ){
        $tags = $categories = [];
        if( ! empty( $settings['eael_post_tags'] ) ) {
            $tags = [[
                'taxonomy' => 'post_tag',
                'field' => 'id',
                'terms' => $settings['eael_post_tags'],
            ]];
        }
        if( ! empty( $settings['category'] ) ) {
            $categories = [[
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $settings['category'],
            ]];
        }
        $relation = ! empty( $categories ) && ! empty( $tags ) ? [ 'relation' => 'OR' ] : [];
        $post_args['tax_query'] = array_merge($relation, $tags, $categories);
    }

    $eael_tiled_post_author = '';
    $eael_tiled_post_authors = $settings['eael_post_authors'];
    if ( !empty( $eael_tiled_post_authors) ) {
        $eael_tiled_post_author = implode( ",", $eael_tiled_post_authors );
        $post_args['author'] = $eael_tiled_post_author;
    }

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
function eael_get_excerpt_by_id($post_id = false, $excerpt_length, $post = false){
    if( $post_id && ! $post ) {
        $the_post = get_post($post_id); //Gets post ID
    } else {
        $the_post = $post;
    }
    
    $the_excerpt = null;
    if ($the_post instanceof \WP_Post) {
        $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
    } else {
        $the_excerpt = $the_post->excerpt->rendered ? $the_post->excerpt->rendered : $the_post->content->rendered;
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
    $options = array();

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
        $options = array();
        $options[0] = esc_html__( 'Select a Contact Form', 'essential-addons-elementor' );
        if ( ! empty( $wpcf7_form_list ) && ! is_wp_error( $wpcf7_form_list ) ){
            foreach ( $wpcf7_form_list as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        } else {
            $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
        }
        return $options;
    }
}

/**
 * Get Gravity Form [ if exists ]
 */
if ( !function_exists('eael_select_gravity_form') ) {
    function eael_select_gravity_form() {
        $options = array();
        if ( class_exists( 'GFCommon' ) ) {
            $gravity_forms = RGFormsModel::get_forms( null, 'title' );

            if ( ! empty( $gravity_forms ) && ! is_wp_error( $gravity_forms ) ) {

                $options[0] = esc_html__( 'Select Gravity Form', 'essential-addons-elementor' );
                foreach ( $gravity_forms as $form ) {   
                    $options[ $form->id ] = $form->title;
                }

            } else {
                $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
            }
        }

        return $options;
    }
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

    $options = array();
    
    if ( ! empty( $wpuf_form_list ) && ! is_wp_error( $wpuf_form_list ) ) {
        $options[0] = esc_html__( 'Select weForm', 'essential-addons-elementor' );
        foreach ( $wpuf_form_list as $post ) {
            $options[ $post->ID ] = $post->post_title;
        }
    } else {
        $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
    }
    
    return $options;
}

/**
 * Get Ninja Form List
 * @return array
 */
if ( !function_exists('eael_select_ninja_form') ) {
    function eael_select_ninja_form() {
        $options = array();
        if ( class_exists( 'Ninja_Forms' ) ) {
            $contact_forms = Ninja_Forms()->form()->get_forms();

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

                $options[0] = esc_html__( 'Select Ninja Form', 'essential-addons-elementor' );

                foreach ( $contact_forms as $form ) {   
                    $options[ $form->get_id() ] = $form->get_setting( 'title' );
                }
            }
        } else {
            $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
        }

        return $options;
    }
}

/**
 * Get Caldera Form List
 * @return array
 */
if ( !function_exists('eael_select_caldera_form') ) {
    function eael_select_caldera_form() {
        $options = array();
        if ( class_exists( 'Caldera_Forms' ) ) {

            $contact_forms = Caldera_Forms_Forms::get_forms( true, true );

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
                $options[0] = esc_html__( 'Select Caldera Form', 'essential-addons-elementor' );
                foreach ( $contact_forms as $form ) {   
                    $options[ $form['ID'] ] = $form['name'];
                }
            }
        } else {
            $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
        }

        return $options;
    }
}

/**
 * Get WPForms List
 * @return array
 */
if ( !function_exists('eael_select_wpforms_forms') ) {
    function eael_select_wpforms_forms() {
        $options = array();
        if ( class_exists( 'WPForms' ) ) {

            $args = array(
                'post_type'         => 'wpforms',
                'posts_per_page'    => -1
            );

            $contact_forms = get_posts( $args );

            if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
                $options[0] = esc_html__( 'Select a WPForm', 'essential-addons-elementor' );
                foreach ( $contact_forms as $post ) {   
                    $options[ $post->ID ] = $post->post_title;
                }
            }
        } else {
            $options[0] = esc_html__( 'Create a Form First', 'essential-addons-elementor' );
        }

        return $options;
    }
}

// Get all elementor page templates
if ( !function_exists('eael_get_page_templates') ) {
    function eael_get_page_templates(){
        $page_templates = get_posts( array(
            'post_type'         => 'elementor_library',
            'posts_per_page'    => -1
        ));

        $options = array();

        if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
            foreach ( $page_templates as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }
}

// Get all Authors
if ( !function_exists('eael_get_authors') ) {
    function eael_get_authors() {

        $options = array();

        $users = get_users();

        foreach ( $users as $user ) {
            $options[ $user->ID ] = $user->display_name;
        }

        return $options;
    }
}

// Get all Authors
if ( !function_exists('eael_get_tags') ) {
    function eael_get_tags() {

        $options = array();

        $tags = get_tags();

        foreach ( $tags as $tag ) {
            $options[ $tag->term_id ] = $tag->name;
        }

        return $options;
    }
}

// Get all Posts
if ( !function_exists('eael_get_posts') ) {
    function eael_get_posts() {

        $post_list = get_posts( array(
            'post_type'         => 'post',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'posts_per_page'    => -1,
        ) );

        $posts = array();

        if ( ! empty( $post_list ) && ! is_wp_error( $post_list ) ) {
            foreach ( $post_list as $post ) {
               $posts[ $post->ID ] = $post->post_title;
            }
        }

        return $posts;
    }
}

/**
 * POST Count
 */
function total_post_count( $args ) {
    $posts = new WP_Query( $args );
    return $posts->post_count;
}

/**
 * Load More
 */

function eael_load_more_ajax(){
    $post_args = eael_get_post_settings( $_POST );
    $posts = new WP_Query( $post_args );
    while( $posts->have_posts() ) : $posts->the_post();
    ob_start();
    ?>
        <article class="eael-grid-post eael-post-grid-column">
            <div class="eael-grid-post-holder">
                <div class="eael-grid-post-holder-inner">
                    <?php if ($thumbnail_exists = has_post_thumbnail()): ?>
                    <div class="eael-entry-media">
                        <div class="eael-entry-overlay">
                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                            <a href="<?php echo get_permalink(); ?>"></a>
                        </div>
                        <div class="eael-entry-thumbnail">
                            <?php if($_POST['showImage'] == 1){ ?>
                            <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])?>">
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="eael-entry-wrapper">
                        <header class="eael-entry-header">
                            <?php if($_POST['showTitle']){ ?>
                            <h2 class="eael-entry-title"><a class="eael-grid-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_ID(); echo '-'; the_title(); ?></a></h2>
                            <?php } ?>

                            <?php if($_POST['showMeta'] && $_POST['metaPosition'] == 'meta-entry-header'){ ?>
                            <div class="eael-entry-meta">
                                <span class="eael-posted-by"><?php the_author_posts_link(); ?></span>
                                <span class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>
                            </div>
                            <?php } ?>
                        </header>

                        <div class="eael-entry-content">
                            <?php if($_POST['showExcerpt']){ ?>
                            <div class="eael-grid-post-excerpt">
                                <p><?php echo  eael_get_excerpt_by_id(get_the_ID(), $_POST['excerptLength']);?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if($_POST['showMeta'] && $_POST['metaPosition'] == 'meta-entry-footer'){ ?>
                    <div class="eael-entry-footer">
                        <div class="eael-author-avatar">
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?> </a>
                        </div>
                        <div class="eael-entry-meta">
                            <div class="eael-posted-by"><?php the_author_posts_link(); ?></div>
                            <div class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </article>
    <?php
    endwhile;
    wp_reset_postdata();
    wp_reset_query();
    echo ob_get_clean();
    die();
}
add_action( 'wp_ajax_nopriv_load_more', 'eael_load_more_ajax' );
add_action( 'wp_ajax_load_more', 'eael_load_more_ajax' );

function dump( $data, $var = true, $die = false ){
    if( ! $var ) {
        var_dump( $data );
    } else {
        echo '<pre>', print_r( $data, 1 ), '</pre>';
    }

    if( $die ) {
        die( 'die from dump' );
    }
}
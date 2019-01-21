<?php
class WPDeveloper_Notice {
    /**
     * Admin Notice Key
     *
     * @var array
     */
    const ADMIN_UPDATE_NOTICE_KEY = 'wpdeveloper_notices_seen';
    public $redirect_url = '/wp-admin';
    /**
     * All Data
     *
     * @var array
     */
    private $data = array();
    private $properties = array(
        'links', 'message', 'thumbnail',
    );
    private $methods = array(
        'message', 'thumbnail', 'classes'
    );
    /**
     * cne_day == current_notice_end_day
     *
     * @var integer
     */
    public $cne_time = '2 day';
    public $maybe_later_time = '7 day';
    /**
     * Is the notice is dismissible?
     *
     * @var boolean
     */
    private $is_dismissible = true;
    /**
     * Plugin Name
     *
     * @var string
     */
    private $plugin_name;
    /**
     * First Install Version Of The Plugin
     *
     * @var string
     */
    private $version;
    /**
     * URL Data Args
     * @var array
     */
    private $data_args;
    /**
     * Saved Data in DB
     * @var array
     */
    private $options_data;
    /**
     * Current Timestamp
     * @var integer
     */
    public $timestamp;
    /**
     * Default Options Set
     *
     * @var array
     */
    public $options_args = array(
        // 'first_install' => true,
        // 'next_notice_time' => '',
        // 'notice_seen' => [
        //     'opt_in' => false,
        //     'first_install' => false,
        //     'update' => false,
        //     'review' => false,
        //     'upsale' => false
        // ],
        // 'notice_will_show' => [
        //     'opt_in' => true,
        //     'first_install' => false,
        //     'update' => true,
        //     'review' => true,
        //     'upsale' => true,
        // ]
    );

    private $notice_id;

    /**
     * Revoke this function when the object is created.
     *
     * @param string $plugin_name
     * @param string $version
     */
    public function __construct( $plugin_name = '', $version = '' ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->timestamp = current_time( 'timestamp' );
        $this->notice_id = 'wpdeveloper_notice_' . str_replace( '.', '_', $this->version );

        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if( ! function_exists( 'wp_get_current_user' ) ) {
            require_once ABSPATH . 'wp-includes/pluggable.php';
        }
    }

    private function current_notice_start_time(){
        $current_notice = $this->next_notice();
        $current_notice_start_time = '';

        $current_notice_start = $this->get_options_data('current_notice_start');

        if( ! $current_notice_start ) {
            $current_notice_start = $this->get_args( 'current_notice_start' );
            if( ! $current_notice_start ) {
                $current_notice_start = $this->timestamp;
            }
        }

        return $current_notice_start;
    }

    public function init(){
        add_action( 'activate_' . $this->plugin_name, array( $this, 'first_install_track' ) );
        add_action( 'deactivate_' . $this->plugin_name, array( $this, 'first_install_end' ) );        
        // add_action( 'admin_init', array( $this, 'clicked' ) );
        add_action( 'wp_ajax_wpdeveloper_upsale_notice_dissmiss', array( $this, 'upsale_notice_dissmiss' ) );
        add_action( 'wpdeveloper_before_notice', array( $this, 'before' ) );
        add_action( 'wpdeveloper_after_notice', array( $this, 'after' ) );
        add_action( 'wpdeveloper_before_upsale_notice', array( $this, 'before_upsale' ) );
        add_action( 'wpdeveloper_after_upsale_notice', array( $this, 'after' ) );
        add_action( 'wpdeveloper_notices', array( $this, 'content' ) );

        if( current_user_can( 'install_plugins' ) ) {
            $this->clicked();
            $current_notice = $this->next_notice();
            $deserve_notice = $this->deserve_notice( $current_notice );
            $options_data = $this->get_options_data();

            $notice_time = isset( $options_data[ $this->plugin_name ]['notice_will_show'][ $current_notice ] ) 
                ? $options_data[ $this->plugin_name ]['notice_will_show'][ $current_notice ] : $this->timestamp;
            $current_notice_end  = $this->makeTime( $notice_time, $this->cne_time );

            if( $deserve_notice ) {
                /**
                 * TODO: automatic maybe later setup with time.
                 */        
                
                if( $this->timestamp >= $current_notice_end ) {
                    $this->maybe_later( $current_notice );
                    $notice_time = false;
                }
                
                if( isset( $options_data[ $this->plugin_name ]['current_notice_start'] ) ) {
                    $current_notice_start = $options_data[ $this->plugin_name ]['current_notice_start'];
                } else {
                    $current_notice_start = $this->timestamp;
                }

                if( $notice_time != false ) {
                    if( $notice_time <= $this->timestamp ) {
                        if( $current_notice === 'upsale' ) {
                            add_action( 'admin_notices', array( $this, 'upsale_notice' ) );
                        } else {
                            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
                        }
                    }
                }
            }

        }

    }

    public function makeTime( $current, $time ) {
        return strtotime( date('Y-m-d H:i:s', $current) . " +$time" );
    }

    private function maybe_later( $notice ){
        $options_data = $this->get_options_data();
        $options_data[ $this->plugin_name ]['notice_will_show'][ $notice ] = $this->makeTime( $this->timestamp, $this->maybe_later_time );
        // $options_data[ $this->plugin_name ]['current_notice_start'][ $notice ] = $this->current_notice_start_time();
        $this->update_options_data( $options_data[ $this->plugin_name ] );
    }
    /**
     * When links are clicked, this function will invoked.
     * @return void
     */
    public function clicked(){
        if( ! isset( $_GET['notice_action'] ) || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'wpdeveloper-nonce' ) ) {
            return;
        }
        if( isset( $_GET['notice_action'] ) && $_GET['notice_action'] === $this->plugin_name ) {
            $options_data = $this->get_options_data();
            $clicked_from = $this->next_notice();
            extract($_GET);

            $later_time = '';

            switch( $clicked_from ) {

                case 'opt_in' :
                    $later_time = $this->makeTime( $this->timestamp,  $this->maybe_later_time );
                    break;

                case 'first_install' : 
                    $later_time = $this->makeTime( $this->timestamp,  $this->maybe_later_time );
                    break;

                case 'update' : 
                    $later_time = $this->makeTime( $this->timestamp,  $this->maybe_later_time );
                    break;

                case 'review' : 
                    $later_time = $this->makeTime( $this->timestamp,  $this->maybe_later_time );
                    break;

                case 'upsale' : 
                    $later_time = $this->makeTime( $this->timestamp,  $this->maybe_later_time );
                    break;

            }
        
            if( isset( $later ) && $later == true ) { 
                $options_data[ $this->plugin_name ]['notice_will_show'][ $clicked_from ] = $later_time;
            }
            if( isset( $dismiss ) && $dismiss == true ) { 
                $user_notices = $this->get_user_notices();
                if( ! $user_notices ) {
                    $user_notices = [];
                }
                $user_notices[ $this->notice_id ][ $this->plugin_name ][] = $clicked_from;

                update_user_meta( get_current_user_id(), self::ADMIN_UPDATE_NOTICE_KEY, $user_notices);
            }
            $this->update_options_data( $options_data[ $this->plugin_name ] );
            wp_safe_redirect( $this->redirect_url );
        }
    }
    /**
     * Before Notice
     * @return void
     */
    public function before(){
        $current_notice = $this->next_notice();
        $classes = 'notice notice-info put-dismiss-notice';
        if( isset( $this->data['classes'] ) ) {
            if( isset( $this->data['classes'][ $current_notice ] ) ) {
                $classes = $this->data['classes'][ $current_notice ];
            }
        }

        echo '<div class="'. $classes .' wpdeveloper-'. $current_notice .'-notice">';
    }
    /**
     * After Notice
     * @return void
     */
    public function after(){
        echo '</div>';
    }
    /**
     * Content generation & Hooks Funciton.
     * @return void
     */
    public function content(){
        $options_data = $this->get_options_data();
        $notice = $this->next_notice();

        switch( $notice ) {
            case 'opt_in' :
                do_action('wpdeveloper_optin_notice');
                $this->get_thumbnail( 'opt_in' );
                $this->get_message( 'opt_in' );
                break;
            case 'first_install' : 
                if( $options_data[ $this->plugin_name ]['first_install'] !== 'deactivated' ) {
                    do_action('wpdeveloper_first_install_notice');
                    $this->get_thumbnail( 'first_install' );
                    $this->get_message( 'first_install' );
                }
                break;
            case 'update' : 
                do_action('wpdeveloper_update_notice');
                $this->get_thumbnail( 'update' );
                $this->get_message( 'update' );
                break;
            case 'review' : 
                do_action('wpdeveloper_review_notice');
                $this->get_thumbnail( 'review' );
                $this->get_message( 'review' );
                break;
        }
    }
    /**
     * Before Upsale Notice
     * @return void
     */
    public function before_upsale(){
        echo '<div class="error notice is-dismissible wpdeveloper-upsale-notice">';
    }
    public function upsale_notice(){
        do_action( 'wpdeveloper_before_upsale_notice' );
            do_action('wpdeveloper_upsale_notice');
            $this->get_message( 'upsale' );
        do_action( 'wpdeveloper_after_upsale_notice' );
        $this->upsale_button_script();
    }
    /**
     * This methods is responsible for get notice image.
     *
     * @param string $msg_for
     * @return void
     */
    protected function get_thumbnail( $msg_for ){
        $output = '';
        if( isset( $this->data['thumbnail'] ) && isset( $this->data['thumbnail'][ $msg_for ] ) ) {
            $output = '<div class="wpdeveloper-notice-thumbnail">';
                $output .= '<img src="'. $this->data['thumbnail'][ $msg_for ] .'" alt="">';
            $output .= '</div>';
        }
        echo $output;
    }
    /**
     * This method is responsible for get messages.
     *
     * @param string $msg_for
     * @return void
     */
    protected function get_message( $msg_for ){
        if( isset( $this->data['message'] ) && isset( $this->data['message'][ $msg_for ] ) ) {
            echo '<div class="wpdeveloper-notice-message">';
                echo $this->data['message'][ $msg_for ];
                $this->dismissible_notice( $msg_for );
            echo '</div>';
        }
    }
    /**
     * Detect which notice will show @ next.
     * @return void
     */
    protected function next_notice(){
        $options_data = $this->get_options_data();
        if( ! $options_data ) {
            $args = $this->get_args();
            $return_notice = $args['notice_will_show'];
        } else {
            $return_notice = $options_data[ $this->plugin_name ]['notice_will_show'];
        }

        $deserve_notice_timestamp = INF;
        $deserve_notice = '';
        foreach( $return_notice as $notice => $timestamp ) {
            if( $timestamp <= $deserve_notice_timestamp ) {
                $deserve_notice_timestamp = $timestamp;
                $deserve_notice = $notice;
            }
        }
        return $deserve_notice;
    }

    private function deserve_notice( $notice ) {        
        $notices = $this->get_user_notices();
        if( empty( $notices ) ) {
            return true;
        } else {
            if( isset( $notices[ $this->notice_id ] ) && isset( $notices[ $this->notice_id ][ $this->plugin_name ] ) ) {
                if( in_array( $notice, $notices[ $this->notice_id ][ $this->plugin_name ] ) ) {
                    return false;
                }
            }
        }
    }

    /**
     * This is the main methods for generate the notice.
     * @return void
     */
    public function admin_notices(){
        do_action( 'wpdeveloper_before_notice' );
            do_action( 'wpdeveloper_notices' );
        do_action( 'wpdeveloper_after_notice' );
    }
    /**
     * This method is responsible for all dismissible links generation.
     * @param string $links_for
     * @return void
     */
    public function dismissible_notice( $links_for = '' ){
        if( empty( $links_for ) ) {
            return;
        }
        $links = isset( $this->data['links'][ $links_for ] ) ? $this->data['links'][ $links_for ] : false;
        if( $links ) :
            $output = '<ul class="wpdeveloper-notice-link">';
            foreach( $links as $key => $link_value ) {
                if( ! empty( $link_value['label'] ) ) {
                    $output .= '<li>';
                        if( isset( $link_value['link'] ) ) {
                            $link = $link_value['link'];
                            $target = isset( $link_value['target'] ) ? 'target="'. $link_value['target'] .'"' : '';
                            if( isset( $link_value['data_args'] ) && is_array( $link_value['data_args'] ) ) {
                                $data_args = [];
                                foreach( $link_value['data_args'] as $key => $args_value ) {
                                    $data_args[ $key ] = $args_value;
                                }
                                $data_args[ 'notice_action' ] = $this->plugin_name;
                                $normal_link = add_query_arg( $data_args, $link );
                                $link   = wp_nonce_url( $normal_link, 'wpdeveloper-nonce' );
                            }
                            $class = '';
                            if( isset( $link_value['link_class'] ) ) {
                                $class = 'class="' . implode( ' ', $link_value['link_class'] ) . '"';
                            }
                            $output .= '<a '. $class .' href="'. esc_url( $link ) .'" '. $target .'>';
                        }
                        if( isset( $link_value['icon_class'] ) ) {
                            $output .= '<span class="dashicons dashicons-external"></span>';
                        }
                        if( isset( $link_value['icon_img'] ) ) {
                            $output .= '<img src="'. $link_value['icon_img'] .'" />';
                        }
                        $output .= $link_value['label'];
                        if( isset( $link_value['link'] ) ) {
                            $output .= '</a>';
                        }
                    $output .= '</li>';
                }
            }
            $output .= '</ul>';
            echo $output;
        endif;
    }
    /**
     * First Installation Track
     *
     * @return void
     */
    public function first_install_track(){
        $args = array(
            'time' => $this->timestamp,
            // 'current_notice_start' => $this->timestamp,
        );
        $options_data = $this->get_options_data();
        $args = wp_parse_args( $args, $this->get_args() );

        if( ! isset( $options_data[ $this->plugin_name ] ) ) {
            $this->update_options_data( $args );
        }
    }
    /**
     * First Installation Deactive Track
     *
     * @return void
     */
    public function first_install_end(){
        $args = array(
            'first_install' => 'deactivated'
        );
        $options_data = $this->get_options_data();
        // if( isset( $options_data[ $this->plugin_name ] ) ) {
        //     $args = wp_parse_args( $args, $options_data[ $this->plugin_name ] );
        //     $this->update_options_data( $args );
        // }
        delete_option( 'wpdeveloper_plugins_data' );
    }
    /**
     * Get all options from database!
     * @return void
     */
    protected function get_options_data( $key = ''){
        $options_data = get_option( 'wpdeveloper_plugins_data' );
        if( empty( $key ) ) {
            return $options_data;
        }

        if( isset( $options_data[ $this->plugin_name ][ $key ] ) ) {
            return $options_data[ $this->plugin_name ][ $key ];
        }
        return false;
    }
    /**
     * This will update the options table for plugins.
     *
     * @param mixed $new_data
     * @param array $args
     * @return void
     */
    protected function update_options_data( $args = array() ){
        $options_data = $this->get_options_data();
        $options_data[ $this->plugin_name ] = $args;
        update_option( 'wpdeveloper_plugins_data', $options_data );
    }
    /**
     * Set properties data, for some selected properties.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set( $name, $value ){
        if( in_array( $name, $this->properties ) ) {
            $this->data[ $name ] = $value;
        }
    }
    /**
     * Invoked when some selected methods are called
     *
     * @param string $name
     * @param array $values
     * @return void
     */
    public function __call( $name, $values ){
        if( in_array( $name, $this->methods ) ) {
            $this->data[ $name ][ $values[0] ] = $values[1];
        }
    }

    private function get_args( $key = '' ){
        if( empty( $key ) ) {
            return $this->options_args;
        }

        if( isset( $this->options_args[ $key ] ) ) {
            return $this->options_args[ $key ];
        }

        return false;
    }

	private function get_user_notices() {
		return get_user_meta( get_current_user_id(), self::ADMIN_UPDATE_NOTICE_KEY, true );
    }

    public function upsale_notice_dissmiss(){
        $dismiss = isset( $_POST['dismiss'] ) ? $_POST['dismiss'] : false;

        if( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'wpdeveloper_upsale_notice_dissmiss' ) ) {
            return;
        }

        if( ! isset( $_POST['action'] ) || ( $_POST['action'] !== 'wpdeveloper_upsale_notice_dissmiss' ) ) {
            return;
        }
        
        if( $dismiss ) { 
            $user_notices = $this->get_user_notices();
            if( ! $user_notices ) {
                $user_notices = [];
            }
            $user_notices[ $this->notice_id ][ $this->plugin_name ][] = $clicked_from;

            update_user_meta( get_current_user_id(), self::ADMIN_UPDATE_NOTICE_KEY, $user_notices);
            echo 'success';
        } else {
            echo 'failed';
        }
        die();
    }
    
    public function upsale_button_script(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $('.wpdeveloper-upsale-notice').on('click', 'button.notice-dismiss', function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        type: 'post',
                        data: {
                            action: 'wpdeveloper_upsale_notice_dissmiss',
                            _wpnonce: '<?php echo wp_create_nonce('wpdeveloper_upsale_notice_dissmiss'); ?>',
                            dismiss: true
                        },
                        success: function(response) {
                            console.log('Success fully saved!');
                        },
                        error: function(error) {
                            console.log('Something went wrong!');
                        },
                        complete: function() {
                            console.log('Its Complete.');
                        }
                    });
                });
            } );
        </script>
        
        <?php
    }
}

// Initialization.
$notice = new WPDeveloper_Notice(ESSENTIAL_ADDONS_BASENAME, ESSENTIAL_ADDONS_VERSION);
$scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
$url = $_SERVER['REQUEST_URI'] . $scheme;
$notice->links = [
   'review' => array(
        'later' => array(
            'link' => 'https://wpdeveloper.net/review-essential-addons-elementor',
            'target' => '_blank',
            'label' => __( 'Ok, you deserve it!', 'essential-addons-elementor' ),
            'icon_class' => 'dashicons dashicons-external',
        ),
        'allready' => array(
            'link' => $url,
            'label' => __( 'I already did', 'essential-addons-elementor' ),
            'icon_class' => 'dashicons dashicons-smiley',
            'data_args' => [
                'dismiss' => true,
            ]
        ),
        'maybe_later' => array(
            'link' => $url,
            'label' => __( 'Maybe Later', 'essential-addons-elementor' ),
            'icon_class' => 'dashicons dashicons-calendar-alt',
            'data_args' => [
                'later' => true,
            ]
        ),
        'support' => array(
            'link' => 'https://wpdeveloper.net/support',
            'label' => __( 'I need help', 'essential-addons-elementor' ),
            'icon_class' => 'dashicons dashicons-sos',
        ),
        'never_show_again' => array(
            'link' => $url,
            'label' => __( 'Never show again', 'essential-addons-elementor' ),
            'icon_class' => 'dashicons dashicons-dismiss',
            'data_args' => [
                'dismiss' => true,
            ]
        ),
    )
];

/**
 * This is upsale notice settings
 * classes for wrapper, 
 * Message message for showing.
 */
$notice->classes( 'upsale', 'error notice is-dismissible' );
$notice->message( 'upsale', '<p><strong>WP Scheduled Posts Pro</strong> requires <strong>WP Scheduled Posts</strong> core plugin to be installed. Please get the plugin now! <button id="wpsp-install-core" class="button button-primary">Install Now!</button></p>' );

/**
 * This is review message and thumbnail.
 */
$notice->message( 'review', '<p>'. __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-elementor' ) .'</p>' );
$notice->thumbnail( 'review', plugins_url( 'admin/assets/images/ea-logo.svg', ESSENTIAL_ADDONS_BASENAME ) );

/**
 * Current Notice End Time.
 * Notice will dismiss in 10 minutes if user does nothing.
 */
$notice->cne_time = '1 Day';
$notice->redirect_url = admin_url( 'admin.php?page=eael-settings' );
/**
 * Current Notice Maybe Later Time.
 * Notice will show again in 1 hour
 */
$notice->maybe_later_time = '7 Day';

$notice->options_args = array(
    'notice_seen' => [
        'review' => false,
        'upsale' => false,
    ],
   'notice_will_show' => [
        'review' => $notice->makeTime( $notice->timestamp, '4 Day' ), // after 4 days
        'upsale' => $notice->makeTime( $notice->timestamp, '2 Hour' ), // will be after 2 hours
   ]
);

$notice->init();
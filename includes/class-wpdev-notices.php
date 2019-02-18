<?php
class WPDeveloper_Notice {
    /**
     * Admin Notice Key
     *
     * @var array
     */
    const ADMIN_UPDATE_NOTICE_KEY = 'wpdeveloper_notices_seen';
    public $text_domain = 'wpdeveloper-notice-text-domain';
    /**
     * All Data
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
     * Plugin Name
     *
     * @var string
     */
    private $plugin_name;
    /**
     * Plugin File Name
     * @var string
     */
    private $plugin_file;
    /**
     * First Install Version Of The Plugin
     *
     * @var string
     */
    private $version;
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
        // 'notice_will_show' => [
        //     'opt_in' => true,
        //     'first_install' => false,
        //     'update' => true,
        //     'review' => true,
        //     'upsale' => true,
        // ]
    );
    /**
     * Notice ID for users.
     * @var string
     */
    private $notice_id;
    /**
     * Upsale Notice Arguments
     * @var array
     */
    public $upsale_args;
    /**
     * Revoke this function when the object is created.
     *
     * @param string $plugin_name
     * @param string $version
     */
    public function __construct( $plugin_file = '', $version = '' ) {
        $this->plugin_file = $plugin_file;
        $this->plugin_name = basename( $plugin_file, '.php' );
        $this->version = $version;
        $this->timestamp = intval( current_time( 'timestamp' ) );
        $this->notice_id = 'wpdeveloper_notice_' . str_replace( '.', '_', $this->version );

        if( ! class_exists( 'WPDeveloper_Core_Installer' ) ) {
            require_once dirname( __FILE__ ) . '/class-wpdev-core-install.php';
        }
    }
    /**
     * Initiate The Plugin
     * @return void
     */
    public function init(){
        add_action( 'init', array( $this, 'first_install_track') );
        add_action( 'deactivate_' . $this->plugin_file, array( $this, 'first_install_end' ) );
        add_action( 'init', array( $this, 'hooks' ) );
    }
    /**
     * All Hooks
     * @return void
     */
    public function hooks(){
        add_action( 'wpdeveloper_notice_clicked', array( $this, 'clicked' ) );
        add_action( 'wp_ajax_wpdeveloper_upsale_notice_dissmiss', array( $this, 'upsale_notice_dissmiss' ) );
        add_action( 'wpdeveloper_before_notice', array( $this, 'before' ) );
        add_action( 'wpdeveloper_after_notice', array( $this, 'after' ) );
        add_action( 'wpdeveloper_before_upsale_notice', array( $this, 'before_upsale' ) );
        add_action( 'wpdeveloper_after_upsale_notice', array( $this, 'after' ) );
        add_action( 'wpdeveloper_notices', array( $this, 'content' ) );
        if( current_user_can( 'install_plugins' ) ) {
            if( isset( $_GET['plugin'] ) &&  $_GET['plugin'] == $this->plugin_name ) {
                do_action( 'wpdeveloper_notice_clicked' );
                /**
                 * Redirect User To the Current URL, but without set query arguments.
                 */
                wp_safe_redirect( $this->redirect_to() );
            }
            $return_notice = $this->next_notice();
            $current_notice = current( $return_notice );
            $next_notice = next( $return_notice ); 

            $deserve_notice = $this->deserve_notice( $current_notice );
            $options_data = $this->get_options_data();
            $user_notices = $this->get_user_notices();

            $notice_time = isset( $options_data[ $this->plugin_name ]['notice_will_show'][ $current_notice ] ) 
                ? $options_data[ $this->plugin_name ]['notice_will_show'][ $current_notice ] : $this->timestamp;
            $next_notice_time = $next_notice ? $options_data[ $this->plugin_name ]['notice_will_show'][ $next_notice ] : $this->timestamp;
            $current_notice_end  = $this->makeTime( $notice_time, $this->cne_time );

            if( ! $deserve_notice ) {
                unset( $options_data[ $this->plugin_name ]['notice_will_show'][ $current_notice ] );
                $this->update_options_data( $options_data[ $this->plugin_name ] );
            }

            if( $deserve_notice ) {
                /**
                 * TODO: automatic maybe later setup with time.
                 */
                if( ( $this->timestamp >= $current_notice_end ) || ( $this->timestamp > $next_notice_time ) ) {
                    $this->maybe_later( $current_notice );
                    $notice_time = false;
                }
                if( $notice_time != false ) {
                    if( $notice_time <= $this->timestamp ) {
                        if( $current_notice === 'upsale' ) {
                            $upsale_args = $this->get_upsale_args();
                            if ( ! function_exists( 'get_plugins' ) ) {
                                include ABSPATH . '/wp-admin/includes/plugin.php';
                            }
                            $plugins = get_plugins();
                            $pkey = $upsale_args['slug'] . '/' . $upsale_args['file'];
                            if( isset( $plugins[ $pkey ] ) ) {
                                $this->update( $current_notice );
                                return;
                            }
                            add_action( 'admin_notices', array( $this, 'upsale_notice' ) );
                        } else {
                            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
                        }
                    }
                }
            }
        }
    }
    /**
     * Make time using timestamp and a string like 2 Hour, 2 Day, 30 Minutes, 1 Week, 1 year
     * @param integer $current
     * @param string $time
     * @return integer
     */
    public function makeTime( $current, $time ) {
        return intval( strtotime( date('Y-m-d H:i:s', $current) . " +$time" ) );
    }
    /**
     * Automatice Maybe Later.
     * @param string $notice
     * @return void
     */
    private function maybe_later( $notice ){
        if( empty( $notice ) ) {
            return;
        }
        $options_data = $this->get_options_data();
        $options_data[ $this->plugin_name ]['notice_will_show'][ $notice ] = $this->makeTime( $this->timestamp, $this->maybe_later_time );
        $this->update_options_data( $options_data[ $this->plugin_name ] );
    }
    /**
     * When links are clicked, this function will invoked.
     * @return void
     */
    public function clicked(){        
        if( isset( $_GET['plugin'] ) && $_GET['plugin'] === $this->plugin_name ) {
            $options_data = $this->get_options_data();
            $clicked_from = current( $this->next_notice() );
            extract($_GET);

            $later_time = '';

            switch( $clicked_from ) {

                case 'opt_in' :
                    $dismiss = ( isset( $plugin_action ) ) ? $plugin_action : false ;
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
                $this->update( $clicked_from );
            }
            $this->update_options_data( $options_data[ $this->plugin_name ] );
        }
    }
    /**
     * For Redirecting Current Page without Arguments!
     *
     * @return void
     */
    private function redirect_to(){
        $request_uri  = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
        $query_string = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
        parse_str( $query_string, $current_url );

        $unset_array = array( 'dismiss', 'plugin', '_wpnonce', 'later', 'plugin_action', 'marketing_optin' );

        foreach( $unset_array as $value ) {
            if( isset( $current_url[ $value ] ) ) {
                unset( $current_url[ $value ] );
            }
        }

        $current_url = http_build_query($current_url);
        $redirect_url = $request_uri . '?' . $current_url;
        return $redirect_url;
    }
    /**
     * Before Notice
     * @return void
     */
    public function before(){
        $current_notice = current( $this->next_notice() );
        $classes = 'notice notice-info put-dismiss-notice';
        if( isset( $this->data['classes'] ) ) {
            if( isset( $this->data['classes'][ $current_notice ] ) ) {
                $classes = $this->data['classes'][ $current_notice ];
            }
        }

        if( $this->has_thumbnail( $current_notice ) ) {
            $classes .= 'notice-has-thumbnail';
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
        $notice = current( $this->next_notice() );

        switch( $notice ) {
            case 'opt_in' :
                do_action('wpdeveloper_optin_notice');
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
        $classes = '';
        if( $this->has_thumbnail('upsale') ) {
            $classes = 'notice-has-thumbnail';
        }
        echo '<div class="error notice is-dismissible wpdeveloper-upsale-notice '. $classes .'">';
    }
    /**
     * Upsale Notice
     */
    public function upsale_notice(){
        do_action( 'wpdeveloper_before_upsale_notice' );
            do_action('wpdeveloper_upsale_notice');
            $this->get_thumbnail( 'upsale' );
            $this->get_message( 'upsale' );
        do_action( 'wpdeveloper_after_upsale_notice' );
        $this->upsale_button_script();
    }
    /**
     * Get upsale arguments.
     * @return void
     */
    private function get_upsale_args(){
        return ( empty( $this->upsale_args ) ) ? array() : $this->upsale_args;
    }
    /**
     * This function is responsible for making the button visible to the upsale notice.
     */
    private function upsale_button(){
        $upsale_args = $this->get_upsale_args();
        $plugin_slug = ( isset( $upsale_args['slug'] )) ? $upsale_args['slug'] : '' ;
        if( empty( $plugin_slug ) ) {
            return;
        }
        echo '<button data-slug="'. $plugin_slug .'" id="plugin-install-core" class="button button-primary">'. __( 'Install Now!', $this->text_domain ) .'</button>';
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
     * Has Thumbnail Check
     *
     * @param string $msg_for
     * @return boolean
     */
    protected function has_thumbnail( $msg_for = '' ){
        if( empty( $msg_for ) ) {
            return false;
        }
        if( isset( $this->data['thumbnail'] ) && isset( $this->data['thumbnail'][ $msg_for ] ) ) {
           return true;
        }
        return false;
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
                if( $msg_for === 'upsale' ) {
                    $this->upsale_button();
                }
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

        if( is_array( $return_notice ) ) {
            $return_notice = array_flip( $return_notice );
            ksort( $return_notice );
        }

        return $return_notice;
    }
    /**
     * Which notice is deserve to show in next slot.
     * @param string $notice
     * @return boolean
     */
    private function deserve_notice( $notice ) {        
        $notices = $this->get_user_notices();
        if( $notice === false ) {
            return false;
        }
        if( empty( $notices ) ) {
            return true;
        } else {
            if( isset( $notices[ $this->notice_id ] ) && isset( $notices[ $this->notice_id ][ $this->plugin_name ] ) ) {
                if( in_array( $notice, $notices[ $this->notice_id ][ $this->plugin_name ] ) ) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
    }
    /**
     * This is the main methods for generate the notice.
     * @return void
     */
    public function admin_notices(){
        $current_notice = current( $this->next_notice() );
        if( $current_notice == 'opt_in' ) {
            do_action( 'wpdeveloper_notices' );
            return;
        }
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
                                $data_args[ 'plugin' ] = $this->plugin_name;
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
                            $output .= '<span class="'. $link_value['icon_class'] .'"></span>';
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
     * @return void
     */
    public function first_install_track( $args = array() ){
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if( empty( $args ) ) {
            $args = array(
                'time' => $this->timestamp,
                'version' => $this->version,
            );
        }
        $options_data = $this->get_options_data();
        $args = wp_parse_args( $args, $this->get_args() );
        if( ! isset( $options_data[ $this->plugin_name ] ) 
            || ( isset( $options_data[ $this->plugin_name ]['version'] ) && version_compare( $options_data[ $this->plugin_name ]['version'], $this->version, '!=' ) ) ) {
            $this->update_options_data( $args );
        }
    }
    /**
     * First Installation Deactive Track
     *
     * @return void
     */
    public function first_install_end(){
        // $args = array(
        //     'first_install' => 'deactivated'
        // );
        // $options_data = $this->get_options_data();
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
    /**
     * Get all option arguments.
     * @param string $key
     * @return array
     */
    private function get_args( $key = '' ){
        if( empty( $key ) ) {
            return $this->options_args;
        }

        if( isset( $this->options_args[ $key ] ) ) {
            return $this->options_args[ $key ];
        }

        return false;
    }
    /**
     * Resetting data on update.
     * @return void
     */
    private function set_args_on_update(){
        $args = $this->get_args();
        $options_data = $this->get_options_data();
        $set_data = $options_data[ $this->plugin_name ];
        $args = wp_parse_args( $set_data, $args );
        $this->update_options_data( $args );
    }
    /**
     * When upgrade is complete. it will fired.
     * @param  WP_Upgrader $upgrader_object
     * @param array $options
     * @return void
     */
    public function upgrade_completed( $upgrader_object, $options ) {
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( isset( $options['action'] ) && $options['action'] == 'update' && $options['type'] == 'plugin' ) {
            if( ! isset( $options['plugin'] ) && isset( $options['plugins'] ) ) {
                foreach( $options['plugins'] as $plugin ) {
                    if( $plugin == $this->plugin_name ) {
                        $this->set_args_on_update();
                    }
                }
            }

            if( isset( $options['plugin'] ) && $options['plugin'] == $this->plugin_name ) {
                $this->set_args_on_update();
            }
        }
    }
    /**
     * This function is responsible for get_user_notices
     * @return void
     */
	private function get_user_notices() {
        $notices = get_user_meta( get_current_user_id(), self::ADMIN_UPDATE_NOTICE_KEY, true );
		return ! $notices ? array() : $notices;
    }
    /**
     * This function is responsible for update meta information.
     *
     * @param string $notice
     * @return void
     */
    private function update( $notice ){
        if( empty( $notice ) ) {
            return;
        }
        $options_data = $this->get_options_data();
        $user_notices = $this->get_user_notices();
        $user_notices[ $this->notice_id ][ $this->plugin_name ][] = $notice;
        // Remove the upsale from notice_will_show field in options DB.
        unset( $options_data[ $this->plugin_name ]['notice_will_show'][ $notice ] );
        $this->update_options_data( $options_data[ $this->plugin_name ] );
        // Set users meta, not to show again current_version notice.
        update_user_meta( get_current_user_id(), self::ADMIN_UPDATE_NOTICE_KEY, $user_notices);
    }
    /**
     * This function is responsible for do action when 
     * the dismiss button clicked in upsale notice.
     */
    public function upsale_notice_dissmiss(){
        
        if( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'wpdeveloper_upsale_notice_dissmiss' ) ) {
            return;
        }
        
        if( ! isset( $_POST['action'] ) || ( $_POST['action'] !== 'wpdeveloper_upsale_notice_dissmiss' ) ) {
            return;
        }
        
        $dismiss = isset( $_POST['dismiss'] ) ? $_POST['dismiss'] : false;
        if( $dismiss ) { 
            $this->update( 'upsale' );
            echo 'success';
        } else {
            echo 'failed';
        }
        die();
    }
    /**
     * Upsale Button Script.
     * When install button is clicked, it will do its own things.
     * also for dismiss button JS.
     * @return void
     */
    public function upsale_button_script(){
        $upsale_args = $this->get_upsale_args();

        $plugin_slug = ( isset( $upsale_args['slug'] ) ) ? $upsale_args['slug'] : '';
        $plugin_file = ( isset( $upsale_args['file'] ) ) ? $upsale_args['file'] : '';
        $page_slug = ( isset( $upsale_args['page_slug'] ) ) ? $upsale_args['page_slug'] : '';

        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                <?php if( ! empty( $plugin_slug ) && ! empty( $plugin_file ) ) : ?>
                $('#plugin-install-core').on('click', function (e) {
                    var self = $(this);
                    e.preventDefault();
                    self.addClass('install-now updating-message');
                    self.text('<?php echo esc_js( 'Installing...' ); ?>');

                    $.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        type: 'POST',
                        data: {
                            action: 'wpdeveloper_upsale_core_install',
                            _wpnonce: '<?php echo wp_create_nonce('wpdeveloper_upsale_core_install'); ?>',
                            slug : '<?php echo $plugin_slug; ?>',
                            file : '<?php echo $plugin_file; ?>'
                        },
                        success: function(response) {
                            self.text('<?php echo esc_js( 'Installed' ); ?>');
                            <?php if( ! empty( $page_slug ) ) : ?>
                                window.location.href = '<?php echo admin_url( "admin.php?page={$page_slug}" ); ?>';
                            <?php endif; ?>
                        },
                        error: function(error) {
                            self.removeClass('install-now updating-message');
                            alert( error );
                        },
                        complete: function() {
                            self.attr('disabled', 'disabled');
                            self.removeClass('install-now updating-message');
                        }
                    });
                });

                <?php endif; ?>

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
$notice->classes( 'upsale', 'notice is-dismissible' );
$notice->message( 'upsale', '<p>'. __( 'Get the missing Drag & Drop Post Calendar feature for WordPress for Free!', 'essential-addons-elementor' ) .'</p>' );
$notice->thumbnail( 'upsale', plugins_url( 'admin/assets/images/wpsp-logo.svg', ESSENTIAL_ADDONS_BASENAME ) );

/**
 * This is review message and thumbnail.
 */
$notice->message( 'review', '<p>'. __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-elementor' ) .'</p>' );
$notice->thumbnail( 'review', plugins_url( 'admin/assets/images/ea-logo.svg', ESSENTIAL_ADDONS_BASENAME ) );

/**
 * Current Notice End Time.
 * Notice will dismiss in 3 days if user does nothing.
 */
$notice->cne_time = '3 Day';
/**
 * Current Notice Maybe Later Time.
 * Notice will show again in 7 days
 */
$notice->maybe_later_time = '7 Day';

$notice->upsale_args = array(
    'slug' => 'wp-scheduled-posts',
    'page_slug' => 'wpsp-schedule-calendar',
    'file' => 'wp-scheduled-posts.php'
);

$notice->text_domain = 'essential-addons-elementor';

$notice->options_args = array(
   'notice_will_show' => [
        'opt_in' => $notice->timestamp,
        'review' => $notice->makeTime( $notice->timestamp, '4 Day' ), // after 4 days
        'upsale' => $notice->makeTime( $notice->timestamp, '2 Hour' ), // will be after 2 hours
   ]
);

$notice->init();
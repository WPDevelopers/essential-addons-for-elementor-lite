<?php
/**
 * Plugin_Usage_Tracker
 * This class is responsible for data sending to insights.
 * @version 3.0.0
 */
namespace Essential_Addons_Elementor\Classes;
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main SDK for Plugin_Usage_Tracker.
 */
	class Plugin_Usage_Tracker {
		/**
		 * WP Insights Version
		 */
		const WPINS_VERSION = '3.0.3';
		/**
		 * API URL
		 */
		const API_URL = 'https://send.wpinsight.com/process-plugin-data';
		/**
		 * Installed Plugin File
		 *
		 * @var string
		 */
		private $plugin_file = null;
		/**
		 * Installed Plugin Name
		 *
		 * @var string
		 */
		private $plugin_name = null;
		/**
		 * How often the event should subsequently
		 * @var string
		 */
		public $recurrence = 'daily';
		private $event_hook = null;
		/**
		 * Instace of Plugin_Usage_Tracker
		 * @var Plugin_Usage_Tracker
		 */
		private static $_instance = null;

		private $disabled_wp_cron;
		private $enable_self_cron;
		private $require_optin;
		private $include_goodbye_form;
		private $marketing;
		private $options;
		private $item_id;
		private $notice_options;

		/**
		 * Get Instance of Plugin_Usage_Tracker
		 * @return Plugin_Usage_Tracker
		 */
		public static function get_instance( $plugin_file, $args = [] ){
			if( is_null( static::$_instance ) ) {
				static::$_instance = new static( $plugin_file, $args );
			}
			return static::$_instance;
		}
		/**
		 * Automatically Invoked when initialized.
		 *
		 * @param array $args
		 */
		public function __construct( $plugin_file, $args = [] ){
			$this->plugin_file          = $plugin_file;
			$this->plugin_name          = basename( $this->plugin_file, '.php' );
			$this->disabled_wp_cron     = defined('DISABLE_WP_CRON') && DISABLE_WP_CRON == true;
			$this->enable_self_cron     = $this->disabled_wp_cron == true ? true : false;

			$this->event_hook 			= 'put_do_weekly_action';

			$this->require_optin        = isset( $args['opt_in'] ) ? $args['opt_in'] : true;
			$this->include_goodbye_form = isset( $args['goodbye_form'] ) ? $args['goodbye_form'] : true;
			$this->marketing            = isset( $args['email_marketing'] ) ? $args['email_marketing'] : true;
			$this->options              = isset( $args['options'] ) ? $args['options'] : [];
			$this->item_id              = isset( $args['item_id'] ) ? $args['item_id'] : false;
			/**
			 * Activation Hook
			 */
			register_activation_hook( $this->plugin_file, array( $this, 'activate_this_plugin' ) );
			/**
			 * Deactivation Hook
			 */
			register_deactivation_hook( $this->plugin_file, array( $this, 'deactivate_this_plugin' ) );
		}
		/**
		 * When user agreed to opt-in tracking schedule is enabled.
		 * @since 3.0.0
		 */
		public function schedule_tracking() {
			if( $this->disabled_wp_cron ) {
				return;
			}
			if ( ! wp_next_scheduled( $this->event_hook ) ) {
				wp_schedule_event( time(), $this->recurrence, $this->event_hook );
			}
		}
		/**
		 * Add the schedule event if the plugin is tracked.
		 *
		 * @return void
		 */
		public function activate_this_plugin(){
			$allow_tracking = $this->is_tracking_allowed();
			if( ! $allow_tracking ) {
				return;
			}
			$this->schedule_tracking();
		}
		/**
		 * Remove the schedule event when plugin is deactivated and send the deactivated reason to inishghts if user submitted.
		 * @since 3.0.0
		 */
		public function deactivate_this_plugin() {
			/**
			 * Check tracking is allowed or not.
			 */
			$allow_tracking = $this->is_tracking_allowed();
			if( ! $allow_tracking ) {
				return;
			}
			$body = $this->get_data();
			$body['status'] = 'Deactivated';
			$body['deactivated_date'] = time();

			// Check deactivation reason and add for insights data.
			if( false !== get_option( 'wpins_deactivation_reason_' . $this->plugin_name ) ) {
				$body['deactivation_reason'] = get_option( 'wpins_deactivation_reason_' . $this->plugin_name );
			}
			if( false !== get_option( 'wpins_deactivation_details_' . $this->plugin_name ) ) {
				$body['deactivation_details'] = get_option( 'wpins_deactivation_details_' . $this->plugin_name );
			}

			$this->send_data( $body );
			delete_option( 'wpins_deactivation_reason_' . $this->plugin_name );
			delete_option( 'wpins_deactivation_details_' . $this->plugin_name );
			/**
			 * Clear the event schedule.
			 */
			if( ! $this->disabled_wp_cron ) {
				wp_clear_scheduled_hook( $this->event_hook );
			}
		}
		/**
		 * Initial Method to Hook Everything.
		 * @return void
		 */
		public function init(){
			// $this->clicked();
            add_action('wpdeveloper_notice_clicked_for_' . $this->plugin_name, array($this, 'clicked'));
			add_action( $this->event_hook, array( $this, 'do_tracking' ) );
			// For Test
			// add_action( 'admin_init', array( $this, 'force_tracking' ) );
			// add_action( 'admin_notices', array( $this, 'notice' ) );
            add_action('wpdeveloper_optin_notice_for_' . $this->plugin_name, array($this, 'notice'));
			/**
			 * Deactivation Reason Form and Submit Data to Insights.
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'deactivate_action_links' ) );
			add_action( 'admin_footer-plugins.php', array( $this, 'deactivate_reasons_form' ) );
			add_action( 'wp_ajax_deactivation_form_' . esc_attr( $this->plugin_name ), array( $this, 'deactivate_reasons_form_submit' ) );
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
		 * This method forcing the do_tracking method to execute instant.
		 * @return void
		 */
		public function force_tracking(){
			$this->do_tracking( true );
		}
		/**
		 * This method is responsible for all the magic from the front of the plugin.
		 * @since 3.0.0
		 * @param $force	Force tracking if it's not the correct time to track/
		 */
		public function do_tracking( $force = false ) {
			/**
			 * Check URL is set or not.
			 */
			if ( empty( self::API_URL ) ) {
				return;
			}
			/**
			 * Check is tracking allowed or not.
			 */
			if( ! $this->is_tracking_allowed() ) {
				return;
			}
			/**
			 * Check is this the correct time to track or not.
			 * or Force to track.
			 */
			if( ! $this->is_time_to_track() && ! $force ) {
				return;
			}
			/**
			 * Get All Data.
			 */
			$body = $this->get_data();
			/**
			 * Send all data.
			 */
			return $this->send_data( $body );
		}
		/**
		 * Is tracking allowed?
		 * @since 1.0.0
		 */
		private function is_tracking_allowed() {
			// First, check if the user has changed their mind and opted out of tracking
			if( $this->has_user_opted_out() ) {
				$this->set_is_tracking_allowed( false, $this->plugin_name );
				return false;
			}
			// The wpins_allow_tracking option is an array of plugins that are being tracked
			$allow_tracking = get_option( 'wpins_allow_tracking' );
			// If this plugin is in the array, then tracking is allowed
			if( isset( $allow_tracking[$this->plugin_name] ) ) {
				return true;
			}
			return false;
		}
		/**
		 * Set a flag in DB If tracking is allowed.
		 *
		 * @since 3.0.0
		 * @param $is_allowed	Boolean	 true if is allowed.
		 */
		public function set_is_tracking_allowed( $is_allowed, $plugin = null ) {
			if( empty( $plugin ) ) {
				$plugin = $this->plugin_name;
			}
			/**
			 * Get All Tracked Plugin List using this Tracker.
			 */
			$allow_tracking = get_option( 'wpins_allow_tracking' );
			/**
			 * Check user is opted out for tracking or not.
			 */
			if( $this->has_user_opted_out() ) {
				if( isset( $allow_tracking[$plugin] ) ) {
					unset( $allow_tracking[$plugin] );
				}
			} else if( $is_allowed || ! $this->require_optin ) {
				/**
				 * If user has agreed to allow tracking
				 */
				if( empty( $allow_tracking ) || ! is_array( $allow_tracking ) ) {
					$allow_tracking = array( $plugin => $plugin );
				} else {
					$allow_tracking[$plugin] = $plugin;
				}
			} else {
				if( isset( $allow_tracking[$plugin] ) ) {
					unset( $allow_tracking[$plugin] );
				}
			}
			update_option( 'wpins_allow_tracking', $allow_tracking );
		}

		/**
		 * Check the user has opted out or not.
		 *
		 * @since 3.0.0
		 * @return Boolean
		 */
		protected function has_user_opted_out() {
			if( ! empty( $this->options ) ) {
				foreach( $this->options as $option_name ) {
					$options = get_option( $option_name );
					if( ! empty( $options['wpins_opt_out'] ) ) {
						return true;
					}
				}
			}
			return false;
		}
		/**
		 * Check if it's time to track
		 *
		 * @since 3.0.0
		 */
		public function is_time_to_track() {
			$track_times = get_option( 'wpins_last_track_time', array() );
			return ! isset( $track_times[$this->plugin_name] ) ? true :
					( ( isset( $track_times[$this->plugin_name] ) && $track_times[$this->plugin_name] ) < strtotime( '-1 day' ) ? true : false );
		}
		/**
		 * Set tracking time.
		 *
		 * @since 3.0.0
		 */
		public function set_track_time() {
			$track_times = get_option( 'wpins_last_track_time', array() );
			$track_times[ $this->plugin_name ] = time();
			update_option( 'wpins_last_track_time', $track_times );
		}
		/**
		 * This method is responsible for collecting all data.
		 *
		 * @since 3.0.0
		 */
		public function get_data() {
			$body = array(
				'plugin_slug'		=> sanitize_text_field( $this->plugin_name ),
				'url'				=> get_bloginfo( 'url' ),
				'site_name' 		=> get_bloginfo( 'name' ),
				'site_version'		=> get_bloginfo( 'version' ),
				'site_language'		=> get_bloginfo( 'language' ),
				'charset'			=> get_bloginfo( 'charset' ),
				'wpins_version'		=> self::WPINS_VERSION,
				'php_version'		=> phpversion(),
				'multisite'			=> is_multisite(),
				'file_location'		=> __FILE__
			);

			// Collect the email if the correct option has been set
			if( $this->marketing ) {
				if( ! function_exists( 'wp_get_current_user' ) ) {
					include ABSPATH . 'wp-includes/pluggable.php';
				}
				$current_user = wp_get_current_user();
				$email = $current_user->user_email;
				if( is_email( $email ) ) {
					$body['email'] = $email;
				} else {
					$email = get_option( 'admin_email' );
					if( is_email($email) ) {
						$body['email'] = $email;
					}
				}
			}
			$body['marketing_method'] = $this->marketing;
			$body['server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';

			/**
			 * Collect all active and inactive plugins
			 */
			if( ! function_exists( 'get_plugins' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}
			$plugins = array_keys( get_plugins() );
			$active_plugins = is_network_admin() ? array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) : get_option( 'active_plugins', array() );
			foreach ( $plugins as $key => $plugin ) {
				if ( in_array( $plugin, $active_plugins ) ) {
					unset( $plugins[$key] );
				}
			}
			$body['active_plugins'] = $active_plugins;
			$body['inactive_plugins'] = $plugins;

			/**
			 * Text Direction.
			 */
			$body['text_direction']	= ( function_exists( 'is_rtl' ) ? ( is_rtl() ? 'RTL' : 'LTR' ) : 'NOT SET' );
			/**
			 * Get Our Plugin Data.
			 * @since 3.0.0
			 */
			$plugin = $this->plugin_data();
			if( empty( $plugin ) ) {
				$body['message'] .= __( 'We can\'t detect any plugin information. This is most probably because you have not included the code in the plugin main file.', 'essential-addons-for-elementor-lite' );
				$body['status'] = 'NOT FOUND';
			} else {
				if( isset( $plugin['Name'] ) ) {
					$body['plugin'] = sanitize_text_field( $plugin['Name'] );
				}
				if( isset( $plugin['Version'] ) ) {
					$body['version'] = sanitize_text_field( $plugin['Version'] );
				}
				$body['status'] = 'Active';
			}

			/**
			 * Get active theme name and version
			 * @since 3.0.0
			 */
			$theme = wp_get_theme();
			if( $theme->Name ) {
				$body['theme'] = sanitize_text_field( $theme->Name );
			}
			if( $theme->Version ) {
				$body['theme_version'] = sanitize_text_field( $theme->Version );
			}

			if ( ! empty( $this->get_used_elements_count() ) ) {
				$body['optional_data'] = $this->get_used_elements_count();
			}

			return $body;
		}

		/**
		 * Collect plugin data,
		 * Retrieve current plugin information
		 *
		 * @since 3.0.0
		 */
		public function plugin_data() {
			if( ! function_exists( 'get_plugin_data' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}
			$plugin = get_plugin_data( $this->plugin_file );
			return $plugin;
		}
		/**
		 * Send the data to insights.
		 * @since 3.0.0
		 */
		public function send_data( $body ) {
			/**
			 * Get SITE ID
			 */
			$site_id_key       = "wpins_{$this->plugin_name}_site_id";
			$site_id           = get_option( $site_id_key, false );
			$failed_data       = [];
			$site_url          = get_bloginfo( 'url' );
			$original_site_url = get_option( "wpins_{$this->plugin_name}_original_url", false );

			if( ( $original_site_url === false || $original_site_url != $site_url ) && version_compare( $body['wpins_version'], '3.0.1', '>=' ) ) {
				$site_id = false;
			}
			/**
			 * Send Initial Data to API
			 */
			if( $site_id == false && $this->item_id !== false ) {
				if( isset( $_SERVER['REMOTE_ADDR'] ) && ! empty( $_SERVER['REMOTE_ADDR'] && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) ) {
					$country_request = wp_remote_get( 'http://ip-api.com/json/'. $_SERVER['REMOTE_ADDR'] .'?fields=country');
					if( ! is_wp_error( $country_request ) && $country_request['response']['code'] == 200 ) {
						$ip_data = json_decode( $country_request["body"] );
						$body['country'] = isset( $ip_data->country ) ? $ip_data->country : 'NOT SET';
					}
				}

				$body['plugin_slug'] = $this->plugin_name;
				$body['url']         = $site_url;
				$body['item_id']     = $this->item_id;

				$request = $this->remote_post( $body );
				if( ! is_wp_error( $request ) && $request['response']['code'] == 200 ) {
					$retrieved_body = json_decode( wp_remote_retrieve_body( $request ), true );
					if( is_array( $retrieved_body ) && isset( $retrieved_body['siteId'] ) ) {
						update_option( $site_id_key, $retrieved_body['siteId'] );
						update_option( "wpins_{$this->plugin_name}_original_url", $site_url );
						update_option( "wpins_{$this->plugin_name}_{$retrieved_body['siteId']}", $body );
					}
				} else {
					$failed_data = $body;
				}
			}

			$site_id_data_key        = "wpins_{$this->plugin_name}_{$site_id}";
			$site_id_data_failed_key = "wpins_{$this->plugin_name}_{$site_id}_send_failed";

			if( $site_id != false ) {
				$old_sent_data = get_option( $site_id_data_key, [] );
				$diff_data = $this->diff( $body, $old_sent_data );
				$failed_data = get_option( $site_id_data_failed_key, [] );
				if( ! empty( $failed_data ) && $diff_data != $failed_data ) {
					$failed_data = array_merge( $failed_data, $diff_data );
				}
			}

			if( ! empty( $failed_data ) && $site_id != false ) {
				$failed_data['plugin_slug']  = $this->plugin_name;
				$failed_data['url']          = $site_url;
				$failed_data['site_id']      = $site_id;
				if( $original_site_url != false ) {
					$failed_data['original_url'] = $original_site_url;
				}

				$request = $this->remote_post( $failed_data );
				if( ! is_wp_error( $request ) ) {
					delete_option( $site_id_data_failed_key );
					$replaced_data = array_merge( $old_sent_data, $failed_data );
					update_option( $site_id_data_key, $replaced_data );
				}
			}

			if( ! empty( $diff_data ) && $site_id != false && empty( $failed_data ) ) {
				$diff_data['plugin_slug']  = $this->plugin_name;
				$diff_data['url']          = $site_url;
				$diff_data['site_id']      = $site_id;
				if( $original_site_url != false ) {
					$diff_data['original_url'] = $original_site_url;
				}

				$request = $this->remote_post( $diff_data );
				if( is_wp_error( $request ) ) {
					update_option( $site_id_data_failed_key, $diff_data );
				} else {
					$replaced_data = array_merge( $old_sent_data, $diff_data );
					update_option( $site_id_data_key, $replaced_data );
				}
			}

			$this->set_track_time();

			if( isset( $request ) && is_wp_error( $request ) ) {
				return $request;
			}

			if( isset( $request ) ) {
				return true;
			}
			return false;
		}
		/**
		 * WP_REMOTE_POST method responsible for send data to the API_URL
		 *
		 * @param array $data
		 * @param array $args
		 * @return void
		 */
		protected function remote_post( $data = array(), $args = array() ){
			if( empty( $data ) ) {
				return;
			}

			$args = wp_parse_args( $args, array(
				'method'      => 'POST',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking'    => true,
				'body'        => $data,
				'user-agent'  => 'PUT/1.0.0; ' . get_bloginfo( 'url' )
			));
			$request = wp_remote_post( esc_url( self::API_URL ), $args );
			if( is_wp_error( $request ) || ( isset( $request['response'], $request['response']['code'] ) && $request['response']['code'] != 200 ) ) {
				return new \WP_Error( 500, 'Something went wrong.' );
			}
			return $request;
		}
		/**
		 * Difference between old and new data
		 *
		 * @param array $new_data
		 * @param array $old_data
		 * @return void
		 */
		protected function diff( $new_data, $old_data ){
			$data = [];
			if( ! empty( $new_data ) ) {
				foreach( $new_data as $key => $value ) {
					if( isset( $old_data[ $key ] ) ) {
						if( $old_data[ $key ] == $value ) {
							continue;
						}
					}
					$data[ $key ] = $value;
				}
			}
			return $data;
		}
		/**
		 * Display the admin notice to users to allow them to opt in
		 *
		 * @since 3.0.0
		 */
		public function notice() {
			/**
			 * Return if notice is not set.
			 */
			if( ! isset( $this->notice_options['notice'] ) ) {
				return;
			}
			/**
			 * Check is allowed or blocked for notice.
			 */
			$block_notice = get_option( 'wpins_block_notice' );
			if( isset( $block_notice[$this->plugin_name] ) ) {
				return;
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$url_yes = add_query_arg( [
				'plugin'          => $this->plugin_name,
				'plugin_action'   => 'yes',
			] );
			$url_no = add_query_arg( array(
				'plugin' 		=> $this->plugin_name,
				'plugin_action'	=> 'no'
			) );

			$url_yes = wp_nonce_url( $url_yes, '_wpnonce_optin_' . $this->plugin_name );
			$url_no  = wp_nonce_url( $url_no, '_wpnonce_optin_' . $this->plugin_name );

			// Decide on notice text
			$notice_text = $this->notice_options['notice'] . ' <a href="#" class="wpinsights-'. esc_attr( $this->plugin_name ) .'-collect">'. $this->notice_options['consent_button_text'] .'</a>';
			$extra_notice_text = $this->notice_options['extra_notice'];

			$output = '';
			$output .= '<div class="notice notice-info updated put-dismiss-notice">';
				$output .= '<p>'. $notice_text .'</p>';
				$output .= '<div class="wpinsights-data" style="display: none;">';
					$output .= '<p>'. $extra_notice_text .'</p>';
				$output .= '</div>';
				$output .= '<p>';
					$output .= '<a href="'. esc_url( $url_yes ) .'" class="button-primary">'. $this->notice_options['yes'] .'</a>&nbsp;';
					$output .= '<a href="'. esc_url( $url_no ) .'" class="button-secondary">'. $this->notice_options['no'] .'</a>';
				$output .= '</p>';
				$output .= "<script type='text/javascript'>jQuery('.wpinsights-". esc_attr( $this->plugin_name ) ."-collect').on('click', function(e) {e.preventDefault();jQuery('.wpinsights-data').slideToggle('fast');});</script>";
			$output .= '</div>';

			printf( '%1$s', $output );
		}
		/**
		 * Set all notice options to customized notice.
		 *
		 * @since 3.0.0
		 * @param array $options
		 * @return void
		 */
		public function set_notice_options( $options = [] ){
			$default_options = [
				'consent_button_text' => __( 'What we collect.', 'essential-addons-for-elementor-lite' ),
				'yes'                 => __( 'Sure, I\'d like to help', 'essential-addons-for-elementor-lite' ),
				'no'                  => __( 'No Thanks.', 'essential-addons-for-elementor-lite' ),
			];
			$options = wp_parse_args( $options, $default_options );
			$this->notice_options = $options;
		}
		/**
		 * Responsible for track the click from Notice.
		 * @return void
		 */
		public function clicked(){
			if ( isset( $_GET['_wpnonce'] ) && isset( $_GET['plugin'] ) && trim( $_GET['plugin'] ) === $this->plugin_name && isset( $_GET['plugin_action'] ) ) {
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], '_wpnonce_optin_' . $this->plugin_name ) ) {
					return;
				}

				if( isset( $_GET['tab'] ) && $_GET['tab'] === 'plugin-information' ) {
                    return;
                }
				$plugin = sanitize_text_field( $_GET['plugin'] );
				$action = sanitize_text_field( $_GET['plugin_action'] );
				if( $action == 'yes' ) {
					$this->schedule_tracking();
					$this->set_is_tracking_allowed( true, $plugin );
					if( $this->do_tracking( true ) ) {
						$this->update_block_notice( $plugin );
					}
					/**
					 * Redirect User To the Current URL, but without set query arguments.
					 */
					wp_safe_redirect( $this->redirect_to() );
				} else {
					$this->set_is_tracking_allowed( false, $plugin );
					$this->update_block_notice( $plugin );
				}
			}
		}
		/**
		 * Set if we should block the opt-in notice for this plugin
		 *
		 * @since 3.0.0
		 */
		public function update_block_notice( $plugin = null ) {
			if( empty( $plugin ) ) {
				$plugin = $this->plugin_name;
			}
			$block_notice = get_option( 'wpins_block_notice' );
			if( empty( $block_notice ) || ! is_array( $block_notice ) ) {
				$block_notice = array( $plugin => $plugin );
			} else {
				$block_notice[$plugin] = $plugin;
			}
			update_option( 'wpins_block_notice', $block_notice );
		}
		/**
		 * AJAX callback when the deactivated form is submitted.
		 * @since 3.0.0
		 */
		public function deactivate_reasons_form_submit() {
			check_ajax_referer( 'wpins_deactivation_nonce', 'security' );
			if( isset( $_POST['values'] ) ) {
				$values = sanitize_text_field( $_POST['values'] );
				update_option( 'wpins_deactivation_reason_' . $this->plugin_name, $values );
			}
			if( isset( $_POST['details'] ) ) {
				$details = sanitize_text_field( $_POST['details'] );
				update_option( 'wpins_deactivation_details_' . $this->plugin_name, $details );
			}
			echo 'success';
			wp_die();
		}
		/**
		 * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
		 * @since 3.0.0
		 */
		public function deactivate_action_links( $links ) {
			/**
			 * Check is tracking allowed or not.
			 */
			if( ! $this->is_tracking_allowed() ) {
				return $links;
			}
			if( isset( $links['deactivate'] ) && $this->include_goodbye_form ) {
				$deactivation_link = $links['deactivate'];
				/**
				 * Change the default deactivate button link.
				 */
				$deactivation_link = str_replace( '<a ', '<div class="wpinsights-goodbye-form-wrapper-'. esc_attr( $this->plugin_name ) .'"><div class="wpinsights-goodbye-form-bg"></div><span class="wpinsights-goodbye-form" id="wpinsights-goodbye-form"></span></div><a onclick="javascript:event.preventDefault();" id="wpinsights-goodbye-link-' . esc_attr( $this->plugin_name ) . '" ', $deactivation_link );
				$links['deactivate'] = $deactivation_link;
			}
			return $links;
		}
		/**
		 * ALL Deactivate Reasons.
		 * @since 3.0.0
		 */
		public function deactivation_reasons() {
			$form = array();
			$form['heading'] = __( 'We Value Your Feedback', 'essential-addons-for-elementor-lite' );
			$form['body'] = __( "Could you please tell us why you're deactivating our plugin? Your insights will help us to improve.", 'essential-addons-for-elementor-lite' );

			$form['options'] = array(
				__( 'I no longer need the plugin', 'essential-addons-for-elementor-lite' ),
				[
					'label' => __( 'I found a better plugin', 'essential-addons-for-elementor-lite' ),
					'extra_field' => __( 'Please share which plugin', 'essential-addons-for-elementor-lite' )
				],
				__( "I couldn't get the plugin to work", 'essential-addons-for-elementor-lite' ),
				__( 'It\'s a temporary deactivation', 'essential-addons-for-elementor-lite' ),
				[
					'label' => __( 'Other', 'essential-addons-for-elementor-lite' ),
					'extra_field' => __( 'Please share the reason', 'essential-addons-for-elementor-lite' ),
					'type' => 'textarea'
				]
			);
			return apply_filters( 'wpins_form_text_' . $this->plugin_name, $form );
		}
		/**
		 * Deactivate Reasons Form.
		 * This form will appears when user wants to deactivate the plugin to send you deactivated reasons.
		 *
		 * @since 3.0.0
		 */
		public function deactivate_reasons_form() {
			$form = $this->deactivation_reasons();
			$class_plugin_name = esc_attr( $this->plugin_name );
			$html = '<div class="ea__modal-close-btn"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/></svg></div><div class="wpinsights-goodbye-form-head"><span><svg width="65" height="64" viewBox="0 0 65 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" width="64" height="64" rx="16" fill="#F6EEFF"/><path d="M41.8346 22.668L45.8346 18.668M19.168 45.3346L23.168 41.3346M26.5013 34.0013L29.8346 30.668M30.5013 38.0013L33.8346 34.668M24.9013 43.068C25.1986 43.3663 25.5518 43.603 25.9408 43.7645C26.3298 43.926 26.7468 44.0092 27.168 44.0092C27.5891 44.0092 28.0062 43.926 28.3951 43.7645C28.7841 43.603 29.1373 43.3663 29.4346 43.068L32.5013 40.0013L24.5013 32.0013L21.4346 35.068C21.1363 35.3653 20.8996 35.7185 20.7381 36.1075C20.5766 36.4964 20.4934 36.9135 20.4934 37.3346C20.4934 37.7558 20.5766 38.1728 20.7381 38.5618C20.8996 38.9508 21.1363 39.304 21.4346 39.6013L24.9013 43.068ZM32.5013 24.0013L40.5013 32.0013L43.568 28.9346C43.8663 28.6373 44.103 28.2841 44.2645 27.8951C44.426 27.5062 44.5092 27.0891 44.5092 26.668C44.5092 26.2468 44.426 25.8298 44.2645 25.4408C44.103 25.0518 43.8663 24.6986 43.568 24.4013L40.1013 20.9346C39.804 20.6363 39.4508 20.3996 39.0618 20.2381C38.6728 20.0766 38.2558 19.9934 37.8346 19.9934C37.4135 19.9934 36.9964 20.0766 36.6075 20.2381C36.2185 20.3996 35.8653 20.6363 35.568 20.9346L32.5013 24.0013Z" stroke="#750EF4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span><h1>' . $form['heading'] . '</h1></div>';
			$html .= '<div class="wpinsights-goodbye-form-body"><p class="wpinsights-goodbye-form-caption">' . esc_html( $form['body'] ) . '</p>';
			if( is_array( $form['options'] ) ) {
				$html .= '<div id="wpinsights-goodbye-options" class="wpinsights-goodbye-options"><ul>';
				foreach( $form['options'] as $option ) {
					if( is_array( $option ) ) {
						$id = strtolower( str_replace( " ", "_", esc_attr( $option['label'] ) ) );
						$id = $id . '_' . $class_plugin_name;
						$html .= '<li class="has-goodbye-extra">';
						$html .= '<input type="radio" name="wpinsights-'. esc_attr( $class_plugin_name ) .'-goodbye-options" id="' . esc_attr( $id ) . '" value="' . esc_attr( $option['label'] ) . '">';
						$html .= '<div><label for="' . $id . '">' . esc_attr( $option['label'] ) . '</label>';
						if( isset( $option[ 'extra_field' ] ) && ! isset( $option['type'] )) {
							$html .= '<input type="text" style="display: none" name="'. esc_attr( $id ) .'" id="' . str_replace( " ", "", esc_attr( $option['extra_field'] ) ) . '" placeholder="' . esc_attr( $option['extra_field'] ) . '">';
						}
						if( isset( $option[ 'extra_field' ] ) && isset( $option['type'] )) {
							$html .= '<'. $option['type'] .' style="display: none" type="text" name="'. esc_attr( $id ) .'" id="' . str_replace( " ", "", esc_attr( $option['extra_field'] ) ) . '" placeholder="' . esc_attr( $option['extra_field'] ) . '"></' . $option['type'] . '>';
						}
						$html .= '</div></li>';
					} else {
						$id = strtolower( str_replace( " ", "_", esc_attr( $option ) ) );
						$id = $id . '_' . $class_plugin_name;
						$html .= '<li><input type="radio" name="wpinsights-'. $class_plugin_name .'-goodbye-options" id="' . esc_attr( $id ) . '" value="' . esc_attr( $option ) . '"> <label for="' . $id . '">' . esc_attr( $option ) . '</label></li>';
					}
				}
				$html .= '</ul></div><!-- .wpinsights-'. $class_plugin_name .'-goodbye-options -->';
			}
			$html .= '</div><!-- .wpinsights-goodbye-form-body -->';
			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __( 'Submitting form', 'essential-addons-for-elementor-lite' ) . '</p>';

			$wrapper_class = '.wpinsights-goodbye-form-wrapper-'. $class_plugin_name;

			$styles = '';
			$styles .= '<style type="text/css">';
				$styles .= '.wpinsights-form-active-' . $class_plugin_name . ' .wpinsights-goodbye-form-bg {';
					$styles .= 'background: rgba( 0, 0, 0, .8 );position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9;';
				$styles .= '}';
				$styles .= $wrapper_class . '{';
					$styles .= 'position: relative; display: none;';
				$styles .= '}';
				$styles .= '.wpinsights-form-active-' . $class_plugin_name . ' ' . $wrapper_class . '{';
					$styles .= 'display: flex !important; position: fixed;top: 0;left: 0;width: 100%;height: 100%; justify-content: center; align-items: center;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form { display: none; }';
				$styles .= '.wpinsights-form-active-' . $class_plugin_name . ' .wpinsights-goodbye-form {';
					$styles .= 'position: relative !important; width: 550px; max-width: 80%; background: #fff; box-shadow: 2px 8px 23px 3px rgba(0,0,0,.2); border-radius: 3px; white-space: normal; overflow: hidden; display: block; z-index: 999999;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-head {';
					$styles .= 'background: #fff; color: #495157; padding: 18px; box-shadow: 0 0 8px rgba(0,0,0,.1); font-size: 15px;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form .wpinsights-goodbye-form-head strong { font-size: 15px; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body { padding: 8px 18px; color: #333; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body label { padding-left: 5px; color: #6d7882; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body .wpinsights-goodbye-form-caption {';
					$styles .= 'font-weight: 500; font-size: 15px; color: #495157; line-height: 1.4;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body #wpinsights-goodbye-options { padding-top: 5px; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li { margin-bottom: 15px; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div { display: inline; padding-left: 3px; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > input, '. $wrapper_class .' .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > textarea {';
					$styles .= 'margin: 10px 18px; padding: 8px; width: 80%;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .deactivating-spinner { display: none; padding-bottom: 20px !important; }';
				$styles .= $wrapper_class . ' .deactivating-spinner .spinner { float: none; margin: 4px 4px 0 18px; vertical-align: bottom; visibility: visible; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-footer { padding: 8px 18px; margin-bottom: 15px; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-footer > .wpinsights-goodbye-form-buttons { display: flex; align-items: center; justify-content: space-between; }';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-footer .wpinsights-submit-btn {';
					$styles .= 'background-color: #f3bafd; -webkit-border-radius: 3px; border-radius: 3px; color: #0c0d0e; line-height: 1; padding: 10px 20px; font-size: 13px; font-weight: 500; text-transform: uppercase; transition: .3s;';
				$styles .= '}';
                $styles .= $wrapper_class . ' .wpinsights-goodbye-form-footer .wpinsights-submit-btn:hover {';
					$styles .= 'background-color: #f5d0fe;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .wpinsights-goodbye-form-footer .wpinsights-deactivate-btn {';
					$styles .= 'font-size: 13px; color: #a4afb7; background: none; float: right; padding-right: 10px; width: auto; text-decoration: underline;';
				$styles .= '}';
				$styles .= $wrapper_class . ' .test {';
				$styles .= '}';
				$styles .= '
                    
                    .wpinsights-form-active-essential_adons_elementor .wpinsights-goodbye-form-wrapper-essential_adons_elementor {
                        display: flex !important;
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        width: 100%;
                        z-index: 9999;
                        height: 100%;
                        justify-content: center;
                        align-items: center;
                    }
                    .wpinsights-form-active-essential_adons_elementor .wpinsights-goodbye-form {
                        border-radius: 8px;
                        width: 563px;
                        overflow: visible;
                        position: relative;
                    }
                    .wpinsights-goodbye-form .ea__modal-close-btn {
                        position: absolute;
                        top: 0;
                        right: -55px;
                        width: 40px;
                        height: 40px;
                        background: #fff;
                        border-radius: 50%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 12px;
                        font-weight: 500;
                        color: #475467;
                        cursor: pointer;
                    }
                    .wpinsights-goodbye-form .ea__modal-close-btn svg {
                        width: 20px;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-head {
                        background: #fff;
                        color: #495157;
                        padding: 24px;
                        border-radius: 8px 8px 0 0;
                        box-shadow: none;
                        border-bottom: 1px solid #EAECF0;
                        display: flex;
                        flex-direction: column;
                        gap: 16px;
                        align-items: center;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-head > span {
                        display: inline-flex;
                    }
                    .wpinsights-goodbye-form-head h1 {
                        font-size: 22px;
                        font-weight: 450;
                        color: #1D2939;
                        padding: 0;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body {
                        padding: 32px 40px;
                        max-height: calc(70vh - 230px);
                        overflow: auto;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body .wpinsights-goodbye-form-caption {
                        font-size: 18px;
                        color: #1D2939;
                        margin: 0;
                        padding-bottom: 16px;
                        font-weight: 400;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options {
                        padding-top: 0px;
                    }
                    .widefat td ul {
                        font-size: 14px;
                        margin: 0;
                        color: #475467;
                        line-height: 1.4em;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li:not(:last-child) {
                        margin-bottom: 16px;  
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li:last-child {
                        margin-bottom: 0;
                    }
                    input[type=radio] {
                        border-radius: 50%;
                        margin-right: .25rem;
                        line-height: .71428571;
                        margin: 0;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body label {
                        padding-left: 4px;
                        color: #475467;
                        font-size: 14px;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > input, .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > textarea {
                        padding: 10px 16px;
                        border-radius: 8px;
                        width: 100%;
                        border: 1px solid #D0D5DD;
                        margin: 0;
                        margin-top: 16px;
                        color: #1D2939;
                        font-size: 14px;
                        line-height: 1.5em;
                        resize: none;
                        max-height: 44px;
                    }
                    input[type=radio] {
                        border: 1px solid #D0D5DD;
                        box-shadow: none;
                    }
                    input[type=radio]:checked::before {
                        content: "";
                        border-radius: 50%;
                        width: .88rem;
                        height: .88rem;
                        margin: -.95px;
                        background-color: #750EF4;
                        border: 1px solid #E2CBFF;
                        line-height: 1.14285714;
                        outline: 0;
                    }
                    input[type=radio]:focus {
                        border-color: #D0D5DD;
                        box-shadow: none;
                        outline: 0;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > input::placeholder,
                     .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-body #wpinsights-goodbye-options ul > li > div > textarea::placeholder{
                        color: #C6CBD2 !important;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-footer {
                        padding: 16px 36px;
                        margin-bottom: 0;
                        border-top: 1px solid #EAECF0;
                    }
                    input:focus,
                    textarea:focus {
                        box-shadow: none !important;
                        outline: 0 !important;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-footer > .wpinsights-goodbye-form-buttons {
                        display: flex;
                        align-items: center;
                        justify-content: flex-start;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-footer .wpinsights-submit-btn {
                        background: conic-gradient(from 195.22deg at 68.31% 39.29%, #8f20fb00, #8f20fb 360deg), linear-gradient(0deg, #6f0af2, #6f0af2);
                        -webkit-border-radius: 8px;
                        border-radius: 8px;
                        color: #fff;
                        padding: 8px 16px;
                        font-size: 14px;
                        font-weight: 500;
                        line-height: 1.5em;
                        text-transform: capitalize;
                        transition: .3s;
                    }
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-footer .wpinsights-submit-btn:hover {
                        background: conic-gradient(from 195.22deg at 68.31% 39.29%, #8f20fb00, #8f20fb 360deg), linear-gradient(0deg, #6f0af2, #6f0af2);
                    }
                    
                    .wpinsights-goodbye-form-wrapper-essential_adons_elementor .wpinsights-goodbye-form-footer .wpsp-put-deactivate-btn {
                        font-size: 14px;
                        font-weight: 500;
                        color: #344054;
                        margin-left: 10px;
                    }
                    .wp-person a:focus .gravatar, a:focus, a:focus .media-icon img, a:focus .plugin-icon {
                        color: #043959;
                        box-shadow: none;
                        outline: 0;
                    }
                    
                ';
			$styles .= '</style>';
			$styles .= '';



			echo $styles;
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#wpinsights-goodbye-link-<?php echo $class_plugin_name; ?>").on("click",function(){
						// We'll send the user to this deactivation link when they've completed or dismissed the form
						var url = document.getElementById("wpinsights-goodbye-link-<?php echo $class_plugin_name; ?>");
						$('body').toggleClass('wpinsights-form-active-<?php echo $class_plugin_name; ?>');
						$(".wpinsights-goodbye-form-wrapper-<?php echo $class_plugin_name; ?> #wpinsights-goodbye-form").fadeIn();
						$(".wpinsights-goodbye-form-wrapper-<?php echo $class_plugin_name; ?> #wpinsights-goodbye-form").html( '<?php echo $html; ?>' + '<div class="wpinsights-goodbye-form-footer"><div class="wpinsights-goodbye-form-buttons"><a id="wpinsights-submit-form-<?php echo $class_plugin_name; ?>" class="wpinsights-submit-btn" href="#"><?php _e( 'Submit and Deactivate', 'essential-addons-for-elementor-lite' ); ?></a>&nbsp;<a class="wpsp-put-deactivate-btn" href="'+url+'"><?php _e( 'Just Deactivate', 'essential-addons-for-elementor-lite' ); ?></a></div></div>');
						$('#wpinsights-submit-form-<?php echo $class_plugin_name; ?>').on('click', function(e){
							// As soon as we click, the body of the form should disappear
							$("#wpinsights-goodbye-form-<?php echo $class_plugin_name; ?> .wpinsights-goodbye-form-body").fadeOut();
							$("#wpinsights-goodbye-form-<?php echo $class_plugin_name; ?> .wpinsights-goodbye-form-footer").fadeOut();
							// Fade in spinner
							$("#wpinsights-goodbye-form-<?php echo $class_plugin_name; ?> .deactivating-spinner").fadeIn();
							e.preventDefault();
							var checkedInput = $("input[name='wpinsights-<?php echo esc_attr( $class_plugin_name ); ?>-goodbye-options']:checked"),
								checkedInputVal, details;
							if( checkedInput.length > 0 ) {
								checkedInputVal = checkedInput.val();
								details = $('input[name="'+ checkedInput[0].id +'"], textarea[name="'+ checkedInput[0].id +'"]').val();
							}

							if( typeof details === 'undefined' ) {
								details = '';
							}
							if( typeof checkedInputVal === 'undefined' ) {
								checkedInputVal = 'No Reason';
							}

							var data = {
								'action': 'deactivation_form_<?php echo esc_attr( $class_plugin_name ); ?>',
								'values': checkedInputVal,
								'details': details,
								'security': "<?php echo wp_create_nonce ( 'wpins_deactivation_nonce' ); ?>",
								'dataType': "json"
							}

							$.post(
								ajaxurl,
								data,
								function(response){
									// Redirect to original deactivation URL
									window.location.href = url;
								}
							);
						});
						$('#wpinsights-goodbye-options > ul ').on('click', 'li label, li > input', function( e ){
							var parent = $(this).parents('li');
							parent.siblings().find('label').next('input, textarea').css('display', 'none');
							parent.find('label').next('input, textarea').css('display', 'block');
						});
						// If we click outside the form, the form will close
						$('.wpinsights-goodbye-form-bg, #wpinsights-goodbye-form > .ea__modal-close-btn').on('click',function(){
							$("#wpinsights-goodbye-form").fadeOut();
							$('body').removeClass('wpinsights-form-active-<?php echo esc_attr( $class_plugin_name ); ?>');
						});
					});
				});
			</script>
		<?php }

		/**
         * Get Used Elements Count
         * Get eael all used elements from all pages
		 * @return array
         *
         * @since 3.7.0
		 */
		public static function get_used_elements_count() {
			global $wpdb;

			$sql           = "SELECT `post_id`
            FROM  $wpdb->postmeta
            WHERE `meta_key` = '_eael_widget_elements'";
			$post_ids      = $wpdb->get_col( $sql );
			$used_elements = [];

			foreach ( $post_ids as $post_id ) {
				$ea_elements = get_post_meta( (int) $post_id, '_eael_widget_elements', true );
				$el_controls = get_post_meta( (int) $post_id, '_elementor_controls_usage', true );
				if ( empty( $ea_elements ) || empty( $el_controls ) || ! is_array( $ea_elements ) || ! is_array( $el_controls ) ) {
					continue;
				}

				foreach ( $ea_elements as $element ) {
					$element_name        = "eael-{$element}";
					$replace_widget_name = array_flip( Elements_Manager::replace_widget_name() );
					$count               = 0;

					if ( isset( $replace_widget_name[ $element_name ] ) ) {
						$element_name = $replace_widget_name[ $element_name ];
					}

					if ( ! empty( $el_controls[ $element_name ] ) && is_array( $el_controls[ $element_name ] ) ) {
						$count = $el_controls[ $element_name ]['count'];
					}

					$used_elements[ $element_name ] = isset( $used_elements[ $element_name ] ) ? $used_elements[ $element_name ] + $count : $count;
				}

				array_walk_recursive( $el_controls, function ( $value, $key ) use ( &$used_elements ) {
					$element_name = '';

					if ( $key === 'eael_particle_switch' ) {
						$element_name = 'eael-section-particles';
					} elseif ( $key === 'eael_parallax_switcher' ) {
						$element_name = 'eael-section-parallax';
					} elseif ( $key === 'eael_tooltip_section_enable' ) {
						$element_name = 'eael-tooltip-section';
					} elseif ( $key === 'eael_ext_content_protection' ) {
						$element_name = 'eael-content-protection';
					} elseif ( $key === 'eael_cl_enable' ) {
						$element_name = 'eael-conditional-display';
					} elseif ( $key === 'eael_ext_advanced_dynamic_tags' ) {
						$element_name = 'eael-advanced-dynamic-tags';
					} 

					if ( ! empty( $element_name ) ) {
						$used_elements[ $element_name ] = isset( $used_elements[ $element_name ] ) ? $used_elements[ $element_name ] + $value : $value;
					}
				} );
			}

			return $used_elements;
		}
	}

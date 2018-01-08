<?php
/**
 * Dismissible Notices Handler.
 *
 * This library is designed to handle dismissible admin notices.
 *
 * LICENSE: This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details. You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://opensource.org/licenses/gpl-license.php>
 *
 * @package   Dismissible Notices Handler
 * @author    Julien Liabeuf <julien@liabeuf.fr>
 * @version   1.0
 * @license   GPL-2.0+
 * @link      https://julienliabeuf.com
 * @copyright 2016 Julien Liabeuf
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Dismissible_Notices_Handler' ) ) {

	final class Dismissible_Notices_Handler {

		/**
		 * @var Dismissible_Notices_Handler Holds the unique instance of the handler
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Library version
		 *
		 * @since 1.0
		 * @var string
		 */
		public $version = '1.0';

		/**
		 * Required version of PHP.
		 *
		 * @since 1.0
		 * @var string
		 */
		public $php_version_required = '5.5';

		/**
		 * Minimum version of WordPress required to use the library
		 *
		 * @since 1.0
		 * @var string
		 */
		public $wordpress_version_required = '4.2';

		/**
		 * @var array Holds all our registered notices
		 * @since 1.0
		 */
		private $notices;

		/**
		 * Instantiate and return the unique Dismissible_Notices_Handler object
		 *
		 * @since     1.0
		 * @return object Dismissible_Notices_Handler Unique instance of the handler
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Dismissible_Notices_Handler ) ) {
				self::$instance = new Dismissible_Notices_Handler;
				self::$instance->init();
			}

			return self::$instance;

		}

		/**
		 * Initialize the library
		 *
		 * @since 1.0
		 * @return void
		 */
		private function init() {

			// Make sure WordPress is compatible
			if ( ! self::$instance->is_wp_compatible() ) {
				self::$instance->spit_error(
					sprintf(
						/* translators: %s: required wordpress version */
						esc_html__( 'The library can not be used because your version of WordPress is too old. You need version %s at least.', 'wp-dismissible-notices-handler' ),
						self::$instance->wordpress_version_required
					)
				);

				return;
			}

			// Make sure PHP is compatible
			if ( ! self::$instance->is_php_compatible() ) {
				self::$instance->spit_error(
					sprintf(
						/* translators: %s: required php version */
						esc_html__( 'The library can not be used because your version of PHP is too old. You need version %s at least.', 'wp-dismissible-notices-handler' ),
						self::$instance->php_version_required
					)
				);

				return;
			}

			self::$instance->includes();

			add_action( 'admin_notices', array( self::$instance, 'display' ) );
			add_action( 'admin_print_scripts', array( self::$instance, 'load_script' ) );
			add_action( 'wp_ajax_dnh_dismiss_notice', array( self::$instance, 'dismiss_notice_ajax' ) );

		}

		/**
		 * Check if the current WordPress version fits the requirements
		 *
		 * @since  1.0
		 * @return boolean
		 */
		private function is_wp_compatible() {

			if ( version_compare( get_bloginfo( 'version' ), self::$instance->wordpress_version_required, '<' ) ) {
				return false;
			}

			return true;

		}

		/**
		 * Check if the version of PHP is compatible with this library
		 *
		 * @since  1.0
		 * @return boolean
		 */
		private function is_php_compatible() {

			if ( version_compare( phpversion(), self::$instance->php_version_required, '<' ) ) {
				return false;
			}

			return true;

		}

		/**
		 * Include all our files
		 *
		 * @since 1.0
		 * @return void
		 */
		private function includes() {
			require( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/helper-functions.php' );
		}

		/**
		 * Load the script
		 *
		 * @since 1.0
		 * @return void
		 */
		public function load_script() {
			wp_register_script( 'dnh', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/js/main.js', array( 'jquery' ), self::$instance->version, true );
			wp_enqueue_script( 'dnh' );
		}

		/**
		 * Display all the registered notices
		 *
		 * @since 1.0
		 * @return void
		 */
		public function display() {

			if ( is_null( self::$instance->notices ) || empty( self::$instance->notices ) ) {
				return;
			}

			foreach ( self::$instance->notices as $id => $notice ) {

				$id = self::$instance->get_id( $id );

				// Check if the notice was dismissed
				if ( self::$instance->is_dismissed( $id ) ) {
					continue;
				}

				// Check if the current user has required capability
				if ( ! empty( $notice['cap'] ) && ! current_user_can( $notice['cap'] ) ) {
					continue;
				}

				$class = array(
					'notice',
					$notice['type'],
					'is-dismissible',
					$notice['class'],
				);

				printf( '<div id="%3$s" class="%1$s"><p>%2$s</p></div>', trim( implode( ' ', $class ) ), $notice['content'], "dnh-$id" );

			}

		}

		/**
		 * Spits an error message at the top of the admin screen
		 *
		 * @since 1.0
		 *
		 * @param string $error Error message to spit
		 *
		 * @return void
		 */
		protected function spit_error( $error ) {
			printf(
				'<div style="margin: 20px; text-align: center;"><strong>%1$s</strong> %2$s</pre></div>',
				esc_html__( 'Dismissible Notices Handler Error:', 'wp-dismissible-notices-handler' ),
				wp_kses_post( $error )
			);
		}

		/**
		 * Sanitize a notice ID and return it
		 *
		 * @since 1.0
		 *
		 * @param string $id
		 *
		 * @return string
		 */
		public function get_id( $id ) {
			return sanitize_key( $id );
		}

		/**
		 * Get available notice types
		 *
		 * @since 1.0
		 * @return array
		 */
		public function get_types() {

			$types = array(
				'error',
				'updated',
			);

			return apply_filters( 'dnh_notice_types', $types );

		}

		/**
		 * Get the default arguments for a notice
		 *
		 * @since 1.0
		 * @return array
		 */
		private function default_args() {

			$args = array(
				'screen' => '', // Coming soon
				'scope'  => 'user', // Scope of the dismissal. Either user or global
				'cap'    => '', // Required user capability
				'class'  => '', // Additional class to add to the notice
			);

			return apply_filters( 'dnh_default_args', $args );

		}

		/**
		 * Register a new notice
		 *
		 * @since 1.0
		 *
		 * @param string $id      Notice ID, used to identify it
		 * @param string $type    Type of notice to display
		 * @param string $content Notice content
		 * @param array  $args    Additional parameters
		 *
		 * @return bool
		 */
		public function register_notice( $id, $type, $content, $args = array() ) {

			if ( is_null( self::$instance->notices ) ) {
				self::$instance->notices = array();
			}

			$id      = self::$instance->get_id( $id );
			$type    = in_array( $t = sanitize_text_field( $type ), self::$instance->get_types() ) ? $t : 'updated';
			$content = wp_kses_post( $content );
			$args    = wp_parse_args( $args, self::$instance->default_args() );

			if ( array_key_exists( $id, self::$instance->notices ) ) {

				self::$instance->spit_error(
					sprintf(
						/* translators: %s: required php version */
						esc_html__( 'A notice with the ID %s has already been registered.', 'wp-dismissible-notices-handler' ),
						"<code>$id</code>"
					)
				);

				return false;
			}

			$notice = array(
				'type'    => $type,
				'content' => $content,
			);

			$notice = array_merge( $notice, $args );

			self::$instance->notices[ $id ] = $notice;

			return true;

		}

		/**
		 * Notice dismissal triggered by Ajax
		 *
		 * @since 1.0
		 * @return void
		 */
		public function dismiss_notice_ajax() {

			if ( ! isset( $_POST['id'] ) ) {
				echo 0;
				exit;
			}

			if ( empty( $_POST['id'] ) || false === strpos( $_POST['id'], 'dnh-' ) ) {
				echo 0;
				exit;
			}

			$id = self::$instance->get_id( str_replace( 'dnh-', '', $_POST['id'] ) );

			echo self::$instance->dismiss_notice( $id );
			exit;

		}

		/**
		 * Dismiss a notice
		 *
		 * @since 1.0
		 *
		 * @param string $id ID of the notice to dismiss
		 *
		 * @return bool
		 */
		public function dismiss_notice( $id ) {

			$notice = self::$instance->get_notice( self::$instance->get_id( $id ) );

			if ( false === $notice ) {
				return false;
			}

			if ( self::$instance->is_dismissed( $id ) ) {
				return false;
			}

			return 'user' === $notice['scope'] ? self::$instance->dismiss_user( $id ) : self::$instance->dismiss_global( $id );

		}

		/**
		 * Dismiss notice for the current user
		 *
		 * @since 1.0
		 *
		 * @param string $id Notice ID
		 *
		 * @return int|bool
		 */
		private function dismiss_user( $id ) {

			$dismissed = self::$instance->dismissed_user();

			if ( in_array( $id, $dismissed ) ) {
				return false;
			}

			array_push( $dismissed, $id );

			return update_user_meta( get_current_user_id(), 'dnh_dismissed_notices', $dismissed );

		}

		/**
		 * Dismiss notice globally on the site
		 *
		 * @since 1.0
		 *
		 * @param string $id Notice ID
		 *
		 * @return bool
		 */
		private function dismiss_global( $id ) {

			$dismissed = self::$instance->dismissed_global();

			if ( in_array( $id, $dismissed ) ) {
				return false;
			}

			array_push( $dismissed, $id );

			return update_option( 'dnh_dismissed_notices', $dismissed );

		}

		/**
		 * Restore a dismissed notice
		 *
		 * @since 1.0
		 *
		 * @param string $id ID of the notice to restore
		 *
		 * @return bool
		 */
		public function restore_notice( $id ) {

			$id     = self::$instance->get_id( $id );
			$notice = self::$instance->get_notice( $id );

			if ( false === $notice ) {
				return false;
			}

			return 'user' === $notice['scope'] ? self::$instance->restore_user( $id ) : self::$instance->restore_global( $id );

		}

		/**
		 * Restore a notice dismissed by the current user
		 *
		 * @since 1.0
		 *
		 * @param string $id ID of the notice to restore
		 *
		 * @return bool
		 */
		private function restore_user( $id ) {

			$id     = self::$instance->get_id( $id );
			$notice = self::$instance->get_notice( $id );

			if ( false === $notice ) {
				return false;
			}

			$dismissed = self::$instance->dismissed_user();

			if ( ! in_array( $id, $dismissed ) ) {
				return false;
			}

			$flip = array_flip( $dismissed );
			$key  = $flip[ $id ];

			unset( $dismissed[ $key ] );

			return update_user_meta( get_current_user_id(), 'dnh_dismissed_notices', $dismissed );

		}

		/**
		 * Restore a notice dismissed globally
		 *
		 * @since 1.0
		 *
		 * @param string $id ID of the notice to restore
		 *
		 * @return bool
		 */
		private function restore_global( $id ) {

			$id     = self::$instance->get_id( $id );
			$notice = self::$instance->get_notice( $id );

			if ( false === $notice ) {
				return false;
			}

			$dismissed = self::$instance->dismissed_global();

			if ( ! in_array( $id, $dismissed ) ) {
				return false;
			}

			$flip = array_flip( $dismissed );
			$key  = $flip[ $id ];

			unset( $dismissed[ $key ] );

			return update_option( 'dnh_dismissed_notices', $dismissed );

		}

		/**
		 * Get all dismissed notices
		 *
		 * This includes notices dismissed globally or per user.
		 *
		 * @since 1.0
		 * @return array
		 */
		public function dismissed_notices() {

			$user   = self::$instance->dismissed_user();
			$global = self::$instance->dismissed_global();

			return array_merge( $user, $global );

		}

		/**
		 * Get user dismissed notices
		 *
		 * @since 1.0
		 * @return array
		 */
		private function dismissed_user() {

			$dismissed = get_user_meta( get_current_user_id(), 'dnh_dismissed_notices', true );

			if ( '' === $dismissed ) {
				$dismissed = array();
			}

			return $dismissed;

		}

		/**
		 * Get globally dismissed notices
		 *
		 * @since 1.0
		 * @return array
		 */
		private function dismissed_global() {
			return get_option( 'dnh_dismissed_notices', array() );
		}

		/**
		 * Check if a notice has been dismissed
		 *
		 * @since 1.0
		 *
		 * @param string $id Notice ID
		 *
		 * @return bool
		 */
		public function is_dismissed( $id ) {

			$dismissed = self::$instance->dismissed_notices();

			if ( ! in_array( self::$instance->get_id( $id ), $dismissed ) ) {
				return false;
			}

			return true;

		}

		/**
		 * Get all the registered notices
		 *
		 * @since 1.0
		 * @return array|null
		 */
		public function get_notices() {
			return self::$instance->notices;
		}

		/**
		 * Return a specific notice
		 *
		 * @since 1.0
		 *
		 * @param string $id Notice ID
		 *
		 * @return array|false
		 */
		public function get_notice( $id ) {

			$id = self::$instance->get_id( $id );

			if ( ! is_array( self::$instance->notices ) || ! array_key_exists( $id, self::$instance->notices ) ) {
				return false;
			}

			return self::$instance->notices[ $id ];

		}

	}

	/**
	 * The main function responsible for returning the unique Dismissible Notices Handler instance
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * @since 1.0
	 * @return object Dismissible_Notices_Handler
	 */
	function DNH() {
		return Dismissible_Notices_Handler::instance();
	}

	/**
	 * Get the library running
	 */
	DNH();

}

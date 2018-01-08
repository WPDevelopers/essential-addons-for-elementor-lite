<?php
/**
 * WP Review Me
 *
 * A lightweight library to help you get more reviews for your WordPress theme/plugin.
 *
 * LICENSE: This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details. You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://opensource.org/licenses/gpl-license.php>
 *
 * @package   WP Review Me
 * @author    Julien Liabeuf <julien@liabeuf.fr>
 * @version   2.0.1
 * @license   GPL-2.0+
 * @link      https://julienliabeuf.com
 * @copyright 2016 Julien Liabeuf
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_Review_Me' ) ) {

	class WP_Review_Me {

		/**
		 * Library version
		 *
		 * @since 1.0
		 * @var string
		 */
		public $version = '2.0.1';

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
		 * Holds the unique identifying key for this particular instance
		 *
		 * @since 1.0
		 * @var string
		 */
		protected $key;

		/**
		 * Link unique ID
		 *
		 * @since 1.0
		 * @var string
		 */
		public $link_id;

		/**
		 * WP_Review_Me constructor.
		 *
		 * @since 1.0
		 *
		 * @param array $args Object settings
		 */
		public function __construct( $args ) {

			$args             = wp_parse_args( $args, $this->get_defaults() );
			$this->days       = $args['days_after'];
			$this->type       = $args['type'];
			$this->slug       = $args['slug'];
			$this->rating     = $args['rating'];
			$this->message    = $args['message'];
			$this->link_label = $args['link_label'];
			$this->cap        = $args['cap'];
			$this->scope      = $args['scope'];

			// Set the unique identifying key for this instance
			$this->key     = 'wrm_' . substr( md5( plugin_basename( __FILE__ ) ), 0, 20 );
			$this->link_id = 'wrm-review-link-' . $this->key;

			// Instantiate
			$this->init();

		}

		/**
		 * Get default object settings
		 *
		 * @since 1.0
		 * @return array
		 */
		protected function get_defaults() {

			$defaults = array(
				'days_after' => 10,
				'type'       => '',
				'slug'       => '',
				'rating'     => 5,
				'message'    => sprintf( esc_html__( 'Hey! It&#039;s been a little while that you&#039;ve been using this product. You might not realize it, but user reviews are such a great help to us. We would be so grateful if you could take a minute to leave a review on WordPress.org. Many thanks in advance :)', 'wp-review-me' ) ),
				'link_label' => esc_html__( 'Click here to leave your review', 'wp-review-me' ),
				// Parameters used in WP Dismissible Notices Handler
				'cap'        => 'administrator',
				'scope'      => 'global',
			);

			return $defaults;

		}

		/**
		 * Initialize the library
		 *
		 * @since 1.0
		 * @return void
		 */
		private function init() {

			// Make sure WordPress is compatible
			if ( ! $this->is_wp_compatible() ) {
				$this->spit_error(
					sprintf(
						esc_html__( 'The library can not be used because your version of WordPress is too old. You need version %s at least.', 'wp-review-me' ),
						$this->wordpress_version_required
					)
				);

				return;
			}

			// Make sure PHP is compatible
			if ( ! $this->is_php_compatible() ) {
				$this->spit_error(
					sprintf(
						esc_html__( 'The library can not be used because your version of PHP is too old. You need version %s at least.', 'wp-review-me' ),
						$this->php_version_required
					)
				);

				return;
			}

			// Make sure the dependencies are loaded
			if ( ! function_exists( 'dnh_register_notice' ) ) {

				$dnh_file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/wp-dismissible-notices-handler/handler.php';

				if ( file_exists( $dnh_file ) ) {
					require( $dnh_file );
				}

				if ( ! function_exists( 'dnh_register_notice' ) ) {
					$this->spit_error(
						sprintf(
							esc_html__( 'Dependencies are missing. Please run a %s.', 'wp-review-me' ),
							'<code>composer install</code>'
						)
					);

					return;
				}
			}

			add_action( 'admin_footer', array( $this, 'script' ) );
			add_action( 'wp_ajax_wrm_clicked_review', array( $this, 'dismiss_notice' ) );

			// And let's roll... maybe.
			$this->maybe_prompt();

		}

		/**
		 * Check if the current WordPress version fits the requirements
		 *
		 * @since  1.0
		 * @return boolean
		 */
		private function is_wp_compatible() {

			if ( version_compare( get_bloginfo( 'version' ), $this->wordpress_version_required, '<' ) ) {
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

			if ( version_compare( phpversion(), $this->php_version_required, '<' ) ) {
				return false;
			}

			return true;

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
				esc_html__( 'WP Review Me Error:', 'wp-review-me' ),
				wp_kses_post( $error )
			);
		}

		/**
		 * Check if it is time to ask for a review
		 *
		 * @since 1.0
		 * @return bool
		 */
		public function is_time() {

			$installed = (int) get_option( $this->key, false );

			if ( false === $installed ) {
				$this->setup_date();
				$installed = time();
			}

			if ( $installed + ( $this->days * 86400 ) > time() ) {
				return false;
			}

			return true;

		}

		/**
		 * Save the current date as the installation date
		 *
		 * @since 1.0
		 * @return void
		 */
		protected function setup_date() {
			update_option( $this->key, time() );
		}

		/**
		 * Get the review link
		 *
		 * @since 1.0
		 * @return string
		 */
		protected function get_review_link() {

			$link = 'https://wordpress.org/support/';

			switch ( $this->type ) {

				case 'theme':
					$link .= 'theme/';
					break;

				case 'plugin':
					$link .= 'plugin/';
					break;

			}

			$link .= $this->slug . '/reviews';
			$link = add_query_arg( 'rate', $this->rating, $link );
			$link = esc_url( $link . '#new-post' );

			return $link;

		}

		/**
		 * Get the complete link tag
		 *
		 * @since 1.0
		 * @return string
		 */
		protected function get_review_link_tag() {

			$link = $this->get_review_link();

			return "<a href='$link' target='_blank' id='$this->link_id'>$this->link_label</a>";

		}

		/**
		 * Trigger the notice if it is time to ask for a review
		 *
		 * @since 1.0
		 * @return void
		 */
		protected function maybe_prompt() {

			if ( ! $this->is_time() ) {
				return;
			}

			dnh_register_notice( $this->key, 'updated', $this->get_message(), array(
				'scope' => $this->scope,
				'cap'   => $this->cap
			) );

		}

		/**
		 * Echo the JS script in the admin footer
		 *
		 * @since 1.0
		 * @return void
		 */
		public function script() { ?>

			<script>
				jQuery(document).ready(function($) {
					$('#<?php echo $this->link_id; ?>').on('click', wrmDismiss);
					function wrmDismiss() {

						var data = {
							action: 'wrm_clicked_review',
							id: '<?php echo $this->link_id; ?>'
						};

						jQuery.ajax({
							type:'POST',
							url: ajaxurl,
							data: data,
							success:function( data ){
								console.log(data);
							}
						});

					}
				});
			</script>

		<?php }

		/**
		 * Dismiss the notice when the review link is clicked
		 *
		 * @since 1.0
		 * @return void
		 */
		public function dismiss_notice() {

			if ( empty( $_POST ) ) {
				echo 'missing POST';
				die();
			}

			if ( ! isset( $_POST['id'] ) ) {
				echo 'missing ID';
				die();
			}

			$id = sanitize_text_field( $_POST['id'] );

			if ( $id !== $this->link_id ) {
				echo "not this instance's job";
				die();
			}

			// Get the DNH notice ID ready
			$notice_id = DNH()->get_id( str_replace( 'wrm-review-link-', '', $id ) );
			$dismissed = DNH()->dismiss_notice( $notice_id );
			
			echo $dismissed;

			/**
			 * Fires right after the notice has been dismissed. This allows for various integrations to perform additional tasks.
			 *
			 * @since 1.0
			 *
			 * @param string $id        The notice ID
			 * @param string $notice_id The notice ID as defined by the DNH class
			 */
			do_action( 'wrm_after_notice_dismissed', $id, $notice_id );

			// Stop execution here
			die();

		}

		/**
		 * Get the review prompt message
		 *
		 * @since 1.0
		 * @return string
		 */
		protected function get_message() {

			$message = $this->message;
			$link    = $this->get_review_link_tag();
			$message = $message . ' ' . $link;

			return wp_kses_post( $message );

		}

	}

}
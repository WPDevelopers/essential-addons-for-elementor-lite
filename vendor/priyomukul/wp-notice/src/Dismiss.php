<?php

namespace PriyoMukul\WPNotice;

use PriyoMukul\WPNotice\Utils\Base;
use PriyoMukul\WPNotice\Utils\Helper;

#[\AllowDynamicProperties]
class Dismiss extends Base {
	use Helper;

	private $id;
	private $scope = 'user';

	/**
	 * @var Notices
	 */
	private $app;
	private $hook;

	public function __construct( $id, $options, $app ) {
		$this->id  = $id;
		$this->app = $app;

		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $_value ) {
				$this->{$key} = $_value;
			}
		}

		$this->hook = $this->app->app . '_wpnotice_dismiss_notice';

		add_action( 'wp_ajax_' . $this->hook, [ $this, 'ajax_maybe_dismiss_notice' ] );
	}

	/**
	 * Print the script for dismissing the notice.
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	public function print_script() {
		$nonce = wp_create_nonce( 'wpnotice_dismiss_notice_' . $this->id );
		$_id   = '#wpnotice-' . esc_attr( $this->app->app ) . '-' . esc_attr( $this->id );
		?>
		<script>
			window.addEventListener('load', function () {
				var dismissBtn = document.querySelector('<?php echo $_id ?> .notice-dismiss');
				var extraDismissBtn = document.querySelectorAll('<?php echo $_id ?> .dismiss-btn');

				function wpNoticeDismissFunc(event) {
					event.preventDefault();

					var httpRequest = new XMLHttpRequest(),
						postData = '',
						dismiss = event.target.dataset?.hasOwnProperty('dismiss') && event.target.dataset.dismiss || false,
						later = event.target.dataset?.hasOwnProperty('later') && event.target.dataset.later || false;

					if (dismiss || later) {
						jQuery(event.target.offsetParent).slideUp(200);
					}

					// Data has to be formatted as a string here.
					postData += 'id=<?php echo esc_attr( rawurlencode( $this->id ) ); ?>';
					postData += '&action=<?php echo esc_attr( $this->hook ); ?>';
					if (dismiss) {
						postData += '&dismiss=' + dismiss;
					}
					if (later) {
						postData += '&later=' + later;
					}

					postData += '&nonce=<?php echo esc_html( $nonce ); ?>';

					httpRequest.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>');
					httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					httpRequest.send(postData);
				}

				// Add an event listener to the dismiss button.
				dismissBtn && dismissBtn.addEventListener('click', wpNoticeDismissFunc);
				if (extraDismissBtn.length > 0) {
					extraDismissBtn.forEach(btn => btn.addEventListener('click', wpNoticeDismissFunc))
				}
			});
		</script>
		<?php
	}


	/**
	 * Run check to see if we need to dismiss the notice.
	 * If all tests are successful then call the dismiss_notice() method.
	 *
	 * @access public
	 * @return void
	 * @since 1.0
	 */
	public function ajax_maybe_dismiss_notice() {
		// Sanity check: Early exit if we're not on a _dismiss_notice action.
		if ( ! isset( $_POST['action'] ) || $this->hook !== $_POST['action'] ) {
			return;
		}

		// Sanity check: Early exit if the ID of the notice is not the one from this object.
		if ( ! isset( $_POST['id'] ) || $this->id !== $_POST['id'] ) {
			return;
		}

		// Security check: Make sure nonce is OK.
		check_ajax_referer( 'wpnotice_dismiss_notice_' . $this->id, 'nonce', true );

		if ( isset( $_POST['later'] ) ) {
			$_recurrence = intval( $this->recurrence ) || 15;
			$_queue      = $this->app->storage()->get();

			$_queue[ $this->id ]['start']  = $this->strtotime( "+$_recurrence days" );
			$_queue[ $this->id ]['expire'] = $this->strtotime( "+" . ( $_recurrence + 3 ) . " days" );
			$this->app->storage()->save( $_queue );

			return;
		}

		// If we got this far, we need to dismiss the notice.
		$this->dismiss_notice();
	}

	/**
	 * Actually dismisses the notice.
	 *
	 * @access private
	 * @return bool
	 * @since 1.0
	 */
	public function dismiss_notice() {
		if ( ! defined( 'WPNOTICE_EXPIRED_TIME' ) ) {
			define( 'WPNOTICE_EXPIRED_TIME', HOUR_IN_SECONDS * 10 );
		}

		set_transient( 'wpnotice_priority_time_expired', true, time() + WPNOTICE_EXPIRED_TIME );

		if ( 'user' === $this->scope ) {
			return $this->app->storage()->save_meta( $this->id );
		}

		$_key = $this->app->app . '_' . $this->id . '_notice_dismissed';

		return $this->app->storage()->save( $_key );
	}

	/**
	 * Check if is dismissed or not
	 * @return boolean
	 */
	public function is_dismissed() {
		if ( 'user' === $this->scope ) {
			return $this->app->storage()->get_meta( $this->id );
		}

		$_key = $this->app->app . '_' . $this->id . '_notice_dismissed';

		return $this->app->storage()->get( $_key );
	}
}
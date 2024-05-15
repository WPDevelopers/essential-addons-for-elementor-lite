<?php

namespace PriyoMukul\WPNotice\Utils;

use PriyoMukul\WPNotice\Notices;

#[\AllowDynamicProperties]
class CacheBank {
	private static $instance;

	private static $accounts = [];

	private static $notices = [];

	private $priority_key = 'wpnotice_priority_time_expired';

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_notices', [ $this, 'notices' ] );
		add_action( 'admin_footer', [ $this, 'scripts' ] );
	}


	public function create_account( $app ) {
		$priority = isset( $app->options['priority'] ) ? $app->priority : count( self::$accounts );

		if ( isset( $app->args['version'] ) && $app->args['version'] === '1.0.0' ) {
			$priority = 999 + count( self::$accounts );
		}

		if ( isset( self::$accounts[ $priority ] ) ) {
			return;
		}

		self::$accounts[ $priority ] = $app;

		ksort( self::$accounts );
	}

	public function calculate_deposits( $app ) {
		if ( ! $app instanceof Notices ) {
			return;
		}

		foreach ( $app->notices as $id => $notice ) {
			$this->deposit( $app->id, $id, $notice );
		}
	}

	public function deposit( $account, $id, $value ) {
		self::$notices[ $account ][ $id ] = $value;
	}

	private function get_current_account() {
		if ( ! empty( self::$accounts ) ) {
			/**
			 * @var Notices $account
			 */
			foreach ( self::$accounts as $account ) {
				$notices = $this->eligible_notices( $account->notices, $account->queue );

				$notices = array_filter( $notices, function ( $notice_key ) use ( $account ) {
					$notice = self::$notices[ $account->id ][ $notice_key ];

					return $notice->show();
				} );

				if ( ! empty( $notices ) ) {
					return $account;
				}
			}
		}

		return false;
	}

	/**
	 * @return Notices
	 */
	public function get() {
		/**
		 * @var Notices $current_notice ;
		 */
		return $this->get_current_account();
	}

	public function notices() {
		if ( get_transient( $this->priority_key ) ) {
			return;
		}

		$notice = $this->get();

		if ( $notice instanceof Notices ) {
			$notice->notices();
		}
	}

	public function scripts() {
		if ( get_transient( $this->priority_key ) ) {
			return;
		}

		$notice = $this->get();

		if ( $notice instanceof Notices ) {
			$notice->scripts();
		}
	}

	/**
	 * This is a fallback method of Notices::eligible_notices.
	 * Please make sure changes are done in both classes.
	 *
	 * @param $notices
	 * @param $queue
	 *
	 * @return array
	 */
	private function eligible_notices( $notices = [], $queue = [] ) {
		$_sorted_queue = [];

		if ( ! empty ( $queue ) ) {
			array_walk( $queue, function ( $value, $key ) use ( &$_sorted_queue, $notices ) {
				$notice = isset( $notices[ $key ] ) ? $notices[ $key ] : null;
				if ( ! is_null( $notice ) ) {
					if ( ! $notice->dismiss->is_dismissed() && ! $notice->is_expired() ) {
						$_sorted_queue[ $notice->options( 'start' ) ] = $key;
					}
				}
			} );
		}

		ksort( $_sorted_queue );

		return $_sorted_queue;
	}


}
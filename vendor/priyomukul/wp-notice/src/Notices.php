<?php

namespace PriyoMukul\WPNotice;

use Exception;
use PriyoMukul\WPNotice\Utils\Base;
use PriyoMukul\WPNotice\Utils\CacheBank;
use PriyoMukul\WPNotice\Utils\Helper;


/**
 * @property $notices []
 * @property $queue []
 * @property $id string
 * @property $stylesheet_url string
 */
#[\AllowDynamicProperties]
final class Notices extends Base {
	use Helper;

	const VERSION = '1.1.0';

	private $version = '1.1.0';

	public  $system_id = 'wpnotice_system';
	public  $app       = 'wpnotice';
	private $storage   = null;
	private $scripts   = null;

	private $args;

	/**
	 * A list of notice
	 * @var array
	 */
	private $notices = [];

	/**
	 * A list of notice based to timestamp (A Queue)
	 * @var false|mixed
	 */
	private $queue;

	/**
	 * @var CacheBank
	 */
	private static $cache_bank;

	/**
	 * @var bool
	 */
	private $dev_mode = false;

	/**
	 * Default notice system options
	 * @var array
	 */
	private $options = [
		'id'             => '',
		'stylesheet_url' => '',
		'priority'       => 1
	];

	private $deprecated_options = [
		'system_id' => 'id',
		'app'       => 'id',
		'scripts'   => 'stylesheet_url'
	];

	private $default_options = [
		'scripts_handle' => ''
	];

	/**
	 * This method takes an array as argument.
	 *
	 * @template $args
	 *
	 * @param $args
	 *
	 * @throws Exception
	 */
	public function __construct( $args ) {
		self::$cache_bank = CacheBank::get_instance();

		/**
		 * Check all the property is passed or not
		 */
		if ( ! isset( $args['version'] ) && self::VERSION === '1.1.0' ) {
			if ( ! is_array( $args ) ) {
				$this->error( "Argument of " . __CLASS__ . " should be an array. " . gettype( $args ) . " given." );
			}

			if ( empty( $args ) ) {
				$this->error( "Argument of " . __CLASS__ . " should not be an empty array." );
			}

			foreach ( $this->options as $key => $value ) {
				if ( ! isset( $args[ $key ] ) ) {
					$this->error( "Missing $key from argument list." );
				}
			}

			$this->options = wp_parse_args( $args, $this->options );
			$this->scripts = $this->stylesheet_url;
		}

		$this->system_id = ! empty( $args['id'] ) ? $args['id'] . '-notice-system' : 'wpnotice_system';
		$this->app       = ! empty( $args['id'] ) ? $args['id'] : 'wpnotice';
		$this->dev_mode  = ! empty( $args['dev_mode'] ) ? $args['dev_mode'] : $this->dev_mode;
		$this->args      = $args;

		if ( ! empty( $args['styles'] ) ) {
			$this->scripts = $args['styles'];
			unset( $args['styles'] );
		}

		$this->queue = $this->storage()->get( '', [] );

		self::$cache_bank->create_account( $this );
	}

	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}

		if ( ! empty( $this->options[ $name ] ) ) {
			return $this->options[ $name ];
		}

		if ( isset( $this->deprecated_options[ $name ] ) && ! empty( $this->options[ $this->deprecated_options[ $name ] ] ) ) {
			return $this->options[ $this->deprecated_options[ $name ] ];
		}

		if ( ! empty( $this->args[ $name ] ) ) {
			return $this->args[ $name ];
		}

		return null;
	}

	public function storage() {
		return $this->database( $this->args );
	}

	public function init() {
	}

	public function notices() {
		wp_enqueue_style( $this->system_id, $this->scripts );

		if ( ! $this->dev_mode ) {
			/**
			 * @var Notice $notice
			 */
			$notice = $this->current_notice();
			if ( $notice ) {
				$notice->display();
			}
		}

		/**
		 * Print all notices while dev_mode is enabled.
		 */
		$this->print_notices_for_dev_mode();
	}

	public function eligible_notices( $notices = [], $queue = [] ) {
		$_sorted_queue = [];
		$notices       = empty( $notices ) ? $this->notices : $notices;
		$queue         = empty( $queue ) ? $this->queue : $queue;

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

	public function scripts() {
		if ( ! $this->dev_mode ) {
			/**
			 * @var Notice $notice
			 */
			$notice = $this->current_notice();

			if ( $notice && $notice->show() ) {
				$notice->dismiss->print_script();
			}
		}

		/**
		 * Print scripts for all notices while dev_mode is enabled.
		 */
		$this->print_notices_for_dev_mode( true );
	}

	public function add( $id, $content, $options = [] ) {
		$this->notices[ $id ] = new Notice( $id, $content, $options, $this->queue, $this );

		self::$cache_bank->deposit( $this->id, $id, $this->notices[ $id ] );
	}

	private function current_notice() {
		$current_notice = current( $this->eligible_notices() );

		return isset( $this->notices[ $current_notice ] ) ? $this->notices[ $current_notice ] : false;
	}

	private function print_notices_for_dev_mode( $scripts = false ) {
		if ( $this->dev_mode ) {
			/**
			 * @var Notice $notice
			 */
			foreach ( $this->notices as $notice ) {
				if ( $scripts ) {
					$notice->dismiss->print_script();
				} else {
					$notice->display( true );
				}
			}
		}
	}
}
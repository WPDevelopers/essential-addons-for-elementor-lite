<?php

namespace PriyoMukul\WPNotice\Utils;

use PriyoMukul\WPNotice\Notices;

class NoticeRemover {
	private static $instance = null;

	public static function get_instance( $version ) {
		if ( self::$instance == null ) {
			self::$instance = new static( $version );
		}

		return self::$instance;
	}

	public function __construct( $version = '1.0.0', $instanceOf = null ) {
		add_action( 'init', function () use ( $version, $instanceOf ) {
			global $wp_filter;

			if ( $instanceOf === null ) {
				$instanceOf = Notices::class;
			}

			foreach ( $wp_filter['admin_notices']->callbacks[10] as $callback ) {
				if ( is_array( $callback['function'] ) && $callback['function'][0] instanceof $instanceOf ) {
					$notice = $callback['function'][0];

					if ( $notice->version === $version ) {
						remove_action( 'admin_notices', [ $notice, 'notices' ] );
						remove_action( 'admin_footer', [ $notice, 'scripts' ] );
					}
				}
			}
		} );
	}
}
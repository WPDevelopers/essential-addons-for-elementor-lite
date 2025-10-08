<?php

namespace PriyoMukul\WPNotice;

use PriyoMukul\WPNotice\Utils\Base;
use PriyoMukul\WPNotice\Utils\Helper;
use WP_Screen;
use function property_exists;

#[\AllowDynamicProperties]
class Notice extends Base {
	use Helper;

	private $app = null;

	private $id      = null;
	private $content = null;

	/**
	 * @var Dismiss
	 */
	public $dismiss;

	private $queue = [];

	/**
	 * @var int start
	 * @var int expire
	 * @var int recurrence Meaning this notice will appear after 10, 20 days.
	 * @var string scope
	 * @var array screens
	 * @var string type Notice type
	 * @var string capability
	 * @var bool dismissible
	 */
	private $options = [
		// 'start'       =>  192933, // timestamp
		// 'expire'       => 1029339, // timestamp
		'classes'     => '',
		'recurrence'  => false,
		'scope'       => 'user',
		'screens'     => null,
		'type'        => 'info',
		'capability'  => null,
		'dismissible' => false,
	];

	public function __construct( ...$args ) {
		list( $id, $content, $options, $queue, $app ) = $args;

		$this->app     = $app;
		$this->id      = $id;
		$this->content = $content;
		$this->queue   = $queue;
		$this->options = wp_parse_args( $options, $this->options );

		$this->dismiss = new Dismiss( $this->id, $this->options, $this->app );

		if ( ! isset( $queue[ $id ] ) || ( ! empty( $this->options['refresh'] ) && ( empty( $queue[ $id ]['refresh'] ) || $queue[ $id ]['refresh'] != $this->options['refresh'] ) ) ) {
			$queue[ $id ]   = [];
			$_eligible_keys = [ 'start', 'expire', 'recurrence', 'refresh' ];
			array_walk( $options, function ( $value, $key ) use ( $id, &$queue, $_eligible_keys ) {
				if ( in_array( $key, $_eligible_keys, true ) ) {
					$queue[ $id ][ $key ] = $value;
				}
			} );

			$this->queue = $queue;
			$this->app->storage()->save( $queue ); // saved in queue
		} else {
			$this->options = wp_parse_args( $queue[ $id ], $this->options );
		}

		if ( isset( $this->options['do_action'] ) ) {
			add_action( 'admin_init', [ $this, 'do_action' ] );
		}
	}

	public function do_action() {
		do_action( $this->options['do_action'], $this );
	}

	private function get_content() {
		if ( is_callable( $this->content ) ) {
			ob_start();
			call_user_func( $this->content );

			return ob_get_clean();
		}

		return $this->content;
	}

	public function display( $force = false ) {
		if ( ! $force && ! $this->show() ) {
			return;
		}

		$content = $this->get_content();
		if ( empty( $content ) ) {
			return; // Return if notice is empty.
		}

		$links = $this->get_links();

		// Print the notice.
		printf( '<div style="display: flex; flex-wrap: nowrap; gap: 15px; align-items: center;" id="%1$s" class="%2$s">%3$s<div class="wpnotice-content-wrapper">%4$s%5$s</div></div>', 'wpnotice-' . esc_attr( $this->app->app ) . '-' . esc_attr( $this->id ), // The ID.
			esc_attr( $this->get_classes() ), // The classes.
			! empty( $content['thumbnail'] ) ? $this->get_thumbnail( $content['thumbnail'] ) : '', ! empty( $content['html'] ) ? $content['html'] : $content, ! empty( $links ) ? $this->links( $links ) : '' );
	}

	public function get_links() {
		return ! empty( $this->content['links'] ) ? $this->content['links'] : ( ! empty( $this->options['links'] ) ? $this->options['links'] : [] );
	}

	public function links( $links ) {
		$output      = '<ul style="display: flex; width: 100%; align-items: center;" class="notice-links ' . $this->app->id . '-notice-links">';
		foreach ( $links as $link ) {
			$_attributes = '';
			$class = ! empty( $link['class'] ) ? $link['class'] : '';

			if ( ! empty( $link['attributes'] ) ) {
				$_attributes = $this->attributes( $link['attributes'] );
			}

			if( empty( $link['link'] ) ) {
				$link['link'] = '#';
			}

			$output .= '<li style="margin: 0 15px 0 0;" class="notice-link-item ' . $class . '">';
			$output .= ! empty( $link['link'] ) ? '<a href="' . esc_url( $link['link'] ) . '" ' . $_attributes . '>' : '';
			if ( isset( $link['icon_class'] ) ) {
				$output .= '<span style="margin-right: 5px" class="' . esc_attr( $link['icon_class'] ) . '"></span>';
			}
			$output .= $link['label'];
			$output .= ! empty( $link['link'] ) ? '</a>' : '';
			$output .= '</li>';
		}

		$output .= '</ul>';

		return $output;
	}

	public function attributes( $params = [] ) {
		$_attr = [];
		if ( ! empty( $params ) ) {
			foreach ( $params as $key => $value ) {
				$_attr[ $key ] = $value;

				if( $key === 'class' ) {
					$_attr[ $key ] = [ $value ];
				}

				if( in_array( $key, [ 'data-later', 'data-dismiss' ] ) ) {
					$_attr[ 'class' ][] = 'dismiss-btn';
				}
			}
		}

		$_attrs = [];
		if( ! empty( $_attr ) ) {
			foreach ( $_attr as $property => $value ) {
				$_attrs[] = "$property=" . '"' . ( is_array( $value ) ? esc_attr( implode(' ', $value ) ) : esc_attr( $value ) ) . '"';
			}
		}

		return implode( ' ', $_attrs );
	}

	public function url( $params = [] ) {
		$nonce = wp_create_nonce( 'wpnotice_dismiss_notice_' . $this->id );

		return esc_url( add_query_arg( [
			'action' => 'wpnotice_dismiss_notice',
			'id'     => $this->id,
			'nonce'  => $nonce,
		], admin_url( '/' ) ) );
	}

	/**
	 * Get the notice classes.
	 *
	 * @access public
	 * @return string
	 * @since 1.0
	 */
	public function get_classes() {
		$classes = [ 'wpnotice-wrapper notice', $this->app->id ];

		// Add the class for notice-type.
		$classes[] = $this->options['classes'];
		$classes[] = 'notice-' . $this->options['type'];
		$classes[] = 'notice-' . $this->app->id . '-' . $this->id;

		if ( $this->options['dismissible'] ) {
			$classes[] = 'is-dismissible';
		}

		// Combine classes to a string.
		return implode( ' ', $classes );
	}

	/**
	 * Determine if the notice should be shown or not.
	 *
	 * @access public
	 * @return bool
	 * @since 1.0
	 */
	public function show() {
		// External Condition Check
		if ( isset( $this->options['display_if'] ) && ! $this->options['display_if'] ) {
			return false;
		}
		// Don't show if the user doesn't have the required capability.
		if ( ! is_null( $this->options['capability'] ) && ! current_user_can( $this->options['capability'] ) ) {
			return false;
		}

		// Don't show if we're not on the right screen.
		if ( ! $this->is_screen() ) {
			return false;
		}

		// Don't show if notice has been dismissed.
		if ( $this->dismiss->is_dismissed() ) {
			return false;
		}

		// Start and Expiration Check.
		if ( $this->time() <= $this->options['start'] ) {
			return false;
		}

		if ( $this->is_expired() ) {
			if ( $this->options['recurrence'] ) {
				$_recurrence                        = intval( $this->options['recurrence'] );
				$this->queue[ $this->id ]['start']  = $this->strtotime( "+$_recurrence days" );
				$this->queue[ $this->id ]['expire'] = $this->strtotime( "+" . ( $_recurrence + 3 ) . " days" );
				$this->app->storage()->save( $this->queue );
			}

			return false;
		}

		return true;
	}

	/**
	 * Evaluate if we're on the right place depending on the "screens" argument.
	 *
	 * @access private
	 * @return bool
	 * @since 1.0
	 */
	private function is_screen() {
		// Make sure the get_current_screen function exists.
		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . 'wp-admin/includes/screen.php';
		}

		/** @var WP_Screen $current_screen */
		$current_screen = get_current_screen();

		if ( $current_screen->id === 'update' ) {
			return false;
		}

		// If screen is empty we want this shown on all screens.
		if ( empty( $this->options['screens'] ) ) {
			return true;
		}

		return ( in_array( $current_screen->id, $this->options['screens'], true ) );
	}

	public function is_expired() {
		if ( isset( $this->options['expire'] ) && $this->time() >= $this->options['expire'] ) {
			return true;
		}

		return false;
	}

	public function __call( $name, $args ) {
		if ( property_exists( $this, $name ) ) {
			return $this->{$name}[ $args[0] ];
		}

		return null;
	}

	public function get_thumbnail( $image ) {
		$output = '<div class="wpnotice-thumbnail-wrapper">';
		$output .= '<img style="max-width: 100%;" src="' . esc_url( $image ) . '">';
		$output .= '</div>';

		return wp_kses_post( $output );
	}
}
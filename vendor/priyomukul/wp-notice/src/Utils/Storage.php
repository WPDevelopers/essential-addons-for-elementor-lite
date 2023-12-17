<?php

namespace PriyoMukul\WPNotice\Utils;

use function property_exists;

#[\AllowDynamicProperties]
class Storage extends Base {
	private $id          = 'wpnotice';
	private $type        = 'options';
	private $version     = '1.1.0';
	private $storage_key = 'notices';

	public function __construct( $args ) {
		$this->id          = ! empty( $args['id'] ) ? $args['id'] : $this->id;
		$this->type        = ! empty( $args['store'] ) ? $args['store'] : $this->type;
		$this->storage_key = ! empty( $args['storage_key'] ) ? $this->id . '_' . $args['storage_key'] : "{$this->id}_{$this->storage_key}";
	}

	public function __get( $name ) {
		return property_exists( $this, $name ) ? $this->$name : null;
	}

	public function save( $value, $key = '' ) {
		if ( empty( $key ) ) {
			$key              = $this->storage_key;
			$value['version'] = $this->version;
		}

		if ( $this->type === 'options' ) {
			return update_site_option( $key, $value );
		}

		return false;
	}

	public function get( $key = '', $default = false ) {
		$key = empty( $key ) ? $this->storage_key : $key;

		if ( $this->type === 'options' ) {
			return get_site_option( $key, $default );
		}

		return $default;
	}

	public function save_meta( $id, $value = true ) {
		return update_user_meta( get_current_user_id(), "{$this->id}_{$id}_notice_dismissed", $value );
	}

	public function get_meta( $id ) {
		return boolval( get_user_meta( get_current_user_id(), "{$this->id}_{$id}_notice_dismissed", true ) );
	}

	public function remove_meta( $id ) {
		return delete_user_meta( get_current_user_id(), "{$this->id}_{$id}_notice_dismissed" );
	}
}
<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Stack;
use Elementor\Plugin;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XD_Copy {
	use \Essential_Addons_Elementor\Traits\Library;

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'wp_ajax_eael_xd_copy_fetch_content', array( $this, 'eael_xdcp_fetch_content_data' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_xd_copy_scripts' ) );
	}

	/**
	 * Enqueue cross domain copy paste script files
	 */
	public function enqueue_xd_copy_scripts() {
		wp_enqueue_script(
			'eael-xd-copy',
			$this->safe_url( EAEL_PLUGIN_URL . 'assets/front-end/js/edit/eael-xd-copy.min.js' ),
			[ 'jquery' ],
			time(),
			true
		);

		wp_localize_script(
			'eael-xd-copy',
			'eael_xd_copy',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'eael_xd_copy_fetch_content' ),
				'i18n'     => [
					'ea_copy'  => __( 'EA Copy', 'essential-addons-for-elementor-lite' ),
					'ea_paste' => __( 'EA Paste', 'essential-addons-for-elementor-lite' ),
					'section_message' => __( 'Section Copied! 📰', 'essential-addons-for-elementor-lite' ),
					'column_message' => __( 'Column Copied! 📰', 'essential-addons-for-elementor-lite' ),
					'widget_message' => __( 'Widget Copied! 📰', 'essential-addons-for-elementor-lite' ),
					'paste_message' => __( 'EA Copy Data Pasted! ✔️', 'essential-addons-for-elementor-lite' ),
				]
			]
		);
	}

	/**
	 * Ajax handler for media elements
	 */
	public static function eael_xdcp_fetch_content_data() {

		check_ajax_referer( 'eael_xd_copy_fetch_content', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error(
				__( 'Not a Valid User', 'essential-addons-for-elementor-lite' ),
				403
			);
		}

		$media_xdcp_data = isset( $_POST['xd_copy_data'] ) ? wp_unslash( $_POST['xd_copy_data'] ) : '';

		if ( empty( $media_xdcp_data ) ) {
			wp_send_json_error( __( 'Empty Content', 'essential-addons-for-elementor-lite' ) );
		}

		$media_xdcp_data = array( json_decode( $media_xdcp_data, true ) );
		$media_xdcp_data = self::eael_xdcp_replace_elements_ids( $media_xdcp_data );
		$media_xdcp_data = self::eael_xdcp_create_copy_content( $media_xdcp_data );

		wp_send_json_success( $media_xdcp_data );
	}

	/**
	 * Set random id of media elements
	 *
	 * @param $media_xdcp_data
	 *
	 * @return array|mixed
	 */
	protected static function eael_xdcp_replace_elements_ids( $media_xdcp_data ) {

		return Plugin::instance()->db->iterate_data(
			$media_xdcp_data,
			function ( $element ) {
				$element['id'] = Utils::generate_random_string();

				return $element;
			}
		);

	}

	/**
	 * Create element instance from copied content
	 *
	 * @param $media_xdcp_data
	 *
	 * @return array|mixed
	 */
	protected static function eael_xdcp_create_copy_content( $media_xdcp_data ) {

		return Plugin::instance()->db->iterate_data(
			$media_xdcp_data,
			function ( $element_data ) {
				$elements = Plugin::instance()->elements_manager->create_element_instance( $element_data );

				if ( ! $elements ) {
					return null;
				}

				return self::eael_xdcp_process_media( $elements );
			}
		);

	}

	/**
	 * @param Controls_Stack $element
	 * @param string $method
	 *
	 * @return array|mixed
	 */
	protected static function eael_xdcp_process_media( Controls_Stack $element, $method = 'on_import' ) {
		$get_element_instance = $element->get_data();

		if ( method_exists( $element, $method ) ) {
			$get_element_instance = $element->{$method}( $get_element_instance );
		}

		foreach ( $element->get_controls() as $get_control ) {
			$control_type = Plugin::instance()->controls_manager->get_control( $get_control['type'] );
			$control_name = $get_control['name'];

			if ( ! $control_type ) {
				return $get_element_instance;
			}

			if ( method_exists( $control_type, $method ) ) {
				$get_element_instance['settings'][ $control_name ] = $control_type->{$method}( $element->get_settings( $control_name ), $get_control );
			}
		}

		return $get_element_instance;
	}
}

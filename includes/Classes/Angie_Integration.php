<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;

/**
 * Angie (Elementor AI Assistant) integration.
 *
 * Registers a browser-side MCP server (via @elementor/angie-sdk) that lets
 * Angie discover Essential Addons widgets installed on the site and fetch
 * their control schemas, so the agent can build designs using EA widgets.
 *
 * Everything is gated on the Angie plugin being active — zero footprint
 * otherwise.
 *
 * @since 6.7.4
 */
class Angie_Integration {

	const NONCE_ACTION = 'eael_angie_integration';

	/**
	 * Category slug EA widgets register under.
	 */
	const EA_CATEGORY = 'essential-addons-elementor';

	public function __construct() {
		if ( ! defined( 'ANGIE_VERSION' ) ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'wp_ajax_eael_angie_widget_catalog', [ $this, 'ajax_widget_catalog' ] );
		add_action( 'wp_ajax_eael_angie_widget_schema', [ $this, 'ajax_widget_schema' ] );
	}

	/**
	 * Enqueue the MCP server bundle wherever the Angie sidebar can appear.
	 */
	public function enqueue_scripts() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		wp_enqueue_script(
			'eael-angie-integration',
			EAEL_PLUGIN_URL . 'assets/admin/js/angie-integration.min.js',
			[],
			EAEL_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'eael-angie-integration',
			'eaelAngie',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
				'version' => EAEL_PLUGIN_VERSION,
			]
		);
	}

	/**
	 * List active EA widgets: name, title, keywords, categories.
	 *
	 * Reads from Elementor's widgets manager so the list automatically
	 * respects the user's enabled/disabled element settings and includes
	 * Pro widgets when Pro is active.
	 */
	public function ajax_widget_catalog() {
		$this->verify_request();

		$widgets = [];

		foreach ( Plugin::$instance->widgets_manager->get_widget_types() as $widget ) {
			if ( ! in_array( self::EA_CATEGORY, (array) $widget->get_categories(), true ) ) {
				continue;
			}

			$widgets[] = [
				'name'       => $widget->get_name(),
				'title'      => $widget->get_title(),
				'keywords'   => (array) $widget->get_keywords(),
				'categories' => (array) $widget->get_categories(),
			];
		}

		wp_send_json_success( [ 'widgets' => $widgets ] );
	}

	/**
	 * Return the control schema for one EA widget so the agent can produce
	 * valid settings JSON.
	 *
	 * By default only Content-tab controls are returned (what the agent
	 * needs to compose a design); pass tab=all for everything.
	 */
	public function ajax_widget_schema() {
		$this->verify_request();

		$widget_name = isset( $_POST['widget'] ) ? sanitize_text_field( wp_unslash( $_POST['widget'] ) ) : '';
		$tab         = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : 'content';

		if ( '' === $widget_name ) {
			wp_send_json_error( [ 'message' => 'Missing widget name.' ], 400 );
		}

		$widget = Plugin::$instance->widgets_manager->get_widget_types( $widget_name );

		if ( ! $widget || ! in_array( self::EA_CATEGORY, (array) $widget->get_categories(), true ) ) {
			wp_send_json_error( [ 'message' => 'Unknown Essential Addons widget.' ], 404 );
		}

		$stack    = $widget->get_stack( false );
		$controls = isset( $stack['controls'] ) ? $stack['controls'] : [];

		wp_send_json_success(
			[
				'widget'   => $widget->get_name(),
				'title'    => $widget->get_title(),
				'controls' => $this->trim_controls( $controls, $tab ),
			]
		);
	}

	/**
	 * Strip render-only keys and (optionally) non-content tabs so the
	 * payload stays small enough for an agent context window.
	 *
	 * @param array  $controls Raw control stack.
	 * @param string $tab      'content' or 'all'.
	 *
	 * @return array
	 */
	protected function trim_controls( $controls, $tab ) {
		$strip_keys = [ 'selectors', 'selector', 'render_type', 'prefix_class', 'classes', 'style_transfer', 'separator' ];
		$trimmed    = [];

		foreach ( $controls as $key => $control ) {
			if ( 'all' !== $tab && isset( $control['tab'] ) && 'content' !== $control['tab'] ) {
				continue;
			}

			foreach ( $strip_keys as $strip_key ) {
				unset( $control[ $strip_key ] );
			}

			// Repeater fields carry their own nested control stacks.
			if ( isset( $control['fields'] ) && is_array( $control['fields'] ) ) {
				$control['fields'] = $this->trim_controls( $control['fields'], 'all' );
			}

			$trimmed[ $key ] = $control;
		}

		return $trimmed;
	}

	/**
	 * Nonce + capability gate shared by both AJAX handlers.
	 */
	protected function verify_request() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Insufficient permissions.' ], 403 );
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_send_json_error( [ 'message' => 'Elementor is not active.' ], 400 );
		}
	}
}

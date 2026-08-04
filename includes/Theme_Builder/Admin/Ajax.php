<?php
/**
 * Theme Builder AJAX endpoints.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Admin;

use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Conditions\Rules;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Cache;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Models\Template;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Handles the template creation and condition builder requests.
 *
 * Every endpoint is registered for logged-in users only, verifies the shared
 * nonce and re-checks the capability — templates control what every visitor of
 * the site sees, so none of these may be reachable by an unauthenticated caller.
 *
 * @since 6.7.3
 */
class Ajax {

	/**
	 * Nonce action shared by all Theme Builder endpoints.
	 */
	const NONCE_ACTION = 'eael_theme_builder';

	/**
	 * Register the endpoints.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		add_action( 'wp_ajax_eael_theme_builder_create_template', [ $this, 'create_template' ] );
		add_action( 'wp_ajax_eael_theme_builder_save_conditions', [ $this, 'save_conditions' ] );
		add_action( 'wp_ajax_eael_theme_builder_search_objects', [ $this, 'search_objects' ] );
		add_action( 'wp_ajax_eael_theme_builder_quick_edit', [ $this, 'quick_edit' ] );
	}

	/**
	 * Save the Quick Edit form and return the cells the row has to repaint.
	 *
	 * @since 6.7.3
	 */
	public function quick_edit() {
		$this->verify_request();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified in verify_request().
		$template_id   = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
		$title         = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$slug          = isset( $_POST['slug'] ) ? sanitize_title( wp_unslash( $_POST['slug'] ) ) : '';
		$password      = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';
		$is_private    = ! empty( $_POST['private'] ) && 'no' !== $_POST['private'];
		$page_template = isset( $_POST['page_template'] ) ? sanitize_text_field( wp_unslash( $_POST['page_template'] ) ) : 'default';
		$type          = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
		$status        = isset( $_POST['status'] ) ? sanitize_key( wp_unslash( $_POST['status'] ) ) : '';
		$priority      = isset( $_POST['priority'] ) ? absint( $_POST['priority'] ) : 10;
		$active        = ! empty( $_POST['active'] ) && 'no' !== $_POST['active'];

		$month  = isset( $_POST['mm'] ) ? absint( $_POST['mm'] ) : 0;
		$day    = isset( $_POST['jj'] ) ? absint( $_POST['jj'] ) : 0;
		$year   = isset( $_POST['aa'] ) ? absint( $_POST['aa'] ) : 0;
		$hour   = isset( $_POST['hh'] ) ? absint( $_POST['hh'] ) : 0;
		$minute = isset( $_POST['mn'] ) ? absint( $_POST['mn'] ) : 0;
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$template = Template::get( $template_id );

		if ( ! $template ) {
			wp_send_json_error( [ 'message' => __( 'This template no longer exists.', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( ! current_user_can( 'edit_post', $template_id ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to edit this template.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		if ( '' === trim( $title ) ) {
			wp_send_json_error( [ 'message' => __( 'Please give the template a name.', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( ! Template_Types::instance()->type_exists( $type ) ) {
			wp_send_json_error( [ 'message' => __( 'Please choose a valid template type.', 'essential-addons-for-elementor-lite' ) ] );
		}

		// Trashing is a row action, not something Quick Edit should do by accident.
		if ( ! in_array( $status, [ 'publish', 'draft', 'pending' ], true ) ) {
			$status = $template->get_status();
		}

		// Private is a checkbox next to the password field, exactly as in core:
		// the two are mutually exclusive.
		if ( $is_private ) {
			$status   = 'private';
			$password = '';
		} elseif ( 'private' === $status ) {
			$status = 'publish';
		}

		$post_data = [
			'ID'            => $template_id,
			'post_title'    => $title,
			'post_status'   => $status,
			'post_password' => $password,
		];

		if ( '' !== $slug ) {
			$post_data['post_name'] = $slug;
		}

		if ( $year && $month && $day ) {
			if ( ! wp_checkdate( $month, $day, $year, sprintf( '%04d-%02d-%02d', $year, $month, $day ) ) ) {
				wp_send_json_error( [ 'message' => __( 'Please enter a valid date.', 'essential-addons-for-elementor-lite' ) ] );
			}

			$post_data['post_date']     = sprintf( '%04d-%02d-%02d %02d:%02d:00', $year, $month, $day, min( $hour, 23 ), min( $minute, 59 ) );
			$post_data['post_date_gmt'] = get_gmt_from_date( $post_data['post_date'] );
			// Without this wp_update_post() silently keeps the original date.
			$post_data['edit_date']     = true;
		}

		$page_templates = Post_Type::get_page_template_options();

		if ( ! isset( $page_templates[ $page_template ] ) ) {
			$page_template = 'default';
		}

		update_post_meta( $template_id, '_wp_page_template', $page_template );

		$updated = wp_update_post( $post_data, true );

		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( [ 'message' => $updated->get_error_message() ] );
		}

		update_post_meta( $template_id, Post_Type::META_TYPE, $type );
		$template->set_priority( $priority );
		$template->set_active( $active );

		Template_Cache::flush();

		// Re-read so the response reflects what was actually stored.
		$template = Template::get( $template_id );
		$post     = $template->get_post();

		wp_send_json_success(
			[
				'id'         => $template->get_id(),
				'title'      => $template->get_title(),
				'states'     => Templates_List_Table::render_post_states( $template ),
				'type_label' => $template->get_type_label(),
				'date_html'  => Templates_List_Table::render_date( $post ),
				'inline'     => [
					'title'          => $template->get_title(),
					'slug'           => $post->post_name,
					'date'           => $post->post_date,
					'password'       => $post->post_password,
					'private'        => 'private' === $post->post_status ? 'yes' : 'no',
					'page-template'  => (string) get_post_meta( $template_id, '_wp_page_template', true ),
					'status'         => $template->get_status(),
					'type'           => $template->get_type(),
					'priority'       => $template->get_priority(),
					'active'         => $template->is_active() ? 'yes' : 'no',
				],
			]
		);
	}

	/**
	 * Create a template and return its editor URL.
	 *
	 * @since 6.7.3
	 */
	public function create_template() {
		$this->verify_request();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified in verify_request().
		$type  = isset( $_POST['template_type'] ) ? sanitize_key( wp_unslash( $_POST['template_type'] ) ) : '';
		$title = isset( $_POST['template_title'] ) ? sanitize_text_field( wp_unslash( $_POST['template_title'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$template = Template::create( $type, $title );

		if ( is_wp_error( $template ) ) {
			wp_send_json_error( [ 'message' => $template->get_error_message() ] );
		}

		Template_Cache::flush();

		wp_send_json_success(
			[
				'id'         => $template->get_id(),
				'type'       => $template->get_type(),
				'title'      => $template->get_title(),
				'conditions' => $this->decorate_conditions( $template->get_conditions() ),
				'edit_url'   => $template->get_edit_url(),
			]
		);
	}

	/**
	 * Save the display conditions of a template.
	 *
	 * @since 6.7.3
	 */
	public function save_conditions() {
		$this->verify_request();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified in verify_request().
		$template_id = isset( $_POST['template_id'] ) ? absint( $_POST['template_id'] ) : 0;
		$raw         = isset( $_POST['conditions'] ) ? wp_unslash( $_POST['conditions'] ) : [];
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$template = Template::get( $template_id );

		if ( ! $template ) {
			wp_send_json_error( [ 'message' => __( 'This template no longer exists.', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( ! current_user_can( 'edit_post', $template_id ) ) {
			wp_send_json_error( [ 'message' => __( 'You are not allowed to edit this template.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		$validated = Conditions_Manager::instance()->validate_conditions( is_array( $raw ) ? $raw : [] );

		if ( is_wp_error( $validated ) ) {
			wp_send_json_error( [ 'message' => $validated->get_error_message() ] );
		}

		$saved = $template->set_conditions( $validated );

		Template_Cache::flush();

		$conflicts = Conditions_Manager::instance()->find_conflicts( $template->get_type(), $saved, $template_id );

		wp_send_json_success(
			[
				'id'         => $template->get_id(),
				'conditions' => $this->decorate_conditions( $saved ),
				'summary'    => Conditions_Manager::instance()->get_conditions_summary( $saved ),
				'conflicts'  => wp_list_pluck( $conflicts, 'title' ),
				'edit_url'   => $template->get_edit_url(),
			]
		);
	}

	/**
	 * Search the objects a condition rule can be narrowed down to.
	 *
	 * @since 6.7.3
	 */
	public function search_objects() {
		$this->verify_request();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified in verify_request().
		$source = isset( $_POST['source'] ) ? sanitize_key( wp_unslash( $_POST['source'] ) ) : '';
		$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( ! $this->is_known_source( $source ) ) {
			wp_send_json_error( [ 'message' => __( 'Unknown object type.', 'essential-addons-for-elementor-lite' ) ] );
		}

		wp_send_json_success(
			[
				'results' => Conditions_Manager::instance()->search_objects( $source, $search ),
			]
		);
	}

	/**
	 * Whether a sub-object source is declared by at least one rule.
	 *
	 * Prevents the endpoint from being used as a generic query proxy for post
	 * types and taxonomies the condition builder never offers.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source Source slug.
	 *
	 * @return bool
	 */
	private function is_known_source( $source ) {
		return '' !== Rules::get_sub_source_type( $source );
	}

	/**
	 * Add the object labels the condition builder needs to rebuild its selects.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return array
	 */
	private function decorate_conditions( $conditions ) {
		$manager   = Conditions_Manager::instance();
		$decorated = [];

		foreach ( $conditions as $condition ) {
			$rule = Rules::get_rule( $condition['name'] );

			$condition['sub_label'] = ( $rule && ! empty( $rule['sub_source'] ) && $condition['sub_id'] )
				? $manager->get_object_label( $rule['sub_source'], $condition['sub_id'] )
				: '';

			$decorated[] = $condition;
		}

		return $decorated;
	}

	/**
	 * Verify the nonce and the capability, or end the request.
	 *
	 * @since 6.7.3
	 */
	private function verify_request() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		if ( ! current_user_can( Theme_Builder::capability() ) ) {
			wp_send_json_error(
				[ 'message' => __( 'You are not allowed to manage Theme Builder templates.', 'essential-addons-for-elementor-lite' ) ],
				403
			);
		}
	}
}

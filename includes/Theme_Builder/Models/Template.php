<?php
/**
 * Theme Builder template model.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Models;

use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Thin, typed wrapper around a `ea_theme_builder` post.
 *
 * Everything that reads or writes template metadata goes through this model so
 * the meta keys stay in one place and defaults are applied consistently.
 *
 * @since 6.7.3
 */
class Template {

	/**
	 * Underlying post.
	 *
	 * @var \WP_Post
	 */
	protected $post;

	/**
	 * Constructor.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $post Template post.
	 */
	public function __construct( \WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * Build a model from a post ID or object.
	 *
	 * @since 6.7.3
	 *
	 * @param int|\WP_Post $post Post ID or object.
	 *
	 * @return Template|null Null when the post is not a template.
	 */
	public static function get( $post ) {
		$post = get_post( $post );

		if ( ! $post instanceof \WP_Post || Post_Type::CPT !== $post->post_type ) {
			return null;
		}

		return new self( $post );
	}

	/**
	 * Create a new template.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type   Template type slug.
	 * @param string $title  Template title.
	 * @param array  $args   Optional overrides for wp_insert_post().
	 *
	 * @return Template|\WP_Error
	 */
	public static function create( $type, $title, $args = [] ) {
		$type = sanitize_key( $type );

		if ( ! Template_Types::instance()->type_exists( $type ) ) {
			return new \WP_Error(
				'eael_tb_invalid_type',
				__( 'Please choose a valid template type.', 'essential-addons-for-elementor-lite' )
			);
		}

		$title = trim( wp_strip_all_tags( (string) $title ) );
		$named = ( '' !== $title );

		if ( ! $named ) {
			// Placeholder only. The generated name carries the template ID, which
			// `wp_insert_post()` has not handed out yet — and it refuses a post with
			// no title, no content and no excerpt, so it cannot be left empty here.
			$title = Template_Types::instance()->get_label( $type );
		}

		$defaults = [
			'post_title'  => $title,
			'post_type'   => Post_Type::CPT,
			'post_status' => 'draft',
			'post_author' => get_current_user_id(),
		];

		$post_id = wp_insert_post( wp_parse_args( $args, $defaults ), true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		if ( ! $named ) {
			wp_update_post(
				[
					'ID'         => $post_id,
					'post_title' => self::generate_title( $type, $post_id ),
				]
			);
		}

		foreach ( Post_Type::default_meta( $type ) as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Elementor resolves the document class from `_elementor_template_type`,
		// and skips its "Edit with Elementor" landing screen when the edit mode is
		// already `builder` — so the template opens straight into the editor.
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_template_type', Post_Type::DOCUMENT_TYPE );

		// Start on Elementor's blank canvas: a header or footer is a fragment, so
		// previewing it inside the theme's single template is misleading. The user
		// can change this from Page Settings → Page Layout at any time.
		update_post_meta( $post_id, '_wp_page_template', Post_Type::PAGE_TEMPLATE_CANVAS );

		$template = self::get( $post_id );

		/**
		 * Fires after a Theme Builder template is created.
		 *
		 * @since 6.7.3
		 *
		 * @param Template $template The new template.
		 */
		do_action( 'eael/theme_builder/template_created', $template );

		return $template;
	}

	/**
	 * Name for a template the user did not name themselves.
	 *
	 * Reads as "Header Template #205 (by EA)": the type says what it is, the ID
	 * keeps two unnamed templates of the same type apart, and the suffix marks it
	 * as ours in a media library or an export that mixes plugins.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type    Template type slug.
	 * @param int    $post_id Template ID.
	 *
	 * @return string
	 */
	public static function generate_title( $type, $post_id ) {
		$title = sprintf(
			/* translators: 1: template type label, e.g. Header. 2: template ID. */
			__( '%1$s Template #%2$d (by EA)', 'essential-addons-for-elementor-lite' ),
			Template_Types::instance()->get_label( $type ),
			(int) $post_id
		);

		/**
		 * Filters the name given to a template that was created without one.
		 *
		 * @since 6.7.3
		 *
		 * @param string $title   Generated title.
		 * @param string $type    Template type slug.
		 * @param int    $post_id Template ID.
		 */
		return (string) apply_filters( 'eael/theme_builder/auto_template_title', $title, $type, (int) $post_id );
	}

	/**
	 * Copy this template, including its Elementor layout and every meta value.
	 *
	 * The copy is always a draft, so duplicating a live header never puts a
	 * second one on the site by accident.
	 *
	 * @since 6.7.3
	 *
	 * @return Template|\WP_Error
	 */
	public function duplicate() {
		$new_id = wp_insert_post(
			[
				/* translators: %s: template title. */
				'post_title'   => sprintf( __( '%s (copy)', 'essential-addons-for-elementor-lite' ), $this->get_title() ),
				'post_type'    => Post_Type::CPT,
				'post_status'  => 'draft',
				'post_author'  => get_current_user_id(),
				'post_content' => $this->post->post_content,
			],
			true
		);

		if ( is_wp_error( $new_id ) ) {
			return $new_id;
		}

		// Regenerated per post, and copying them would point the new template at
		// the original's cached CSS file and asset manifest.
		$skip = [
			'_edit_lock',
			'_edit_last',
			'_elementor_css',
			'_elementor_page_assets',
			'_eael_widget_elements',
		];

		foreach ( get_post_meta( $this->get_id() ) as $key => $values ) {
			if ( in_array( $key, $skip, true ) ) {
				continue;
			}

			foreach ( $values as $value ) {
				// add_metadata() unslashes what it is given, so slash first to keep
				// `_elementor_data`'s JSON byte-for-byte identical.
				add_post_meta( $new_id, $key, wp_slash( maybe_unserialize( $value ) ) );
			}
		}

		$duplicate = self::get( $new_id );

		/**
		 * Fires after a Theme Builder template is duplicated.
		 *
		 * @since 6.7.3
		 *
		 * @param Template $duplicate The new copy.
		 * @param Template $original  The template it was copied from.
		 */
		do_action( 'eael/theme_builder/template_duplicated', $duplicate, $this );

		return $duplicate;
	}

	/**
	 * Template ID.
	 *
	 * @since 6.7.3
	 *
	 * @return int
	 */
	public function get_id() {
		return (int) $this->post->ID;
	}

	/**
	 * Underlying post object.
	 *
	 * @since 6.7.3
	 *
	 * @return \WP_Post
	 */
	public function get_post() {
		return $this->post;
	}

	/**
	 * Template title.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->post->post_title;
	}

	/**
	 * Post status.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->post->post_status;
	}

	/**
	 * Template type slug.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_type() {
		return (string) get_post_meta( $this->get_id(), Post_Type::META_TYPE, true );
	}

	/**
	 * Human readable type label.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_type_label() {
		return Template_Types::instance()->get_label( $this->get_type() );
	}

	/**
	 * Builder platform that owns the template.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_platform() {
		$platform = get_post_meta( $this->get_id(), Post_Type::META_PLATFORM, true );

		return $platform ? (string) $platform : 'elementor';
	}

	/**
	 * Display conditions.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function get_conditions() {
		$conditions = get_post_meta( $this->get_id(), Post_Type::META_CONDITIONS, true );

		if ( ! is_array( $conditions ) ) {
			return [];
		}

		return Conditions_Manager::instance()->sanitize_conditions( $conditions );
	}

	/**
	 * Persist display conditions.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Raw condition rows.
	 *
	 * @return array The sanitized conditions that were stored.
	 */
	public function set_conditions( $conditions ) {
		$conditions = Conditions_Manager::instance()->sanitize_conditions( $conditions );

		update_post_meta( $this->get_id(), Post_Type::META_CONDITIONS, $conditions );

		return $conditions;
	}

	/**
	 * Matching priority — lower wins when two templates are equally specific.
	 *
	 * @since 6.7.3
	 *
	 * @return int
	 */
	public function get_priority() {
		$priority = get_post_meta( $this->get_id(), Post_Type::META_PRIORITY, true );

		return is_numeric( $priority ) ? (int) $priority : Post_Type::PRIORITY_DEFAULT;
	}

	/**
	 * Set the matching priority.
	 *
	 * Out-of-range values are clamped rather than refused — callers that need to
	 * reject them (the Quick Edit endpoint) validate before calling this.
	 *
	 * @since 6.7.3
	 *
	 * @param int $priority Priority between `Post_Type::PRIORITY_MIN` and `Post_Type::PRIORITY_MAX`.
	 */
	public function set_priority( $priority ) {
		$priority = max( Post_Type::PRIORITY_MIN, min( Post_Type::PRIORITY_MAX, (int) $priority ) );

		update_post_meta( $this->get_id(), Post_Type::META_PRIORITY, $priority );
	}

	/**
	 * Whether the template takes part in condition matching.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public function is_active() {
		return 'no' !== get_post_meta( $this->get_id(), Post_Type::META_ACTIVE, true );
	}

	/**
	 * Toggle the active flag.
	 *
	 * @since 6.7.3
	 *
	 * @param bool $active Whether the template should be active.
	 */
	public function set_active( $active ) {
		update_post_meta( $this->get_id(), Post_Type::META_ACTIVE, $active ? 'yes' : 'no' );
	}

	/**
	 * Whether the template is live — published and active.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public function is_live() {
		return 'publish' === $this->get_status() && $this->is_active();
	}

	/**
	 * Elementor edit URL.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_edit_url() {
		return Post_Type::get_elementor_edit_url( $this->get_id() );
	}

	/**
	 * Array representation used by the admin UI and the AJAX endpoints.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function to_array() {
		return [
			'id'         => $this->get_id(),
			'title'      => $this->get_title(),
			'type'       => $this->get_type(),
			'type_label' => $this->get_type_label(),
			'platform'   => $this->get_platform(),
			'status'     => $this->get_status(),
			'priority'   => $this->get_priority(),
			'active'     => $this->is_active(),
			'conditions' => $this->get_conditions(),
			'edit_url'   => $this->get_edit_url(),
		];
	}
}

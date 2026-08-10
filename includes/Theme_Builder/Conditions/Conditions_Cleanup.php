<?php
/**
 * Removal of condition rows whose target no longer exists.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Conditions;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Keeps display conditions in step with the objects they point at.
 *
 * A condition narrowed down to one object — "Page: Contact", "Category: News",
 * "Author: Jane" — stores that object's ID. Delete the object and the row is
 * stranded: it can never match again, and the templates list can no longer name
 * what it used to target. WordPress core solves the identical problem for nav
 * menu items by deleting them along with their target; this does the same for
 * condition rows.
 *
 * Deactivation is the deliberate part. Dropping a template's last include row
 * would leave a published template that matches nothing, which is
 * indistinguishable from a broken one — so the template is flagged inactive and
 * the dashboard says why.
 *
 * @since 6.7.3
 */
class Conditions_Cleanup {

	/**
	 * Option holding the IDs of templates deactivated by a cleanup run.
	 *
	 * Read and cleared by the dashboard, which turns it into a notice.
	 */
	const DEACTIVATED_OPTION = 'eael_tb_deactivated_templates';

	/**
	 * Register the cleanup hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		add_action( 'deleted_post', [ $this, 'on_deleted_post' ], 10, 2 );
		add_action( 'delete_term', [ $this, 'on_deleted_term' ], 10, 3 );
		add_action( 'deleted_user', [ $this, 'on_deleted_user' ] );
	}

	/**
	 * Drop conditions targeting a permanently deleted post.
	 *
	 * Post IDs are unique across every post type, so the type is only needed to
	 * skip the module's own templates — matching on the ID alone is safe.
	 *
	 * @since 6.7.3
	 *
	 * @param int           $post_id Deleted post ID.
	 * @param \WP_Post|null $post    Deleted post object. Passed since WP 5.5.
	 */
	public function on_deleted_post( $post_id, $post = null ) {
		if ( $post instanceof \WP_Post && Post_Type::CPT === $post->post_type ) {
			return;
		}

		$this->purge( Rules::SOURCE_POST_TYPE, '', $post_id );
	}

	/**
	 * Drop conditions targeting a deleted term.
	 *
	 * @since 6.7.3
	 *
	 * @param int    $term_id  Deleted term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy the term belonged to.
	 */
	public function on_deleted_term( $term_id, $tt_id, $taxonomy ) {
		$this->purge( Rules::SOURCE_TAXONOMY, $taxonomy, $term_id );
	}

	/**
	 * Drop conditions targeting a deleted user.
	 *
	 * @since 6.7.3
	 *
	 * @param int $user_id Deleted user ID.
	 */
	public function on_deleted_user( $user_id ) {
		$this->purge( Rules::SOURCE_USER, '', $user_id );
	}

	/**
	 * Remove every condition row pointing at one deleted object.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source_type One of the `Rules::SOURCE_*` constants.
	 * @param string $source      Sub-source slug, or an empty string to match every
	 *                            sub-source of that type.
	 * @param int    $object_id   ID of the deleted object.
	 */
	private function purge( $source_type, $source, $object_id ) {
		$object_id = absint( $object_id );

		if ( ! $object_id ) {
			return;
		}

		$rule_names = $this->get_rule_names( $source_type, $source );

		if ( empty( $rule_names ) ) {
			return;
		}

		$deactivated = [];
		$changed     = false;

		foreach ( $this->get_template_ids() as $template_id ) {
			$conditions = get_post_meta( $template_id, Post_Type::META_CONDITIONS, true );

			if ( ! is_array( $conditions ) || empty( $conditions ) ) {
				continue;
			}

			$kept = [];

			foreach ( $conditions as $condition ) {
				if ( ! is_array( $condition ) ) {
					continue;
				}

				$name   = isset( $condition['name'] ) ? $condition['name'] : '';
				$sub_id = isset( $condition['sub_id'] ) ? absint( $condition['sub_id'] ) : 0;

				if ( $sub_id === $object_id && isset( $rule_names[ $name ] ) ) {
					continue;
				}

				$kept[] = $condition;
			}

			if ( count( $kept ) === count( $conditions ) ) {
				continue;
			}

			$changed = true;

			update_post_meta( $template_id, Post_Type::META_CONDITIONS, $kept );

			if ( $this->has_include_row( $kept ) ) {
				continue;
			}

			// Nothing left that could ever match: stop the template from taking
			// part in matching, and remember to say so on the dashboard.
			update_post_meta( $template_id, Post_Type::META_ACTIVE, 'no' );

			$deactivated[] = $template_id;

			/**
			 * Fires when a template is deactivated because its last include
			 * condition pointed at a deleted object.
			 *
			 * @since 6.7.3
			 *
			 * @param int $template_id Template ID.
			 */
			do_action( 'eael/theme_builder/template_orphaned', $template_id );
		}

		if ( $deactivated ) {
			$this->remember_deactivated( $deactivated );
		}

		if ( $changed ) {
			Template_Cache::flush();
		}
	}

	/**
	 * Names of the rules that target a given kind of object.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source_type One of the `Rules::SOURCE_*` constants.
	 * @param string $source      Sub-source slug, or an empty string for all of them.
	 *
	 * @return array Rule names as keys.
	 */
	private function get_rule_names( $source_type, $source ) {
		$names = [];

		foreach ( Rules::get_rules() as $name => $rule ) {
			if ( empty( $rule['sub_source'] ) || $rule['sub_source_type'] !== $source_type ) {
				continue;
			}

			if ( '' !== $source && $rule['sub_source'] !== $source ) {
				continue;
			}

			$names[ $name ] = true;
		}

		return $names;
	}

	/**
	 * Whether a condition set still has an include row.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Condition rows.
	 *
	 * @return bool
	 */
	private function has_include_row( $conditions ) {
		foreach ( $conditions as $condition ) {
			if ( ! isset( $condition['type'] ) || 'include' === $condition['type'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Every template that could hold a condition, whatever its status.
	 *
	 * Drafts and trashed templates are included on purpose: a stale row left in
	 * one of those would come back to life the moment it is published. The status
	 * list is spelled out because `any` drops trashed posts.
	 *
	 * @since 6.7.3
	 *
	 * @return array Template IDs.
	 */
	private function get_template_ids() {
		$query = new \WP_Query(
			[
				'post_type'              => Post_Type::CPT,
				'post_status'            => [ 'publish', 'draft', 'pending', 'future', 'private', 'trash' ],
				'posts_per_page'         => 500,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'suppress_filters'       => true,
			]
		);

		return $query->posts;
	}

	/**
	 * Queue deactivated templates for the dashboard notice.
	 *
	 * @since 6.7.3
	 *
	 * @param array $ids Template IDs.
	 */
	private function remember_deactivated( $ids ) {
		$stored = get_option( self::DEACTIVATED_OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		update_option( self::DEACTIVATED_OPTION, array_values( array_unique( array_merge( $stored, $ids ) ) ), false );
	}

	/**
	 * Templates deactivated since the notice was last shown, then forget them.
	 *
	 * @since 6.7.3
	 *
	 * @return array Template IDs.
	 */
	public static function pull_deactivated() {
		$stored = get_option( self::DEACTIVATED_OPTION, [] );

		if ( empty( $stored ) || ! is_array( $stored ) ) {
			return [];
		}

		delete_option( self::DEACTIVATED_OPTION );

		return array_map( 'absint', $stored );
	}
}

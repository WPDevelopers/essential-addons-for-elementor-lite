<?php
/**
 * Display condition engine.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Conditions;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Cache;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Integrations\Compatibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Resolves which template of a given type should render for the current request.
 *
 * Matching rules:
 *
 * 1. A template matches when at least one of its `include` rows matches and none
 *    of its `exclude` rows do.
 * 2. Of all matching templates, the one whose winning `include` row is the most
 *    specific wins ("Page: Contact" > "Singular" > "Entire Site").
 * 3. Equal specificity is broken by the `_ea_template_priority` meta (lower
 *    first), then by the most recently modified template.
 *
 * @since 6.7.3
 */
class Conditions_Manager {

	/**
	 * Singleton instance.
	 *
	 * @var Conditions_Manager|null
	 */
	private static $instance = null;

	/**
	 * Singleton accessor.
	 *
	 * @since 6.7.3
	 *
	 * @return Conditions_Manager
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Normalize raw condition rows into the stored shape.
	 *
	 * Unknown rules, unsupported include/exclude combinations and duplicates are
	 * dropped rather than stored — the meta is read on every front-end request,
	 * so it must never contain anything the engine cannot evaluate.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $conditions Raw rows.
	 *
	 * @return array
	 */
	public function sanitize_conditions( $conditions ) {
		if ( ! is_array( $conditions ) ) {
			return [];
		}

		$sanitized = [];
		$seen      = [];

		foreach ( $conditions as $condition ) {
			if ( ! is_array( $condition ) ) {
				continue;
			}

			$type   = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : 'include';
			$name   = isset( $condition['name'] ) ? sanitize_key( $condition['name'] ) : '';
			$sub_id = isset( $condition['sub_id'] ) ? absint( $condition['sub_id'] ) : 0;

			if ( ! in_array( $type, [ 'include', 'exclude' ], true ) ) {
				$type = 'include';
			}

			if ( ! Rules::is_valid( $name, $type ) ) {
				continue;
			}

			$rule = Rules::get_rule( $name );

			// A sub-object ID is only meaningful for rules that declare a source.
			if ( empty( $rule['sub_source'] ) ) {
				$sub_id = 0;
			}

			$key = $type . '|' . $name . '|' . $sub_id;

			if ( isset( $seen[ $key ] ) ) {
				continue;
			}

			$seen[ $key ] = true;

			$sanitized[] = [
				'type'   => $type,
				'name'   => $name,
				'sub_id' => $sub_id,
			];
		}

		return $sanitized;
	}

	/**
	 * Validate raw condition rows submitted from the condition builder.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $conditions Raw rows.
	 *
	 * @return array|\WP_Error Sanitized rows, or an error explaining the problem.
	 */
	public function validate_conditions( $conditions ) {
		if ( ! is_array( $conditions ) || empty( $conditions ) ) {
			return new \WP_Error(
				'eael_tb_no_conditions',
				__( 'Add at least one display condition so we know where to show this template.', 'essential-addons-for-elementor-lite' )
			);
		}

		foreach ( $conditions as $condition ) {
			$name = isset( $condition['name'] ) ? sanitize_key( $condition['name'] ) : '';
			$type = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : 'include';

			if ( '' === $name ) {
				return new \WP_Error(
					'eael_tb_empty_rule',
					__( 'Please choose a display rule for every condition.', 'essential-addons-for-elementor-lite' )
				);
			}

			if ( ! Rules::is_valid( $name, $type ) ) {
				return new \WP_Error(
					'eael_tb_invalid_rule',
					__( 'One of the selected display conditions is not available. Please review your conditions.', 'essential-addons-for-elementor-lite' )
				);
			}
		}

		$sanitized = $this->sanitize_conditions( $conditions );

		$has_include = false;

		foreach ( $sanitized as $condition ) {
			if ( 'include' === $condition['type'] ) {
				$has_include = true;
				break;
			}
		}

		if ( ! $has_include ) {
			return new \WP_Error(
				'eael_tb_no_include',
				__( 'At least one condition must be an "Include" rule, otherwise the template can never be displayed.', 'essential-addons-for-elementor-lite' )
			);
		}

		return $sanitized;
	}

	/**
	 * Templates of a type that are eligible for matching.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return array List of `[ id, priority, conditions, modified ]` rows.
	 */
	public function get_templates_by_type( $type ) {
		$type = sanitize_key( $type );

		if ( '' === $type ) {
			return [];
		}

		$cache_key = 'templates_' . $type;
		$templates = Template_Cache::get( $cache_key );

		if ( false !== $templates && is_array( $templates ) ) {
			return $templates;
		}

		$query = new \WP_Query(
			[
				'post_type'              => Post_Type::CPT,
				'post_status'            => 'publish',
				'posts_per_page'         => 200,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'meta_query'             => [
					[
						'key'   => Post_Type::META_TYPE,
						'value' => $type,
					],
				],
			]
		);

		$templates = [];

		foreach ( $query->posts as $post_id ) {
			if ( 'no' === get_post_meta( $post_id, Post_Type::META_ACTIVE, true ) ) {
				continue;
			}

			$conditions = get_post_meta( $post_id, Post_Type::META_CONDITIONS, true );

			if ( ! is_array( $conditions ) || empty( $conditions ) ) {
				continue;
			}

			$priority = get_post_meta( $post_id, Post_Type::META_PRIORITY, true );

			$templates[] = [
				'id'         => (int) $post_id,
				'priority'   => is_numeric( $priority ) ? (int) $priority : 10,
				'conditions' => $this->sanitize_conditions( $conditions ),
			];
		}

		Template_Cache::set( $cache_key, $templates );

		return $templates;
	}

	/**
	 * Score a condition set against the current request.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return int|false Specificity of the winning include row, or false when the
	 *                   set does not match the current request.
	 */
	public function match_conditions( $conditions ) {
		$score = false;

		foreach ( $conditions as $condition ) {
			if ( ! Rules::check( $condition['name'], $condition['sub_id'] ) ) {
				continue;
			}

			// A single matching exclude row vetoes the whole template.
			if ( 'exclude' === $condition['type'] ) {
				return false;
			}

			$specificity = Rules::get_specificity( $condition['name'], $condition['sub_id'] );

			if ( false === $score || $specificity > $score ) {
				$score = $specificity;
			}
		}

		return $score;
	}

	/**
	 * ID of the template that should render for the current request.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return int Template ID, or 0 when nothing matches.
	 */
	public function get_active_template_id( $type ) {
		$type = sanitize_key( $type );

		$cached = Template_Cache::recall( 'active_' . $type );

		if ( null !== $cached ) {
			return $cached;
		}

		$best_id    = 0;
		$best_score = false;
		$best_prio  = PHP_INT_MAX;

		foreach ( $this->get_templates_by_type( $type ) as $template ) {
			$score = $this->match_conditions( $template['conditions'] );

			if ( false === $score ) {
				continue;
			}

			if ( false === $best_score || $score > $best_score ) {
				$best_id    = $template['id'];
				$best_score = $score;
				$best_prio  = $template['priority'];
				continue;
			}

			// Same specificity: the lower priority number wins. The query is
			// ordered by modification date, so the first template to claim a
			// priority also wins ties on it.
			if ( $score === $best_score && $template['priority'] < $best_prio ) {
				$best_id   = $template['id'];
				$best_prio = $template['priority'];
			}
		}

		if ( $best_id ) {
			$best_id = Compatibility::translate_template_id( $best_id );
		}

		/**
		 * Filters the template chosen for the current request.
		 *
		 * @since 6.7.3
		 *
		 * @param int    $best_id Template ID, or 0 when nothing matched.
		 * @param string $type    Template type slug.
		 */
		$best_id = (int) apply_filters( 'eael/theme_builder/active_template_id', $best_id, $type );

		return Template_Cache::remember( 'active_' . $type, $best_id );
	}

	/**
	 * Other templates of the same type that claim one of the given include rules.
	 *
	 * Surfaced as a non-blocking warning in the condition builder so users can
	 * spot two headers fighting over the same pages before they publish.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type        Template type slug.
	 * @param array  $conditions  Sanitized condition rows.
	 * @param int    $exclude_id  Template being edited.
	 *
	 * @return array List of `[ id, title ]` rows.
	 */
	public function find_conflicts( $type, $conditions, $exclude_id = 0 ) {
		$claimed = [];

		foreach ( $conditions as $condition ) {
			if ( 'include' === $condition['type'] ) {
				$claimed[ $condition['name'] . '|' . $condition['sub_id'] ] = true;
			}
		}

		if ( empty( $claimed ) ) {
			return [];
		}

		$conflicts  = [];
		$exclude_id = absint( $exclude_id );

		foreach ( $this->get_templates_by_type( $type ) as $template ) {
			if ( $template['id'] === $exclude_id ) {
				continue;
			}

			foreach ( $template['conditions'] as $condition ) {
				if ( 'include' !== $condition['type'] ) {
					continue;
				}

				if ( isset( $claimed[ $condition['name'] . '|' . $condition['sub_id'] ] ) ) {
					$conflicts[ $template['id'] ] = [
						'id'    => $template['id'],
						'title' => get_the_title( $template['id'] ),
					];
					break;
				}
			}
		}

		return array_values( $conflicts );
	}

	/**
	 * Human readable summary of a condition set, for the templates list.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return string
	 */
	public function get_conditions_summary( $conditions ) {
		if ( empty( $conditions ) ) {
			return __( 'No conditions set', 'essential-addons-for-elementor-lite' );
		}

		$parts = [];

		foreach ( $conditions as $condition ) {
			$rule = Rules::get_rule( $condition['name'] );

			if ( ! $rule ) {
				continue;
			}

			$label = $rule['label'];

			if ( $condition['sub_id'] && ! empty( $rule['sub_source'] ) ) {
				$object = $this->get_object_label( $rule['sub_source'], $condition['sub_id'] );

				if ( $object ) {
					/* translators: 1: rule label, 2: object title. */
					$label = sprintf( _x( '%1$s: %2$s', 'theme builder condition', 'essential-addons-for-elementor-lite' ), $label, $object );
				}
			}

			if ( 'exclude' === $condition['type'] ) {
				/* translators: %s: condition label. */
				$label = sprintf( __( 'Except %s', 'essential-addons-for-elementor-lite' ), $label );
			}

			$parts[] = $label;
		}

		return implode( ', ', $parts );
	}

	/**
	 * Title of a sub-object referenced by a condition.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source Object source.
	 * @param int    $id     Object ID.
	 *
	 * @return string Empty string when the object no longer exists.
	 */
	public function get_object_label( $source, $id ) {
		$id = absint( $id );

		if ( ! $id ) {
			return '';
		}

		// The registry knows whether the source is a post type, a taxonomy or a
		// user lookup, so this stays correct for dynamically registered types.
		switch ( Rules::get_sub_source_type( $source ) ) {
			case Rules::SOURCE_TAXONOMY:
				$term = get_term( $id, $source );

				return ( $term && ! is_wp_error( $term ) ) ? $term->name : '';

			case Rules::SOURCE_USER:
				$user = get_userdata( $id );

				return $user ? $user->display_name : '';

			default:
				$post = get_post( $id );

				return $post ? $post->post_title : '';
		}
	}

	/**
	 * Search selectable objects for a rule's sub-selector.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source Object source.
	 * @param string $search Search term.
	 * @param int    $limit  Maximum number of results.
	 *
	 * @return array List of `[ id, text ]` rows.
	 */
	public function search_objects( $source, $search = '', $limit = 30 ) {
		$source  = sanitize_key( $source );
		$search  = sanitize_text_field( $search );
		$limit   = max( 1, min( 100, (int) $limit ) );
		$results = [];

		switch ( Rules::get_sub_source_type( $source ) ) {
			case Rules::SOURCE_TAXONOMY:
				$terms = get_terms(
					[
						'taxonomy'   => $source,
						'search'     => $search,
						'number'     => $limit,
						'hide_empty' => false,
					]
				);

				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$results[] = [
							'id'   => (int) $term->term_id,
							'text' => $term->name,
						];
					}
				}
				break;

			case Rules::SOURCE_USER:
				$users = get_users(
					[
						'search'         => $search ? '*' . $search . '*' : '',
						'number'         => $limit,
						'fields'         => [ 'ID', 'display_name' ],
						'search_columns' => [ 'user_login', 'user_nicename', 'display_name' ],
					]
				);

				foreach ( $users as $user ) {
					$results[] = [
						'id'   => (int) $user->ID,
						'text' => $user->display_name,
					];
				}
				break;

			default:
				// Only ever query a post type a rule actually declared, so this
				// can never be steered into an arbitrary type.
				if ( Rules::SOURCE_POST_TYPE !== Rules::get_sub_source_type( $source ) || ! post_type_exists( $source ) ) {
					break;
				}

				$post_type = $source;

				$posts = get_posts(
					[
						'post_type'           => $post_type,
						'post_status'         => [ 'publish', 'private' ],
						's'                   => $search,
						'posts_per_page'      => $limit,
						'orderby'             => 'title',
						'order'               => 'ASC',
						'suppress_filters'    => false,
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
					]
				);

				foreach ( $posts as $post ) {
					$results[] = [
						'id'   => (int) $post->ID,
						'text' => $post->post_title ? $post->post_title : __( '(no title)', 'essential-addons-for-elementor-lite' ),
					];
				}
				break;
		}

		return $results;
	}

	/**
	 * Whether a type slug is a registered template type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Type slug.
	 *
	 * @return bool
	 */
	public function is_valid_type( $type ) {
		return Template_Types::instance()->type_exists( $type );
	}
}

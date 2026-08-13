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
 *    first), then by the highest post ID — i.e. the most recently *created*
 *    template. The ID is used rather than the modification date so that
 *    re-saving the losing template cannot silently flip the winner.
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
			$row = $this->sanitize_condition( $condition );

			if ( ! $row ) {
				continue;
			}

			$key = implode( '|', $row );

			if ( isset( $seen[ $key ] ) ) {
				continue;
			}

			$seen[ $key ] = true;

			$sanitized[] = $row;
		}

		return $sanitized;
	}

	/**
	 * Normalize one row, translating the pre-6.7.3 flat shape on the way.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $condition Raw row.
	 *
	 * @return array|null The row, or null when it cannot be evaluated.
	 */
	protected function sanitize_condition( $condition ) {
		if ( ! is_array( $condition ) ) {
			return null;
		}

		$type     = isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : 'include';
		$name     = isset( $condition['name'] ) ? sanitize_key( $condition['name'] ) : '';
		$sub_name = isset( $condition['sub_name'] ) ? sanitize_key( $condition['sub_name'] ) : '';
		$sub_id   = isset( $condition['sub_id'] ) ? absint( $condition['sub_id'] ) : 0;

		if ( ! in_array( $type, [ 'include', 'exclude' ], true ) ) {
			$type = 'include';
		}

		// A row saved before the builder moved to Elementor's model names a rule
		// where a top level condition belongs. Translate rather than drop it: the
		// meta is what decides where a live header renders.
		if ( '' === $sub_name && ! Rules::is_top_level( $name ) ) {
			$legacy = Rules::map_legacy_rule( $name );

			if ( ! $legacy ) {
				return null;
			}

			list( $name, $sub_name ) = $legacy;
		}

		if ( ! Rules::is_valid_pair( $name, $sub_name ) ) {
			return null;
		}

		// An object ID is only meaningful for a sub-condition that offers a picker.
		if ( '' === $sub_name || ! Rules::get_source( $sub_name ) ) {
			$sub_id = 0;
		}

		return [
			'type'     => $type,
			'name'     => $name,
			'sub_name' => $sub_name,
			'sub_id'   => $sub_id,
		];
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

			if ( '' === $name ) {
				return new \WP_Error(
					'eael_tb_empty_rule',
					__( 'Please choose a display rule for every condition.', 'essential-addons-for-elementor-lite' )
				);
			}

			if ( ! $this->sanitize_condition( $condition ) ) {
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
	 * Whether one row matches the current request.
	 *
	 * Two checks, in Elementor's order: the top level first — it is cheap and
	 * rules out most rows — and the sub-condition only if that passed.
	 *
	 * @since 6.7.3
	 *
	 * @param array $condition Sanitized row.
	 *
	 * @return bool
	 */
	public function check_condition( $condition ) {
		if ( ! Rules::check( $condition['name'] ) ) {
			return false;
		}

		if ( '' === (string) $condition['sub_name'] ) {
			return true;
		}

		return Rules::check( $condition['sub_name'], $condition['sub_id'] );
	}

	/**
	 * Score a condition set against the current request.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return int|false Priority of the narrowest matching include row — **lower
	 *                   is narrower** — or false when the set does not match.
	 */
	public function match_conditions( $conditions ) {
		$score = false;

		foreach ( $conditions as $condition ) {
			if ( ! $this->check_condition( $condition ) ) {
				continue;
			}

			// A single matching exclude row vetoes the whole template.
			if ( 'exclude' === $condition['type'] ) {
				return false;
			}

			$priority = Rules::get_priority( $condition['name'], $condition['sub_name'], $condition['sub_id'] );

			if ( false === $score || $priority < $score ) {
				$score = $priority;
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

			// The narrowest condition wins, and narrower means a *lower* score —
			// the scale is Elementor's, where "Post: Hello World" is 20 and
			// "Entire Site" is 100.
			if ( false === $best_score || $score < $best_score ) {
				$best_id    = $template['id'];
				$best_score = $score;
				$best_prio  = $template['priority'];
				continue;
			}

			if ( $score !== $best_score ) {
				continue;
			}

			// Equally specific: the lower template priority number wins.
			if ( $template['priority'] < $best_prio ) {
				$best_id   = $template['id'];
				$best_prio = $template['priority'];
				continue;
			}

			// Same specificity *and* same priority. Leaving this case to fall
			// through would hand the tie to whichever row the query happened to
			// return first — which is `orderby => modified`, so the winner would
			// flip every time the loser was re-saved with nothing about it
			// actually changed. The post ID is the one key that never moves, so
			// the tie goes to the template created last and stays there.
			if ( $template['priority'] === $best_prio && $template['id'] > $best_id ) {
				$best_id = $template['id'];
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
				$claimed[ $this->condition_key( $condition ) ] = true;
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

				if ( isset( $claimed[ $this->condition_key( $condition ) ] ) ) {
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
	 * Add the object labels the condition builder needs to rebuild its selects.
	 *
	 * A stored row only carries the target's ID; the builder shows its title.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return array
	 */
	public function decorate_conditions( $conditions ) {
		$decorated = [];

		foreach ( (array) $conditions as $condition ) {
			$condition['sub_label'] = $condition['sub_id']
				? $this->get_object_label( $condition['sub_name'], $condition['sub_id'] )
				: '';

			$decorated[] = $condition;
		}

		return $decorated;
	}

	/**
	 * Identity of a row, for conflict and duplicate comparisons.
	 *
	 * @since 6.7.3
	 *
	 * @param array $condition Sanitized row.
	 *
	 * @return string
	 */
	protected function condition_key( $condition ) {
		return $condition['name'] . '|' . $condition['sub_name'] . '|' . $condition['sub_id'];
	}

	/**
	 * The label a row is shown under: the sub-condition's, or the top level's.
	 *
	 * @since 6.7.3
	 *
	 * @param array $condition Sanitized row.
	 *
	 * @return string Empty when neither condition is registered any more.
	 */
	public function get_condition_label( $condition ) {
		if ( '' !== (string) $condition['sub_name'] ) {
			$sub = Rules::get_condition( $condition['sub_name'] );

			if ( $sub ) {
				// A sub-condition that owns a group is labelled by its "all" name:
				// the row means "Posts", not "Post".
				return empty( $sub['sub_conditions'] ) ? $sub['label'] : $sub['all_label'];
			}

			return '';
		}

		$parent = Rules::get_condition( $condition['name'] );

		return $parent ? $parent['all_label'] : '';
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
			$label = $this->get_condition_label( $condition );

			if ( '' === $label ) {
				continue;
			}

			if ( $condition['sub_id'] ) {
				$object = $this->get_object_label( $condition['sub_name'], $condition['sub_id'] );

				if ( '' === $object ) {
					// The target is gone. Rendering a bare "Page" here reads like a
					// rule that targets every page, which is the opposite of what
					// this row now does — it can never match anything again.
					/* translators: %d: ID of the deleted object. */
					$object = sprintf( __( 'deleted #%d', 'essential-addons-for-elementor-lite' ), (int) $condition['sub_id'] );
				}

				/* translators: 1: rule label, 2: object title. */
				$label = sprintf( _x( '%1$s: %2$s', 'theme builder condition', 'essential-addons-for-elementor-lite' ), $label, $object );
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
	 * Condition rows whose target object no longer exists.
	 *
	 * `Conditions_Cleanup` removes these as the objects are deleted, so in
	 * practice this only turns up rows orphaned while the plugin was inactive —
	 * which is exactly when an admin needs to be told about them.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Sanitized condition rows.
	 *
	 * @return array The broken rows.
	 */
	public function find_broken_conditions( $conditions ) {
		$broken = [];

		foreach ( (array) $conditions as $condition ) {
			if ( empty( $condition['sub_id'] ) ) {
				continue;
			}

			if ( '' === $this->get_object_label( $condition['sub_name'], $condition['sub_id'] ) ) {
				$broken[] = $condition;
			}
		}

		return $broken;
	}

	/**
	 * Title of the object a condition row is narrowed to.
	 *
	 * @since 6.7.3
	 *
	 * @param string $condition Condition name (the row's `sub_name`).
	 * @param int    $id        Object ID.
	 *
	 * @return string Empty string when the object no longer exists.
	 */
	public function get_object_label( $condition, $id ) {
		$id     = absint( $id );
		$source = Rules::get_source( $condition );

		if ( ! $id || ! $source ) {
			return '';
		}

		switch ( $source['kind'] ) {
			case Rules::SOURCE_TERM:
				$term = get_term( $id, $source['taxonomy'] );

				return ( $term && ! is_wp_error( $term ) ) ? $term->name : '';

			case Rules::SOURCE_USER:
				$user = get_userdata( $id );

				return $user ? $user->display_name : '';

			default:
				$post = get_post( $id );

				if ( ! $post ) {
					return '';
				}

				return $post->post_title ? $post->post_title : __( '(no title)', 'essential-addons-for-elementor-lite' );
		}
	}

	/**
	 * Search the objects a condition can be narrowed down to.
	 *
	 * The query is derived from the **condition**, never from anything else the
	 * client sends: an unknown condition, or one with no picker, returns nothing.
	 * That keeps the endpoint from being usable as a generic post/term/user query
	 * proxy for content the builder never offers.
	 *
	 * @since 6.7.3
	 *
	 * @param string $condition Condition name.
	 * @param string $search    Search term.
	 * @param int    $limit     Maximum number of results.
	 *
	 * @return array List of `[ id, text ]` rows.
	 */
	public function search_objects( $condition, $search = '', $limit = 30 ) {
		$source = Rules::get_source( sanitize_key( $condition ) );

		if ( ! $source ) {
			return [];
		}

		$search  = sanitize_text_field( $search );
		$limit   = max( 1, min( 100, (int) $limit ) );
		$results = [];

		switch ( $source['kind'] ) {
			case Rules::SOURCE_TERM:
				$terms = get_terms(
					[
						'taxonomy'   => $source['taxonomy'],
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
				$posts = get_posts(
					[
						'post_type'           => (array) $source['post_type'],
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

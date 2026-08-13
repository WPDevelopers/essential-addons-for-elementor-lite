<?php
/**
 * Display condition registry.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Conditions;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * The conditions a template can be displayed on, and how they are evaluated.
 *
 * The model is Elementor's Theme Builder, deliberately and in detail — users
 * come to this screen already knowing that vocabulary, and an almost-the-same
 * one is worse than either. A saved row is four values:
 *
 *     type      include | exclude
 *     name      general | archive | singular        (the top level)
 *     sub_name  a condition nested under that name, or '' for "all of it"
 *     sub_id    the object that sub-condition is narrowed to, or 0 for "all"
 *
 * Which produces, in the builder: `Include / Singular / In Category / News`.
 *
 * Nesting is two levels deep and rendered as one grouped select, exactly as
 * Elementor does it: a sub-condition that has sub-conditions of its own becomes
 * an optgroup whose first option is the sub-condition itself.
 *
 *     Archives ─┬─ All Archives
 *               ├─ Author Archive · Date Archive · Search Results
 *               └─ [Posts Archive] ─┬─ Posts Archive
 *                                   ├─ Categories · Tags
 *                                   └─ Direct child Category of · Any child …
 *     Singular ─┬─ All Singular
 *               ├─ Front Page
 *               ├─ [Posts] ─┬─ Posts
 *               │           ├─ In Category · In child Categories · In Tag
 *               │           └─ Posts by Author
 *               └─ Direct child of · Any child of · By Author · 404 Page
 *
 * ### Specificity
 *
 * `priority` is Elementor's, and Elementor's runs backwards: the **lowest**
 * number wins, because a condition is scored by how narrow it is. The numbers
 * themselves are copied rather than reinvented so that two templates that
 * overlap resolve the way a user who has used Elementor's Theme Builder expects.
 *
 * @since 6.7.3
 */
class Rules {

	/**
	 * `sub_id` is a post ID.
	 */
	const SOURCE_POST = 'post';

	/**
	 * `sub_id` is a term ID.
	 */
	const SOURCE_TERM = 'term';

	/**
	 * `sub_id` is a user ID.
	 */
	const SOURCE_USER = 'user';

	/**
	 * The whole site — the least specific thing a template can claim.
	 */
	const PRIORITY_GENERAL = 100;

	/**
	 * Every archive.
	 */
	const PRIORITY_ARCHIVE = 80;

	/**
	 * One kind of archive: an author, a date, a taxonomy, a post type's archive.
	 */
	const PRIORITY_ARCHIVE_SUB = 70;

	/**
	 * Every singular view.
	 */
	const PRIORITY_SINGULAR = 60;

	/**
	 * One kind of singular view: a post type, a term membership, an author.
	 */
	const PRIORITY_SINGULAR_SUB = 40;

	/**
	 * The front page — one page, and there is only ever one of it.
	 */
	const PRIORITY_FRONT_PAGE = 30;

	/**
	 * The 404 view.
	 */
	const PRIORITY_NOT_FOUND = 20;

	/**
	 * Top level condition names, in the order the builder lists them.
	 */
	const TOP_LEVEL = [ 'general', 'archive', 'singular' ];

	/**
	 * Memoized registry.
	 *
	 * @var array|null
	 */
	private static $conditions = null;

	/**
	 * Every registered condition, keyed by name.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_conditions() {
		if ( null !== self::$conditions ) {
			return self::$conditions;
		}

		$conditions = [];

		self::register_general( $conditions );
		self::register_archive( $conditions );
		self::register_singular( $conditions );

		/**
		 * Filters the display conditions.
		 *
		 * A condition is an array of `label`, `callback` and — optionally —
		 * `all_label`, `priority`, `sub_conditions` and `source`. To make one
		 * reachable in the builder, add its name to the `sub_conditions` of the
		 * condition it belongs under:
		 *
		 *     add_filter( 'eael/theme_builder/conditions', function ( $conditions ) {
		 *         $conditions['logged_in'] = [
		 *             'label'    => __( 'Logged-in Users', 'my-textdomain' ),
		 *             'priority' => 40,
		 *             'callback' => 'is_user_logged_in',
		 *         ];
		 *
		 *         $conditions['singular']['sub_conditions'][] = 'logged_in';
		 *
		 *         return $conditions;
		 *     } );
		 *
		 * @since 6.7.3
		 *
		 * @param array $conditions Condition definitions keyed by name.
		 */
		$conditions = self::normalize( apply_filters( 'eael/theme_builder/conditions', $conditions ) );

		// Post types and taxonomies are still being registered until `init` has
		// finished, so caching before then would freeze an incomplete registry.
		if ( did_action( 'init' ) ) {
			self::$conditions = $conditions;
		}

		return $conditions;
	}

	/**
	 * Drop the memoized registry.
	 *
	 * @since 6.7.3
	 */
	public static function flush() {
		self::$conditions = null;
	}

	/* ---------------------------------------------------------------------
	 * Registration.
	 * ------------------------------------------------------------------ */

	/**
	 * The whole site.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Registry, by reference.
	 */
	protected static function register_general( &$conditions ) {
		$conditions['general'] = [
			'label'     => __( 'General', 'essential-addons-for-elementor-lite' ),
			'all_label' => __( 'Entire Site', 'essential-addons-for-elementor-lite' ),
			'priority'  => self::PRIORITY_GENERAL,
			'callback'  => '__return_true',
		];
	}

	/**
	 * Archives: the listing views.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Registry, by reference.
	 */
	protected static function register_archive( &$conditions ) {
		$sub = [ 'author', 'date', 'search' ];

		$conditions['author'] = [
			'label'    => __( 'Author Archive', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_ARCHIVE_SUB,
			'source'   => [ 'kind' => self::SOURCE_USER ],
			'callback' => function ( $sub_id = 0 ) {
				return $sub_id ? is_author( $sub_id ) : is_author();
			},
		];

		$conditions['date'] = [
			'label'    => __( 'Date Archive', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_ARCHIVE_SUB,
			'callback' => 'is_date',
		];

		$conditions['search'] = [
			'label'    => __( 'Search Results', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_ARCHIVE_SUB,
			'callback' => 'is_search',
		];

		foreach ( self::get_public_post_types() as $slug => $post_type ) {
			// `has_archive` is not the test: `post` has no archive flag but does
			// have a posts page, and Elementor uses the same link check.
			if ( ! get_post_type_archive_link( $slug ) ) {
				continue;
			}

			$name = $slug . '_archive';

			$conditions[ $name ] = [
				/* translators: %s: post type plural label, e.g. Products. */
				'label'          => sprintf( __( '%s Archive', 'essential-addons-for-elementor-lite' ), self::get_label( $post_type, 'name', $slug ) ),
				'priority'       => self::PRIORITY_ARCHIVE_SUB,
				'sub_conditions' => [],
				'callback'       => function () use ( $slug ) {
					return self::check_post_type_archive( $slug );
				},
			];

			// The taxonomy archives of that post type hang under it, so "Categories"
			// is found where a user looks for it: inside "Posts Archive".
			foreach ( self::get_object_taxonomies( $slug ) as $tax_slug => $taxonomy ) {
				if ( isset( $conditions[ $tax_slug ] ) ) {
					$conditions[ $name ]['sub_conditions'][] = $tax_slug;
					continue;
				}

				$conditions[ $tax_slug ] = [
					'label'    => self::get_label( $taxonomy, 'name', $tax_slug ),
					'priority' => self::PRIORITY_ARCHIVE_SUB,
					'source'   => [ 'kind' => self::SOURCE_TERM, 'taxonomy' => $tax_slug ],
					'callback' => function ( $sub_id = 0 ) use ( $tax_slug ) {
						return self::check_taxonomy_archive( $tax_slug, $sub_id );
					},
				];

				$conditions[ $name ]['sub_conditions'][] = $tax_slug;

				if ( ! $taxonomy->hierarchical ) {
					continue;
				}

				$singular = self::get_label( $taxonomy, 'singular_name', $tax_slug );

				$conditions[ 'child_of_' . $tax_slug ] = [
					/* translators: %s: taxonomy singular label, e.g. Category. */
					'label'    => sprintf( __( 'Direct child %s of', 'essential-addons-for-elementor-lite' ), $singular ),
					'priority' => self::PRIORITY_ARCHIVE_SUB,
					'source'   => [ 'kind' => self::SOURCE_TERM, 'taxonomy' => $tax_slug ],
					'callback' => function ( $sub_id = 0 ) use ( $tax_slug ) {
						return self::check_child_of_term( $tax_slug, $sub_id, false );
					},
				];

				$conditions[ 'any_child_of_' . $tax_slug ] = [
					/* translators: %s: taxonomy singular label, e.g. Category. */
					'label'    => sprintf( __( 'Any child %s of', 'essential-addons-for-elementor-lite' ), $singular ),
					'priority' => self::PRIORITY_ARCHIVE_SUB,
					'source'   => [ 'kind' => self::SOURCE_TERM, 'taxonomy' => $tax_slug ],
					'callback' => function ( $sub_id = 0 ) use ( $tax_slug ) {
						return self::check_child_of_term( $tax_slug, $sub_id, true );
					},
				];

				array_push( $conditions[ $name ]['sub_conditions'], 'child_of_' . $tax_slug, 'any_child_of_' . $tax_slug );
			}

			$sub[] = $name;
		}

		$conditions['archive'] = [
			'label'          => __( 'Archives', 'essential-addons-for-elementor-lite' ),
			'all_label'      => __( 'All Archives', 'essential-addons-for-elementor-lite' ),
			'priority'       => self::PRIORITY_ARCHIVE,
			'sub_conditions' => $sub,
			'callback'       => [ __CLASS__, 'check_archive' ],
		];
	}

	/**
	 * Singular: the views that show one thing.
	 *
	 * @since 6.7.3
	 *
	 * @param array $conditions Registry, by reference.
	 */
	protected static function register_singular( &$conditions ) {
		$conditions['front_page'] = [
			'label'    => __( 'Front Page', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_FRONT_PAGE,
			'callback' => 'is_front_page',
		];

		$sub = [ 'front_page' ];

		foreach ( self::get_public_post_types() as $slug => $post_type ) {
			$name     = $slug;
			$singular = self::get_label( $post_type, 'singular_name', $slug );
			$plural   = self::get_label( $post_type, 'name', $slug );

			$conditions[ $name ] = [
				'label'          => $singular,
				'all_label'      => $plural,
				'priority'       => self::PRIORITY_SINGULAR_SUB,
				'source'         => [ 'kind' => self::SOURCE_POST, 'post_type' => $slug ],
				'sub_conditions' => [],
				'callback'       => function ( $sub_id = 0 ) use ( $slug ) {
					return self::check_singular_post_type( $slug, $sub_id );
				},
			];

			// "In Category" and friends: the answer to "show this on every post
			// filed under News", which a category *archive* rule cannot express.
			foreach ( self::get_object_taxonomies( $slug ) as $tax_slug => $taxonomy ) {
				$tax_singular = self::get_label( $taxonomy, 'singular_name', $tax_slug );
				$tax_plural   = self::get_label( $taxonomy, 'name', $tax_slug );
				$in_name      = 'in_' . $tax_slug;

				if ( ! isset( $conditions[ $in_name ] ) ) {
					$conditions[ $in_name ] = [
						/* translators: %s: taxonomy singular label, e.g. Category. */
						'label'    => sprintf( __( 'In %s', 'essential-addons-for-elementor-lite' ), $tax_singular ),
						'priority' => self::PRIORITY_SINGULAR_SUB,
						'source'   => [ 'kind' => self::SOURCE_TERM, 'taxonomy' => $tax_slug ],
						'callback' => function ( $sub_id = 0 ) use ( $tax_slug ) {
							return self::check_in_taxonomy( $tax_slug, $sub_id );
						},
					];
				}

				$conditions[ $name ]['sub_conditions'][] = $in_name;

				if ( ! $taxonomy->hierarchical ) {
					continue;
				}

				$children_name = 'in_' . $tax_slug . '_children';

				if ( ! isset( $conditions[ $children_name ] ) ) {
					$conditions[ $children_name ] = [
						/* translators: %s: taxonomy plural label, e.g. Categories. */
						'label'    => sprintf( __( 'In child %s', 'essential-addons-for-elementor-lite' ), $tax_plural ),
						'priority' => self::PRIORITY_SINGULAR_SUB,
						'source'   => [ 'kind' => self::SOURCE_TERM, 'taxonomy' => $tax_slug ],
						'callback' => function ( $sub_id = 0 ) use ( $tax_slug ) {
							return self::check_in_taxonomy_children( $tax_slug, $sub_id );
						},
					];
				}

				$conditions[ $name ]['sub_conditions'][] = $children_name;
			}

			$by_author = $slug . '_by_author';

			$conditions[ $by_author ] = [
				/* translators: %s: post type plural label, e.g. Products. */
				'label'    => sprintf( __( '%s by Author', 'essential-addons-for-elementor-lite' ), $plural ),
				'priority' => self::PRIORITY_SINGULAR_SUB,
				'source'   => [ 'kind' => self::SOURCE_USER ],
				'callback' => function ( $sub_id = 0 ) use ( $slug ) {
					return is_singular( $slug ) && self::check_by_author( $sub_id );
				},
			];

			$conditions[ $name ]['sub_conditions'][] = $by_author;

			$sub[] = $name;
		}

		$conditions['child_of'] = [
			'label'    => __( 'Direct child of', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_SINGULAR_SUB,
			'source'   => [ 'kind' => self::SOURCE_POST, 'post_type' => self::get_hierarchical_post_types() ],
			'callback' => function ( $sub_id = 0 ) {
				return self::check_child_of( $sub_id, false );
			},
		];

		$conditions['any_child_of'] = [
			'label'    => __( 'Any child of', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_SINGULAR_SUB,
			'source'   => [ 'kind' => self::SOURCE_POST, 'post_type' => self::get_hierarchical_post_types() ],
			'callback' => function ( $sub_id = 0 ) {
				return self::check_child_of( $sub_id, true );
			},
		];

		$conditions['by_author'] = [
			'label'    => __( 'By Author', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_SINGULAR_SUB,
			'source'   => [ 'kind' => self::SOURCE_USER ],
			'callback' => function ( $sub_id = 0 ) {
				return is_singular() && self::check_by_author( $sub_id );
			},
		];

		$conditions['not_found404'] = [
			'label'    => __( '404 Page', 'essential-addons-for-elementor-lite' ),
			'priority' => self::PRIORITY_NOT_FOUND,
			'callback' => 'is_404',
		];

		array_push( $sub, 'child_of', 'any_child_of', 'by_author', 'not_found404' );

		$conditions['singular'] = [
			'label'          => __( 'Singular', 'essential-addons-for-elementor-lite' ),
			'all_label'      => __( 'All Singular', 'essential-addons-for-elementor-lite' ),
			'priority'       => self::PRIORITY_SINGULAR,
			'sub_conditions' => $sub,
			'callback'       => [ __CLASS__, 'check_singular' ],
		];
	}

	/**
	 * Fill in the optional keys and drop anything unusable.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $conditions Condition definitions keyed by name.
	 *
	 * @return array
	 */
	protected static function normalize( $conditions ) {
		if ( ! is_array( $conditions ) ) {
			return [];
		}

		$normalized = [];

		foreach ( $conditions as $name => $condition ) {
			$name = sanitize_key( $name );

			if ( '' === $name || ! is_array( $condition ) ) {
				continue;
			}

			$condition = array_merge(
				[
					'label'          => '',
					'all_label'      => '',
					'priority'       => self::PRIORITY_SINGULAR_SUB,
					'sub_conditions' => [],
					'source'         => null,
					'callback'       => null,
				],
				$condition
			);

			// A condition the engine cannot evaluate, or the UI cannot label, is
			// worse than no condition at all.
			if ( '' === (string) $condition['label'] || ! is_callable( $condition['callback'] ) ) {
				continue;
			}

			$condition['name']           = $name;
			$condition['priority']       = (int) $condition['priority'];
			$condition['all_label']      = '' !== (string) $condition['all_label'] ? $condition['all_label'] : $condition['label'];
			$condition['sub_conditions'] = array_values( array_unique( array_map( 'strval', (array) $condition['sub_conditions'] ) ) );

			$normalized[ $name ] = $condition;
		}

		// A sub-condition that was filtered away must not leave a dangling name in
		// somebody else's list, or the builder would render an empty option.
		foreach ( $normalized as $name => $condition ) {
			$normalized[ $name ]['sub_conditions'] = array_values(
				array_filter(
					$condition['sub_conditions'],
					function ( $sub ) use ( $normalized ) {
						return isset( $normalized[ $sub ] );
					}
				)
			);
		}

		return $normalized;
	}

	/* ---------------------------------------------------------------------
	 * Lookups.
	 * ------------------------------------------------------------------ */

	/**
	 * One condition definition.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Condition name.
	 *
	 * @return array|null
	 */
	public static function get_condition( $name ) {
		$conditions = self::get_conditions();

		return isset( $conditions[ $name ] ) ? $conditions[ $name ] : null;
	}

	/**
	 * Whether a name is one of the three top level conditions.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Condition name.
	 *
	 * @return bool
	 */
	public static function is_top_level( $name ) {
		return in_array( $name, self::TOP_LEVEL, true );
	}

	/**
	 * Whether a sub-condition may be used under a top level one.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name     Top level condition.
	 * @param string $sub_name Sub-condition, or '' for the top level itself.
	 *
	 * @return bool
	 */
	public static function is_valid_pair( $name, $sub_name ) {
		if ( ! self::is_top_level( $name ) ) {
			return false;
		}

		if ( '' === (string) $sub_name ) {
			return true;
		}

		$parent = self::get_condition( $name );

		if ( ! $parent || ! self::get_condition( $sub_name ) ) {
			return false;
		}

		if ( in_array( $sub_name, $parent['sub_conditions'], true ) ) {
			return true;
		}

		// Second level: "In Category" sits under "Posts", which sits under Singular.
		foreach ( $parent['sub_conditions'] as $child ) {
			$condition = self::get_condition( $child );

			if ( $condition && in_array( $sub_name, $condition['sub_conditions'], true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * The object picker a condition offers, if any.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Condition name.
	 *
	 * @return array|null `[ kind, taxonomy|post_type ]`, or null when the
	 *                    condition targets a whole view.
	 */
	public static function get_source( $name ) {
		$condition = self::get_condition( $name );

		return ( $condition && ! empty( $condition['source'] ) ) ? $condition['source'] : null;
	}

	/**
	 * Evaluate one condition against the current request.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name   Condition name.
	 * @param int    $sub_id Object the condition is narrowed to, 0 for all.
	 *
	 * @return bool
	 */
	public static function check( $name, $sub_id = 0 ) {
		$condition = self::get_condition( $name );

		if ( ! $condition || ! is_callable( $condition['callback'] ) ) {
			return false;
		}

		$matched = (bool) call_user_func( $condition['callback'], absint( $sub_id ) );

		/**
		 * Filters the outcome of a single condition.
		 *
		 * @since 6.7.3
		 *
		 * @param bool   $matched Whether it matched the current request.
		 * @param string $name    Condition name.
		 * @param int    $sub_id  Object ID the condition was narrowed to.
		 */
		return (bool) apply_filters( 'eael/theme_builder/check_rule', $matched, $name, $sub_id );
	}

	/**
	 * How specific a saved row is — **lower wins**.
	 *
	 * Elementor's algorithm, kept verbatim so overlapping templates resolve the
	 * way they do there: start from the top level, take the sub-condition's score
	 * when it is narrower, then subtract for each further narrowing — naming a
	 * sub-condition at all, and naming an object inside it. A sub-condition with
	 * no children of its own is inherently specific and gets part of that bonus
	 * even without an object.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name     Top level condition.
	 * @param string $sub_name Sub-condition, or ''.
	 * @param int    $sub_id   Object ID, or 0.
	 *
	 * @return int
	 */
	public static function get_priority( $name, $sub_name = '', $sub_id = 0 ) {
		$condition = self::get_condition( $name );

		if ( ! $condition ) {
			return self::PRIORITY_GENERAL;
		}

		$priority = (int) $condition['priority'];
		$sub      = '' !== (string) $sub_name ? self::get_condition( $sub_name ) : null;

		if ( ! $sub ) {
			return $priority;
		}

		if ( (int) $sub['priority'] < $priority ) {
			$priority = (int) $sub['priority'];
		}

		$priority -= 10;

		if ( absint( $sub_id ) ) {
			$priority -= 10;
		} elseif ( empty( $sub['sub_conditions'] ) ) {
			$priority -= 5;
		}

		return $priority;
	}

	/**
	 * The registry as the condition builder needs it — labels and shape, no callbacks.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_conditions_for_ui() {
		$prepared = [];

		foreach ( self::get_conditions() as $name => $condition ) {
			$source = $condition['source'];

			$prepared[ $name ] = [
				'name'           => $name,
				'label'          => $condition['label'],
				'all_label'      => $condition['all_label'],
				'sub_conditions' => $condition['sub_conditions'],
				// The builder only needs to know *whether* to show the object
				// picker. Which objects it may search is resolved server side from
				// the condition name, so nothing about the query is client-supplied.
				'has_source'     => ! empty( $source ),
			];
		}

		return $prepared;
	}

	/**
	 * Top level conditions, in display order, as `[ name, label ]` rows.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_top_level_for_ui() {
		$rows = [];

		foreach ( self::TOP_LEVEL as $name ) {
			$condition = self::get_condition( $name );

			if ( ! $condition ) {
				continue;
			}

			// "Entire Site" for general, "Archives" and "Singular" for the others —
			// general has no second select, so its all_label is what names it.
			$rows[] = [
				'name'  => $name,
				'label' => 'general' === $name ? $condition['all_label'] : $condition['label'],
			];
		}

		return $rows;
	}

	/* ---------------------------------------------------------------------
	 * Legacy conditions.
	 * ------------------------------------------------------------------ */

	/**
	 * Map a pre-6.7.3 flat rule name onto the current `name` / `sub_name` pair.
	 *
	 * Templates saved before the builder moved to Elementor's model store rows
	 * like `[ 'name' => 'category_archive', 'sub_id' => 5 ]`. They are translated
	 * on read rather than migrated in place: the meta is rewritten the next time
	 * the template is saved, and a site that rolls the plugin back keeps working.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Legacy rule name.
	 *
	 * @return array|null `[ name, sub_name ]`, or null when it has no equivalent.
	 */
	public static function map_legacy_rule( $name ) {
		$map = [
			'entire_site'    => [ 'general', '' ],
			'front_page'     => [ 'singular', 'front_page' ],
			'blog'           => [ 'archive', 'post_archive' ],
			'search'         => [ 'archive', 'search' ],
			'not_found'      => [ 'singular', 'not_found404' ],
			'archive'        => [ 'archive', '' ],
			'date_archive'   => [ 'archive', 'date' ],
			'author_archive' => [ 'archive', 'author' ],
			'singular'       => [ 'singular', '' ],
		];

		if ( isset( $map[ $name ] ) ) {
			return $map[ $name ];
		}

		// `category_archive` / `tag_archive` / `{taxonomy}_archive` → the taxonomy
		// condition; `{post_type}_archive` → the post type archive condition.
		if ( '_archive' === substr( $name, -8 ) ) {
			$base = substr( $name, 0, -8 );

			if ( 'category' === $base || 'tag' === $base ) {
				$base = 'tag' === $base ? 'post_tag' : 'category';
			}

			if ( taxonomy_exists( $base ) ) {
				return [ 'archive', $base ];
			}

			if ( post_type_exists( $base ) ) {
				return [ 'archive', $base . '_archive' ];
			}

			return null;
		}

		// Everything else was a singular post type rule, named after the type.
		if ( post_type_exists( $name ) ) {
			return [ 'singular', $name ];
		}

		return null;
	}

	/* ---------------------------------------------------------------------
	 * Object helpers.
	 * ------------------------------------------------------------------ */

	/**
	 * Post types users target content on.
	 *
	 * `public` alone is not the test: Elementor's library, Templately's library
	 * and similar builder types are public so their previews resolve. The
	 * discriminator is `show_ui && show_in_nav_menus` — content types users link
	 * to are in nav menus, container types are not — plus a blocklist.
	 *
	 * @since 6.7.3
	 *
	 * @return \WP_Post_Type[] Keyed by slug.
	 */
	public static function get_public_post_types() {
		$excluded = self::get_excluded_post_types();
		$types    = [];

		foreach ( get_post_types( [ 'public' => true ], 'objects' ) as $slug => $post_type ) {
			if ( in_array( $slug, $excluded, true ) ) {
				continue;
			}

			if ( ! $post_type->show_ui || ! $post_type->show_in_nav_menus ) {
				continue;
			}

			$types[ $slug ] = $post_type;
		}

		return $types;
	}

	/**
	 * Hierarchical public post type slugs — what "child of" can point at.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function get_hierarchical_post_types() {
		$slugs = [];

		foreach ( self::get_public_post_types() as $slug => $post_type ) {
			if ( $post_type->hierarchical ) {
				$slugs[] = $slug;
			}
		}

		return $slugs ? $slugs : [ 'page' ];
	}

	/**
	 * The taxonomies of a post type that users browse by.
	 *
	 * @since 6.7.3
	 *
	 * @param string $post_type Post type slug.
	 *
	 * @return \WP_Taxonomy[] Keyed by slug.
	 */
	protected static function get_object_taxonomies( $post_type ) {
		$excluded   = self::get_excluded_taxonomies();
		$taxonomies = [];

		foreach ( get_object_taxonomies( $post_type, 'objects' ) as $slug => $taxonomy ) {
			if ( in_array( $slug, $excluded, true ) ) {
				continue;
			}

			if ( ! $taxonomy->public || ! $taxonomy->show_ui || ! $taxonomy->show_in_nav_menus ) {
				continue;
			}

			$taxonomies[ $slug ] = $taxonomy;
		}

		return $taxonomies;
	}

	/**
	 * Post types the registry never offers.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_excluded_post_types() {
		$excluded = [
			'attachment',
			Post_Type::CPT,
			'elementor_library',
			'e-landing-page',
			'e-floating-buttons',
			'templately_library',
			'elementor-hf',
			'wp_block',
			'wp_template',
			'wp_template_part',
			'wp_navigation',
			'wp_global_styles',
		];

		/**
		 * Filters the post types excluded from the display conditions.
		 *
		 * @since 6.7.3
		 *
		 * @param array $excluded Post type slugs.
		 */
		return (array) apply_filters( 'eael/theme_builder/excluded_post_types', $excluded );
	}

	/**
	 * Taxonomies the registry never offers.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_excluded_taxonomies() {
		$excluded = [
			'post_format',
			'nav_menu',
			'link_category',
			'wp_theme',
			'wp_template_part_area',
			'wp_pattern_category',
			'elementor_library_type',
			'elementor_library_category',
		];

		/**
		 * Filters the taxonomies excluded from the display conditions.
		 *
		 * @since 6.7.3
		 *
		 * @param array $excluded Taxonomy slugs.
		 */
		return (array) apply_filters( 'eael/theme_builder/excluded_taxonomies', $excluded );
	}

	/**
	 * Read a label off a post type or taxonomy object, with fallbacks.
	 *
	 * @since 6.7.3
	 *
	 * @param object $object   Post type or taxonomy object.
	 * @param string $property Label property to read.
	 * @param string $fallback Value to use when the object has no usable label.
	 *
	 * @return string
	 */
	protected static function get_label( $object, $property, $fallback ) {
		if ( isset( $object->labels->$property ) && '' !== $object->labels->$property ) {
			return $object->labels->$property;
		}

		if ( isset( $object->label ) && '' !== $object->label ) {
			return $object->label;
		}

		return ucfirst( str_replace( [ '-', '_' ], ' ', $fallback ) );
	}

	/**
	 * Whether WooCommerce is available.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) || post_type_exists( 'product' );
	}

	/* ---------------------------------------------------------------------
	 * Condition callbacks.
	 * ------------------------------------------------------------------ */

	/**
	 * Any listing view.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_archive() {
		return is_archive() || is_home() || is_search();
	}

	/**
	 * Any view that shows a single thing — plus 404, which Elementor groups here
	 * because a header still has to render on it.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_singular() {
		return ( is_singular() && ! is_embed() ) || is_404();
	}

	/**
	 * The archive of one post type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $post_type Post type slug.
	 *
	 * @return bool
	 */
	public static function check_post_type_archive( $post_type ) {
		if ( is_post_type_archive( $post_type ) ) {
			return true;
		}

		if ( 'post' === $post_type && is_home() ) {
			return true;
		}

		// WooCommerce's shop page is a page, not a `product` archive.
		return 'product' === $post_type && function_exists( 'is_shop' ) && is_shop();
	}

	/**
	 * A taxonomy term archive, optionally one term.
	 *
	 * @since 6.7.3
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @param int    $sub_id   Term ID, or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_taxonomy_archive( $taxonomy, $sub_id = 0 ) {
		$sub_id = absint( $sub_id );

		if ( 'category' === $taxonomy ) {
			return $sub_id ? is_category( $sub_id ) : is_category();
		}

		if ( 'post_tag' === $taxonomy ) {
			return $sub_id ? is_tag( $sub_id ) : is_tag();
		}

		return is_tax( $taxonomy, $sub_id ? $sub_id : '' );
	}

	/**
	 * A term archive below a given term.
	 *
	 * @since 6.7.3
	 *
	 * @param string $taxonomy  Taxonomy slug.
	 * @param int    $sub_id    Parent term ID.
	 * @param bool   $recursive Whether to follow the whole ancestor chain.
	 *
	 * @return bool
	 */
	public static function check_child_of_term( $taxonomy, $sub_id, $recursive ) {
		$sub_id = absint( $sub_id );

		if ( ! $sub_id || ! self::check_taxonomy_archive( $taxonomy ) ) {
			return false;
		}

		$current = get_queried_object();

		if ( ! $current instanceof \WP_Term ) {
			return false;
		}

		if ( ! $recursive ) {
			return $sub_id === (int) $current->parent;
		}

		return in_array( $sub_id, get_ancestors( $current->term_id, $taxonomy, 'taxonomy' ), true );
	}

	/**
	 * One entry of a post type, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param string $post_type Post type slug.
	 * @param int    $sub_id    Post ID, or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_singular_post_type( $post_type, $sub_id = 0 ) {
		$sub_id = absint( $sub_id );

		if ( $sub_id ) {
			return is_singular() && get_queried_object_id() === $sub_id;
		}

		return is_singular( $post_type );
	}

	/**
	 * A singular view filed under a term.
	 *
	 * @since 6.7.3
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @param int    $sub_id   Term ID, or 0 for "has any term of it".
	 *
	 * @return bool
	 */
	public static function check_in_taxonomy( $taxonomy, $sub_id = 0 ) {
		if ( ! is_singular() ) {
			return false;
		}

		$sub_id = absint( $sub_id );

		if ( ! $sub_id ) {
			$terms = get_the_terms( get_queried_object_id(), $taxonomy );

			return ! empty( $terms ) && ! is_wp_error( $terms );
		}

		return has_term( $sub_id, $taxonomy, get_queried_object_id() );
	}

	/**
	 * A singular view filed under a child of a term.
	 *
	 * @since 6.7.3
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @param int    $sub_id   Parent term ID.
	 *
	 * @return bool
	 */
	public static function check_in_taxonomy_children( $taxonomy, $sub_id = 0 ) {
		$sub_id = absint( $sub_id );

		if ( ! is_singular() || ! $sub_id ) {
			return false;
		}

		$children = get_term_children( $sub_id, $taxonomy );

		if ( empty( $children ) || is_wp_error( $children ) ) {
			return false;
		}

		return has_term( $children, $taxonomy, get_queried_object_id() );
	}

	/**
	 * A singular view written by an author.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id User ID, or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_by_author( $sub_id = 0 ) {
		$sub_id = absint( $sub_id );

		if ( ! $sub_id ) {
			return true;
		}

		return (int) get_post_field( 'post_author', get_queried_object_id() ) === $sub_id;
	}

	/**
	 * A singular view below another post.
	 *
	 * @since 6.7.3
	 *
	 * @param int  $sub_id    Parent post ID, or 0 for "has any parent".
	 * @param bool $recursive Whether to follow the whole ancestor chain.
	 *
	 * @return bool
	 */
	public static function check_child_of( $sub_id, $recursive ) {
		if ( ! is_singular() ) {
			return false;
		}

		$sub_id = absint( $sub_id );
		$id     = get_queried_object_id();

		if ( $recursive ) {
			$ancestors = get_post_ancestors( $id );

			return $sub_id ? in_array( $sub_id, $ancestors, true ) : ! empty( $ancestors );
		}

		$parent = (int) wp_get_post_parent_id( $id );

		return $sub_id ? $parent === $sub_id : $parent > 0;
	}
}

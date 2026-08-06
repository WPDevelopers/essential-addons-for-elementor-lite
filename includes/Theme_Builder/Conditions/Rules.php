<?php
/**
 * Display condition rules.
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
 * Registry and evaluator for the individual condition rules.
 *
 * The registry is built in three layers, in this order:
 *
 * 1. **Core rules** — the fixed WordPress views (Entire Site, Front Page, Blog,
 *    Search, 404, Archive, Category/Tag/Author/Date archives, Singular, Post,
 *    Page). Hard-coded, because their conditional tags have no generic form.
 * 2. **Dynamic rules** — one singular rule per public post type and one archive
 *    rule per public post type with an archive and per public taxonomy, derived
 *    from `get_post_types()` / `get_taxonomies()`. A site that registers a
 *    `product` or `portfolio` type gets targeting for it with no code.
 * 3. **Third-party rules** — whatever `eael/theme_builder/condition_rules`
 *    returns. Anything missing from a filtered rule is filled in by
 *    `normalize_rules()`, so the minimum viable rule is a label and a callback.
 *
 * Each rule declares:
 *
 * - `label`           Human readable name shown in the condition builder.
 * - `group`           Optgroup the rule is listed under.
 * - `specificity`     How targeted the rule is. The engine always renders the
 *                     most specific matching template, so "Post: Hello World"
 *                     beats "Singular", which in turn beats "Entire Site".
 * - `sub_source`      Slug of the object type the rule can be narrowed down to,
 *                     or false when the rule targets a whole view.
 * - `sub_source_type` What `sub_source` names — `post_type`, `taxonomy` or `user`.
 * - `supports`        Which condition types (`include` / `exclude`) offer the rule.
 * - `callback`        Callable receiving the sub-object ID and returning a bool.
 *
 * @since 6.7.3
 */
class Rules {

	/**
	 * Specificity of a rule that targets the whole site.
	 */
	const SPECIFICITY_SITE = 10;

	/**
	 * Specificity of a broad rule such as "Archive" or "Singular".
	 */
	const SPECIFICITY_BROAD = 20;

	/**
	 * Specificity of a rule that targets one kind of object.
	 */
	const SPECIFICITY_TYPE = 30;

	/**
	 * Specificity of a rule that targets one unique view (front page, 404, …).
	 */
	const SPECIFICITY_UNIQUE = 40;

	/**
	 * Bonus applied when a rule is narrowed down to a single object.
	 */
	const SPECIFICITY_OBJECT_BONUS = 20;

	/**
	 * `sub_source` names a post type.
	 */
	const SOURCE_POST_TYPE = 'post_type';

	/**
	 * `sub_source` names a taxonomy.
	 */
	const SOURCE_TAXONOMY = 'taxonomy';

	/**
	 * `sub_source` names a user role/author lookup.
	 */
	const SOURCE_USER = 'user';

	/**
	 * Memoized rule definitions.
	 *
	 * @var array|null
	 */
	private static $rules = null;

	/**
	 * All registered rules keyed by name.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_rules() {
		if ( null !== self::$rules ) {
			return self::$rules;
		}

		$rules = self::get_core_rules();
		$rules = array_merge( $rules, self::get_post_type_rules( $rules ) );
		$rules = array_merge( $rules, self::get_taxonomy_rules( $rules ) );

		/**
		 * Filters the available display condition rules.
		 *
		 * The canonical extension point for third-party conditions. Only `label`
		 * and `callback` are required — every other key falls back to a sane
		 * default:
		 *
		 *     add_filter( 'eael/theme_builder/condition_rules', function ( $rules ) {
		 *         $rules['is_logged_in'] = [
		 *             'label'    => __( 'Logged-in Users', 'my-textdomain' ),
		 *             'group'    => 'general',
		 *             'supports' => [ 'include', 'exclude' ],
		 *             'callback' => 'is_user_logged_in',
		 *         ];
		 *
		 *         return $rules;
		 *     } );
		 *
		 * @since 6.7.3
		 *
		 * @param array $rules Rule definitions keyed by rule name.
		 */
		$rules = self::normalize_rules( apply_filters( 'eael/theme_builder/condition_rules', $rules ) );

		// Post types and taxonomies are still being registered until `init` has
		// finished, so caching before then would freeze an incomplete registry.
		if ( did_action( 'init' ) ) {
			self::$rules = $rules;
		}

		return $rules;
	}

	/**
	 * Drop the memoized registry.
	 *
	 * Needed when a post type or taxonomy is registered late, and by tests.
	 *
	 * @since 6.7.3
	 */
	public static function flush() {
		self::$rules = null;
	}

	/**
	 * The fixed WordPress views.
	 *
	 * These stay hard-coded: `is_front_page()`, `is_search()`, `is_404()` and
	 * friends have no generic, data-driven equivalent.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function get_core_rules() {
		return [
			'entire_site'      => [
				'label'       => __( 'Entire Site', 'essential-addons-for-elementor-lite' ),
				'group'       => 'general',
				'specificity' => self::SPECIFICITY_SITE,
				'supports'    => [ 'include', 'exclude' ],
				'callback'    => [ __CLASS__, 'check_entire_site' ],
			],
			'front_page'       => [
				'label'       => __( 'Front Page', 'essential-addons-for-elementor-lite' ),
				'group'       => 'general',
				'specificity' => self::SPECIFICITY_UNIQUE,
				'supports'    => [ 'include', 'exclude' ],
				'callback'    => [ __CLASS__, 'check_front_page' ],
			],
			'blog'             => [
				'label'       => __( 'Blog Page', 'essential-addons-for-elementor-lite' ),
				'group'       => 'general',
				'specificity' => self::SPECIFICITY_UNIQUE,
				'supports'    => [ 'include' ],
				'callback'    => [ __CLASS__, 'check_blog' ],
			],
			'search'           => [
				'label'       => __( 'Search Results', 'essential-addons-for-elementor-lite' ),
				'group'       => 'general',
				'specificity' => self::SPECIFICITY_UNIQUE,
				'supports'    => [ 'include' ],
				'callback'    => [ __CLASS__, 'check_search' ],
			],
			'not_found'        => [
				'label'       => __( '404 Page', 'essential-addons-for-elementor-lite' ),
				'group'       => 'general',
				'specificity' => self::SPECIFICITY_UNIQUE,
				'supports'    => [ 'include' ],
				'callback'    => [ __CLASS__, 'check_not_found' ],
			],
			'archive'          => [
				'label'       => __( 'Archive', 'essential-addons-for-elementor-lite' ),
				'group'       => 'archive',
				'specificity' => self::SPECIFICITY_BROAD,
				'supports'    => [ 'include', 'exclude' ],
				'callback'    => [ __CLASS__, 'check_archive' ],
			],
			'category_archive' => [
				'label'           => __( 'Category Archive', 'essential-addons-for-elementor-lite' ),
				'group'           => 'archive',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => 'category',
				'sub_source_type' => self::SOURCE_TAXONOMY,
				'supports'        => [ 'include' ],
				'callback'        => [ __CLASS__, 'check_category_archive' ],
			],
			'tag_archive'      => [
				'label'           => __( 'Tag Archive', 'essential-addons-for-elementor-lite' ),
				'group'           => 'archive',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => 'post_tag',
				'sub_source_type' => self::SOURCE_TAXONOMY,
				'supports'        => [ 'include' ],
				'callback'        => [ __CLASS__, 'check_tag_archive' ],
			],
			'author_archive'   => [
				'label'           => __( 'Author Archive', 'essential-addons-for-elementor-lite' ),
				'group'           => 'archive',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => 'author',
				'sub_source_type' => self::SOURCE_USER,
				'supports'        => [ 'include' ],
				'callback'        => [ __CLASS__, 'check_author_archive' ],
			],
			'date_archive'     => [
				'label'       => __( 'Date Archive', 'essential-addons-for-elementor-lite' ),
				'group'       => 'archive',
				'specificity' => self::SPECIFICITY_TYPE,
				'supports'    => [ 'include' ],
				'callback'    => [ __CLASS__, 'check_date_archive' ],
			],
			'singular'         => [
				'label'       => __( 'Singular', 'essential-addons-for-elementor-lite' ),
				'group'       => 'singular',
				'specificity' => self::SPECIFICITY_BROAD,
				'supports'    => [ 'include', 'exclude' ],
				'callback'    => [ __CLASS__, 'check_singular' ],
			],
			'post'             => [
				'label'           => __( 'Post', 'essential-addons-for-elementor-lite' ),
				'group'           => 'singular',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => 'post',
				'sub_source_type' => self::SOURCE_POST_TYPE,
				'supports'        => [ 'include', 'exclude' ],
				'callback'        => [ __CLASS__, 'check_post' ],
			],
			'page'             => [
				'label'           => __( 'Page', 'essential-addons-for-elementor-lite' ),
				'group'           => 'singular',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => 'page',
				'sub_source_type' => self::SOURCE_POST_TYPE,
				'supports'        => [ 'include', 'exclude' ],
				'callback'        => [ __CLASS__, 'check_page' ],
			],
		];
	}

	/**
	 * One singular rule per public post type, plus an archive rule where the
	 * type has one.
	 *
	 * `post` and `page` are already core rules and are skipped, so WooCommerce's
	 * `product` — and any other content type on the site — appears here with the
	 * same shape and the same specific-target support.
	 *
	 * @since 6.7.3
	 *
	 * @param array $registered Rules registered so far, used to avoid collisions.
	 *
	 * @return array
	 */
	protected static function get_post_type_rules( $registered ) {
		$rules    = [];
		$excluded = self::get_excluded_post_types();

		foreach ( get_post_types( [ 'public' => true ], 'objects' ) as $slug => $post_type ) {
			if ( isset( $registered[ $slug ] ) || in_array( $slug, $excluded, true ) ) {
				continue;
			}

			// Library and builder post types are public so their previews resolve,
			// but they are not content anyone targets a header at.
			if ( ! $post_type->show_ui || ! $post_type->show_in_nav_menus ) {
				continue;
			}

			$singular = self::get_label( $post_type, 'singular_name', $slug );

			$rules[ $slug ] = [
				'label'           => $singular,
				'group'           => 'singular',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => $slug,
				'sub_source_type' => self::SOURCE_POST_TYPE,
				'supports'        => [ 'include', 'exclude' ],
				'callback'        => function ( $sub_id = 0 ) use ( $slug ) {
					return self::check_post_type_singular( $slug, $sub_id );
				},
			];

			if ( empty( $post_type->has_archive ) ) {
				continue;
			}

			$archive_name = $slug . '_archive';

			if ( isset( $registered[ $archive_name ] ) || isset( $rules[ $archive_name ] ) ) {
				continue;
			}

			$rules[ $archive_name ] = [
				/* translators: %s: post type plural label, e.g. Products. */
				'label'       => sprintf( __( '%s Archive', 'essential-addons-for-elementor-lite' ), self::get_label( $post_type, 'name', $slug ) ),
				'group'       => 'archive',
				'specificity' => self::SPECIFICITY_TYPE,
				'supports'    => [ 'include' ],
				'callback'    => function () use ( $slug ) {
					return self::check_post_type_archive( $slug );
				},
			];
		}

		return $rules;
	}

	/**
	 * One archive rule per public taxonomy, each targetable down to a single term.
	 *
	 * `category` and `post_tag` are skipped — they are core rules already, under
	 * the names existing saved conditions reference.
	 *
	 * @since 6.7.3
	 *
	 * @param array $registered Rules registered so far, used to avoid collisions.
	 *
	 * @return array
	 */
	protected static function get_taxonomy_rules( $registered ) {
		$rules    = [];
		$excluded = self::get_excluded_taxonomies();

		foreach ( get_taxonomies( [ 'public' => true ], 'objects' ) as $slug => $taxonomy ) {
			if ( in_array( $slug, $excluded, true ) ) {
				continue;
			}

			if ( ! $taxonomy->show_ui || ! $taxonomy->show_in_nav_menus ) {
				continue;
			}

			$name = $slug . '_archive';

			if ( isset( $registered[ $name ] ) || isset( $rules[ $name ] ) ) {
				continue;
			}

			$rules[ $name ] = [
				/* translators: %s: taxonomy singular label, e.g. Product Category. */
				'label'           => sprintf( __( '%s Archive', 'essential-addons-for-elementor-lite' ), self::get_label( $taxonomy, 'singular_name', $slug ) ),
				'group'           => 'archive',
				'specificity'     => self::SPECIFICITY_TYPE,
				'sub_source'      => $slug,
				'sub_source_type' => self::SOURCE_TAXONOMY,
				'supports'        => [ 'include' ],
				'callback'        => function ( $sub_id = 0 ) use ( $slug ) {
					return self::check_taxonomy_archive( $slug, $sub_id );
				},
			];
		}

		return $rules;
	}

	/**
	 * Post types the dynamic layer never offers.
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
	 * Taxonomies the dynamic layer never offers.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_excluded_taxonomies() {
		$excluded = [
			// Already registered as `category_archive` / `tag_archive`.
			'category',
			'post_tag',
			'post_format',
			'nav_menu',
			'link_category',
			'wp_theme',
			'wp_template_part_area',
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
	 * Fill in the optional keys and drop anything unusable.
	 *
	 * Runs over the filtered registry so a third-party rule only has to supply a
	 * label and a callback, and so a malformed one can never reach the matching
	 * engine.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $rules Rule definitions keyed by rule name.
	 *
	 * @return array
	 */
	protected static function normalize_rules( $rules ) {
		if ( ! is_array( $rules ) ) {
			return [];
		}

		$defaults = [
			'label'           => '',
			'group'           => 'general',
			'specificity'     => self::SPECIFICITY_TYPE,
			'sub_source'      => false,
			'sub_source_type' => self::SOURCE_POST_TYPE,
			'supports'        => [ 'include' ],
			'callback'        => null,
		];

		$normalized = [];

		foreach ( $rules as $name => $rule ) {
			$name = sanitize_key( $name );

			if ( '' === $name || ! is_array( $rule ) ) {
				continue;
			}

			$rule = array_merge( $defaults, $rule );

			// A rule the engine cannot evaluate, or the UI cannot label, is worse
			// than no rule at all.
			if ( '' === (string) $rule['label'] || ! is_callable( $rule['callback'] ) ) {
				continue;
			}

			$rule['name']        = $name;
			$rule['specificity'] = (int) $rule['specificity'];
			$rule['supports']    = array_values( array_intersect( (array) $rule['supports'], [ 'include', 'exclude' ] ) );

			if ( empty( $rule['supports'] ) ) {
				$rule['supports'] = [ 'include' ];
			}

			if ( ! in_array( $rule['sub_source_type'], [ self::SOURCE_POST_TYPE, self::SOURCE_TAXONOMY, self::SOURCE_USER ], true ) ) {
				$rule['sub_source_type'] = self::SOURCE_POST_TYPE;
			}

			$normalized[ $name ] = $rule;
		}

		return $normalized;
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
	 * A single rule definition.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Rule name.
	 *
	 * @return array|null
	 */
	public static function get_rule( $name ) {
		$rules = self::get_rules();

		return isset( $rules[ $name ] ) ? $rules[ $name ] : null;
	}

	/**
	 * Whether a rule exists and supports the given condition type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Rule name.
	 * @param string $type `include` or `exclude`.
	 *
	 * @return bool
	 */
	public static function is_valid( $name, $type = 'include' ) {
		$rule = self::get_rule( $name );

		if ( ! $rule ) {
			return false;
		}

		return in_array( $type, (array) $rule['supports'], true );
	}

	/**
	 * What kind of object a sub-source slug names.
	 *
	 * Lets the object search and label lookups stay generic: they ask the
	 * registry rather than carrying their own list of taxonomies.
	 *
	 * @since 6.7.3
	 *
	 * @param string $source Sub-source slug.
	 *
	 * @return string One of the SOURCE_* constants, or an empty string when no
	 *                registered rule declares that source.
	 */
	public static function get_sub_source_type( $source ) {
		if ( '' === (string) $source ) {
			return '';
		}

		foreach ( self::get_rules() as $rule ) {
			if ( ! empty( $rule['sub_source'] ) && $rule['sub_source'] === $source ) {
				return $rule['sub_source_type'];
			}
		}

		return '';
	}

	/**
	 * Evaluate a rule against the current request.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name   Rule name.
	 * @param int    $sub_id Optional object ID the rule is narrowed down to.
	 *
	 * @return bool
	 */
	public static function check( $name, $sub_id = 0 ) {
		$rule = self::get_rule( $name );

		if ( ! $rule || ! is_callable( $rule['callback'] ) ) {
			return false;
		}

		$matched = (bool) call_user_func( $rule['callback'], absint( $sub_id ) );

		/**
		 * Filters the outcome of a single condition rule.
		 *
		 * @since 6.7.3
		 *
		 * @param bool   $matched Whether the rule matched the current request.
		 * @param string $name    Rule name.
		 * @param int    $sub_id  Object ID the rule was narrowed down to.
		 */
		return (bool) apply_filters( 'eael/theme_builder/check_rule', $matched, $name, $sub_id );
	}

	/**
	 * Specificity score of a rule row.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name   Rule name.
	 * @param int    $sub_id Object ID the rule is narrowed down to.
	 *
	 * @return int
	 */
	public static function get_specificity( $name, $sub_id = 0 ) {
		$rule = self::get_rule( $name );

		if ( ! $rule ) {
			return 0;
		}

		$score = (int) $rule['specificity'];

		if ( $sub_id && ! empty( $rule['sub_source'] ) ) {
			$score += self::SPECIFICITY_OBJECT_BONUS;
		}

		return $score;
	}

	/**
	 * Rule definitions prepared for the admin UI (no callbacks, grouped labels).
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_rules_for_ui() {
		$prepared = [];

		foreach ( self::get_rules() as $name => $rule ) {
			// `sub_source_type` is deliberately not sent: the client passes the
			// sub_source slug back and the server resolves the kind itself, so
			// shipping it would be payload nothing reads.
			$prepared[ $name ] = [
				'name'       => $name,
				'label'      => $rule['label'],
				'group'      => $rule['group'],
				'sub_source' => $rule['sub_source'],
				'supports'   => array_values( (array) $rule['supports'] ),
			];
		}

		return $prepared;
	}

	/**
	 * Labels of the rule groups, in display order.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_groups() {
		$groups = [
			'general'  => __( 'General', 'essential-addons-for-elementor-lite' ),
			'archive'  => __( 'Archive', 'essential-addons-for-elementor-lite' ),
			'singular' => __( 'Singular', 'essential-addons-for-elementor-lite' ),
		];

		/**
		 * Filters the condition rule groups.
		 *
		 * Third-party rules using an unregistered group are still listed, just
		 * without an optgroup heading.
		 *
		 * @since 6.7.3
		 *
		 * @param array $groups Group labels keyed by group slug.
		 */
		return (array) apply_filters( 'eael/theme_builder/condition_groups', $groups );
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
	 * Generic callbacks, used by the dynamic layer.
	 * ------------------------------------------------------------------ */

	/**
	 * A single entry of any post type, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param string $post_type Post type slug.
	 * @param int    $sub_id    Post ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_post_type_singular( $post_type, $sub_id = 0 ) {
		return self::check_single_object( $post_type, $sub_id );
	}

	/**
	 * The archive of a post type.
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

		// WooCommerce's shop page is a page, not a `product` archive.
		return 'product' === $post_type && function_exists( 'is_shop' ) && is_shop();
	}

	/**
	 * A taxonomy term archive, optionally a specific term.
	 *
	 * @since 6.7.3
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @param int    $sub_id   Term ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_taxonomy_archive( $taxonomy, $sub_id = 0 ) {
		$sub_id = absint( $sub_id );

		if ( ! is_tax( $taxonomy, $sub_id ? $sub_id : '' ) ) {
			return false;
		}

		return true;
	}

	/* ---------------------------------------------------------------------
	 * Core rule callbacks.
	 * ------------------------------------------------------------------ */

	/**
	 * Always true — the catch-all rule.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_entire_site() {
		return true;
	}

	/**
	 * Site front page, whether it shows the blog or a static page.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_front_page() {
		return is_front_page();
	}

	/**
	 * The posts index.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_blog() {
		return is_home();
	}

	/**
	 * Search results.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_search() {
		return is_search();
	}

	/**
	 * The 404 view.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_not_found() {
		return is_404();
	}

	/**
	 * Any archive listing, including the posts index and the shop page.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_archive() {
		if ( is_archive() || is_home() ) {
			return true;
		}

		return self::is_woocommerce_active() && function_exists( 'is_shop' ) && is_shop();
	}

	/**
	 * Category archive, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id Term ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_category_archive( $sub_id = 0 ) {
		return $sub_id ? is_category( $sub_id ) : is_category();
	}

	/**
	 * Tag archive, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id Term ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_tag_archive( $sub_id = 0 ) {
		return $sub_id ? is_tag( $sub_id ) : is_tag();
	}

	/**
	 * Author archive, optionally a specific author.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id User ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_author_archive( $sub_id = 0 ) {
		return $sub_id ? is_author( $sub_id ) : is_author();
	}

	/**
	 * Date based archive.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_date_archive() {
		return is_date();
	}

	/**
	 * Any single post, page or custom post type entry.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function check_singular() {
		return is_singular();
	}

	/**
	 * A blog post, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id Post ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_post( $sub_id = 0 ) {
		return self::check_single_object( 'post', $sub_id );
	}

	/**
	 * A page, optionally a specific one.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id Post ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_page( $sub_id = 0 ) {
		return self::check_single_object( 'page', $sub_id );
	}

	/**
	 * A WooCommerce product, optionally a specific one.
	 *
	 * Kept for backward compatibility — the `product` rule is now produced by the
	 * dynamic post type layer, but this callback is part of the public surface.
	 *
	 * @since 6.7.3
	 *
	 * @param int $sub_id Product ID or 0 for any.
	 *
	 * @return bool
	 */
	public static function check_product( $sub_id = 0 ) {
		return self::check_single_object( 'product', $sub_id );
	}

	/**
	 * Shared helper for the singular object rules.
	 *
	 * @since 6.7.3
	 *
	 * @param string $post_type Post type slug.
	 * @param int    $sub_id    Post ID or 0 for any.
	 *
	 * @return bool
	 */
	private static function check_single_object( $post_type, $sub_id = 0 ) {
		if ( ! is_singular( $post_type ) ) {
			return false;
		}

		if ( ! $sub_id ) {
			return true;
		}

		return get_queried_object_id() === absint( $sub_id );
	}
}

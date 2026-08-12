<?php
/**
 * Theme Builder custom post type.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Core;

use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Registers the `ea_theme_builder` post type and its metadata.
 *
 * The post type is intentionally not public: templates are fragments, not
 * documents. It stays `publicly_queryable` so the Elementor editor preview
 * iframe can load it, and `template_redirect` (see the Elementor integration)
 * blocks direct visits from anyone who cannot edit the template.
 *
 * @since 6.7.3
 */
class Post_Type {

	/**
	 * Post type slug.
	 */
	const CPT = 'ea_theme_builder';

	/**
	 * Elementor document type stored in `_elementor_template_type`.
	 *
	 * Declared here — rather than only on the document class — so nothing has to
	 * autoload an Elementor subclass just to read the identifier.
	 */
	const DOCUMENT_TYPE = 'ea-theme-builder';

	/**
	 * Elementor's blank canvas page template — the default layout for new templates.
	 *
	 * Mirrors `Elementor\Modules\PageTemplates\Module::TEMPLATE_CANVAS`, declared
	 * here so nothing has to load an Elementor class just to read the slug.
	 */
	const PAGE_TEMPLATE_CANVAS = 'elementor_canvas';

	/**
	 * Template type — `header`, `footer`, …
	 */
	const META_TYPE = '_ea_template_type';

	/**
	 * Serialized display conditions.
	 */
	const META_CONDITIONS = '_ea_template_conditions';

	/**
	 * Tie-breaker used when two templates match the same request equally well.
	 */
	const META_PRIORITY = '_ea_template_priority';

	/**
	 * Lowest priority a template may be given.
	 */
	const PRIORITY_MIN = 1;

	/**
	 * Highest priority a template may be given.
	 */
	const PRIORITY_MAX = 100;

	/**
	 * Priority applied to a template that has none.
	 */
	const PRIORITY_DEFAULT = 10;

	/**
	 * Mirror of `post_status`, kept for fast meta-only lookups.
	 */
	const META_STATUS = '_ea_template_status';

	/**
	 * Builder that owns the template — `elementor` in v1.
	 */
	const META_PLATFORM = '_ea_template_platform';

	/**
	 * Whether the template participates in condition matching — `yes` / `no`.
	 */
	const META_ACTIVE = '_ea_template_active';

	/**
	 * Wire up registration hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		// Priority 0: the post type has to exist before Elementor maps documents
		// to post types on `init`.
		add_action( 'init', [ $this, 'register_post_type' ], 0 );
		add_action( 'init', [ $this, 'register_meta' ], 1 );

		// Keep the mirrored status meta in sync and drop stale caches.
		add_action( 'save_post_' . self::CPT, [ $this, 'sync_status_meta' ], 10, 2 );
		add_action( 'transition_post_status', [ $this, 'on_transition_post_status' ], 10, 3 );
		add_action( 'deleted_post', [ $this, 'on_deleted_post' ] );

		// Core sitemaps must not advertise template fragments.
		add_filter( 'wp_sitemaps_post_types', [ $this, 'exclude_from_sitemap' ] );
	}

	/**
	 * Register the post type.
	 *
	 * @since 6.7.3
	 */
	public function register_post_type() {
		$labels = [
			'name'               => _x( 'Theme Builder', 'Post Type General Name', 'essential-addons-for-elementor-lite' ),
			'singular_name'      => _x( 'Template', 'Post Type Singular Name', 'essential-addons-for-elementor-lite' ),
			'menu_name'          => __( 'Theme Builder', 'essential-addons-for-elementor-lite' ),
			'name_admin_bar'     => __( 'Template', 'essential-addons-for-elementor-lite' ),
			'add_new'            => __( 'Add New Template', 'essential-addons-for-elementor-lite' ),
			'add_new_item'       => __( 'Add New Template', 'essential-addons-for-elementor-lite' ),
			'new_item'           => __( 'New Template', 'essential-addons-for-elementor-lite' ),
			'edit_item'          => __( 'Edit Template', 'essential-addons-for-elementor-lite' ),
			'view_item'          => __( 'View Template', 'essential-addons-for-elementor-lite' ),
			'all_items'          => __( 'Templates', 'essential-addons-for-elementor-lite' ),
			'search_items'       => __( 'Search Template', 'essential-addons-for-elementor-lite' ),
			'not_found'          => __( 'No templates found.', 'essential-addons-for-elementor-lite' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'essential-addons-for-elementor-lite' ),
		];

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Essential Addons Theme Builder templates.', 'essential-addons-for-elementor-lite' ),
			'public'              => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'menu_position'       => 58.7,
			'supports'            => [ 'title', 'author', 'revisions', 'elementor' ],
		];

		/**
		 * Filters the Theme Builder post type arguments.
		 *
		 * @since 6.7.3
		 *
		 * @param array $args Arguments passed to register_post_type().
		 */
		$args = apply_filters( 'eael/theme_builder/post_type_args', $args );

		register_post_type( self::CPT, $args );
	}

	/**
	 * Register the template metadata with sanitization callbacks.
	 *
	 * Registering meta centralises sanitization: anything writing through
	 * `update_post_meta()` gets the same treatment as the AJAX endpoints.
	 *
	 * @since 6.7.3
	 */
	public function register_meta() {
		$auth_callback = function ( $allowed, $meta_key, $post_id ) {
			return current_user_can( 'edit_post', $post_id );
		};

		register_post_meta(
			self::CPT,
			self::META_TYPE,
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_key',
				'auth_callback'     => $auth_callback,
			]
		);

		register_post_meta(
			self::CPT,
			self::META_PLATFORM,
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_key',
				'auth_callback'     => $auth_callback,
			]
		);

		register_post_meta(
			self::CPT,
			self::META_STATUS,
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_key',
				'auth_callback'     => $auth_callback,
			]
		);

		register_post_meta(
			self::CPT,
			self::META_ACTIVE,
			[
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => [ $this, 'sanitize_active' ],
				'auth_callback'     => $auth_callback,
			]
		);

		register_post_meta(
			self::CPT,
			self::META_PRIORITY,
			[
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => false,
				'sanitize_callback' => [ $this, 'sanitize_priority' ],
				'auth_callback'     => $auth_callback,
			]
		);

		// Conditions are an array of rule rows; sanitization lives in the
		// condition manager so the rule registry stays the single source of truth.
		register_post_meta(
			self::CPT,
			self::META_CONDITIONS,
			[
				'type'          => 'array',
				'single'        => true,
				'show_in_rest'  => false,
				'auth_callback' => $auth_callback,
			]
		);
	}

	/**
	 * Sanitize the active flag.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string `yes` or `no`.
	 */
	public function sanitize_active( $value ) {
		return ( 'no' === $value || false === $value || '0' === $value ) ? 'no' : 'yes';
	}

	/**
	 * Sanitize the priority value into a sane range.
	 *
	 * The last line of defence for anything writing the meta directly — imports,
	 * WP-CLI, third-party add-ons. Input coming from the Quick Edit UI is
	 * range-checked and rejected before it reaches here (see `Ajax::quick_edit()`),
	 * so a typed-in value is never silently changed behind the user's back.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return int
	 */
	public function sanitize_priority( $value ) {
		$value = is_numeric( $value ) ? (int) $value : self::PRIORITY_DEFAULT;

		return max( self::PRIORITY_MIN, min( self::PRIORITY_MAX, $value ) );
	}

	/**
	 * Mirror `post_status` into meta and flush the template cache.
	 *
	 * @since 6.7.3
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public function sync_status_meta( $post_id, $post ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, self::META_STATUS, sanitize_key( $post->post_status ) );

		Template_Cache::flush();
	}

	/**
	 * Flush the template cache whenever a template changes status.
	 *
	 * @since 6.7.3
	 *
	 * @param string   $new_status New status.
	 * @param string   $old_status Old status.
	 * @param \WP_Post $post       Post object.
	 */
	public function on_transition_post_status( $new_status, $old_status, $post ) {
		if ( ! $post instanceof \WP_Post || self::CPT !== $post->post_type ) {
			return;
		}

		if ( $new_status === $old_status ) {
			return;
		}

		update_post_meta( $post->ID, self::META_STATUS, sanitize_key( $new_status ) );

		Template_Cache::flush();
	}

	/**
	 * Flush the template cache when a template is deleted for good.
	 *
	 * @since 6.7.3
	 *
	 * @param int $post_id Post ID.
	 */
	public function on_deleted_post( $post_id ) {
		if ( self::CPT !== get_post_type( $post_id ) ) {
			return;
		}

		Template_Cache::flush();
	}

	/**
	 * Remove the post type from the core sitemap provider.
	 *
	 * @since 6.7.3
	 *
	 * @param array $post_types Post type objects keyed by name.
	 *
	 * @return array
	 */
	public function exclude_from_sitemap( $post_types ) {
		unset( $post_types[ self::CPT ] );

		return $post_types;
	}

	/**
	 * Default metadata applied to a freshly created template.
	 *
	 * Conditions start **empty**. A seeded "Entire Site" row would be a decision
	 * the user never made, sitting pre-selected in the modal that opens when they
	 * publish — and "everywhere" is the widest answer there is. They pick one.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return array
	 */
	public static function default_meta( $type ) {
		return [
			self::META_TYPE       => $type,
			self::META_CONDITIONS => [],
			self::META_PRIORITY   => self::PRIORITY_DEFAULT,
			self::META_STATUS     => 'draft',
			self::META_PLATFORM   => 'elementor',
			self::META_ACTIVE     => 'yes',
		];
	}

	/**
	 * Edit URL of a template inside the Elementor editor.
	 *
	 * @since 6.7.3
	 *
	 * @param int $post_id Template ID.
	 *
	 * @return string
	 */
	public static function get_elementor_edit_url( $post_id ) {
		$post_id = absint( $post_id );

		if ( ! $post_id ) {
			return '';
		}

		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->documents ) ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post_id );

			if ( $document && method_exists( $document, 'get_edit_url' ) ) {
				return $document->get_edit_url();
			}
		}

		return admin_url( 'post.php?post=' . $post_id . '&action=elementor' );
	}

	/**
	 * Page layouts a template can be rendered with, as `value => label`.
	 *
	 * Elementor injects its three pseudo-templates into `get_page_templates()`
	 * keyed the opposite way round from the theme's own files, so they are added
	 * explicitly here and any non-`.php` entry from the filtered list is skipped
	 * to avoid listing them twice.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_page_template_options() {
		$options = [ 'default' => __( 'Default', 'essential-addons-for-elementor-lite' ) ];

		if ( class_exists( '\Elementor\Modules\PageTemplates\Module' ) ) {
			$options[ \Elementor\Modules\PageTemplates\Module::TEMPLATE_CANVAS ]        = __( 'Elementor Canvas', 'essential-addons-for-elementor-lite' );
			$options[ \Elementor\Modules\PageTemplates\Module::TEMPLATE_HEADER_FOOTER ] = __( 'Elementor Full Width', 'essential-addons-for-elementor-lite' );
			$options[ \Elementor\Modules\PageTemplates\Module::TEMPLATE_THEME ]         = __( 'Theme', 'essential-addons-for-elementor-lite' );
		}

		if ( ! function_exists( 'get_page_templates' ) ) {
			require_once ABSPATH . 'wp-admin/includes/theme.php';
		}

		foreach ( get_page_templates( null, self::CPT ) as $label => $file ) {
			if ( '.php' !== substr( (string) $file, -4 ) ) {
				continue;
			}

			$options[ $file ] = $label;
		}

		/**
		 * Filters the page layouts offered for Theme Builder templates.
		 *
		 * @since 6.7.3
		 *
		 * @param array $options Layouts as `value => label`.
		 */
		return apply_filters( 'eael/theme_builder/page_template_options', $options );
	}

	/**
	 * Whether the current user may manage Theme Builder templates.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function current_user_can_manage() {
		return current_user_can( Theme_Builder::capability() );
	}
}

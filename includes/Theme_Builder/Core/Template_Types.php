<?php
/**
 * Registry of Theme Builder template types.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Extensible registry for template types.
 *
 * v1 ships `header` and `footer`. A new type only needs to be registered here to
 * appear in the dashboard tabs, in the "Add New Template" modal and in the
 * condition engine:
 *
 *     add_action( 'eael/theme_builder/register_types', function ( $types ) {
 *         $types->register_type( 'archive', [
 *             'label'       => __( 'Archive', 'my-textdomain' ),
 *             'wrapper_tag' => 'div',
 *         ] );
 *     } );
 *
 * @since 6.7.3
 */
class Template_Types {

	/**
	 * Singleton instance.
	 *
	 * @var Template_Types|null
	 */
	private static $instance = null;

	/**
	 * Registered types keyed by slug.
	 *
	 * @var array
	 */
	private $types = [];

	/**
	 * Whether the default types have been registered yet.
	 *
	 * @var bool
	 */
	private $initialized = false;

	/**
	 * Singleton accessor.
	 *
	 * @since 6.7.3
	 *
	 * @return Template_Types
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a template type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Type slug — stored in the `_ea_template_type` meta.
	 * @param array  $args {
	 *     Type arguments.
	 *
	 *     @type string $label       Singular human readable label.
	 *     @type string $plural      Plural human readable label.
	 *     @type string $wrapper_tag HTML tag wrapping the rendered output.
	 *     @type string $location    Render location: `header`, `footer` or `content`.
	 *     @type bool   $single      Whether only one template of this type renders per request.
	 *     @type int    $menu_order  Ordering weight in the dashboard tabs.
	 * }
	 *
	 * @return bool True when registered, false when the slug is invalid or taken.
	 */
	public function register_type( $slug, $args = [] ) {
		$slug = sanitize_key( $slug );

		if ( '' === $slug || isset( $this->types[ $slug ] ) ) {
			return false;
		}

		$defaults = [
			'label'       => ucfirst( $slug ),
			'plural'      => ucfirst( $slug ),
			'wrapper_tag' => 'div',
			'location'    => 'content',
			'single'      => true,
			'menu_order'  => 10,
		];

		$args = wp_parse_args( $args, $defaults );

		// Only a small, known set of tags may wrap rendered output — the value is
		// printed unescaped as a tag name.
		$allowed_tags = [ 'div', 'header', 'footer', 'section', 'aside', 'nav', 'main' ];

		if ( ! in_array( $args['wrapper_tag'], $allowed_tags, true ) ) {
			$args['wrapper_tag'] = 'div';
		}

		$args['slug']            = $slug;
		$args['single']          = (bool) $args['single'];
		$args['menu_order']      = (int) $args['menu_order'];
		$this->types[ $slug ]    = $args;

		return true;
	}

	/**
	 * Unregister a template type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Type slug.
	 */
	public function unregister_type( $slug ) {
		unset( $this->types[ sanitize_key( $slug ) ] );
	}

	/**
	 * All registered types, ordered by `menu_order`.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function get_types() {
		$this->maybe_register_defaults();

		$types = $this->types;

		uasort(
			$types,
			function ( $a, $b ) {
				if ( $a['menu_order'] === $b['menu_order'] ) {
					return strcmp( $a['slug'], $b['slug'] );
				}

				return ( $a['menu_order'] < $b['menu_order'] ) ? -1 : 1;
			}
		);

		return $types;
	}

	/**
	 * A single registered type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Type slug.
	 *
	 * @return array|null
	 */
	public function get_type( $slug ) {
		$types = $this->get_types();
		$slug  = sanitize_key( $slug );

		return isset( $types[ $slug ] ) ? $types[ $slug ] : null;
	}

	/**
	 * Whether a type slug is registered.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Type slug.
	 *
	 * @return bool
	 */
	public function type_exists( $slug ) {
		return null !== $this->get_type( $slug );
	}

	/**
	 * Label for a type slug, falling back to the slug itself.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Type slug.
	 *
	 * @return string
	 */
	public function get_label( $slug ) {
		$type = $this->get_type( $slug );

		return $type ? $type['label'] : ucfirst( (string) $slug );
	}

	/**
	 * Type slugs rendered at a given location.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location `header`, `footer` or `content`.
	 *
	 * @return array
	 */
	public function get_types_by_location( $location ) {
		$matched = [];

		foreach ( $this->get_types() as $slug => $type ) {
			if ( $type['location'] === $location ) {
				$matched[ $slug ] = $type;
			}
		}

		return $matched;
	}

	/**
	 * Register the built-in types once, late enough for translations to be loaded.
	 *
	 * @since 6.7.3
	 */
	private function maybe_register_defaults() {
		if ( $this->initialized ) {
			return;
		}

		$this->initialized = true;

		$this->register_type(
			'header',
			[
				'label'       => __( 'Header', 'essential-addons-for-elementor-lite' ),
				'plural'      => __( 'Headers', 'essential-addons-for-elementor-lite' ),
				'wrapper_tag' => 'header',
				'location'    => 'header',
				'menu_order'  => 10,
			]
		);

		$this->register_type(
			'footer',
			[
				'label'       => __( 'Footer', 'essential-addons-for-elementor-lite' ),
				'plural'      => __( 'Footers', 'essential-addons-for-elementor-lite' ),
				'wrapper_tag' => 'footer',
				'location'    => 'footer',
				'menu_order'  => 20,
			]
		);

		/**
		 * Fires after the built-in template types are registered.
		 *
		 * The canonical extension point for additional template types.
		 *
		 * @since 6.7.3
		 *
		 * @param Template_Types $registry The type registry.
		 */
		do_action( 'eael/theme_builder/register_types', $this );
	}
}

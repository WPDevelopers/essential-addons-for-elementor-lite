<?php
/**
 * Elementor integration for the Theme Builder.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Integrations;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Cache;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Wires Theme Builder templates into the Elementor editor.
 *
 * @since 6.7.3
 */
class Elementor_Integration {

	/**
	 * Register the Elementor hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		add_action( 'elementor/documents/register', [ $this, 'register_document' ] );

		// Templates are fragments: render them on a blank canvas, never inside the
		// theme's single template.
		add_filter( 'template_include', [ $this, 'template_include' ], 999 );
		add_action( 'template_redirect', [ $this, 'restrict_direct_access' ], 1 );

		// Any editor save can change the layout, so drop the resolved-template caches.
		add_action( 'elementor/editor/after_save', [ $this, 'flush_cache' ], 10, 2 );

		add_filter( 'body_class', [ $this, 'body_class' ] );
	}

	/**
	 * Register the Theme Builder document type.
	 *
	 * @since 6.7.3
	 *
	 * @param \Elementor\Core\Documents_Manager $documents_manager Elementor's document manager.
	 */
	public function register_document( $documents_manager ) {
		// Guard against Elementor internals moving: without the document type the
		// module still works, Elementor just falls back to its generic post document.
		if ( ! class_exists( '\Elementor\Core\DocumentTypes\Post' ) ) {
			return;
		}

		$documents_manager->register_document_type( Document::TYPE, Document::class );
	}

	/**
	 * Render a template on Elementor's blank canvas, unless the user chose otherwise.
	 *
	 * Elementor's page-templates module resolves the "Page Layout" setting at
	 * priority 11. This filter runs after it and only steps in when no explicit
	 * layout is set, so picking "Elementor Full Width" or "Theme" in Page Settings
	 * keeps working.
	 *
	 * @since 6.7.3
	 *
	 * @param string $template Template path resolved by WordPress.
	 *
	 * @return string
	 */
	public function template_include( $template ) {
		if ( ! is_singular( Post_Type::CPT ) ) {
			return $template;
		}

		$post_id       = get_queried_object_id();
		$page_template = $post_id ? get_post_meta( $post_id, '_wp_page_template', true ) : '';

		if ( $page_template && 'default' !== $page_template ) {
			return $template;
		}

		return $this->get_canvas_template( $template );
	}

	/**
	 * Path of the blank canvas template.
	 *
	 * @since 6.7.3
	 *
	 * @param string $fallback Template to keep when no canvas is available.
	 *
	 * @return string
	 */
	private function get_canvas_template( $fallback ) {
		if ( defined( 'ELEMENTOR_PATH' ) ) {
			$canvas = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';

			if ( file_exists( $canvas ) ) {
				return $canvas;
			}
		}

		$canvas = Theme_Builder::path() . 'Templates/canvas.php';

		return file_exists( $canvas ) ? $canvas : $fallback;
	}

	/**
	 * Block direct front-end access to a template for everyone who cannot edit it.
	 *
	 * Templates are publicly queryable so the editor preview iframe can load them;
	 * that must not turn them into publicly reachable URLs.
	 *
	 * @since 6.7.3
	 */
	public function restrict_direct_access() {
		if ( ! is_singular( Post_Type::CPT ) ) {
			return;
		}

		$post_id = get_queried_object_id();

		if ( $post_id && current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}

	/**
	 * Flush the template caches after an Elementor save.
	 *
	 * @since 6.7.3
	 *
	 * @param int   $post_id Saved post ID.
	 * @param array $data    Saved editor data.
	 */
	public function flush_cache( $post_id, $data = [] ) {
		if ( Post_Type::CPT !== get_post_type( $post_id ) ) {
			return;
		}

		Template_Cache::flush();
	}

	/**
	 * Add an identifying body class while editing a template.
	 *
	 * @since 6.7.3
	 *
	 * @param array $classes Body classes.
	 *
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( is_singular( Post_Type::CPT ) ) {
			$classes[] = 'eael-theme-builder-template';
			$classes[] = 'eael-theme-builder-' . sanitize_html_class( (string) get_post_meta( get_queried_object_id(), Post_Type::META_TYPE, true ) );
		}

		return $classes;
	}
}

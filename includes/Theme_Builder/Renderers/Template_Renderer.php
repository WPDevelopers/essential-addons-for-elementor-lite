<?php
/**
 * Template renderer.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Renderers;

use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Turns a resolved template into markup.
 *
 * Rendering is idempotent per type: once a header has been printed, a second
 * call is a no-op. That keeps a theme calling `get_header()` twice — or a
 * plugin re-running the hook — from duplicating the header on the page.
 *
 * @since 6.7.3
 */
class Template_Renderer {

	/**
	 * Types already rendered during this request.
	 *
	 * @var array
	 */
	private static $rendered = [];

	/**
	 * ID of the template that should render for a type on this request.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return int Template ID, or 0 when nothing matches.
	 */
	public static function get_template_id( $type ) {
		return Conditions_Manager::instance()->get_active_template_id( $type );
	}

	/**
	 * Whether a template of the given type will render on this request.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return bool
	 */
	public static function has_template( $type ) {
		return (bool) self::get_template_id( $type );
	}

	/**
	 * Whether a type has already been printed.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return bool
	 */
	public static function is_rendered( $type ) {
		return ! empty( self::$rendered[ $type ] );
	}

	/**
	 * Print the template for a type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 */
	public static function render( $type ) {
		// The markup is produced by Elementor's own renderer, which escapes each
		// widget's output; escaping again here would corrupt valid HTML.
		echo self::get_html( $type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Markup of the template for a type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 *
	 * @return string Empty string when nothing matches or the type was rendered already.
	 */
	public static function get_html( $type ) {
		$type = sanitize_key( $type );

		$type_args = Template_Types::instance()->get_type( $type );

		if ( ! $type_args ) {
			return '';
		}

		if ( ! empty( $type_args['single'] ) && self::is_rendered( $type ) ) {
			return '';
		}

		$template_id = self::get_template_id( $type );

		if ( ! $template_id ) {
			return '';
		}

		$content = self::get_template_content( $template_id );

		if ( '' === trim( (string) $content ) ) {
			return '';
		}

		self::$rendered[ $type ] = $template_id;

		$tag = $type_args['wrapper_tag'];

		$classes = [
			'eael-theme-builder',
			'eael-theme-builder--' . $type,
			'eael-theme-builder-tpl-' . $template_id,
		];

		/**
		 * Filters the CSS classes of a rendered Theme Builder template.
		 *
		 * @since 6.7.3
		 *
		 * @param array  $classes     CSS classes.
		 * @param string $type        Template type slug.
		 * @param int    $template_id Template ID.
		 */
		$classes = apply_filters( 'eael/theme_builder/wrapper_classes', $classes, $type, $template_id );
		$classes = array_map( 'sanitize_html_class', (array) $classes );

		$html = sprintf(
			'<%1$s class="%2$s" data-eael-template-id="%3$d">%4$s</%1$s>',
			$tag,
			esc_attr( implode( ' ', $classes ) ),
			$template_id,
			$content
		);

		/**
		 * Filters the full markup of a rendered Theme Builder template.
		 *
		 * @since 6.7.3
		 *
		 * @param string $html        Rendered markup.
		 * @param string $type        Template type slug.
		 * @param int    $template_id Template ID.
		 */
		return apply_filters( 'eael/theme_builder/rendered_html', $html, $type, $template_id );
	}

	/**
	 * Elementor content of a template.
	 *
	 * @since 6.7.3
	 *
	 * @param int $template_id Template ID.
	 *
	 * @return string
	 */
	public static function get_template_content( $template_id ) {
		$template_id = absint( $template_id );

		if ( ! $template_id || ! class_exists( '\Elementor\Plugin' ) ) {
			return '';
		}

		$frontend = \Elementor\Plugin::$instance->frontend;

		if ( ! $frontend || ! method_exists( $frontend, 'get_builder_content_for_display' ) ) {
			return '';
		}

		return $frontend->get_builder_content_for_display( $template_id );
	}

	/**
	 * Reset the rendered-types tracker.
	 *
	 * Only useful for tests and for long running processes that render several
	 * simulated requests in a row.
	 *
	 * @since 6.7.3
	 */
	public static function reset() {
		self::$rendered = [];
	}
}

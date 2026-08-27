<?php
/**
 * Elementor document type for Theme Builder templates.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Integrations;

use Elementor\Core\DocumentTypes\Post as Post_Document;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Teaches Elementor how to edit and render a Theme Builder template.
 *
 * The class is only ever loaded from `Elementor_Integration::register_document()`,
 * which runs on `elementor/documents/register` and guards on the parent class
 * existing — so the file is never parsed on a site without Elementor.
 *
 * @since 6.7.3
 */
class Document extends Post_Document {

	/**
	 * Document type identifier, stored in `_elementor_template_type`.
	 */
	const TYPE = Post_Type::DOCUMENT_TYPE;

	/**
	 * Document properties.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['cpt']         = [ Post_Type::CPT ];
		$properties['support_kit'] = true;

		// Both properties gate the "Page Layout" select in Page Settings:
		// `support_page_layout` decides whether the control is injected at all
		// (Page_Templates_Module::action_register_template_control), and
		// `support_wp_page_templates` decides whether the chosen layout is honoured
		// when the template is rendered on its own (…::template_include).
		// Users need the control to preview a header on a canvas rather than inside
		// the theme's single template, so both stay on.
		$properties['support_page_layout']       = true;
		$properties['support_wp_page_templates'] = true;

		$properties['show_in_finder']    = false;
		$properties['show_on_admin_bar'] = false;
		// Theme Builder templates are managed from their own dashboard; they must
		// not leak into Elementor's own template library screen.
		$properties['register_type']           = false;
		$properties['admin_tab_group']         = '';

		return $properties;
	}

	/**
	 * Document name.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_name() {
		return self::TYPE;
	}

	/**
	 * Document title shown in the editor.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function get_title() {
		return __( 'Theme Builder Template', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * CSS selector the generated stylesheet is scoped to.
	 *
	 * A Theme Builder template renders on pages other than its own, so the
	 * default `body.elementor-page-{id}` scope of a post document would never
	 * match. `Frontend::get_builder_content_for_display()` wraps the output in
	 * `.elementor-{id}`, which is the scope used here.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_css_wrapper_selector() {
		return '.elementor-' . $this->get_main_id();
	}
}

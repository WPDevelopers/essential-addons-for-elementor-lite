<?php
/**
 * Theme Builder inside the Elementor editor.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Integrations;

use Essential_Addons_Elementor\Theme_Builder\Admin\Admin;
use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Models\Template;
use Essential_Addons_Elementor\Theme_Builder\Presets\Preset_Library;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Mounts the condition builder in the editor and gates publishing behind it.
 *
 * Display conditions used to be step 2 of the creation wizard, asked before the
 * user had built anything — at a point where the only honest answer is often
 * "not sure yet". They are asked at the moment they matter instead: the click
 * that puts the header or footer on the live site.
 *
 * The PHP side only loads the app and hands it the template it is editing; the
 * publish gate itself is a data-dependency hook on `document/save/publish`,
 * registered by the React bundle.
 *
 * @since 6.7.3
 */
class Editor {

	/**
	 * Register the editor hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Load the condition builder when the edited document is a template.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_assets() {
		$template = $this->get_edited_template();

		if ( ! $template ) {
			return;
		}

		Admin::enqueue_app( [], Admin::get_script_data( [ 'editor' => $this->get_editor_data( $template ) ] ) );
	}

	/**
	 * The template open in the editor, if the current user may edit it.
	 *
	 * @since 6.7.3
	 *
	 * @return Template|null
	 */
	private function get_edited_template() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! Theme_Builder::is_enabled() ) {
			return null;
		}

		$post_id = \Elementor\Plugin::$instance->editor->get_post_id();

		if ( ! $post_id || Post_Type::CPT !== get_post_type( $post_id ) ) {
			return null;
		}

		if ( ! current_user_can( Theme_Builder::capability() ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return null;
		}

		return Template::get( $post_id );
	}

	/**
	 * Editor context handed to the React app.
	 *
	 * @since 6.7.3
	 *
	 * @param Template $template Template being edited.
	 *
	 * @return array
	 */
	private function get_editor_data( Template $template ) {
		return [
			'templateId' => $template->get_id(),
			'type'       => $template->get_type(),
			'typeLabel'  => $template->get_type_label(),
			'title'      => $template->get_title(),
			'status'     => $template->get_status(),
			'conditions' => Conditions_Manager::instance()->decorate_conditions( $template->get_conditions() ),
			'presets'    => Preset_Library::get_presets_for_ui(),
			'icon'       => EAEL_PLUGIN_URL . 'assets/admin/images/ea-icon-new-white.svg',
		];
	}
}

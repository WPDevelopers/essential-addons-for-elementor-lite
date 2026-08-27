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
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Mounts the Theme Builder's editor UI: the preset picker and the condition
 * builder that gates publishing.
 *
 * The two have different reach. Presets are sections like any other, so the
 * button that inserts them belongs on every document opened in Elementor —
 * a header preset is as useful at the top of a landing page as it is in a
 * template. Conditions only mean anything for a Theme Builder template, so
 * everything about them is handed over only when one is what is being edited,
 * and the app leaves that UI unregistered otherwise.
 *
 * Display conditions used to be step 2 of the creation wizard, asked before the
 * user had built anything — at a point where the only honest answer is often
 * "not sure yet". They are asked at the moment they matter instead: the click
 * that puts the header or footer on the live site.
 *
 * The PHP side only loads the app and hands it the document it is editing; the
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
		// Late on purpose, and it must stay late.
		//
		// Elementor fires `elementor:init` once, from a promise continuation that
		// can settle before the footer's later script tags have run. An
		// integration that registers its editor UI directly — as this one does —
		// does not care. One that waits for that event, as several do, is only
		// safe while its script still runs before the event fires.
		//
		// This plugin's directory sorts ahead of most, so at the default priority
		// its bundle is enqueued first and prints ahead of those scripts, pushing
		// them past the point of no return. Running last keeps our script tag
		// behind theirs and leaves that timing exactly as it was.
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_assets' ], 100 );
	}

	/**
	 * Load the editor app for any document the user may edit.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_assets() {
		$post_id = $this->get_edited_post_id();

		if ( ! $post_id ) {
			return;
		}

		// Declared rather than assumed: the bundle reaches for `elementor`,
		// `Marionette` and `$e` as soon as it runs, and an editor script with no
		// dependencies is only ordered after them by accident of the queue.
		Admin::enqueue_app( [ 'elementor-editor' ], Admin::get_script_data( [ 'editor' => $this->get_editor_data( $post_id ) ] ) );
	}

	/**
	 * The document open in the editor, if the current user may edit it here.
	 *
	 * Any Elementor document qualifies — the preset button is not template-only.
	 * The Theme Builder capability still applies to all of it: presets are part
	 * of the module, and the endpoint that serves their content asks for the same
	 * capability, so a button shown past this point would only fail on click.
	 *
	 * @since 6.7.3
	 *
	 * @return int Post ID, or 0 when the app should not load.
	 */
	private function get_edited_post_id() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! Theme_Builder::is_enabled() ) {
			return 0;
		}

		$post_id = \Elementor\Plugin::$instance->editor->get_post_id();

		if ( ! $post_id ) {
			return 0;
		}

		if ( ! current_user_can( Theme_Builder::capability() ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return 0;
		}

		return (int) $post_id;
	}

	/**
	 * The Theme Builder template open in the editor, if that is what it is.
	 *
	 * @since 6.7.3
	 *
	 * @param int $post_id Document being edited.
	 *
	 * @return Template|null
	 */
	private function get_edited_template( $post_id ) {
		if ( Post_Type::CPT !== get_post_type( $post_id ) ) {
			return null;
		}

		return Template::get( $post_id );
	}

	/**
	 * Whether this editor load should open the preset picker, once.
	 *
	 * The creation flow leaves a flag on the template it just made; reading it
	 * here spends it. A reload is a second opinion, not the same arrival, and a
	 * picker that reopened on every load of a template the user had already
	 * decided to build by hand would be a dialog they cannot get rid of.
	 *
	 * @since 6.7.3
	 *
	 * @param int $template_id Template being edited.
	 *
	 * @return bool
	 */
	private function take_preset_offer( $template_id ) {
		if ( ! get_post_meta( $template_id, Post_Type::META_OFFER_PRESETS, true ) ) {
			return false;
		}

		delete_post_meta( $template_id, Post_Type::META_OFFER_PRESETS );

		return true;
	}

	/**
	 * Editor context handed to the React app.
	 *
	 * The template half is absent on an ordinary page or post, which is how the
	 * app tells the two apart: it registers the preset button either way, and the
	 * publish gate and the conditions menu item only when there is a template ID
	 * for them to act on.
	 *
	 * @since 6.7.3
	 *
	 * @param int $post_id Document being edited.
	 *
	 * @return array
	 */
	private function get_editor_data( $post_id ) {
		$data = [
			'documentId' => $post_id,
		];

		$template = $this->get_edited_template( $post_id );

		if ( ! $template ) {
			return $data;
		}

		return array_merge(
			$data,
			[
				'offerPresets' => $this->take_preset_offer( $template->get_id() ),
				'templateId' => $template->get_id(),
				'type'       => $template->get_type(),
				'typeLabel'  => $template->get_type_label(),
				'title'      => $template->get_title(),
				'status'     => $template->get_status(),
				'conditions' => Conditions_Manager::instance()->decorate_conditions( $template->get_conditions() ),
			]
		);
	}
}

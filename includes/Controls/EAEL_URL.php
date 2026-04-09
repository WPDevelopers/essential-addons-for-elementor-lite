<?php
/**
 * Custom Elementor control: EAEL_URL
 *
 * Extends the built-in URL control with an AJAX-powered Select2 search that
 * lets editors pick any published Post, Page, or Product (WooCommerce) as the
 * link target. The stored value format is identical to the native URL control
 * ( { url, is_external, nofollow, custom_attributes } ), so existing widget
 * code that reads `$settings['my_control']['url']` keeps working without
 * any backend changes.
 *
 * Usage in a widget's register_controls():
 *
 *   $this->add_control( 'redirect_url', [
 *       'type'        => 'eael-url',
 *       'label'       => __( 'Redirect URL', 'essential-addons-for-elementor-lite' ),
 *       'post_types'  => [ 'page', 'post', 'product' ],  // optional override
 *   ] );
 *
 * @package Essential_Addons_Elementor\Controls
 * @since   6.5.14
 */

namespace Essential_Addons_Elementor\Controls;

use Elementor\Control_URL;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EAEL_URL
 */
class EAEL_URL extends Control_URL {

	// -------------------------------------------------------------------------
	// Identity
	// -------------------------------------------------------------------------

	/**
	 * Control type identifier registered with Elementor.
	 *
	 * Use `'eael-url'` as the `type` key when calling `add_control()`.
	 *
	 * @return string
	 */
	public function get_type() {
		return 'eael-url';
	}

	// -------------------------------------------------------------------------
	// Default settings
	// -------------------------------------------------------------------------

	/**
	 * Merge in EAEL-specific defaults on top of the URL control defaults.
	 *
	 * @return array
	 */
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(),
			[
				/**
				 * Post types surfaced in the AJAX search dropdown.
				 * 'product' is included only if WooCommerce is active (checked in JS).
				 */
				'post_types'  => [ 'page', 'post', 'product' ],

				/**
				 * Override the URL control placeholder so it reflects that the
				 * field can be populated via search OR typed manually.
				 */
				'placeholder' => esc_html__( 'Search or type a URL…', 'essential-addons-for-elementor-lite' ),
			]
		);
	}

	// -------------------------------------------------------------------------
	// Asset enqueueing (called by Elementor when this control type is rendered)
	// -------------------------------------------------------------------------

	/**
	 * Enqueue the AJAX search script.
	 *
	 * Elementor calls this method automatically the first time a control of
	 * this type is rendered in the editor panel, so the script is loaded only
	 * when the control is actually in use.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_register_script(
			'eael-url-search',
			EAEL_PLUGIN_URL . 'assets/admin/js/eael-url-search.js',
			[ 'jquery', 'jquery-elementor-select2' ],
			EAEL_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'eael-url-search',
			'eaelURLSearch',
			[
				'ajaxUrl'     => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'       => wp_create_nonce( 'eael_lr_redirect_search' ),
				'placeholder' => esc_html__( 'Type ≥ 3 characters to search…', 'essential-addons-for-elementor-lite' ),
				'minChars'    => 3,
			]
		);

		wp_enqueue_script( 'eael-url-search' );
	}

	// -------------------------------------------------------------------------
	// Editor template (Underscore.js / Backbone)
	// -------------------------------------------------------------------------

	/**
	 * Render the control HTML inside the Elementor panel.
	 *
	 * Adds an AJAX Select2 search dropdown above the standard URL text input.
	 * Selecting a result automatically populates the URL input so the value is
	 * persisted by Elementor's normal control save mechanism.  All original URL
	 * control options (is_external, nofollow, custom_attributes) are preserved.
	 *
	 * @return void
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field elementor-control-url-external-{{{ ( data.options && data.options.length ) ? 'show' : 'hide' }}}">
			<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>

			{{-- AJAX search wrapper; post_types is JSON-encoded so JS can read it --}}
			<div class="eael-url-search-wrap"
				data-post-types="{{{ JSON.stringify( data.post_types || [] ) }}}"
				style="margin-bottom:6px;">
				<select class="eael-url-ajax-search" style="width:100%;"></select>
				<p class="eael-url-search-hint"
				   style="margin:4px 0 0;font-size:11px;color:#808a97;">
					<?php echo esc_html__( 'Type ≥ 3 characters to search posts, pages and products — or type a URL directly below.', 'essential-addons-for-elementor-lite' ); ?>
				</p>
			</div>

			<div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
				<i class="elementor-control-url-autocomplete-spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				<input id="<?php $this->print_control_uid(); ?>"
				       class="elementor-control-tag-area elementor-input"
				       data-setting="url"
				       placeholder="{{ view.getControlPlaceholder() }}" />
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input id="_ajax_linking_nonce" type="hidden" value="<?php echo wp_create_nonce( 'internal-linking' ); ?>">
				<# if ( data.options && data.options.length ) { #>
				<button class="elementor-control-url-more tooltip-target elementor-control-unit-1"
				        data-tooltip="<?php echo esc_attr__( 'Link Options', 'elementor' ); ?>"
				        aria-label="<?php echo esc_attr__( 'Link Options', 'elementor' ); ?>">
					<i class="eicon-cog" aria-hidden="true"></i>
				</button>
				<# } #>
			</div>

			<# if ( data.options && data.options.length ) { #>
			<div class="elementor-control-url-more-options">
				<div class="elementor-control-url-option">
					<input id="<?php $this->print_control_uid( 'is_external' ); ?>"
					       type="checkbox"
					       class="elementor-control-url-option-input"
					       data-setting="is_external">
					<label for="<?php $this->print_control_uid( 'is_external' ); ?>">
						<?php echo esc_html__( 'Open in new window', 'elementor' ); ?>
					</label>
				</div>
				<div class="elementor-control-url-option">
					<input id="<?php $this->print_control_uid( 'nofollow' ); ?>"
					       type="checkbox"
					       class="elementor-control-url-option-input"
					       data-setting="nofollow">
					<label for="<?php $this->print_control_uid( 'nofollow' ); ?>">
						<?php echo esc_html__( 'Add nofollow', 'elementor' ); ?>
					</label>
				</div>
				<div class="elementor-control-url__custom-attributes elementor-control-direction-ltr">
					<label for="<?php $this->print_control_uid( 'custom_attributes' ); ?>"
					       class="elementor-control-url__custom-attributes-label">
						<?php echo esc_html__( 'Custom Attributes', 'elementor' ); ?>
					</label>
					<input type="text"
					       id="<?php $this->print_control_uid( 'custom_attributes' ); ?>"
					       class="elementor-control-unit-5"
					       placeholder="key|value"
					       data-setting="custom_attributes">
				</div>
				<# if ( data.options && -1 !== data.options.indexOf( 'custom_attributes' ) && data.custom_attributes_description ) { #>
				<div class="elementor-control-field-description">{{{ data.custom_attributes_description }}}</div>
				<# } #>
			</div>
			<# } #>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>

		<# /* Trigger JS initialisation for this specific control instance. */
		( function( $ ) {
			$( document.body ).trigger( 'eael_url_search_init' );
		}( jQuery ) );
		#>
		<?php
	}
}

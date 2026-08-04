<?php
/**
 * Theme Builder dashboard modals.
 *
 * Step 1 collects the template type and name, step 2 the display conditions.
 * Both are printed once per screen and driven by assets/admin/js/theme-builder.js.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<div class="eael-tb-modal" id="eael-tb-modal-create" aria-hidden="true">
	<div class="eael-tb-modal__overlay" data-eael-tb-close></div>

	<div class="eael-tb-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="eael-tb-create-title">
		<button type="button" class="eael-tb-modal__close" data-eael-tb-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'essential-addons-for-elementor-lite' ); ?></span>
			<span aria-hidden="true">&times;</span>
		</button>

		<h2 class="eael-tb-modal__title" id="eael-tb-create-title">
			<?php esc_html_e( 'Choose the type of template you want to work on', 'essential-addons-for-elementor-lite' ); ?>
		</h2>

		<form class="eael-tb-modal__body" id="eael-tb-create-form">
			<div class="eael-tb-field">
				<label for="eael-tb-template-type"><?php esc_html_e( 'Template Type', 'essential-addons-for-elementor-lite' ); ?></label>
				<select id="eael-tb-template-type" name="template_type" required>
					<option value=""><?php esc_html_e( 'Select', 'essential-addons-for-elementor-lite' ); ?></option>
					<?php foreach ( Template_Types::instance()->get_types() as $slug => $type ) : ?>
						<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $type['label'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="eael-tb-field">
				<label for="eael-tb-template-title"><?php esc_html_e( 'Template Name', 'essential-addons-for-elementor-lite' ); ?></label>
				<input type="text"
					id="eael-tb-template-title"
					name="template_title"
					placeholder="<?php esc_attr_e( 'Name your template', 'essential-addons-for-elementor-lite' ); ?>" />
			</div>

			<p class="eael-tb-modal__error" role="alert"></p>

			<div class="eael-tb-modal__footer">
				<button type="submit" class="button button-primary button-hero eael-tb-submit">
					<?php esc_html_e( 'Create Template', 'essential-addons-for-elementor-lite' ); ?>
				</button>
			</div>
		</form>
	</div>
</div>

<div class="eael-tb-modal eael-tb-modal--wide" id="eael-tb-modal-conditions" aria-hidden="true">
	<div class="eael-tb-modal__overlay" data-eael-tb-close></div>

	<div class="eael-tb-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="eael-tb-conditions-title">
		<button type="button" class="eael-tb-modal__close" data-eael-tb-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'essential-addons-for-elementor-lite' ); ?></span>
			<span aria-hidden="true">&times;</span>
		</button>

		<h2 class="eael-tb-modal__title" id="eael-tb-conditions-title">
			<?php esc_html_e( 'Where Do You Want to Display This Template', 'essential-addons-for-elementor-lite' ); ?>
		</h2>

		<p class="eael-tb-modal__subtitle">
			<?php esc_html_e( "Set the conditions that determine where your Template is used throughout your site. For example, choose 'Entire Site' to display the template across your site.", 'essential-addons-for-elementor-lite' ); ?>
		</p>

		<form class="eael-tb-modal__body" id="eael-tb-conditions-form">
			<input type="hidden" name="template_id" value="0" />

			<div class="eael-tb-conditions" id="eael-tb-conditions-list"></div>

			<p class="eael-tb-conditions__add">
				<button type="button" class="button eael-tb-add-condition">
					<?php esc_html_e( 'Add Condition', 'essential-addons-for-elementor-lite' ); ?>
				</button>
			</p>

			<p class="eael-tb-modal__error" role="alert"></p>
			<p class="eael-tb-modal__warning" role="status"></p>

			<div class="eael-tb-modal__footer">
				<button type="submit" class="button button-primary button-hero eael-tb-submit">
					<?php esc_html_e( 'Save & Close', 'essential-addons-for-elementor-lite' ); ?>
				</button>
			</div>
		</form>
	</div>
</div>

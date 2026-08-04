<?php
/**
 * Theme Builder dashboard screen.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 *
 * @var \Essential_Addons_Elementor\Theme_Builder\Admin\Templates_List_Table|null $list_table Prepared list table.
 * @var string                                                                            $notice     Result of the last action.
 */

use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$current_type   = $list_table ? $list_table->get_request_type() : '';
$current_status = $list_table ? $list_table->get_request_status() : '';
$elementor_ok   = Theme_Builder::is_enabled();

?>
<div class="wrap eael-tb-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Templates', 'essential-addons-for-elementor-lite' ); ?></h1>

	<?php if ( $elementor_ok ) : ?>
		<button type="button" class="page-title-action eael-tb-add-new">
			<?php esc_html_e( 'Add New Template', 'essential-addons-for-elementor-lite' ); ?>
		</button>
	<?php endif; ?>

	<hr class="wp-header-end">

	<?php if ( ! $elementor_ok ) : ?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'Theme Builder needs Elementor to be installed and activated.', 'essential-addons-for-elementor-lite' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $notice ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo esc_html( $notice ); ?></p>
		</div>
	<?php endif; ?>

	<div class="notice notice-error is-dismissible eael-tb-error-notice" style="display:none;">
		<p></p>
	</div>

	<nav class="nav-tab-wrapper eael-tb-tabs wp-clearfix">
		<a href="<?php echo esc_url( Theme_Builder::page_url() ); ?>"
			class="nav-tab <?php echo '' === $current_type ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'All', 'essential-addons-for-elementor-lite' ); ?>
		</a>
		<?php foreach ( Template_Types::instance()->get_types() as $slug => $type ) : ?>
			<a href="<?php echo esc_url( Theme_Builder::page_url( [ 'template_type' => $slug ] ) ); ?>"
				class="nav-tab <?php echo $current_type === $slug ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_html( $type['label'] ); ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<?php if ( $list_table ) : ?>
		<?php $list_table->views(); ?>

		<form id="eael-tb-templates-filter" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( Theme_Builder::page_slug() ); ?>" />
			<?php if ( $current_status ) : ?>
				<input type="hidden" name="post_status" value="<?php echo esc_attr( $current_status ); ?>" />
			<?php endif; ?>

			<?php
			$list_table->search_box( __( 'Search Template', 'essential-addons-for-elementor-lite' ), 'eael-tb-template' );
			$list_table->display();
			?>
		</form>
	<?php endif; ?>

	<?php
	/*
	 * Mount point for the Theme Builder React app — it renders the "Add New
	 * Template" and "Display Conditions" modals, and hooks the buttons above
	 * through delegated listeners. See includes/templates/admin/theme-builder/.
	 */
	?>
	<div id="eael-theme-builder-app"></div>
</div>
